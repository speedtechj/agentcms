<?php

namespace App\Filament\Pages;

use BackedEnum;
use Filament\Actions\Action;
use Filament\Forms\Components\RichEditor;
use Filament\Forms\Components\TextInput;
use Filament\Notifications\Notification;
use Filament\Pages\Page;
use Filament\Schemas\Components\Actions;
use Filament\Schemas\Components\Form;
use Filament\Schemas\Schema;
use Filament\Support\Icons\Heroicon;
use Marcelorodrigo\FilamentBarcodeScannerField\Forms\Components\BarcodeInput;

class Scanbarcode extends Page
{
protected static string | BackedEnum | null $navigationIcon = Heroicon::QrCode;
    protected string $view = 'filament.pages.scanbarcode';
    public function form(Schema $schema): Schema
    {
        return $schema
            ->components([
                BarcodeInput::make('barcode')
    ->icon('heroicon-o-arrow-right')
    ->label('Product Barcode')
    ->placeholder('Scan or type barcode...')
    ->required()
    ->unique('products', 'barcode')
    ->rules(['min:8', 'max:50'])
    ->helperText('Scan the barcode on the product packaging')
    ->hint('Required')
    ->live()
    ->afterStateUpdated(function($state){
            dd($state);
    })
            ]);
            // ->record($this->getRecord())
            //->statePath('data');
    }

}
