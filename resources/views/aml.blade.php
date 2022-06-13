@extends('layouts1m2')
@section('content')
    <form method="POST">
        <div class="row">
            <div class="six columns">
                <label for="telefono">Transactions by DNI </label>
               <input class="u-full-width" type="text" placeholder="02717709-0" id="dni" name="dni" value="{{ isset($dni) ? $dni : '' }}" autocomplete="off">
               <!--  <textarea class="u-full-width" type="text" placeholder="02717709-0" id="dni" name="dni" rows='5'> {{ isset($dni) ? $dni : '' }}</textarea>-->

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

    @if (isset($csv))
    <a href="{{ $csv }}" target="_blank">Download {{ $csv }}</a>
    @endif

    <table class="table table-bordered table-hover">
    <thead>
        <th>Type Tx</th>
        <th>Id Tx</th>
        <th>Main DNI</th>
        <th>Main Name </th>
        <th>Date Tx</th>        
        <th>Cur</th>
        <th>Amount</th>
        <th>Source/Destination wallet</th>
        <th>Reference</th>
        <th>Sourde/Destination DNI</th>
        <th>Source/Destination Name</th>
        <th>Status</th>
    </thead>
    <tbody>
    @if (isset($retirobanco))
        @foreach ($retirobanco as $salida)
        <tr>
             <td>{{ $salida->tipo }}</td>
             <td>R-{{ $salida->idtx }}</td>
            <td>{{ $salida->dni }}</td>
            <td>{{ $salida->name }}</td>
            <td>{{ $salida->created }}</td>
            <td>{{ $salida->moneda }}</td>
            <td>{{ $salida->monto }}</td>
            <td>{{ $salida->wallet }}</td>
            <td>{{ $salida->reference }}</td>
            <td>{{ $salida->iddestino }}</td>
            <td>{{ $salida->destino }}</td>
            <td>{{ $salida->status }}</td>
            <td>
</tr>
@endforeach
@endif
@if (isset($retirowallet))
        @foreach ($retirowallet as $salida)
        <tr>
             <td>{{ $salida->tipo }}</td>
             <td>R-{{ $salida->idtx }}</td>
            <td>{{ $salida->dni }}</td>
            <td>{{ $salida->name }}</td>
            <td>{{ $salida->created }}</td>
            <td>{{ $salida->moneda }}</td>
            <td>{{ $salida->monto }}</td>
            <td>{{ $salida->wallet }}</td>
            <td>{{ $salida->reference }}</td>
            <td>{{ $salida->iddestino }}</td>
            <td>{{ $salida->destino }}</td>
            <td>{{ $salida->status }}</td>
            <td>
</tr>
@endforeach
@endif
@if (isset($retirocajero))
        @foreach ($retirocajero as $salida)
        <tr>
             <td>{{ $salida->tipo }}</td>
             <td>R-{{ $salida->idtx }}</td>
            <td>{{ $salida->dni }}</td>
            <td>{{ $salida->name }}</td>
            <td>{{ $salida->created }}</td>
            <td>{{ $salida->moneda }}</td>
            <td>{{ $salida->monto }}</td>
            <td>{{ $salida->wallet }}</td>
            <td>{{ $salida->reference }}</td>
            <td>{{ $salida->iddestino }}</td>
            <td>{{ $salida->destino }}</td>
            <td>{{ $salida->status }}</td>
            <td>
</tr>
@endforeach
@endif
@if (isset($retirowalletexterna))
        @foreach ($retirowalletexterna as $salida)
        <tr>
             <td>{{ $salida->tipo }}</td>
             <td>R-{{ $salida->idtx }}</td>
            <td>{{ $salida->dni }}</td>
            <td>{{ $salida->name }}</td>
            <td>{{ $salida->created }}</td>
            <td>{{ $salida->moneda }}</td>
            <td>{{ $salida->monto }}</td>
            <td>{{ $salida->wallet }}</td>
            <td>{{ $salida->reference }}</td>
            <td>{{ $salida->iddestino }}</td>
            <td>{{ $salida->destino }}</td>
            <td>{{ $salida->status }}</td>
            <td>
</tr>
@endforeach
@endif
@if (isset($comprasbtc))
@foreach ($comprasbtc as $salida)
        <tr>
             <td>{{ $salida->tipo }}</td>
             <td>C-{{ $salida->idtx }}</td>
            <td>{{ $salida->dni }}</td>
            <td>{{ $salida->name }}</td>
            <td>{{ $salida->created }}</td>
            <td>{{ $salida->moneda }}</td>
            <td>{{ $salida->monto }}</td>
            <td>{{ $salida->wallet }}</td>
            <td>{{ $salida->reference }}</td>
            <td>{{ $salida->iddestino }}</td>
            <td>{{ $salida->destino }}</td>
            <td>{{ $salida->status }}</td>
            <td>
</tr>
@endforeach
@endif
@if (isset($comprasusd))
@foreach ($comprasusd as $salida)
        <tr>
             <td>{{ $salida->tipo }}</td>
             <td>C-{{ $salida->idtx }}</td>
            <td>{{ $salida->dni }}</td>
            <td>{{ $salida->name }}</td>
            <td>{{ $salida->created }}</td>
            <td>{{ $salida->moneda }}</td>
            <td>{{ $salida->monto }}</td>
            <td>{{ $salida->wallet }}</td>
            <td>{{ $salida->reference }}</td>
            <td>{{ $salida->iddestino }}</td>
            <td>{{ $salida->destino }}</td>
            <td>{{ $salida->status }}</td>
            <td>
