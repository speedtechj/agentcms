<?php

namespace App\Filament\Resources;

use Filament\Forms;
use App\Models\User;
use Filament\Tables;
use App\Models\Booking;
use Filament\Forms\Form;
use Filament\Tables\Table;
use Filament\Resources\Resource;
use Filament\Tables\Filters\Filter;
use Filament\Tables\Actions\BulkAction;
use Filament\Forms\Components\DatePicker;
use Filament\Tables\Columns\Layout\Split;
use Illuminate\Database\Eloquent\Builder;
use Illuminate\Database\Eloquent\Collection;
use App\Filament\Resources\BookingResource\Pages;
use Illuminate\Database\Eloquent\SoftDeletingScope;
use App\Filament\Resources\BookingResource\RelationManagers;
use Spatie\LaravelIgnition\Http\Requests\UpdateConfigRequest;

class BookingResource extends Resource
{
    protected static ?string $model = Booking::class;

    protected static ?string $navigationIcon = 'heroicon-o-rectangle-stack';

    public static function form(Form $form): Form
    {
        return $form
            ->schema([
                Forms\Components\TextInput::make('catextracharge_id')
                    ->numeric(),
                Forms\Components\TextInput::make('booking_invoice')
                    ->required()
                    ->maxLength(191),
                Forms\Components\TextInput::make('manual_invoice')
                    ->maxLength(191),
                Forms\Components\TextInput::make('sender_id')
                    ->required()
                    ->numeric(),
                Forms\Components\TextInput::make('senderaddress_id')
                    ->required()
                    ->numeric(),
                Forms\Components\TextInput::make('boxtype_id')
                    ->required()
                    ->numeric(),
                Forms\Components\TextInput::make('servicetype_id')
                    ->required()
                    ->numeric(),
                Forms\Components\DatePicker::make('booking_date')
                    ->required(),
                Forms\Components\TextInput::make('start_time'),
                Forms\Components\TextInput::make('end_time'),
                Forms\Components\TextInput::make('discount_id')
                    ->numeric(),
                Forms\Components\Toggle::make('is_pickup')
                    ->required(),
                Forms\Components\TextInput::make('total_price')
                    ->required()
                    ->numeric(),
                Forms\Components\TextInput::make('irregular_length')
                    ->numeric(),
                Forms\Components\TextInput::make('irregular_width')
                    ->numeric(),
                Forms\Components\TextInput::make('irregular_height')
                    ->numeric(),
                Forms\Components\TextInput::make('total_inches')
                    ->numeric(),

                Forms\Components\TextInput::make('dimension')
                    ->maxLength(191),
                Forms\Components\Textarea::make('note')
                    ->maxLength(65535)
                    ->columnSpanFull(),
                Forms\Components\TextInput::make('extracharge_amount')
                    ->numeric(),
                Forms\Components\Toggle::make('box_replacement')
                    ->required(),
                Forms\Components\TextInput::make('user_id')
                    ->required()
                    ->numeric(),
                Forms\Components\Toggle::make('is_edit')
                    ->required(),
                Forms\Components\Toggle::make('is_agent')
                    ->required(),
                Forms\Components\TextInput::make('agentdiscount_id')
                    ->numeric(),
            ]);
    }

    public static function table(Table $table): Table
    {
        return $table
            ->columns([
                Split::make([
                Tables\Columns\TextColumn::make('booking_invoice')
                    ->searchable(),
                Tables\Columns\TextColumn::make('sender.full_name'),
                Tables\Columns\TextColumn::make('senderaddress.address')
                    ->label('Address')
                    ->sortable(),
                    Tables\Columns\TextColumn::make('senderaddress.quadrant')
                    ->label('Quadrant')
                    ->sortable(),
                    Tables\Columns\TextColumn::make('sender.mobile_no')
                    ->label('Mobile Number')
                    ->url(fn (Booking $record) => "tel:{$record->sender->mobile_no}")
                    ->color('info')
                    ->sortable(),
                Tables\Columns\TextColumn::make('sender.home_no')
                    ->label('Home Number')
                    ->url(fn (Booking $record) => "tel:{$record->sender->home_no}")
                    ->color('info')
                    ->sortable(),
                Tables\Columns\TextColumn::make('boxtype.description')
                    ->numeric()
                    ->sortable(),
                Tables\Columns\TextColumn::make('user.full_name')
                    ->sortable(),
                Tables\Columns\TextColumn::make('booking_date')
                    ->date()
                    ->sortable(),
                Tables\Columns\TextColumn::make('start_time'),
                Tables\Columns\TextColumn::make('end_time'),
                Tables\Columns\TextColumn::make('discount_id')
                    ->money('USD')
                    ->sortable(),
                Tables\Columns\ToggleColumn::make('is_pickup')
                    ->sortable(),
                Tables\Columns\TextColumn::make('total_inches')
                    ->numeric()
                    ->sortable(),
                Tables\Columns\TextColumn::make('dimension')
                    ->searchable(),
                Tables\Columns\TextColumn::make('extracharge_amount')
                    ->numeric()
                    ->sortable(),
                    // Tables\Columns\TextColumn::make('user.full_name')
                    // ->label('Agent Name')
                    // ->sortable(),
                    Tables\Columns\TextColumn::make('payment_balance')
                    ->money('USD')
                    ])->from('sm')
            ])
            ->filters([
                Filter::make('is_pickup')
                    ->label('Not Pickup')
                    ->query(fn (Builder $query): Builder => $query->where('is_pickup', false))->default('false'),
                    Filter::make('created_at')
                    ->form([
                        DatePicker::make('booking_date')->default(now()),
                        
                    ])
                    ->query(function (Builder $query, array $data): Builder {
                        return $query
                            ->when(
                                $data['booking_date'],
                                fn (Builder $query, $date): Builder => $query->whereDate('booking_date', '=', $date)
                            );
                            
                    })
            ])
            ->actions([
                // Tables\Actions\EditAction::make(),
            ])
            ->bulkActions([
                BulkAction::make('Assign To Agent')
                ->form([
                    Forms\Components\Select::make('agent_id')
                        ->label('Agent Name')
                        ->options(User::where('agent_type', 1)->pluck('full_name', 'id'))
                        ->searchable()
                        ->placeholder('Select Agent')
                        ->preload()
                        ->required(),
                ])
                ->action(function(Collection $records, array $data): void {
                    foreach ($records as $record) {
                        $record->update([
                            'agent_id' => $data['agent_id'],
                            
                        ]);
                    }
                    
                })
            ]);
    }

    public static function getRelations(): array
    {
        return [
            //
        ];
    }
    public static function getEloquentQuery(): Builder
    {
        //    dd(auth()->user());
        return parent::getEloquentQuery()->where('agent_id', auth()->user()->id);
    }

    public static function getPages(): array
    {
        return [
            'index' => Pages\ListBookings::route('/'),
            'create' => Pages\CreateBooking::route('/create'),
            // 'edit' => Pages\EditBooking::route('/{record}/edit'),
        ];
    }
}
