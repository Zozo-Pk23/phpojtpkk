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

    public function profile($id)
    {
        $user = User::where('id', $id)->first();
        return $user;
    }
    public function updateProfile($id, $request)
    {
        // sdd($request);
        $user = User::where('id', $id)->update([
            'name' => $request->name,
            'email' => $request->email,
            'type' => $request->type,
            'phone' => $request->phone,
            'date_of_birth' => $request->date,
            'address' => $request->address,
            'profile' => $request->profile,
        ]);
        return $user;
    }
    public function searchuser($request)
    {
        $startdate = $request->createdfrom;
        $enddate = $request->createdto;
        $users = DB::table('users')
            ->select('users.id', 'users.name', 'users.email', 'u2.name As pname', 'users.phone', 'users.date_of_birth', 'users.address', 'users.created_at', 'users.updated_at', 'users.profile')
            ->join('users As u2', 'u2.id', '=', 'users.created_user_id')
            ->where('users.deleted_at', '=', NULL)
            ->when($request->createdfrom, function ($query) use ($request) {
                $query->whereDate('users.created_at', '>=', $request->createdfrom);
            })
            ->when($request->createdto, function ($query) use ($request) {
                $query->whereDate('users.created_at', '<=', $request->createdto);
            })
            ->when($request->searchemail, function ($query) use ($request) {
                $query->where('users.email', 'LIKE', "%" . $request->searchemail . "%");
            })
            ->when($request->searchname, function ($query) use ($request) {
                $query->where('users.name', 'LIKE', "%" . $request->searchname . "%");
            })
            ->paginate(7);
        return $users;
    }
}
