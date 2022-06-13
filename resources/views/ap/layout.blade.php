<!DOCTYPE html>

<html lang="en">

<head>
    <title>Chivo Admin</title>
    <meta charset="UTF-8" />
    <meta name="viewport" content="width=device-width,initial-scale=1" />
    <meta name="description" content="" />
    <link rel="stylesheet" type="text/css" href="https://cdnjs.cloudflare.com/ajax/libs/skeleton/2.0.4/skeleton.min.css" />
    <link rel="stylesheet" type="text/css" href="https://cdnjs.cloudflare.com/ajax/libs/jquery-datetimepicker/2.5.9/jquery.datetimepicker.min.css" />
    <link rel="stylesheet" type="text/css" href="{{ asset('assets/css/app.css') }}" />
    <link rel="stylesheet" href="https://cdnjs.cloudflare.com/ajax/libs/font-awesome/6.1.1/css/all.min.css" integrity="sha512-KfkfwYDsLkIlwQp6LFnl8zNdLGxu9YAA1QvwINks4PhcElQSvqcyVLLD9aMhXd13uQjoXtEKNosOWaZqXgel0g==" crossorigin="anonymous" referrerpolicy="no-referrer" />
    <link rel="stylesheet" href="https://cdn.jsdelivr.net/npm/bootstrap@4.0.0/dist/css/bootstrap.min.css" integrity="sha384-Gn5384xqQ1aoWXA+058RXPxPg6fy4IWvTNh0E263XmFcJlSAwiGgFAW/dAiS6JXm" crossorigin="anonymous">
    <!-- datatable -->
    <link rel="stylesheet" type="text/css" href="https://cdn.datatables.net/v/dt/dt-1.12.1/b-2.2.3/cr-1.5.6/r-2.3.0/datatables.css" />

</head>

<body>

    <div class="content-views">
        <nav class="navbarSide">
            <a href="{{ url('/ap') }}" class="logo-section">
                <img class="logo-pc" src="{{ asset('assets/img/imgNew/adminWhite.png') }}" alt="logo">
                <div class="logo-text">
                    <img src="{{ asset('assets/img/imgNew/logoChivoWhite.png') }}" alt="">
                    <p>Sistema de administración</p>
                </div>
            </a>

            @auth
            <div class="user-name">
                <i class="fa-solid fa-user"></i>
                <p class="user">{{ Auth::user()->name }}</p>
            </div>
            @endauth

            <ol class="menu-items">
                <li> <a class="nav-option-btn" href="{{ url("/ap/kyc") }}">
                        <div class="icon"><i class="fa-solid fa-address-card"></i></div>
                        <p>KYC ALL</p>
                    </a></li>
                <li><a class="nav-option-btn" href="{{ url("/ap/topups") }}">
                        <div class="icon"><i class="fa-solid fa-credit-card"></i></div>
                        <p>TOPUPS</p>
                    </a></li>
                <li><a class="nav-option-btn" href="{{ url("/assets/Locked20220610.xlsx") }}">
                        <div class="icon"><i class="fa-solid fa-user-xmark"></i></div>
                        <p class="min-text">FRAUDE, LOCKED AND FROZEN LIST USERS</p>
                    </a></li>
                <li><a class="nav-option-btn" href="{{ url("/ap/walletap") }}">
                        <div class="icon"><i class="fa-solid fa-wallet"></i></div>
                        <p>FIND BY WALLET</p>
                    </a></li>
                <li><a class="nav-option-btn" href="#">
                        <div class="icon"><i class="fa-solid fa-scale-balanced"></i></div>
                        <p>FISCALIA</p>
                    </a></li>
                <li><a class="nav-option-btn" href="{{ url("/ap/txap") }}">
                        <div class="icon"><i class="fa-solid fa-money-bill-transfer"></i></div>
                        <p>TRANSACTIONS</p>
                    </a></li>
            </ol>
        </nav>
        @yield('content')
    </div>

    <script src="https://code.jquery.com/jquery-3.6.0.min.js" integrity="sha256-/xUj+3OJU5yExlq6GSYGSHk7tPXikynS7ogEvDej/m4=" crossorigin="anonymous"></script>
    <script src="https://cdnjs.cloudflare.com/ajax/libs/jquery-datetimepicker/2.5.9/jquery.datetimepicker.full.min.js"></script>
    <!-- Datatable -->
    <script type="text/javascript" src="https://cdn.datatables.net/v/dt/dt-1.12.1/b-2.2.3/cr-1.5.6/r-2.3.0/datatables.js"></script>
    <script src="https://cdn.jsdelivr.net/npm/bootstrap@4.0.0/dist/js/bootstrap.min.js" integrity="sha384-JZR6Spejh4U02d8jOt6vLEHfe/JQGiRRSQQxSfFWpi1MquVdAyjUar5+76PVCmYl" crossorigin="anonymous"></script>


    @yield('script')
</body>

</html>