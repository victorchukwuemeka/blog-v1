<?php

namespace App\Filament\Resources\Jobs\Tables;

use Filament\Tables\Table;
use Filament\Actions\EditAction;
use Filament\Actions\BulkActionGroup;
use Filament\Actions\DeleteBulkAction;
use Filament\Tables\Columns\IconColumn;
use Filament\Tables\Columns\TextColumn;

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
            ->filters([
                //
            ])
            ->recordActions([
                EditAction::make(),
            ])
            ->toolbarActions([
                BulkActionGroup::make([
                    DeleteBulkAction::make(),
                ]),
            ]);
    }
}
