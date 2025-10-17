<?php

namespace App\Http\Controllers;

use GrahamCampbell\ResultType\Success;
use Illuminate\Http\Request;
use App\Models\Event;
use App\Models\JobPost;
use App\Models\Magazine;
use App\Models\News;
use App\Models\MemberFeed;
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



}
