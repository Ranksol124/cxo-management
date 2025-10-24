<?php

namespace App\Http\Controllers;

use App\Models\MemberContent;
use App\Models\User;
use GrahamCampbell\ResultType\Success;
use Illuminate\Http\Request;
use App\Models\Event;
use App\Models\JobPost;
use App\Models\Magazine;
use App\Models\News;
use App\Models\MemberFeed;
use App\Models\JobMembers;
use App\Models\Member;
use App\Http\Controllers\Controller;
use Illuminate\Support\Facades\Hash;
use Illuminate\Validation\Rules\Password;
use App\Services\CvMailerService;

use Illuminate\Support\Facades\Validator;

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

        $userId = $request->header('member-id');

        if (!$userId) {
            return response()->json([
                'success' => false,
                'message' => 'User ID header missing',
            ], 400);
        }

        $member = Member::where('user_id', $userId)->first();

        $memberId = $member ? $member->id : null;

        $models = [
            'events' => [Event::class, []],
            'job_post' => [JobPost::class, []],
            'my_jobs' => [JobPost::class, []],
            'news' => [News::class, []],
            'magzines' => [Magazine::class, []],
            'user' => [User::class, []],
            'members' => [Member::class, []],
            'member_feeds' => [MemberFeed::class, ['comments', 'attachments', 'likesAndDislikes']],
            'member_contents' => [MemberContent::class, []],
            'my_content' => [MemberContent::class, []],
        ];

        $data = [];

        foreach ($models as $key => [$modelClass, $relations]) {
            if ($key === 'my_content') {

                $data[$key] = $memberId
                    ? $modelClass::with($relations)->where('member_id', $memberId)->get()
                    : collect();
            } elseif ($key === 'my_jobs') {

                $data[$key] = $userId
                    ? $modelClass::with($relations)->where('user_id', $userId)->get()
                    : collect();
            } else {
                $data[$key] = $modelClass::with($relations)->get();
            }
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



    public function ApplyJob(Request $request, CvMailerService $cvMailerService)
    {

        $validator = Validator::make($request->all(), [
            'members_id' => 'required|exists:users,id',
            'jobs_id' => 'required|exists:job_posts,id',
            'name' => 'required|string|max:255',
            'current_address' => 'nullable|string',
            'education' => 'nullable|string',
            'experience' => 'nullable|string',
            'cover_letter' => 'nullable|string',
            'cv_upload' => 'required|file|mimes:pdf,doc,docx|max:2048',
        ]);

        if ($validator->fails()) {
            return response()->json(['errors' => $validator->errors()], 422);
        }

        $cvFile = $request->file('cv_upload');
        $cvPath = $cvFile->store('member_cv', 'public');
        $cvFileName = $cvFile->getClientOriginalName();


        $jobMember = JobMembers::create([
            'members_id' => $request->members_id,
            'jobs_id' => $request->jobs_id,
            'name' => $request->name,
            'current_address' => $request->current_address,
            'education' => $request->education,
            'experience' => $request->experience,
            'cover_letter' => $request->cover_letter,
            'cv_upload' => $cvPath,
        ]);

        $jobPost = JobPost::find($request->jobs_id);
        if (!$jobPost || !$jobPost->user) {
            return response()->json(['message' => 'Job post or owner not found.'], 404);
        }
        $jobPostOwnerEmail = $jobPost->user->email;

        $cvMailerService->sendCv(storage_path('app/public/' . $cvPath), $cvFileName, $jobPostOwnerEmail);

        return response()->json(['message' => 'Job applied successfully']);
    }

}
