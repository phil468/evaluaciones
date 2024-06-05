<!-- Modal -->
<div wire:ignore.self class="modal fade" id="firmaModal" data-backdrop="static" tabindex="-1" role="dialog" aria-labelledby="createDataModalLabel" aria-hidden="true">
    <div class="modal-dialog modal-lg" role="document">
        <div class="rounded-2xl modal-content">
            <div class="text-white modal-header bg-vanguard rounded-t-2xl">                <h5 class="modal-title" id="firmaModalLabel">Agregar Firma</h5>
                <button type="button" class="close text-white" data-dismiss="modal" aria-label="Close">
                     <span aria-hidden="true close-btn">×</span>
                </button>
            </div>
           <div class="modal-body">            
            <canvas id="firma" width="450" height="300"></canvas>
            <br>
            <a class="btn btn-sm btn-default" id="btnLimpiar">Limpiar</a>
            {{-- <button id="btnDescargar">Guardar</button> --}}
            <a class="btn btn-primary close-modal" id="btnGenerarDocumento">Guardar</a>
            </div>
            @push('js')
<script>
    
document.addEventListener('livewire:load', function () {
const $canvas = document.getElementById("firma");
// $btnDescargar = document.querySelector("#btnDescargar"),
$btnLimpiar = document.querySelector("#btnLimpiar");
$btnGenerarDocumento = document.querySelector("#btnGenerarDocumento");
const contexto = $canvas.getContext("2d");
const COLOR_PINCEL = "black";
const COLOR_FONDO = "white";
const GROSOR = 2;
let xAnterior = 0, yAnterior = 0, xActual = 0, yActual = 0;
const obtenerXReal = (clientX) => clientX - $canvas.getBoundingClientRect().left;
const obtenerYReal = (clientY) => clientY - $canvas.getBoundingClientRect().top;
let haComenzadoDibujo = false; // Bandera que indica si el usuario está presionando el botón del mouse sin soltarlo


const limpiarCanvas = () => {
    // Colocar color blanco en fondo de canvas
    contexto.fillStyle = COLOR_FONDO;
    contexto.fillRect(0, 0, $canvas.width, $canvas.height);
};
limpiarCanvas();
$btnLimpiar.onclick = limpiarCanvas;
// Escuchar clic del botón para descargar el canvas
// $btnDescargar.onclick = () => {
//     const enlace = document.createElement('a');
//     // El título
//     enlace.download = "Firma.png";
//     // Convertir la imagen a Base64 y ponerlo en el enlace
//     enlace.href = $canvas.toDataURL();
//     // Hacer click en él
//     enlace.click();
// };

window.obtenerImagen = () => {
    return $canvas.toDataURL();
};

$btnGenerarDocumento.onclick = () => {
    // console.log($canvas.toDataURL());
    
    @this.firma = $canvas.toDataURL();

    $('#firmaModal').modal('hide');

    $btnGenerarDocumento.disabled = true; 
    Livewire.emit('guardarFirma')
    limpiarCanvas();
};
// Lo demás tiene que ver con pintar sobre el canvas en los eventos del mouse
$canvas.addEventListener("mousedown", evento => {
    // En este evento solo se ha iniciado el clic, así que dibujamos un punto
    xAnterior = xActual;
    yAnterior = yActual;
    xActual = obtenerXReal(evento.clientX);
    yActual = obtenerYReal(evento.clientY);
    contexto.beginPath();
    contexto.fillStyle = COLOR_PINCEL;
    contexto.fillRect(xActual, yActual, GROSOR, GROSOR);
    contexto.closePath();
    // Y establecemos la bandera
    haComenzadoDibujo = true;
});

$canvas.addEventListener("mousemove", (evento) => {
    if (!haComenzadoDibujo) {
        return;
    }
    // El mouse se está moviendo y el usuario está presionando el botón, así que dibujamos todo

    xAnterior = xActual;
    yAnterior = yActual;
    xActual = obtenerXReal(evento.clientX);
    yActual = obtenerYReal(evento.clientY);
    contexto.beginPath();
    contexto.moveTo(xAnterior, yAnterior);
    contexto.lineTo(xActual, yActual);
    contexto.strokeStyle = COLOR_PINCEL;
    contexto.lineWidth = GROSOR;
    contexto.stroke();
    contexto.closePath();
});
["mouseup", "mouseout"].forEach(nombreDeEvento => {
    $canvas.addEventListener(nombreDeEvento, () => {
        haComenzadoDibujo = false;
    });
});

    var canvas2 = document.getElementById('firma');
    var context = canvas2.getContext('2d');
    // context.lineWidth = 0.5; // Ajusta el grosor de la línea según tus preferencias
    // context.lineJoin = "round"; // Suaviza las esquinas del trazo

    var dibujando = false;

    canvas2.addEventListener('touchstart', iniciarTrazo);
    canvas2.addEventListener('touchmove', dibujar);
    canvas2.addEventListener('touchend', finalizarTrazo);
   
    // Función para iniciar el trazo
    function iniciarTrazo(event) {
        event.preventDefault();
        // xAnterior = xActual;
        // yAnterior = yActual;
        // xActual = obtenerXReal2(event.clientX);
        // yActual = obtenerYReal2(event.clientY);
        // contexto.beginPath();
        // contexto.fillStyle = COLOR_PINCEL;
        // contexto.fillRect(xActual, yActual, GROSOR, GROSOR);
        // contexto.closePath();
        // Y establecemos la bandera
        // haComenzadoDibujo = true;

        var touch = event.targetTouches[0];
        var canvasRect = canvas2.getBoundingClientRect(); // Obtener la posición y dimensiones del canvas
        dibujando = true;
        context.beginPath();
        context.moveTo(touch.clientX - canvasRect.left, touch.clientY - canvasRect.top);
    }

    function dibujar(event) {
        event.preventDefault();
    if (dibujando) {

        // xAnterior = xActual;
        // yAnterior = yActual;
        // xActual = obtenerXReal2(event.clientX);
        // yActual = obtenerYReal2(event.clientY);
        // contexto.beginPath();
        // contexto.moveTo(xAnterior, yAnterior);
        // contexto.lineTo(xActual, yActual);
        // contexto.strokeStyle = COLOR_PINCEL;
        // contexto.lineWidth = GROSOR;
        // contexto.stroke();
        // contexto.closePath();
      
        var touch = event.targetTouches[0];
        var canvasRect = canvas2.getBoundingClientRect(); // Obtener la posición y dimensiones del canvas
        context.lineTo(touch.clientX - canvasRect.left, touch.clientY - canvasRect.top);
        context.stroke();
    }
    }

    // Función para finalizar el trazo
    function finalizarTrazo() {
        event.preventDefault();
      dibujando = false;
    }


    })

</script>

@endpush

            {{-- <div class="modal-footer">
                <button type="button" class="btn btn-secondary close-btn" data-dismiss="modal">Cerrar</button>
                <button type="button" wire:click.prevent="store()" class="btn btn-primary close-modal">Guardar</button>
            </div> --}}
        </div>
    </div>
</div>
