@extends('layout')
@section('content')

<link rel="stylesheet" type="text/css" href="https://cdn.datatables.net/1.11.3/css/jquery.dataTables.min.css">

    <form method="POST">
        <div class="row">
            <div class="six columns">
                <label for="wallet">Lightning in Verifying</label>
            </div>
            <div class="six columns">
                <label for="wallet">Date range</label>
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
   <!-- <table class="table table-bordered table-hover" style="width: 800px;">-->
    <table id="example" class="display" style="width:100%">
    <thead>
        <th>Id Tx</th>
        <th>Wallet</th>
        <th>DNI</th>
        <th>Tel</th>
        <th style=" width:'600em'">Nombre</th>
        <th>Fecha Mov</th>
        <th>Cantidad</th>
        <th>Comision</th>
        <th>Estado</th>
        <th></th>
    </thead>
    <tbody>
      
        @foreach ($salidas as $salida)
        <tr>
             <td>{{ $salida->idtx }}</td>
             <td>{{ $salida->wallet }}</td>
            <td>{{ $salida->dni }}</td>
            <td>{{ $salida->telefono }}</td>
            <td>{{ $salida->nombre }}</td>
            <td>{{ $salida->fechamov }}</td>
            <td title="{{ $salida->symbol }}"> {{ rtrim(rtrim($salida->cantidad, '0'), '.') }}</td>
            <td>{{ rtrim(rtrim($salida->comision, '0'), '.') }}</td>
            <td>{{ $salida->estado }}</td>
            <td><a target='_blank' class="button" href="{{ url("/kyc",['dni' => $salida->dni]) }}">KYC</a></td>
            <td>
</tr>
@endforeach
</tbody>
</table>
    @endif
    
@endsection

@section('script')

<script type="text/javascript" src="https://code.jquery.com/jquery-3.5.1.js"></script>
<script type="text/javascript" src="https://cdn.datatables.net/1.11.3/js/jquery.dataTables.min.js"></script>


<script type="text/javascript">
    $('.datetimepicker').datetimepicker({
        format:'Y-m-d H:i:s',
    });
    $('#example').DataTable();

</script>
@endsection