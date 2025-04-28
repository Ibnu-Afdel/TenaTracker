<?php

namespace App\Filament\Journal\Pages;

use Filament\Pages\Page;

class Dashboard extends Page
{
    protected static ?string $navigationIcon = 'heroicon-o-home';

    protected static string $view = 'filament.journal.pages.dashboard';

    protected static ?int $navigationSort = -2;

    public static function shouldRegisterNavigation(): bool
    {
        return true;
    }

    public function getTitle(): string
    {
        return 'Journal Dashboard';
    }
}

