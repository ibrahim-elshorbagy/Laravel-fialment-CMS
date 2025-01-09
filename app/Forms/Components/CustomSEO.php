<?php

namespace App\Forms\Components;

use Filament\Forms\Components\Group;
use Filament\Forms\Components\TagsInput;
use Filament\Forms\Components\TextInput;
use RalphJSmit\Filament\SEO\SEO;
use Illuminate\Database\Eloquent\Model;

class CustomSEO
{
    public static function make(array $fields = ['title', 'author', 'description', 'keywords']): Group
    {
        // Get the original SEO component
        $seoComponent = SEO::make($fields);

        // Get the original schema
        $originalSchema = $seoComponent->getChildComponents();

        // Find and modify the author field
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

                if ($record->seo && $record->seo->exists) {
                    $record->seo->update($state);
                } else {
                    $record->seo()->create($state);
                }
            });
    }
}
