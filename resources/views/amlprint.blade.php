@extends('layoutprint')
@section('content')
<style>
    @media print
{
.noprint {display:none;}
}
#encabezado {
  font-family: Arial, Helvetica, sans-serif;
  border-collapse: collapse;
  width: 100%;
}

#encabezado td, #encabezado th {
  border: 1px solid #ddd;
  padding: 8px;
}

#encabezado tr:nth-child(even){background-color: #f2f2f2;}

#encabezado tr:hover {background-color: #ddd;}

#encabezado th {
  padding-top: 12px;
  padding-bottom: 12px;
  text-align: center;
  background-color: #002060;
  color: white;
}

#contenido {
  font-family: Arial, Helvetica, sans-serif;
  border-collapse: collapse;
  width: 100%;
}

#contenido td, #contenido th {
  border: 1px solid #ddd;
  padding: 8px;
}

#contenido tr:nth-child(even){background-color: #f2f2f2;}

#contenido tr:hover {background-color: #ddd;}

#contenido th {
  padding-top: 12px;
  padding-bottom: 12px;
  text-align: center;
  background-color: #002060;
  color: white;
}

#saldo {
  font-family: Arial, Helvetica, sans-serif;
  border-collapse: collapse;
}

#saldo td, #encabezado th {
  border: 1px solid #ddd;
  padding: 8px;
}

#saldo tr:nth-child(even){background-color: #f2f2f2;}

#saldo tr:hover {background-color: #ddd;}

#saldo th {
  padding-top: 12px;
  padding-bottom: 12px;
  text-align: center;
  background-color: #002060;
  color: white;
}



</style>
    <form method="POST" class="noprint">
        <div class="row">
            <div class="six columns">
                <label for="telefono">Report by DNI </label>
               <input class="u-full-width" type="text" placeholder="02717709-0" id="dni" name="dni" value="{{ isset($dni) ? $dni : '' }}" autocomplete="off">
               <!--  <textarea class="u-full-width" type="text" placeholder="02717709-0" id="dni" name="dni" rows='5'> {{ isset($dni) ? $dni : '' }}</textarea>-->

            </div>
</div>
            <div class="row">

            <div class="six columns">
                <label for="telefono">Full Name </label>
               <input class="u-full-width" type="text" placeholder="" id="fullname" name="fullname" value="{{ isset($fullname) ? $fullname : '' }}" autocomplete="off">
               <!--  <textarea class="u-full-width" type="text" placeholder="02717709-0" id="dni" name="dni" rows='5'> {{ isset($dni) ? $dni : '' }}</textarea>-->

            </div>

</div>
<div class="row">

            <div class="six columns">
                <label for="telefono">Date time </label>
               <input class="u-full-width" type="text" placeholder="" id="fecha" name="fecha" value="{{ isset($fecha) ? $fecha : '' }}" autocomplete="off">
               <!--  <textarea class="u-full-width" type="text" placeholder="02717709-0" id="dni" name="dni" rows='5'> {{ isset($dni) ? $dni : '' }}</textarea>-->

            </div>

</div>
<div class="row">

            <div class="six columns">
                <label for="telefono">Reference </label>
               <input class="u-full-width" type="text" placeholder="" id="referencia" name="referencia" value="{{ isset($referencia) ? $referencia : '' }}" autocomplete="off">
               <!--  <textarea class="u-full-width" type="text" placeholder="02717709-0" id="dni" name="dni" rows='5'> {{ isset($dni) ? $dni : '' }}</textarea>-->

            </div>
</div>
<div class="row">
            <div class="six columns" style='display:none;'>
                <label for="telefono">Date range</label>
                <input name="date_from" class="datetimepicker" type="text" placeholder="From" >
                <input name="date_to" class="datetimepicker" type="text" placeholder="To">
            </div>
</div>
<div class="row">

        <input class="button-primary" type="submit" value="Search">


        </div>
        </div>
        @csrf
    </form>

    <hr />

<div>

@if (isset($dni))
    <table style="width: 100%;">
        <thead>
            <tr>
                <td rowspan="4">                <img style="margin: auto;" src="{{ asset('assets/img/chivocircle.png') }}" alt="Chivo Admin Panel">
</td>
                <td rowspan="4" style="font-size:'x-large'"> REPORTE TRANSACCIONAL CUENTA USUARIO CHIVO</td>
            </tr>
            <tr>
                <td>Fecha y hora de impresion <br>{{ $fecha ?? '-' }} </td>
            </tr>
            <tr>
                <td>REF: {{ $referencia ?? '-' }}</td>
            </tr>
            <tr>
                <td>Version 1.0</td>
            </tr>
        </thead>
    </table>
</div>

<table id="encabezado" >
    <tr><th colspan="2">{{ $fullname }}</th></tr>
    <tr><td>Fecha y hora de activación de cuenta</td><td>{{ $fechacreacion }}</td></tr>
    <tr><td>DNI de usuario</td><td>{{ $dni }}</td></tr>
    <tr><td>Número de Teléfono del que se creó la cuenta</td><td>{{ $numero }}</td></tr>
    <tr><td>Estatus de la cuenta</td><td>{{ $estado }}</td></tr>
