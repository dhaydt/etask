<?php

namespace App\Http\Controllers;

use App\Helpers\Helpers;
use App\Helpers\TaskHelpers;
use App\Models\AsnTerkait;
use App\Models\Dasar;
use App\Models\Task;
use App\Models\User;
use Carbon\Carbon;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\File;
use Illuminate\Support\Facades\Storage;

class TaskController extends Controller
{
    public function index()
    {
        $user = User::where('nip', session()->get('nip'))->first();

        if ($user['role'] == 1) {
            $data['todo'] = Task::where('status', 'todo')->orderBy('updated_at', 'desc')->get();
            $data['doing'] = Task::where('status', 'doing')->orderBy('updated_at', 'desc')->get();
            $data['done'] = Task::where('status', 'done')->orderBy('updated_at', 'desc')->get();
        } else {
            if (env('APP_ENV') == 'server') {
                $data['todo'] = json_encode(TaskHelpers::getTaskMaria($user, 'todo'));
                $data['doing'] = json_encode(TaskHelpers::getTaskMaria($user, 'doing'));
                $data['done'] = json_encode(TaskHelpers::getTaskMaria($user, 'done'));
            } else {
                // $data['todo'] = Task::where('status', 'todo')->whereRaw('JSON_CONTAINS(staff->"$[*].id"'.', "'.$user->nip.'")')->get();
                $data['todo'] = Task::where('status', 'todo')->whereRaw('UPPER(staff->"$[*].id") LIKE UPPER("%'.$user->nip.'%")')->get();
                $data['doing'] = Task::where('status', 'doing')->whereRaw('UPPER(staff->"$[*].id") LIKE UPPER("%'.$user->nip.'%")')->get();
                $data['done'] = Task::where('status', 'done')->whereRaw('UPPER(staff->"$[*].id") LIKE UPPER("%'.$user->nip.'%")')->get();
            }
        }
        $data['staffs'] = AsnTerkait::where('id_users', session()->get('user_id'))->get();
        $data['dasar'] = Dasar::orderBy('created_at', 'desc')->get();

        return view('app', $data)->with('success', 'Selamat datang di Aplikasi E-Task');
    }

    public function addTask(Request $request)
    {
        $staff = [];
        $spt = [];
        $report = [];
        $attr = [];
        $user = Helpers::getAuthUser(session()->get('user_id'));
        $asn_terkait = $user->asn;
        $foto = '';
        if (count($asn_terkait) > 0) {
            $foto = $asn_terkait[0]->foto;
        }
        if (session()->get('role_id') == 2) {
            $staff = [
                [
                        'id' => $user->nip,
                        'nama' => $user->name,
                        'foto' => $foto,
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
        $task->attribute = json_encode($attr);
        $task->save();

        TaskHelpers::history('add', $task->id, 'todo');

        $data = TaskHelpers::refresh();

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
        $newTask = TaskHelpers::refresh();

        TaskHelpers::history('status', $request->id, $request->status);

        return response()->json($newTask);
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
        $newStaff = json_decode($request->staf, true);

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

        if ($task->status == 'doing' || $task->status == 'todo') {
            $task->name = $request->name;
            $task->description = $request->description;
            $task->updated_at = now();
            $task->spt_id = json_decode($request->dasar, true);
            $task->start = $request->start;
            $task->staff = $staffOld;
            $task->kendaraan = $request->kendaraan;
            $task->tipe_dinas = $request->tipe_dinas;
            if ($request->tipe_dinas == 'SPPD') {
                $task->mulai_sppd = Carbon::parse($request->mulai_sppd);
                $task->selesai_sppd = Carbon::parse($request->selesai_sppd);
                $data = [
                    'pemberi_perintah' => $request->pemberi_perintah,
                    'kota_berangkat' => $request->kota_berangkat,
                    'kota_tujuan' => $request->kota_tujuan,
                    'instansi_pembebanan_anggaran' => $request->instansi_pembebanan_anggaran,
                    'keterangan' => $request->keterangan,
                ];
                $task->attribute = json_encode($data);
            }
        }

        if ($task->status == 'doing') {
            $file = $request->file('file');
            $task->start_do = Carbon::parse($request->start_on)->addHours(7)->format('Y-m-d H:i:s');
            $task->finish_do = Carbon::parse($request->finish_on)->addHours(7)->format('Y-m-d H:i:s');

            $dir = 'report';
            if ($file) {
                $imgLogo = Helpers::upload($dir, '.png', $file);

                $old_image = $task['report'];

                if ($task->report !== null) {
                    if (File::exists(public_path($old_image))) {
                        unlink(public_path($old_image));
                    }
                }

                if ($file !== null) {
                    $imageName = Carbon::now()->toDateString().'-'.uniqid().'.'.'.png';

                    if (!Storage::disk('public')->exists($dir)) {
                        Storage::disk('public')->makeDirectory($dir);
                    }
                    $url = $file->store('storage/'.$dir);
                } else {
                    $url = null;
                }
                $task->report = $url;
            }
        }

        if ($task->status == 'done') {
            $file = $request->file('file');

            $dir = 'report';
            if ($file) {
                $logo = $task->report;
                $imgLogo = Helpers::update($dir, $logo, 'png', $file);

                $old_image = $task['report'];

                if ($task->report !== null) {
                    if (File::exists(public_path($old_image))) {
                        unlink(public_path($old_image));
                    }
                }

                if ($file !== null) {
                    $imageName = Carbon::now()->toDateString().'-'.uniqid().'.'.'.png';

                    if (!Storage::disk('public')->exists($dir)) {
                        Storage::disk('public')->makeDirectory($dir);
                    }
                    $url = $file->store('storage/'.$dir);
                } else {
                    $url = null;
                }
                $task->report = $url;
            }
        }
        $task->save();
        if ($task->status !== 'done') {
            foreach ($task->staff as $s) {
                Helpers::checkAvailable($s['id']);
            }
        }

        TaskHelpers::history('update_task', $request->id, $task->status);
        $data = TaskHelpers::refresh();

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

        $data = TaskHelpers::refresh();

        TaskHelpers::history('status', $request->id, $request->status);

        return response()->json($data);
    }

    public function updateStaff(Request $request)
    {
        $staff = AsnTerkait::where('nip_terkait', $request->staff)->get();
        if ($request->task == '' || $request->task == 'todo') {
            TaskHelpers::staffRemove($request->staff, $request->task_id);

            $from = Task::find($request->task_id);

            $data = TaskHelpers::refresh();

            TaskHelpers::history('remove_staff', $request->task_id, $from->status);

            return response()->json($data);
        }
        $task = Task::find($request->task);
        TaskHelpers::staffRemove($request->staff, $request->task_id);

        $staffTask = json_decode($task->staff);

        $staffExtarck = ['id' => $staff['id'], 'name' => $staff['name']];
        array_push($staffTask, $staffExtarck);
        $task->staff = $staffTask;

        $task->save();

        Helpers::checkAvailable($request->staff);

        TaskHelpers::history('move_staff', $task->id, $task->status);
        $data = TaskHelpers::refresh();

        return response()->json($data);
    }

    public function deleteTask(Request $request)
    {
        $task = Task::find($request->task_id);
        $task->delete();
        TaskHelpers::history('delete', $request->task_id, 'deleted');
        $data = TaskHelpers::refresh();

        return response()->json($data);
    }
}
