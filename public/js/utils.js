// resources/js/utils.js
function deleteItem(modelName, itemId, route, successCallback, errorCallback, gender = 'male') {
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
                url: route.replace(':id', itemId),
                type: 'DELETE',
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

function showLoading(text, subtext = '') {
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

function saveOrUpdateItem({ 
    modelName, 
    url, 
    method = 'POST', 
    data, 
    modalSelector, 
    successCallback, 
    errorCallback, 
    loadingText = 'Guardando...', 
    successText = 'Guardado correctamente.' 
}) {
    showLoading(loadingText, `Por favor espera mientras se guarda ${modelName}.`);
    $.ajax({
        url: url,
        type: method,
        data: data,
        success: function(response) {
            if (modalSelector) $(modalSelector).modal('hide');
            Swal.close();
            Swal.fire('¡Éxito!', successText, 'success');
            if (successCallback) successCallback(response);
        },
        error: function(xhr) {
            let errorMessage = 'Error al guardar.';
            if (xhr.responseJSON && xhr.responseJSON.errors) {
                errorMessage = Object.values(xhr.responseJSON.errors).map(arr => arr[0]).join('\n');
            } else if (xhr.responseJSON && xhr.responseJSON.message) {
                errorMessage = xhr.responseJSON.message;
            }
            Swal.close();
            Swal.fire('¡Error!', errorMessage, 'error');
            if (errorCallback) errorCallback(errorMessage);
        }
    });
}

// function hideModal(modalSelector) {
//     if (modalSelector) {
//         $(modalSelector).modal('hide');
//     }
// }

// $('.modal').modal('hide')

// Exponer globalmente
window.deleteItem = deleteItem;
window.showLoading = showLoading;
window.saveOrUpdateItem = saveOrUpdateItem;