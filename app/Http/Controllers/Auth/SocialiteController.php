<?php

namespace App\Http\Controllers\Auth;

use App\Models\User;
use App\Models\Saldo;
use Illuminate\Support\Str;
use Illuminate\Http\Request;
use Spatie\Permission\Models\Role;
use App\Http\Controllers\Controller;
use Illuminate\Support\Facades\Auth;
use Illuminate\Support\Facades\Hash;
use Illuminate\Support\Facades\Storage;
use Laravel\Socialite\Facades\Socialite;

class SocialiteController extends Controller
{
    public function redirectToGoogle() {
        return Socialite::driver('google')
        ->scopes(['https://www.googleapis.com/auth/user.phonenumbers.read'])
        ->redirect();
        // return Socialite::driver('google')->userFromToken($token);

    }

    public function handleGoogleCallback() {
        $googleUser = Socialite::driver('google')->user();
        $user = User::where('email', $googleUser->email)->first();

        // Get profile picture URL
        $profilePictureUrl = $googleUser->getAvatar();

        // Generate unique filename
        $room_slug_name = Str::slug($googleUser->getName()); // Using Laravel's Str helper
        $profile_name = 'PROFILE-' . $room_slug_name . '-' . rand(000, 999);

        // Get file extension (assuming it's jpg/jpeg)
        $ext = 'jpg'; // or detect dynamically if needed

        // Full filename
        $profile_full_name = $profile_name . '.' . $ext;

        // Upload path
        $upload_path = 'storage/profile_pictures/';

        // Full URL path
        $profile_url = $upload_path . $profile_full_name;

        // Download and move the file
        $profileImage = file_get_contents($profilePictureUrl);
        file_put_contents(public_path($profile_url), $profileImage);

        if(!$user) {
            $user = User::create([
                'name' => $googleUser->name,
                'email' => $googleUser->email,
                'phone' => $googleUser->phone,
                'avatar' => $profile_url,
                'password' => Hash::make(rand(100000,999999)),
            ]);
        }

        $user->assignRole('user');

        Saldo::create([
            'user_id' => $user->id,
            'credit' => 0,
            'debit' => 0,
            'amount' => 0,
            'created_at' => now(),
            'updated_at' => now(),
        ]);

        Auth::login($user);
        return redirect()->route('frontpage.index');
    }
}
