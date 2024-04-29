<?php

namespace App\Filament\Resources;

use App\Filament\Resources\PersonalResource\Pages;
use App\Filament\Resources\PersonalResource\RelationManagers;
use App\Models\Personal;
use Filament\Forms;
use Filament\Resources\Form;
use Filament\Resources\Resource;
use Filament\Resources\Table;
use Filament\Tables;
use Illuminate\Database\Eloquent\Builder;
use Illuminate\Database\Eloquent\SoftDeletingScope;

class PersonalResource extends Resource
{
    protected static ?string $model = Personal::class;

    protected static ?string $navigationIcon = 'heroicon-o-collection';

    public static function form(Form $form): Form
    {
        return $form
            ->schema([
                Forms\Components\TextInput::make('dni')
                    ->maxLength(100),
                Forms\Components\TextInput::make('name')
                    ->maxLength(250),
                Forms\Components\TextInput::make('nombres')
                    ->maxLength(250),
                Forms\Components\TextInput::make('apellido_paterno')
                    ->maxLength(250),
                Forms\Components\TextInput::make('apellido_materno')
                    ->maxLength(250),
                Forms\Components\TextInput::make('empresa_id'),
                Forms\Components\TextInput::make('gerencia_id'),
                Forms\Components\TextInput::make('sede_id'),
                Forms\Components\TextInput::make('area_id'),
                Forms\Components\TextInput::make('cargo_id'),
                Forms\Components\TextInput::make('correo_empresa')
                    ->maxLength(250),
                Forms\Components\TextInput::make('celular_empresa')
                    ->maxLength(250),
                Forms\Components\TextInput::make('correo_personal')
                    ->maxLength(250),
                Forms\Components\TextInput::make('telefono_personal')
                    ->tel()
                    ->maxLength(20),
                Forms\Components\TextInput::make('celular_personal')
                    ->maxLength(20),
                Forms\Components\TextInput::make('foto')
                    ->maxLength(200),
                Forms\Components\Toggle::make('estado'),
                Forms\Components\TextInput::make('genero')
                    ->maxLength(1),
                Forms\Components\DatePicker::make('fecha_ingreso'),
                Forms\Components\TextInput::make('firma')
                    ->maxLength(65535),
                Forms\Components\TextInput::make('personal_id'),
                Forms\Components\TextInput::make('tipo_de_trabajador_id'),
                Forms\Components\TextInput::make('tipo_de_personal_id'),
                Forms\Components\TextInput::make('planilla_id'),
                Forms\Components\TextInput::make('sexo')
                    ->maxLength(1),
                Forms\Components\DatePicker::make('fecha_cese'),
                Forms\Components\Toggle::make('cesado'),
                Forms\Components\Toggle::make('importado'),
                Forms\Components\Toggle::make('expositor'),
            ]);
    }

    public static function table(Table $table): Table
    {
        return $table
            ->columns([
                Tables\Columns\TextColumn::make('dni'),
                Tables\Columns\TextColumn::make('name'),
                Tables\Columns\TextColumn::make('nombres'),
                Tables\Columns\TextColumn::make('apellido_paterno'),
                Tables\Columns\TextColumn::make('apellido_materno'),
                Tables\Columns\TextColumn::make('empresa_id'),
                Tables\Columns\TextColumn::make('gerencia_id'),
                Tables\Columns\TextColumn::make('sede_id'),
                Tables\Columns\TextColumn::make('area_id'),
                Tables\Columns\TextColumn::make('cargo_id'),
                Tables\Columns\TextColumn::make('correo_empresa'),
                Tables\Columns\TextColumn::make('celular_empresa'),
                Tables\Columns\TextColumn::make('correo_personal'),
                Tables\Columns\TextColumn::make('telefono_personal'),
                Tables\Columns\TextColumn::make('celular_personal'),
                Tables\Columns\TextColumn::make('foto'),
                Tables\Columns\TextColumn::make('created_at')
                    ->dateTime(),
                Tables\Columns\TextColumn::make('updated_at')
                    ->dateTime(),
                Tables\Columns\TextColumn::make('deleted_at')
                    ->dateTime(),
                Tables\Columns\IconColumn::make('estado')
                    ->boolean(),
                Tables\Columns\TextColumn::make('genero'),
                Tables\Columns\TextColumn::make('fecha_ingreso')
                    ->date(),
                Tables\Columns\TextColumn::make('firma'),
                Tables\Columns\TextColumn::make('personal_id'),
                Tables\Columns\TextColumn::make('tipo_de_trabajador_id'),
                Tables\Columns\TextColumn::make('tipo_de_personal_id'),
                Tables\Columns\TextColumn::make('planilla_id'),
                Tables\Columns\TextColumn::make('sexo'),
                Tables\Columns\TextColumn::make('fecha_cese')
                    ->date(),
                Tables\Columns\IconColumn::make('cesado')
                    ->boolean(),
                Tables\Columns\IconColumn::make('importado')
                    ->boolean(),
                Tables\Columns\IconColumn::make('expositor')
                    ->boolean(),
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
            'index' => Pages\ListPersonals::route('/'),
            'create' => Pages\CreatePersonal::route('/create'),
            'edit' => Pages\EditPersonal::route('/{record}/edit'),
        ];
    }    
}
