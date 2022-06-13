@extends('layout')
@section('content')
    <form method="POST">
        <div class="row">
            <div class="six columns">
                <label for="telefono">Phones by DNIs </label>
               <!-- <input class="u-full-width" type="text" placeholder="02717709-0" id="dni" name="dni" value="{{ isset($dni) ? $dni : '' }}" autocomplete="off">-->
                <textarea class="u-full-width" type="text" placeholder="02717709-0" id="dni" name="dni" rows='5'> {{ isset($dni) ? $dni : '' }}</textarea>

            </div>
            <div class="six columns" style='display:none;'>
                <label for="telefono">Date range</label>
                <input name="date_from" class="datetimepicker" type="text" placeholder="From" >
                <input name="date_to" class="datetimepicker" type="text" placeholder="To">
            </div>
        </div>
        <input class="button-primary" type="submit" value="Search">
        @csrf
    </form>

    <hr />

    
    @if (isset($salidas))
    <a href="{{ $csv }}" target="_blank">Download {{ $csv }}</a>
    <table class="table table-bordered table-hover">
    <thead>
        <th>Id Comercio</th>
        <th>DNI</th>
        <th>Tel</th>
        <th style=" width:'600em'">Nombre</th>
        <th>Fecha Creacion</th>        
        <th></th>
    </thead>
    <tbody>
      
        @foreach ($salidas as $salida)
        <tr>
             <td>{{ $salida->idtx }}</td>
            <td>{{ $salida->dni }}</td>
            <td>{{ $salida->telefono }}</td>
            <td>{{ $salida->nombre }}</td>
            <td>{{ $salida->fechacreacion }}</td>
            <td><a target='_blank' class="button" href="{{ url("/kyc",['dni' => $salida->dni]) }}">KYC</a></td>
            <td>
</tr>
@endforeach
</tbody>
</table>
    @endif
    
@endsection

@section('script')
<script type="text/javascript">
    $('.datetimepicker').datetimepicker({
        format:'Y-m-d H:i:s',
    });
</script>
@endsection