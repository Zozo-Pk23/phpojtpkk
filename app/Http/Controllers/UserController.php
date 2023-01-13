<?php

namespace App\Http\Controllers;

use App\Http\Services\UserService;
use App\Models\User;
use Illuminate\Database\Eloquent\SoftDeletes;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\DB;
use Illuminate\Support\Facades\Hash;

class UserController extends Controller
{
    private $userService;
    use SoftDeletes;
    public function __construct(UserService $userService)
    {
        $this->userService = $userService;
    }
    public function index()
    {
        $users = DB::table('users')
            ->select('users.id', 'users.name', 'users.email', 'u2.name As pname', 'users.phone', 'users.date_of_birth', 'users.address', 'users.created_at', 'users.updated_at')
            ->join('users As u2', 'u2.id', '=', 'users.created_user_id')
            ->where('users.deleted_at', '=', NULL)
            ->paginate(7);
        // $createdUserName = User::where('id', '=', $users->created_user_id)->first();
        //dd($users->created_user_id);
        return view('users.users', ['users' => $users]);
    }
    public function createuser(Request $request)
    {
        $validated = $request->validate([
            'name' => 'required',
            'email' =>  'required|email:rfc',
            'password' => [
                'required',
                'same:confirmpassword',
                'min:6',
                'regex:/^.*(?=.{3,})(?=.*[a-zA-Z])(?=.*[0-9])(?=.*[\d\x])(?=.*[!$#%]).*$/',
            ],
            'confirmpassword' => 'required',
        ]);

        // $file = $request->File('profile');
        // $newname = rand() . '.' . $file->getClientOriginalExtension();
        // $file->move(public_path("images"),$newname);
        // return back();

        //dd($request->profile);
        if ($request->hasFile('profile')) {
            $file = $request->file('profile');
            $fname = $file->getClientOriginalName();
            $file->move("images", $fname);
        }
        // dd($request->file('profile'));


        return view('users.confirmuser', ['user' => $request, 'fname' => $fname]);
    }
    public function saveuser(Request $request)
    {
        $this->userService->save($request);
        return redirect()->route('users');
    }
    public function deleteuser($id)
    {
        $this->userService->deleteuser($id);
        return redirect()->route('users');
    }
    public function search(Request $request)
    {
        $users = $this->userService->searchuser($request);
        return view('users.users', ['users' => $users]);
    }
    public function changepasswordscreen($id)
    {
        $password = $this->userService->changepasswordscreen($id);
        return view('users.passwordreset', ['password' => $password]);
    }
    public function updatepassword($id, Request $request)
    {
        $old = User::where('id', $id)->first();
        $typepass = $request->oldpassword;
        if (Hash::check($typepass, $old->password)) {
            $validated = $request->validate([
                'oldpassword' => 'required',
                'newpassword' =>  [
                    'required',
                    'min:8',
                    'regex:/^.*(?=.{3,})(?=.*[a-zA-Z])(?=.*[0-9]).*$/',
                    'same:password_confirmation'
                ],
                'password_confirmation' => 'required',
            ]);
            $this->userService->updatepassword($id, $request);
            return redirect()->route('home');
        } else {
            return redirect()->back()->withErrors(['msg' => 'Reenter your old password!!!!']);;
        }
    }
}
