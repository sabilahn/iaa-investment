<p></p>
<h2> SALDO HARIAN <br> CV. DHARMA IAA TAHUN BUKU <?= $tahun ?></h2>
<p></p>
<table border="1">
    <thead>
        <tr class="text-center">
            <th>No.</th>
            <th>Nomor Rekening</th>
            <th>Nama</th>
            <th>Tanggal Daftar</th>
            <th>Nilai Investasi</th>
            <th>Total Rata-rata Harian (Rp)</th>
            <th>Saldo Rata-rata/Tahun (Rp)</th>
        </tr>
    </thead>
    <tbody>
        <?php
        $no = 0;
        $total_investasi = 0;
        $total_rata_harian = 0;
        $total_rata_tahun = 0;
        foreach ($data_saldo as $saldo) {
            $no += 1;
            $total_investasi += (float) $saldo['nilai_investasi'];
            $total_rata_harian += (float) $saldo['rata_rata_harian'];
            $total_rata_tahun += (float) $saldo['rata_rata_tahun'];
        ?>
            <tr>
                <td style="text-align: center"><?= $no ?></td>
                <td style="text-align: center"><?= $saldo['no_rekening'] ?></td>
                <td><?= ucwords($saldo['nama']) ?></td>
                <td class="tengah"><?= $saldo['tgl_daftar'] ?></td>
                <td class="kanan"><?= number_format($saldo['nilai_investasi'], 2, ',', '.'); ?></td>
                <td class="kanan"><?= number_format($saldo['rata_rata_harian'], 2, ',', '.'); ?></td>
                <td class="kanan"><?= number_format($saldo['rata_rata_tahun'], 2, ',', '.'); ?></td>
            </tr>
        <?php }
        ?>

        <tr>
            <td colspan="4" class="kanan"><b>Jumlah</b></td>
            <td class="kanan"><b><?= number_format($total_investasi, 2, ',', '.'); ?></b></td>
            <td class="kanan"><b><?= number_format($total_rata_harian, 2, ',', '.'); ?></b></td>
            <td class="kanan"><b><?= number_format($total_rata_tahun, 2, ',', '.'); ?></b></td>
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