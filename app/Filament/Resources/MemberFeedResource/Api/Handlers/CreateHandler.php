<?php
namespace App\Filament\Resources\MemberFeedResource\Api\Handlers;

use Illuminate\Http\Request;
use Rupadana\ApiService\Http\Handlers;
use App\Filament\Resources\MemberFeedResource;
use App\Filament\Resources\MemberFeedResource\Api\Requests\CreateMemberFeedRequest;

class CreateHandler extends Handlers
{
    public static string|null $uri = '/';
    public static string|null $resource = MemberFeedResource::class;
    public static bool $public = true;
    public static function getMethod()
    {
        return Handlers::POST;
    }

    public static function getModel()
    {
        return static::$resource::getModel();
    }

    /**
     * Create MemberFeed
     *
     * @param CreateMemberFeedRequest $request
     * @return \Illuminate\Http\JsonResponse
     */
    public function handler(CreateMemberFeedRequest $request)
    {
        $model = new (static::getModel());

        $model->fill($request->except('attachment_path'));
        $model->save();

        $files = $request->file('attachment_path');

        if ($files) {
            $files = is_array($files) ? $files : [$files];

            foreach ($files as $file) {
                $path = $file->store('members', 'public');

                $model->attachments()->create([
                    'attachment_path' => $path,
                ]);
            }
        }

        return static::sendSuccessResponse(
            $model->load('attachments'),
            "Successfully created MemberFeed with attachments"
        );
    }



}