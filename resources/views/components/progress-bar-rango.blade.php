<div class="progress" style="height: 40px">
    @foreach ($rangos as $index=>$rango)
        <div class="progress-bar" role="progressbar" 
        style="width: 25%; background-color: {{ $rango->color }};" 
        aria-valuenow="25" aria-valuemin="0" 
        aria-valuemax="100"> 
            <h5 style="font-weight: 500; font-size: 12px;">{{ $rangos[$index-1]->rango_mayor??0 }} - {{ $rango->rango_mayor }} <br> {{ $rango->name }}</h5>
        </div>
    @endforeach
</div>