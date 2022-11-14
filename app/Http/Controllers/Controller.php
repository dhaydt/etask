<?php

namespace App\Http\Controllers;

use App\Helpers\Helpers;
use App\Models\AsnTerkait;
use App\Models\Dasar;
use App\Models\sptGenerate;
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
use Illuminate\Support\Facades\File;
use Illuminate\Support\Facades\Hash;
use Illuminate\Support\Facades\Storage;

class Controller extends BaseController
{
    use AuthorizesRequests;
    use DispatchesJobs;
    use ValidatesRequests;

    public function generate_sppd($task_id, $staff_id)
    {
        $task = Task::find($task_id);
        $auth = session()->get('user_id');
        $staf = AsnTerkait::where(['nip_terkait' => $staff_id])->first();
        $templateProcessor = new \PhpOffice\PhpWord\TemplateProcessor(public_path('/template/sppdTemplate2.docx'));

        $jarak = strtotime($task['selesai_sppd']) - strtotime($task['mulai_sppd']);

        $hari = $jarak / 60 / 60 / 24;
        $hari = $hari + 1;

        $jabatan = strtolower($staf->nama_jabatan);
        $jabatan = ucwords($jabatan);

        $nip = $staf->nip_terkait;

        if ($jabatan == 'Kontrak') {
            $jabatan = 'Staf';
            $nip = '-';
        }

        if ($staf->type == 'warga') {
            $jabatan = $staf->nama_jabatan;
            $nip = '-';
        }

        $templateProcessor->setValues([
            'nama_pegawai' => $staf->nama,
            'pangkat' => $staf->pangkat,
            'Jabatan' => $jabatan,
            'maksud_sppd' => $task->description,
            'alat_angkut' => $task->kendaraan,
            'tempat_berangkat' => json_decode($task->attribute)->kota_berangkat,
            'tempat_tujuan' => json_decode($task->attribute)->kota_tujuan,
            'lama_perjalanan' => $hari,
            'tgl_berangkat' => Carbon::parse($task['mulai_sppd'])->isoFormat('D MMMM Y'),
            'tgl_kembali' => Carbon::parse($task['selesai_sppd'])->isoFormat('D MMMM Y'),
            'pengikut' => 'belum ada data',
            'instansi_pembebanan_anggaran' => json_decode($task->attribute)->instansi_pembebanan_anggaran,
            'mata_anggaran' => '',
            'berangkat_darikota_tujuan' => Carbon::parse($task['selesai_sppd'])->isoFormat('D MMMM Y'),
            'tiba_dikota_asal' => Carbon::parse($task['selesai_sppd'])->isoFormat('D MMMM Y'),
            'keterangan' => json_decode($task->attribute)->keterangan,
            'dikeluarkan_di' => 'Bukittinggi',
            'nip_pegawai' => $nip,
            'jabatan_tembusan' => json_decode($task->attribute)->pemberi_perintah,
            // 'nomor_naskah' => '094.3 /             /Diskominfo/2022',
            // 'pengirim' => 'Kepala Dinas Komunikasi dan Informatika',
            // 'jabatan_pengirim' => 'KEPALA DINAS KOMUNIKASI DAN',
            // 'jurusan' => 'INFORMATIKA',
            // 'nip_pengirim' => '196311301988031003',
            // 'tanggal_naskah' => Carbon::parse($task['start'])->isoFormat('D MMMM Y'),
            // 'nama_pengirim' => 'Drs.ERWIN UMAR, M.Pd',
        ]);

        $templateProcessor->setImageValue('kop_surat', ['path' => public_path('kop/'.session()->get('id_skpd').'.png'), 'width' => 650, 'height' => 100, 'ratio' => false]);

        $name = 'SPPD-'.now();

        header('Content-Disposition: attachment; filename='.$name.'.docx');

        $templateProcessor->saveAs(public_path('storage/sppd/SPPD.docx'));

        return response()->file(public_path('storage/sppd/SPPD.docx'));
    }

