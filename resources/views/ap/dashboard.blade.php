<!DOCTYPE html>

<html lang="en">

<head>

    <title>Chivo Admin</title>

    <meta charset="UTF-8" />

    <meta name="viewport" content="width=device-width,initial-scale=1" />

    <meta name="description" content="" />

    <link rel="stylesheet" type="text/css"
        href="https://cdnjs.cloudflare.com/ajax/libs/skeleton/2.0.4/skeleton.min.css" />
    
    <link rel="stylesheet" type="text/css" 
        href="https://cdnjs.cloudflare.com/ajax/libs/jquery-datetimepicker/2.5.9/jquery.datetimepicker.min.css" />
    
    <link rel="stylesheet" type="text/css" 
        href="{{ asset('assets/css/app.css') }}" />

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
</head>

<body>

    <div class="container ">
        <div style="text-align: center">
        <img style="margin: auto;" src="{{ asset('assets/img/chivo.png') }}" alt="Chivo Admin Panel">
        <br> <br>
        <h2>Dashboard</h2>
        <hr>

       
      
        <center>
        <form method="POST">
        <div class="row">
            <div class="twelve columns">
                <input  type="hidden" id="dni" name="dni" value="2" >
            </div>
            <div class="">
                <label for="wallet">Date range</label>
                <input  name="date_from" class="datetimepicker" type="hidden" placeholder="From" >
                <input  name="date_to" class="datetimepicker" type="text" placeholder="To">
                <input class="button-primary" type="submit" value="Generar"  >

            </div>
        </div>
</center>

   
<!-- Standard <ul> with class of "tabs" -->
<ul class="tabs">
  <!-- Give href an ID value of corresponding "tabs-content" <li>'s -->
  <li><a class="active"  href="#originales">Datos Originales</a></li>
  <li><a href="#persistentes">Datos Persistentes</a></li>
 
</ul>

<!-- Standard <ul> with class of "tabs-content" -->
<ul class="tabs-content">
  <!-- Give ID that matches HREF of above anchors -->
  <li class="active" id="originales">

  <table class="table table-bordered table-hover">
    <thead>
      
    </thead>
    <tbody>
@if (isset($salidas))
        @foreach ($salidas as $salida)

        @if($salida->valor>0)
        <tr>
             <td>{{ $salida->kpi }}</td><td>{{ $salida->valor }}</td>
             
         </tr>
         @endif
@endforeach
@endif
</tbody>
</table>


  </li>
  <li class="active" id="persistentes">

  <table class="table table-bordered table-hover">
    <thead>
      
    </thead>
    <tbody>
@if (isset($salidas))
        @foreach ($salidas as $salida)

        @if($salida->saldo_suav>0)
        <tr>
             <td>{{ $salida->kpi }}</td><td>{{ $salida->saldo_suav }}</td>
             
         </tr>
         @endif
@endforeach
@endif
</tbody>
</table>
  </li>
  </ul>


        @csrf
   
       
     <hr>

           </div>
    </div>

    <script src="https://code.jquery.com/jquery-3.6.0.min.js"
        integrity="sha256-/xUj+3OJU5yExlq6GSYGSHk7tPXikynS7ogEvDej/m4=" crossorigin="anonymous"></script>
    
    <script src="https://cdnjs.cloudflare.com/ajax/libs/jquery-datetimepicker/2.5.9/jquery.datetimepicker.full.min.js"></script>
    @section('script')
<script type="text/javascript">
    $('.datetimepicker').datetimepicker({
        format:'Y-m-d',
        timepicker:false,
        minDate:'2021-12-16',
        maxDate:new Date(Date.now() - 86400000),
        value:new Date(Date.now() - 86400000)
    });
</script>
@endsection
    @yield('script')

    
</body>

</html>
