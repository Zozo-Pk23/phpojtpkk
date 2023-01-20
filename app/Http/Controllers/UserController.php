<?php

namespace App\Http\Controllers;

use App\Http\Services\UserService;
use App\Models\User;
use Illuminate\Database\Eloquent\SoftDeletes;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Auth;
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
    /**
     * Get all users
     * 
     * @return array $users
     */
    public function index()
    {
        $users = $this->userService->index();
        return view('users.users', ['users' => $users]);
    }
    /**
     * Create user
     * 
     * @param Request $request
     * 
     * @return $user and $fname
     */
    public function createuser(Request $request)
    {
        if ($request->hasFile('profile')) {
            $file = $request->file('profile');
            $fname = $file->getClientOriginalName();
            $file->move("images", $fname);
        }
        $validated = $request->validate(
            [
                'name' => 'required',
                'email' =>  'required|email:rfc',
                'password' => [
                    'required',
                    'same:confirmpassword',
                    'min:8',
                    'regex:/^.*(?=.{3,})(?=.*[a-zA-Z])(?=.*[0-9])(?=.*[\d\x])(?=.*[!$#%]).*$/',
                ],
                'confirmpassword' => 'required',
                'profile' => 'required',
                'date' => 'nullable|before:today'
            ],
            [
                'password.required' => 'fill the password please',
                'password.min' => 'password must longer than 8 letters',
                'password.regex' => 'password must include integer,uppercase,lowercase,sign'
            ]
        );

        return view('users.confirmuser', ['user' => $request, 'fname' => $fname]);
    }
    /**
     * Save User to Database
     * 
     * @param Request $request
     * 
     */
    public function saveuser(Request $request)
    {
        $this->userService->save($request);
        return redirect()->route('users');
    }
    /**
     * Delete User
     * 
     * @param $id
     * 
     */
    public function deleteuser($id)
    {
        $this->userService->deleteuser($id);
        return redirect()->route('users');
    }
    /**
     * Change password screen
     * 
     * @param $id
     * 
     * @return $password
     */
    public function findUserById($id)
    {
        $password = $this->userService->findUserById($id);
        return view('users.passwordreset', ['password' => $password]);
    }
    /**
     * Update password
     * 
     * @param $id,Request $request
     * 
     */
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
            ], [
                'newpassword.regex' => 'New must include integer,uppercase,lowercase,sign'
            ]);
            $this->userService->updatepassword($id, $request);
            return redirect('login')->with(Auth::logout());
        } else {
            return redirect()->back()->withErrors(['msg' => 'Re enter your old password!!!!']);;
        }
    }


    /**
     * Show post details
     * 
     * @param $id
     * 
     * @return $user
     */
    public function profile($id)
    {
        $users = $this->userService->findUserById($id);
        return view('users.myprofile', ['user' => $users]);
    }
    /**
     * Show user detail
     * 
     * @param $id
     * 
     * @return $user
     * 
     */
    public function editProfile($id)
    {
        $users = $this->userService->findUserById($id);
        return view('users.userupdateform', ['user' => $users]);
    }
    /**
     * Validate Update Form
     * 
     * @param Request $request
     * 
     * @return $user,$fname
     * 
     */
    public function confirmProfile(Request $request)
    {
        $validated = $request->validate([
            'name' => 'required',
            'email' => 'required|email:rfc',
            'phone' => 'required',
        ]);
        if ($request->hasFile('profile')) {
            $file = $request->file('profile');
            $fname = $file->getClientOriginalName();
            $file->move("images", $fname);
        } else {
            $fname = $request->oldprofile;
        }
        return view('users.userupdateconfirm', ['user' => $request, 'fname' => $fname]);
    }
    /**
     * Final update user to database
     * 
     * @param $id,Request $request
     * 
     */
    public function updateUser($id, Request $request)
    {
        $this->userService->updateProfile($id, $request);
        return redirect()->route('home');
    }
}
