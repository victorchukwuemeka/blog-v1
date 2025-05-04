<?php

namespace App\Filament\Resources;

use Filament\Forms;
use Filament\Tables;
use App\Models\Comment;
use Filament\Forms\Form;
use Filament\Tables\Table;
use Filament\Resources\Resource;
use Filament\Support\Enums\FontWeight;
use Illuminate\Database\Eloquent\Model;
use App\Filament\Resources\CommentResource\Pages;

class CommentResource extends Resource
{
    protected static ?string $model = Comment::class;

    protected static ?string $navigationGroup = 'Blog';

    protected static ?string $navigationIcon = 'heroicon-o-chat-bubble-oval-left';

    protected static ?int $navigationSort = 1;

    public static function form(Form $form) : Form
    {
        return $form
            ->schema([
                Forms\Components\MarkdownEditor::make('content')
                    ->required()
                    ->columnSpanFull(),

                Forms\Components\Section::make('Metadata')
                    ->schema([
                        Forms\Components\Select::make('user_id')
                            ->relationship('user', 'name')
                            ->required()
                            ->label('Author'),

                        Forms\Components\Select::make('post_id')
                            ->relationship('post', 'title')
                            ->required()
                            ->label('Attached To Post'),

                        Forms\Components\Select::make('parent_id')
                            ->relationship('parent', 'content')
                            ->label('In Reply To'),

                        Forms\Components\DateTimePicker::make('modified_at')
                            ->timezone('Europe/Paris')
                            ->native(false)
                            ->label('Modification Date')
                            ->helperText('This is blank until the user updates the comment.'),
                    ])
                    ->collapsible(),
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

                Tables\Columns\TextColumn::make('user.name')
                    ->searchable()
                    ->label('Author'),

                Tables\Columns\TextColumn::make('post.title')
                    ->searchable()
                    ->label('Post'),

                Tables\Columns\TextColumn::make('content')
                    ->searchable()
                    ->limit(50),

                Tables\Columns\TextColumn::make('created_at')
                    ->date()
                    ->sortable()
                    ->label('Creation Date'),

                Tables\Columns\TextColumn::make('modified_at')
                    ->date()
                    ->sortable()
                    ->label('Modification Date')
                    ->toggleable(isToggledHiddenByDefault: true),
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

                Tables\Actions\ActionGroup::make([
                    Tables\Actions\Action::make('activities')
                        ->url(fn (Model $record) => self::getUrl('activities', compact('record')))
                        ->icon('heroicon-o-list-bullet'),
                ]),
            ])
            ->bulkActions([
                Tables\Actions\BulkActionGroup::make([
                    Tables\Actions\DeleteBulkAction::make(),
                ]),
            ]);
    }

    public static function getRelations() : array
    {
        return [
            //
        ];
    }

    public static function getPages() : array
    {
        return [
            'index' => Pages\ListComments::route('/'),
            'activities' => Pages\ListCommentActivities::route('/{record}/activities'),
            'create' => Pages\CreateComment::route('/create'),
            'edit' => Pages\EditComment::route('/{record}/edit'),
        ];
    }

    public static function getGloballySearchableAttributes() : array
    {
        return ['user.name', 'content'];
    }

    public static function getGlobalSearchResultTitle(Model $record) : string
    {
        return strlen($record->content) > 50 ? substr($record->content, 0, 50) . '…' : $record->content;
    }

    public static function getGlobalSearchResultDetails(Model $record) : array
    {
        return ['Author' => $record->user->name];
    }
}
