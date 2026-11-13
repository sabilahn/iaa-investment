<!-- footer -->
<footer class="main-footer">
    <div class="footer-left">
        Copyright &copy; 2018 <div class="bullet"></div> Design By Bootstrap
    </div>
</footer>
<!-- /footer -->
</div>
</div>

<!-- General JS Scripts -->
<script src="<?= base_url() ?>assets/stisla/vendors/popper.js"></script>
<script src="<?= base_url() ?>assets/stisla/vendors/tooltip.js"></script>
<script src="<?= base_url() ?>assets/stisla/vendors/bootstrap/js/bootstrap.min.js"></script>
<script src="<?= base_url() ?>assets/stisla/vendors/nicescroll/jquery.nicescroll.min.js"></script>
<script src="<?= base_url() ?>assets/stisla/vendors/moment.min.js"></script>
<script src="<?= base_url() ?>assets/stisla/build/js/stisla.js"></script>

<!-- JS Libraies -->
<script src="<?= base_url() ?>assets/stisla/vendors/simple-weather/jquery.simpleWeather.min.js"></script>
<script src="<?= base_url() ?>assets/stisla/vendors/chart.min.js"></script>
<script src="<?= base_url() ?>assets/stisla/vendors/jqvmap/dist/jquery.vmap.min.js"></script>
<script src="<?= base_url() ?>assets/stisla/vendors/jqvmap/dist/maps/jquery.vmap.world.js"></script>
<script src="<?= base_url() ?>assets/stisla/vendors/summernote/summernote-bs4.js"></script>
<script src="<?= base_url() ?>assets/stisla/vendors/chocolat/dist/js/jquery.chocolat.min.js"></script>

<script src="<?= base_url() ?>assets/stisla/vendors/datatables/datatables.min.js"></script>
<script src="<?= base_url() ?>assets/stisla/vendors/datatables/DataTables/js/dataTables.bootstrap4.min.js"></script>
<script src="<?= base_url() ?>assets/stisla/vendors/datatables/Select-1.2.4/js/dataTables.select.min.js"></script>
<script src="<?= base_url() ?>assets/stisla/vendors/jquery-ui/jquery-ui.min.js"></script>
<script src="<?= base_url() ?>assets/stisla/vendors/izitoast/js/iziToast.min.js"></script>

<!-- Page Specific JS File -->
<script src="<?= base_url() ?>assets/stisla/build/js/page/index-0.js"></script>
<script src="<?= base_url() ?>assets/stisla/build/js/page/bootstrap-modal.js"></script>
<script src="<?= base_url() ?>assets/stisla/build/js/page/modules-datatables.js"></script>
<script src="<?= base_url() ?>assets/stisla/build/js/page/modules-toastr.js"></script>

<!-- Template JS File -->
<script src="<?= base_url() ?>assets/stisla/build/js/scripts.js"></script>
<script src="<?= base_url() ?>assets/stisla/build/js/custom.js"></script>

<!-- JS input Libraies -->
<script src="<?= base_url() ?>assets/stisla/vendors/cleave-js/dist/cleave.min.js"></script>
<script src="<?= base_url() ?>assets/stisla/vendors/cleave-js/dist/addons/cleave-phone.us.js"></script>
<script src="<?= base_url() ?>assets/stisla/vendors/jquery-pwstrength/jquery.pwstrength.min.js"></script>
<script src="<?= base_url() ?>assets/stisla/vendors/bootstrap-daterangepicker/daterangepicker.js"></script>
<script src="<?= base_url() ?>assets/stisla/vendors/bootstrap-colorpicker/dist/js/bootstrap-colorpicker.min.js"></script>
<script src="<?= base_url() ?>assets/stisla/vendors/bootstrap-timepicker/js/bootstrap-timepicker.min.js"></script>
<script src="<?= base_url() ?>assets/stisla/vendors/bootstrap-tagsinput/dist/bootstrap-tagsinput.min.js"></script>
<script src="<?= base_url() ?>assets/stisla/vendors/select2/dist/js/select2.full.min.js"></script>
<script src="<?= base_url() ?>assets/stisla/vendors/jquery-selectric/jquery.selectric.min.js"></script>
<script>
    function initAutoDismissAlerts() {
        $('.alert').each(function() {
            var $alert = $(this);
            if (!$alert.data('auto-dismiss-bound')) {
                $alert.data('auto-dismiss-bound', true);
                setTimeout(function() {
                    $alert.fadeOut(400, function() {
                        $(this).remove();
                    });
                }, 3000);
            }
        });
    }

    function formatNominalValue(rawValue, fromDatabase) {
        if (rawValue === null || rawValue === undefined) return '';
        var str = String(rawValue).trim();
        if (str === '') return '';

        if (fromDatabase) {
            // Nilai dari DB memakai titik untuk desimal (mis. 1000000 atau 1000000.50)
            str = str.replace(/\./g, ',');
        }

        // Hanya izinkan digit dan satu koma desimal
        str = str.replace(/[^0-9,]/g, '');
        var parts = str.split(',');
        var intPart = parts[0].replace(/^0+(?=\d)/, '');
        var formattedInt = intPart.replace(/\B(?=(\d{3})+(?!\d))/g, '.');

        if (parts.length > 1) {
            return formattedInt + ',' + parts.slice(1).join('');
        }
        return formattedInt;
    }

    function cleanNominalValue(formattedValue) {
        if (!formattedValue) return '';
        return String(formattedValue).replace(/\./g, '').replace(',', '.').trim();
    }

    function initCurrencyInputs() {
        $('.currency-input').each(function() {
            var $input = $(this);
            if ($input.val() !== '') {
                $input.val(formatNominalValue($input.val(), true));
            }
            if (!$input.data('currency-bound')) {
                $input.data('currency-bound', true);
                $input.on('input', function() {
                    var el = this;
                    var formatted = formatNominalValue(el.value, false);
                    el.value = formatted;
                });
            }
        });

        $('form').each(function() {
            var $form = $(this);
            if (!$form.data('currency-submit-bound')) {
                $form.data('currency-submit-bound', true);
                $form.on('submit', function() {
                    $form.find('.currency-input').each(function() {
                        $(this).val(cleanNominalValue($(this).val()));
                    });
                });
            }
        });
    }

    $(document).ready(function() {
        initAutoDismissAlerts();
        initCurrencyInputs();
    });
    $(document).ajaxComplete(function() {
        initAutoDismissAlerts();
    });
</script>
</body>

</html>