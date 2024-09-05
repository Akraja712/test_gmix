<!DOCTYPE html>
<html>
<head>
    <meta charset="utf-8">
    <meta http-equiv="X-UA-Compatible" content="IE=edge">
    <title>@yield('title', config('app.name'))</title>
    <meta name="viewport" content="width=device-width, initial-scale=1">
    <link rel="stylesheet" href="https://code.ionicframework.com/ionicons/2.0.1/css/ionicons.min.css">
    <link rel="stylesheet" href="https://cdnjs.cloudflare.com/ajax/libs/font-awesome/6.0.0-beta3/css/all.min.css">
    <link rel="stylesheet" href="{{ asset('public/css/app.css') }}">
    <link rel="stylesheet" href="https://cdnjs.cloudflare.com/ajax/libs/font-awesome/5.15.4/css/all.min.css">
    <link rel="stylesheet" href="https://fonts.googleapis.com/css?family=Arvo:400,700&display=swap">
    
    <script src="https://cdn.onesignal.com/sdks/OneSignalSDK.js" async></script>
    <script>
        var OneSignal = window.OneSignal || [];
        OneSignal.push(function() {
            OneSignal.init({
                appId: "56ae8e91-b63e-4a1e-af5e-0defc77ae2f0",
                serviceWorkerPath: '/OneSignalSDKWorker.js',
                serviceWorkerParam: { scope: '/' }
            });
        });
    </script>
    @yield('css')
    <style>
        body, input, button {
            font-family: 'Arvo', sans-serif;
        }
        .modal {
            display: none;
            position: fixed;
            z-index: 1;
            left: 0;
            top: 0;
            width: 100%;
            height: 100%;
            overflow: auto;
            background-color: rgb(0,0,0);
            background-color: rgba(0,0,0,0.4);
        }

        .modal-content {
            background-color: #fefefe;
            margin: 15% auto;
            padding: 20px;
            border: 1px solid #888;
            width: 50%;
        }

        @media (max-width: 768px) {
            .modal-content {
                width: 80%;
                margin: 10% auto;
            }
        }
        .text-enable {
            color: green;
        }
        .text-disable {
            color: red;
        }
        .text-disables {
            color:  #007bff;
}
.text-pending {
    color:  #007bff;
}

.text-paid {
    color: green; /* Or any color/style for paid status */
}

.text-cancelled {
    color: red; /* Or any color/style for cancelled status */
}

/* Add these styles to your CSS file or inside a <style> block in the Blade template */
.status-fake {
    color: red; /* Red color for "Fake" */
    font-weight: bold;
}

.status-not-fake {
    color: blue; /* Blue color for "Not-Fake" */
    font-weight: bold;
}


    </style>
</head>
<body class="hold-transition sidebar-mini">
    <div class="wrapper">
        @include('layouts.partials.navbar')
        @include('layouts.partials.sidebar')
        <div class="content-wrapper">
            <section class="content-header">
                <div class="container-fluid">
                    <div class="row mb-2">
                        <div class="col-sm-6">
                            <h1>@yield('content-header')</h1>
                        </div>
                        <div class="col-sm-6 text-right">
                            @yield('content-actions')
                        </div>
                    </div>
                </div>
            </section>
            <section class="content">
                @include('layouts.partials.alert.success')
                @include('layouts.partials.alert.error')
                @yield('content')
            </section>
        </div>
        @include('layouts.partials.footer')
        <aside class="control-sidebar control-sidebar-dark"></aside>
    </div>
    <script src="{{ asset('public/ckeditor/ckeditor5.js') }}"></script>
    <script src="{{ asset('public/js/app.js') }}"></script>
    @yield('js')
</body>
</html>
