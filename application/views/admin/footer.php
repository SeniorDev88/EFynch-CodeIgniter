<!-- Footer -->
<div class="footer text-muted">
    &copy; 2016. <a href="#">DMS</a> by <a href="#" target="_blank">Matrix Intech</a>
</div>
<!-- /footer -->

</div>
<!-- /content area -->

</div>
<!-- /main content -->

</div>
<!-- /page content -->

</div>
<!-- /page container -->
<!-- Core JS files -->
<script type="text/javascript" src="<?php echo(base_url()); ?>assets/admin/js/plugins/loaders/pace.min.js"></script>
<script type="text/javascript" src="<?php echo(base_url()); ?>assets/admin/js/core/libraries/bootstrap.min.js"></script>
<script type="text/javascript" src="<?php echo(base_url()); ?>assets/admin/js/plugins/loaders/blockui.min.js"></script>
<!-- /core JS files -->

<!-- Theme JS files -->
<script type="text/javascript" src="<?php echo(base_url()); ?>assets/admin/js/plugins/visualization/d3/d3.min.js"></script>
<script type="text/javascript" src="<?php echo(base_url()); ?>assets/admin/js/plugins/visualization/d3/d3_tooltip.js"></script>
<script type="text/javascript" src="<?php echo(base_url()); ?>assets/admin/js/plugins/forms/styling/switchery.min.js"></script>
<script type="text/javascript" src="<?php echo(base_url()); ?>assets/admin/js/plugins/forms/styling/uniform.min.js"></script>
<script type="text/javascript" src="<?php echo(base_url()); ?>assets/admin/js/plugins/forms/selects/bootstrap_multiselect.js"></script>
<script type="text/javascript" src="<?php echo(base_url()); ?>assets/admin/js/plugins/ui/moment/moment.min.js"></script>

<!--<script type="text/javascript" src="<?php /*echo(base_url()); */?>assets/admin/js/core/app.js"></script>-->
<script type="text/javascript" src="<?php echo(base_url()); ?>assets/admin/js/core/libraries/jquery_ui/datepicker.min.js"></script>
<script type="text/javascript" src="<?php echo(base_url()); ?>assets/admin/js/core/libraries/jquery_ui/effects.min.js"></script>
<script type="text/javascript" src="<?php echo(base_url()); ?>assets/admin/js/plugins/notifications/jgrowl.min.js"></script>
<script type="text/javascript" src="<?php echo(base_url()); ?>assets/admin/js/plugins/ui/moment/moment.min.js"></script>
<script type="text/javascript" src="<?php echo(base_url()); ?>assets/admin/js/plugins/pickers/daterangepicker.js"></script>
<script type="text/javascript" src="<?php echo(base_url()); ?>assets/admin/js/plugins/pickers/anytime.min.js"></script>
<script type="text/javascript" src="<?php echo(base_url()); ?>assets/admin/js/plugins/pickers/pickadate/picker.js"></script>
<script type="text/javascript" src="<?php echo(base_url()); ?>assets/admin/js/plugins/pickers/pickadate/picker.date.js"></script>
<script type="text/javascript" src="<?php echo(base_url()); ?>assets/admin/js/plugins/pickers/pickadate/picker.time.js"></script>
<script type="text/javascript" src="<?php echo(base_url()); ?>assets/admin/js/plugins/pickers/pickadate/legacy.js"></script>
<script type="text/javascript" src="<?php echo(base_url()); ?>assets/admin/js/pages/picker_date.js"></script>
<!--<script type="text/javascript" src="<?php /*echo(base_url()); */?>assets/admin/js/pages/dashboard.js"></script>-->
<!-- /theme JS files -->

<!-- Theme JS files for ckEditor -->
<script type="text/javascript" src="<?php echo(base_url()); ?>assets/ckeditor/ckeditor.js"></script>
<!--<script type="text/javascript" src="<?php /*echo(base_url()); */?>assets/admin/js/plugins/forms/selects/select2.min.js"></script>
<script type="text/javascript" src="<?php /*echo(base_url()); */?>assets/admin/js/core/app.js"></script>-->
<script type="text/javascript" src="<?php echo(base_url()); ?>assets/admin/js/core/app.js"></script>
<script type="text/javascript" src="<?php echo(base_url()); ?>assets/admin/js/pages/editor_ckeditor.js"></script>
<!-- /theme JS files -->

<script>
    $(document).ready(function() {
        $(function () {
            var pgurl = window.location.href;
            $("#menuBoxX ul li a").each(function () {
                if ($(this).attr("href") == pgurl || $(this).attr("href") == '')
                    $(this).css("background", "#1b3135");
            })
        });
    });
</script>

</body>
</html>