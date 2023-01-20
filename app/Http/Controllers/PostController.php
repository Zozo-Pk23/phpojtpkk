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
use Illuminate\Validation\Rule;
use Maatwebsite\Excel\Facades\Excel;
use Maatwebsite\Excel\Validators\Failure;

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
        $posts = $this->postService->index();
        return view('home', ['posts' => $posts, 'type' => $loginUser]);
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
            'title' => [
                'required',
                Rule::unique('posts', 'title')->ignore(1, 'posts.delete_flag'),
                'max:255',
            ],
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
                Rule::unique('posts', 'title')->ignore(1, 'posts.delete_flag'),
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
        if ($request->file('file')) {
            try {
                Excel::import(new PostImport, $file);
                return redirect('home');
            } catch (\Maatwebsite\Excel\Validators\ValidationException $e) {
                $messages = $e->failures();
                return redirect()->back()->with('messages', $messages);
            }
        }
    }
}
