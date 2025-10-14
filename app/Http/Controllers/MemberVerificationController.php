<?php

namespace App\Http\Controllers;

use App\Models\User;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Auth;
use Illuminate\Support\Facades\Crypt;

class MemberVerificationController extends Controller
{
    public function verify(Request $request)
    {
        try {
            $userId = Crypt::decryptString(urldecode($request->query('token')));
            info("userId");
            info(print_r($userId));
        } catch (\Exception $e) {
            abort(403, 'Invalid verification link');
        }

        $user = User::findOrFail($userId);

        // Already verified
        if ($user->email_verified_at) {
        
            if (!Auth::check()) {
                Auth::login($user);
            }

            return redirect('/portal/my-profile')
                ->with('success', 'Your email is already verified. You can change your password here.');
        }

        // Case 2: First-time verification
        $user->email_verified_at = now();
        $user->save();

        // Auto login
        Auth::login($user);

        return redirect('/portal/my-profile')
            ->with('success', 'Email verified successfully! Please set your password.');
    }
}
