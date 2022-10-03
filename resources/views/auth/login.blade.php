<x-guest-layout>
    <div class="row w-100 justify-content-center py-4">
        <div class="col-10 col-md-6">
            <x-auth-card>
                <x-slot name="logo">
                    <a href="{{ route('dashboard') }}">
                        <x-application-logo class="w-100" />
                    </a>
                </x-slot>


                <h5 class="text-center">E-Task Login</h5>
                <div class="card shadow-sm">
                    <!-- Session Status -->
                    <x-auth-session-status class="mb-4" :status="session('status')" />

                    <!-- Validation Errors -->
                    <x-auth-validation-errors class="mb-4" :errors="$errors"/>
                    <div class="card-body">
                        <form method="POST" action="{{ route('login') }}">
                            @csrf

                            <!-- NIP -->
                            <div class="input-group mb-3">
                                <span class="input-group-text" id="basic-addon1">NIP</span>
                                <input type="number" class="form-control" placeholder="Nomor Induk Pegawai" name="nip"
                                    required>
                            </div>

                            <!-- Password -->
                            <div class="input-group mb-3">
                                <span class="input-group-text">Password</span>
                                <input type="password" class="form-control" name="password">
                            </div>

                            <!-- Remember Me -->
                            {{-- <div class="block mt-4">
                                <label for="remember_me" class="inline-flex items-center">
                                    <input id="remember_me" type="checkbox"
                                        class="rounded border-gray-300 text-indigo-600 shadow-sm focus:border-indigo-300 focus:ring focus:ring-indigo-200 focus:ring-opacity-50"
                                        name="remember">
                                    <span class="ml-2 text-sm text-gray-600">{{ __('Remember me') }}</span>
                                </label>
                            </div> --}}

                            <div class="d-flex items-center justify-content-end mt-4">
                                {{-- @if (Route::has('password.request'))
                                <a class="underline text-sm text-gray-600 hover:text-gray-900"
                                    href="{{ route('password.request') }}">
                                    {{ __('Forgot your password?') }}
                                </a>
                                @endif --}}

                                <x-button class=" ml-auto">
                                    {{ __('Masuk') }}
                                </x-button>
                            </div>
                        </form>
                    </div>
                </div>

            </x-auth-card>
        </div>
    </div>
</x-guest-layout>
