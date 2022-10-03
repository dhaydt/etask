<?php

namespace App\Http\Controllers;

use App\Helpers\Helpers;
use App\Models\Staff;
use App\Models\StaffDetail;
use App\Models\Task;
use App\Models\Task_history;
use App\Models\User;
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

    public function addStaff(Request $request)
    {
        $data = $this->getStaff($request->nip);
        if ($data['code'] == 404) {
            return response()->json($data);
        } elseif ($data['code'] == 200) {
            $data = $data['data'];
            $nip = $data->nip;
            $user = User::where('nip', $nip)->first();

            if ($user) {
                $resp = [
                    'code' => 403,
                    'message' => 'User Sudah Tersedia',
                ];

                return response()->json($resp);
            } else {
                $user = new User();
                $user->nip = $nip;
                $user->name = $data->nama;
                $user->password = Hash::make($nip);
                $user->created_at = now();
                $user->updated_at = now();

                $staff = new Staff();
                $staff->id = $nip;
                $staff->name = $data->nama;
                $staff->available = 1;

                $detail = new StaffDetail();
                $detail->id_staff = $nip;
                $detail->nip = $nip;
                $detail->nama = $data->nama;
                $detail->gelar_depan = $data->gelardpn;
                $detail->gelar_belakang = $data->gelarblk;
                $detail->id_jenis_asn = $data->id_jns_asn;
                $detail->tempat_lahir = $data->tmplhr;
                $detail->tanggal_lahir = $data->tgllhr;
                $detail->foto = $data->foto;
                $detail->jenis_kelamin = $data->jenkel;
                $detail->id_agama = $data->id_agama;
                $detail->alamat = $data->alamat;
                $detail->active = $data->active;
                $detail->no_hp = $data->nohp;
                $detail->nik = $data->nik;
                $detail->id_jabatan = $data->id_jab;
                $detail->id_jenis_jabatan = $data->id_jnsjab;
                $detail->id_skpd = $data->id_skpd;

                $user->save();
                $staff->save();
                $detail->save();

                $refresh = $this->refresh();

                return response()->json([
                    'code' => 200,
                    'message' => 'Berhasil menambahkan '.$data->nama,
                    'data' => $refresh,
                ]);
            }

            return response()->json($user);
        } else {
            return response()->json($data);
        }

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
        $this->history('status', $request->id, $request->status);

        $data = $this->refresh();

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

                // dd($resp == 0);
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
        $history->nip = auth()->user()->nip;
        $history->action = $action;
        $history->task_id = $task_id;
        $history->status = $status;
        $history->save();
    }

    public function task()
    {
        // $this->getStaff();
        $user = auth()->user();
        if ($user->role == 1) {
            $data['todo'] = Task::where('status', 'todo')->get();
            $data['doing'] = Task::where('status', 'doing')->get();
            $data['done'] = Task::where('status', 'done')->get();
            $data['staffs'] = Staff::where('available', 1)->get();
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

            $data['staffs'] = Staff::get();
        }

        return view('app', $data);
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
        $request->validate([
            'name' => 'required',
            'description' => 'required',
        ], [
            'name.required' => 'Mohon isi judul Task!',
            'description.required' => 'Mohon isi Deskripsi Task!',
        ]);
        $task = Task::find($request->id);
        $task->name = $request->name;
        $task->description = $request->description;
        $task->updated_at = now();
        $task->save();

        $this->history('update_task', $request->id, $task->status);

        return redirect()->back();
    }

    public function refresh()
    {
        $user = auth()->user();
        if ($user->role == 1) {
            $data['todo'] = Task::where('status', 'todo')->get();
            $data['doing'] = Task::where('status', 'doing')->get();
            $data['done'] = Task::where('status', 'done')->get();
            $data['staffs'] = Staff::where('available', 1)->get();
        } else {
            $data['todo'] = Task::where('status', 'todo')->whereRaw('JSON_CONTAINS(staff->"$[*].id"'.', "'.$user->nip.'")')->get();
            $data['doing'] = Task::where('status', 'doing')->whereRaw('JSON_CONTAINS(staff->"$[*].id"'.', "'.$user->nip.'")')->get();
            $data['done'] = Task::where('status', 'done')->whereRaw('JSON_CONTAINS(staff->"$[*].id"'.', "'.$user->nip.'")')->get();
            $data['staffs'] = Staff::get();
        }

        return response()->json($data);
    }

    public function post(Request $request)
    {
        $staff = [];
        if (auth()->user()->role == 2) {
            $staff = [
                [
                        'id' => auth()->user()->nip,
                        'name' => auth()->user()->name,
                ],
            ];
        }
        $task = new Task();
        $task->nip = auth()->user()->nip;
        $task->name = $request->title;
        $task->status = $request->status;
        $task->staff = json_encode($staff);
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
        if ($request->task == '') {
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
