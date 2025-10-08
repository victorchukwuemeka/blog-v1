<?php

namespace App\Filament\Resources\Jobs\Tables;

use App\Models\Job;
use App\Jobs\ReviseJob;
use Filament\Tables\Table;
use Filament\Actions\Action;
use Filament\Actions\EditAction;
use Filament\Actions\BulkActionGroup;
use Filament\Actions\DeleteBulkAction;
use Filament\Forms\Components\Textarea;
use Filament\Tables\Columns\IconColumn;
use Filament\Tables\Columns\TextColumn;
use Filament\Notifications\Notification;

class JobsTable
{
    public static function configure(Table $table) : Table
    {
        return $table
            ->defaultSort('id', 'desc')
            ->columns([
                TextColumn::make('company.name')
                    ->searchable(),

                TextColumn::make('url')
                    ->searchable()
                    ->label('URL'),

                TextColumn::make('source')
                    ->searchable(),

                TextColumn::make('language')
                    ->searchable(),

                TextColumn::make('title')
                    ->searchable(),

                TextColumn::make('slug')
                    ->searchable(),

                TextColumn::make('setting')
                    ->searchable(),

                TextColumn::make('min_salary')
                    ->numeric()
                    ->sortable()
                    ->label('Minimum Salary'),

                TextColumn::make('max_salary')
                    ->numeric()
                    ->sortable()
                    ->label('Maximum Salary'),

                TextColumn::make('currency')
                    ->searchable(),

                IconColumn::make('equity')
                    ->boolean(),

                TextColumn::make('created_at')
                    ->dateTime()
                    ->sortable()
                    ->toggleable(isToggledHiddenByDefault: true)
                    ->label('Creation Date'),

                TextColumn::make('updated_at')
                    ->dateTime()
                    ->sortable()
                    ->toggleable(isToggledHiddenByDefault: true)
                    ->label('Modification Date'),
            ])
            ->recordActions([
                Action::make('revise')
                    ->schema([
                        Textarea::make('additional_instructions')
                            ->nullable(),
                    ])
                    ->modalSubmitActionLabel('Revise')
                    ->action(function (Job $record, array $data) {
                        ReviseJob::dispatch($record, $data['additional_instructions']);

                        Notification::make()
                            ->title('The job has been queued for revision.')
                            ->success()
                            ->send();
                    })
                    ->icon('heroicon-o-arrow-path'),

                EditAction::make()
                    ->icon('heroicon-o-pencil-square'),

                DeleteAction::make()
                    ->icon('heroicon-o-trash'),
            ])
            ->toolbarActions([
                BulkActionGroup::make([
                    DeleteBulkAction::make(),
                ]),
            ]);
    }
}
