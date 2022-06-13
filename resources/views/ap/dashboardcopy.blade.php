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

</head>

<body>

    <div class="container ">
        <div style="text-align: center">
        <img style="margin: auto;" src="{{ asset('assets/img/chivo.png') }}" alt="Chivo Admin Panel">
        <br> <br>
        <h2>Dashboard</h2>
        <hr>
        @if (isset($tipo))

        @if ($tipo==2)
       
        <a  class="button button-primary" href="dashboard">Ver Datos Orginales</a>
       
        @endif
        @else
        <center>
        <form method="POST">
        <div class="row">
            <div class="twelve columns">
                <input  type="hidden" id="dni" name="dni" value="2" >
            </div>
            <div class="">
                <label for="wallet">Date range</label>
                <input  name="date_from" class="datetimepicker" type="text" placeholder="From" >
                <input  name="date_to" class="datetimepicker" type="text" placeholder="To">
            </div>
        </div>
        <input class="button-primary" type="submit" value="Limpiar Datos no Persistentes"  >
</center>
        <table>
            <tr>
                <td>Total amount given in Bonus</td><td>$125,072,370.00</td>
            </tr>
            <tr>
                <td>Total number of bonus given</td><td>4,271,323</td>
            </tr>
            <tr>
                <td>Total user balance USD</td><td>$2,828.58</td>
            </tr>
            <tr>
                <td>Total user balance BTC</td><td>-0.06378428</td>
            </tr> 
            <tr>
                <td>Total notional balances (BTC+USD)</td><td>$-218,618,548.14</td>
            </tr>
           
            <tr>
                <td>Average total users balance USD</td><td>$48.30</td>
            </tr>
            <tr>
            <td>Average total users balance BTC</td><td>0.00051287</td>
            </tr>
            <tr>
                <td>Average  balance USD users balance > $1</td><td>$135.07</td>
            </tr>
            <tr>
            <td>Average  balance BTC users balance > $1</td><td>0.00155378</td>
            </tr>
            <tr>
                <td>Total amount of CC deposits  and external wallets</td><td>$32,283,985,778.13</td>
            </tr>
            <tr>
                <td>Total number of CC deposits  and external wallets</td><td>6,153,243</td>
            </tr>
            <tr>
                <td>Total amount of ATM deposits</td><td>$32,211,636.77</td>
            </tr>
            <tr>
                <td>Total number of ATM deposits</td><td>172,010</td>
            </tr>
            <tr>
                <td>Total amount of ATM withdrawals</td><td>$16,513,083.73</td>
            </tr>
            <tr>
                <td>Total number of ATM withdrawals</td><td>82,826</td>
            </tr>
            <tr>
                <td>Total amount of bank and external wallets withdrawals</td><td>$31,424,700,890.66</td>
            </tr>
            <tr>
                <td>Total number of bank and external wallets withdrawals</td><td>6,118,242</td>
            </tr>
            <tr>
                <td>Total amount of payments received</td><td>$17,396,856,274.55</td>
            </tr>
            <tr>
                <td>Total number of payments received</td><td>2,750,887</td>
            </tr>
            <tr>
                <td>Total amount of purchases</td><td>$17,380,165,318.21</td>
            </tr>
            <tr>
                <td>Total number of purchases</td><td>2,669,621</td>
            </tr>
            <tr>
                <td>Total amount of BTC exchanges</td><td>$77,080,311,026.22</td>
            </tr>
            <tr>
                <td>Total number of BTC exchanges</td><td>25,855,253</td>
            </tr>
            <tr>
                <td>Total amount of USD exchanges</td><td>$81,815,284,840.02</td>
            </tr>
            <tr>
                <td>Total number of USD exchanges</td><td>26,688,589</td>
            </tr>
           
                <td>Total amount of users created</td><td>4,467,578</td>
            </tr>
            <tr>
                <td>Total amount of merchants created</td><td>20,150</td>
            </tr>
            <tr>
                <td>Total amount of personal accounts created</td><td>3,252,213</td>
            </tr>
            <tr>
                <td>Total amount of verified users</td><td>3,376,738</td>
            </tr>
            <tr>
                <td>Total number of users migrates</td><td>825,775</td>
            </tr>
 
        </table>
        @endif
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
        format:'d/m/Y',
        timepicker:false,
        minDate:new Date(),
        maxDate:new Date(),
        value:new Date(),
    });
</script>
@endsection
    @yield('script')

    
</body>

</html>
