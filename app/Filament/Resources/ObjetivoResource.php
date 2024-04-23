<?php

namespace App\Filament\Resources;

use App\Filament\Resources\ObjetivoResource\Pages;
use App\Filament\Resources\ObjetivoResource\RelationManagers;
use App\Models\Objetivo;
use Filament\Forms;
use Filament\Resources\Form;
use Filament\Resources\Resource;
use Filament\Resources\Table;
use Filament\Tables;
use Illuminate\Database\Eloquent\Builder;
use Illuminate\Database\Eloquent\SoftDeletingScope;

class ObjetivoResource extends Resource
{
    protected static ?string $model = Objetivo::class;

    protected static ?string $navigationIcon = 'heroicon-o-collection';

    public static function form(Form $form): Form
    {
        return $form
            ->schema([
                Forms\Components\TextInput::make('id'),
                Forms\Components\TextInput::make('cantidad'),
                Forms\Components\TextInput::make('evalua_id'),
                Forms\Components\TextInput::make('tipo_objetivo'),
            ]);
    }

    public static function table(Table $table): Table
    {
        return $table
            ->columns([
                Tables\Columns\TextColumn::make('id'),
                Tables\Columns\TextColumn::make('cantidad'),
                Tables\Columns\TextColumn::make('evalua_id'),
                Tables\Columns\TextColumn::make('tipo_objetivo'),
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
            'index' => Pages\ListObjetivos::route('/'),
            'create' => Pages\CreateObjetivo::route('/create'),
            'edit' => Pages\EditObjetivo::route('/{record}/edit'),
        ];
    }    
}
