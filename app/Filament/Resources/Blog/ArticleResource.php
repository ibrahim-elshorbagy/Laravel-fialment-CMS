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
                                    ->afterStateUpdated(function ($state, callable $set) {
                                        if ($state) {
                                            $userName = \App\Models\User::find($state)->name;
                                            $set('seo.author', $userName);
                                        } else {
                                            $set('seo.author', null);
                                        }
                                    })
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
                                        ->required()
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
        return $table
            ->columns([
                // CuratorColumn::make('media_id')->size(100)->label('Image'),
                TextColumn::make('title')->searchable()
                ->sortable(),
                TextColumn::make('categories.title')->searchable()->label('Category')->sortable(),
                ToggleColumn::make('is_published')
                            ->label('Published')
                            ->onIcon('heroicon-m-check-circle')
                            ->offIcon('heroicon-m-x-circle')
                            ->disabled(fn() => !auth()->user()?->can('publish_any_blog::article'))
                            ->afterStateUpdated(function ($state, $record) {
                            if (!auth()->user()->can('publish_any_blog::article')) {
                                abort(403, 'You are not authorized to update this field.');
                            }

                            if ($state) {
                                // If checked, set published_at to the current date and time
                                $record->published_at = Carbon::now();
                            } else {
                                // If unchecked, set published_at to null
                                $record->published_at = null;
                            }
                            $record->save(); // Save the record
                        }),
                TextColumn::make('published_at')
                ->label('Published At')
                ->dateTime()
                ->sortable()
                ->searchable(),
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
            'create',
            'update',
            'delete',
            'delete_any',
            'publish_any'
        ];
    }
}