</table>
@endif
@php ($i = 1)
    <table  id="contenido">
    <thead>
        <tr>
        <th>Num</th>
        <th>Id Transaccion</th>
        <th>Fecha y Hora de Movimiento</th>
        <th>Cantidad </th>
        <th>Moneda</th>        
        <th>Origen</th>
        <th>Destino</th>
        <th>Estado</th>
</tr>
    </thead>
    <tbody>
    @if (isset($retirobanco))
        @foreach ($retirobanco as $salida)
        <tr>
            <td>{{ $i }}</td>
            <td>{{ $salida->idtx }}</td>
            <td>{{ $salida->created }}</td>
            <td>{{ $salida->monto }}</td>
            <td>{{ $salida->moneda }}</td>
            <td>{{ $salida->name }} DNI:{{ $salida->dni }}</td>
            <td>{{ $salida->tipo }}: <br>{{ $salida->destino }} <br>Cuenta:{{ $salida->iddestino }}</td>           
            <td>{{ $salida->status }}</td>
            
</tr>
@php ($i = $i+1)
@endforeach
@endif
@if (isset($retirowallet))
        @foreach ($retirowallet as $salida)
        <tr>
            <td>{{ $i }}</td>
            <td>{{ $salida->idtx }}</td>
            <td>{{ $salida->created }}</td>
            <td>{{ $salida->monto }}</td>
            <td>{{ $salida->moneda }}</td>
            <td>{{ $salida->name }}  <br>DNI:{{ $salida->dni }}</td>
            <td>{{ $salida->tipo }}: <br>{{ $salida->destino }} <br>DNI:{{ $salida->iddestino }}</td>           
            <td>{{ $salida->status }}</td>
            
</tr>
@php ($i = $i+1)
@endforeach
@endif
@if (isset($retirocajero))
        @foreach ($retirocajero as $salida)
        <tr>
        <td>{{ $i }}</td>
            <td>{{ $salida->idtx }}</td>
            <td>{{ $salida->created }}</td>
            <td>{{ $salida->monto }}</td>
            <td>{{ $salida->moneda }}</td>
            <td>{{ $salida->name }}  <br>DNI:{{ $salida->dni }}</td>
            <td>{{ $salida->tipo }}: <br>{{ $salida->destino }} </td>           
            <td>{{ $salida->status }}</td>
</tr>
@php ($i = $i+1)
@endforeach
@endif
@if (isset($retirowalletexterna))
        @foreach ($retirowalletexterna as $salida)
        <tr>
        <td>{{ $i }}</td>
            <td>{{ $salida->idtx }}</td>
            <td>{{ $salida->created }}</td>
            <td>{{ $salida->monto }}</td>
            <td>{{ $salida->moneda }}</td>
            <td>{{ $salida->name }}  <br>DNI:{{ $salida->dni }}</td>
            <td>{{ $salida->tipo }}: <br>{{ $salida->destino }} </td>           
            <td>{{ $salida->status }}</td>
           
</tr>
@php ($i = $i+1)
@endforeach
@endif
@if (isset($comprasbtc))
@foreach ($comprasbtc as $salida)
        <tr>
        <td>{{ $i }}</td>
            <td>{{ $salida->idtx }}</td>
            <td>{{ $salida->created }}</td>
            <td>{{ $salida->monto }}</td>
            <td>{{ $salida->moneda }}</td>
            <td>{{ $salida->name }}  DNI:{{ $salida->dni }}</td>
            <td>{{ $salida->tipo }}: <br>{{ $salida->destino }} <br>Usuario de comercio:<br>{{ $salida->iddestino }}</td>           
            <td>{{ $salida->status }}</td>
</tr>
@php ($i = $i+1)
@endforeach
@endif
@if (isset($comprasusd))
@foreach ($comprasusd as $salida)
        <tr>
        <td>{{ $i }}</td>
            <td>{{ $salida->idtx }}</td>
            <td>{{ $salida->created }}</td>
            <td>{{ $salida->monto }}</td>
            <td>{{ $salida->moneda }}</td>
            <td>{{ $salida->name }}  DNI:{{ $salida->dni }}</td>
            <td>{{ $salida->tipo }}: <br>{{ $salida->destino }} <br>Usuario comercio:<br>{{ $salida->iddestino }}</td>           
            <td>{{ $salida->status }}</td>
</tr>
@php ($i = $i+1)
@endforeach
@endif
@if (isset($txrecibidas))
@foreach ($txrecibidas as $salida)
        <tr>
         
        <td>{{ $i }}</td>
            <td>{{ $salida->idtx }}</td>
            <td>{{ $salida->created }}</td>
            <td>{{ $salida->monto }}</td>
            <td>{{ $salida->moneda }}</td>
            <td>{{ $salida->tipo }}:<br>{{ $salida->name }}  <br>DNI:{{ $salida->dni }}</td>
            <td> {{ $salida->destino }} DNI:{{ $salida->iddestino }}</td>           
            <td>{{ $salida->status }}</td>
