<?php
namespace App\Filament\Resources\MemberFeedResource\Api\Handlers;

use Illuminate\Http\Request;
use Rupadana\ApiService\Http\Handlers;
use App\Filament\Resources\MemberFeedResource;
use App\Filament\Resources\MemberFeedResource\Api\Requests\UpdateMemberFeedRequest;

class UpdateHandler extends Handlers
{
    public static string|null $uri = '/{id}';
    public static string|null $resource = MemberFeedResource::class;
    public static bool $public = true;
    public static function getMethod()
    {
        return Handlers::PUT;
    }

    public static function getModel()
    {
        return static::$resource::getModel();
    }


    /**
     * Update MemberFeed
     *
     * @param UpdateMemberFeedRequest $request
     * @return \Illuminate\Http\JsonResponse
     */
    public function handler(UpdateMemberFeedRequest $request)
    {
        $id = $request->route('id');

        $model = static::getModel()::find($id);

        if (!$model) {
            return static::sendNotFoundResponse();
        }


        $model->fill($request->except('attachment_path'));
        $model->save();

        $files = $request->file('attachment_path');

        if ($files) {
            foreach ($model->attachments as $attachment) {
                if (\Storage::disk('public')->exists($attachment->attachment_path)) {
                    \Storage::disk('public')->delete($attachment->attachment_path);
                }

                $attachment->delete();
            }


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
            "Successfully updated MemberFeed with new attachments"
        );
    }

}