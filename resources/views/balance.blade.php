@extends('layouts1m2')
@section('content')
    @include('searchform', ['subtitle' => 'Balance'])

    @if (isset($balances))
        <table id='tdata' style='font-size: small;' class="table table-bordered table-hover">
            <thead>
                <tr>
                    <th>Currency</th>
                    <th>Balance</th>
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

@endsection
