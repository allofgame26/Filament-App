<?php

namespace App\Filament\Resources\BarangResource\Pages;

use App\Filament\Resources\BarangResource;
use Filament\Actions;
use Filament\Resources\Pages\CreateRecord;
use Filament\Notifications\Notification;

class CreateBarang extends CreateRecord
{
    protected static string $resource = BarangResource::class;

    // protected function getCreatedNotificationTitle(): ?string
    // {
    //     return 'Data Barang Sudah Tersimpan';
    // }

    protected function getCreatedNotification(): ?Notification
{
    return Notification::make()
        ->success()
        ->title('Data Sudah Tersimpan')
        ->icon('heroicon-o-archive-box-arrow-down')
        ->iconColor('success')
        ->duration(2000) // mengatur durasi notifikasi, dalam milisecond. 2000 -> 2 detik
        ->body('Data Barang sudah tersimpan.');
}
}