</tr>
@endforeach
@endif
@if (isset($txrecibidas))
@foreach ($txrecibidas as $salida)
        <tr>
             <td>{{ $salida->tipo }}</td>
             <td>T-{{ $salida->idtx }}</td>
            <td>{{ $salida->dni }}</td>
            <td>{{ $salida->name }}</td>
            <td>{{ $salida->created }}</td>
            <td>{{ $salida->moneda }}</td>
            <td>{{ $salida->monto }}</td>
            <td>{{ $salida->wallet }}</td>
            <td>{{ $salida->reference }}</td>
            <td>{{ $salida->iddestino }}</td>
            <td>{{ $salida->destino }}</td>
            <td>{{ $salida->status }}</td>
            <td>
</tr>
@endforeach
@endif
@if (isset($txrealizadas))
@foreach ($txrealizadas as $salida)
        <tr>
             <td>{{ $salida->tipo }}</td>
             <td>T-{{ $salida->idtx }}</td>
            <td>{{ $salida->dni }}</td>
            <td>{{ $salida->name }}</td>
            <td>{{ $salida->created }}</td>
            <td>{{ $salida->moneda }}</td>
            <td>{{ $salida->monto }}</td>
            <td>{{ $salida->wallet }}</td>
            <td>{{ $salida->reference }}</td>
            <td>{{ $salida->iddestino }}</td>
            <td>{{ $salida->destino }}</td>
            <td>{{ $salida->status }}</td>
            <td>
</tr>
@endforeach
@endif
@if (isset($depositosbtcinterno))
@foreach ($depositosbtcinterno as $salida)
        <tr>
             <td>{{ $salida->tipo }}</td>
             <td>D-{{ $salida->idtx }}</td>
            <td>{{ $salida->dni }}</td>
            <td>{{ $salida->name }}</td>
            <td>{{ $salida->created }}</td>
            <td>{{ $salida->moneda }}</td>
            <td>{{ $salida->monto }}</td>
            <td>{{ $salida->wallet }}</td>
            <td>{{ $salida->reference }}</td>
            <td>{{ $salida->iddestino }}</td>
            <td>{{ $salida->destino }}</td>
            <td>{{ $salida->status }}</td>
            <td>
</tr>
@endforeach
@endif
@if (isset($depositosbtcexterno))
@foreach ($depositosbtcexterno as $salida)
        <tr>
             <td>{{ $salida->tipo }}</td>
             <td>D-{{ $salida->idtx }}</td>
            <td>{{ $salida->dni }}</td>
            <td>{{ $salida->name }}</td>
            <td>{{ $salida->created }}</td>
            <td>{{ $salida->moneda }}</td>
            <td>{{ $salida->monto }}</td>
            <td>{{ $salida->wallet }}</td>
            <td>{{ $salida->reference }}</td>
            <td>{{ $salida->iddestino }}</td>
            <td>{{ $salida->destino }}</td>
            <td>{{ $salida->status }}</td>
            <td>
</tr>
@endforeach
@endif
@if (isset($depositosbtcbono))
@foreach ($depositosbtcbono as $salida)
        <tr>
             <td>{{ $salida->tipo }}</td>
             <td>D-{{ $salida->idtx }}</td>
            <td>{{ $salida->dni }}</td>
            <td>{{ $salida->name }}</td>
            <td>{{ $salida->created }}</td>
            <td>{{ $salida->moneda }}</td>
            <td>{{ $salida->monto }}</td>
            <td>{{ $salida->wallet }}</td>
            <td>{{ $salida->reference }}</td>
            <td>{{ $salida->iddestino }}</td>
            <td>{{ $salida->destino }}</td>
            <td>{{ $salida->status }}</td>
            <td>
</tr>
@endforeach
@endif
@if (isset($depositosusd))
@foreach ($depositosusd as $salida)
        <tr>
             <td>{{ $salida->tipo }}</td>
             <td>D-{{ $salida->idtx }}</td>
            <td>{{ $salida->dni }}</td>
            <td>{{ $salida->name }}</td>
            <td>{{ $salida->created }}</td>
            <td>{{ $salida->moneda }}</td>
            <td>{{ $salida->monto }}</td>
            <td>{{ $salida->wallet }}</td>
            <td>{{ $salida->reference }}</td>
            <td>{{ $salida->iddestino }}</td>
            <td>{{ $salida->destino }}</td>
            <td>{{ $salida->status }}</td>
            <td>
</tr>
@endforeach
@endif
@if (isset($pagosrecibidos))
@foreach ($pagosrecibidos as $salida)
        <tr>
             <td>{{ $salida->tipo }}</td>
             <td>PR-{{ $salida->idtx }}</td>
            <td>{{ $salida->dni }}</td>
            <td>{{ $salida->name }}</td>
            <td>{{ $salida->created }}</td>
            <td>{{ $salida->moneda }}</td>
            <td>{{ $salida->monto }}</td>
            <td>{{ $salida->wallet }}</td>
            <td>{{ $salida->reference }}</td>
            <td>{{ $salida->iddestino }}</td>
            <td>{{ $salida->destino }}</td>
            <td>{{ $salida->status }}</td>
            <td>
</tr>
@endforeach
@endif
</tbody>
</table>
   
    
@endsection

@section('script')
<script type="text/javascript">
    $('.datetimepicker').datetimepicker({
        format:'Y-m-d H:i:s',
    });
</script>
@endsection
