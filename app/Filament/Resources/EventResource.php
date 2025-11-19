<?php

namespace App\Filament\Resources;

use App\Filament\Resources\EventResource\Pages;
use App\Filament\Resources\EventResource\RelationManagers;
use App\Models\Event;
use Filament\Forms;
use Filament\Forms\Form;
use Filament\Resources\Resource;
use Filament\Tables;
use Filament\Tables\Table;
use Illuminate\Database\Eloquent\Builder;
use Illuminate\Database\Eloquent\SoftDeletingScope;
use Filament\Forms\Components\TextInput;
use Filament\Forms\Components\Textarea;
use Filament\Forms\Components\Select;
use Filament\Forms\Components\DatePicker;
use Filament\Forms\Components\FileUpload;
use Filament\Tables\Columns\TextColumn;
use Filament\Tables\Columns\ImageColumn;
use Filament\Tables\Columns\ToggleColumn;
use Filament\Tables\Columns\BadgeColumn;
use App\Filament\Traits\HasResourcePermissions;
use Filament\Forms\Components\Section;
use Filament\Forms\Components\Grid;
use Filament\Tables\Columns\Layout\Stack;
use Filament\Infolists;
use Filament\Infolists\Infolist;
use Filament\Tables\Columns\SelectColumn;

use Filament\Tables\Actions\Action;
use Filament\Notifications\Notification;
use Illuminate\Support\Facades\Auth;
class EventResource extends Resource
{


    protected static ?string $model = Event::class;
    protected static ?string $navigationGroup = 'Media Center';
    protected static ?string $navigationIcon = 'heroicon-o-calendar-days';

    public static function form(Form $form): Form
    {
        return $form
            ->schema([
                Section::make('Event')->extraAttributes([
                    'class' => 'filament-custom-section',
                ])->schema([
                            Grid::make(2)->schema([

                                TextInput::make('title')
                                    ->required()
                                    ->maxLength(255),

                                DatePicker::make('start_date')->required(),
                                DatePicker::make('end_date'),

                                TextInput::make('link')
                                    ->url()
                                    ->nullable(),

                                Select::make('website_preview')
                                    ->options([
                                        1 => 'Yes',
                                        0 => 'No',
                                    ])
                                    ->default(1),

                                Forms\Components\Hidden::make('event_status')
                                    ->default('pending'),

                                FileUpload::make('event_image')
                                    ->directory('events')
                                    ->image()
                                    ->nullable(),
                                \Filament\Forms\Components\RichEditor::make('description')
                                    ->label('Description')
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
                            ])

                        ])->columns(2),

            ]);
    }

