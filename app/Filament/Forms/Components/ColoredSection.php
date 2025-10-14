<?php

namespace App\Filament\Forms\Components;

use Filament\Forms\Components\Section;

class ColoredSection extends Section
{
    protected string $view = 'filament.custom.colored-section';

    protected function getViewData(): array
    {
        // Force evaluation of heading and description to ensure they're strings
        return [
            'heading'     => $this->getHeading() ? (string) $this->evaluate($this->getHeading()) : '',
            'description' => $this->getDescription() ? (string) $this->evaluate($this->getDescription()) : '',
            'schema'      => $this->getSchema(),
        ];
    }
}