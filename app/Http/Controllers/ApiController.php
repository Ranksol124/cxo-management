<?php

namespace App\Http\Controllers;

use App\Models\User;
use GrahamCampbell\ResultType\Success;
use Illuminate\Http\Request;
use App\Models\Event;
use App\Models\JobPost;
use App\Models\Magazine;
use App\Models\News;
use App\Models\MemberFeed;

use App\Http\Controllers\Controller;
use Illuminate\Support\Facades\Hash;
use Illuminate\Validation\Rules\Password;


class ApiController extends Controller
{
    public function GetRecordAll(Request $request)
    {

        $apiKey = $request->header('x-api-key');
        $Key = env('Apikey');

        if ($apiKey !== $Key) {
            return response()->json([
                'success' => false,
                'message' => 'Unauthorized',
            ], 403);
        }


        $models = [
            'events' => [Event::class, []],
            'job_post' => [JobPost::class, []],
            'news' => [News::class, []],
            'magzines' => [Magazine::class, []],
            'user' => [User::class, []],
            'member_feeds' => [MemberFeed::class, ['comments', 'attachments', 'likesAndDislikes']],
        ];

        $data = [];

        foreach ($models as $key => [$modelClass, $relations]) {
            $data[$key] = $modelClass::with($relations)->get();
        }

        return response()->json([
            'success' => true,
            'data' => $data,
        ]);
    }

    //auth work from here 

    public function updateProfile(Request $request)
    {
        $user = $request->user();

        $validated = $request->validate([
            'name' => 'required|string|max:255',
            'email' => 'required|email|unique:users,email,' . $user->id,
            'profile_picture' => 'nullable|max:2048',
        ]);

        if ($request->hasFile('profile_picture')) {
            $path = $request->file('profile_picture')->store('avatar', 'public');
            $validated['profile_picture'] = $path;
        }

        $user->update($validated);

        return response()->json([
            'message' => 'Profile updated successfully',
            'user' => $user->fresh(),
        ]);
    }

    public function updatePassword(Request $request)
    {
        $request->validate([
            'password' => ['required', 'confirmed', Password::default()->mixedCase()->uncompromised(3)],
        ]);

        $user = $request->user();

        $user->update([
            'password' => Hash::make($request->password),
        ]);

        return response()->json(['message' => 'Password updated successfully']);
    }



    public function passwordReset()
    {

    }

    public function ApplyJob()
    {

    }
}
