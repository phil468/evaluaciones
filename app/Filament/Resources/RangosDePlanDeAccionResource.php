<?php

namespace App\Filament\Resources;

use App\Filament\Resources\RangosDePlanDeAccionResource\Pages;
use App\Filament\Resources\RangosDePlanDeAccionResource\RelationManagers;
use App\Models\RangosDePlanDeAccion;
use Filament\Forms;
use Filament\Resources\Form;
use Filament\Resources\Resource;
use Filament\Resources\Table;
use Filament\Tables;
use Illuminate\Database\Eloquent\Builder;
use Illuminate\Database\Eloquent\SoftDeletingScope;

class RangosDePlanDeAccionResource extends Resource
{
    protected static ?string $model = RangosDePlanDeAccion::class;

    protected static ?string $navigationIcon = 'heroicon-o-collection';

    public static function form(Form $form): Form
    {
        return $form
            ->schema([
                Forms\Components\TextInput::make('name')
                    ->required()
                    ->maxLength(50),
                Forms\Components\ColorPicker::make('color')->rgba()
                    // ->maxLength(50)
                    ,
                Forms\Components\Toggle::make('estado')
                    ->required(),
                Forms\Components\TextInput::make('nombre_para_mostrar')
                    ->required()
                    ->maxLength(50),
                Forms\Components\TextInput::make('descripción')
                    ->maxLength(50),
                Forms\Components\TextInput::make('rango_mayor')
                    ->required(),
            ]);
    }

    public static function table(Table $table): Table
    {
        return $table
            ->columns([
                Tables\Columns\TextColumn::make('name'),
                Tables\Columns\ColorColumn::make('color'),
                Tables\Columns\TextColumn::make('created_at')
                    ->dateTime(),
                Tables\Columns\TextColumn::make('updated_at')
                    ->dateTime(),
                Tables\Columns\TextColumn::make('deleted_at')
                    ->dateTime(),
                Tables\Columns\IconColumn::make('estado')
                    ->boolean(),
                Tables\Columns\TextColumn::make('nombre_para_mostrar'),
                Tables\Columns\TextColumn::make('descripción'),
                Tables\Columns\TextColumn::make('rango_mayor'),
            ])
            ->filters([
                //
            ])
            ->actions([
                Tables\Actions\ViewAction::make(),
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
            'index' => Pages\ListRangosDePlanDeAccions::route('/'),
            'create' => Pages\CreateRangosDePlanDeAccion::route('/create'),
            'view' => Pages\ViewRangosDePlanDeAccion::route('/{record}'),
            'edit' => Pages\EditRangosDePlanDeAccion::route('/{record}/edit'),
        ];
    }    
}
