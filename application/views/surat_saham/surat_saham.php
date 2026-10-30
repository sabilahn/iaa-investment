<?php
$path = base_url('assets/stisla/images/iaa.png');
$type = pathinfo($path, PATHINFO_EXTENSION);
$data = file_get_contents($path);
$base64 = 'data:image/' . $type . ';base64,' . base64_encode($data);

$path2 = base_url('assets/stisla/images/bg.png');
$type2 = pathinfo($path2, PATHINFO_EXTENSION);
$data2 = file_get_contents($path2);
$base642 = 'data:image/' . $type2 . ';base64,' . base64_encode($data2);

$path3 = base_url('assets/stisla/images/ttd.png');
$type3 = pathinfo($path3, PATHINFO_EXTENSION);
$data3 = file_get_contents($path3);
$base643 = 'data:image/' . $type3 . ';base64,' . base64_encode($data3);

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

        .bg {
            overflow: hidden;
            height: auto;
            position: relative;
        }

        .btn {
            border: none;
            border-radius: 5px;
            color: black;
            background-color: #eeeeef;
            text-align: center;
            font-size: 15px;
            margin: 4px 2px;
            opacity: 0.8;
            transition: 0.3s;
            /* display: inline-block; */
            text-decoration: none;
            cursor: pointer;

            width: 100px;
            height: 30px;
            padding-top: 12px;
            margin-left: 10px;
            margin-top: 530px;
            position: relative;
        }

        .btn:hover {
            opacity: 1
        }

        .box {
            width: 760;
            height: 389;
            position: absolute;
            background-image: url('<?= $base642 ?>');
            background-size: cover;
        }

        .sisi-1 {
            /* position: absolute; */
            text-align: center;
            padding: 55px;
            margin-top: 240px;
        }

        .sisi-2 {
            width: 580px;
            margin-left: 350px;
            height: 360px;
            /* position: absolute;
            display: table; */
            text-align: center;
            padding: 20px;
        }

        .logo-title {
            /* display: table-cell; */
            vertical-align: middle;
            margin: auto;
        }

        .isi {
            /* display: table-cell; */
            vertical-align: middle;
        }

        .text-left {
            text-align: left;
        }

        .title {
            font-size: 20px;
            padding: 5px;
            color: rgb(0, 155, 76);
        }

        .wrap {
            /* display: table-cell; */
            vertical-align: middle;
            margin-left: -695px;
            margin-top: 10px;
            padding: 20px;
        }

        .table-font {
            font-size: 14px;
        }

        .no-surat {
            font-size: 16px;
            font-family: Arial, Helvetica, sans-serif;
        }

        .titik2 {
            width: 30px;
            text-align: center;
        }
    </style>
</head>

<body>
    <div id="print">
        <div class="box">
            <div class="sisi-1">
                <div class="logo-title">
                    <div class="wrap">
                        <p class="no-surat">No. <?= $data_transaksi->no_sertifikat ?></p>
                    </div>
                </div>
            </div>
            <div class="sisi-2">
                <div class="isi text-left"><br>
                    <h3>Surat Saham ini diberikan kepada:</h3>
                    <br>
                    <div style="margin-top: 3px; margin-bottom: 7px;">
                        <table>
                            <?php
                            $dsn = $profiles->dsn;
                            $ds = $profiles->ds;
                            $rt = $profiles->rt;
                            $rw = $profiles->rw;
                            $kec = $profiles->kec;
                            $kab = $profiles->kab;
                            ?>
                            <tr>
                                <td>No. Anggota</td>
                                <td class="titik2">:</td>
                                <td class="noid"><?= $profiles->no_rekening ?></td>
                            </tr>
                            <tr>
                                <td>NIK</td>
                                <td class="titik2">:</td>
                                <td class="name"><?= $profiles->nik ?></td>
                            </tr>
                            <tr>
                                <td>Nama</td>
                                <td class="titik2">:</td>
                                <td class="name"><?= ucwords($profiles->nama) ?></td>
                            </tr>
                            <tr>
                                <td>Tempat & Tanggal Lahir</td>
                                <td class="titik2">:</td>
                                <td class="ttl"><?= ucwords($profiles->tmp_lahir) . ', ' . date('d-m-Y', strtotime($profiles->tgl_lahir))  ?></td>

                            </tr>
                        </table>
                    </div>
                    <div style="margin-top: -8px; margin-bottom: 7px;">
                        <table>
                            <tr>
                                <td>Tanggal Transaksi</td>
                                <td class="titik2" style="padding-left: 50px;">:</td>
                                <td class="date"><?= date('d-m-Y', strtotime($data_transaksi->tgl_transaksi)) ?></td>
                            </tr>
                            <tr>
                                <td>Nilai Investasi</td>
                                <td class="titik2" style="padding-left: 50px;">:</td>
                                <td class="value" style="font-size: 25px;"><b><?= 'Rp. ' . number_format($data_transaksi->jml_transaksi, 2, ',', '.'); ?><b></td>
                            </tr>
                        </table>
                    </div>
                    <div style="margin-top: 30px;">
                        <br>
                        <p><?= 'Sumenep, ' . date('d-m-Y'); ?></p>
                        <p>CV. DHARMA IAA</p>
                        <p><b>Direktur,</b></p>
                        <br>
                        <img src="<?= $base643 ?>" style="width:25%;">
                        <h4>A. KURDY KHAN</h4>
                    </div>
                </div>
            </div>
        </div>
    </div>
</body>

</html>