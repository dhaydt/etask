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
                    <x-button class=" btn" onclick="checkUser()">
                        {{ __('Masuk') }}
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
                    <form method="POST" action="{{ route('login') }}" class="w-100">
                        @csrf
                    <div class="modal-body">
                        <div class="input-group mb-3">
                            <span class="input-group-text" id="basic-addon1"><i class="fas fa-user"></i></span>
                            <input type="text" name="nip" id="nipUser" class="form-control" aria-label="Username" readonly aria-describedby="basic-addon1">
                        </div>
                        <div class="input-group mb-3">
                            <span class="input-group-text" id="basic-addon1"><i class="fas fa-lock"></i></span>
                            <input type="password" name="password" class="form-control" placeholder="Masukan Password anda"  aria-describedby="basic-addon1" required>
                        </div>
                    </div>
                    <div class="modal-footer">
                        <x-button class=" btn">
                            {{ __('Masuk') }}
                        </x-button>
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
                        @csrf
                    <div class="modal-body">
                        <div class="input-group mb-3">
                            <span class="input-group-text" id="basic-addon1"><i class="fas fa-user"></i></span>
                            <input type="text" name="nip" id="nipUser" class="form-control" aria-label="Username" readonly aria-describedby="basic-addon1">
                        </div>
                        <div class="input-group mb-3">
                            <span class="input-group-text" id="basic-addon1"><i class="fas fa-lock"></i></span>
                            <input type="password" name="password" class="form-control" placeholder="Masukan Password anda"  aria-describedby="basic-addon1" required>
                        </div>
                        <div class="input-group mb-3">
                            <span class="input-group-text" id="basic-addon1"><i class="fas fa-lock"></i></span>
                            <input type="password" name="password_confirm" class="form-control" placeholder="Konfirmasi password anda"  aria-describedby="basic-addon1" required>
                        </div>
                    </div>
                    <div class="modal-footer">
                        <button type="submit" class="btn btn-primary">Daftar</button>
                    </div>
                    </form>
                </div>
            </div>
        </div>
    </div>
</x-guest-layout>
<script>
    function checkUser(){
        var nip = $('#nip').val();
        console.log('called', nip)
        if(nip == ''){
            toastr.warning('Mohon masukan NIP anda!')
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
                    if(data.code == 200){
                        toastr.success(data.message);
                        $('#nipUser').val(String(data.data.nip))
                        $('#loginModal').modal('show');
                        console.log('checked user', data);
                    }else if(data.code == 404){
                        toastr.warning(data.message)
                        console.log('dataReg', data)
                        // $('#nipUser').val(String(data.data.nip))
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
