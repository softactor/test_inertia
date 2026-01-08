<?php

namespace App\Http\Controllers;

use App\Models\OtpCode;
use App\Models\User;
use Carbon\Carbon;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Auth;
use Inertia\Inertia;

use function Symfony\Component\Clock\now;

class OtpController extends Controller
{
    
    public function create()
    {
        return Inertia::render('Auth/OtpLogin');
    }
    
    public function send(Request $request)
    {
        $data = $request->validate([
            'email' => 'required|email'
        ]);


        $code = (string) random_int(100000, 999999);

        $currentTime = Carbon::now();

        OtpCode::create([
            'email' => $request->email,
            'code' => $code,
            'expires_at' => $currentTime->addMinutes(1)
        ]);

        return back()->with('success', "OTP :$code");
        
    }
    
    public function verify(Request $request)
    {
        $data = $request->validate([
            'email' => 'required|email',
            'code' => 'required|digits:6'
        ]);


        $otp = OtpCode::where('email', $request->email)
            ->where('code', $request->code)
            ->whereNull('used_at')
            ->latest()
            ->first();

        if(!$otp)
        {
            return back()->with('error', 'OTP not found');
        }
        
        if (Carbon::now()->gt($otp->expires_at))
        {
            return back()->with('error', 'OTP Expired!');
        }

        $user = User::where('email', $request->email)
                    ->first();

        Auth::login($user);

        return redirect()->route('dashboard')->with('success', 'Logged in with OTP');

    }

}
