<?php

namespace App\Http\Controllers;

use App\Http\Services\PostService;
use App\Models\User;
use Illuminate\Database\Eloquent\SoftDeletes;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\DB;
use Illuminate\Support\Facades\Schema;

class PostController extends Controller
{
    private $postService;
    use SoftDeletes;
    public function __construct(PostService $postService)
    {
        $this->postService = $postService;
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

        //dd($posts);
        return view('home', ['posts' => $posts]);
    }
    public function index()
    {
        $posts = DB::table('posts')
            ->select('posts.id', 'posts.title', 'posts.description', 'users.name As pname', 'posts.created_at', 'posts.updated_at', 'posts.status')
            ->join('users', 'users.id', '=', 'posts.created_user_id')
            ->where('posts.deleted_at', '=', NULL)
            ->paginate(10);
        //dd($posts);
        //$username = User::where('id', '=', $posts.created_user_id)->first();
        //dd($posts['created_user_id']);
        return view('home', ['posts' => $posts]);
    }
    public function edit($id)
    {
        $post = $this->postService->edit($id);
        return view('posts.updatepost', ['post' => $post]);
    }
    public function update($id, Request $request)
    {
        //dd($id);
        $this->postService->update($id, $request);
        return redirect()->route('home');
    }
    public function updateblade($id, Request $request)
    {
        //dd($request);
        $validated = $request->validate([
            'title' => 'required|unique:posts,title|max:255',
            'des' =>  'required',
        ]);
        return view('posts.confirmupdate', ['post' => $request]);
    }
    public function delete($id)
    {
        //dd($id);
        $this->postService->delete($id);
        return redirect()->route('home');
    }
    public function profile($id)
    {
        $users = $this->postService->profile($id);
        return view('posts.myprofile', ['user' => $users]);
    }
}