    public static function table(Table $table): Table
    {
        return $table
            ->modifyQueryUsing(function ($query) {
                if (!auth()->user()?->hasRole(['super-admin', 'admin'])) {
                    $query->where('event_type', 'public');
                }
            })
            ->recordUrl(false)
            ->contentGrid([
                'md' => 3,
                'lg' => 4,
            ])
            ->columns([

                Tables\Columns\Layout\Stack::make([
                    Stack::make([
                        ImageColumn::make('event_image')
                            ->extraImgAttributes(['class' => 'rounded-t-md !h-40 !w-40'])
                            ->defaultImageUrl(asset('icons/no_icon.svg')), // 👈 fallback image
                    ])->extraAttributes(['class' => 'mb-4 text-center']),

                    Stack::make([
                        Tables\Columns\TextColumn::make('title')->prefix('Title: ')->limit(50)->extraAttributes(['class' => 'font-semibold']),
                        Tables\Columns\TextColumn::make('end_date')->prefix('Event Date: ')->extraAttributes(['class' => 'mb-1 mt-1']),
                        Tables\Columns\TextColumn::make('link')->extraAttributes(['class' => 'mb-1']),
                        BadgeColumn::make('event_type')
                            ->colors([
                                'primary' => 'public',
                                'warning' => 'private',
                            ])->extraAttributes(['class' => 'mb-1']),
                        SelectColumn::make('event_status')
                            ->options([
                                1 => 'Active',
                                0 => 'Inactive',
                            ])
                            ->default(0) // default to Inactive
                            ->selectablePlaceholder(false) // prevents empty option
                            ->visible(fn($record) => auth()->user()?->hasAnyRole(['super-admin', 'moderator']))->extraAttributes(['class' => 'my-1'])
                        // ToggleColumn::make('event_status')
                        //     ->getStateUsing(fn($record) => $record->event_status == 1)
                        //     ->afterStateUpdated(function ($state, $record) {
                        //         $record->update([
                        //             'event_status' => $state ? 1 : 0,
                        //         ]);
                        //     })
                        //     ->visible(fn($record) => auth()->user()?->hasAnyRole(['super-admin', 'moderator']))
                        //     ->onColor('success')
                        //     ->offColor('danger'),
                    ]),

                ])->extraAttributes([
                            'class' => 'p-1 shadow-sm bg-white',
                        ]),

            ])
            ->filters([
                // Tables\Filters\SelectFilter::make('event_status')
                //     ->options([
                //         'upcoming' => 'Upcoming',
                //         'completed' => 'Completed',
                //         'cancelled' => 'Cancelled',
                //     ]),
                // Tables\Filters\SelectFilter::make('event_type')
                //     ->options([
                //         'public' => 'Public',
                //         'private' => 'Private',
                //     ]),
            ])
            ->actions([
                Tables\Actions\ViewAction::make()
                    ->icon('heroicon-o-eye')
                    ->label('')
                    ->iconButton(),

                Tables\Actions\EditAction::make()
                    ->icon('heroicon-o-pencil')
                    ->label('')
                    ->iconButton(),

                Tables\Actions\DeleteAction::make()
                    ->icon('heroicon-o-trash')
                    ->label('')
                    ->iconButton(),
                Action::make('applyNow')
                    ->label('Book a seat')
                    ->icon('heroicon-o-briefcase')
                    ->button()
                    ->visible(fn() => Auth::user()->hasRole('member'))
                    ->action(function (array $data, Event $record, $livewire) {

                        $loggedInUser = auth()->user();

                        $member = \App\Models\Member::where('user_id', $loggedInUser->id)->first();
                        if (!$member) {
                            return null;
                        } else {
                            $memberPlan = \App\Models\Plan::find($member->plan_id);

                            $deduction = match ($memberPlan->name) {
                                'Gold' => 5,
                                'Silver' => 3,
                                'Basic' => 3,
                                default => null,
                            };

                            if ($deduction === null) {
                                return response()->json([
                                    'success' => false,
                                    'message' => 'Plan not found.'
                                ]);
                            }

                            $currentCredits = $member->remaining_credits;
                            if ($currentCredits < $deduction) {

                                Notification::make()
                                    ->title('You dont have enough credits !')
                                    ->success()
                                    ->send();


                            } else {
                                \App\Models\EventMembers::create([
                                    'member_id' => $member->id,
                                    'event_id' => $record->id,
                                    'status' => null,
                                ]);


                                $member->update([
                                    'remaining_credits' => $currentCredits - $deduction,
                                ]);
                                Notification::make()
                                    ->title('Thanks for booking the seat')
                                    ->success()
                                    ->send();
                            }


                        }
                    })
            ])
            ->bulkActions([
                // Tables\Actions\DeleteBulkAction::make(),
            ]);
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
            'index' => Pages\ListEvents::route('/'),
            'create' => Pages\CreateEvent::route('/create'),
            'edit' => Pages\EditEvent::route('/{record}/edit'),
            'view' => Pages\ViewEvent::route('/{record}'),
        ];
    }
    // public static function infolist(Infolist $infolist): Infolist
    // {
    //     return $infolist
    //         ->schema([
    //             Infolists\Components\TextEntry::make('title'),
    //             Infolists\Components\TextEntry::make('description'),
    //         ]);
    // }
}
