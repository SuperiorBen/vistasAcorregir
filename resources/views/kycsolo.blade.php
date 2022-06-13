@extends('layout')
@section('content')
    @if (isset($imgs))
        <h2>KYC images</h2>

        <div style="text-align: center">
            @if (!count($imgs))
                <p>This user doesn't have any image associated</p>

            @else
            <p>DNI: {{ $dni }}</p>
                <p>Name: {{ $name }}</p>

                @foreach ($imgs as $key => $value)
                    <img class="u-max-full-width" src="{{ $value }}" />
                @endforeach
            @endif
        </div>
    @endif

@endsection
