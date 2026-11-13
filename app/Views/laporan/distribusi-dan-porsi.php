<!-- Main Content -->
<div class="main-content">
    <section class="section">
        <div class="section-header">
            <h1>Laporan Distribusi dan Porsi</h1>
            <div class="section-header-breadcrumb">
                <div class="breadcrumb-item active"><a href="<?= site_url('dashboard') ?>">Dashboard</a></div>
                <div class="breadcrumb-item">Laporan</div>
                <div class="breadcrumb-item">Distribusi dan Porsi</div>
            </div>
        </div>
        <div class="card card-primary">
            <div class="card-body">
                <form target="_blank" action="<?= site_url() ?>laporan/cetak_distribusi_dan_porsi" method="POST">
                    <div class="form-group col-md-4 col-lg-4">
                        <label>Tahun</label>
                        <select class="custom-select" id="tahunDisporsi" name="tahunDisporsi">
                            <?php
                            $thn_skr = date('Y');
                            for ($x = $thn_skr; $x >= $thn_skr - 5; $x--) {
                            ?>
                                <option value="<?= $x ?>"><?= $x ?></option>
                            <?php
                            } ?>
                        </select>
                        <h1></h1>
                        <button class="btn btn-primary" type="submit">Cetak</button>
                    </div>
                </form>
            </div>
        </div>
    </section>
</div>