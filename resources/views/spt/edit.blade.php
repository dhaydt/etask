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
            src: url({{ asset('css/font/Acme-Regular.ttf')}});
        }

        @font-face {
            font-family: 'Courgette';
            font-style: normal;
            font-weight: normal;
            src: url({{ asset('css/font/Courgette-Regular.ttf')}});
        }

        @font-face {
            font-family: 'Noto Serif Gujarati';
            font-style: normal;
            font-weight: normal;
            src: url({{ asset('css/font/NotoSerifGujarati-Light.ttf')}});
        }

        body {
            background: rgb(68, 68, 68);
            background: linear-gradient(183deg, rgba(68, 68, 68, 1) 45%, rgba(3, 204, 250, 1) 100%);
            min-height: 100vh;
        }

        .bg-img {
            background-image: url('img/bg.jpg');
            position: fixed;
            top: 0;
            bottom: 0;
            left: 0;
            right: 0;
        }

        nav {
            background-color: rgba(89, 89, 151, 0.4392156863);
            transition: background-color 300ms;
            border-bottom: 1px solid var(--dynamic-text-color-transparent);
            box-sizing: border-box;
            display: flex;
            -webkit-backdrop-filter: blur(6px);
            backdrop-filter: blur(6px);
            max-height: 44px;
            padding: 6px 20px;
        }

        nav .app-logo {
            display: block;
            position: relative;
            flex-shrink: 0;
            margin-top: 1px;
            padding: 0 6px;
            height: 32px;
            border-radius: 3px;
        }

        nav .app-logo .logo {
            color: #fff;
            font-weight: 700;
            font-size: 18px;
            font-family: "Courgette", cursive;
        }
        .container .card{
            background-color: #f5f8fad1;
        }

        .fs-10px{
            font-size: 11px;
        }
        .menu-left {
            display: flex;
            flex-basis: 100%;
        }
        .menu-left .menu-left-container {
            align-items: stretch;
            display: flex;
            flex-grow: 1;
            flex-shrink: 0;
            flex-basis: 0;
            height: 100%;
            position: relative;
        }
        button.profile-name{
            position: absolute;
            right: 0;
            cursor: none !important;
            background-color: rgba(255, 255, 255, 0.3019607843);
            box-shadow: none;
            color: #fff;
            border: none;
            /* color: var(--ds-text, inherit); */
        }
        .nav-items{
            height: 32px;
            line-height: 32px;
            margin-right: 4px;
            padding-right: 10px;
            font-weight: 600;
            white-space: nowrap;
        }
        .nav-global {
            color: #172b4d;
            font-family: -apple-system, BlinkMacSystemFont, "Segoe UI", "Roboto", "Noto Sans", "Ubuntu", "Droid Sans", "Helvetica Neue", sans-serif;
            font-size: 14px;
            line-height: 20px;
            font-weight: 600;
            box-sizing: border-box;
            display: inline-flex;
            align-items: center;
            justify-content: center;
            border-radius: 3px;
            cursor: pointer;
            padding: 6px 12px;
            text-decoration: none;
            white-space: normal;
            background-color: var(--ds-background-neutral, rgba(9, 30, 66, 0.04));
            box-shadow: none;
            border: none;
            color: var(--ds-text, inherit);
            transition-property: background-color, border-color, box-shadow;
            transition-duration: 85ms;
            transition-timing-function: ease;
        }
        .status{
            top: 7px;
            right: 7px;
            cursor: pointer;
        }
        .form-switch.form-check-solid .form-check-input {
            border: 1px solid #cfd1d6;
            cursor: pointer;
        }
    </style>
</head>

