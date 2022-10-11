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

        @font-face {
            font-family: 'Noto Serif Gujarati';
            font-style: normal;
            font-weight: normal;
            src: url({{ asset('css/font/NotoSerifGujarati-Light.ttf') }});
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
    @php
        $id = auth()->user()->nip;
        if($id){
            $user = App\Helpers\Helpers::getUserDetail($id);
            if($user){
                $skpd = $user['detail']['id_skpd'];
            }else{
                $skpd = 0;
            }
        }else{
            $skpd = 0;
        }

        $newStaffs = [];
        foreach($staffs as $s){
            $staf = [
                'id' => (string)$s['id'],
                'available' => $s['available'],
                'name' => $s['name'],
                'jabatan_id' => $s['jabatan_id'],
                'created_at' => $s['created_at'],
                'foto' => $s['detail']['foto'],
                'updated_at' => $s['updated_at'],
        ];

            array_push($newStaffs, $staf);
        }

    @endphp
    <div id="app">
        <example-component :id_skpd="{{ $skpd }}" :dasar="{{ $dasar }}" :todos="{{ $todo }}" :doing="{{ $doing }}" :done="{{ $done }}" :staffs="{{ collect($newStaffs) }}"
            :user="{{ auth()->user() }}">
        </example-component>
    </div>
    <div class="container-fluid">
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
