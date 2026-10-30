<?php
require_once('header_pdf.php');
?>

<body>
    <table width=100%; style="padding-bottom: 20px;">
        <tr>
            <td style="text-align: center;" class="no-background">
                <h2>TRANSAKSI INVESTASI TAHUN BUKU <?= date('Y') ?> <br> CV. DHARMA IAA S/D <?= date('d') . ' ' . $nama_bulan . ' ' . date('Y') ?></h2>
            </td>
        </tr>
    </table>
    <table>
        <thead>
            <tr class="text-center">
                <th>No.</th>
                <th>Nomor Rekening</th>
                <th>Nama</th>
                <th>Nomor Sertifikat</th>
                <th>Tanggal Transaksi</th>
                <th>Jumlah Transaksi (Rp)</th>
            </tr>
        </thead>
        <tbody>
            <?php
            $no = 0;
            $total_transaksi = 0;
            foreach ($data_trans_terkini as $trans_terkini) {
                $no += 1;
                $total_transaksi += (float) $trans_terkini['jml_transaksi'];
            ?>
                <tr>
                    <td style="text-align: center"><?= $no ?></td>
                    <td style="text-align: center"><?= $trans_terkini['no_rekening'] ?></td>
                    <td><?= ucwords($trans_terkini['nama']) ?></td>
                    <td class="tengah">
                        <font color='#ffffff'>'</font><?= $trans_terkini['no_sertifikat'] ?>
                    </td>
                    <td class="tengah"><?= $trans_terkini['tgl_transaksi'] ?></td>
                    <td class="kanan"><?= number_format($trans_terkini['jml_transaksi'], 2, ',', '.'); ?></td>
                </tr>
            <?php }
            ?>

            <tr>
                <td colspan="5" class="kanan"><b>Jumlah</b></td>
                <td class="kanan"><b><?= number_format($total_transaksi, 2, ',', '.'); ?></b></td>
            </tr>
        </tbody>
    </table>
    <table class="no-background">
        <thead>
            <tr class="putih">
                <td class="no-border"></td>
            </tr>
            <tr class="putih">
                <td class="no-border kanan">Sumenep, <?= date('d-m-Y') ?>
            </tr>
            </td>
        </thead>
        <tbody>
            <tr class="putih">
                <td class="no-border kanan">
                    Bendahara,</td>
            </tr>
            <tr class="putih">
                <td class="no-border"></td>
            </tr>
            <tr class="putih">
                <td class="no-border"></td>
            </tr>
            <tr class="putih">
                <td class="no-border kanan"><b>
                        Abd. Basith</b>
            </tr>
            </td>
        </tbody>
    </table>
</body>

</html>