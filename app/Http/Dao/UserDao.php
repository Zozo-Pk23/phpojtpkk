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
            'password' =>Hash::make($request['password']),
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
    public function searchuser($request)
    {
        $search = DB::table('users')
            ->select('users.id', 'users.name', 'users.email', 'u2.name As pname', 'users.phone', 'users.date_of_birth', 'users.address', 'users.created_at', 'users.updated_at')
            ->join('users As u2', 'u2.id', '=', 'users.created_user_id')
            //->where('users.deleted_at', '=', NULL)
            ->orWhere('users.name', 'LIKE', '%' . $request->searchitem . '%')
            ->orWhere('users.email', 'LIKE', '%' . $request->searchitem . '%')
            ->orWhereBetween('users.date_of_birth', [$request->createdfrom, $request->createdto])
            ->paginate(7);
        return $search;
    }
}
