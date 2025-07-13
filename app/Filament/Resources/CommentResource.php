<?php

namespace App\Filament\Resources;

use App\Models\Comment;
use Filament\Tables\Table;
use Filament\Schemas\Schema;
use Filament\Actions\EditAction;
use Filament\Resources\Resource;
use Filament\Actions\DeleteAction;
use Filament\Actions\BulkActionGroup;
use Filament\Forms\Components\Select;
use Filament\Actions\DeleteBulkAction;
use Filament\Tables\Columns\TextColumn;
use Illuminate\Database\Eloquent\Model;
use Filament\Schemas\Components\Section;
use Filament\Forms\Components\DateTimePicker;
use Filament\Forms\Components\MarkdownEditor;
use App\Filament\Resources\CommentResource\Pages\EditComment;
use App\Filament\Resources\CommentResource\Pages\ListComments;
use App\Filament\Resources\CommentResource\Pages\CreateComment;
use App\Filament\Resources\PostResource\Pages\ManagePostComments;

class CommentResource extends Resource
{
    protected static ?string $model = Comment::class;

    protected static string|\UnitEnum|null $navigationGroup = 'Blog';

    protected static string|\BackedEnum|null $navigationIcon = 'heroicon-o-chat-bubble-oval-left';

    protected static ?int $navigationSort = 1;

    public static function form(Schema $schema) : Schema
    {
        return $schema
            ->components([
                MarkdownEditor::make('content')
                    ->required()
                    ->columnSpanFull(),

                Section::make('Metadata')
                    ->schema([
                        Select::make('user_id')
                            ->relationship('user', 'name')
                            ->required()
                            ->label('Author'),

                        Select::make('post_id')
                            ->relationship('post', 'title')
                            ->required()
                            ->label('Attached To Post'),

                        Select::make('parent_id')
                            ->relationship('parent', 'content')
                            ->label('In Reply To'),

                        DateTimePicker::make('modified_at')
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
                TextColumn::make('user.name')
                    ->searchable()
                    ->label('Author'),

                TextColumn::make('post.title')
                    ->searchable()
                    ->label('Post')
                    ->hiddenOn(ManagePostComments::class),

                TextColumn::make('content')
                    ->searchable()
                    ->limit(50),

                TextColumn::make('created_at')
                    ->date()
                    ->sortable()
                    ->label('Creation Date'),

                TextColumn::make('modified_at')
                    ->date()
                    ->sortable()
                    ->label('Modification Date')
                    ->toggleable(isToggledHiddenByDefault: true),
            ])
            ->recordActions([
                EditAction::make()
                    ->icon('')
                    ->button()
                    ->outlined()
                    ->size('xs'),

                DeleteAction::make()
                    ->icon('')
                    ->button()
                    ->outlined()
                    ->size('xs'),
            ])
            ->toolbarActions([
                BulkActionGroup::make([
                    DeleteBulkAction::make(),
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
            'index' => ListComments::route('/'),
            'create' => CreateComment::route('/create'),
            'edit' => EditComment::route('/{record}/edit'),
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
