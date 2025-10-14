<?php

namespace App\Filament\Resources;

use App\Filament\Resources\MemberResource\Pages;
use App\Models\Member;
use Filament\Forms;
use Filament\Forms\Form;
use Filament\Resources\Resource;
use Filament\Tables;
use Filament\Tables\Table;
use Filament\Forms\Components\TextInput;
use Filament\Forms\Components\Textarea;
use Filament\Forms\Components\Grid;
use Filament\Forms\Components\Select;
use Filament\Forms\Components\Toggle;
use Filament\Forms\Components\FileUpload;
use Filament\Forms\Components\Section;
use App\Enums\OrganizationBusiness;
use App\Enums\PaymentMethods;
use App\Enums\MemberStatus;
use Filament\Tables\Columns\TextColumn;
use Filament\Forms\Components\Group;
use App\Enums\OrganizationStatus;
use Filament\Tables\Columns\BadgeColumn;
use Filament\Forms\Components\Radio;
use App\Enums\PaymentTimeline;
use App\Filament\Traits\HasResourcePermissions;
use Filament\Forms\Components\Actions\Action;
use Filament\Tables\Actions\ViewAction;
use Filament\Tables\Columns\ImageColumn;
use Filament\Tables\Columns\Layout\Stack;
use Filament\Tables\Columns\SelectColumn;
use Filament\Tables\Columns\ToggleColumn;
use Filament\Tables\Filters\Filter;
use Filament\Tables\Filters\SelectFilter;
use Illuminate\Database\Eloquent\Model;

class MemberResource extends Resource
{
    protected static ?string $model = Member::class;

    protected static ?string $navigationIcon = 'heroicon-o-users';

    protected static ?string $navigationLabel = 'Members';

    protected static ?string $navigationGroup = 'Membership';

