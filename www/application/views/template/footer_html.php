<script src="<?= base_url('node_modules/jquery/dist/jquery.min.js') ?>"></script>
<script src="<?= base_url('node_modules/bootstrap/dist/js/bootstrap.bundle.min.js') ?>"></script>
<script src="<?= base_url('node_modules/jquery-mask-plugin/dist/jquery.mask.min.js') ?>"></script>
<script src="<?= base_url('node_modules/bootstrap-datepicker/dist/js/bootstrap-datepicker.min.js') ?>"></script>
<script src="<?= base_url('node_modules/bootstrap-datepicker/js/locales/bootstrap-datepicker.pt-BR.js') ?>"></script>
<script src="<?= base_url('node_modules/moment/min/moment-with-locales.min.js') ?>"></script>
<script src="<?= base_url('node_modules/chart.js/dist/chart.js') ?>"></script>
<script src="<?= base_url('assets/js/script.js') ?>"></script>
<?php
if (isset($script)):
    echo '<script src="' . $script . '"></script>';
endif;
?>
</body>

</html>