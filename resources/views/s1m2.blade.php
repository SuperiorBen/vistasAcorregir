@extends('layouts1m2')
@section('content')
<div style="text-align: center">
<a style="" class="button welcome-button" href="{{ url("/balance") }}">Balance by DNI</a>
<br />
<a style="" class="button welcome-button" href="{{ url("/aml") }}">Tracking Transactions</a>
<br />
<a style="" class="button welcome-button" href="{{ url("/balancegraph") }}">Balance History</a>
<br />
</div>
@endsection

