<?php

namespace App\Http\Dao;

use App\Contracts\Dao\userDaoInterface;
use App\Models\User;
use Illuminate\Support\Facades\Auth;
use Illuminate\Support\Facades\DB;
use Illuminate\Support\Facades\Hash;

class UserDao implements userDaoInterface
{
    public function save($request)
    {
        $id = Auth::user()->id;
        $user = User::create([
            'name' => $request['name'],
            'email' => $request['email'],
            'password' => Hash::make($request['password']),
            'profile' => $request['profile'],
            'type' => $request['type'],
            'phone' => $request['phone'],
            'address' => $request['address'],
            'date_of_birth' => $request['date'],
            'created_user_id' => $id,
            'updated_user_id' => $id,
        ]);
        return $user;
    }
    public function deleteuser($id)
    {
        $user = User::where('id', $id)->delete();
        return $user;
    }
    public function changepasswordscreen($id)
    {
        $pass = User::where('id', $id)->first();
        return $pass;
    }
    public function updatepassword($id, $request)
    {
        $user = User::where('id', $id)->update([
            'password' => Hash::make($request->newpassword),
        ]);
        return $user;
    }
    // public function searchuser($request)
    // {
    //     $users = \DB::table('users');
    //     if ($request->searchname) {
    //         $users = $users->where('name', 'LIKE', "%" . $request->searchname . "%");
    //     }
    //     if ($request->searchemail) {
    //         $users = $users->where('email', 'LIKE', "%" . $request->searchemail . "%");
    //     }
    //     if ($request->min_age && $request->max_age) {
    //         $users = $users->where('age', '>=', $request->min_age)
    //             ->where('age', '<=', $request->max_age);
    //     }
    //     dd($users);
    //     $users = $users->paginate(10);
    //     return "Hello";
    //     return view('users.users', ['users' => $users]);
    // }
    // public function searchuser($request)
    // {
    //     $loginUser = Auth::user()->id;
    //     $users = DB::table('users')
    //         ->select('users.id', 'users.name', 'users.email', 'u2.name As pname', 'users.phone', 'users.date_of_birth', 'users.address', 'users.created_at', 'users.updated_at', 'users.profile')
    //         ->join('users As u2', 'u2.id', '=', 'users.created_user_id')
    //         ->where([
    //             ['users.id', '!=', $loginUser],
    //             ['users.deleted_at', '=', NULL],
    //             ['users.name']
    //         ])
    //         ->paginate(7);
    //     return $users;
    // }
    public function searchuser($request)
    {
        $from=$request->createdfrom;
        $user = User::when(($from && $request->createdto), function ($query) {
            $query->whereBetween('created_at', [$from, $request->createdto]);
        })->get();
        return $user;
    }
}
