<?php

namespace App\Filament\Resources;

use App\Filament\Resources\ObjetivosPrecargadoResource\Pages;
use App\Filament\Resources\ObjetivosPrecargadoResource\RelationManagers;
use App\Models\ObjetivosPrecargado;
use Filament\Forms;
use Filament\Forms\Components\TextInput;
use Filament\Resources\Form;
use Filament\Resources\Resource;
use Filament\Resources\Table;
use Filament\Tables;
use Illuminate\Database\Eloquent\Builder;
use Illuminate\Database\Eloquent\SoftDeletingScope;

class ObjetivosPrecargadoResource extends Resource
{
    protected static ?string $model = ObjetivosPrecargado::class;

    protected static ?string $navigationIcon = 'heroicon-o-collection';

    public static function form(Form $form): Form
    {
        return $form
            ->schema([
                Forms\Components\TextInput::make('meta')
                    ->maxLength(200),
                Forms\Components\Toggle::make('grupal'),
                Forms\Components\TextInput::make('porcentaje_de_participacion')
                ->type('number')
                ->mask(fn (TextInput\Mask $mask) => $mask
                    ->numeric()
                    ->decimalPlaces(2) // Set the number of digits after the decimal point.
                    ->decimalSeparator('.') // Add a separator for decimal numbers.
                    ->integer() // Disallow decimal numbers.
                    ->mapToDecimalSeparator(['.']) // Map additional characters to the decimal separator.
                    ->minValue(0) // Set the minimum value that the number can be.
                    ->maxValue(100) // Set the maximum value that the number can be.
                    ->normalizeZeros() // Append or remove zeros at the end of the number.
                    ->padFractionalZeros() // Pad zeros at the end of the number to always maintain the maximum number of decimal places.
                    ->thousandsSeparator(','), // Add a separator for thousands.
                )
                ,
                Forms\Components\Textarea::make('evidencias')
                    ->maxLength(65535),
                Forms\Components\TextInput::make('resultado_anterior_o_esperado'),
                Forms\Components\TextInput::make('tipo_objetivo_id'),
                Forms\Components\TextInput::make('minimo'),
                Forms\Components\TextInput::make('maximo'),
                Forms\Components\TextInput::make('valor'),
                Forms\Components\TextInput::make('porcentaje_de_logro_STI'),
                Forms\Components\TextInput::make('peso_ponderado'),
                Forms\Components\TextInput::make('evaluacion_id'),
            ]);
    }

    public static function table(Table $table): Table
    {
        return $table
            ->columns([
                Tables\Columns\TextColumn::make('meta'),
                Tables\Columns\TextColumn::make('grupal'),
                Tables\Columns\TextColumn::make('porcentaje_de_participacion'),
                Tables\Columns\TextColumn::make('evidencias'),
                Tables\Columns\TextColumn::make('resultado_anterior_o_esperado'),
                Tables\Columns\TextColumn::make('tipo_objetivo_id'),
                Tables\Columns\TextColumn::make('minimo'),
                Tables\Columns\TextColumn::make('maximo'),
                Tables\Columns\TextColumn::make('valor'),
                Tables\Columns\TextColumn::make('porcentaje_de_logro_STI'),
                Tables\Columns\TextColumn::make('peso_ponderado'),
                Tables\Columns\TextColumn::make('evaluacion_id'),
                Tables\Columns\TextColumn::make('created_at')
                    ->dateTime(),
                Tables\Columns\TextColumn::make('updated_at')
                    ->dateTime(),
                Tables\Columns\TextColumn::make('deleted_at')
                    ->dateTime(),
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
            'index' => Pages\ListObjetivosPrecargados::route('/'),
            'create' => Pages\CreateObjetivosPrecargado::route('/create'),
            'view' => Pages\ViewObjetivosPrecargado::route('/{record}'),
            'edit' => Pages\EditObjetivosPrecargado::route('/{record}/edit'),
        ];
    }    
}
