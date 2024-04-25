<?php

namespace App\Filament\Resources;

use App\Filament\Resources\EvaluacioneResource\Pages;
use App\Filament\Resources\EvaluacioneResource\RelationManagers;
use App\Models\Evaluacione;
use Clockwork\Request\Request;
use Filament\Forms;
use Filament\Forms\Components\DateTimePicker;
use Filament\Forms\Components\Repeater;
use Filament\Resources\Form;
use Filament\Resources\Resource;
use Filament\Resources\Table;
use Filament\Tables;
use Filament\Tables\Columns\Layout\Split;
use Illuminate\Contracts\View\View;
use Illuminate\Database\Eloquent\Builder;
use Illuminate\Database\Eloquent\SoftDeletingScope;
use Illuminate\Support\Facades\DB;

class EvaluacioneResource extends Resource
{
    protected static ?string $model = Evaluacione::class;

    protected static ?string $navigationIcon = 'heroicon-o-collection';

    // protected static ?string $modelLabel = 'Evaluación';

    // protected static ?string $navigationLabel = 'Evaluaciones';

    protected static ?string $label = 'Evaluaciones';
    
    // protected static ?string $recordRouteKeyName = 'Evaluación--';


    

    public static function form(Form $form): Form
    {
        return $form
            ->schema([
                Forms\Components\TextInput::make('title')
                ->name('Título')
                    ->maxLength(100)
                    ->required(),
                Forms\Components\Toggle::make('status')
                ->name('Habilitado'),
                Forms\Components\TextInput::make('nombre_para_mostrar')->required(),
                Forms\Components\TextInput::make('campania')->name('Campaña')->required(),
                Forms\Components\DatePicker::make('fecha_inicio')->displayFormat('d/m/Y')->required()->beforeOrEqual('fecha_fin'),
                Forms\Components\DatePicker::make('fecha_fin')->displayFormat('d/m/Y')->required()->afterOrEqual('fecha_inicio'),
                Forms\Components\TextInput::make('identificador')->maxLength(20)->unique(ignoreRecord: true),
                Repeater::make('recordatorios')
                ->relationship()
                ->schema([
                    //que se pueda seleecionar cada 10  minutos                    
                    DateTimePicker::make('fecha')
                        ->label('Fecha del recordatorio')
                        ->displayFormat('d/m/Y H:i')
                        // ->format('d/m/Y H:i')
                        ->required()
                        // ->beforeOrEqual('fecha_fin')
                        ->afterOrEqual('fecha_inicio')
                        // ->timezone('America/Lima')
                        ->minDate('fecha_inicio')
                        // ->maxDate('fecha_fin')
                        ->minutesStep(10)
                        ->secondsStep(60)

                        // ->step(10)
                        ,
                ]),
            ]);
    }

    public static function table(Table $table): Table
    {
        return 
            $table
            ->columns([
                // Split::make([
                Tables\Columns\TextColumn::make('title')
                    ->label('Título')
                    ->searchable()
                    ->sortable(),
                    Tables\Columns\TextColumn::make('identificador')->searchable()->sortable(),
                Tables\Columns\IconColumn::make('status')
                    ->label('Habilitado')
                    ->boolean()
                    ->searchable()
                    ->sortable(),
                Tables\Columns\TextColumn::make('nombre_para_mostrar')->searchable()->sortable(),
                Tables\Columns\TextColumn::make('campania')->label('Campaña')->searchable()->sortable(),
                Tables\Columns\TextColumn::make('fecha_inicio')->searchable()->sortable()->date('d/m/Y'),
                Tables\Columns\TextColumn::make('fecha_fin')->searchable()->sortable()->date('d/m/Y'),
                Tables\Columns\TextColumn::make('recordatorios.fecha')
                    ->label('Recordatorios')
                    ->searchable()
                    ->sortable(),
                Tables\Columns\TextColumn::make('created_at')->label('Creación')
                    ->dateTime(),
                Tables\Columns\TextColumn::make('updated_at')->label('Modificación')
                    ->dateTime(),
                // Tables\Columns\TextColumn::make('deleted_at')
                //     ->dateTime(),
                // quiero ver los recordatorios.fecha
                // agrupar
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
            'index' => Pages\ListEvaluaciones::route('/'),
            'create' => Pages\CreateEvaluacione::route('/create'),
            'edit' => Pages\EditEvaluacione::route('/{record}/edit'),
        ];
    }

    // public static function storeRecord(Request $request, $record)
    // {
    //     DB::transaction(function () use ($request, $record) {
    //         $record->save();
    
    //         foreach ($request->input('recordatorios', []) as $data) {
    //             $record->recordatorios()->create($data);
    //         }
    //     });
    
    //     return redirect('admin/evaluaciones');
    // }
    
    // public static function updateRecord(Request $request, $record)
    // {
    //     DB::transaction(function () use ($request, $record) {
    //         $record->update($request->all());
    
    //         // Actualiza los recordatorios aquí si es necesario
    //     });
    
    //     return redirect('admin/evaluaciones');
    // }
    // public static function storeRecord(Request $request, $record)
    // {
    //     DB::transaction(function () use ($request, $record) {
    //         $record->save();
    
    //         foreach ($request->input('recordatorios', []) as $data) {
    //             $record->recordatorios()->create($data);
    //         }
    //     });
    // }
    
    // public static function updateRecord(Request $request, $record)
    // {
    //     DB::transaction(function () use ($request, $record) {
    //         dd($request->all());
    //         $record->update($request->all());
    
    //         $record->recordatorios()->delete();
    
    //         foreach ($request->input('recordatorios', []) as $data) {
    //             $record->recordatorios()->create($data);
    //         }
    //     });
    // }

    protected function getFooter(): View
        {
            return view('footer');
        }
}