<body>
    <div class="bg-img"></div>
    <nav id="header" data-test-id="header-container" data-desktop-id="header-inner" aria-label="Primary"
        class="headers">
        <a href="javascript:" aria-label="Back to home" class="app-logo">
            <div class="h-100 d-flex align-items-center">
                <div class="logo d-flex align-items-center"><img src="img/etask.png" height="23" alt="" class="me-2">
                    E-Tasks
                </div>
            </div>
        </a>
        <div class="menu-left">
            <div class="menu-left-container position-relative">
                <div>
                    <button
                        class="nav-items nav-global profile-name"
                        data-test-id="recently-viewed-boards-menu"
                        type="button"
                        aria-haspopup="true"
                        aria-expanded="false"
                        title="Recent"
                        aria-label="Recent boards"
                    >
                        <span class="nav-item-title me-2 text-light">{{ $user['name'] }}</span>
                    </button>
                </div>
                <div class="dyyadVYdVIR0rS">
                    <div
                        style="display: block; width: 100%; position: absolute"
                    ></div>
                </div>
            </div>
        </div>
    </nav>
    <div class="container">
        <div class="row">

            {{-- <div class="tab-content">
                <div class="tab-pane fade show active" id="kt_table_widget_5_tab_1" role="tabpanel">
                    <div class="table-responsive">
                        <table class="table table-row-dashed table-row-gray-200 align-middle gs-0 gy-4">
                            <thead>
                                <tr class="border-0">
                                    <th class="p-0 w-50px"></th>
                                    <th class="p-0 min-w-150px"></th>
                                    <th class="p-0 min-w-140px"></th>
                                    <th class="p-0 min-w-110px"></th>
                                    <th class="p-0 min-w-50px"></th>
                                </tr>
                            </thead>
                            <tbody>
                                @foreach ($spt as $s)
                                <tr>
                                    <td>
                                        <div class="symbol symbol-45px me-2">
                                            <span class="symbol-label">
                                                <i class="fa-solid fa-book" style="font-size:2rem;"></i>
                                            </span>
                                        </div>
                                    </td>
                                    <td class="text-center">
                                        <a href="javascript:" class="text-dark fw-bold text-hover-primary mb-1 fs-6">{{ $s->dasar }}</a>
                                    </td>
                                    <td class="text-end text-muted fw-bold">{{ $s->keterangan }}</td>
                                    <td class="text-end">
                                        <div class="justify-content-end form-check form-switch form-check-custom form-check-solid">
                                                <input
                                                    class="form-check-input h-20px w-30px"
                                                    {{ $s->status == 1 ? 'checked' : '' }}
                                                    type="checkbox"
                                                    id="flexSwitchDefault"
                                                    onchange="updateDasar({{ $s }})"
                                                />
                                                @if ($s->status == 0)
                                                <label class="form-check-label" for="flexSwitchDefault">
                                                    <span class="badge badge-light-danger">Non Active</span>
                                                </label>
                                                @else
                                                <label class="form-check-label" for="flexSwitchDefault">
                                                    <span class="badge badge-light-success">Active</span>
                                                </label>
                                                @endif
                                            </div>
                                    </td>
                                    <td class="text-end">
                                        <div class="d-flex justify-content-center">
                                            <button onclick="editModal({{ $s }})"
                                                class="btn btn-sm btn-light-success"><i
                                                    class="fa-solid fa-edit"></i></button>
                                        </div>
                                    </td>
                                </tr>
                                @endforeach
                            </tbody>
                        </table>
                    </div>
                </div>
            </div> --}}
        </div>

    </div>
    <div class="main-body mt-4">
        <div class="container">
            <div class="card">
                <div class="card-header border-0 pt-5">
                    <h3 class="card-title align-items-start flex-column">
                        <span class="card-label fw-bold fs-3 mb-1">Kelola Surat Perintah Tugas</span>
                        {{-- <span class="text-muted mt-1 fw-semibold fs-7">More than 400 new products</span> --}}
                    </h3>
                </div>
                <div class="card-body py-3">
                    <div class="row">
                        @foreach ($spt as $s)
                        <div class="col-md-6 col-12">
                            <div class="card mb-5 mb-xxl-8">
                                <div class="card-body pb-0 position-relative">
                                    <div class="status position-absolute">
                                        <div data-bs-toggle="tooltip" title="Ubah Status SPT" class="justify-content-end form-check form-switch form-check-custom form-check-solid">
                                            @if ($s->status == 0)
                                            <label class="form-check-label me-2" for="flexSwitchDefault">
                                                <span class="badge badge-light-danger">Non Aktif</span>
                                            </label>
                                            @else
                                            <label class="form-check-label me-2" for="flexSwitchDefault">
                                                <span class="badge badge-light-success">Aktif</span>
                                            </label>
                                            @endif
                                            <input
                                                class="form-check-input h-20px w-30px"
                                                {{ $s->status == 1 ? 'checked' : '' }}
                                                type="checkbox"
                                                id="flexSwitchDefault"
                                                onchange="updateDasar({{ $s }})"
                                            />
                                        </div>
                                    </div>
                                    <div class="d-flex align-items-center mb-5">
                                        <div class="d-flex align-items-center flex-grow-1">
                                            <div class="symbol symbol-45px me-5">
                                                <img src="{{ asset('img/order.webp') }}" alt="">
                                            </div>
                                            <div class="d-flex flex-column">
                                                <a href="#" class="text-gray-900 text-hover-primary fs-6 fw-bold">{{ \App\Helpers\Helpers::getSkpd($s['user']['id_skpd']) }}</a>
                                                <span class="text-gray-400 fw-bold fs-10px">Oleh: {{ $s['user']['name'] }}</span>
                                            </div>
                                        </div>
                                        <div class="my-0">
                                            {{-- <button type="button" class="btn btn-sm btn-icon btn-color-primary btn-active-light-primary" data-kt-menu-trigger="click" data-kt-menu-placement="bottom-end"> --}}
                                            <button type="button" data-bs-toggle="tooltip" title="Ubah SPT" class="btn btn-sm btn-icon btn-color-primary btn-active-light-primary" onclick="editModal({{ $s }})">
                                                <span class="svg-icon svg-icon-2 text-primary">
                                                    <i class="fa-solid fa-edit" style="font-size: 18px;"></i>
                                                </span>
                                            </button>
                                        </div>
                                    </div>
                                    <div class="mb-5">
                                        <p class="text-gray-800 fw-normal mb-5">{{ $s->dasar }}</p>
                                    </div>
                                </div>
                            </div>
                        </div>
                        @endforeach
                    </div>
                </div>
            </div>

            <div class="modal fade" id="modalSpt" tabindex="-1" aria-labelledby="exampleModalLabel" aria-hidden="true">
                <div class="modal-dialog">
                <div class="modal-content">
                    <div class="modal-header">
                        <h5 class="modal-title" id="exampleModalLabel">Ubah Surat Perintah Tugas</h5>
                    <button type="button" class="btn-close" data-bs-dismiss="modal" aria-label="Close"></button>
                    </div>
                    <form action="{{ route('update.spt') }}" method="POST">
                        @csrf
                    <div class="modal-body">
                        <div class="input">
                            <input type="hidden" name="id" id="idSpt">
                            <label for="dasar" class="form-label fw-bold">Dasar</label>
                            <textarea name="dasar" class="form-control" id="dasarNew" rows="3" required></textarea>
                        </div>
                        <div class="input mt-4">
                            <label for="ket" class="form-label fw-bold">Keterangan</label>
                            <textarea name="keterangan" id="ketNew" class="form-control" rows="3" required></textarea>
                        </div>
                    </div>
                    <div class="modal-footer">
                    <button type="submit" class="btn btn-primary">Ubah</button>
                    </form>
                    </div>
                </div>
                </div>
            </div>
        </div>
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


    @include('template.script')
    <script>
        $(document).ready(function(){
            setInterval(session, 3600500);
        })

        function editModal(data){
            var myModal = new bootstrap.Modal(document.getElementById('modalSpt'), {
                keyboard: false
            })
            console.log('dasar', data)
            $('#dasarNew').text(data.dasar);
            $('#ketNew').text(data.dasar);
            $('#idSpt').val(data.id);
            myModal.show();
        }

        function updateDasar(data){
            console.log('status', data)
            $.ajaxSetup({
                        headers: {
                            'X-CSRF-TOKEN': $('meta[name="csrf-token"]').attr('content')
                        }
                    });
                    $.ajax({
                        url: "{{route('updateDasarStatus')}}",
                        method: 'POST',
                        data: {
                            id: data.id,
                            status: data.status
                        },
                        success: function (resp) {
                            // console.log('err status', resp);
                            if(resp.code == 200){
                                toastr.success('Status SPT berhasil di Ubah!');
                            }else{
                                console.log('err status', resp);
                            }
                            location.reload();
                        }
                    });
            // axios.post('updateDasarStatus',{
            //     id: id,
            //     status: active
            // }, this.config).then(function(resp){
            //     var data = resp.data;
            //     if(data.code == 200){
            //         that.updateDasarStatus(data.data);
            //         Vue.$toast.success(data.message);
            //     }
            //     console.log('respon',data);
            // }).catch(function(err){
            //     window.alert(err);
            // })
        }

        function session(){
            location.reload();
        }
    </script>
</body>

</html>
