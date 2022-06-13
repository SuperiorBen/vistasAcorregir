@extends('ap/layout')
@section('content')


<style>
    /* #Tabs (activate in tabs.js)
================================================== */
	ul.tabs {
		display: block;
		margin: 0 0 20px 0;
		padding: 0;
		border-bottom: solid 1px #ddd; }
	ul.tabs li {
		display: block;
		width: auto;
		height: 30px;
		padding: 0;
		float: left;
		margin-bottom: 0; }
	ul.tabs li a {
		display: block;
		text-decoration: none;
		width: auto;
		height: 29px;
		padding: 0px 20px;
		line-height: 30px;
		border: solid 1px #ddd;
		border-width: 1px 1px 0 0;
		margin: 0;
		background: #f5f5f5;
		font-size: 13px; }
	ul.tabs li a.active {
		background: #fff;
		height: 30px;
		position: relative;
		top: -4px;
		padding-top: 4px;
		border-left-width: 1px;
		margin: 0 0 0 -1px;
		color: #111;
		-moz-border-radius-topleft: 2px;
		-webkit-border-top-left-radius: 2px;
		border-top-left-radius: 2px;
		-moz-border-radius-topright: 2px;
		-webkit-border-top-right-radius: 2px;
		border-top-right-radius: 2px; }
	ul.tabs li:first-child a.active {
		margin-left: 0; }
	ul.tabs li:first-child a {
		border-width: 1px 1px 0 1px;
		-moz-border-radius-topleft: 2px;
		-webkit-border-top-left-radius: 2px;
		border-top-left-radius: 2px; }
	ul.tabs li:last-child a {
		-moz-border-radius-topright: 2px;
		-webkit-border-top-right-radius: 2px;
		border-top-right-radius: 2px; }

	ul.tabs-content { margin: 0; display: block; }
	ul.tabs-content > li { display:none; }
	ul.tabs-content > li.active { display: block; }

	/* Clearfixing tabs for beautiful stacking */
	ul.tabs:before,
	ul.tabs:after {
	  content: '\0020';
	  display: block;
	  overflow: hidden;
	  visibility: hidden;
	  width: 0;
	  height: 0; }
	ul.tabs:after {
	  clear: both; }
	ul.tabs {
	  zoom: 1; }
</style>

<script src="https://cdn.jsdelivr.net/npm/jquery/dist/jquery.min.js"></script>


<script>
    /**
 *Tabs from Skeleton
 */

(function ($) {

    hashchange();
    $(hashchange);
  // hash change handler
  function hashchange () {
    var hash = window.location.hash
      , el = $('ul.tabs [href*="' + hash + '"]')
      , content = $(hash)

    if (el.length && !el.hasClass('active') && content.length) {
      el.closest('.tabs').find('.active').removeClass('active');
      el.addClass('active');
      content.show().addClass('active').siblings().hide().removeClass('active');
    }
  }

  // listen on event and fire right away
  $(window).on('hashchange.skeleton', hashchange);
  hashchange();
  $(hashchange);

  //$("#tabdepositos").addClass("active");

})(jQuery);
</script>


<form method="POST">
        <div class="row">
            <div class="twelve columns">
                <label for="dni">Transacciones por DNI uiuiuiiiiiiiiiiii </label>
                <input class="u-full-width" type="text" placeholder="06377628-8" id="dni" name="dni" value="{{ isset($dni) ? $dni : '' }}" autocomplete="off">
            </div>
        </div>
        <input class="button-primary" type="submit" value="Search">
        @csrf
    </form>

    <hr />


    @if (isset($salidasbtc))


    
<!-- Standard <ul> with class of "tabs" -->
<ul class="tabs">
  <!-- Give href an ID value of corresponding "tabs-content" <li>'s -->
  <li><a class="active"  href="#rtrbtcwalletwallet">RTR BTC Wallet Wallet</a></li>
  <li><a class=""  href="#depositos">Depositos</a></li>
  <li><a href="#txrecibidas">Tx Recibidas</a></li>
  <li><a href="#txenviadas">Tx Enviadas</a></li>
  <li><a href="#compras">Compras via POS</a></li>
  <li><a href="#salidasusd">Salidas USD</a></li>
  <li><a href="#salidasbtc">Salidas BTC</a></li>
</ul>

<!-- Standard <ul> with class of "tabs-content" -->
<ul class="tabs-content">
  <!-- Give ID that matches HREF of above anchors -->
  <li class="active" id="rtrbtcwalletwallet">
  <table class="table table-bordered table-hover">
    <thead>
        <th>Id Ord</th>
        <th>Ti Per Ord</th>
        <th>Nombre Ord</th>
        <th>Tipo Doc Ord</th>
        <th>Doc Ord</th>
        <th>Id Dest</th>
        <th>Tipo Per Dest</th>
        <th>Nombre Dest</th>
        <th>Tpo Doc Dest</th>
        <th>Id Tx</th>
        <th>Fecha</th>
        <th>Tipo Act Vir</th>
        <th>Monto Act Vir</th>
        <th>Monto Dol</th>
        <th>Concepto Tx</th>
    </thead>
    <tbody>
      
        @foreach ($rtrbtcwalletwallet as $salida)
        <tr>
            <td>{{ $salida->identificador_ordenante }}</td> 
            <td>{{ $salida->tipo_persona_ordenante }}</td>
            <td>{{ $salida->nombres_ordenante }}</td>
            <td>{{ $salida->tipo_documento_ordenante }}</td>
            <td>{{ $salida->documento_ordenante }}</td>
            <td>{{ $salida->identificador_destinatario }}</td>
            <td>{{ $salida->tipo_persona_destinatario }}</td>
            <td>{{ $salida->nombre_destinatario }}</td>
            <td>{{ $salida->tipo_documento_destinatario }}</td>
            <td>{{ $salida->documento_destinatario }}</td>
            <td>{{ $salida->id_transaccion }}</td>
            <td>{{ $salida->fecha_hora }}</td>
            <td>{{ $salida->tipo_activo_virtual }}</td>
            <td>{{ $salida->monto_activo_virtual }}</td>
            <td>{{ $salida->monto_dolares }}</td>
            <td>{{ $salida->concepto_transaccion }}</td>
            <td>

</tr>
@endforeach
</tbody>
</table>    
</li>
  <li class="" id="depositos">
  <table class="table table-bordered table-hover">
    <thead>
        <th>Id Tx</th>
        <th>Fecha Mov</th>
        <th>Cantidad</th>
        <th>Moneda</th>
        <th>Origen</th>
        <th>Estado</th>
        <th></th>
    </thead>
    <tbody>
      
        @foreach ($depositos as $salida)
        <tr>
            <td>{{ $salida->idtx }}</td> 
            <td>{{ $salida->fechamov }}</td>
            <td>{{ $salida->cantidad }}</td>
            <td>{{ $salida->moneda }}</td>
            <td>{{ $salida->comentario }}</td>
            <td>{{ $salida->estado }}</td>
            <td>
</tr>
@endforeach
</tbody>
</table>    
</li>
<li class="" id="txrecibidas">
  <table class="table table-bordered table-hover">
    <thead>
        <th>Id Tx</th>
        <th>Fecha Mov</th>
        <th>Cantidad</th>
        <th>Moneda</th>
        <th>Origen</th>
        <th>Estado</th>
        <th>Enviado por</th>
    </thead>
    <tbody>
      
        @foreach ($txrecibidas as $salida)
        <tr>
            <td>{{ $salida->idtx }}</td>
            <td>{{ $salida->fechamov }}</td>
            <td>{{ $salida->cantidad }}</td>
            <td>{{ $salida->moneda }}</td>
            <td>{{ $salida->comentario }}</td>
            <td>{{ $salida->estado }}</td>
            <td><a target='_blank' class="button welcome-button" href="{{ url("/kycsolo",['id' => $salida->txorigen]) }}">Ver</a></td>

</tr>
@endforeach
</tbody>
</table>    
</li>
<li class="" id="txenviadas">
  <table class="table table-bordered table-hover">
    <thead>
        <th>Id Tx</th>
        <th>Fecha Mov</th>
        <th>Cantidad</th>
        <th>Moneda</th>
        <th>Origen</th>
        <th>Estado</th>
        <th>Enviado a</th>
        <th></th>
    </thead>
    <tbody>
      
        @foreach ($txenviadas as $salida)
        <tr>
            <td>{{ $salida->idtx }}</td>
            <td>{{ $salida->fechamov }}</td>
            <td>{{ $salida->cantidad }}</td>
            <td>{{ $salida->moneda }}</td>
            <td>{{ $salida->comentario }}</td>
            <td>{{ $salida->estado }}</td>
            <td><a target='_blank' class="button welcome-button" href="{{ url("/kycsolo",['id' => $salida->txorigen]) }}">Ver</a></td>

</tr>
@endforeach
</tbody>
</table>    
</li>
<li id="compras">
  <table class="table table-bordered table-hover">
    <thead>
        <th>Id Tx</th>
        <th>Fecha Mov</th>
        <th>Cantidad</th>
        <th>Moneda</th>
        <th>NIT Comercio</th>
        <th>Comercio</th>
        <th>Estado</th>
        <th></th>
    </thead>
    <tbody>
      
        @foreach ($compras as $salida)
        <tr>
            <td>{{ $salida->idtx }}</td>
            <td>{{ $salida->fechamov }}</td>
            <td>{{ $salida->cantidad }}</td>
            <td>{{ $salida->moneda }}</td>
            <td>{{ $salida->numcomercio }}</td>
            <td>{{ $salida->comercio }}</td>
            <td>{{ $salida->estado }}</td>
            <td>
</tr>
@endforeach
</tbody>
</table>    
</li>
  <li id="salidasusd">
  <table class="table table-bordered table-hover">
    <thead>
        <th>Id Tx</th>
        <th>Fecha Mov</th>
        <th>Cantidad</th>
        <th>Cuenta</th>
        <th>Banco</th>
        <th>Estado</th>
        <th></th>
    </thead>
    <tbody>
      
        @foreach ($salidasusd as $salida)
        <tr>
            <td>{{ $salida->idtx }}</td>
            <td>{{ $salida->fechamov }}</td>
            <td>{{ $salida->cantidad }}</td>
            <td>{{ $salida->cuenta }}</td>
            <td>{{ $salida->banco }}</td>
            <td>{{ $salida->estado }}</td>
            <td>
</tr>
@endforeach
</tbody>
</table>    
</li>
  <li id="salidasbtc">
  <table class="table table-bordered table-hover">
    <thead>
        <th>Id Tx</th>
        <th>Fecha Mov</th>
        <th>Cantidad</th>
        <th>Comision</th>
        <th>Wallet</th>
        <th>Estado</th>
        <th></th>
    </thead>
    <tbody>
      
        @foreach ($salidasbtc as $salida)
        <tr>
            <td>{{ $salida->idtx }}</td>
            <td>{{ $salida->fechamov }}</td>
            <td>{{ $salida->cantidad }}</td>
            <td>{{ $salida->comision }}</td>
            <td>{{ $salida->wallet }}</td>
            <td>{{ $salida->estado }}</td>
            <td>
</tr>
@endforeach
</tbody>
</table>
  </li>
</ul>
  
    @endif
    
@endsection
