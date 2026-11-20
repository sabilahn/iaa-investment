<!-- Update -->
<div class="main-content">
    <section class="section">
        <div class="section-header">
            <h1>Profil Pemegang Saham</h1>
            <div class="section-header-breadcrumb">
                <div class="breadcrumb-item active"><a href="<?= site_url('dashboard') ?>">Dashboard</a></div>
                <div class="breadcrumb-item active"><a href="<?= site_url('profil_pemegang_saham') ?>">Profil Pemegang Saham</a></div>
                <div class="breadcrumb-item">Ubah Data</div>
            </div>
        </div>
        <?php
        foreach ($profiles as $profile) :
            $no_rekening = $profile['no_rekening'];

        ?>
            <div class="section-body">
                <div class="card card-primary">
                    <form action="<?= site_url() ?>profil_pemegang_saham/ubah_data" method="POST">
                        <div class="card-body">
                            <div class="row">
                                <div class="col-12 col-md-4 col-lg-4">
                                    <div class="form-group mb-0">
                                        <label>No. Rekening <font color='#ff0000'> *</font></label>
                                        <input type="text" name="rekening_edt" id="rekening_edt" class="form-control" value="<?= $profile['no_rekening'] ?>" readonly="">
                                    </div>
                                    <div class="form-group mb-0">
                                        <label>NIK</label>
                                        <input type="number" name="nik_edt" id="nik_edt" class="form-control" value="<?= $profile['nik'] ?>" required="">
                                    </div>
                                    <div class="form-group mb-0">
                                        <label>Nama <font color='#ff0000'> *</font></label>
                                        <input type="text" name="nama_edt" id="nama_edt" class="form-control" value="<?= $profile['nama'] ?>" required="">
                                    </div>
                                    <div class="form-group mb-0">
                                        <label>Tempat & Tanggal Lahir</label>
                                        <div class="input-group">
                                            <input type="text" name="tmp_lahir_edt" id="tmp_lahir_edt" class="form-control" placeholder="Tmp. Lahir" value="<?= $profile['tmp_lahir'] ?>">
                                            <input type="text" name="tgl_lahir_edt" id="tgl_lahir_edt" class="form-control datepicker" value="<?= $profile['tgl_lahir'] ?>">
                                        </div>
                                    </div>
                                    <div class="form-group mb-0">
                                        <label>Pekerjaan</label>
                                        <select class="custom-select" name="pekerjaan_edt" id="pekerjaan_edt">
                                            <option value="">--Pilih Pekerjaan--</option>
                                            <option value="PNS" <?php if ($profile['pekerjaan'] == "PNS") {
                                                                    echo "selected";
                                                                } ?>>PNS</option>
                                            <option value="Wiraswasta" <?php if ($profile['pekerjaan'] == "Wiraswasta") {
                                                                            echo "selected";
                                                                        } ?>>Wiraswasta</option>
                                        </select>
                                    </div>
                                </div>

                                <div class="col-12 col-md-4 col-lg-4">
                                    <div class="form-group mb-0">
                                        <label>Alamat Rumah</label>
                                        <div class="input-group">
                                            <input type="text" name="dsn_edt" id="dsn_edt" class="form-control" placeholder="Dusun" value="<?= $profile['dsn'] ?>">
                                            <input type="text" name="ds_edt" id="ds_edt" class="form-control" placeholder="Desa" value="<?= $profile['ds'] ?>">
                                        </div>
                                    </div>
                                    <div class="form-group mb-0">
                                        <label>RT/RW</label>
                                        <div class="input-group">
                                            <input type="number" name="rt_edt" id="rt_edt" class="form-control" placeholder="RT" value="<?= $profile['rt'] ?>">
                                            <input type="number" name="rw_edt" id="rw_edt" class="form-control" placeholder="RW" value="<?= $profile['rw'] ?>">
                                        </div>
                                    </div>
                                    <div class="form-group mb-0">
                                        <label>Kecamatan</label>
                                        <input type="text" name="kec_edt" id="kec_edt" class="form-control" value="<?= $profile['kec'] ?>">
                                    </div>
                                    <div class="form-group mb-0">
                                        <label>Kabupaten</label>
                                        <input type="text" name="kab_edt" id="kab_edt" class="form-control" value="<?= $profile['kab'] ?>">
                                    </div>
                                    <div class="form-group mb-0">
                                        <label>Nama Ibu Kandung</label>
                                        <input type="text" name="nama_ibu_edt" id="nama_ibu_edt" class="form-control" value="<?= $profile['nama_ibu'] ?>">
                                    </div>
                                </div>
                                <div class="col-12 col-md-4 col-lg-4">
                                    <div class="form-group mb-0">
                                        <label>Gaji/bulan</label>
                                        <input type="number" name="gaji_edt" id="gaji_edt" class="form-control" value="<?= $profile['gaji'] ?>">
                                    </div>
                                    <div class="form-group mb-0">
                                        <label>Usaha yang Dikelola</label>
                                        <input type="text" name="usaha_edt" id="usaha_edt" class="form-control" value="<?= $profile['usaha'] ?>">
                                    </div>
                                    <div class="form-group mb-0">
                                        <label>Omzet/bulan</label>
                                        <input type="number" name="omzet_edt" id="omzet_edt" class="form-control" value="<?= $profile['omzet'] ?>">
                                    </div>
                                    <div class="form-group mb-0">
                                        <label>Tanggal Daftar</label>
                                        <input type="text" name="tgl_daftar_edt" id="tgl_daftar_edt" class="form-control datepicker" value="<?= $profile['tgl_daftar'] ?>">
                                    </div>
                                    <div class="form-group mb-0">
                                        <label>No. HP</label>
                                        <input type="number" name="no_hp_edt" id="no_hp_edt" class="form-control" value="<?= $profile['no_hp'] ?>">
                                    </div>
                                </div>
                            </div>
                            <br>
                            <p>
                                <font color='#ff0000'> ( * ) Form harus diisi </font>
                            </p>
                        </div>
                        <div class="card-footer text-right">
                            <a href="<?= site_url() ?>profil_pemegang_saham" class="btn btn-info">Kembali</a>
                            <button type="submit" name="btn_edit" id="btn_edit" class="btn btn-primary">Perbarui</button>
                        </div>
                    </form>
                </div>
            </div>
        <?php endforeach; ?>
    </section>
</div>