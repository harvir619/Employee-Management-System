<?php

namespace App\Filament\Resources\EmployeeResource\Widgets;

use App\Models\Country;
use App\Models\Employee;
use Filament\Widgets\StatsOverviewWidget as BaseWidget;
use Filament\Widgets\StatsOverviewWidget\Card;

class EmployeeStatsOverview extends BaseWidget
{
    protected function getCards(): array
    {
        $us = Country::where('country_code', 'US')->withCount('employees')->first();
        $hk = Country::where('country_code', 'HK')->withCount('employees')->first();
        // dd(Country::where('country_code', 'HK')->withCount('employees')->first());
        return [
            Card::make('All Employees', Employee::all()->count()),
            Card::make($us->name . ' Employees' ?? 'None' . ' Employees', $us->employees_count ?? ''),
            Card::make($hk->name . ' Employees' ?? 'Hong Kong', $hk->employees_count ?? ''),
        ];
    }
}