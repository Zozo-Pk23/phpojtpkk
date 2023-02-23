<?php

namespace App\Http\Controllers;

use App\Image;
use App\Models\User;
use DateTime;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Auth;
use Illuminate\Support\Facades\DB;
use Illuminate\Support\Facades\Log;
use Illuminate\Support\Facades\Storage;
use Illuminate\Support\Facades\Validator;
use Illuminate\Http\UploadedFile;
use Illuminate\Support\Carbon;
use Illuminate\Support\Facades\File;
use Illuminate\Support\Collection;
use Illuminate\Support\Facades\Hash;
use Illuminate\Support\Facades\Password;
use Illuminate\Validation\Rule;
use Illuminate\Validation\Validator as ValidationValidator;

use function PHPUnit\Framework\returnSelf;

class ProfileController extends Controller
{
    public function index(Request $request)
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
                    ->when($startdate, function ($query) use ($startdate) {
                        $query->whereDate('users.created_at', '>=', $startdate);
                    })
                    ->when($enddate, function ($query) use ($enddate) {
                        $query->whereDate('users.created_at', '<=', $enddate);
                    })
                    ->when($name, function ($query) use ($name) {
                        $query->where('users.name', 'LIKE', "%" . $name . "%");
                    })
                    ->when($email, function ($query) use ($email) {
                        $query->where('users.email', 'LIKE', "%" . $email . "%");
                    });
            })
            ->get();
        return $users;
    }

    public function create(Request $request)
    {
        $validated = Validator::make(
            $request->all(),
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
                'image' => 'required',
                'phone' => 'numeric'
            ]
        );
        if ($validated->fails()) {
            return response()->json(['errors' => $validated->errors()], 400);
        } else {
            return response()->json(['success' => true, 'message' => 'Data has been saved']);
        }
    }
    public function confirm(Request $request)
    {
        $id = Auth::user()->id;
        $image = $request['image'];
        $fileName = uniqid() . '.jpg';
        $path = storage_path('app/public/' . $fileName);
        $success = file_put_contents($path, base64_decode($image));
        $datetime = $request['selectedDate'];
        $carbon = Carbon::createFromFormat('Y-m-d\TH:i:s.u\Z', $datetime);
        $result = $carbon->format('Y-m-d H:i:s');
        $users = User::create([
            'name' => $request['name'],
            'email' => $request['email'],
            'password' => Hash::make($request['password']),
            'profile' => $fileName,
            'type' => $request['value'],
            'phone' => $request['phone'],
            'address' => $request['address'],
            'delete_flag' => 0,
            'date_of_birth' => $result,
            'created_user_id' => $id,
            'updated_user_id' => $id,
        ]);
        return $users;
    }
    public function edit(Request $request)
    {
        $validated = Validator::make(
            $request['name'],
            [
                'name' => 'required',
                'email' => 'required|email:rfc',
                'phone' => 'numeric'
            ]
        );
        if ($validated->fails()) {
            return response()->json(['message' => $validated->errors()], 400);
        } else {
            return response()->json(['success' => true, 'message' => 'Ok']);
        }
    }
    public function update(Request $request)
    {
        if ($request->newphoto) {
            $image = $request->newphoto;
            $fileName = uniqid() . '.jpg';
            $path = storage_path('app/public/' . $fileName);
            $success = file_put_contents($path, base64_decode($image));
        } else {
            $fileName = $request['data']['selectedImage'];
        }
        $users = User::where('id', $request['data']['id'])
            ->update([
                'name' => $request['data']['name'],
                'email' => $request['data']['email'],
                'profile' => $fileName,
                'type' => $request['data']['value'],
                'phone' => $request['data']['phone'],
                'date_of_birth' => $request['data']['dateone'],
                'address' => $request['data']['address'],
            ]);
        return $users;
    }
    public function destroy(Request $request)
    {
        return User::where('id', $request['id'])->update([
            'delete_flag' => 1
        ]);
    }

    public function userInfo()
    {
        if (Auth::check()) {
            $user = Auth::user();
            return response()->json([
                'message' => 'User Information',
                'user' => $user
            ]);
        } else {
            return response()->json([
                'message' => 'No authenticated user found'
            ]);
        }
    }

    public function passwordreset(Request $request)
    {
        $validated = Validator::make(
            $request->all(),
            [
                'oldpassword' => 'required',
                'newpassword' =>  [
                    'required',
                    'min:8',
                    'regex:/^.*(?=.{3,})(?=.*[a-zA-Z])(?=.*[0-9]).*$/',
                    'same:password_confirmation'
                ],
                'password_confirmation' => 'required',
            ],
            [
                'newpassword.regex' => 'New must include integer,uppercase,lowercase,sign'
            ]
        );
        if ($validated->fails()) {
            return response()->json(['message' => $validated->errors()]);
        } else {
            $old = Auth::user()->password;
            $id = Auth::user()->id;
            $typepass = $request->oldpassword;

            if (Hash::check($typepass, $old)) {
                $user = User::where('id', $id)->update([
                    'password' => Hash::make($request->newpassword),
                ]);
                return response()->json(['message' => 'password changed successfully', 'user' => $user]);
            } else {
                return response()->json(['message'  => [
                    'oldpassword' => [
                        'Re enter your password'
                    ]
                ], 400]);
            }
        }
    }
    public function login(Request $request)
    {
        try {
            $loginDetails = $request->only('email', 'password');
            $validated = Validator::make(
                $request->all(),
                [
                    'email' => 'required',
                    'password' => 'required'
                ]
            );
            if ($validated->fails()) {
                return response()->json(['message' => $validated->errors()], 400);
            }
            $user = User::where('email', $request->email)->first();
            if (!$user) {
                return response()->json([
                    'message'  => [
                        'email' => [
                            'Email does not existed'
                        ]
                    ]
                ], 422);
            }

            if (Auth::attempt($loginDetails)) {
                $token = auth()->user()->createToken('passport_token')->accessToken;
                $data = Auth::user();
                return response()->json([
                    'success' => true,
                    'message' => 'login successful',
                    'token' => $token,
                    'data' => $data,
                ], 200);
            } else {
                return response()->json([
                    'message'  => [
                        'password' => [
                            'Wrong Password Details'
                        ]
                    ]
                ], 422);
            }
        } catch (Exception $e) {
            return response()->json([
                'message' => 'An error occurred while trying to log in',
                'error' => $e->getMessage()
            ], 500);
        }
    }
    public function logout()
    {
        Auth::logout();
    }
}