    public function generate_spt($id)
    {
        $task = Task::find($id);
        $staff = json_decode($task->staff);

        foreach ($staff as $s) {
            $spt = new sptGenerate();
            $spt->nip = $s->id;
            $spt->name = $s->nama;

            $findStaf = AsnTerkait::where('nip_terkait', $s->id)->first();

            $jabatan = ucwords(strtolower($findStaf->nama_jabatan));
            if ($jabatan == 'Kontrak') {
                $jabatan = 'Staf';
            }

            if ($findStaf->type == 'warga') {
                $jabatan = $findStaf->nama_jabatan;
            }

            $spt->jabatan = $jabatan;
            $spt->spt_id = $id;
            $spt->save();
        }
        if (count($staff) > 3) {
            $input = public_path('template/SPTTable39.jrxml');
        } else {
            $input = public_path('template/SPT33.jrxml');
        }
        $output = public_path('/storage/spt');

        $dasar = '';
        $spt = json_decode($task->spt_id);
        if (count($spt) > 0) {
            $dasar = $spt[0]->dasar;
        }

        $hari = Carbon::parse($task['mulai'])->isoFormat('dddd');
        $tgl = Carbon::parse($task['mulai'])->isoFormat('D MMMM Y');
        $tempat = json_decode($task['attribute']) ? json_decode($task['attribute'])->kota_tujuan : 'Belum ada data';
        $taskName = $task->description;

        // $jasperstarter = base_path('/vendor/cossou/jasperphp/src/JasperStarter/lib/jasperstarter.jar');
        $jasperstarter = base_path('/vendor/cossou/jasperphp/src/JasperStarter/lib/jasperstarter.jar');

        // $parameter = 'dasar="'.$date.'" spt_id='.$id.' tanggal_naskah="'.$naskah.'" pengirim="'.$pengirim.'" perihal="'.$perihal.'" pemberi_tugas="'.$pemberi_tugas.'"';
        if (count($staff) > 3) {
            $parameter = 'dasar="'.$dasar.'" spt_id='.$id.' task="'.$taskName.'"';
        } else {
            $parameter = 'dasar="'.$dasar.'" spt_id='.$id.' hari="'.$hari.'" tgl="'.$tgl.'" tempat="'.$tempat.'" task="'.$taskName.'"';
        }
        $database = 'mysql -H localhost -u c1_etask -p KhSh_Bx4 -n c1_etask';

        // dd("java -jar $jasperstarter pr $input -o $output -f docx -P $parameter");
        exec("java -jar $jasperstarter pr $input -o $output -f docx -P $parameter -t $database");

        $this->removeTask($id);

        if (count($staff) > 3) {
            return response()->file(public_path('storage/spt/SPTTable39.docx'));
        } else {
            return response()->file(public_path('storage/spt/SPT33.docx'));
        }
    }

    public function removeTask($id)
    {
        $remove = sptGenerate::where('spt_id', $id)->get();
        if (count($remove) > 0) {
            foreach ($remove as $r) {
                $r->delete();
            }
        }
    }

    public function staffList()
    {
        $staff = AsnTerkait::where('id_users', session()->get('user_id'))->get();

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
        $user->id_skpd = $request['id_skpd'];
        $user->created_at = now();
        $user->updated_at = now();

        $user->save();

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
            $refresh = $this->refresh();

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

                $refresh = $this->refresh();

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

                $refresh = $this->refresh();

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
            $data['todo'] = Task::where('status', 'todo')->orderBy('updated_at', 'desc')->get();
            $data['doing'] = Task::where('status', 'doing')->orderBy('updated_at', 'desc')->get();
            $data['done'] = Task::where('status', 'done')->orderBy('updated_at', 'desc')->get();
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
        $data['staffs'] = AsnTerkait::where('id_users', session()->get('user_id'))->get();
        $data['dasar'] = Dasar::orderBy('created_at', 'desc')->get();

        return view('app', $data)->with('success', 'Selamat datang di Aplikasi E-Task');
    }

    public function getTaskMaria($user, $status)
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
            $data['todo'] = Task::where('status', 'todo')->orderBy('updated_at', 'desc')->get();
            $data['doing'] = Task::where('status', 'doing')->orderBy('updated_at', 'desc')->get();
            $data['done'] = Task::where('status', 'done')->orderBy('updated_at', 'desc')->get();
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

        $data['staffs'] = AsnTerkait::where('id_users', session()->get('user_id'))->get();
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
        $attr = [];
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
        $task->attribute = json_encode($attr);
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
        $staff = AsnTerkait::where('nip_terkait', $request->staff)->get();
        if ($request->task == '' || $request->task == 'todo') {
            $this->staffRemove($request->staff, $request->task_id);

            $from = Task::find($request->task_id);

            $data = $this->refresh();

            $this->history('remove_staff', $request->task_id, $from->status);

            return response()->json($data);
        }
        $task = Task::find($request->task);
        $this->staffRemove($request->staff, $request->task_id);

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

    public function staffRemove($id, $task_id)
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
