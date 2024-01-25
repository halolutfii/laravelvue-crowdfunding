<?php

namespace App\Http\Controllers\Auth;

use App\Http\Controllers\Controller;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Hash;
use App\Models\User;
use App\Models\otpCode;
use Carbon\Carbon;
use App\Events\UserRegisteredEvent;
use App\Events\GenerateOTPCodeEvent;


use Illuminate\Support\Facades\Auth as AuthUser;

class AuthController extends Controller
{
    public function register(Request $request){
        $request->validate([
            'name' => 'required',
            'email' => 'required|email|unique:users,email',
            'password' => 'required|confirmed|min:8',
        ]);

        $user = User::create([
            'name' => $request['name'],
            'email' => $request['email'],
            'password' => Hash::make($request->password),
        ]);

        $data['user'] = $user;
        $token = AuthUser::login($user);
        // 
        event(new UserRegisteredEvent($user));

        return response()->json([
            'response_code' => '00',
            'response_message' => 'User berhasil register!',
            'data' => $data,
            'token' => $token,
        ]);
    }

    public function login(Request $request){
        $request->validate([
            'email' => 'required',
            'password' => 'required',
        ]);

        $credentials = request(['email', 'password']);

        if (! $token = auth()->attempt($credentials)) {
            return response()->json(['response_message' => 'Unauthorized'], 401);
        }

        return response()->json([
            'response_code' => '00',
            'response_message' => 'User berhasil login!',
            'token' => $token,
        ]);
    }

    public function profile(Request $request){
        $data['user'] = Auth()->user()->load('role');

        return response()->json([
            'response_code' => '00',
            'response_message' => 'Data berhasil ditampilkan!',
            'data' => $data,
        ]);
    }

    public function logout(Request $request){
        auth()->logout();
        
        return response()->json([
            'response_message' => 'User berhasil logout!',
        ]);
    }

    public function updateProfile(Request $request) {
        $user = auth()->user();

        if($request->hasFile('photo_profile')){
            $photo_profile = $request->file('photo_profile');
            $photo_profile_extension = $photo_profile->getClientOriginalExtension();
            $photo_profile_name = Str::slug($user->name, '-').'-'.$user->id.'.'.$photo_profile_extension;
            $photo_profile_folder = '/photo/user/photo-profile';
            $photo_profile_location = $photo_profile_folder . $photo_profile_name;
            try{
                $photo_profile->move(public_patch($photo_profile_folder), $photo_profile_name);
                //code...
                $user->update([
                    'photo_profile' => $photo_profile_location,
                ]);
            }catch (\Throwable $th){
                return response()->json([
                    'response_code' => '01',
                    'response_message' => 'Profile gagal  di upload!', 
                    // 'data' => $data
                ],200);
            }
        }

        $user->update([
            'name' => $request->name,
        ]);

        $data['user'] = $user;

        return response()->json([
            'response_code' => '00',
            'response_message' => 'Profile berhasil di update!', 
            'data' => $data
        ],200);
    }

    public function updatePassword(Request $request) {
        $this->validate($request,[
            'email' => 'required|email',
            'password' => 'required|confirmed|min:8',
        ]);

        User::where('email', $request->email)->update([
            'password' => Hash::make($request->password)
        ]);

        return response()->json([
            'response_code' => '00',
            'response_message' => 'Password berhasil di update!', 
        ],200);
    }

    public function generateOTP(Request $request){
        $request->validate([
            'email' => 'required|email',
        ]);

        $user = User::where('email', $request->email)->first();

        $user->generate_otp_code();

        $data['user']=$user;

        event(new GenerateOTPCodeEvent($user));

        return response()->json([
            'response_code' => '00',
            'response_message' => 'OTP Code berhasil di generate!', 
            'data' => $user,
        ],200);
    }

    public function verification(Request $request){
        $request->validate([
            'otp' => 'required',
        ]);

        $otp_code = otpCode::where('otp', $request->otp)->first();

        if(!$otp_code){
            return response()->json([
                'response_code' => '01',
                'response_message' => 'OTP Code tidak di temukan!', 
            ],400);
        }

        $now = Carbon::now();
        if($now > $otp_code->valid_until){
            return response()->json([
                'response_code' => '01',
                'response_message' => 'OTP Code sudah kadaluarsa, silahkan generate ulang!', 
            ],400);
        }

        // Update USER
        $user = User::find($otp_code->user_id);
        $user->email_verified_at = $now;
        $user->save();

        // Delete OTP CODE
        $otp_code->delete();

        return response()->json([
            'response_code' => '00',
            'response_message' => 'Email berhasil verifikasi!', 
        ],200);
    }
}
