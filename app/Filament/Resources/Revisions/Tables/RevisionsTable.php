<?php

namespace App\Filament\Resources\Revisions\Tables;

use App\Models\Revision;
use Filament\Tables\Table;
use Filament\Actions\Action;
use Filament\Actions\ViewAction;
use Filament\Actions\ActionGroup;
use Filament\Actions\DeleteAction;
use Filament\Actions\BulkActionGroup;
use Filament\Actions\DeleteBulkAction;
use Filament\Tables\Columns\TextColumn;

class RevisionsTable
{
    public static function configure(Table $table) : Table
    {
        return $table
            ->columns([
                TextColumn::make('report.post.title'),

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

                    Action::make('complete')
                        ->label('Mark as completed')
                        ->icon('heroicon-o-check')
                        ->action(function (Revision $record) {
                            $record->update(['completed_at' => now()]);
                        }),

                    DeleteAction::make(),
                ]),
            ])
            ->toolbarActions([
                BulkActionGroup::make([
                    DeleteBulkAction::make(),
                ]),
            ]);
    }
}
