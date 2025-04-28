<?php

namespace App\Filament\Journal\Resources;

use App\Filament\Journal\Resources\JournalEntryResource\Pages;
use App\Models\JournalEntry;
use App\Models\Tag;
use Filament\Forms;
use Filament\Forms\Form;
use Filament\Resources\Resource;
use Filament\Tables;
use Filament\Tables\Table;
use Illuminate\Database\Eloquent\Builder;
use Illuminate\Database\Eloquent\Model;
use Filament\Forms\Components\DatePicker;
use Filament\Forms\Components\TextInput;
use Filament\Forms\Components\Select;
use Filament\Forms\Components\Toggle;
use Filament\Forms\Components\TagsInput;
use Filament\Tables\Columns\TextColumn;
use Filament\Tables\Columns\ToggleColumn;
use Filament\Tables\Columns\IconColumn;
use FilamentTiptapEditor\TiptapEditor;

class JournalEntryResource extends Resource
{
    protected static ?string $model = JournalEntry::class;

    protected static ?string $navigationIcon = 'heroicon-o-book-open';

    protected static ?string $navigationGroup = 'Journal Management';

    protected static ?string $recordTitleAttribute = 'title';

    public static function form(Form $form): Form
    {
        return $form
            ->schema([
                Forms\Components\Section::make('Basic Information')
                    ->schema([
                        TextInput::make('title')
                            ->required()
                            ->maxLength(255),

                        TiptapEditor::make('content')
                            ->profile('default')
                            ->disk('public')
                            ->directory('journal-images')
                            ->label('Content')
                            ->required(),

                        DatePicker::make('date')
                            ->required()
                            ->default(now()),

                        Select::make('challenge_id')
                            ->relationship('challenge', 'name')
                            ->searchable()
                            ->preload(),

                        TagsInput::make('tags')
                            ->separator(','),

                        Toggle::make('is_private')
                            ->label('Private Entry')
                            ->helperText('If enabled, this entry will only be visible to you')
                            ->default(false),
                    ]),
            ]);
    }

    public static function table(Table $table): Table
    {
        return $table
            ->columns([
                TextColumn::make('title')
                    ->searchable()
                    ->sortable(),
                
                TextColumn::make('date')
                    ->date()
                    ->sortable(),
                
                TextColumn::make('challenge.name')
                    ->label('Challenge')
                    ->searchable()
                    ->sortable(),
                
                IconColumn::make('is_private')
                    ->boolean()
                    ->label('Private'),
                
                TextColumn::make('created_at')
                    ->dateTime()
                    ->sortable()
                    ->toggleable(isToggledHiddenByDefault: true),
            ])
            ->filters([
                Tables\Filters\Filter::make('private')
                    ->query(fn (Builder $query): Builder => $query->where('is_private', true))
                    ->label('Private Entries'),
                
                Tables\Filters\Filter::make('public')
                    ->query(fn (Builder $query): Builder => $query->where('is_private', false))
                    ->label('Public Entries'),
            ])
            ->actions([
                Tables\Actions\EditAction::make(),
                Tables\Actions\DeleteAction::make(),
            ])
            ->bulkActions([
                Tables\Actions\BulkActionGroup::make([
                    Tables\Actions\DeleteBulkAction::make(),
                ]),
            ]);
    }

    public static function getRelations(): array
    {
        return [
            //
        ];
    }

    public static function getPages(): array
    {
        return [
            'index' => Pages\ListJournalEntries::class,
            'create' => Pages\CreateJournalEntry::class,
            'edit' => Pages\EditJournalEntry::class,
        ];
    }

    public static function getNavigationBadge(): ?string
    {
        return static::getModel()::count();
    }
}

