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
        $user =  Post::create(['title' => $request['title'], 'description' => $request['des'], "created_user_id" => $id, "updated_user_id" => $id]);
        return $user;
    }
    public function search($request)
    {
        $id = Auth::user()->id;
        $search = DB::table('posts')
            ->select('posts.id', 'posts.title', 'posts.description', 'users.name As pname', 'uone.name As uname', 'posts.created_at', 'posts.status', 'posts.updated_at',)
            ->join('users', 'users.id', '=', 'posts.created_user_id')
            ->where('posts.title', 'LIKE', '%' . $request->searchitem . '%')
            ->rightJoin('users as uone', 'uone.id', '=', 'posts.updated_user_id')
            ->orWhere('posts.description', 'LIKE', '%' . $request->searchitem . '%')
            ->paginate(10);

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
        $post = Post::where('id', $id)->update(['title' => $request->title, 'description' => $request->des, 'updated_user_id' => $updated_user_id, 'status' => $request->status]);
        return $post;
    }
    public function delete($id)
    {
        $post = Post::where('id', $id)->delete();
        return $post;
    }
}
