<!DOCTYPE html>
<html lang="en">

<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <meta http-equiv="X-UA-Compatible" content="ie=edge">
    <title>E-Task</title>
    <link href="{{ asset('css/fontawesome/all.min.css') }}" rel="stylesheet">
    <link href="{{ asset('css/fontawesome/fontawesome.min.css') }}" rel="stylesheet">
    <link rel="stylesheet" href="{{ asset('bootstrap/css/bootstrap.min.css') }}">
    <link rel="preconnect" href="https://fonts.googleapis.com">
    <link rel="preconnect" href="https://fonts.gstatic.com" crossorigin>
    <meta name="csrf-token" content="{{ csrf_token() }}">
    <style>
        @font-face {
            font-family: 'Acme';
            font-style: normal;
            font-weight: normal;
            src: url({{ asset('css/font/Acme-Regular.ttf') }});
        }

        @font-face {
            font-family: 'Courgette';
            font-style: normal;
            font-weight: normal;
            src: url({{ asset('css/font/Courgette-Regular.ttf') }});
        }

        body {
            background: rgb(68, 68, 68);
            background: linear-gradient(183deg, rgba(68, 68, 68, 1) 45%, rgba(3, 204, 250, 1) 100%);
            background-image: url('img/bg.jpg');
            min-height: 100vh;
        }
        .alert.alert-danger{
            z-index: 1;
            margin-top: 50px;
        }

    </style>
</head>

<body>

    <div id="app">
        <example-component :todos="{{ $todo }}" :doing="{{ $doing }}" :done="{{ $done }}" :staffs="{{ $staffs }}"
            :user="{{ auth()->user() }}">
        </example-component>

    </div>
    <div class="container">
        @if ($errors->any())
        <div class="alert alert-danger">
            <ul>
                @foreach ($errors->all() as $error)
                <li>{{ $error }}</li>
                @endforeach
            </ul>
        </div>
        @endif
    </div>

    <script src="{{ mix('/js/app.js') }}"></script>
    <script src="{{ asset('bootstrap/js/bootstrap.bundle.js') }}"></script>
    <script src="{{ asset('js/jquery-3.6.js') }}"></script>

</body>

</html>
