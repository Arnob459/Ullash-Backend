<?php

namespace App\Http\Controllers\Api;

use App\Http\Controllers\Controller;
use Illuminate\Http\Request;
use App\Http\Requests\RegisterRequest;
use Illuminate\Support\Facades\Hash;
use Illuminate\Support\Facades\Response;

use Illuminate\Support\Facades\DB;
use Illuminate\Support\Facades\Mail;
use Illuminate\Support\Str;
use App\Mail\Forgotmail;
use App\Models\User;
use Illuminate\Database\QueryException;

use Auth;

class UserController extends Controller
{
    /**
     * Display a listing of the resource.
     */
    public function userRegister(Request $request)
    {
        try {
            $validatedData = $request->validate([
                'name' => 'required|string|max:255',
                'email' => 'required|email|unique:users,email',
                'password' => 'required|min:6',
            ]);

            $user = User::create([
                'name' => $request->name,
                'email' => $request->email,
                'password' => Hash::make($request->password),

            ],400);
            $token = $user->createToken('example')->accessToken;
            return response()->json([
                'message' => 'Registration successful',
                'token' => $token,
                'user' => $user,
            ], 200);
        } catch (\Throwable $th) {
            return response()->json([
                'message' => 'Registration failed',
            ], 500);
        }



    }

    public function forgotPassword(Request $request)
    {
            $email = $request->email;
            if (User::where('email',$email)->doesntExist()) {
                return response()->json([
                    'message' => 'Invalid email',
                ], 404);
            }
            $token = rand(10,100000);
            DB::table('password_reset_tokens')->insert([
                'email' => $email,
                'token' => $token,
            ]);
            Mail::to($email)->send(new Forgotmail($token));
            return response()->json([
                'message' => ' email send successfully',
            ], 400);



    }



//     public function forgotPassword(Request $request)
// {
//     try {
//         $email = $request->email;

//         // Check if the email exists
//         if (!User::where('email', $email)->exists()) {
//             return response()->json([
//                 'message' => 'Invalid email',
//             ], 404);
//         }

//         // Generate a secure token
//         $token = Str::random(8);

//         // Insert into the password_reset_tokens table
//         User::createPasswordResetToken($email, $token);

//         // Send email with the token
//         Mail::to($email)->send(new Forgotmail($token));

//         return response()->json([
//             'message' => 'Email sent successfully',
//         ], 200);

//     }catch (\Throwable $th) {
//         // Log the exception details for debugging
//         \Log::error($th);

//         return response()->json([
//             'message' => 'Internal server error',
//         ], 500);
//     }
// }

public function resetPassword(Request $request)
{
        $email = $request->email;
        $token = $request->token;
        $password = Hash::make($request->password);

    $emailCheck = DB::table('password_reset_tokens')->where('email',$email)->first();
    $pinCheck = DB::table('password_reset_tokens')->where('token',$token)->first();

    if (!$emailCheck) {
        return response([
            'message'=>'Email Invalid'
        ],401);

    }
    if (!$pinCheck) {
        return response([
            'message'=>'Token Invalid'
        ],401);

    }

    User::where('email',$email)->update(['password' => $password]);
    DB::table('password_reset_tokens')->where('email',$email)->delete();

    return response([
        'message' => 'password chenged successfully'
    ],200);

}


    public function loginUser(Request $request)
    {
        $input = $request->all();

        Auth::attempt($input);

        $user = Auth::user();

        $token = $user->createToken('example')->accessToken;

        return Response([
            'status' => 200,
            'message' => 'login successfully',

            'token' => $token,
            'user' => $user,

     ],200);
    }

    /**
     * Store a newly created resource in storage.
     */
    public function getUserDetail()
    {
        if(Auth::guard('api')->check()){
            $user = Auth::guard('api')->user();
            return Response(['data' => $user],200);
        }
        return Response(['data' => 'Unauthorized'],401);
    }

    public function userLogout()
    {
        if(Auth::guard('api')->check()){
            $accessToken = Auth::guard('api')->user()->token();

                \DB::table('oauth_refresh_tokens')
                    ->where('access_token_id', $accessToken->id)
                    ->update(['revoked' => true]);
            $accessToken->revoke();

            return Response(['data' => 'Unauthorized','message' => 'User logout successfully.'],200);
        }
        return Response(['data' => 'Unauthorized'],401);
    }
}
