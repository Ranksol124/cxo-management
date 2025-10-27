<?php

namespace App\Filament\Resources;

use App\Filament\Resources\JobContentResource\Pages;
use App\Models\JobMembers;
use App\Enums\NewsType;
use Filament\Forms;
use Filament\Forms\Form;
use Filament\Forms\Components\Section;
use Filament\Forms\Components\Select;
use Filament\Forms\Components\Textarea;
use Filament\Forms\Components\TextInput;
use Filament\Resources\Resource;
use Filament\Tables;
use Filament\Tables\Table;
use Filament\Tables\Columns\TextColumn;
use Filament\Tables\Columns\ImageColumn;
use Filament\Tables\Columns\IconColumn;
use Filament\Tables\Columns\TextInputColumn;
use Filament\Tables\Columns\SpatieMediaLibraryImageColumn;
use Filament\Tables\Columns\ViewColumn;
use Filament\Tables\Columns\BadgeColumn;
use Filament\Tables\Columns\SelectColumn;
use Filament\Tables\Columns\ImageColumn as FileColumn;
use Filament\Tables\Columns\ToggleColumn;
use Filament\Tables\Columns\CheckboxColumn;
use Filament\Tables\Columns\LinkColumn;
use Illuminate\Database\Eloquent\Builder;
use Illuminate\Support\Facades\Auth;
use Illuminate\Support\HtmlString;
use Filament\Tables\Columns\TextColumn\TextColumnSize;

class JobContentResource extends Resource
{
    protected static ?string $model = JobMembers::class;
    protected static ?string $navigationGroup = 'Media Center';
    protected static ?string $navigationIcon = 'heroicon-o-rectangle-stack';


    public static function shouldRegisterNavigation(): bool
    {
        return Auth::user()?->hasRole('super-admin');
    }

    public static function canViewAny(): bool
    {
        return Auth::user()?->hasRole('super-admin');
    }

    public static function canCreate(): bool
    {
        return false;
    }

    public static function getNavigationLabel(): string
    {
        return 'Job Contents';
    }

    public static function form(Form $form): Form
    {
        return $form;
    }

    public static function table(Table $table): Table
    {
        return $table

            ->columns([
                TextColumn::make('member.email')
                    ->label('Member Email')
                    ->sortable()
                    ->searchable(),


                TextColumn::make('job.title')
                    ->label('Job Title')
                    ->sortable()
                    ->searchable(),

                TextColumn::make('cv_upload')
                    ->label('CV File')
                    ->formatStateUsing(function ($state) {
                        if (!$state) {
                            return '-';
                        }

                        return new HtmlString(
                            '<a href="' . asset('storage/' . $state) . '" 
                target="_blank" 
                class="text-primary-600 hover:underline whitespace-nowrap">
                Download
            </a>'
                        );
                    })
                    ->html()
                    ->alignCenter()->extraAttributes(['style' => 'max-width: 170px;'])

            ])
            ->actions([
                Tables\Actions\ViewAction::make(),
            ])
            ->bulkActions([
                Tables\Actions\BulkActionGroup::make([
                    Tables\Actions\DeleteBulkAction::make(),
                ]),
            ])
            ->defaultSort('id', 'desc');
    }

    public static function getRelations(): array
    {
        return [];
    }

    public static function getPages(): array
    {
        return [
            'index' => Pages\ListJobContents::route('/'),
            'view' => Pages\ViewJobContent::route('/{record}'),
        ];
    }
}
