<?php

namespace App\Http\Controllers\Api\Auth;

use App\Http\Controllers\Controller;
use App\Mail\VerifyEmailOtpMail;
use App\Models\User;
use Ichtrojan\Otp\Otp;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Auth;
use Illuminate\Support\Facades\Hash;
use Illuminate\Support\Facades\Mail;
use Illuminate\Support\Facades\Validator;


class AuthController extends Controller
{
    //register
   public function register(Request $request){
      //validation
      $validator= Validator::make($request->all(),[
             "name" => "required|string|max:255",
             "email" => "required|string|max:255|email|unique:users,email",
             "password" => "required|min:8",
             "role" => "nullable|string",
      ]);

      if($validator->fails()){
         return response()->json([
               "success" => false,
               "errors" => $validator->errors(),
         ],422);
      }

      $passwordHash =Hash::make($request->password);
      //create
      $user= User::create([
           'name' => $request->name,
           'email' => $request->email,
           'password' => $passwordHash,
           'role' => $request->role ?? 'user', //default => user
           'is_verified'  => false,
      ]);
      //create OTP
       $otp = new Otp;
       $response = $otp->generate($user->email, 'numeric', 6, 10);

       // send to mail
        Mail::to($user->email)->send(new VerifyEmailOtpMail($response->token));


      //response
       return response()->json([
               "success" => true,
               "message" => "user created successfully. Please check your email for OTP to verify your account",
         ],201);
   }

   //
   public function verifyEmail(Request $request){
        $request->validate([
        "email" => "required|email",
        "otp"   => "required|string",
    ]);

    $otp = new Otp;
    $verify = $otp->validate($request->email, $request->otp);

    if (!$verify->status) {
        return response()->json([
            "success" => false,
            "message" => "Invalid or expired OTP"
        ], 400);
    }

    $user = User::where("email", $request->email)->first();
    $user->is_verified = true;
    $user->save();

    return response()->json([
        "success" => true,
        "message" => "Email verified successfully"
    ], 200);
}

   //





   //login
   public function login(Request $request){
    //validation
         $request->validate([
            "email" => "required|string|email",
            "password" => "required|min:8",
         ]);

    //check
          $user =User::where("email",$request->email)->first(); //make select by query builder
          //dd($user);

           if(!$user || !Hash::check($request->password,$user->password)){
            return response()->json([
                "success" => false,
                "message" => "invalid credentials",
            ],401);
           }

        //    //create otp
        //    $otp = new Otp;
        //      $response = $otp->generate($user->email, 'numeric', 6, 10);

        //       // هنا ترسل الكود للمستخدم عبر البريد أو SMS
        //       // Mail::to($user->email)->send(new SendOtpMail($response->token));

        //      return response()->json([
        //             "success" => true,
        //             "message" => "OTP sent to your email",
        //      ], 200);
        //     }

        //     public function verifyOtp(Request $request)
        //     {
        //             $request->validate([
        //              "email" => "required|string|email",
        //               "otp"   => "required|string",
        //             ]);

        //              $otp = new Otp;
        //              $verify = $otp->validate($request->email, $request->otp);

        //             if (!$verify->status) {
        //             return response()->json([
        //              "success" => false,
        //              "message" => "Invalid or expired OTP",
        //              ], 400);
        //         }

        //              $user = User::where("email", $request->email)->first();
        //              $token = $user->createToken("API token")->plainTextToken;

        //              return response()->json([
        //             "success" => true,
        //             "message" => "Login successful",
        //             "token"   => $token,
        //             ], 200);
        //     }
        //    //1

    //token (login)
      $token=$user->createToken("API token")->plainTextToken;
    //response
          return response()->json([
            "success" => true,
            "message"  => "login successfully",
            "token" => $token,
          ],200);
        }


   public function logout(Request $request){
      //dd(Auth::user());
      $logout = $request->user()->currentAccessToken()->delete();      // = $user=()Auth::user();

        return response()->json([
             "success" => true,
             "message" => "logout successfully",
        ],200);


   }
}


