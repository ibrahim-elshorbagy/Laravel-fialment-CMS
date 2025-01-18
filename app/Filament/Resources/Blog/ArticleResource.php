<?php

namespace App\Filament\Resources\Blog;

use App\Filament\Resources\Blog\ArticleResource\Pages;
use App\Filament\Resources\Blog\ArticleResource\RelationManagers;
use App\Forms\Components\slug;
use App\Models\Article;
use App\Models\Tag;
use App\Models\User;
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
use Filament\Forms\Components\FileUpload;
use Filament\Forms\Components\Grid;
use Illuminate\Http\UploadedFile;
use Illuminate\Support\Facades\Storage;
use Pboivin\FilamentPeek\Tables\Actions\ListPreviewAction;
use BezhanSalleh\FilamentShield\Contracts\HasShieldPermissions;
use Filament\Tables\Columns\ToggleColumn;
use Illuminate\Support\Facades\Gate;
use Filament\Tables\Columns\ImageColumn;
use Filament\Tables\Filters\TernaryFilter;

class ArticleResource extends Resource implements HasShieldPermissions
{
    protected static ?string $model = Article::class;

    protected static ?string $navigationIcon = 'heroicon-o-rectangle-stack';

    public static function getRouteKey(): string
    {
        return 'id';
    }
    public static function form(Form $form): Form
    {
        return $form
            ->schema([
                // Main Content Area
                Grid::make()->columns(3)->schema([
                    // Left Sidebar (Main Content)
                    Grid::make()->columnSpan(2)->schema([
                        Section::make('Content')
                            ->description('Manage the content of this article')
                            ->schema([
                                // Title and Slug
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

                                // Rich Text Editor
                                RichEditor::make('content')
                                    ->label('Article Content')
                                    ->required()
                                    ->columnSpanFull(),

                                // Brief Description
                                Textarea::make('brief')
                                    ->label('Excerpt')
                                    ->helperText('This is a short description of the article on the home page Cards.')
                                    ->maxLength(160)
                                    ->columnSpanFull(),
                            ]),

                            // SEO Section (Full Width)
                                Section::make('SEO')
                                ->schema([
                                    CustomSEO::make(['title', 'author', 'description', 'keywords']),
                                ])
                    ]),

                    // Right Sidebar
                    Grid::make()->columnSpan(1)->schema([
                        // Publishing Status
                        Section::make('Publish')
                            ->schema([
                                Toggle::make('is_published')
                                    ->label('Published')
                                    ->onIcon('heroicon-m-check-circle')
                                    ->offIcon('heroicon-m-x-circle')
                                    ->default(true),

                                DateTimePicker::make('published_at')
                                    ->label('Publish Date'),

                                Select::make('user_id')
                                    ->label('Author')
                                    ->relationship('user', 'name', fn ($query) => $query->limit(10))
                                    ->placeholder('Select an author')
                                    ->preload()
                                    ->searchable()
                                    ->required()
                                    ->reactive()
                                    ->prefixIcon('heroicon-m-user')
                                    ->hidden(fn () => !auth()->user()->can('select_author_blog::article'))
                                    ->default(auth()->id())
                                    ->columnSpanFull(),


                            ]),

                        // Categories and Tags
                        Section::make('Taxonomy')
                            ->schema([
                            select::make('categories')
                                ->relationship('categories', 'title',fn($query)=> $query->limit(10))
                                ->preload()
                                ->label('Categories'),

                            select::make('tags')
                                ->multiple()
                                ->relationship('tags', 'title',fn($query)=> $query->limit(10))
                                ->preload()
                                ->label('Tags'),
                            ]),

                        // Featured Image
                        Section::make('media')
                            ->relationship('media')
                            ->heading('Featured Image')
                                ->schema([
                                    FileUpload::make('path')
                                        ->label('Featured Image')
                                        ->image()
                                        // ->required()
                                        ->disk('public')
                                        ->directory(function (Get $get) {
                                            $userId = $get('../user_id');
                                            $id = $get('../id');
                                            return 'blogs/' . $userId . '/article/'. $id;
                                        })
                                        ->getUploadedFileNameForStorageUsing(function (UploadedFile $file): string {
                                            return Str::slug($file->getClientOriginalName()) . '.' . $file->getClientOriginalExtension();
                                        })
                                        ->afterStateUpdated(function ($state, $record, $set) {

                                            if ($state instanceof UploadedFile && $record?->path) {
                                                Storage::disk('public')->delete($record->path);
                                            }
                                        }),


                                    Textarea::make('alt')
                                        ->label('Image Alt Text'),
                                ]),
                    ]),
                ]),


            ]);
    }


    public static function table(Table $table): Table
    {
        return $table->modifyQueryUsing(function ($query) {
                    $user = auth()->user();

                    if ($user->can('view_all_articles_blog::article')) {
                        return $query;
                    }

                    if ($user->can('view_own_articles_blog::article')) {
                        return $query->where('user_id', $user->id);
                    }

                    return $query->whereRaw('0 = 1');
                })
            ->columns([
                ImageColumn::make('media.path')->size(100)->label('Image'),
                TextColumn::make('title')
                ->searchable()
                ->sortable(),

                TextColumn::make('categories.title')->searchable()->label('Category')->sortable(),

                ToggleColumn::make('is_published')
                            ->label('Published')
                            ->onIcon('heroicon-m-check-circle')
                            ->offIcon('heroicon-m-x-circle')

                            ->disabled(fn($record) => !Gate::allows('publishAny', $record) && !Gate::allows('viewOwnArticles', $record))

                            ->beforeStateUpdated(function (bool $state, $record) {
                                if (!Gate::allows('publishAny', $record)) {
                                    Gate::authorize('viewOwnArticles', $record);
                                }
                            })

                            ->afterStateUpdated(function (bool $state, $record) {
                                $record->published_at = $state ? Carbon::now() : null;
                                $record->save();
                            }),


                TextColumn::make('published_at')
                ->label('Published At')
                ->dateTime()
                ->sortable()
                ->searchable(),
                // TextColumn::make('brief'),

                ])
            ->filters([
                TernaryFilter::make('is_published')
                ->label('Published'),
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
                ListPreviewAction::make(),

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
            'edit' => Pages\EditArticle::route('/{record:id}/edit'),
        ];
    }
    protected function getRedirectUrl(): string
    {
        return $this->getResource()::getUrl('index');
    }


    public static function getPermissionPrefixes(): array
    {
        return [
            'view',
            'view_any',
            'view_own_articles',
            'create',
            'update',
            'delete',
            'publish_all_articles',
            'view_all_articles',
            'select_author'

        ];
    }
}

