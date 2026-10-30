<p></p>
<h2> PENARIKAN INVESTASI <br> CV. DHARMA IAA TAHUN BUKU <?= $tahun ?></h2>
<p></p>
<table border="1">
    <thead>
        <tr class="text-center">
            <th>No.</th>
            <th>Nomor Rekening</th>
            <th>Nama</th>
            <th>Tanggal Penarikan</th>
            <th>Jumlah Penarikan (Rp)</th>
        </tr>
    </thead>
    <tbody>
        <?php
        $no = 0;
        $total_penarikan = 0;
        foreach ($data_penarikan as $penarikan) {
            $no += 1;
            $total_penarikan += (float) $penarikan['jml_penarikan'];
        ?>
            <tr>
                <td style="text-align: center"><?= $no ?></td>
                <td style="text-align: center"><?= $penarikan['no_rekening'] ?></td>
                <td><?= ucwords($penarikan['nama']) ?></td>
                <td class="tengah"><?= $penarikan['tgl_penarikan'] ?></td>
                <td class="kanan"><?= number_format($penarikan['jml_penarikan'], 2, ',', '.'); ?></td>
            </tr>
        <?php }
        ?>

        <tr>
            <td colspan="4" class="kanan"><b>Jumlah</b></td>
            <td class="kanan"><b><?= number_format($total_penarikan, 2, ',', '.'); ?></b></td>
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