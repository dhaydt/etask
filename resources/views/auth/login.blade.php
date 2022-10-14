<x-guest-layout>
    <img class="wave" src="{{ asset('img/wave.png') }}">
    <div class="container">
        <div class="img">
            <img src="{{ asset('img/etask2.gif') }}">
        </div>
        <div class="login-content">
            <div class="toast-session">
                <!-- Session Status -->
                <x-auth-session-status class="mb-4" :status="session('status')" />

                <!-- Validation Errors -->
                <x-auth-validation-errors class="mb-4" :errors="$errors" />
            </div>
            <div class="login-card" style="width: 360px;">
                {{-- <form method="POST" action="{{ route('login') }}"> --}}
                    @csrf
                    <img src="{{ asset('img/etask.png') }}">
                    <h3 class="title mt-3">E-TASK Login</h3>
                    <div class="input-div one">
                        <div class="i">
                            <i class="fas fa-user"></i>
                        </div>
                        <div class="div">
                            <h5>NIP</h5>
                            <input type="number" id="nip" class="input" name="nip">
                        </div>
                    </div>
                    {{-- <div class="input-div pass">
                        <div class="i">
                            <i class="fas fa-lock"></i>
                        </div>
                        <div class="div">
                            <h5>Password</h5>
                            <input type="password" name="password" class="input">
                        </div>
                    </div> --}}
                    <x-button class="login-btn btn" onclick="checkUser()">
                        <div class="spin spinner-border text-info spinner-border-sm d-none" role="status">
                        </div>
                        <span>
                            {{ __('Masuk') }}
                        </span>
                    </x-button>
                    {{--
                </form> --}}
            </div>
        </div>
        <!-- Modal Login-->
        <div class="modal fade" id="loginModal" data-bs-backdrop="static" data-bs-keyboard="false" tabindex="-1"
            aria-labelledby="staticBackdropLabel" aria-hidden="true">
            <div class="modal-dialog">
                <div class="modal-content">
                    <div class="modal-header">
                        <h5 class="modal-title" id="staticBackdropLabel">Login E-Task</h5>
                        <button type="button" class="btn-close" data-bs-dismiss="modal" aria-label="Close"></button>
                    </div>
                    {{-- @if (\Session::has('success'))
                    <div class="alert alert-success">
                        <ul>
                            <li>{!! \Session::get('success') !!}</li>
                        </ul>
                    </div>
                    @endif --}}
                    <form method="POST" action="{{ route('post-login') }}" class="w-100">
                        @csrf
                        <div class="modal-body">
                            <div class="input-group mb-3">
                                <span class="input-group-text" id="basic-addon1"><i class="fas fa-user"></i></span>
                                <input type="text" name="nip" id="nipUser" class="form-control" aria-label="Username"
                                    readonly aria-describedby="basic-addon1">
                            </div>
                            <div class="input-group mb-3">
                                <span class="input-group-text" id="basic-addon1"><i class="fas fa-lock"></i></span>
                                <input type="password" name="password" class="form-control"
                                    placeholder="Masukan Password anda" aria-describedby="basic-addon1" required>
                            </div>
                        </div>
                        <div class="modal-footer" >
                            <button class="btn btn-success btn-sm login-post" onclick="postLogin()">
                                <div class="spin spinner-border text-info spinner-border-sm d-none" role="status">
                                </div>
                                <span>
                                    {{ __('Masuk') }}
                                </span>
                            </button>
                        </div>
                    </form>
                </div>
            </div>
        </div>

        <!-- Modal Login-->
        <div class="modal fade" id="registerModal" data-bs-backdrop="static" data-bs-keyboard="false" tabindex="-1"
            aria-labelledby="staticBackdropLabel" aria-hidden="true">
            <div class="modal-dialog">
                <div class="modal-content">
                    <div class="modal-header">
                        <h5 class="modal-title" id="staticBackdropLabel">Register E-Task</h5>
                        <button type="button" class="btn-close" data-bs-dismiss="modal" aria-label="Close"></button>
                    </div>
                    <form method="POST" action="{{ route('reg') }}" class="w-100">
                        <input type="hidden" name="foto">
                        <input type="hidden" name="active">
                        <input type="hidden" name="gelarBlk">
                        <input type="hidden" name="gelarDpn">
                        <input type="hidden" name="id_agama">
                        <input type="hidden" name="id_jabatan">
                        <input type="hidden" name="id_skpd">
                        <input type="hidden" name="id_status">
                        <input type="hidden" name="nama_peg">
                        <input type="hidden" name="no_hp">
                        <input type="hidden" name="nik">
                        @csrf
                        <div class="modal-body">
                            <div class="input-group mb-3">
                                <span class="input-group-text" id="basic-addon1"><i class="fas fa-user"></i></span>
                                <input type="text" name="nip" id="nipUserReg" class="form-control" aria-label="Username"
                                    readonly aria-describedby="basic-addon1">
                            </div>
                            <div class="input-group mb-3">
                                <span class="input-group-text" id="basic-addon1"><i class="fas fa-lock"></i></span>
                                <input type="password" name="password" class="form-control"
                                    placeholder="Masukan Password anda" aria-describedby="basic-addon1" required>
                            </div>
                            <div class="input-group mb-3">
                                <span class="input-group-text" id="basic-addon1"><i class="fas fa-lock"></i></span>
                                <input type="password" name="password_confirm" class="form-control"
                                    placeholder="Konfirmasi password anda" aria-describedby="basic-addon1" required>
                            </div>
                        </div>
                        <div class="modal-footer">
                            <button type="submit" class="btn btn-primary reg-btn" onclick="toggleLoading()">
                                <div class="spin spinner-border text-info spinner-border-sm d-none" role="status">
                                </div>
                                <span>
                                    {{ __('Daftar') }}
                                </span>
                            </button>
                        </div>
                    </form>
                </div>
            </div>
        </div>
    </div>
