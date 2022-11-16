<?php

namespace App\Helpers;

use App\Models\AsnTerkait;
use App\Models\Dasar;
use App\Models\sptGenerate;
use App\Models\Task;
use App\Models\Task_history;

class TaskHelpers
{
    public static function getTaskMaria($user, $status)
    {
        $todos = Task::where('status', $status)->orderBy('updated_at', 'desc')->get();
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

    public static function refresh()
    {
        $user = Helpers::getAuthUser(session()->get('user_id'));
        if (!$user) {
            return redirect()->route('login');
        }
        if ($user->role == 1) {
            $data['todo'] = Task::where('status', 'todo')->orderBy('updated_at', 'desc')->get();
            $data['doing'] = Task::where('status', 'doing')->orderBy('updated_at', 'desc')->get();
            $data['done'] = Task::where('status', 'done')->orderBy('updated_at', 'desc')->get();
        } else {
            if (env('APP_ENV') == 'server') {
                $data['todo'] = TaskHelpers::getTaskMaria($user, 'todo');
                $data['doing'] = TaskHelpers::getTaskMaria($user, 'doing');
                $data['done'] = TaskHelpers::getTaskMaria($user, 'done');
            } else {
                $data['todo'] = Task::where('status', 'todo')->whereRaw('UPPER(staff->"$[*].id") LIKE UPPER("%'.$user->nip.'%")')->get();
                $data['doing'] = Task::where('status', 'doing')->whereRaw('UPPER(staff->"$[*].id") LIKE UPPER("%'.$user->nip.'%")')->get();
                $data['done'] = Task::where('status', 'done')->whereRaw('UPPER(staff->"$[*].id") LIKE UPPER("%'.$user->nip.'%")')->get();
            }
        }

        $data['staffs'] = AsnTerkait::where('id_users', session()->get('user_id'))->get();
        $data['dasar'] = Dasar::get();

        return response()->json($data);
    }

    public static function removeTask($id)
    {
        $remove = sptGenerate::where('spt_id', $id)->get();
        if (count($remove) > 0) {
            foreach ($remove as $r) {
                $r->delete();
            }
        }
    }

    public static function history($action, $task_id, $status)
    {
        $history = new Task_history();
        $history->nip = Helpers::getAuthUser(session()->get('user_id'))->nip;
        $history->action = $action;
        $history->task_id = $task_id;
        $history->status = $status;
        $history->save();
    }

    public static function staffRemove($id, $task_id)
    {
        $taskStaff = Task::find($task_id);
        if ($taskStaff) {
            $staffs = json_decode($taskStaff->staff);
            $newStaff = [];
            foreach ($staffs as $st) {
                if ($st->id !== $id) {
                    array_push($newStaff, $st);
                }
            }
            $taskStaff->staff = json_encode($newStaff);
            $taskStaff->save();
        }
    }
}
