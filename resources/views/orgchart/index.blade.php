@extends('adminlte::page')

@section('title', 'Organigrama')

@section('content_header')
{{-- <h1></h1> --}}
<h5 class="h3">Organigrama de la Empresa</h5>
@stop

@section('content')
<div class="container-fluid">
    <div class="row justify-content-center">
        <div class="col-md-12">
            <div class="card rounded-xl">
                {{-- <div class="text-white card-header bg-vanguard rounded-t-xl"> --}}
                    {{-- <h5 class="h3">Organigrama de la Empresa</h5> --}}
                {{-- </div> --}}
                {{-- <div class="card-body"> --}}
                    {{-- <div class="row">
                        <div class="mb-3 col-md-12">
                            <div class="btn-group">
                                <button id="zoomIn" class="btn btn-sm btn-info">
                                    <i class="fas fa-search-plus"></i> Acercar
                                </button>
                                <button id="zoomOut" class="btn btn-sm btn-secondary">
                                    <i class="fas fa-search-minus"></i> Alejar
                                </button>
                                <button id="fitToScreen" class="btn btn-sm btn-success">
                                    <i class="fas fa-expand"></i> Ajustar a pantalla
                                </button>
                                <button id="exportPNG" class="btn btn-sm btn-warning">
                                    <i class="fas fa-download"></i> Exportar PNG
                                </button>
                                <button id="exportPDF" class="btn btn-sm btn-danger">
                                    <i class="fas fa-file-pdf"></i> Exportar PDF
                                </button>
                            </div>
                        </div>
                    </div> --}}
                    <div id="chart-container" style="
                    width: 100%; overflow: hidden; 
                    "></div>
                {{-- </div> --}}
            </div>
        </div>
    </div>
</div>
@stop

@section('css')
    <style>
        /* Estilo del contenedor principal */
        #chart-container {
            background-color: #f9f9f9;
            border-radius: 8px;
            box-shadow: 0 0 10px rgba(0,0,0,0.05);
        }
        
        /* Estilos para los nodos */
        .node-card {
            border-radius: 8px;
            box-shadow: 0 4px 8px rgba(0,0,0,0.1);
            transition: all 0.3s ease;
            overflow: hidden;
            background-color: white;
            border: 1px solid #efefef;
        }
        
        .node-card:hover {
            box-shadow: 0 8px 16px rgba(0,0,0,0.2);
            transform: translateY(-3px);
        }
        
        .node-header {
            background-color: #039BE5;
            color: white;
            text-align: center;
            padding: 10px;
            font-weight: bold;
        }
        
        .node-body {
            padding: 12px;
            text-align: center;
        }
        
        .node-avatar {
            width: 65px;
            height: 65px;
            border-radius: 50%;
            margin: 0 auto 10px;
            object-fit: cover;
            border: 3px solid white;
            box-shadow: 0 2px 5px rgba(0,0,0,0.2);
        }
        
        .node-name {
            font-size: 14px;
            font-weight: bold;
            margin-bottom: 5px;
            color: #333;
        }
        
        .node-position {
            font-size: 12px;
            color: #666;
            margin-bottom: 5px;
        }
        
        .node-area {
            font-size: 11px;
            font-style: italic;
            color: #888;
            margin-bottom: 5px;
        }
        
        .node-email {
            font-size: 10px;
            color: #999;
            word-break: break-all;
        }

        /* Estilos para los tooltips */
        .d3-org-tooltip {
            background-color: rgba(0,0,0,0.8);
            color: white;
            padding: 8px;
            border-radius: 4px;
            font-size: 12px;
            pointer-events: none;
        }
        
        /* Estilo para los botones de navegación */
        .btn-control-panel {
            margin-bottom: 15px;
            background-color: white;
            padding: 10px;
            border-radius: 8px;
            box-shadow: 0 2px 5px rgba(0,0,0,0.1);
        }
        
        /* Estilos para estados expandido/colapsado */
        .node-collapsed-indicator {
            background-color: #FF9800;
            color: white;
            border-radius: 50%;
            width: 20px;
            height: 20px;
            display: flex;
            align-items: center;
            justify-content: center;
            font-size: 12px;
            position: absolute;
            bottom: -5px;
            right: -5px;
        }
    </style>
@stop

@section('js')
{{-- <script src="https://cdnjs.cloudflare.com/ajax/libs/html2canvas/1.4.1/html2canvas.min.js"></script>
<script src="https://cdnjs.cloudflare.com/ajax/libs/jspdf/2.5.1/jspdf.umd.min.js"></script> --}}

<script>
document.addEventListener('DOMContentLoaded', function() {
    // Datos del organigrama desde el controlador
    const data = @json($orgChartData);
    
    // let chart = new d3.OrgChart()
    // .container('#chart-container')
    // .data(data)
    // .render();
    
    
    new d3.OrgChart()
      .nodeHeight((d) => 85 + 25)
      .nodeWidth((d) => 220 + 2)
      .childrenMargin((d) => 50)
      .compactMarginBetween((d) => 35)
      .compactMarginPair((d) => 30)
      .neighbourMargin((a, b) => 20)
      .nodeContent(function (d, i, arr, state) {
        const color = '#FFFFFF';
        const imageDiffVert = 25 + 2;
        return `
                <div style='width:${
                  d.width
                }px;height:${d.height}px;padding-top:${imageDiffVert - 2}px;padding-left:1px;padding-right:1px'>
                        <div style="font-family: 'Inter', sans-serif;background-color:${color};  margin-left:-1px;width:${d.width - 2}px;height:${d.height - imageDiffVert}px;border-radius:10px;border: 1px solid #E4E2E9">
                            <div style="display:flex;justify-content:flex-end;margin-top:5px;margin-right:8px">#${
                              d.data.id
                            }</div>
                            <div style="background-color:${color};margin-top:${-imageDiffVert - 20}px;margin-left:${15}px;border-radius:100px;width:50px;height:50px;" ></div>
                            <div style="margin-top:${
                              -imageDiffVert - 20
                            }px;">   <img src=" ${d.data.image}" style="margin-left:${20}px;border-radius:100px;width:40px;height:40px;" /></div>
                            <div style="font-size:15px;color:#08011E;margin-left:20px;margin-top:10px">  ${
                              d.data.name
                            } </div>
                            <div style="color:#716E7B;margin-left:20px;margin-top:3px;font-size:10px;"> ${
                              d.data.position
                            } </div>

                        </div>
                    </div>
                            `;
      })
      .container('#chart-container')
      .data(data)
      .render();
      
});
</script>
@stop