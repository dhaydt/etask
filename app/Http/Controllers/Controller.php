<?php

namespace App\Http\Controllers;

use App\Helpers\Helpers;
use App\Helpers\TaskHelpers;
use App\Models\AsnTerkait;
use App\Models\Dasar;
use App\Models\User;
use GuzzleHttp\Client;
use GuzzleHttp\Exception\ClientException;
use Illuminate\Foundation\Auth\Access\AuthorizesRequests;
use Illuminate\Foundation\Bus\DispatchesJobs;
use Illuminate\Foundation\Validation\ValidatesRequests;
use Illuminate\Http\Request;
use Illuminate\Routing\Controller as BaseController;
use Illuminate\Support\Facades\Hash;
use Yoeunes\Toastr\Facades\Toastr;

class Controller extends BaseController
{
    use AuthorizesRequests;
    use DispatchesJobs;
    use ValidatesRequests;

    public function editSpt()
    {
        $data['spt'] = Dasar::orderBy('created_at', 'desc')->get();

        return view('spt.edit', $data);
    }

    public function updateSpt(Request $request)
    {
        $spt = Dasar::find($request['id']);
        if (!$spt) {
            Toastr::info('Surat Perintah Tugas Tidak Ditemukan');

            return redirect()->back();
        } else {
            $spt->dasar = $request['dasar'];
            $spt->keterangan = $request['keterangan'];
            $spt->save();

            Toastr::success('Surat Perintah Tugas Berhasil di Ubah!!');

            return redirect()->back();
        }
    }

    public function staffList()
    {
        $staff = AsnTerkait::where('id_users', session()->get('user_id'))->get();

        return response()->json($staff);
    }

    public function getSkpd(Request $request)
    {
        $token = Helpers::getToken();

        $key = $token;

        $main_api = 'https://apidoc.bukittinggikota.go.id/simpeg/public/api';

        $api = $main_api.'/data_pegawai_skpd';

        $header = [
            'Authorization' => 'Bearer'.$key,
        ];

        try {
            $client = new Client();

            $response = $client->request('POST', $api, [
                'headers' => $header,
            ]);

            $status = $response->getStatusCode();

            session()->put('token', $key);

            if ($status == 200) {
                $resp = json_decode($response->getBody()->getContents())->api_status;

                if ($resp == 0) {
                    $data = [
                        'code' => 404,
                        'message' => 'SKPD tidak ditemukan',
                    ];

                    return $data;
                } else {
                    $user = AsnTerkait::where('id_users', session()->get('user_id'))->get();
                    $dataMentah = json_decode($response->getBody())->data;
                    $dataSkpd = [];
                    $grouped = [];
                    foreach ($dataMentah as $dm) {
                        if ($dm->id_skpd == $request->id_skpd) {
                            array_push($dataSkpd, $dm);
                        }

                        /*
                            * If the key in the new array does not exists, set it to a blank array.
                        */
                        if (!isset($grouped[$dm->nama_skpd])) {
                            $grouped[$dm->nama_skpd] = [];
                        }

                        /*
                            * Add a new item to the array, making shore it falls into the correct category / key
                        */
                        $grouped[$dm->nama_skpd][] = $dm;
                    }

                    $data = [
                        'code' => 200,
                        'data' => $dataSkpd,
                        'user' => $user,
                        'dataMentah' => $grouped,
                    ];

                    return $data;
                }
            }
        } catch (ClientException $e) {
            $this->getSkpd($request);
        }
    }

    public function updateDasarStatus(Request $request)
    {
        $dasar = Dasar::find($request->id);
        $status = $request->status;
        if ($status == true) {
            $status = 0;
        } else {
            $status = 1;
        }
        if ($dasar) {
            $dasar->status = $status;
            $dasar->save();

            $data = TaskHelpers::refresh();

            return response()->json([
                'code' => 200,
                'message' => 'Status dasar SPT berhasil di Ubah!',
                'data' => $data,
            ]);
        }

        return response()->json([
            'code' => 404,
            'message' => 'Dasar tidak ditemukan!',
        ]);
    }

