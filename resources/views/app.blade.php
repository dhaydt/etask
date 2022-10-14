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
            min-height: 100vh;
        }
        .bg-img{
            background-image: url('img/bg.jpg');
            position: fixed;
            top: 0;
            bottom: 0;
            left: 0;
            right: 0;
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
        .menu.menu-user.menu-sub.menu-sub-dropdown.menu-column{
            border-radius: 4px;
        }

        .datetime-picker.flatpickr-input.active{
            display: inline-block;
            z-index: 99999;
            background: #fff;
        }

    </style>
</head>

<body>
    <div class="bg-img"></div>
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

        $role = auth()->user()->role;

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


        $u = $user;
        if($user){
            $format = [
                'id' => $u['id'],
                'name' => $u['name'],
                'created_at' => $u['created_at'],
                'updated_at' => $u['updated_at'],
                'available' => $u['available'],
                'foto' => $u['detail']['foto'],
                'id_skpd' => $u['detail']['id_skpd'],
            ];
        }else{
            $format = [
                'id' => 0,
                'name' => 'default',
                'created_at' => '',
                'updated_at' => '',
                'available' => 0,
                'foto' => '',
                'id_skpd' => 11,
        ];
        }

    @endphp
    <div id="app" class="mb-3">
        <main-component :roles="{{ $role }}" :id_skpd="{{ $skpd }}" :dasar="{{ $dasar }}" :todos="{{ $todo }}" :doing="{{ $doing }}" :done="{{ $done }}" :staffs="{{ collect($newStaffs) }}"
            :user="{{ collect($format) }}">
        </main-component>
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
