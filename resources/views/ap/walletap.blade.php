@extends('ap/layout')
@section('content')

<div class="content-find-wallet">



    <form method="POST">
        <div class="row">
            <div class="six columns">
                <label for="wallet">DNIs by Wallet</label>
                <input class="u-full-width" type="text" placeholder="bc1qujek5cy4z6tfwcugf74zfl366c303337xyckff" id="wallet" name="wallet" value="{{ isset($wallet) ? $wallet : '' }}" autocomplete="off">
            </div>
            <div class="six columns">
                <label for="wallet">Date range</label>
                <input name="date_from" class="datetimepicker" type="text" placeholder="From">
                <input name="date_to" class="datetimepicker" type="text" placeholder="To">
            </div>
        </div>
        <input class="button-primary" type="submit" value="Search">
        @csrf
    </form>
    <hr />

    @if (isset($salidas))
    <a href="{{ url($csv) }}" target="_blank">Download {{ $csv }}</a>
    <table class="table table-bordered table-hover">
        <thead>
            <th>Id Tx</th>
            <th>DNI</th>
            <th>Tel</th>
            <th style=" width:'600em'">Nombre</th>
            <th>Fecha Mov</th>
            <th>Cantidad</th>
            <th>Estado</th>
            <th></th>
        </thead>
        <tbody>

            @foreach ($salidas as $salida)
            <tr>
                <td>{{ $salida->transaction_id }}</td>
                <td>{{ $salida->identifier }}</td>
                <td>{{ $salida->tel }}</td>
                <td>{{ $salida->account_name }}</td>
                <td>{{ $salida->fechamov }}</td>
                <td title="{{ $salida->notional_amount }}"> {{ rtrim(rtrim($salida->notional_amount, '0'), '.') }}</td>
                <td>{{ $salida->tx_status }}</td>
                <td><a target='_blank' class="button" href="{{ url("/ap/kyc",['dni' => $salida->identifier]) }}">KYC</a></td>
                <td>
            </tr>
            @endforeach
        </tbody>
    </table>
    @endif


</div>
@endsection

@section('script')
<script type="text/javascript">
    $('.datetimepicker').datetimepicker({
        format: 'Y-m-d H:i:s',
    });
</script>
@endsection