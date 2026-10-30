<?php

// URL of the image you want to load
$imageUrl = base_url('assets/stisla/images/iaa.png');
$imageUrl2 = base_url('assets/stisla/images/qrcode/penarikan/' . $data_penarikan->id_penarikan . '.png');

// Initialize cURL session
$ch = curl_init($imageUrl);
$ch2 = curl_init($imageUrl2);

// Set cURL options
curl_setopt($ch, CURLOPT_RETURNTRANSFER, true);
curl_setopt($ch2, CURLOPT_RETURNTRANSFER, true);

// Execute cURL session and fetch the image data
$imageData = curl_exec($ch);
$imageData2 = curl_exec($ch2);

$base64Image = base64_encode($imageData);
$base64 = 'data:image/png'. ';base64,' . $base64Image;

$base64Image2 = base64_encode($imageData2);
$base64qr = 'data:image/png'. ';base64,' . $base64Image2;

?>

<!DOCTYPE html>
<html lang="en">

<head>
    <title><?= $title ?></title>
    <link rel="stylesheet" href="<?= base_url() ?>assets/stisla/vendors/fontawesome/css/all.min.css">
    <style>
        * {
            margin: 0;
            padding: 0;
            top: 5px;
            left: 5px;
            right: 5px;
            bottom: 5px;
            font-family: 'Gill Sans', 'Gill Sans MT', Calibri, 'Trebuchet MS', sans-serif;
        }

        td {
            font-family: 'Gill Sans', 'Gill Sans MT', Calibri, 'Trebuchet MS', sans-serif;
            font-size: 20px;
            height: 25px;
        }

        .font-16 {
            font-family: 'Gill Sans', 'Gill Sans MT', Calibri, 'Trebuchet MS', sans-serif;
            font-size: 16px;
            height: 25px;
        }

        .text-right {
            text-align: right;
        }

        .text-center {
            text-align: center;
        }
    </style>
</head>

<body>

    <table width=100%;>
        <tr>
            <td style="text-align: center;">
                <img src="<?= $base64 ?>" alt="" style="width: 100px; height:100px; margin-top:114px;">
            </td>
        </tr>
        <tr>
            <td style="text-align: center;">
                <h4 style="margin-top:20px;">CV. DHARMA IAA</h4>
                <h4>SUMENEP - JAWA TIMUR</h4>
                <h5 style="margin-top:40px;">SURAT KETERANGAN PENARIKAN DANA INVESTASI</h5>
            </td>
        </tr>
    </table>

        <p style="margin-left: 114px; margin-top:30px;">Yang bertanda tangan di bawah ini saya :</p>
        <table style="margin-left: 151px;" width=100%;>
            <tr>
                <td class="font-16" style="width: 20%;">Nama</td>
                <td class="font-16" style="width: 5%;">:</td>
                <td class="font-16"><?= ucwords($data_penarikan->nama) ?></td>
            </tr>
            <tr>
                <td class="font-16">No. Anggota</td>
                <td class="font-16">:</td>
                <td class="font-16"><?= $data_penarikan->no_rekening ?></td>
            </tr>
            <tr>
                <td class="font-16">NIK</td>
                <td class="font-16">:</td>
                <td class="font-16"><?= $data_penarikan->nik ?></td>
            </tr>
            <tr>
                <td class="font-16">Alamat</td>
                <td class="font-16">:</td>
                <td class="font-16"><?= 'Ds. ' . $data_penarikan->ds . " RT/RW. " . $data_penarikan->rt . '/' . $data_penarikan->rw ?></td>
            </tr>
            <tr>
                <td class="font-16"></td>
                <td class="font-16"></td>
                <td class="font-16"><?= 'Kec. ' . $data_penarikan->kec . " Kab. " . $data_penarikan->kab ?></td>
            </tr>
        </table>
        <p style="margin-left: 114px; margin-right: 114px; margin-top: 3px; text-align: justify;">dengan ini mengakui telah melakukan penarikan dana investasi di CV. Dharma</p>
        <p style="margin-left: 114px; margin-right: 114px; margin-top: 3px; text-align: justify;">IAA Sumenep Jawa Timur sebesar Rp. <?= number_format($data_penarikan->jml_penarikan, 2, ',', '.'); ?></p>
        <p style="margin-left: 114px; margin-right: 114px; margin-top: 20px; text-align: justify;">Demikian keterangan ini saya buat dengan sadar tanpa ada unsur paksaan dari pihak manapun.</p>

        <table style="margin-left: 114px; margin-right: 114px; margin-top: 20px;" width=100%;>
            <tr>
                <td></td>
                <td class="font-16 text-right">Sumenep, <?= date('d-m-Y') ?></td>
            </tr>
            <tr>
                <td class="font-16 text-center" style="width: 50%; padding-top:15px;">Direktur CV. Dharma IAA,</td>
                <td class="font-16 text-center" style="width: 50%; padding-top:15px;">Saya,</td>
            </tr>
            <tr>
                <td style="height:50px; text-align:center;"><img src="<?= $base64qr ?>" style="width:15%; margin-top: 5px;"></td>
                <td style="text-align:center;"><img src="<?= $base64qr ?>" style="width:15%; margin-top: 5px;"></td>
            </tr>
            <tr>
                <td class="font-16 text-center">A. Kurdy Khan</td>
                <td class="font-16 text-center"><?= ucwords($data_penarikan->nama) ?></td>
            </tr>
        </table>
</body>

</html>