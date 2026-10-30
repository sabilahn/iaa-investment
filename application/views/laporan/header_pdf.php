<!DOCTYPE html>
<html>

<head>
    <meta charset='UTF-8'>
    <title>Export <?= $title ?></title>
    <meta content="width=device-width, initial-scale=1, maximum-scale=1, shrink-to-fit=no" name="viewport">

    <link rel="stylesheet" href="<?= base_url() ?>assets/stisla/vendors/fontawesome/css/all.min.css">
    <style>
        * {
            margin: 0;
            padding: 0;
            font-family: helvetica;
        }

        body {
            margin: 50px;
        }
        
        #page-wrap {
            margin: 50px;
        }

        p {
            margin: 20px 0;
        }

        h1 {
            text-align: center;
        }

        h2 {
            text-align: center;
        }

        h3 {
            text-align: left;
        }

        /*
	Generic Styling, for Desktops/Laptops
	*/
        table {
            width: 100%;
            border-collapse: collapse;
        }

        td.no-border {
            border: 0;
        }

        /* Zebra striping */
        tr:nth-of-type(odd) {
            background: #eee;
        }

        th {
            background: #333;
            color: white;
            font-weight: bold;
            padding: 6px;
            border: 1px solid #ccc;
            text-align: center;

        }

        td {
            padding: 6px;
            border: 1px solid #ccc;
            /*text-align: left; 	*/
        }

        tr.putih {
            background: #fff;
        }

        tr.biru {
            background: #05f0fc;
            font-weight: bold;
        }

        .kiri {
            text-align: left;
        }

        .tengah {
            text-align: center;
        }

        .kanan {
            text-align: right;
        }

        .prg {
            text-align: left;
            font-weight: bold;
        }

        .black {
            background: #000;
        }

        .red {
            color: red;
        }

        @page {
            margin: 50px;
        }

        .no-background {
            background: white;
            border: white;
        }
    </style>
</head>