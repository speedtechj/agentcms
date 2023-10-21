<?php

namespace App\Filament\Widgets;

use App\Models\Booking;
use Filament\Widgets\StatsOverviewWidget\Stat;
use Filament\Widgets\StatsOverviewWidget as BaseWidget;

class StatsOverview extends BaseWidget
{
    protected function getStats(): array
    {
        return [
            Stat::make('Total Box', Booking::where('booking_date', now()->format('Y-m-d'))
            ->where('agent_id', auth()->user()->id)
            ->count())
            ->description('Total Box Today')
            ->descriptionIcon('heroicon-m-arrow-trending-up')
            ->chart([7, 2, 10, 3, 15, 4, 17])
            ->color('success'),
            Stat::make('Total Box', Booking::where('is_pickup', 1)
            ->where('booking_date', now()->format('Y-m-d'))
            ->where('agent_id', auth()->user()->id)
            ->count())
            ->description('Pickup Today')
            ->descriptionIcon('heroicon-m-arrow-trending-up')
            ->chart([7, 2, 10, 3, 15, 4, 17])
            ->color('success'),
            Stat::make('Total Box', Booking::where('is_pickup', 0)
            ->where('booking_date', now()->format('Y-m-d'))
            ->where('agent_id', auth()->user()->id)
            ->count())
            ->description('Not Pickup Today')
            ->descriptionIcon('heroicon-m-arrow-trending-up')
            ->chart([7, 2, 10, 3, 15, 4, 17])
            ->color('info'),
        ];
    }
}
