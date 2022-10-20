<?php

namespace App\Http\Controllers;

use App\Helpers\Helpers;
use App\Models\AsnTerkait;
use App\Models\Dasar;
use App\Models\Staff;
use App\Models\StaffDetail;
use App\Models\Task;
use App\Models\Task_history;
use App\Models\User;
use Carbon\Carbon;
use GuzzleHttp\Client;
use GuzzleHttp\Exception\ClientException;
use Illuminate\Foundation\Auth\Access\AuthorizesRequests;
use Illuminate\Foundation\Bus\DispatchesJobs;
use Illuminate\Foundation\Validation\ValidatesRequests;
use Illuminate\Http\Request;
use Illuminate\Routing\Controller as BaseController;
use Illuminate\Support\Facades\Hash;

class Controller extends BaseController
{
    use AuthorizesRequests;
    use DispatchesJobs;
    use ValidatesRequests;

    public function staffList()
    {
        $staff = AsnTerkait::where('id_skpd', session()->get('id_skpd'))->get();

        return response()->json($staff);
    }

    public function reg(Request $request)
    {
        $user = new User();
        $name = $request['gelarDpn'].$request['nama_peg'].$request['gelarBlk'];
        $user->nip = $request['nip'];
        $user->name = $name;
        $user->password = Hash::make($request['password']);
        if ($request['nip'] == 1372010910920041 || $request['nip'] == 198611252010011009) {
            $user->role = 1;
        } else {
            $user->role = 2;
        }
        $user->created_at = now();
        $user->updated_at = now();

        $staff = new Staff();
        $staff->id = $request['nip'];
        $staff->name = $name;
        $staff->available = 1;

        $detail = new StaffDetail();
        $detail->id_staff = $request['nip'];
        $detail->nip = $request['nip'];
        $detail->nama = $name;
        $detail->gelar_depan = $request['gelarDpn'];
        $detail->gelar_belakang = $request['gelarBlk'];
        $detail->id_jenis_asn = null;
        $detail->tempat_lahir = null;
        $detail->tanggal_lahir = null;
        $detail->foto = $request['foto'];
        $detail->jenis_kelamin = null;
        $detail->id_agama = $request['id_agama'];
        $detail->alamat = null;
        $detail->active = $request['active'];
        $detail->no_hp = $request['no_hp'];
        $detail->nik = $request['nik'];
        $detail->nama_jabatan = 'Belum Ada Respon Api';
        $detail->id_sotk = null;
        $detail->id_skpd = $request['id_skpd'];

        $user->save();
        $staff->save();
        $detail->save();

        return redirect()->back()->with('success', 'Akun E-Task anda berhasil didaftarkan! Silhakna login menggunakan NIP dan password yang didaftarkan');
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
            $data = $this->getStaff($id);
            $response = [
                'code' => 404,
                'message' => 'NIP tidak terdaftar, tapi tersedia di data kepegawaian kami. Silahkan daftarkan akun untuk E-Task anda!',
                'data' => $data,
                'nip' => $id,
            ];
        }

        return $response;
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
                    $user = AsnTerkait::where('id_skpd', session()->get('id_skpd'))->get();
                    $dataMentah = json_decode($response->getBody())->data;
                    $dataSkpd = [];
                    foreach ($dataMentah as $dm) {
                        if ($dm->id_skpd == $request->id_skpd) {
                            array_push($dataSkpd, $dm);
                        }
                    }
                    $data = [
                        'code' => 200,
                        'data' => $dataSkpd,
                        'user' => $user,
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

            $data = $this->refresh();

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

        $data = $request['user'];
        if ($request['status'] == false) {
            $check = User::where('nip', $data['nip'])->first();
            if (!$check) {
                $user = new User();
                $user->nip = $data['nip'];
                $user->name = $data['nama_pegawai'];
                $user->password = Hash::make($data['nip']);
                if ($data['nip'] == 1372010910920041 || $data['nip'] == 198611252010011009) {
                    $user->role = 1;
                } else {
                    $user->role = 2;
                }
                $user->id_skpd = $data['id_skpd'];
                $user->created_at = now();
                $user->updated_at = now();
            }

            $checkStaff = AsnTerkait::where(['id_users' => $auth, 'nip_terkait' => $data['nip']])->get();
            if (count($checkStaff) > 0) {
                foreach ($checkStaff as $c) {
                    $c->delete();
                }
            }

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
            $staff->available = 1;

            if (!$check) {
                $user->save();
            }
            $staff->save();

            $refresh = $this->refresh();

            return response()->json([
                'code' => 200,
                'message' => 'Berhasil menyimpan ASN Terkait',
                'data' => $refresh,
        ]);
        } else {
            $staff = AsnTerkait::where('nip_terkait', $data['nip'])->first();
            if ($staff) {
                $staff->delete();
            }

            $refresh = $this->refresh();

            return response()->json([
                'code' => 200,
                'message' => 'ASN Terkait berhasil dihilangkan.',
                'data' => $refresh,
            ]);
        }
    }

