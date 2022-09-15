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
            Card::make($us->name . ' Employees' ??  'UK Employees', $us->employees_count ?? '0'),
            Card::make($hk->name . ' Employees' ?? 'HK Employees', $hk->employees_count ?? '0'),
        ];
    }
}