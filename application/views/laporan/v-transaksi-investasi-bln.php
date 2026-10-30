<p></p>
<h2> TRANSAKSI INVESTASI BULAN <?= strtoupper($nama_bulan) ?> <br> CV. DHARMA IAA TAHUN BUKU <?= $tahun ?></h2>
<p></p>
<table border="1">
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
        foreach ($data_trans_bln as $trans_bln) {
            $no += 1;
            $total_transaksi += (float) $trans_bln['jml_transaksi'];
        ?>
            <tr>
                <td style="text-align: center"><?= $no ?></td>
                <td style="text-align: center"><?= $trans_bln['no_rekening'] ?></td>
                <td><?= ucwords($trans_bln['nama']) ?></td>
                <td class="tengah">
                    <font color='#ffffff'>'</font><?= $trans_bln['no_sertifikat'] ?>
                </td>
                <td class="tengah"><?= $trans_bln['tgl_transaksi'] ?></td>
                <td class="kanan"><?= number_format($trans_bln['jml_transaksi'], 2, ',', '.'); ?></td>
            </tr>
        <?php }
        ?>

        <tr>
            <td colspan="5" class="kanan"><b>Jumlah</b></td>
            <td class="kanan"><b><?= number_format($total_transaksi, 2, ',', '.'); ?></b></td>
        </tr>
    </tbody>
</table>
<table>
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