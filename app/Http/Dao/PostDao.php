<?php

namespace App\Http\Dao;

use App\Contracts\Dao\postDaoInterface;
use App\Models\Post;
use Illuminate\Support\Facades\Auth;
use Illuminate\Support\Facades\DB;

class PostDao implements postDaoInterface
{
    public function index()
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
            ->when($loginUser == 1, function ($query) use ($loginUserId) {
                $query->where('posts.created_user_id', $loginUserId);
            })
            ->paginate(10);
        return $posts;



        // if ($loginUser == 1) {
        //     $posts = DB::table('posts')->where('posts.delete_flag', 0)->where('posts.created_user_id', $loginUserId)->where(function ($qry) {
        //         $searchitem = request()->input('searchitem');
        //         $qry->where("title", "LIKE", "%{$searchitem}%")->orWhere("description", "LIKE", "%{$searchitem}%");
        //     })->paginate(10);
        //     return $posts;
        // } else if ($loginUser == 0) {
        // $posts = DB::table('posts')
        //     ->select('posts.id', 'posts.title', 'posts.description', 'users.name As pname', 'uone.name As uname', 'posts.created_at', 'posts.status', 'posts.updated_at',)
        // ->leftjoin('users', 'users.id', '=', 'posts.created_user_id')
        // ->rightJoin('users as uone', 'uone.id', '=', 'posts.updated_user_id')
        //  ->where('posts.delete_flag', '=', '0')->where(function ($query) {
        //             $searchitem = request()->input('searchitem');
        //             $query->where("title", "LIKE", "%{$searchitem}%")->orWhere("description", "LIKE", "%{$searchitem}%");
        //         })
        //         ->paginate(10);
        //     return $posts;
        // }
    }
    public function create($request)
    {
        $id = Auth::user()->id;
        $user =  Post::create(['title' => $request['title'], 'description' => $request['des'], "created_user_id" => $id, "updated_user_id" => $id]);
        return $user;
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
        $post = Post::where('id', $id)->update([
            'delete_flag' => 1
        ]);
        return $post;
    }
}