    public function addStaff(Request $request)
    {
        $data = $request['user'];
        $auth = session()->get('user_id');
        if ($request->type == 'warga') {
            $checkStaff = AsnTerkait::where(['id_users' => $auth, 'nip_terkait' => $request->nip])->get();
            if (count($checkStaff) < 1) {
                $staff = new AsnTerkait();
                $staff->id_users = $auth;
                $staff->nip_terkait = $request->nik;
                $staff->nama = $request->nama;
                $staff->gelar_depan = '';
                $staff->gelar_belakang = '';
                $staff->tempat_lahir = null;
                $staff->foto = '';
                $staff->tanggal_lahir = null;
                $staff->id_jenis_kelamin = null;
                $staff->id_agama = null;
                $staff->alamat = null;
                $staff->no_hp = $request->phone;
                $staff->nik = $request->nik;
                $staff->nama_jabatan = $request->profesi;
                $staff->id_sotk = 0;
                $staff->id_skpd = session()->get('id_skpd');
                $staff->pangkat = '-';
                $staff->available = 1;
                $staff->type = 'warga';
                $staff->save();
            }
            $refresh = TaskHelpers::refresh();

            return response()->json([
                'code' => 200,
                'message' => 'Berhasil menyimpan warga',
                'data' => $refresh,
        ]);
        } else {
            if ($request['status'] == false) {
                $check = User::where('nip', $data['nip'])->first();
                if (!$check) {
                    $user = new User();
                    $user->nip = $data['nip'];
                    $user->name = $data['nama_pegawai'];
                    $user->password = Hash::make($data['nip']);
                    if ($data['nip'] == 1372010910920041 || $data['nip'] == 198611252010011009 || $data['nip'] == 1375010903960003) {
                        $user->role = 1;
                    } else {
                        $user->role = 2;
                    }
                    $user->id_skpd = $data['id_skpd'];
                    $user->created_at = now();
                    $user->updated_at = now();
                }

                $checkStaff = AsnTerkait::where(['id_users' => $auth, 'nip_terkait' => $data['nip']])->get();
                if (count($checkStaff) < 1) {
                    $staff = new AsnTerkait();
                    $staff->id_users = $auth;
                    $staff->nip_terkait = $data['nip'];
                    $staff->nama = $data['nama_pegawai'];
                    $staff->gelar_depan = '';
                    $staff->gelar_belakang = '';
                    $staff->tempat_lahir = null;
                    $staff->foto = $data['foto'];
                    $staff->tanggal_lahir = null;
                    $staff->id_jenis_kelamin = null;
                    $staff->id_agama = null;
                    $staff->alamat = null;
                    $staff->no_hp = $data['no_hp'];
                    $staff->nik = null;
                    $staff->nama_jabatan = $data['nama_jabatan'];
                    $staff->id_sotk = $data['id_sotk'];
                    $staff->id_skpd = $data['id_skpd'];
                    $staff->pangkat = $data['pangkat'].'/ '.$data['golongan'];
                    $staff->available = 1;
                    $staff->type = 'asn';
                    $staff->save();
                }

                if (!$check) {
                    $user->save();
                }

                $refresh = TaskHelpers::refresh();

                return response()->json([
                'code' => 200,
                'message' => 'Berhasil menyimpan ASN Terkait',
                'data' => $refresh,
        ]);
            } else {
                $staff = AsnTerkait::where(['id_users' => session()->get('user_id'), 'nip_terkait' => $data['nip']])->first();
                if ($staff) {
                    $staff->delete();
                }

                $refresh = TaskHelpers::refresh();

                return response()->json([
                'code' => 200,
                'message' => 'ASN Terkait berhasil dihilangkan.',
                'data' => $refresh,
            ]);
            }
        }
    }

    public function addSpt(Request $request)
    {
        $user = Helpers::getAuthUser(session()->get('user_id'))->nip;
        $status = $request->status;
        $keterangan = $request->keterangan;
        $base = $request->dasar;

        if ($status == true) {
            $status = 1;
        } else {
            $status = 0;
        }

        $dasar = new Dasar();
        $dasar->dasar = $base;
        $dasar->keterangan = $keterangan;
        $dasar->status = $status;
        $dasar->add_by = $user;
        $dasar->save();

        $data = [
            'code' => 200,
            'message' => 'Berhasil menambahkan Dasar SPT',
        ];

        return response()->json($data);
    }

    public function staffIdString($data)
    {
        $newStaffs = [];
        foreach ($data as $s) {
            $staf = [
                'id' => (string) $s['id'],
                'available' => $s['available'],
                'name' => $s['name'],
                'jabatan_id' => $s['jabatan_id'],
                'created_at' => $s['created_at'],
                'updated_at' => $s['updated_at'],
                'foto' => $s['detail']['foto'],
        ];

            array_push($newStaffs, $staf);
        }

        return collect($newStaffs);
    }
}