    public static function form(Form $form): Form
    {
        return $form
            ->schema([
                Section::make('Member')
                    ->extraAttributes([
                        'class' => 'filament-custom-section'
                    ])
                    ->schema([
                        Grid::make(2)->schema([
                            TextInput::make('full_name')->required(),
                            TextInput::make('email')->email()->required()->unique(ignoreRecord: true),
                            TextInput::make('contact')->required(),
                            // Select::make('country_id')
                            //     ->label('Country')
                            //     ->relationship('country', 'name')
                            //     ->searchable()
                            //     ->reactive()->preload()
                            //     ->afterStateUpdated(fn(callable $set) => $set('state_id', null))->required(),

                            // Select::make('state_id')
                            //     ->label('State')
                            //     ->relationship('state', 'name', modifyQueryUsing: function ($query, callable $get) {
                            //         if ($countryId = $get('country_id')) {
                            //             $query->where('country_id', $countryId);
                            //         }
                            //     })
                            //     ->searchable()
                            //     ->reactive()
                            //     ->preload()
                            //     ->afterStateUpdated(fn(callable $set) => $set('city_id', null))->required(),

                            // Select::make('city_id')
                            //     ->label('City')
                            //     ->relationship('city', 'name', modifyQueryUsing: function ($query, callable $get) {
                            //         if ($stateId = $get('state_id')) {
                            //             $query->where('state_id', $stateId);
                            //         }
                            //     })
                            //     ->searchable()->preload()->required(),


                            TextInput::make('designation')->required(),
                            TextInput::make('zip_code')->required(),

                            Select::make('plan_id')
                                ->label('Select Plan')
                                ->relationship('plan', 'name') // Assuming you have a plan relationship
                                ->searchable()
                                ->preload()
                                ->reactive() // 👈 Make this field reactive
                                ->afterStateUpdated(function ($state, callable $set) {
                                    if ($state) {
                                        // Your custom query to get the roles for the selected plan
                                        $plan = \App\Models\Plan::with('roles')->find($state);

                                        if ($plan && $plan->roles->isNotEmpty()) {
                                            // Set the 'roles' field to the roles from the plan
                                            $set('roles', $plan->roles->pluck('name')->toArray());
                                        } else {
                                            // If the plan has no roles, clear the roles field
                                            $set('roles', []);
                                        }
                                    }
                                }),

                            Select::make('payment_method')
                                ->label('Payment Method')
                                ->options(PaymentMethods::options())
                                ->required(),

                            Select::make('roles')
                                ->label('Assign Roles')
                                ->multiple()
                                ->preload()
                                ->searchable()
                                ->options(\Spatie\Permission\Models\Role::pluck('name', 'name')->toArray())
                                ->afterStateHydrated(function ($set, $record, callable $get) {
                                    if ($record && $record->user) {
                                        // In edit mode, populate with the user's current roles
                                        $set('roles', $record->user->roles->pluck('name')->toArray());
                                    } elseif ($planId = $get('plan_id')) {
                                        // In create mode, if a plan is already selected, set roles from that plan
                                        $plan = \App\Models\Plan::with('roles')->find($planId);
                                        if ($plan && $plan->roles->isNotEmpty()) {
                                            $set('roles', $plan->roles->pluck('name')->toArray());
                                        }
                                    }
                                }),


                            FileUpload::make('dp')->label('Profile Picture')
                                ->image()
                                ->directory('members')->columnSpanFull(),
                            TextInput::make('password')
                                ->password()
                                ->label('Update Password')
                                ->minLength(8)
                                ->hiddenOn('create')
                                ->dehydrateStateUsing(fn($state) => !empty($state) ? bcrypt($state) : null) // only hash when value given
                                ->afterStateHydrated(fn($set) => $set('password', '')) // field always empty when form loads
                                ->suffixActions([
                                    Action::make('toggle')
                                        ->icon('heroicon-m-eye')
                                        ->tooltip('Show / Hide Password')
                                        ->extraAttributes(['class' => 'eye-toggle']),
                                    Action::make('copy')
                                        ->icon('heroicon-m-clipboard')
                                        ->tooltip('Copy Password')
                                        ->extraAttributes(['class' => 'copy-password']),
                                ])
                        ]),

                        # ENTERPRISE
                        // Group::make([
                        //     Grid::make(2)->schema([
                        //         TextInput::make('organization')->required(),
                        //         Select::make('organization_business')
                        //             ->label('Organization Business')
                        //             ->options(OrganizationBusiness::options())
                        //             ->required(),
                        //         TextInput::make('organization_contact'),
                        //         TextInput::make('second_member_name'),
                        //         TextInput::make('second_member_contact'),
                        //         TextInput::make('second_member_designation'),
                        //         TextInput::make('second_member_email'),
                        //         Select::make('organization_status')
                        //             ->options(OrganizationStatus::options()),
                        //         TextInput::make('number_of_employees')
                        //             ->numeric()
                        //             ->default(1)
                        //             ->dehydrateStateUsing(fn($state) => $state ?: 1),
                        //         TextInput::make('mailing_address'),
                        //         Textarea::make('expectation'),
                        //         Toggle::make('payment_term'),
                        //     ]),
                        // ])
                        //     ->relationship('enterpriseDetails') // ✅ relation bind
                        //     // ->visible(function (callable $get) {
                        //     //         dump($get('plan_id')); // Browser me dump hoga (agar debugging mode on hai)
                        //     //         return false;
                        //     //     }),
                        //     ->visible(fn(callable $get) => optional(\App\Models\Plan::find($get('plan_id')))->slug === 'enterprise'),

                        // # GOLD
                        // Group::make([
                        //     Grid::make(2)->schema([
                        //         TextInput::make('organization')->required(),
                        //         Select::make('organization_status')->options(OrganizationStatus::options()),
                        //         TextInput::make('number_of_employees')
                        //             ->numeric()
                        //             ->default(1)
                        //             ->dehydrateStateUsing(fn($state) => $state ?: 1),
                        //         Select::make('gender')->options(['male' => 'Male', 'female' => 'Female', 'other' => 'Other']),
                        //         TextInput::make('qualification'),
                        //         // TextInput::make('annual_membership_fee')->numeric()->prefix('$'),
                        //         // Toggle::make('registration_agreement')->required(),
                        //         Select::make('payment_timeline')->options(PaymentTimeline::options())->required(),
                        //         Select::make('organization_business')
                        //             ->label('Organization Business')
                        //             ->options(OrganizationBusiness::options())
                        //             ->required(),
                        //         Textarea::make('expectation'),
                        //     ]),
                        // ])
                        //     ->relationship('goldDetails') // ✅ relation bind
                        //     ->visible(fn(callable $get) => optional(\App\Models\Plan::find($get('plan_id')))->slug === 'gold'),

                        // # SILVER
                        // Group::make([
                        //     Grid::make(2)->schema([
                        //         TextInput::make('organization')->required(),
                        //         Select::make('organization_status')->options(OrganizationStatus::options()),
                        //         TextInput::make('number_of_employees')
                        //             ->numeric()
                        //             ->default(1)
                        //             ->dehydrateStateUsing(fn($state) => $state ?: 1),
                        //         Select::make('gender')->options(['male' => 'Male', 'female' => 'Female', 'other' => 'Other']),
                        //         TextInput::make('qualification'),
                        //         // TextInput::make('annual_membership_fee')->numeric()->prefix('$'),
                        //         // Toggle::make('registration_agreement')->required(),
                        //         Select::make('payment_timeline')->options(PaymentTimeline::options())->required(),
                        //         Select::make('organization_business')
                        //             ->label('Organization Business')
                        //             ->options(OrganizationBusiness::options())
                        //             ->required(),
                        //         Textarea::make('expectation'),
                        //     ]),
                        // ])
                        //     ->relationship('silverDetails') // ✅ relation bind
                        //     ->visible(fn(callable $get) => optional(\App\Models\Plan::find($get('plan_id')))->slug === 'silver'),
                    ])
            ]);
    }


