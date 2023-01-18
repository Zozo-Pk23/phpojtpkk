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
        $loginUser = Auth::user()->id;
        $users = DB::table('users')
            ->select('users.id', 'users.name', 'users.email', 'u2.name As pname', 'users.phone', 'users.date_of_birth', 'users.address', 'users.created_at', 'users.updated_at', 'users.profile')
            ->join('users As u2', 'u2.id', '=', 'users.created_user_id')
            ->where('users.deleted_at', '=', NULL)
            ->paginate(7);
        return view('users.users', ['users' => $users]);
    }
    /**
     * Search user
     * 
     * @param Request $request
     * 
     * @return $users
     */
    public function search(Request $request)
    {
        $users = $this->userService->searchuser($request);
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
                'date' => 'before:today'
            ],
            [
                'password.required' => 'fill the password please',
                'password.min' => 'password must longer than 8 letters',
                'password.regex' => 'password must include integer,uppercase,lowercase,sign'
            ]
        );
        if ($request->hasFile('profile')) {
            $file = $request->file('profile');
            $fname = $file->getClientOriginalName();
            $file->move("images", $fname);
        }
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
    public function changepasswordscreen($id)
    {
        $password = $this->userService->changepasswordscreen($id);
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
            return redirect()->back()->withErrors(['msg' => 'Reenter your old password!!!!']);;
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
        $users = $this->userService->profile($id);
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
        $users = $this->userService->profile($id);

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
        $post = $this->userService->updateProfile($id, $request);
        return redirect()->route('home');
    }
}
