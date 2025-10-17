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
    public function GetRecordAll()
    {
        $models = [
            'events' => [Event::class, []],
            'job_post' => [JobPost::class, []],
            'news' => [News::class, []],
            'magzines' => [Magazine::class, []],
            'member_feeds' => [MemberFeed::class, ['comments', 'attachments', 'likesAndDislikes']],
        ];

        $data = [];

        foreach ($models as $key => [$modelClass, $relations]) {
            $data[$key] = $modelClass::with($relations ?? null)->get();
        }

        return response()->json([
            'success' => true,
            'data' => $data,
        ]);
    }


}