    public static function table(Table $table): Table
    {
        return $table
            ->modifyQueryUsing(function ($query) {

                $query->with(['plan', 'user.roles', 'country', 'state', 'city']);

                if (!auth()->user()?->hasAnyRole(['super-admin', 'admin'])) {
                    $query->where('status', 'active');
                }
            })
            ->recordUrl(false)
            ->contentGrid([
                'md' => 3,
                'lg' => 4,
            ])
            ->columns([
                Stack::make([
                    Stack::make([
                        ImageColumn::make('dp')->url(null)
                            ->extraImgAttributes(['class' => 'rounded-t-md !h-40 !w-40'])
                            ->defaultImageUrl(asset('icons/no_icon.svg')),
                    ])->extraAttributes(['class' => 'mb-4 text-center']),

                    Stack::make([
                        TextColumn::make('full_name')
                            ->url(null)
                            ->prefix('Name: ')
                            ->searchable()
                            ->limit(50)
                            ->extraAttributes(['class' => 'font-semibold']),

                        TextColumn::make('email')
                            ->url(null)
                            ->prefix('Email: ')
                            ->searchable()
                            ->extraAttributes(['class' => 'mb-1 mt-1']),

                        TextColumn::make('contact')
                            ->url(null)
                            ->prefix('Contact: ')
                            ->extraAttributes(['class' => 'mb-1']),

                        BadgeColumn::make('plan.name')
                            ->url(null)
                            ->prefix('Plan: ')
                            ->colors([
                                'primary' => 'Enterprise',
                                'danger' => 'Gold',
                                'warning' => 'Silver',
                            ])
                            ->extraAttributes(['class' => 'mb-1']),

                        TextColumn::make('roles')
                            ->url(null)
                            ->getStateUsing(
                                fn($record) =>
                                $record->user?->roles->pluck('name')->unique()->toArray() ?? []
                            )
                            ->badge()
                            ->separator(', ')
                            ->color(fn($state) => match (true) {
                                str_contains($state, 'enterprise') => 'success',
                                str_contains($state, 'gold') => 'danger',
                                str_contains($state, 'silver') => 'warning',
                                str_contains($state, 'admin') => 'info',
                                default => 'gray',
                            }),

                        SelectColumn::make('status')
                            ->url(null)
                            ->options(MemberStatus::options())
                            ->extraAttributes(['class' => 'mb-2 mt-2'])
                            ->visible(fn($record) => auth()->user()?->hasAnyRole(['super-admin', 'moderator', 'admin'])),
                    ]),
                ])->extraAttributes([
                            'class' => 'p-1 shadow-sm bg-white',
                        ]),
            ])
            ->actions([
                ViewAction::make()
                    ->icon('heroicon-o-eye')
                    ->label('')
                    ->iconButton(),

                Tables\Actions\EditAction::make()
                    ->icon('heroicon-o-pencil')
                    ->label('')
                    ->iconButton(),

                Tables\Actions\DeleteAction::make()
                    ->icon('heroicon-o-trash')
                    ->iconButton()
                    ->after(function ($record) {
                        if (method_exists($record, 'user') && $record->user) {
                            try {
                                $record->user->delete();
                            } catch (\Exception $e) {
                                // Log the exception as needed
                                \Log::error('Failed to delete user for member ' . $record->id . ': ' . $e->getMessage());
                            }
                        }
                    }),
            ], position: \Filament\Tables\Enums\ActionsPosition::AfterCells)
            ->filters([
                SelectFilter::make('status')
                    ->options(MemberStatus::options())
                    ->label('Status'),

                SelectFilter::make('country_id')
                    ->label('Country')
                    ->relationship('country', 'name')
                    ->searchable(), // removed preload

                SelectFilter::make('state_id')
                    ->label('State')
                    ->relationship('state', 'name')
                    ->searchable(), // removed preload

                SelectFilter::make('city_id')
                    ->label('City')
                    ->relationship('city', 'name')
                    ->searchable(), // removed preload
            ]);
    }

    // public static function getRelations(): array
    // {
    //     return [];
    // }

    public static function getPages(): array
    {
        return [
            'index' => Pages\ListMembers::route('/'),
            'create' => Pages\CreateMember::route('/create'),
            'view' => Pages\ViewMember::route('/{record}'),
            'edit' => Pages\EditMember::route('/{record}/edit'),
        ];
    }
}
