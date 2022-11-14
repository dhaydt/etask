<?php

namespace App\Http\Controllers;

use App\Helpers\AuthHelpers;
use App\Helpers\Helpers;
use App\Models\LoginLogs;
use App\Models\User;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Hash;
use Illuminate\Support\Facades\Validator;

class AutentikasController extends Controller
{
    public function postLogin(Request $request)
    {
        $data = $request->only('nip', 'password');
        $validate = Validator::make($data, [
            'nip' => 'required',
            'password' => 'required|string',
        ], [
            'nip.required' => 'NIP Tidak boleh kosong',
            'password.required' => 'Password tidak boleh kosong',
            'password.string' => 'Password tidak valid !',
        ]);

        if ($validate->fails()) {
            return redirect()->back()->with('fail', $validate->errors()->all()[0]);
        }

        $user = User::where('nip', $data['nip'])->first();
        if (!$user) {
            return redirect()->back()->with('error', 'User dengan NIP '.$data['NIP'].' tidak ditemukan silahkan hubungi administrator');
        } else {
            if (!Hash::check($data['password'], $user->password)) {
                return redirect()->back()->with('error', 'Password salah. silahkan ulangi !');
            }

            $token = Helpers::crypt(date('Y-m-d H:i:s'));

            $agent = Helpers::regexUserAgent($request->header('user-agent'));
            $version = '';
            // $version = $agent['browser'] ? $agent['browser'].' '.$agent['version'] : 'Android';

            $loginLogs = LoginLogs::create([
                'nip_user' => $user['nip'],
                'devices' => $_SERVER['HTTP_USER_AGENT'],
                'token' => $token,
                'is_active' => 1,
                'user_agent' => $version ? $version : 'undetected',
                'ip_address' => $request->ip(),
            ]);

            $request->session()->put('nip', $user['nip']);
            $request->session()->put('token_user', $loginLogs->token);
            $request->session()->put('user_log_id', $loginLogs->id);
            $request->session()->put('role_id', $user->role);
            $request->session()->put('user_id', $user->id);
            $request->session()->put('id_skpd', $user->id_skpd);

            return redirect()->route('dashboard')->with('success', 'Selamat datang '.$user['name']);
        }
    }

    public function checkUser(Request $request)
    {
        $id = $request->nip;
        $user = User::where('nip', $id)->first();
        if ($user) {
            $response = [
                'code' => 200,
                'message' => 'NIP sudah terdaftar, silahkan masukkan password!',
                'data' => $user,
            ];
        } else {
            $data = AuthHelpers::getPresensi($id);
            $response = [
                'code' => 404,
                'message' => 'NIP tidak terdaftar, tapi tersedia di data kepegawaian kami. Silahkan daftarkan akun untuk E-Task anda!',
                'data' => $data,
                'nip' => $id,
            ];
        }

        return $response;
    }

    public function registerUser(Request $request)
    {
        $user = new User();
        $user->nip = $request['nip'];
        $user->id_skpd = $request['id_skpd'];
        $user->name = $request['name_peg'];
        $user->id_eselon = $request['id_eselon'];
        $user->password = Hash::make($request['password']);

        if ($request['nip'] == 1372010910920041 || $request['nip'] == 198611252010011009 || $request['id_eselon'] != 0) {
            $user->role = 1;
        } else {
            $user->role = 2;
        }

        $user->id_skpd = $request['id_skpd'];
        $user->created_at = now();
        $user->updated_at = now();

        $user->save();

        return redirect()->back()->with('success', 'Akun E-Task anda berhasil didaftarkan! Silhakna login menggunakan NIP dan password yang didaftarkan');
    }

    public function logout()
    {
        $loginLogs = LoginLogs::where('nip_user', session()->get('nip'))
        ->where('token', session()->get('token_user'))
        ->first();
        if ($loginLogs) {
            $loginLogs->update([
                'is_active' => 0,
            ]);
        }

        session()->forget('nip');
        session()->forget('token_user');
        session()->forget('user_log_id');
        session()->forget('role_id');

        return redirect()->route('login')->with('success', 'Logout Berhasil');
    }
}
