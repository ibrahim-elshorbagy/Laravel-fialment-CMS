<?php

namespace App\Filament\Resources\Classification\CategoryResource\Pages;

use App\Filament\Resources\Classification\CategoryResource;
use Filament\Actions;
use Filament\Resources\Pages\CreateRecord;

class CreateCategory extends CreateRecord
{
    protected static string $resource = CategoryResource::class;
}
