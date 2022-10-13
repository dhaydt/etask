<!DOCTYPE html>
<html lang="{{ str_replace('_', '-', app()->getLocale()) }}">

<head>
    <meta charset="utf-8">
    <meta name="viewport" content="width=device-width, initial-scale=1">
    <meta name="csrf-token" content="{{ csrf_token() }}">

    <title>{{ config('app.name', 'Laravel') }}</title>
    <link rel="stylesheet" href="https://cdnjs.cloudflare.com/ajax/libs/font-awesome/6.2.0/css/all.min.css"
        integrity="sha512-xh6O/CkQoPOWDdYTDqeRdPCVd1SpvCA9XXcUnZS2FmJNp1coAFzvtCN9BmamE+4aHK8yyUHUSCcJHgXloTyT2A=="
        crossorigin="anonymous" referrerpolicy="no-referrer" />
    <link href="https://cdn.jsdelivr.net/npm/bootstrap@5.0.2/dist/css/bootstrap.min.css" rel="stylesheet"
        integrity="sha384-EVSTQN3/azprG1Anm3QDgpJLIm9Nao0Yz1ztcQTwFspd3yD65VohhpuuCOmLASjC" crossorigin="anonymous">
    <link rel="preconnect" href="https://fonts.googleapis.com">
    <link rel="preconnect" href="https://fonts.gstatic.com" crossorigin>
    <link href="https://fonts.googleapis.com/css2?family=Courgette&display=swap" rel="stylesheet">
    <!-- Fonts -->
    <link href="https://fonts.googleapis.com/css?family=Poppins:600&display=swap" rel="stylesheet">
	<script src="https://kit.fontawesome.com/a81368914c.js"></script>
    <link rel="stylesheet" href="https://fonts.googleapis.com/css2?family=Nunito:wght@400;600;700&display=swap">


<link href="{{ asset('/assets/plugins/custom/fullcalendar/fullcalendar.bundle.css') }}" rel="stylesheet"
type="text/css" />
<link href="{{ asset('/assets/plugins/custom/datatables/datatables.bundle.css') }}" rel="stylesheet" type="text/css" />
<link href="{{ asset('/assets/plugins/global/plugins.bundle.css') }}" rel="stylesheet" type="text/css" />
<link href="{{ asset('/assets/css/style.bundle.css') }}" rel="stylesheet" type="text/css" />
<link rel="stylesheet" href="{{ asset('/assets/css/custom.css') }}" type="text/css">
    <link rel="stylesheet" type="text/css" href="{{ asset('css/login.css') }}">
    <link rel="stylesheet" type="text/css" href="{{ asset('css/toastr.css') }}">
    <!-- Scripts -->
    {{-- @vite(['resources/css/app.css', 'resources/js/app.js']) --}}
    <style>
    .toast-session{
        position: absolute;
        top: 15px;
        right: 15px;
    }
    input:-internal-autofill-selected{
        border-radius: 20px !important;
    }
    </style>
</head>

<body>
    <div class="font-sans text-gray-900 antialiased">
        {{ $slot }}
    </div>
    <script type="text/javascript" src="{{ asset('js/login.js') }}"></script>
    <script src="{{ asset('js/jquery-2.2.4.min.js') }}"></script>
    <script src={{asset("js/toastr.js")}}></script>
    <script src="{{ asset('/assets/plugins/global/plugins.bundle.js') }}"></script>

<script src="{{ asset('/assets/js/scripts.bundle.js') }}"></script>
<script src="{{ asset('/assets/plugins/custom/fullcalendar/fullcalendar.bundle.js') }}"></script>
<script src="{{ asset('/assets/plugins/custom/datatables/datatables.bundle.js') }}"></script>
<script src="{{ asset('/assets/js/widgets.bundle.js') }}"></script>
<script src="{{ asset('/assets/js/custom/widgets.js') }}"></script>
<script src="{{ asset('/assets/js/custom/apps/chat/chat.js') }}"></script>
<script src="{{ asset('/assets/js/custom/utilities/modals/create-project/type.js') }}"></script>
<script src="{{ asset('/assets/js/custom/utilities/modals/create-project/budget.js') }}"></script>
<script src="{{ asset('/assets/js/custom/utilities/modals/create-project/settings.js') }}"></script>
<script src="{{ asset('/assets/js/custom/utilities/modals/create-project/team.js') }}"></script>
<script src="{{ asset('/assets/js/custom/utilities/modals/create-project/targets.js') }}"></script>
<script src="{{ asset('/assets/js/custom/utilities/modals/create-project/files.js') }}"></script>
<script src="{{ asset('/assets/js/custom/utilities/modals/create-project/complete.js') }}"></script>
<script src="{{ asset('/assets/js/custom/utilities/modals/create-project/main.js') }}"></script>
<script src="{{ asset('/assets/js/custom/utilities/modals/users-search.js') }}"></script>
<script src="{{ asset('/assets/js/alpine.min.js') }}"></script>
<script src="{{ asset('/assets/js/custom.js') }}"></script>
</body>

</html>
