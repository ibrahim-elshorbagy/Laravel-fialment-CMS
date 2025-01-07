<?php

namespace App\Filament\Resources\Blog;

use App\Filament\Resources\Blog\ArticleResource\Pages;
use App\Filament\Resources\Blog\ArticleResource\RelationManagers;
use App\Forms\Components\slug;
use App\Models\Blog\Article;
use App\Models\Classification\Category;
use App\Models\Classification\Tag;
use Awcodes\Curator\Components\Forms\CuratorPicker;
use Awcodes\Curator\Components\Tables\CuratorColumn;
use Filament\Forms;
use Filament\Forms\Components\DateTimePicker;
use Filament\Forms\Components\MorphToSelect;
use Filament\Forms\Components\RichEditor;
use Filament\Forms\Components\Section;
use Filament\Forms\Components\Select;
use Filament\Forms\Components\Textarea;
use Filament\Forms\Components\TextInput;
use Filament\Forms\Components\Toggle;
use Filament\Forms\Form;
use Filament\Resources\Resource;
use Filament\Tables;
use Filament\Tables\Columns\TextColumn;
use Filament\Tables\Table;
use Illuminate\Database\Eloquent\Builder;
use Illuminate\Database\Eloquent\SoftDeletingScope;

use Filament\Forms\Get;
use Filament\Forms\Set;
use Illuminate\Support\Str;


class ArticleResource extends Resource
{
    protected static ?string $model = Article::class;

    protected static ?string $navigationIcon = 'heroicon-o-rectangle-stack';

    public static function form(Form $form): Form
    {
           return $form
                    ->schema([
                        Section::make('General Information')
                            ->schema([
                                TextInput::make('title')->required()->maxLength(150)->minLength(1)->live(onBlur:true)
                                ->afterStateUpdated(function (Get $get, Set $set, ?string $old, ?string $state) {
                                    if (($get('slug') ?? '') !== Str::slug($old)) {
                                        return;
                                    }

                                    $set('slug', Str::slug($state));
                                }),

                                TextInput::make('slug')->required()->unique(ignoreRecord:true)
                                ->maxLength(150)
                                ->minLength(1)
                                ->disabled()
                                ->dehydrated()
                                ->required(),

                                RichEditor::make('content')
                                    ->label('Content')
                                    ->placeholder('Write your content here...')
                                    ->required()
                                    ->columnSpanFull(),

                                Textarea::make('brief')
                                    ->label('Brief Description')
                                    ->placeholder('A short summary of the post')
                                    ->required()
                                    ->maxLength(160)
                                    ->columnSpanFull(),
                                    
                                select::make('categories')
                                    ->relationship('categories', 'title',fn($query)=> $query->limit(10))
                                    ->preload()
                                    ->label('Categories'),

                                select::make('tags')
                                    ->multiple()
                                    ->relationship('tags', 'title',fn($query)=> $query->limit(10))
                                    ->preload()
                                    ->label('Tags'),

                            ])
                            ->columns(2),

                        Section::make('Media & Author')
                            ->schema([
                                CuratorPicker::make('media_id')
                                    ->label('Featured Image')
                                    ->nullable(),

                                Select::make('user_id')
                                    ->label('Author')
                                    ->relationship('user', 'name',fn ($query)=> $query->limit(10) )
                                    ->placeholder('Select an author')
                                    ->preload()
                                    ->searchable()
                                    ->required(),
                            ])
                            ->columns(2),

                        Section::make('Publishing')
                            ->schema([
                                Toggle::make('is_published')
                                    ->label('Published')
                                    ->default(true),

                                DateTimePicker::make('published_at')
                                    ->label('Publish Date')
                                    ->default(now())
                                    ->required(),
                            ])
                            ->columns(2),
                    ]);

    }

    public static function table(Table $table): Table
    {
        return $table
            ->columns([
                CuratorColumn::make('media_id')->size(100)->label('Image'),
                TextColumn::make('title')->searchable(),
                // TextColumn::make('brief'),

                ])
            ->filters([
                //
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
            'index' => Pages\ListArticles::route('/'),
            'create' => Pages\CreateArticle::route('/create'),
            'edit' => Pages\EditArticle::route('/{record}/edit'),
        ];
    }
}
