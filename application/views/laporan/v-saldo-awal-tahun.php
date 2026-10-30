<p></p>
<h2> SALDO AWAL TAHUN<br> CV. DHARMA IAA TAHUN BUKU <?= $tahun ?></h2>
<p></p>
<table border="1">
    <thead>
        <tr class="text-center">
            <th>No.</th>
            <th>Nomor Rekening</th>
            <th>Nama</th>
            <th>Tanggal Transaksi</th>
            <th>Jumlah Transaksi (Rp)</th>
        </tr>
    </thead>
    <tbody>
        <?php
        $no = 0;
        $total_transaksi = 0;
        foreach ($data_saldo_awal as $saldo) {
            $no += 1;
            $total_transaksi += (float) $saldo['jml_transaksi'];
        ?>
            <tr>
                <td style="text-align: center"><?= $no ?></td>
                <td  style="text-align: center"><?= $saldo['no_rekening'] ?></td>
                <td><?= ucwords($saldo['nama']) ?></td>
                <td class="tengah"><?= $saldo['tgl_transaksi'] ?></td>
                <td class="kanan"><?= number_format($saldo['jml_transaksi'], 2, ',', '.'); ?></td>
            </tr>
        <?php }
        ?>

        <tr>
            <td colspan="4" class="kanan"><b>Jumlah</b></td>
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