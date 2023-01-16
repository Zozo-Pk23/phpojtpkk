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
    /**
     * Get all users
     * 
     * @return array $users
     */
    public function index()
    {
        $users = DB::table('users')
            ->select('users.id', 'users.name', 'users.email', 'u2.name As pname', 'users.phone', 'users.date_of_birth', 'users.address', 'users.created_at', 'users.updated_at')
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
            'profile' => 'required'
        ]);
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
            ]);
            $this->userService->updatepassword($id, $request);
            return redirect()->route('home');
        } else {
            return redirect()->back()->withErrors(['msg' => 'Reenter your old password!!!!']);;
        }
    }
}
