{{-- filepath: c:\laragon\www\evaluaciones\resources\views\livewire\planes-de-accion-table.blade.php --}}
<div>
    {{-- Tabla existente --}}
    {{-- <div class="overflow-hidden bg-white shadow-xl sm:rounded-lg">
        @livewire('planes-de-accion-table')
    </div> --}}

    {{-- Modal de validación --}}
    @if($showModalValidacion)
    {{-- {{ dd("mostrar validacion") }} --}}
    <div class="fixed inset-0 z-50 overflow-y-auto" style="background: rgba(0,0,0,0.5);" wire:click="cerrarModalValidacion">
        <div class="flex items-center justify-center min-h-screen p-4">
            <div class="w-full max-w-md bg-white rounded-lg shadow-xl" wire:click.stop>
                <div class="p-6">
                    <div class="flex items-center justify-between mb-4">
                        <h3 class="text-lg font-medium">
                            {{ $estadoValidacion === 'validado' ? 'Validar Plan de Mejora' : 'Rechazar Plan de Mejora' }}
                        </h3>
                        <button wire:click="cerrarModalValidacion" class="text-gray-400 hover:text-gray-600">
                            <i class="fas fa-times"></i>
                        </button>
                    </div>
                    
                    @if($estadoValidacion === 'validado')
                        <div class="flex items-center p-3 mb-4 text-green-700 bg-green-100 rounded-lg">
                            <i class="mr-2 fas fa-check-circle"></i>
                            <span>¿Confirma que desea validar este plan de mejora?</span>
                        </div>
                    @else
                        <div class="flex items-center p-3 mb-4 text-red-700 bg-red-100 rounded-lg">
                            <i class="mr-2 fas fa-exclamation-triangle"></i>
                            <span>Por favor, indique las observaciones para el rechazo:</span>
                        </div>
                    @endif

                    <div class="mb-4">
                        <label class="block mb-2 text-sm font-medium text-gray-700">
                            Observaciones {{ $estadoValidacion === 'no_validado' ? '*' : '(opcional)' }}
                        </label>
                        <textarea 
                            wire:model="observacionValidacion"
                            class="w-full px-3 py-2 border border-gray-300 rounded-md focus:outline-none focus:ring-2 focus:ring-blue-500 focus:border-blue-500"
                            rows="{{ $estadoValidacion === 'no_validado' ? '4' : '3' }}"
                            placeholder="{{ $estadoValidacion === 'no_validado' ? 'Escriba las observaciones detalladas...' : 'Comentarios adicionales...' }}"></textarea>
                        @error('observacionValidacion')
                            <p class="mt-1 text-sm text-red-500">{{ $message }}</p>
                        @enderror
                    </div>

                    <div class="flex justify-end space-x-3">
                        <button 
                            wire:click="cerrarModalValidacion"
                            class="px-4 py-2 text-sm font-medium text-gray-700 bg-gray-100 rounded-md hover:bg-gray-200 focus:outline-none focus:ring-2 focus:ring-gray-500">
                            Cancelar
                        </button>
                        <button 
                            wire:click="procesarValidacion"
                            class="px-4 py-2 text-sm font-medium text-white rounded-md focus:outline-none focus:ring-2
                                   {{ $estadoValidacion === 'validado' ? 'bg-green-600 hover:bg-green-700 focus:ring-green-500' : 'bg-red-600 hover:bg-red-700 focus:ring-red-500' }}">
                            {{ $estadoValidacion === 'validado' ? 'Validar Plan' : 'Rechazar Plan' }}
                        </button>
                    </div>
                </div>
            </div>
        </div>
    </div>
    @endif
</div>