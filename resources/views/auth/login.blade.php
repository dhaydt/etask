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
                <x-auth-validation-errors class="mb-4" :errors="$errors"/>
            </div>
            <form method="POST" action="{{ route('login') }}">
                @csrf
                <img src="{{ asset('img/etask.png') }}">
                <h3 class="title mt-3">E-TASK Login</h3>
                <div class="input-div one">
                    <div class="i">
                        <i class="fas fa-user"></i>
                    </div>
                    <div class="div">
                        <h5>NIP</h5>
                        <input type="text" class="input" name="nip">
                    </div>
                </div>
                <div class="input-div pass">
                    <div class="i">
                        <i class="fas fa-lock"></i>
                    </div>
                    <div class="div">
                        <h5>Password</h5>
                        <input type="password" name="password" class="input">
                    </div>
                </div>
                <x-button class=" btn">
                    {{ __('Masuk') }}
                </x-button>
            </form>
        </div>
    </div>
</x-guest-layout>
