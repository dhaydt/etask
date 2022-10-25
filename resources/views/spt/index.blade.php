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
            text-align: center;
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
        .kop-surat{
            padding-top: 15px;
            width: 100%;
            text-align: center;
        }
        .kop-surat h4{
            font-size: 22px !important;
            font-weight: 900;
        }
        .kop-surat h5{
            font-size: 18px;
            font-weight: 700;
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

    @include('template.script')
</body>

</html>
