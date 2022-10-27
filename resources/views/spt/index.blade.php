<!DOCTYPE html>
<html lang="en">

<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <meta http-equiv="X-UA-Compatible" content="ie=edge">
    <title>Generate Surat Tugas</title>

    @include('template.header')
    <style>
        .clearfix:after {
            content: "";
            display: table;
            clear: both;
        }

        a {
            color: #0087c3;
            text-decoration: none;
        }

        body {
            position: relative;
            width: 21cm;
            height: 29.7cm;
            align-items: center;
            margin: 0 auto;
            color: #000;
            background: #ffffff;
            font-family: Arial, sans-serif;
            font-size: 14px;
            font-family: SourceSansPro;
        }

        header {
            padding: 10px 0;
            margin-bottom: 20px;
            border-bottom: 4px solid #000;
            width: 17cm
        }

        .logo-sumbar {
            height: 75px;
        }

        .kop-surat {
            padding-top: 15px;
            width: 100%;
            text-align: center;
        }

        .kop-surat h4 {
            font-size: 22px !important;
            font-weight: 900;
        }

        .kop-surat h5 {
            font-size: 18px;
            font-weight: 700;
        }

        span.title {
            font-size: 18px;
            font-weight: 600;
            text-decoration: underline;
        }

        .fs-15 {
            font-size: 15px;
        }

        .row .name {
            font-weight: 700
        }

        .row .nip {
            color: grey
        }

        table {
            width: 80%;
            border-collapse: collapse;
            border-spacing: 0;
            margin-top: 40px;
            margin-bottom: 20px;
        }

        table th,
        table td {
            padding: 10px;
            background: #ffffff;
            text-align: center;
            border-top: 1px solid #000 !important;
            border-left: 1px solid #000 !important;
        }

        thead tr{
            border-right: 1px solid #000;
        }

        table th {
            white-space: nowrap;
            font-weight: 900;
            text-align: center;
        }

        table td {
            text-align: center;
        }

        table td h3 {
            color: #57b223;
            font-size: 14px;
            font-weight: normal;
            margin: 0 0 0.2em 0;
        }

        table .no {
            color: #000;
            font-size: 15px;
            background: #fff;
        }

        table th.no {
            font-weight: 900;
        }

        table .desc {
            text-align: center;
            font-weight: 900;
        }

        table th.desc {
            font-weight: 900;
        }

        table th.total {
            font-weight: 900;
        }

        table .total {
            background: #fff;
            color: #000;
        }

        table td.unit,
        table td.qty,
        table td.total {
            font-size: 1.2em;
        }

        table tbody tr:last-child td {
            border: 1px solid #000 !important;
        }

        .table tbody tr:last-child td {
            border-bottom: 1px solid #000 !important;
        }

        table tfoot td {
            padding: 10px 20px;
            background: #ffffff;
            border-bottom: none;
            font-size: 1.2em;
            white-space: nowrap;
            border-top: 1px solid #aaaaaa;
        }

        table tfoot tr:first-child td {
            border-top: none;
        }

        table tfoot tr:last-child td {
            color: #57b223;
            font-size: 1.4em;
            border-top: 1px solid #57b223;
        }

        table tfoot tr td:first-child {
            border: none;
        }

    </style>
</head>

<body>
    <header class="clearfix">
        <div class="container d-flex justify-content-between align-items-center">
            <img src="{{ asset('img/logo_footer.png') }}" class="logo-sumbar" alt="">
            <div class="kop-surat d-flex flex-column">
                <h5>PEMERINTAH KOTA BUKITTINGGI</h5>
                <h4>DINAS KOMUNIKASI DAN INFORMATIKA</h4>

                <span class="mt-2">Jalan Kusuma Bhakti, Bukit Gulai Bancah, Bukittinggi Telepon (0752) 33369,
                    21879</span>
                <span>Fax. (0752) 32767 Website : bukittinggikota.go.id Kode Pos : 26122</span>
            </div>
        </div>
    </header>
    <div class="row text-center">
        <span class="title">Surat Tugas</span>
        <span class="fs-4">Nomor : 802/ /Diskominfo.03/2022</span>
    </div>
    <div class="row mt-4">
        <div class="col-2 d-flex justify-content-end">
            <span class="fs-14">
                Dasar :
            </span>
        </div>
        <div class="col-10 fs-15" style="padding-right: 70px;">
            Surat dari Kementerian Komunikasi dan Informatika RI Nomor B-757/DJAI/AI.01.10/10/2022, Tanggal 10 Oktober
            2022, Perihal Undangan Sosialisasi.
            Kepala Dinas Komunikasi dan Informatika Kota Bukittinggi dengan ini, memerintahkan kepada nama-nama yang
            tersebut dibawah ini:
        </div>
        @php
        $staffs = json_decode($task->staff);
        @endphp
        <div class="conainer px-4">
            <div class="row justify-content-center px-4">
                <div class="table d-flex justify-content-center">
                    <table cellspacing="0" cellpadding="0">
                        <thead>
                            <tr>
                                <th class="no">NO.</th>
                                <th class="desc">NAMA / NIP</th>
                                <th class="total">JABATAN</th>
                            </tr>
                        </thead>
                        <tbody>
                            @foreach ($staffs as $s)
                            <tr>
                                <td class="no">Nomor</td>
                                <td class="desc">
                                    <div class="row">
                                        <span class="name">{{ $s->nama }}</span>
                                        <span class="nip">{{ $s->id }}</span>
                                    </div>
                                </td>
                                <td class="total">Jabatan</td>
                            </tr>
                            @endforeach
                        </tbody>
                    </table>
                </div>
            </div>
        </div>
    </div>

    @include('template.script')
</body>

</html>
