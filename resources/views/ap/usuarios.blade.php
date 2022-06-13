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
        <br>
        <hr>
        <h1>{{ $total }}</h1>
     <h3>   Usuarios con Login <br>Nuevo Chivo</h3>da
     <hr>

           </div>
    </div>

    <script src="https://code.jquery.com/jquery-3.6.0.min.js"
        integrity="sha256-/xUj+3OJU5yExlq6GSYGSHk7tPXikynS7ogEvDej/m4=" crossorigin="anonymous"></script>
    
    <script src="https://cdnjs.cloudflare.com/ajax/libs/jquery-datetimepicker/2.5.9/jquery.datetimepicker.full.min.js"></script>

    @yield('script')
</body>

</html>
