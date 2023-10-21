<?php

namespace App\Filament\Pages;


use Filament\Pages\Page;
use Filament\Forms\Components\Grid;
use Illuminate\Support\Facades\Hash;
use Filament\Forms\Components\Section;
use Filament\Forms\Components\TextInput;
use Filament\Notifications\Notification;

class EditProfile extends Page
{
    protected static ?string $navigationIcon = 'heroicon-o-document-text';
    protected static bool $shouldRegisterNavigation = false;
    protected static string $view = 'filament.pages.edit-profile';
    public $first_name;
    public $last_name;
    public $email;

    public $current_password;

    public $new_password;

    public $new_password_confirmation;
    public function mount()
    {
        $this->form->fill([
            'first_name' => auth()->user()->first_name,
            'last_name' => auth()->user()->last_name,
            'email' => auth()->user()->email,
        ]);
    }
    public function submit()
    {
        $this->form->getState();

        $state = array_filter([
            // 'name' => $this->name,
            'email' => $this->email,
            'password' => $this->new_password ? Hash::make($this->new_password) : null,
        ]);

        $user = auth()->user();

        $user->update($state);

        if ($this->new_password) {
            $this->updateSessionPassword($user);
        }

        $this->reset(['current_password', 'new_password', 'new_password_confirmation']);
        Notification::make()
            ->title('Saved successfully')
            ->success()
            ->send();
       
    }
    protected function updateSessionPassword($user)
    {
        request()->session()->put([
            'password_hash_' . auth()->getDefaultDriver() => $user->getAuthPassword(),
        ]);
    }
    public function getCancelButtonUrlProperty()
    {
        return static::getUrl();
    }
    
    protected function getFormSchema(): array
    {
        return [
            Section::make('General')
                ->columns(2)
                ->schema([
                    TextInput::make('first_name')
                        ->required(),
                        TextInput::make('last_name')
                        ->required(),
                    TextInput::make('email')
                        ->label('Email Address')
                        ->required(),
                ]),
            Section::make('Update Password')
                ->columns(2)
                ->schema([
                    TextInput::make('current_password')
                        ->label('Current Password')
                        ->password()
                        ->rules(['required_with:new_password'])
                        ->currentPassword()
                        ->autocomplete('off')
                        ->columnSpan(1),
                    Grid::make()
                        ->schema([
                            TextInput::make('new_password')
                                ->label('New Password')
                                ->password()
                                ->rules(['confirmed'])
                                ->autocomplete('new-password'),
                            TextInput::make('new_password_confirmation')
                                ->label('Confirm Password')
                                ->password()
                                ->rules([
                                    'required_with:new_password',
                                ])
                                ->autocomplete('new-password'),
                        ]),
                ]),
        ];
    }
}
