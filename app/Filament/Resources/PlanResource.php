<?php

namespace App\Filament\Resources;

use App\Filament\Resources\PlanResource\Pages;
use App\Filament\Resources\PlanResource\RelationManagers;
use App\Models\Plan;
use Filament\Forms;
use Filament\Forms\Form;
use Filament\Resources\Resource;
use Filament\Tables;
use Filament\Tables\Table;
use Illuminate\Database\Eloquent\Builder;
use Illuminate\Database\Eloquent\SoftDeletingScope;
use App\Filament\Resources\PlanResource\RelationManagers\PlanFeaturesRelationManager;
use App\Enums\PlanInterval;
use Filament\Forms\Components\Grid;
use Filament\Forms\Components\TextInput;
use Filament\Forms\Components\Textarea;
use Filament\Forms\Components\Select;
use Filament\Forms\Components\Toggle;
use Filament\Tables\Columns\TextColumn;
use Filament\Tables\Columns\BooleanColumn;
use Filament\Forms\Components\Section;
use Illuminate\Support\Str;
use Filament\Notifications\Notification;
use Filament\Forms\Set;
use App\Models\PlanFeature;
use Filament\Forms\Components\FileUpload;
use App\Filament\Traits\HasResourcePermissions;
use App\Enums\Currency;
use Filament\Tables\Columns\ViewColumn;
class PlanResource extends Resource
{
    protected static ?string $model = Plan::class;
    protected static ?string $navigationGroup = 'Membership';
    protected static ?string $navigationIcon = 'heroicon-o-credit-card';


    public static function form(Form $form): Form
    {
        return $form->schema([
            Section::make('Plan Details')->extraAttributes([
                'class' => 'filament-custom-section',
            ])->schema([
                        Grid::make(2)->schema([

                            TextInput::make('name')
                                ->required()
                                ->maxLength(255)
                                ->live(onBlur: true)
                                ->afterStateUpdated(fn(Set $set, ?string $state) => $set('slug', Str::slug($state))),

                            TextInput::make('slug')
                                ->maxLength(255)
                                // ->disabled() // This prevents users from editing the slug
                                ->unique(ignoreRecord: true),
                            // ->reactive()
                            // ->afterStateUpdated(function ($state, callable $set, callable $get) {
                            //     if (! $get('slug')) { // sirf tab slug banega jab empty ho
                            //         $set('slug', Str::slug($state));
                            //     }
                            // }),



                            Select::make('currency')->options(Currency::options())->required()->default('usd'),
                            TextInput::make('price')
                                ->required()
                                ->numeric()
                                ->reactive(), // taake discount field ke sath sync ho

                            TextInput::make('discount_price')
                                ->numeric()->required()
                                ->rule(function (callable $get) {
                                    return function (string $attribute, $value, \Closure $fail) use ($get) {
                                        if ($value && $value >= $get('price')) {
                                            $fail('Discount price must be less than price.');
                                        }
                                    };
                                }),
                            Select::make('interval')->options(PlanInterval::options())->required()->default('one_time'),
                            TextInput::make('interval_count')->numeric()->default(1),
                            Grid::make(2)->schema([
                                Toggle::make('is_active')->label('Active')->default(true)->extraAttributes(['class' => 'toggle--wide',]),
                                Toggle::make('is_featured')->label('Featured')->default(false)->extraAttributes(['class' => 'toggle--wide',]),
                            ]),
                            Select::make('roles')
                                ->label('Assign Roles')
                                ->relationship('roles', 'name')
                                ->multiple()
                                ->preload()->required()
                                ->searchable(),
                            FileUpload::make('image')->disk('public')->directory('plans_images')->image(),
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
                    ]),
        ]);
    }

    public static function table(Table $table): Table
    {
        return $table
            ->modifyQueryUsing(function ($query) {
                // if NOT super-admin, only show active events
                if (!auth()->user()?->hasRole(['super-admin', 'admin'])) {
                    // $query->where('is_active', 1);
                }
            })
            ->columns([

                TextColumn::make('name')->sortable()->searchable(),
                TextColumn::make('price')->sortable()->searchable(),
                TextColumn::make('currency'),

                TextColumn::make('interval')->searchable()
                    ->label('Interval')
                    ->formatStateUsing(fn($state) => $state instanceof \App\Enums\PlanInterval ? $state->label() : ucfirst((string) $state)),
                TextColumn::make('interval_count')->label('Interval Count'),

                BooleanColumn::make('is_active')->label('Active'),
                BooleanColumn::make('is_featured')->label('Featured'),
                ViewColumn::make('')->label('Payment')
                    ->view('filament.components.payment-button'),
            ])
            ->filters([
                //
            ])
            ->actions([
                Tables\Actions\EditAction::make(),
                Tables\Actions\DeleteAction::make(),

                Tables\Actions\ViewAction::make()
                    ->visible(fn() => auth()->user()?->hasRole(['super-admin', 'admin'])),

            ])
            ->bulkActions([
                Tables\Actions\BulkActionGroup::make([
                    Tables\Actions\DeleteBulkAction::make(),
                ]),
            ]);
    }











    public static function getRelations(): array
    {
        return [
            PlanFeaturesRelationManager::class
        ];
    }

    public static function getPages(): array
    {
        return [
            'index' => Pages\ListPlans::route('/'),
            'create' => Pages\CreatePlan::route('/create'),
            'edit' => Pages\EditPlan::route('/{record}/edit'),
        ];
    }
}
