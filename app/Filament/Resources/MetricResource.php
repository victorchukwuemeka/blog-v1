<?php

namespace App\Filament\Resources;

use Filament\Forms;
use Filament\Tables;
use App\Models\Metric;
use Filament\Forms\Form;
use Filament\Tables\Table;
use Filament\Resources\Resource;
use Filament\Support\Enums\FontWeight;
use Illuminate\Database\Eloquent\Model;
use Illuminate\Contracts\Support\Htmlable;
use App\Filament\Resources\MetricResource\Pages;

class MetricResource extends Resource
{
    protected static ?string $model = Metric::class;

    protected static ?string $navigationGroup = 'Others';

    protected static ?string $navigationIcon = 'heroicon-o-chart-bar';

    protected static ?int $navigationSort = 4;

    protected static ?string $recordTitleAttribute = 'key';

    public static function form(Form $form) : Form
    {
        return $form
            ->schema([
                Forms\Components\TextInput::make('key')
                    ->required()
                    ->maxLength(255)
                    ->columnSpanFull(),

                Forms\Components\Textarea::make('value')
                    ->required()
                    ->columnSpanFull(),

                Forms\Components\DateTimePicker::make('created_at')
                    ->timezone('Europe/Paris')
                    ->native(false)
                    ->columnSpanFull(),
            ]);
    }

    public static function table(Table $table) : Table
    {
        return $table
            ->defaultSort('id', 'desc')
            ->columns([
                Tables\Columns\TextColumn::make('id')
                    ->sortable()
                    ->label('ID')
                    ->weight(FontWeight::Bold),

                Tables\Columns\TextColumn::make('key')
                    ->searchable(),

                Tables\Columns\TextColumn::make('value')
                    ->searchable(),

                Tables\Columns\TextColumn::make('created_at')
                    ->date()
                    ->sortable()
                    ->label('Creation Date'),
            ])
            ->actions([
                Tables\Actions\EditAction::make()
                    ->icon('')
                    ->button()
                    ->outlined()
                    ->size('xs'),

                Tables\Actions\DeleteAction::make()
                    ->icon('')
                    ->button()
                    ->outlined()
                    ->size('xs'),
            ])
            ->bulkActions([
                Tables\Actions\DeleteBulkAction::make(),
                Tables\Actions\ForceDeleteBulkAction::make(),
                Tables\Actions\RestoreBulkAction::make(),
            ]);
    }

    public static function getPages() : array
    {
        return [
            'index' => Pages\ListMetrics::route('/'),
            'view' => Pages\ViewMetric::route('/{record}'),
        ];
    }

    public static function getGloballySearchableAttributes() : array
    {
        return ['key', 'value'];
    }

    public static function getGlobalSearchResultDetails(Model $record) : array
    {
        return ['Value' => $record->value];
    }

    public static function getRecordTitle(?Model $record) : string|Htmlable|null
    {
        return "\"{$record->key}\" metric";
    }
}
