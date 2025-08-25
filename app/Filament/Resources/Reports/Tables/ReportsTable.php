<?php

namespace App\Filament\Resources\Reports\Tables;

use App\Models\Report;
use Filament\Tables\Table;
use Filament\Actions\Action;
use Filament\Actions\ViewAction;
use Filament\Actions\ActionGroup;
use Filament\Actions\BulkActionGroup;
use Filament\Actions\DeleteBulkAction;
use Filament\Forms\Components\Textarea;
use Filament\Tables\Columns\TextColumn;

class ReportsTable
{
    public static function configure(Table $table) : Table
    {
        return $table
            ->columns([
                TextColumn::make('post.title'),

                TextColumn::make('created_at')
                    ->dateTime()
                    ->sortable()
                    ->label('Creation Date'),

                TextColumn::make('updated_at')
                    ->dateTime()
                    ->sortable()
                    ->toggleable(isToggledHiddenByDefault: true)
                    ->label('Last Modification Date'),
            ])
            ->filters([
                //
            ])
            ->recordActions([
                ActionGroup::make([
                    ViewAction::make(),

                    Action::make('transmit')
                        ->action(function (Report $record, array $data) {
                            //
                        })
                        ->modalHeading('Transmit to writer')
                        ->modalDescription('Are you sure you want to transmit this report to the writer?')
                        ->schema([
                            Textarea::make('instructions')
                                ->nullable()
                                ->helperText("Override some of the editor's instructions for the writer."),
                        ])
                        ->modalSubmitActionLabel('Transmit')
                        ->label('Transmit to writer')
                        ->icon('heroicon-o-arrow-right'),
                ]),
            ])
            ->toolbarActions([
                BulkActionGroup::make([
                    DeleteBulkAction::make(),
                ]),
            ]);
    }
}