</x-guest-layout>
<script>
    function toggleLoading(){
        $('.reg-btn .spin').removeClass('d-none');
        $('.reg-btn span').addClass('d-none');
    }
    function postLogin(){
        $('.login-post .spin').removeClass('d-none');
        $('.login-post span').addClass('d-none');
    }
    function checkUser(){
        $('.login-btn .spin').removeClass('d-none');
        $('.login-btn span').addClass('d-none');
        var nip = $('#nip').val();
        console.log('called', nip)
        if(nip == ''){
            toastr.warning('Mohon masukan NIP anda!')
            $('.login-btn span').removeClass('d-none');
            $('.login-btn .spin').addClass('d-none');
        }else{
            $.ajaxSetup({
                headers: {
                    'X-CSRF-TOKEN': $('meta[name="csrf-token"]').attr('content')
                }
            });

            $.ajax({
                url : "{{ route('checkUser') }}",
                type : "POST",
                data: {
                    nip : nip
                },
                success: function(data){
                    $('.login-btn span').removeClass('d-none');
                    $('.login-btn .spin').addClass('d-none');
                    if(data.code == 200){
                        toastr.success(data.message);
                        $('#nipUser').val(String(data.data.nip))
                        $('#loginModal').modal('show');
                        console.log('checked user', data);
                    }else if(data.code == 404){
                        toastr.warning(data.message)
                        var responData = data.data
                        console.log('dataReg', responData.data)
                        $('input[name="foto"]').val(responData.data.foto);
                        $('input[name="acive"]').val(responData.data.active);
                        $('input[name="gelarBlk"]').val(responData.data.gelarblk);
                        $('input[name="gelarDpn"]').val(responData.data.gelardpn);
                        $('input[name="id_agama"]').val(responData.data.id_agama);
                        $('input[name="id_jabatan"]').val(responData.data.id_jabatan);
                        $('input[name="id_skpd"]').val(responData.data.id_skpd);
                        $('input[name="id_status"]').val(responData.data.id_status);
                        $('input[name="nama_peg"]').val(responData.data.nama);
                        $('input[name="nohp"]').val(responData.data.nohp);
                        $('input[name="nik"]').val(responData.data.nik);
                        $('#nipUserReg').val(String(data.nip))
                        $('#registerModal').modal('show');
                    }else{
                        toastr.danger('Error Data')
                        console.log('err', data);
                    }
                }
            })
        }
    }
</script>
