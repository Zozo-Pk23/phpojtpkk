<?php

namespace App\Http\Controllers;

use App\Http\Services\PostService;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\DB;

class PostController extends Controller
{
    private $postService;
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
            ->select('posts.id', 'posts.title', 'posts.description', 'users.name As pname', 'posts.created_at')
            ->join('users', 'created_user_id', '=', 'users.id')
            ->get();
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
    public function
}
