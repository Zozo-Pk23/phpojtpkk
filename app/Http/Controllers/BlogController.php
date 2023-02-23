<?php

namespace App\Http\Controllers;

use App\Models\Post;
use Illuminate\Http\Request;
use Illuminate\Validation\Rule;
use App\Http\Services\PostService;
use App\Imports\PostImport;
use Illuminate\Support\Facades\Auth;
use Illuminate\Support\Facades\DB;
use Illuminate\Support\Facades\Response;
use Illuminate\Support\Facades\Validator;
use Maatwebsite\Excel\Facades\Excel;

class BlogController extends Controller
{
    private $postService;
    /**
     * Display a listing of the resource.
     *
     * @return \Illuminate\Http\Response
     */
    public function index(Request $request)
    {
        $loginUser = Auth::user()->type;
        $loginUserId = Auth::user()->id;
        $posts = DB::table('posts')
            ->select('posts.id', 'posts.title', 'posts.description', 'users.name As pname', 'uone.name As uname', 'posts.created_at', 'posts.status', 'posts.updated_at',)
            ->leftjoin('users', 'users.id', '=', 'posts.created_user_id')
            ->rightJoin('users as uone', 'uone.id', '=', 'posts.updated_user_id')
            ->where('posts.delete_flag', '=', '0')
            ->where(function ($query) {
                $searchitem = request()->input('searchitem');
                $query->where("title", "LIKE", "%{$searchitem}%")->orWhere("description", "LIKE", "%{$searchitem}%");
            })
            ->when($loginUser == 0, function ($query) use ($loginUserId) {
                $query->where('posts.created_user_id', $loginUserId);
            })
            ->get();
        return response()->json($posts);
        //
    }

    /**
     * Show the form for creating a new resource.
     *
     * @return \Illuminate\Http\Response
     */
    public function create()
    {
        $posts = DB::table('posts')
            ->select('posts.id', 'posts.title', 'posts.description', 'users.name As pname', 'uone.name As uname', 'posts.created_at', 'posts.status', 'posts.updated_at', 'posts.delete_flag')
            ->leftjoin('users', 'users.id', '=', 'posts.created_user_id')
            ->rightJoin('users as uone', 'uone.id', '=', 'posts.updated_user_id')
            ->where('posts.delete_flag', 0)
            ->get();
        return response()->json($posts);
    }

    /**
     * Store a newly created resource in storage.
     *
     * @param  \Illuminate\Http\Request  $request
     * @return \Illuminate\Http\Response
     */
    public function store(Request $request)
    {
        $id = Auth::user()->id;
        Post::create(['title' => $request['data']['title'], 'description' => $request['data']['description'], 'created_user_id' => $id, 'updated_user_id' => $id]);
    }

    public function confirmstore(Request $request)
    {
        // $this->postService->create($request->all());
        // return redirect()->route('home');
    }

    /**
     * Display the specified resource.
     *
     * @param  int  $id
     * @return \Illuminate\Http\Response
     */
    public function show(Post $post)
    {
        return response()->json(['post' => $post]);
    }

    /**
     * Show the form for editing the specified resource.
     *
     * @param  int  $id
     * @return \Illuminate\Http\Response
     */
    public function edit(Request $request)
    {
        $id = $request->id['id'];
        $data = $request->id;
        $validated = Validator::make(
            $request['id'],
            [
                'description' =>  'required',
                'title' => [
                    'required',
                    Rule::unique('posts')->ignore($id)->where('posts.delete_flag', 0),
                    'max:255'
                ]
            ]
        );
        if ($validated->fails()) {
            return response()->json(['message' => $validated->errors()], 400);
        } else {
            return response()->json(['success' => true, 'message' => 'Ok']);
        }
    }

    /**
     * Update the specified resource in storage.
     *
     * @param  \Illuminate\Http\Request  $request
     * @param  int  $id
     * @return \Illuminate\Http\Response
     */
    public function update(Request $request)
    {
        $id = Auth::user()->id;
        $post = $request['id'];
        return Post::where('id', $post['id'])->update(['title' => $post['title'], 'description' => $post['description'], 'updated_user_id' => $id, 'status' => $post['status']]);
    }

    /**
     * Remove the specified resource from storage.
     *
     * @param  int  $id
     * @return \Illuminate\Http\Response
     */
    public function destroy(Request $request)
    {
        return Post::where('id', $request['id'])->update([
            'delete_flag' => 1
        ]);
    }
    public function download()
    {
        $posts = DB::table('posts')
            ->select('posts.id', 'posts.title', 'posts.description', 'users.name As pname', 'uone.name As uname', 'posts.created_at', 'posts.status', 'posts.updated_at', 'posts.delete_flag')
            ->leftjoin('users', 'users.id', '=', 'posts.created_user_id')
            ->rightJoin('users as uone', 'uone.id', '=', 'posts.updated_user_id')
            ->where('posts.delete_flag', 0)
            ->get();
        return response()->json($posts);
    }
    public function upload(Request $request)
    {
        $validator =  Validator::make($request['file'], [
            'name' => 'required',
            'type' =>  ['required', Rule::in(['text/csv', 'text/comma-separated-values'])],
        ]);
        if ($validator->fails()) {
            return response()->json([
                'message' => $validator->errors()
            ]);
        }
        $file = base64_decode($request->uri);
        $tempFile = tempnam(sys_get_temp_dir(), 'temp_file_');
        file_put_contents($tempFile, $file);
        if ($tempFile) {
            try {
                Excel::import(new PostImport, $tempFile);
                return response()->json(['success' => true, 'message' => 'uploaded succesfully']);
            } catch (\Maatwebsite\Excel\Validators\ValidationException $e) {
                $messages = $e->failures();
                return response()->json(['message', $messages]);
            }
        }
    }
    public function something(Request $request)
    {
        $validated = Validator::make(
            $request['title'],
            [
                'title' => [
                    'required',
                    Rule::unique('posts', 'title')->ignore(1, 'posts.delete_flag'),
                    'max:255',
                ],
                'description' =>  'required|max:255',
            ]
        );
        if ($validated->fails()) {
            return response()->json(['message' => $validated->errors()], 400);
        } else {
            return response()->json(['success' => true, 'message' => 'Data has been saved']);
        }
    }
}