</tr>
@php ($i = $i+1)
@endforeach
@endif
@if (isset($txrealizadas))
@foreach ($txrealizadas as $salida)
        <tr>
        <td>{{ $i }}</td>
        <td>{{ $salida->idtx }}</td>
            <td>{{ $salida->created }}</td>
            <td>{{ $salida->monto }}</td>
            <td>{{ $salida->moneda }}</td>
            <td>{{ $salida->name }}  DNI:{{ $salida->dni }}</td>
            <td>{{ $salida->tipo }}: <br>{{ $salida->destino }} <br>Usuario comercio:<br>{{ $salida->iddestino }}</td>           
            <td>{{ $salida->status }}</td>
</tr>
@php ($i = $i+1)
@endforeach
@endif
@if (isset($depositosbtcinterno))
@foreach ($depositosbtcinterno as $salida)
        <tr>
        <td>{{ $i }}</td>
        <td>{{ $salida->idtx }}</td>
            <td>{{ $salida->created }}</td>
            <td>{{ $salida->monto }}</td>
            <td>{{ $salida->moneda }}</td>
            <td>{{ $salida->tipo }}:<br>{{ $salida->name }}  <br>DNI:{{ $salida->dni }}</td>
            <td> {{ $salida->destino }} DNI:{{ $salida->iddestino }}</td>           
            <td>{{ $salida->status }}</td>
</tr>
@php ($i = $i+1)
@endforeach
@endif
@if (isset($depositosbtcexterno))
@foreach ($depositosbtcexterno as $salida)
        <tr>
        <td>{{ $i }}</td>
        <td>{{ $salida->idtx }}</td>
            <td>{{ $salida->created }}</td>
            <td>{{ $salida->monto }}</td>
            <td>{{ $salida->moneda }}</td>
            <td>{{ $salida->tipo }}: <br>{{ $salida->name }}  <br>DNI:{{ $salida->dni }}</td>
            <td> {{ $salida->destino }}</td>           
            <td>{{ $salida->status }}</td>
</tr>
@php ($i = $i+1)
@endforeach
@endif
@if (isset($depositosbtcbono))
@foreach ($depositosbtcbono as $salida)
        <tr>
        <td>{{ $i }}</td>
        <td>{{ $salida->idtx }}</td>
            <td>{{ $salida->created }}</td>
            <td>{{ $salida->monto }}</td>
            <td>{{ $salida->moneda }}</td>
            <td>{{ $salida->tipo }}</td>
            <td> {{ $salida->destino }} <br>DNI:{{ $salida->iddestino }}</td>         
            <td>{{ $salida->status }}</td>
</tr>
@php ($i = $i+1)
@endforeach
@endif
@if (isset($depositosusd))
@foreach ($depositosusd as $salida)
        <tr>
        <td>{{ $i }}</td>
        <td>{{ $salida->idtx }}</td>
            <td>{{ $salida->created }}</td>
            <td>{{ $salida->monto }}</td>
            <td>{{ $salida->moneda }}</td>
            <td>{{ $salida->tipo }}</td>
            <td> {{ $salida->destino }} <br>DNI:{{ $salida->iddestino }}</td>         
            <td>{{ $salida->status }}</td>
</tr>
@php ($i = $i+1)
@endforeach
@endif
@if (isset($pagosrecibidos))
@foreach ($pagosrecibidos as $salida)
        <tr>
        <td>{{ $i }}</td>
        <td>{{ $salida->idtx }}</td>
            <td>{{ $salida->created }}</td>
            <td>{{ $salida->monto }}</td>
            <td>{{ $salida->moneda }}</td>
            <td>{{ $salida->tipo }}: <br>{{ $salida->name }} <br>DNI:{{ $salida->dni }}</td>
            <td> {{ $salida->destino }} <br>DNI:{{ $salida->iddestino }}</td>           
            <td>{{ $salida->status }}</td>
</tr>
@php ($i = $i+1)
@endforeach
@endif
</tbody>
</table>
   
@if (isset($balances))
        <table id='saldo'  style='font-size: small;' class="table table-bordered table-hover">
            <thead>
                <tr>
                <th colspan="2">SALDO ACTUAL</th>
                </tr>
                
            </thead>
            <tbody>
                @foreach ($balances as $balance)
                    <tr>
                        <td>{{$balance->name}}</td>
                        <td>{{$balance->sum}}</td>
                    </tr>
                @endforeach
            </tbody>
        </table>
    @endif
<center>
                 <img style="margin: auto;" src="{{ asset('assets/img/banner.png') }}" alt="Chivo Admin Panel">

                 </center>
@endsection

@section('script')
<script type="text/javascript">
    $('.datetimepicker').datetimepicker({
        format:'Y-m-d H:i:s',
    });
</script>
@endsection
