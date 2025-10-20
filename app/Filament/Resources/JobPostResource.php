<?php

namespace App\Filament\Resources;

use App\Filament\Resources\JobPostResource\Pages;
use App\Filament\Resources\JobPostResource\RelationManagers;
use App\Models\JobPost;
use Filament\Forms;
use Filament\Forms\Form;
use Filament\Resources\Resource;
use Filament\Tables;
use Filament\Tables\Table;
use Illuminate\Database\Eloquent\Builder;
use Illuminate\Database\Eloquent\SoftDeletingScope;
use Filament\Forms\Components\Section;
use App\Filament\Traits\HasResourcePermissions;
use Filament\Forms\Components\TextInput;
use Filament\Tables\columns\TextColumn;
use Filament\Tables\Columns\ImageColumn;
use Filament\Tables\Columns\ToggleColumn;
use Filament\Tables\Columns\BadgeColumn;
use Filament\Forms\Components\Grid;
use App\Enums\JobType;
use Filament\Forms\Components\Card;
use Filament\Forms\Components\DatePicker;
use Filament\Forms\Components\FileUpload;
use Filament\Forms\Components\Select;
use Filament\Forms\Components\Tabs;
use Filament\Forms\Components\Tabs\Tab;
use Filament\Forms\Components\Textarea;
use Filament\Tables\Columns\Layout\Stack;
use Filament\Tables\Columns\SelectColumn;
use Filament\Tables\Columns\ViewColumn;
use Illuminate\Support\Facades\Mail;
use Filament\Tables\Actions\Action;
use Illuminate\Support\Facades\Auth;
use App\Services\CvMailerService;
use Filament\Notifications\Notification;
use Livewire\Component;

class JobPostResource extends Resource
{
    protected static ?string $model = JobPost::class;
    protected static ?string $navigationGroup = 'Media Center';
    protected static ?string $navigationIcon = 'heroicon-o-rectangle-stack';

