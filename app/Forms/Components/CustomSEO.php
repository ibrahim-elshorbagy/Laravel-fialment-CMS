<?php

namespace App\Forms\Components;

use Filament\Forms\Components\Group;
use Filament\Forms\Components\TagsInput;
use Filament\Forms\Components\TextInput;
use RalphJSmit\Filament\SEO\SEO;
use Illuminate\Database\Eloquent\Model;
use Filament\Forms\Get;
use Filament\Forms\Set;

class CustomSEO
{
    public static function make(array $fields = ['title', 'author', 'description', 'keywords']): Group
    {
        // Get the original SEO component
        $seoComponent = SEO::make($fields);

        // Get the original schema
        $originalSchema = $seoComponent->getChildComponents();

        // Find and modify both title and author fields
        $modifiedSchema = array_map(function ($component) {
            if ($component instanceof TextInput && $component->getName() === 'author') {
                return TextInput::make('author')
                    ->translateLabel()
                    ->label(__('filament-seo::translations.author'))
                    ->columnSpan(2)
                    ->disabled()
                    ->reactive()
                    ->hidden();
            }

            // Modify the title field to sync with article title
            if ($component instanceof TextInput && $component->getName() === 'title') {
                return TextInput::make('title')
                    ->translateLabel()
                    ->label(__('filament-seo::translations.title'))
                    ->columnSpan(2)
                    ->disabled() // Make it disabled as it will sync with article title
                    ->reactive()
                    ->hidden()
                    ->afterStateHydrated(function (TextInput $component, Get $get) {
                        // Get the article title
                        $articleTitle = $get('../../title');
                        if ($articleTitle) {
                            $component->state($articleTitle);
                        }
                    });
            }

            return $component;
        }, $originalSchema);

        // Add keywords field to the schema
        $newSchema = array_merge($modifiedSchema, [
            TagsInput::make('keywords')
                ->label('Keywords')
                ->separator(',')
                ->columnSpan(2)
        ]);

        return Group::make($newSchema)
            ->afterStateHydrated(function (Group $component, ?Model $record): void {
                if ($record?->seo) {
                    $seoData = $record->seo->toArray();

                    // Convert keywords from JSON to array if needed
                    if (isset($seoData['keywords']) && is_string($seoData['keywords'])) {
                        $seoData['keywords'] = json_decode($seoData['keywords'], true);
                    }

                    // Set the title to match the article title
                    $seoData['title'] = $record->title;

                    $component->getChildComponentContainer()->fill($seoData);
                }
            })
            ->statePath('seo')
            ->dehydrated(false)
            ->saveRelationshipsUsing(function (Model $record, array $state): void {
                // Ensure keywords is JSON encoded
                if (isset($state['keywords']) && is_array($state['keywords'])) {
                    $state['keywords'] = json_encode($state['keywords']);
                }

                // Always set the SEO title to match the article title
                $state['title'] = $record->title;

                if ($record->seo && $record->seo->exists) {
                    $record->seo->update($state);
                } else {
                    $record->seo()->create($state);
                }
            });
    }
}
