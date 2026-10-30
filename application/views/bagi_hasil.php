<!-- Main Content -->
<div class="main-content">
    <section class="section">
        <div class="section-header">
            <h1>Bagi Hasil Sisa Hasil Usaha</h1>
            <div class="section-header-breadcrumb">
                <div class="breadcrumb-item active"><a href="<?= site_url('dashboard') ?>">Dashboard</a></div>
                <div class="breadcrumb-item">Bagi Hasil SHU</div>
                <div class="breadcrumb-item"><?= $tahun ?></div>
            </div>
        </div>
        <div class="card card-primary">
            <div class="card-body">
                <form action="<?= site_url() ?>bagi_hasil" method="POST">
                    <div class="form-group col-md-4 col-lg-4">
                        <label>Tahun</label>
                        <div class="input-group">
                            <select class="custom-select" id="tahunBagiHasil" name="tahunBagiHasil">
                                <option value="">--Pilih Tahun--</option>
                                <?php
                                $thn_skr = date('Y');
                                for ($x = $thn_skr; $x >= $thn_skr - 5; $x--) {
                                ?>
                                    <option value="<?= $x ?>" <?php if ($tahun == $x) {
                                                                    echo "selected";
                                                                } ?>><?= $x ?></option>
                                <?php
                                } ?>
                            </select>
                            <div class="input-group-append">
                                <button class="btn btn-primary" type="submit">OK</button>
                            </div>
                        </div>
                    </div>
                </form>
            </div>
        </div>
        <div class="card card-primary">
            <div class="card-header">
                <h2 class="section-title">Distribusi Nisbah Bagi Hasil</h2>
            </div>
            <div class="card-body">
                <div class="table-responsive">
                    <table class="table table-striped" id="table-1">
                        <thead>
                            <tr class="text-center">
                                <th>No.</th>
                                <th>Nomor Rekening</th>
                                <!-- <th>Nama Anggota</th> -->
                                <th>Saham Persero (Rp)</th>
                                <th>Sisa Hasil Usaha (Rp)</th>
                                <th>Total Saldo<br>Rata-rata Harian (Rp)</th>
                                <th>Saham Rata-rata<br>Harian Persero (Rp)</th>
                                <th>Nisbah Bagi Hasil (Rp)</th>
                            </tr>
                        </thead>
                        <tbody>
                            <?php
                            $no = 0;
                            foreach ($data_bagi_hasil as $bagi_hasil) {
                                $no += 1; ?>
                                <tr>
                                    <td class="text-center"><?= $no ?></td>
                                    <td class="text-center"><?= $bagi_hasil['no_rekening'] ?></td>
                                    <!-- <td class="text-center"><?= $bagi_hasil['nama'] ?></td> -->
                                    <td class="text-center"><?= number_format($bagi_hasil['saham_persero'], 2, ',', '.'); ?></td>
                                    <td class="text-right"><?= number_format($bagi_hasil['shu'], 2, ',', '.'); ?></td>
                                    <td class="text-right"><?= number_format($bagi_hasil['total_rata_harian'], 2, ',', '.'); ?></td>
                                    <td class="text-right"><?= number_format($bagi_hasil['rata_harian_persero'], 2, ',', '.'); ?></td>
                                    <td class="text-right"><?= number_format($bagi_hasil['nisbah_bagi_hasil'], 2, ',', '.'); ?></td>
                                </tr>
                            <?php
                            }
                            ?>
                        </tbody>
                    </table>
                </div>
            </div>
    </section>
</div>