    public static function form(Form $form): Form
    {
        return $form
            ->schema([
                Section::make('Job Post')->extraAttributes([
                    'class' => 'filament-custom-section',
                ])->schema([
                            Grid::make(2)->schema([
                                TextInput::make('title')
                                    ->required()
                                    ->maxLength(255),
                                TextInput::make('company'),
                                TextInput::make('designation'),
                                Select::make('job_type')->label('Job Type')->options(JobType::options()),
                                TextInput::make('salary')->label('Salary')->numeric(),
                                DatePicker::make('due_date')->label('Due Date'),
                                TextInput::make('link')->url()->nullable(),
                                Select::make('website_preview')
                                    ->options([
                                        1 => 'Yes',
                                        0 => 'No',
                                    ])
                                    ->default(1),
                                FileUpload::make('job_image')
                                    ->label('Image')
                                    ->image()->disk('public')->directory('job_posts')->visibility('public'),

                                TextInput::make('address')->columnSpanFull(),
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
                        ])
            ]);

    }

    public static function table(Table $table): Table
    {
        return $table
            ->modifyQueryUsing(function ($query) {
                // if NOT super-admin, only show active events
                if (!auth()->user()?->hasRole(['super-admin', 'admin'])) {
                    $query->where('job_status', 1);
                }
            })
            ->recordUrl(false)
            ->contentGrid([
                'md' => 3,
                'lg' => 4,
            ])
            ->columns([
                // ViewColumn::make('job_card')
                //     ->view('filament.tables.columns.job-cards-column'),
                // ToggleColumn::make('job_status')
                // ->label('Approved')
                // ->getStateUsing(fn ($record) => $record->job_status == 1)
                // ->afterStateUpdated(function ($state, $record) {
                //     $record->update([
                //         'job_status' => $state ? 1 : 0,
                //     ]);
                // })
                // ->visible(fn ($record) => auth()->user()?->hasAnyRole(['super-admin','moderator']))
                // ->onColor('success')
                // ->offColor('danger')
                Tables\Columns\Layout\Stack::make([
                    Stack::make([
                        ImageColumn::make('job_image')
                            ->extraImgAttributes(['class' => 'rounded-t-md !h-40 !w-40'])
                            ->defaultImageUrl(asset('icons/no_icon.svg')), // 👈 fallback image
                    ])->extraAttributes(['class' => 'mb-4 text-center']),

                    Stack::make([
                        Tables\Columns\TextColumn::make('title')->prefix('Title: ')->limit(50)->extraAttributes(['class' => 'font-semibold']),
                        Tables\Columns\TextColumn::make('company')->prefix('Company: ')->extraAttributes(['class' => 'mb-1 mt-1']),
                        Tables\Columns\TextColumn::make('link')->extraAttributes(['class' => 'mb-1']),
                        SelectColumn::make('job_status')
                            ->options([
                                1 => 'Active',
                                0 => 'Inactive',
                            ])
                            ->default(0) // default to Inactive
                            ->selectablePlaceholder(false) // prevents empty option
                            ->visible(fn($record) => auth()->user()?->hasAnyRole(['super-admin', 'moderator']))->extraAttributes(['class' => 'my-1'])
                        // ToggleColumn::make('job_status')
                        //     ->label('Approved')
                        //     ->getStateUsing(fn($record) => $record->job_status == 1)
                        //     ->afterStateUpdated(function ($state, $record) {
                        //         $record->update([
                        //             'job_status' => $state ? 1 : 0,
                        //         ]);
                        //     })
                        //     ->visible(fn($record) => auth()->user()?->hasAnyRole(['super-admin', 'moderator']))
                        //     ->onColor('success')
                        //     ->offColor('danger')->extraAttributes(['class' => 'mt-2'])
                    ]),

                ])->extraAttributes([
                            'class' => 'p-1 shadow-sm bg-white',
                        ]),
            ])

            ->filters([
                //
            ])
            ->actions([
                Tables\Actions\ViewAction::make()->icon('heroicon-o-eye')->label('')->iconButton(),
                Tables\Actions\EditAction::make()->icon('heroicon-o-pencil')->label('')->iconButton(),
                Tables\Actions\DeleteAction::make()->icon('heroicon-o-trash')->label('')->iconButton(),

                Action::make('applyNow')
                    ->label('Apply Now')
                    ->icon('heroicon-o-briefcase')
                    ->button()
                    ->visible(fn() => Auth::user()->hasRole('member'))
                    ->modal('applyNowModal')
                    ->modalHeading('Upload your CV')
                    ->modalWidth('md')
                    ->form([
                        FileUpload::make('cv')
                            ->label('Upload your CV')
                            ->required()
                            ->disk('public')                    
                            ->directory('member_cv')           
                            ->acceptedFileTypes([
                                'application/pdf',
                                'application/msword',
                                'application/vnd.openxmlformats-officedocument.wordprocessingml.document',
                            ])

                    ])
                    ->action(function (array $data, JobPost $record, $livewire) {
                        $loggedInUser = auth()->user();

                        $cvPath = $data['cv'];
                        $fileName = basename($cvPath);
                        $absolutePath = storage_path('app/public/' . $cvPath);

                        $jobOwnerEmail = $record->user?->email;
                        // dd($jobOwnerEmail);
                        if ($jobOwnerEmail) {
                            (new CvMailerService())->sendCv(
                                $absolutePath,
                                $fileName,
                                $jobOwnerEmail
                            );
                        } else {
                            return null;
                        }

                        \App\Models\JobMembers::create([
                            'members_id' => $loggedInUser->id,
                            'jobs_id' => $record->id,
                            'cv_upload' => $cvPath,
                        ]);

                        Notification::make()
                            ->title('Applied successfully')
                            ->success()
                            ->send();


                    })



            ])
            ->bulkActions([
                // Tables\Actions\BulkActionGroup::make([
                //     Tables\Actions\DeleteBulkAction::make(),
                // ]),
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
            'index' => Pages\ListJobPosts::route('/'),
            'create' => Pages\CreateJobPost::route('/create'),
            'edit' => Pages\EditJobPost::route('/{record}/edit'),
            'view' => Pages\ViewJobPost::route('/{record}'),
        ];
    }
    public function submit(): void
    {
        JobPost::create($this->form->getState());
        $this->form->fill(); // reset after save
    }
}
