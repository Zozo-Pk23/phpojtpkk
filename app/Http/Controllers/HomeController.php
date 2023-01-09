<?php

namespace App\Http\Controllers;

use App\Models\Post;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\DB;

class HomeController extends Controller
{
    /**
     * Create a new controller instance.
     *
     * @return void
     */
    public function __construct()
    {
        $this->middleware('auth');
    }

    /**
     * Show the application dashboard.
     *
     * @return \Illuminate\Contracts\Support\Renderable
     */
    public function index()
    {
        $posts = DB::table('posts')
            ->select('posts.id', 'posts.title', 'posts.description', 'users.name As pname', 'posts.created_at')
            ->join('users', 'created_user_id', '=', 'users.id')
            ->get();
        return view('home', ['post' => $posts]);
    }
}
