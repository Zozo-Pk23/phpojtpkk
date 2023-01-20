<?php

namespace App\Http\Dao;

use App\Contracts\Dao\userDaoInterface;
use App\Models\User;
use Illuminate\Support\Facades\Auth;
use Illuminate\Support\Facades\DB;
use Illuminate\Support\Facades\Hash;

class UserDao implements userDaoInterface
{
    public function index()
    {

        $users = DB::table('users')->select('users.id', 'users.name', 'users.email', 'u2.name As pname', 'users.phone', 'users.date_of_birth', 'users.address', 'users.created_at', 'users.updated_at', 'users.profile')
            ->join('users As u2', 'u2.id', '=', 'users.created_user_id')
            ->where('users.delete_flag', 0)
            ->where(function ($query) {
                $name = request()->input('searchname');
                $email = request()->input('searchemail');
                $startdate = request()->input('createdfrom');
                $enddate = request()->input('createdto');
                $query
                    ->when($name, function ($qry) use ($name) {
                        $qry->where('users.name', 'LIKE', "%" . $name . " %");
                    })
                    ->when($startdate, function ($qry) use ($startdate) {
                        $qry->whereDate('users.created_at', '>=', $startdate);
                    })
                    ->when($enddate, function ($query) use ($enddate) {
                        $query->whereDate('users.created_at', '<=', $enddate);
                    })
                    ->when($email, function ($query) use ($email) {
                        $query->where('users.email', 'LIKE', "%" . $email . "%");
                    });
            })
            ->paginate(7);
        return $users;
    }
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
        $user = User::where('id', $id)->update([
            'delete_flag' => 1
        ]);
        //$user = User::where('id', $id)->delete();
        return $user;
    }
    public function updatepassword($id, $request)
    {
        $old = User::where('id', $id)->first();
        $typepass = $request->oldpassword;
        if (Hash::check($typepass, $old->password)) {
            $user = User::where('id', $id)->update([
                'password' => Hash::make($request->newpassword),
            ]);
            return $user;
        } else {
            return redirect()->back()->withErrors(['msg' => 'Re enter your old password!!!!']);;
        }
    }

    public function findUserById($id)
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
}
