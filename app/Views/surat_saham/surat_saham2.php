<?php

// Path of the local images to load
$imagePath  = FCPATH . 'assets/stisla/images/iaa.png';
$imagePath2 = FCPATH . 'assets/stisla/images/bg.png';
$imagePath3 = FCPATH . 'assets/stisla/images/qrcode/surat_saham/' . $data_transaksi->no_sertifikat . '.png';

$imageData  = is_file($imagePath) ? file_get_contents($imagePath) : '';
$imageData2 = is_file($imagePath2) ? file_get_contents($imagePath2) : '';
$imageData3 = is_file($imagePath3) ? file_get_contents($imagePath3) : '';

$base64   = 'data:image/png;base64,' . base64_encode($imageData);
$base642  = 'data:image/png;base64,' . base64_encode($imageData2);
$base64qr = 'data:image/png;base64,' . base64_encode($imageData3);

?>
<!DOCTYPE html>
<html lang="en">

<head>
    <title><?= $title ?></title>
    <style>
        @page {
            margin: 0;
        }

        * {
            margin: 0;
            padding: 0;
            box-sizing: border-box;
            font-family: Helvetica, Arial, sans-serif;
        }

        body {
            margin: 0;
            padding: 0;
            color: #111;
        }

        .box {
            width: 771pt;
            height: 395pt;
            position: relative;
            background-image: url('<?= $base642 ?>');
            background-size: 100% 100%;
            background-repeat: no-repeat;
        }

        .sisi-1 {
            position: absolute;
            top: 251pt;
            left: 0;
            width: 241.5pt;
            text-align: center;
        }

        .no-surat {
            font-size: 12pt;
            font-family: Helvetica, Arial, sans-serif;
            color: #111;
        }

        .sisi-2 {
            position: absolute;
            top: 28pt;
            left: 280pt;
            right: 25pt;
            text-align: left;
        }

        .intro-title {
            font-size: 14.5pt;
            font-weight: normal;
            margin-bottom: 16pt;
        }

        .info-table {
            border-collapse: collapse;
            width: 100%;
            margin-bottom: 20pt;
        }

        .info-table td {
            font-size: 14.5pt;
            line-height: 1.25;
            vertical-align: middle;
            padding: 0;
            font-weight: normal;
        }

        .info-table td.label {
            width: 176pt;
        }

        .info-table td.titik2 {
            width: 18pt;
            text-align: center;
        }

        .info-table td.nilai-investasi {
            font-size: 19.5pt;
            line-height: 1.25;
        }

        .ttd-block {
            font-size: 11.5pt;
            line-height: 1.35;
            font-weight: normal;
        }

        .ttd-block img {
            width: 68pt;
            height: 68pt;
            margin: 8pt 0 8pt 0;
            display: block;
        }

        .ttd-nama {
            font-size: 12pt;
            font-weight: normal;
        }
    </style>
</head>

<body>
    <div id="print">
        <div class="box">
            <div class="sisi-1">
                <p class="no-surat">No. <?= $data_transaksi->no_sertifikat ?></p>
            </div>
            <div class="sisi-2">
                <p class="intro-title">Surat Saham ini diberikan kepada:</p>
                <table class="info-table">
                    <tr>
                        <td class="label">No. Anggota</td>
                        <td class="titik2">:</td>
                        <td class="noid"><?= $profiles->no_rekening ?></td>
                    </tr>
                    <tr>
                        <td class="label">NIK</td>
                        <td class="titik2">:</td>
                        <td class="name"><?= $profiles->nik ?></td>
                    </tr>
                    <tr>
                        <td class="label">Nama</td>
                        <td class="titik2">:</td>
                        <td class="name"><?= ucwords($profiles->nama) ?></td>
                    </tr>
                    <tr>
                        <td class="label">Tempat &amp; Tanggal Lahir</td>
                        <td class="titik2">:</td>
                        <td class="ttl"><?= ucwords($profiles->tmp_lahir) . ', ' . date('d-m-Y', strtotime($profiles->tgl_lahir)) ?></td>
                    </tr>
                    <tr>
                        <td class="label">Tanggal Transaksi</td>
                        <td class="titik2">:</td>
                        <td class="date"><?= date('d-m-Y', strtotime($data_transaksi->tgl_transaksi)) ?></td>
                    </tr>
                    <tr>
                        <td class="label">Nilai Investasi</td>
                        <td class="titik2">:</td>
                        <td class="nilai-investasi"><?= 'Rp. ' . number_format((float) $data_transaksi->jml_transaksi, 2, ',', '.'); ?></td>
                    </tr>
                </table>
                <div class="ttd-block">
                    <p><?= 'Sumenep, ' . date('d-m-Y'); ?></p>
                    <p>CV. DHARMA IAA</p>
                    <p>Direktur,</p>
                    <img src="<?= $base64qr ?>" alt="QR Code">
                    <p class="ttd-nama">A. KURDY KHAN</p>
                </div>
            </div>
        </div>
    </div>
</body>

</html>
