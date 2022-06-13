@extends('layout')
@section('content')
    @include('searchform', ['subtitle' => 'KYC'])

    @if (isset($imgs))
        <h2>KYC images</h2>

        <div style="text-align: center">
            <h1>Athena</h1>
            @if (!count($imgs_old))
                <p>This user doesn't have any image associated</p>

            @else
                <p>Name: {{ $name_old }}</p>
                @foreach ($imgs_old as $key => $value)
                    <img class="u-max-full-width" src="{{ $value }}" />
                @endforeach
            @endif
        </div>

        <div style="text-align: center">
            <h1>AlphaPoint</h1>
            @if (!count($imgs))
                <p>This user doesn't have any image associated</p>

            @else
                <p>Name: {{ $name }}</p>
                @foreach ($imgs as $key => $value)
                    <img class="u-max-full-width" src="{{ $value }}" />
                @endforeach
            @endif
        </div>
    @endif

@endsection
