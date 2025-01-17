<?php

namespace App\Filament\Resources\Classification;

use App\Filament\Resources\Classification\CategoryResource\Pages;
use App\Filament\Resources\Classification\CategoryResource\RelationManagers;
use App\Models\Category;
use Filament\Forms;
use Filament\Forms\Components\Select;
use Filament\Forms\Components\Textarea;
use Filament\Forms\Components\TextInput;
use Filament\Forms\Components\Toggle;
use Filament\Forms\Form;
use Filament\Resources\Resource;
use Filament\Tables;
use Filament\Tables\Table;
use Illuminate\Database\Eloquent\Builder;
use Illuminate\Database\Eloquent\SoftDeletingScope;
use Filament\Forms\Components\Section;
use Filament\Tables\Columns\ColorColumn;
use Filament\Tables\Columns\TextColumn;
use Filament\Forms\Get;
use Filament\Forms\Set;
use Illuminate\Support\Str;
use BezhanSalleh\FilamentShield\Contracts\HasShieldPermissions;
class CategoryResource extends Resource implements HasShieldPermissions
{
    protected static ?string $model = Category::class;
    protected static ?string $navigationIcon = 'heroicon-o-cube-transparent';
    protected static ?string $navigationGroup = 'Classification';
    protected static ?int $navigationSort = 1;

    public static function form(Form $form): Form
    {
        return $form
            ->schema([
                Section::make()
                    ->schema([
                        TextInput::make('title')->required()->maxLength(150)->minLength(1)->live(onBlur:true)
                                ->afterStateUpdated(function (Get $get, Set $set, ?string $old, ?string $state) {
                                    if (($get('slug') ?? '') !== Str::slug($old)) {
                                        return;
                                    }

                                    $set('slug', Str::slug($state));
                                }),

                        TextInput::make('slug')
                            ->required()
                            ->disabled()
                            ->dehydrated(),

                        Textarea::make('description')
                            ->placeholder('Write your description here...')
                            ->nullable(),

                        Select::make('parent_id')
                            ->label('Parent Category')
                            ->relationship('parent', 'title',fn ($query)=> $query->limit(10))
                            ->nullable(),

                        Toggle::make('is_active')
                            ->label('Active')
                            ->default(true),

                        Select::make('text_color')
                            ->options([
                                'gray' => 'Gray',
                                'blue' => 'Blue',
                                'red' => 'Red',
                                'yellow' => 'Yellow',
                                'pink' => 'Pink',
                                'indigo' => 'Indigo',
                                'purple' => 'Purple',
                                'green' => 'Green',
                                'lime' => 'Lime',
                            ])
                            ->default('gray')
                            ->nullable()
                            ->preload(),

                        Select::make('bg_color')
                            ->options([
                                'gray' => 'Gray',
                                'blue' => 'Blue',
                                'red' => 'Red',
                                'yellow' => 'Yellow',
                                'pink' => 'Pink',
                                'indigo' => 'Indigo',
                                'purple' => 'Purple',
                                'green' => 'Green',
                                'lime' => 'Lime',
                            ])
                            ->default('gray')
                            ->nullable()
                            ->preload(),
                    ])
            ]);
    }


    public static function table(Table $table): Table
    {
        return $table
            ->columns([
                TextColumn::make('title')
                    ->searchable()
                    ->sortable(),
                TextColumn::make('is_active')
                    ->badge()
                    ->label('Active')
                    ->formatStateUsing(function (string $state): string {
                        return $state === '1' ? 'Active' : 'Inactive';
                    })
                    ->color(fn (string $state): string => match ($state) {
                        '1' => 'success',
                        '0' => 'danger',
                    }),

                ColorColumn::make('text_color'),
                ColorColumn::make('bg_color')
                            ->label('Background Color'),


                TextColumn::make('created_at')
                    ->dateTime()
                    ->sortable(),

            ])
            ->filters([
                Tables\Filters\SelectFilter::make('is_active')
                    ->label('Active')
                    ->options([
                        1 => 'Active',
                        0 => 'Inactive',
                    ]),
            ])
            ->actions([
                Tables\Actions\EditAction::make(),
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
            //
        ];
    }

    public static function getPages(): array
    {
        return [
            'index' => Pages\ListCategories::route('/'),
            'create' => Pages\CreateCategory::route('/create'),
            'edit' => Pages\EditCategory::route('/{record}/edit'),
        ];
    }

        public static function getPermissionPrefixes(): array
    {
        return [
            'view',
            'view_any',
            'create',
            'update',
            'delete',
            'delete_any',
            'publish'
        ];
    }
}
