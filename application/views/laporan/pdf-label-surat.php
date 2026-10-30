<!DOCTYPE html>
<html>

<head>
    <meta charset='UTF-8'>
    <title>Export <?= $title ?></title>
    <meta content="width=device-width, initial-scale=1, maximum-scale=1, shrink-to-fit=no" name="viewport">

    <link rel="stylesheet" href="<?= base_url() ?>assets/stisla/vendors/fontawesome/css/all.min.css">
    <style>
        * {
            font-family: 'Gill Sans', 'Gill Sans MT', Calibri, 'Trebuchet MS', sans-serif;
        }
        @page {
            margin: 30px;
        }

        body {
            margin: 30px;
        }

    </style>
</head>

<body>

    <?php
    foreach ($data_label as $label) { ?>

        <p style="line-height: 0.5;"><b><?= $label['no_rekening'] ?></b></p>
        <p style="line-height: 0.5;"><?= ucwords($label['nama']) ?></p>
        <p style="line-height: 0.5;"><?= $label['dsn'] . ' ' . $label['ds'] . ' RT/RW ' . $label['rt'] . '/' . $label['rw'] ?></p>
        <p style="line-height: 0.5;"><?= $label['kec'] . ' - ' . $label['kab'] ?></p>
        <p style="line-height: 0.5;"><b>Rp. <?= number_format($label['nisbah_bagi_hasil'], 2, ',', '.'); ?></b></p>

        <div style="page-break-after: always;"></div>
    <?php }
    ?>
</body>

</html>