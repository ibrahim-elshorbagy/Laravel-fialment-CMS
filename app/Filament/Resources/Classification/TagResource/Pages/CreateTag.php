<?php

namespace App\Filament\Resources\Classification\TagResource\Pages;

use App\Filament\Resources\Classification\TagResource;
use Filament\Actions;
use Filament\Resources\Pages\CreateRecord;

class CreateTag extends CreateRecord
{
    protected static string $resource = TagResource::class;
}
