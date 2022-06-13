@extends('layouts1m2')
@section('content')
<div style="text-align: center">
<a style="" class="button welcome-button" href="{{ url("/ap/dashboard") }}">Dashboard</a>
<br />
<a style="" class="button welcome-button" href="{{ url("/ap/limit") }}">Top Limits</a>
<br />

</div>
@endsection

