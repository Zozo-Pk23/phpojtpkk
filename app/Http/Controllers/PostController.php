<?php

namespace App\Http\Controllers;

use App\Exports\PostExport;
use App\Http\Services\PostService;
use App\Imports\PostImport;
use App\Models\Post;
use App\Models\User;
use Exception;
use Illuminate\Database\Eloquent\SoftDeletes;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Auth;
use Illuminate\Support\Facades\DB;
use Illuminate\Support\Facades\Schema;
use Maatwebsite\Excel\Facades\Excel;

class PostController extends Controller
{
    private $postService;
    use SoftDeletes;
    public function __construct(PostService $postService)
    {
        $this->postService = $postService;
    }
    /**
     * Get Post from database
     * 
     * @return $posts,$type
     */
    public function index()
    {
        $loginUser = Auth::user()->type;
        //dd($loginUser);
        $loginUserId = Auth::user()->id;
        if ($loginUser == 1) {
            $posts = DB::table('posts')
                ->select('posts.id', 'posts.title', 'posts.description', 'users.name As pname', 'uone.name As uname', 'posts.created_at', 'posts.updated_at', 'posts.status',)
                ->leftJoin('users', 'users.id', '=', 'posts.created_user_id')
                ->rightJoin('users as uone', 'uone.id', '=', 'posts.updated_user_id')
                ->where('posts.deleted_at', '=', NULL)
                ->where('posts.created_user_id', '=', $loginUserId)
                ->paginate(10);

            return view('home', ['posts' => $posts, 'type' => $loginUser]);
        } else {
            $posts = DB::table('posts')
                ->select('posts.id', 'posts.title', 'posts.description', 'users.name As pname', 'uone.name As uname', 'posts.created_at', 'posts.updated_at', 'posts.status')
                ->join('users', 'users.id', '=', 'posts.created_user_id')
                ->join('users as uone', 'uone.id', '=', 'posts.updated_user_id')
                ->where('posts.deleted_at', '=', NULL)
                ->paginate(50);
            // dd($posts);
            return view('home', ['posts' => $posts, 'type' => $loginUser]);
        }
    }
    /**
     * search post
     * 
     * @param Request $request
     * 
     * @return $posts,$type
     * 
     */
    public function search(Request $request)
    {
        $loginUser = Auth::user()->type;
        $posts = $this->postService->search($request);
        return view('home', ['posts' => $posts, 'type' => $loginUser]);
    }
    /**
     * Save post
     * 
     * @param Request $request
     * 
     * @return $post
     */
    public function confirm(Request $request)
    {
        $validated = $request->validate([
            'title' => 'required|unique:posts,title|max:255',
            'des' =>  'required|max:255',
        ]);
        return view('posts/confirmpost', ['post' => $request]);
    }
    /**
     * Save Post to database
     * 
     * @param Request $request
     * 
     */
    public function save(Request $request)
    {
        $this->postService->create($request->all());
        return redirect()->route('home');
    }
    /**
     * Go to update page
     * 
     * @param Request $request
     * 
     * @return $post
     */
    public function edit($id)
    {
        $post = $this->postService->edit($id);
        return view('posts.updatepost', ['post' => $post]);
    }
    /**
     * Show data in update page
     * 
     * @param Request $request,$id
     * 
     * @return $post
     */
    public function updateblade($id, Request $request)
    {
        $validated = $request->validate([
            'des' =>  'required',
            'title' => [
                'required',
                'unique:posts,title,'.$id,
                'max:255'
            ]
        ]);

        return view('posts.confirmupdate', ['post' => $request]);
    }
    /**
     * Update input in database
     * 
     * @param $id,Request $request
     * 
     */
    public function update($id, Request $request)
    {
        $this->postService->update($id, $request);
        return redirect()->route('home');
    }
    /**
     * Delete Post
     * 
     * @param $id
     * 
     */
    public function delete($id)
    {
        $this->postService->delete($id);
        return redirect()->route('home');
    }
    /**
     * Download as csv
     * 
     * @param Request $request
     * 
     */
    public function download(Request $request)
    {
        return Excel::download(new PostExport, 'post.csv');
    }
    /**
     * Import csv to database
     * 
     * @param Request $request
     * 
     */
    public function upload(Request $request)
    {
        $validated = $request->validate([
            'file' => 'required|mimes:csv|max:2080'
        ]);
        $file = $request->file('file');
        Excel::import(new PostImport, $file);
        return redirect()->route('home');
    }





    //User




    /**
     * Show post details
     * 
     * @param $id
     * 
     * @return $user
     */
    public function profile($id)
    {
        $users = $this->postService->profile($id);
        return view('posts.myprofile', ['user' => $users]);
    }
    /**
     * Show user detail
     * 
     * @param $id
     * 
     * @return $user
     * 
     */
    public function editProfile($id)
    {
        $users = $this->postService->profile($id);

        return view('posts.userupdateform', ['user' => $users]);
    }
    /**
     * Validate Update Form
     * 
     * @param Request $request
     * 
     * @return $user,$fname
     * 
     */
    public function confirmProfile(Request $request)
    {
        $validated = $request->validate([
            'name' => 'required',
            'email' => 'required|email:rfc',
            'phone' => 'required',
        ]);
        if ($request->hasFile('profile')) {
            $file = $request->file('profile');
            $fname = $file->getClientOriginalName();
            $file->move("images", $fname);
        } else {
            $fname = $request->oldprofile;
        }
        return view('posts.userupdateconfirm', ['user' => $request, 'fname' => $fname]);
    }
    /**
     * Final update user to database
     * 
     * @param $id,Request $request
     * 
     */
    public function updateUser($id, Request $request)
    {
        $post = $this->postService->updateProfile($id, $request);
        return redirect()->route('home');
    }
}
