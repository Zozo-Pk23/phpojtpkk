<?php

namespace App\Http\Dao;

use App\Contracts\Dao\postDaoInterface;
use App\Models\Post;
use App\Models\User;
use Illuminate\Support\Facades\Auth;
use Illuminate\Support\Facades\DB;

class PostDao implements postDaoInterface
{
    public function create($request)
    {
        $id = Auth::user()->id;
        //dd($id);
        $user =  Post::create(['title' => $request['title'], 'description' => $request['des'], "created_user_id" => $id]);
        return $user;
    }
    public function search($request)
    {
        $id = Auth::user()->id;
        $search = DB::table('posts')
            ->select('posts.id', 'posts.title', 'posts.description', 'users.name As pname', 'posts.created_at')
            ->join('users', 'created_user_id', '=', 'users.id')
            ->where('posts.title', 'LIKE', '%' . $request->searchitem . '%')
            ->orWhere('posts.description', 'LIKE', '%' . $request->searchitem . '%')
            ->get();

        //dd($search);
        return $search;
    }
    public function edit($id)
    {
        $users = Post::where('id', $id)->first();
        return $users;
    }
    public function update($id, $request)
    {
        $updated_user_id = Auth::user()->id;
        $post = Post::where('id', $id)->update(['title' => $request->title, 'description' => $request->des, 'updated_user_id' => $updated_user_id]);
        return $post;
    }
}