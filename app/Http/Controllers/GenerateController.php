<?php

namespace App\Http\Controllers;

use App\Helpers\TaskHelpers;
use App\Models\AsnTerkait;
use App\Models\sptGenerate;
use App\Models\Task;
use Carbon\Carbon;

class GenerateController extends Controller
{
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
            $jabatan = 'Kesalahan data jabatan';

            if ($findStaf) {
                $jabatan = ucwords(strtolower($findStaf->nama_jabatan));
                if ($jabatan == 'Kontrak') {
                    $jabatan = 'Staf';
                }
                if ($findStaf->type == 'warga') {
                    $jabatan = $findStaf->nama_jabatan;
                }
            }

            $spt->jabatan = $jabatan;
            $spt->spt_id = $id;
            $spt->save();
        }
        if (count($staff) > 3) {
            $input = public_path('template/SPTTable39.jrxml');
        } else {
            $input = public_path('template/SPT08.jrxml');
        }
        $output = public_path('/storage/spt');

        $dasar = '';
        $spt = json_decode($task->spt_id);
        if (count($spt) > 0) {
            $dasar = $spt[0]->dasar;
        }

        $hari = Carbon::parse($task['start'])->isoFormat('dddd');
        $tgl = Carbon::parse($task['start'])->isoFormat('D MMMM Y');
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

        TaskHelpers::removeTask($id);

        if (count($staff) > 3) {
            return response()->file(public_path('storage/spt/SPTTable39.docx'));
        } else {
            return response()->file(public_path('storage/spt/SPT08.docx'));
        }
    }
}