    public function addSpt(Request $request)
    {
        $user = Helpers::getUserDetail(session()->get('nip'))->user->nip;
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

    public function deleteTask(Request $request)
    {
        $task = Task::find($request->task_id);
        $task->delete();
        $this->history('delete', $request->task_id, 'deleted');
        $data = $this->refresh();

        return response()->json($data);
    }

    public function taskStatus(Request $request)
    {
        $task = Task::find($request->id);

        $task->status = $request->status;
        $task->save();
        $staffList = json_decode($task->staff);

        foreach ($staffList as $s) {
            Helpers::checkAvailable($s->id);
        }

        $data = $this->refresh();

        $this->history('status', $request->id, $request->status);

        return response()->json($data);
    }

    public function getStaff($nip)
    {
        $token = Helpers::getToken();
        $key = $token;

        $main_api = 'https://apidoc.bukittinggikota.go.id/simpeg/public/api';

        $api = $main_api.'/data_pegawai';

        $header = [
            'Authorization' => 'Bearer'.$key,
        ];

        try {
            $client = new Client();

            $response = $client->request('POST', $api, [
                'headers' => $header,
                'form_params' => ['nip' => $nip],
            ]);

            $status = $response->getStatusCode();

            session()->put('token', $key);

            if ($status == 200) {
                $resp = json_decode($response->getBody()->getContents())->api_status;

                if ($resp == 0) {
                    $data = [
                        'code' => 404,
                        'message' => 'Staff tidak ditemukan',
                    ];

                    return $data;
                } else {
                    $data = [
                        'code' => 200,
                        'data' => json_decode($response->getBody())->data,
                    ];

                    return $data;
                }
            }
        } catch (ClientException $e) {
            $this->getStaff($nip);
        }
    }

    public function history($action, $task_id, $status)
    {
        $history = new Task_history();
        $history->nip = Helpers::getAuthUser(session()->get('user_id'))->nip;
        $history->action = $action;
        $history->task_id = $task_id;
        $history->status = $status;
        $history->save();
    }

    public function task()
    {
        // $this->getStaff();
        $user = User::where('nip', session()->get('nip'))->first();

        if ($user['role'] == 1) {
            $data['todo'] = Task::where('status', 'todo')->get();
            $data['doing'] = Task::where('status', 'doing')->get();
            $data['done'] = Task::where('status', 'done')->get();
        } else {
            if (env('APP_ENV') == 'server') {
                $data['todo'] = json_encode($this->getTaskMaria($user, 'todo'));
                $data['doing'] = json_encode($this->getTaskMaria($user, 'doing'));
                $data['done'] = json_encode($this->getTaskMaria($user, 'done'));
            } else {
                $data['todo'] = Task::where('status', 'todo')->whereRaw('JSON_CONTAINS(staff->"$[*].id"'.', "'.$user->nip.'")')->get();
                $data['doing'] = Task::where('status', 'doing')->whereRaw('JSON_CONTAINS(staff->"$[*].id"'.', "'.$user->nip.'")')->get();
                $data['done'] = Task::where('status', 'done')->whereRaw('JSON_CONTAINS(staff->"$[*].id"'.', "'.$user->nip.'")')->get();
            }
        }
        $data['staffs'] = AsnTerkait::where('id_skpd', session()->get('id_skpd'))->get();
        $data['dasar'] = Dasar::orderBy('created_at', 'desc')->get();

        return view('app', $data)->with('success', 'Selamat datang di Aplikasi E-Task');
    }

    public function getTaskMaria($user, $status)
    {
        $todos = Task::where('status', $status)->get();
        $todoId = [];

        if (count($todos) > 0) {
            foreach ($todos as $to) {
                $staffs = json_decode($to['staff']);
                if (count($staffs) > 0) {
                    foreach ($staffs as $s) {
                        if ($s->id == $user->nip) {
                            array_push($todoId, $to);
                        }
                    }
                }
            }
        }

        return $todoId;
    }

    public function updateTask(Request $request)
    {
        // dd($request);
        $request->validate([
            'name' => 'required',
            'description' => 'required',
        ], [
            'name.required' => 'Mohon isi judul Task!',
            'description.required' => 'Mohon isi Deskripsi Task!',
        ]);
        $newStaff = $request->staf;

        $task = Task::find($request->id);
        $staffOld = [];

        foreach ($newStaff as $s) {
            $st = [
                'id' => $s['id'],
                'nama' => $s['nama'],
                'foto' => $s['foto'],
            ];
            array_push($staffOld, $st);
        }

        $task->name = $request->name;
        $task->description = $request->description;
        $task->updated_at = now();
        $task->spt_id = $request->dasar;
        $task->start = $request->start;
        $task->staff = $staffOld;

        if ($task->status == 'doing') {
            $task->start_do = Carbon::parse($request->start_on)->addHours(7)->format('Y-m-d H:i:s');
            $task->finish_do = Carbon::parse($request->finish_on)->addHours(7)->format('Y-m-d H:i:s');
        }
        $task->save();

        foreach ($task->staff as $s) {
            Helpers::checkAvailable($s['id']);
        }

        $this->history('update_task', $request->id, $task->status);
        $data = $this->refresh();

        return response()->json($data);
    }

    public function refresh()
    {
        $user = Helpers::getAuthUser(session()->get('user_id'));
        if (!$user) {
            return redirect()->route('login');
        }
        if ($user->role == 1) {
            $data['todo'] = Task::where('status', 'todo')->get();
            $data['doing'] = Task::where('status', 'doing')->get();
            $data['done'] = Task::where('status', 'done')->get();
        } else {
            if (env('APP_ENV') == 'server') {
                $data['todo'] = $this->getTaskMaria($user, 'todo');
                $data['doing'] = $this->getTaskMaria($user, 'doing');
                $data['done'] = $this->getTaskMaria($user, 'done');
            } else {
                $data['todo'] = Task::where('status', 'todo')->whereRaw('JSON_CONTAINS(staff->"$[*].id"'.', "'.$user->nip.'")')->get();
                $data['doing'] = Task::where('status', 'doing')->whereRaw('JSON_CONTAINS(staff->"$[*].id"'.', "'.$user->nip.'")')->get();
                $data['done'] = Task::where('status', 'done')->whereRaw('JSON_CONTAINS(staff->"$[*].id"'.', "'.$user->nip.'")')->get();
            }
        }

        $data['staffs'] = AsnTerkait::where('id_skpd', session()->get('id_skpd'))->get();
        $data['dasar'] = Dasar::get();

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

    public function post(Request $request)
    {
        $staff = [];
        $spt = [];
        $report = [];
        $user = Helpers::getAuthUser(session()->get('user_id'));
        if ($user->nip == 2) {
            $staff = [
                [
                        'id' => $user->nip,
                        'name' => $user->user->name,
                ],
            ];
        }
        $task = new Task();
        $task->nip = $user->nip;
        $task->name = $request->title;
        $task->status = $request->status;
        $task->staff = json_encode($staff);
        $task->spt_id = json_encode($spt);
        $task->report = json_encode($report);
        $task->save();

        $this->history('add', $task->id, 'todo');

        $data = $this->refresh();

        return response()->json($data);
    }

    public function status(Request $request)
    {
        $task = Task::find($request->id);
        $task->status = $request->status;
        $task->save();

        $staffList = json_decode($task->staff);

        foreach ($staffList as $s) {
            Helpers::checkAvailable($s->id);
        }
        $newTask = $this->refresh();

        $this->history('status', $request->id, $request->status);

        return response()->json($newTask);
    }

    public function updateStaff(Request $request)
    {
        $staff = Staff::find($request->staff);
        if ($request->task == '' || $request->task == 'todo') {
            $this->staffRemove($request->staff);
            $staff->available = 1;
            $staff->save();
            $from = Task::find($request->task_id);

            $data = $this->refresh();

            $this->history('remove_staff', $request->task_id, $from->status);

            return response()->json($data);
        }
        $task = Task::find($request->task);
        $this->staffRemove($request->staff);

        $staffTask = json_decode($task->staff);

        $staffExtarck = ['id' => $staff['id'], 'name' => $staff['name']];
        array_push($staffTask, $staffExtarck);
        $task->staff = $staffTask;

        $task->save();

        Helpers::checkAvailable($request->staff);

        $this->history('move_staff', $task->id, $task->status);
        $data = $this->refresh();

        return response()->json($data);
    }

    public function staffRemove($id)
    {
        $taskStaff = Task::get();
        foreach ($taskStaff as $ts) {
            $staffs = json_decode($ts->staff);
            $newStaff = [];
            foreach ($staffs as $st) {
                if ($st->id !== $id) {
                    array_push($newStaff, $st);
                }
            }
            $ts->staff = json_encode($newStaff);
            $ts->save();
        }
    }
}
