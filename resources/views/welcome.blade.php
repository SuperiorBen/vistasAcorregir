@extends('layout')
@section('content')
<div style="text-align: center">
<a style="" class="button welcome-button" href="{{ url("/ap/kyc") }}">KYC</a>
<br />
<a style="" class="button welcome-button" href="{{ url("/wallet") }}">Retiros por Wallet</a>
<br />
<a style="" class="button welcome-button" href="{{ url("/tx") }}">TRANSACTIONS</a>
<br />
<a style="" class="button welcome-button" href="{{ url("/telefono") }}">PHONES BY DNI</a>
<br />
<a style="" class="button welcome-button" href="{{ url("/lightning") }}">Lightning Verifying</a>
<br />
<a style="" class="button welcome-button" href="{{ url("/ap/topups") }}">Topups</a>
<br />
<hr>
<a style="" class="button welcome-button" target="_blank" href="{{ url("/bitgo/pendingApprovals") }}">PENDING APPROVALS</a>
</div>
@endsection

