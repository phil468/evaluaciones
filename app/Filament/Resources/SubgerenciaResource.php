<?php

namespace App\Filament\Resources;

use App\Filament\Resources\SubgerenciaResource\Pages;
use App\Filament\Resources\SubgerenciaResource\RelationManagers;
use App\Models\Subgerencia;
use Filament\Forms;
use Filament\Resources\Form;
use Filament\Resources\Resource;
use Filament\Resources\Table;
use Filament\Tables;
use Illuminate\Database\Eloquent\Builder;
use Illuminate\Database\Eloquent\SoftDeletingScope;

class SubgerenciaResource extends Resource
{
    protected static ?string $model = Subgerencia::class;

    protected static ?string $navigationIcon = 'heroicon-o-collection';

    public static function form(Form $form): Form
    {
        return $form
            ->schema([
                Forms\Components\TextInput::make('name')
                    ->maxLength(100),
                Forms\Components\Toggle::make('estado'),
                Forms\Components\TextInput::make('idarea_nisira')
                    ->maxLength(50),
                Forms\Components\Select::make('gerencia_id')
                    ->label('Gerencia')
                    ->options(
                        \App\Models\Gerencia::all()->pluck('name', 'id')
                    )->searchable(),
                    // ->relationship('gerencia', 'name'),
            ]);
    }

    public static function table(Table $table): Table
    {
        return $table
            ->columns([
                Tables\Columns\TextColumn::make('name'),
                Tables\Columns\TextColumn::make('created_at')
                    ->dateTime(),
                Tables\Columns\TextColumn::make('updated_at')
                    ->dateTime(),
                Tables\Columns\TextColumn::make('deleted_at')
                    ->dateTime(),
                Tables\Columns\IconColumn::make('estado')
                    ->boolean(),
                Tables\Columns\TextColumn::make('idarea_nisira'),
                Tables\Columns\TextColumn::make('gerencia_id'),
            ])
            ->filters([
                //
            ])
            ->actions([
                Tables\Actions\EditAction::make(),
            ])
            ->bulkActions([
                Tables\Actions\DeleteBulkAction::make(),
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
            'index' => Pages\ListSubgerencias::route('/'),
            'create' => Pages\CreateSubgerencia::route('/create'),
            'edit' => Pages\EditSubgerencia::route('/{record}/edit'),
        ];
    }    
}
