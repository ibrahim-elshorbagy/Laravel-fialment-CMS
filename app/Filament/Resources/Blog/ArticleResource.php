<?php

namespace App\Filament\Resources\Blog;

use App\Filament\Resources\Blog\ArticleResource\Pages;
use App\Filament\Resources\Blog\ArticleResource\RelationManagers;
use App\Forms\Components\slug;
use App\Models\Article;
use App\Models\Tag;
use App\Models\User;
use Awcodes\Curator\Components\Forms\CuratorPicker;
use Awcodes\Curator\Components\Tables\CuratorColumn;
use Carbon\Carbon;
use Filament\Forms;
use Filament\Forms\Components\Checkbox;
use Filament\Forms\Components\DateTimePicker;
use Filament\Forms\Components\Hidden;
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
use Filament\Tables\Columns\CheckboxColumn;
use Filament\Tables\Filters\SelectFilter;
use Illuminate\Support\Str;
use App\Forms\Components\CustomSEO;
use Filament\Forms\Components\Grid;

class ArticleResource extends Resource
{
    protected static ?string $model = Article::class;

    protected static ?string $navigationIcon = 'heroicon-o-rectangle-stack';


    public static function form(Form $form): Form
    {
           return $form
                    ->schema([
                        Section::make('Content')
                            ->description('Manage the content of this article')

                            ->schema([
                                    TextInput::make('title')
                                        ->required()
                                        ->maxLength(150)
                                        ->minLength(1)
                                        ->live(onBlur:true)
                                        ->afterStateUpdated(function (Get $get, Set $set, ?string $state) {
                                            if (!$state) return;

                                            $slug = preg_replace('/\s+/u', '-', trim($state));
                                            $slug = str_replace("/", "", $slug);
                                            $slug = str_replace("?", "", $slug);
                                            $slug = mb_strtolower($slug, 'UTF-8');

                                            $set('slug', $slug);
                                        })->prefixIcon('heroicon-m-document-text'),

                                    TextInput::make('slug')
                                        ->required()
                                        ->unique(ignoreRecord: true)
                                        ->maxLength(150)
                                        ->minLength(1)
                                        ->disabled()
                                        ->prefixIcon('heroicon-m-link')
                                        ->helperText('This will be the URL of your content')
                                        ->dehydrated(),

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

                            Grid::make()->schema([
                                // Publishing Status Card
                                Section::make()
                                    ->schema([
                                        Grid::make(2)
                                            ->schema([
                                                Toggle::make('is_published')
                                                    ->label('Publishing Status')
                                                    ->default(true)
                                                    ->inline()
                                                    ->onIcon('heroicon-m-check-circle')
                                                    ->offIcon('heroicon-m-x-circle'),

                                                DateTimePicker::make('published_at')
                                                    ->label('Publish Date')
                                                    ->displayFormat('M d, Y H:i')
                                                    ->timezone('UTC')
                                                    ->prefixIcon('heroicon-m-calendar'),

                                                Select::make('user_id')
                                                    ->label('Author')
                                                    ->relationship('user', 'name', fn ($query) => $query->limit(10))
                                                    ->placeholder('Select an author')
                                                    ->preload()
                                                    ->searchable()
                                                    ->required()
                                                    ->reactive()
                                                    ->prefixIcon('heroicon-m-user')
                                                    ->afterStateUpdated(function ($state, callable $set) {
                                                        if ($state) {
                                                            $userName = \App\Models\User::find($state)->name;
                                                            $set('seo.author', $userName);
                                                        } else {
                                                            $set('seo.author', null);
                                                        }
                                                    })
                                                    ->columnSpanFull(),
                                            ])
                                    ])
                                    ->columnSpan(['lg' => 1, 'sm' => 2])
                                    ->heading('Publishing Information')
                                    ->description('Control when this content goes live'),

                                // Media & Author Card
                                Section::make()
                                    ->schema([
                                        Grid::make(1)
                                            ->schema([
                                                CuratorPicker::make('media_id')
                                                    ->label('Featured Image')
                                                    ->helperText('Select a featured image for this content')
                                                    ->columnSpanFull(),


                                            ])
                                    ])
                                    ->columnSpan(['lg' => 1, 'sm' => 2])
                                    ->heading('Media & Authorship')
                                    ->description('Manage content media and attribution')
                            ])->columns(2),

                        Section::make('SEO')
                        ->schema([
                            CustomSEO::make(['title', 'author', 'description', 'keywords']),
                        ])->description('Enhance SEO visibility'),


                    ]);

    }

    public static function table(Table $table): Table
    {
        return $table
            ->columns([
                CuratorColumn::make('media_id')->size(100)->label('Image'),
                TextColumn::make('title')->searchable()
                ->sortable(),
                TextColumn::make('categories.title')->searchable()->label('Category')->sortable(),
                CheckboxColumn::make('is_published')
                    ->label('Published')
                    ->afterStateUpdated(function ($state, $record) {
                        if ($state) {
                            // If checked, set published_at to the current date and time
                            $record->published_at = Carbon::now();
                        } else {
                            // If unchecked, set published_at to null
                            $record->published_at = null;
                        }
                        $record->save(); // Save the record
                    }),
                TextColumn::make('published_at')->label('Published At')->dateTime()->sortable()->searchable(),
                // TextColumn::make('brief'),

                ])
            ->filters([
                SelectFilter::make('Category')->relationship('categories', 'title',fn($query)=> $query->limit(10))
                ->preload()
                ->searchable(),
                SelectFilter::make('Tag')->relationship('tags', 'title',fn($query)=> $query->limit(10))
                ->preload()
                ->multiple()
                ->searchable(),
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

