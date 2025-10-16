<?php

namespace App\Filament\Resources;

use App\Filament\Resources\MemberFeedResource\Pages;
use App\Filament\Resources\MemberFeedResource\RelationManagers;
use App\Models\MemberFeed;
use Filament\Forms;
use Filament\Forms\Form;
use Filament\Resources\Resource;
use Filament\Tables;
use Filament\Tables\Table;
use Illuminate\Database\Eloquent\Builder;
use Illuminate\Database\Eloquent\SoftDeletingScope;
use Filament\Forms\Components\TextInput;
use Filament\Forms\Components\Textarea;
use Filament\Forms\Components\Toggle;
use Filament\Forms\Components\Section;
use Filament\Forms\Components\HasManyRepeater;
use Filament\Forms\Components\Group;
use Filament\Forms\Components\FileUpload;
use Filament\Tables\Columns\TextColumn;
use Filament\Tables\Columns\Layout\Stack;
use Filament\Tables\Columns\SelectColumn;
use Filament\Tables\Columns\BadgeColumn;
use Filament\Tables\Actions\ViewAction;
use Filament\Forms\Components\Select;
use Filament\Tables\Actions\EditAction;
use Filament\Tables\Actions\DeleteAction;
use Filament\Tables\Enums\ActionsPosition;
use Filament\Tables\Columns\ViewColumn;
class MemberFeedResource extends Resource
{
    protected static ?string $model = MemberFeed::class;

    protected static ?string $navigationIcon = 'heroicon-o-rectangle-stack';





    public static function form(Form $form): Form
    {
        return $form
            ->schema([
                Section::make('Meta Info')
                    ->schema([
                        // Section::make('Meta Fields')
                        //     ->relationship('meta')
                        //     ->schema([
                        //         // TextInput::make('meta_title')
                        //         //     ->label('Meta Title')
                        //         //     ->required(),

                        //         // \Filament\Forms\Components\RichEditor::make('meta_description')
                        //         //     ->label('Meta Description')
                        //         //     ->toolbarButtons([
                        //         //         'blockquote',
                        //         //         'bold',
                        //         //         'bulletList',
                        //         //         'code',
                        //         //         'codeBlock',
                        //         //         'h1',
                        //         //         'h2',
                        //         //         'h3',
                        //         //         'italic',
                        //         //         'link',
                        //         //         'orderedList',
                        //         //         'redo',
                        //         //         'strike',
                        //         //         'underline',
                        //         //         'undo',
                        //         //     ])
                        //         //     ->columnSpanFull(),
                        //     ]),

                        \Filament\Forms\Components\RichEditor::make('content')
                            ->label('Post')
                            ->fileAttachmentsDisk('public')
                            ->fileAttachmentsDirectory('feeds')
                            ->fileAttachmentsVisibility('public')
                            ->toolbarButtons([
                                'blockquote',
                                'bold',
                                'bulletList',
                                'code',
                                'codeBlock',
                                'h1',
                                'h2',
                                'h3',
                                'italic',
                                'link',
                                'orderedList',
                                'redo',
                                'strike',
                                'underline',
                                'undo',
                            ])
                            ->columnSpanFull(),

                        HasManyRepeater::make('attachments')
                            ->relationship('attachments')
                            ->schema([


                                FileUpload::make('attachment_path')
                                    ->label('Image')
                                    ->image()
                                    ->disk('public')
                                    ->directory('members')
                                    ->columnSpanFull(),
                            ])
                            ->defaultItems(0)
                            ->addActionLabel('Add Attachment')
                            ->label(''),


                        Select::make('public')

                            ->options([
                                1 => 'Public',
                                0 => 'Private',
                            ])
                            ->extraAttributes(['class' => 'mr-10'])
                    ]),
            ]);
    }






    public static function getEloquentQuery(): Builder
    {
        $query = parent::getEloquentQuery();

        $user = auth()->user();

        return $query->when($user, function ($query) use ($user) {
            return $query->where(function ($query) use ($user) {
                $query->where('public', 1) 
                    ->orWhere('user_id', $user->id); 
            });
        });
    }




    public static function table(Table $table): Table
    {
        return $table
            ->recordUrl(false)
            ->contentGrid([
                'md' => 1,
                'lg' => 1,
            ])
            ->columns([
                Stack::make([
                    ViewColumn::make('content')
                        ->view('filament.components.forum-design'),
                ])->extraAttributes([
                            'class' => 'p-1 shadow-sm bg-white',
                        ]),
            ])
            ->defaultSort('created_at', 'desc')
            ->actions([]);
    }



