<?php

namespace App\Http\Controllers\Api;
use App\Models\MemberFeed;
use App\Http\Controllers\Controller;
use Illuminate\Http\Request;

class MemberFeedController extends Controller
{
    public function index()
    {
        return MemberFeed::where('public', 1)->latest()->get();
    }

    public function store(Request $request)
    {
        $data = $request->validate([
            'content' => 'required|string',
            'public' => 'required|boolean',
        ]);

        $feed = MemberFeed::create($data);

        return response()->json($feed, 201);
    }

    public function show(MemberFeed $memberFeed)
    {
        return response()->json($memberFeed);
    }

    public function update(Request $request, MemberFeed $memberFeed)
    {
        $data = $request->validate([
            'content' => 'sometimes|required|string',
            'public' => 'sometimes|required|boolean',
        ]);

        $memberFeed->update($data);

        return response()->json($memberFeed);
    }

    public function destroy(MemberFeed $memberFeed)
    {
        $memberFeed->delete();

        return response()->json(null, 204);
    }
}
