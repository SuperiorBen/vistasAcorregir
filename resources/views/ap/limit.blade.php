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
label, input {display:inline-block;}
</style>

<script src="https://cdn.jsdelivr.net/npm/jquery/dist/jquery.min.js"></script>


<script>
    /**
 *Tabs from Skeleton
 */

(function ($) {

   

})(jQuery);
</script>
</head>

<body>

    <div class="container ">
        <div style="text-align: center">
        <img style="margin: auto;" src="{{ asset('assets/img/chivo.png') }}" alt="Chivo Admin Panel">
        <br> <br>
        <h2>Top Limits </h2>
        <hr>

       
      
        <center>
        <form method="POST">
        <div class="row">
            <div class="twelve columns">
                <input  type="hidden" id="dni" name="dni" value="2" >
            <label for="tipo">Type Limit</label>
            <select name="tipo" id="tipo">
                <option value="0">Seleccione</option>
                <option value="Daily Exchange">Daily Exchange</option>
                <option value="Daily Deposit">Daily Deposit</option>
                <option value="Daily Withdraw">Daily Withdraw</option>
                <option value="Monthly Withdraw">Monthly Withdraw</option>
            </select>
            <label for="nivel">&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;Level Verication</label>
            <select name="nivel" id="nivel">
                <option value="0">Seleccione</option>
                <option value="1">1</option>
                <option value="2">2</option>
                <option value="3">3</option>
            </select>
            <input class="button-primary" type="submit" value="Generar"  >
            </div>
            <div class="">
         <!--       <label for="wallet">Date range</label>
                <input  name="date_from" class="datetimepicker" type="hidden" placeholder="From" >
                <input  name="date_to" class="datetimepicker" type="text"  type="hidden" placeholder="To">
                <input class="button-primary" type="submit" value="Generar"  > -->


            </div>
        </div>
        @csrf
        </form>
</center>

  
<h1></h1>

     <hr>
     <table class="table table-bordered table-hover">
    <thead>
        <th>Level Verification</th>
        <th>Type</th>
        <th>Acccount Id</th>
        <th>Account Name</th>
        <th>Total</th>
    </thead>
    <tbody>
@if (isset($salidas))
        @foreach ($salidas as $salida)

        @if($salida->verifcation_level>0)
        <tr>
             <td>{{ $salida->verifcation_level }}</td>
             <td>{{ $salida->type }}</td>
             <td>{{ $salida->account_id }}</td>
             <td>{{ $salida->account_name }}</td>
             <td>{{ $salida->total }}</td>
             
         </tr>
         @endif
@endforeach
@endif
</tbody>
</table>
           </div>
    </div>

    <script src="https://code.jquery.com/jquery-3.6.0.min.js"
        integrity="sha256-/xUj+3OJU5yExlq6GSYGSHk7tPXikynS7ogEvDej/m4=" crossorigin="anonymous"></script>
    
    <script src="https://cdnjs.cloudflare.com/ajax/libs/jquery-datetimepicker/2.5.9/jquery.datetimepicker.full.min.js"></script>
    @section('script')
<script type="text/javascript">
  
</script>
@endsection
    @yield('script')

    
</body>

</html>
