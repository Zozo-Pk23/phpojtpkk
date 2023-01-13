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
    public function download(Request $request)
    {
        return Excel::download(new PostExport, 'user.csv');
    }
    public function upload(Request $request)
    {
        $validated = $request->validate([
            'file' => 'required|mimes:csv|max:2080'
        ]);

        if ($request->hasFile('file')) {
            $file = $request->file('file');
        }
        Excel::import(new PostImport, $file);
        if ('error') {
            return redirect()->back();
        } else {
            $fname = $file->getClientOriginalName();
            $file->move("fies", $fname);
            return redirect()->route('home');
        }
    }
    public function save(Request $request)
    {
        $this->postService->create($request->all());
        return redirect()->route('home');
    }
    public function confirm(Request $request)
    {
        $validated = $request->validate([
            'title' => 'required|unique:posts,title|max:255',
            'des' =>  'required',
        ]);
        return view('posts/confirmpost', ['post' => $request]);
    }
    public function search(Request $request)
    {
        $posts = $this->postService->search($request);
        return view('home', ['posts' => $posts]);
    }
    public function index()
    {
        $loginUser = Auth::user()->type;
        $loginUserId = Auth::user()->id;
        if ($loginUser == 1) {
            $posts = DB::table('posts')
                ->select('posts.id', 'posts.title', 'posts.description', 'users.name As pname', 'posts.created_at', 'posts.updated_at', 'posts.status')
                ->join('users', 'users.id', '=', 'posts.created_user_id')
                //->where('posts.deleted_at', '=', NULL)
                ->where('posts.created_user_id', '=', $loginUserId)
                ->paginate(10);

            return view('home', ['posts' => $posts]);
        } else {
            $posts = DB::table('posts')
                ->select('posts.id', 'posts.title', 'posts.description', 'users.name As pname', 'posts.created_at', 'posts.updated_at', 'posts.status')
                ->join('users', 'users.id', '=', 'posts.created_user_id')
                ->where('posts.deleted_at', '=', NULL)
                ->paginate(10);
            return view('home', ['posts' => $posts]);
        }
    }
    public function edit($id)
    {
        $post = $this->postService->edit($id);
        return view('posts.updatepost', ['post' => $post]);
    }
    public function update($id, Request $request)
    {
        $this->postService->update($id, $request);
        return redirect()->route('home');
    }
    public function updateblade($id, Request $request)
    {
        $validated = $request->validate([
            'title' => 'required|unique:posts,title|max:255',
            'des' =>  'required',
        ]);
        return view('posts.confirmupdate', ['post' => $request]);
    }
    public function delete($id)
    {
        $this->postService->delete($id);
        return redirect()->route('home');
    }
    public function profile($id)
    {
        $users = $this->postService->profile($id);
        return view('posts.myprofile', ['user' => $users]);
    }
    public function editProfile($id)
    {
        $users = $this->postService->profile($id);

        return view('posts.userupdateform', ['user' => $users]);
    }
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
    public function updateUser($id, Request $request)
    {
        $post = $this->postService->updateProfile($id, $request);
        return redirect()->route('home');
    }
}
