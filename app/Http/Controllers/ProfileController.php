<?php

namespace App\Http\Controllers;

use App\Image;
use App\Models\User;
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

class ProfileController extends Controller
{

    private $userService;
    /**
     * Display a listing of the resource.
     *
     * @return \Illuminate\Http\Response
     */
    public function index(Request $request)
    {
        \Log::info($request);
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
            ->get();
        return response()->json($users);
    }

    /**
     * Show the form for creating a new resource.
     *
     * @return \Illuminate\Http\Response
     */
    public function create(Request $request)
    {
        // \Log::info($request);
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
        $image = $request['image'];
        $fileName = uniqid() . '.jpg';
        $path = storage_path('app/public/' . $fileName);
        $success = file_put_contents($path, base64_decode($image));
        \Log::info($request['selectedImage']['assets'][0]['uri']);
        $datetime = $request['selectedDate'];
        $carbon = Carbon::createFromFormat('Y-m-d\TH:i:s.u\Z', $datetime);
        $result = $carbon->format('Y-m-d H:i:s');
        $users = User::create([
            'name' => $request['name'],
            'email' => $request['email'],
            'password' => Hash::make($request['password']),
            'profile' => $request['selectedImage']['assets'][0]['uri'],
            'type' => $request['value'],
            'phone' => $request['phone'],
            'address' => $request['address'],
            'delete_flag' => 0,
            'date_of_birth' => $result,
            'created_user_id' => 1,
            'updated_user_id' => 1,
        ]);
        return $users;
    }

    /**
     * Store a newly created resource in storage.
     *
     * @param  \Illuminate\Http\Request  $request
     * @return \Illuminate\Http\Response
     */
    public function store(Request $request)
    {
        //
    }

    /**
     * Display the specified resource.
     *
     * @param  int  $id
     * @return \Illuminate\Http\Response
     */
    public function show($id)
    {
        //
    }

    /**
     * Show the form for editing the specified resource.
     *
     * @param  int  $id
     * @return \Illuminate\Http\Response
     */
    public function edit(Request $request)
    {
        $validator = Validator::make($request, [
            'name' => 'required',
            'email' => 'required|email:rfc',
        ]);
        if ($validator->fails()) {
            return $validator->errors();
        }
        return response()->json(['success' => true, 'message' => 'Data has been saved']);
    }

    /**
     * Update the specified resource in storage.
     *
     * @param  \Illuminate\Http\Request  $request
     * @param  int  $id
     * @return \Illuminate\Http\Response
     */
    public function update(Request $request)
    {
        \Log::info($request);
        $datetime = $request['selectedDate'];
        $carbon = Carbon::createFromFormat('Y-m-d\TH:i:s.u\Z', $datetime);
        $result = $carbon->format('Y-m-d H:i:s');
        return DB::table('users')
            ->where('id', $request['id'])
            ->update([
                'name' => $request['name'],
                'email' => $request['email'],
                'type' => $request['value'],
                'phone' => $request['phone'],
                'date_of_birth' => $result,
                'address' => $request['address'],
            ]);
    }

    /**
     * Remove the specified resource from storage.
     *
     * @param  int  $id
     * @return \Illuminate\Http\Response
     */
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
        \Log::info($request->oldpassword);
        \Log::info($request->data['password']);
        \Log::info($request);
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
            return response()->json(['message' => $validated->errors()], 400);
        } else {
            $old = $request->data['password'];
            $id = $request->data['id'];
            $typepass = $request->oldpassword;
            if (Hash::check($typepass, $old)) {
                return User::where('id', $id)->update([
                    'password' => Hash::make($request->newpassword),
                ]);
            } else {
                return response()->json(['message' => 'Re enter your password', 400]);
            }
        }
    }
    public function forgotpassword(Request $request)
    {
        \Log::info($request);
        $this->validateEmail($request);

        $response = $this->broker()->sendResetLink(
            $request->only('email')
        );

        return $response == Password::RESET_LINK_SENT
            ? response()->json(['message' => 'Password reset email sent'])
            : response()->json(['error' => 'Unable to send password reset email'], 500);
    }
    protected function broker()
    {
        return Password::broker();
    }

    protected function validateEmail(Request $request)
    {
        $request->validate(['email' => 'required|email']);
    }
}
