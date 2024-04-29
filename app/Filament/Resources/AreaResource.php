<?php

namespace App\Filament\Resources;

use App\Filament\Resources\AreaResource\Pages;
use App\Filament\Resources\AreaResource\RelationManagers;
use App\Models\Area;
use Filament\Forms;
use Filament\Resources\Form;
use Filament\Resources\Resource;
use Filament\Resources\Table;
use Filament\Tables;
use Illuminate\Database\Eloquent\Builder;
use Illuminate\Database\Eloquent\SoftDeletingScope;

class AreaResource extends Resource
{
    protected static ?string $model = Area::class;

    protected static ?string $navigationIcon = 'heroicon-o-collection';

    public static function form(Form $form): Form
    {
        return $form
            ->schema([
                Forms\Components\TextInput::make('name')
                    ->maxLength(250),
                Forms\Components\Toggle::make('estado'),
                Forms\Components\TextInput::make('idempresa_nisira')
                    ->maxLength(50),
                Forms\Components\TextInput::make('idarea_nisira')
                    ->maxLength(50),
                Forms\Components\DatePicker::make('fechacreacion_nisira'),
                Forms\Components\TextInput::make('gerencia_id'),
                Forms\Components\TextInput::make('idccosto_nisira')
                    ->maxLength(50),
                Forms\Components\TextInput::make('empresa_id'),
                Forms\Components\TextInput::make('centro_costo')
                    ->maxLength(250),
                Forms\Components\TextInput::make('subgerencia_id'),
            ]);
    }

    public static function table(Table $table): Table
    {
        return $table
            ->columns([
                Tables\Columns\TextColumn::make('name'),
                Tables\Columns\IconColumn::make('estado')
                    ->boolean(),
                Tables\Columns\TextColumn::make('created_at')
                    ->dateTime(),
                Tables\Columns\TextColumn::make('updated_at')
                    ->dateTime(),
                Tables\Columns\TextColumn::make('deleted_at')
                    ->dateTime(),
                Tables\Columns\TextColumn::make('idempresa_nisira'),
                Tables\Columns\TextColumn::make('idarea_nisira'),
                Tables\Columns\TextColumn::make('fechacreacion_nisira')
                    ->date(),
                Tables\Columns\TextColumn::make('gerencia_id'),
                Tables\Columns\TextColumn::make('idccosto_nisira'),
                Tables\Columns\TextColumn::make('empresa_id'),
                Tables\Columns\TextColumn::make('centro_costo'),
                Tables\Columns\TextColumn::make('subgerencia_id'),
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
            'index' => Pages\ListAreas::route('/'),
            'create' => Pages\CreateArea::route('/create'),
            'edit' => Pages\EditArea::route('/{record}/edit'),
        ];
    }    
}
