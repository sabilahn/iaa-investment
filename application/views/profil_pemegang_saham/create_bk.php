<!-- Tambah -->
<div class="main-content">
    <section class="section">
        <div class="section-header">
            <h1>Profil Pemegang Saham</h1>
            <div class="section-header-breadcrumb">
                <div class="breadcrumb-item active"><a href="<?= site_url('dashboard') ?>">Dashboard</a></div>
                <div class="breadcrumb-item active"><a href="<?= site_url('profil_pemegang_saham') ?>">Profil Pemegang Saham</a></div>
                <div class="breadcrumb-item">Tambah Data</div>
            </div>
        </div>
        <div class="section-body">
            <div class="card card-primary">
                <?= form_open('profil_pemegang_saham/tambah_data'); ?>
                <div class="card-body">
                    <?php if ($this->session->flashdata('error')) : ?>
                        <div class="alert alert-danger alert-dismissible show fade">
                            <div class="alert-body text-center">
                                <button class="close" data-dismiss="alert">
                                    <span>&times;</span>
                                </button>
                                <b><?= $this->session->flashdata('error') ?></b>
                            </div>
                        </div>
                    <?php endif; ?>
                    <div class="row">
                        <div class="col-12 col-md-4 col-lg-4">
                            <div class="form-group mb-0">
                                <label>No. Rekening <font color='#ff0000'> *</font></label>
                                <input type="text" name="rekening" id="rekening" class="form-control" placeholder="0000-0000">
                            </div>
                            <div class="form-group mb-0">
                                <label>NIK</label>
                                <input type="number" name="nik" id="nik" class="form-control">
                            </div>
                            <div class="form-group mb-0">
                                <label>Nama <font color='#ff0000'> *</font></label>
                                <input type="text" name="nama" id="nama" class="form-control">
                            </div>
                            <div class="form-group mb-0">
                                <label>Tempat & Tanggal Lahir</label>
                                <div class="input-group">
                                    <input type="text" name="tmp_lahir" id="tmp_lahir" class="form-control" placeholder="Tmp. Lahir">
                                    <input type="text" name="tgl_lahir" id="tgl_lahir" class="form-control datepicker">
                                </div>
                            </div>
                            <div class="form-group mb-0">
                                <label>Pekerjaan</label>
                                <select class="custom-select" name="pekerjaan" id="pekerjaan">
                                    <option value="">--Pilih Pekerjaan--</option>
                                    <option value="PNS">PNS</option>
                                    <option value="Wiraswasta">Wiraswasta</option>
                                </select>
                            </div>
                        </div>
                        <div class="col-12 col-md-4 col-lg-4">
                            <div class="form-group mb-0">
                                <label>Alamat Rumah</label>
                                <div class="input-group">
                                    <input type="text" name="dsn" id="dsn" class="form-control" placeholder="Dusun">
                                    <input type="text" name="ds" id="ds" class="form-control" placeholder="Desa">
                                </div>
                            </div>
                            <div class="form-group mb-0">
                                <label>RT/RW</label>
                                <div class="input-group">
                                    <input type="number" name="rt" id="rt" class="form-control" placeholder="RT">
                                    <input type="number" name="rw" id="rw" class="form-control" placeholder="RW">
                                </div>
                            </div>
                            <div class="form-group mb-0">
                                <label>Kecamatan</label>
                                <input type="text" name="kec" id="kec" class="form-control">
                            </div>
                            <div class="form-group mb-0">
                                <label>Kabupaten</label>
                                <input type="text" name="kab" id="kab" class="form-control">
                            </div>
                            <div class="form-group mb-0">
                                <label>Nama Ibu Kandung</label>
                                <input type="text" name="nama_ibu" id="nama_ibu" class="form-control">
                            </div>
                        </div>
                        <div class="col-12 col-md-4 col-lg-4">
                            <div class="form-group mb-0">
                                <label>Gaji/bulan</label>
                                <input type="number" name="gaji" id="gaji" class="form-control">
                            </div>
                            <div class="form-group mb-0">
                                <label>Usaha yang Dikelola</label>
                                <input type="text" name="usaha" id="usaha" class="form-control">
                            </div>
                            <div class="form-group mb-0">
                                <label>Omzet/bulan</label>
                                <input type="number" name="omzet" id="omzet" class="form-control">
                            </div>
                            <div class="form-group mb-0">
                                <label>Tanggal Daftar</label>
                                <input type="text" name="tgl_daftar" id="tgl_daftar" class="form-control datepicker">
                            </div>
                            <div class="form-group mb-0">
                                <label>No. HP</label>
                                <input type="number" name="no_hp" id="no_hp" class="form-control">
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
                    <button type="reset" class="btn btn-light">Reset</button>
                    <button type="submit" name="btn_tambah" id="btn_tambah" class="btn btn-primary">Tambah</button>
                </div>
                </form>
            </div>
        </div>
    </section>
</div>