    public static function getRelations(): array
    {
        return [
            //
        ];
    }

    public static function getPages(): array
    {
        return [
            'index' => Pages\ListMemberFeeds::route('/'),
            'create' => Pages\CreateMemberFeed::route('/create'),
            'edit' => Pages\EditMemberFeed::route('/{record}/edit'),
        ];
    }
}












//  ->formatStateUsing(function ($state, $record) {
//                         $user = $record->user?->name ?? 'Anonymous';
//                         $createdAt = $record->created_at?->format('jS M Y, h:i A') ?? '';
//                         $likes = $record->likes ?? 0;
//                         $commentsCount = $record->feed_comments?->count() ?? 0;

//                         $html = '<div x-data="{ showComments: false }" class="w-full p-4 border rounded-md bg-white shadow space-y-3">';

//                         // Header
//                         $html .= '<div class="flex items-center justify-between">';
//                         $html .= '<div class="text-sm font-semibold text-gray-800">' . e($user) . '</div>';
//                         $html .= '<div class="text-xs text-gray-500">' . e($createdAt) . '</div>';
//                         $html .= '</div>';

//                         // Content
//                         $html .= '<div class="text-gray-800 text-sm whitespace-pre-line">' . nl2br(e($record->content)) . '</div>';

//                         // Like / Comment / Share (Icons)
//                         $html .= '<div class="flex items-center justify-start space-x-6 text-sm text-gray-600 border-t pt-2 mt-2">';

//                         $html .= '<div class="flex items-center space-x-1">';
//                         $html .= '<svg class="w-4 h-4 text-red-500" fill="currentColor" viewBox="0 0 20 20"><path d="M3.172 5.172a4 4 0 015.656 0L10 6.343l1.172-1.171a4 4 0 115.656 5.656L10 18.657l-6.828-6.829a4 4 0 010-5.656z"/></svg>';
//                         $html .= '<span>' . $likes . '</span>';
//                         $html .= '</div>';

//                         $html .= '<div class="flex items-center space-x-1 cursor-pointer" @click="showComments = !showComments">';
//                         $html .= '<svg class="w-4 h-4 text-blue-500" fill="none" stroke="currentColor" viewBox="0 0 24 24"><path d="M8 10h.01M12 10h.01M16 10h.01M21 12c0 4.418-4.03 8-9 8a9.77 9.77 0 01-4.39-1.01L3 21l1.37-3.64A7.992 7.992 0 013 12c0-4.418 4.03-8 9-8s9 3.582 9 8z"/></svg>';
//                         $html .= '<span>' . $commentsCount . '</span>';
//                         $html .= '</div>';

//                         $html .= '<div class="flex items-center space-x-1">';
//                         $html .= '<svg class="w-4 h-4 text-gray-500" fill="none" stroke="currentColor" viewBox="0 0 24 24"><path d="M15 8h.01M12 12h.01M9 16h.01M21 12a9 9 0 11-18 0 9 9 0 0118 0z"/></svg>';
//                         $html .= '<span>Share</span>';
//                         $html .= '</div>';

//                         $html .= '</div>'; // end icons row

//                         // Comment Toggle Box
//                         $html .= '<div x-show="showComments" class="mt-4 space-y-3">';

//                         $html .= '<textarea rows="2" class="w-full border rounded-md p-2 text-sm" placeholder="Write a comment..."></textarea>';
//                         $html .= '<button class="mt-1 px-3 py-1 bg-blue-500 text-white text-xs rounded hover:bg-blue-600">Post Comment</button>';

//                         // Existing Comments
//                         foreach ($record->feed_comments ?? [] as $reply) {
//                             $replyUser = $reply->user?->name ?? 'User';
//                             $replyTime = $reply->created_at?->diffForHumans() ?? '';
//                             $replyContent = e($reply->content ?? '');

//                             $html .= '<div class="mt-3 text-sm bg-gray-50 p-2 rounded-md">';
//                             $html .= '<div class="flex justify-between text-xs text-gray-500">';
//                             $html .= '<span>' . $replyUser . '</span>';
//                             $html .= '<span>' . $replyTime . '</span>';
//                             $html .= '</div>';
//                             $html .= '<div class="mt-1 text-gray-800">' . $replyContent . '</div>';
//                             $html .= '</div>';
//                         }

//                         $html .= '</div>'; // end comment toggle
//                         $html .= '</div>'; // end main wrapper

//                         return new  $html;
//                     })