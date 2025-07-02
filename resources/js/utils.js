// resources/js/utils.js
export function deleteItem(modelName, itemId, route, successCallback, errorCallback, gender = 'male') {
    const articulo = gender === 'female' ? 'la' : 'el';
    const pronombreDemostrativo = gender === 'female' ? 'esta' : 'este';
    const eliminadoTexto = gender === 'female' ? 'eliminada' : 'eliminado';

    Swal.fire({
        title: '¿Estás seguro?',
        text: `¿Deseas eliminar ${pronombreDemostrativo} ${modelName}?`,
        icon: 'warning',
        showCancelButton: true,
        confirmButtonColor: '#3085d6',
        cancelButtonColor: '#d33',
        confirmButtonText: 'Sí, eliminar',
        cancelButtonText: 'Cancelar'
    }).then((result) => {
        if (result.isConfirmed) {
            showLoading(`Eliminando...`, `Por favor espera mientras se elimina ${articulo} ${modelName}.`);
            $.ajax({
                url: `${route}/${itemId}`,
                type: 'DELETE',
                // headers: {
                //     'X-CSRF-TOKEN': document.querySelector('meta[name="csrf-token"]').getAttribute('content')
                // },
                // data: {
                //     _token: document.querySelector('meta[name="csrf-token"]').getAttribute('content')
                // },
                // data: { _token: '{{ csrf_token() }}' },getCsrfToken 
                success: function() {
                    Swal.close();
                    Swal.fire('Éxito', `${modelName} ${eliminadoTexto} correctamente`, 'success');
                    if (successCallback) successCallback();
                },
                error: function(xhr) {
                    var errorMessage = xhr.responseJSON?.message || `Error al eliminar ${articulo} ${modelName}`;
                    Swal.close();
                    Swal.fire('Error', errorMessage, 'error');
                    if (errorCallback) errorCallback(errorMessage);
                },
            });
        }
    });
}

export function showLoading(text, subtext = '') {
    Swal.fire({
        title: text,
        icon: 'info',
        text: subtext,
        allowOutsideClick: false,
        showConfirmButton: false,
        didOpen: () => {
            Swal.showLoading();
        }
    });
}