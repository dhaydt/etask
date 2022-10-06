<!DOCTYPE html>
<html lang="en">

<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <meta http-equiv="X-UA-Compatible" content="ie=edge">
    <meta name="csrf-token" content="{{ csrf_token() }}">

    <title>E-Task</title>

    @include('template.header')
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
        .vs__selected-options .vs__selected{
            font-size: 12px;
            font-family: "Acme", sans-serif;
            border: none;
        }

    </style>
</head>

<body>

    <div id="app">
        <example-component :dasar="{{ $dasar }}" :todos="{{ $todo }}" :doing="{{ $doing }}" :done="{{ $done }}" :staffs="{{ $staffs }}"
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

    @include('template.script')

</body>

</html>
