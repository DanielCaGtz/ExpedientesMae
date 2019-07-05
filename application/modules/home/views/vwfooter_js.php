    <footer class="main-footer">
      	<div class="pull-right hidden-xs">
        	<b>Version</b> <?php echo CURRENT_VERSION; ?>
      	</div>
      	<strong>Copyright &copy; <?php echo date('Y'); ?> Integralle.</strong> Todos los derechos reservados.
    </footer>
    <script src="<?php echo base_url(); ?>plugins/jQuery/jquery-2.2.3.min.js"></script>
		<script src="https://code.jquery.com/jquery-1.12.4.js"></script>
		<script src="https://code.jquery.com/ui/1.12.1/jquery-ui.js"></script>
		<script src="<?php echo base_url(); ?>bootstrap/js/bootstrap.min.js"></script>
		<script src="<?php echo base_url(); ?>plugins/datatables/jquery.dataTables.min.js"></script>
		<script src="<?php echo base_url(); ?>plugins/datatables/dataTables.bootstrap.min.js"></script>
		<script src="<?php echo base_url(); ?>plugins/slimScroll/jquery.slimscroll.min.js"></script>
		<script src="<?php echo base_url(); ?>plugins/fastclick/fastclick.js"></script>
		<script src="<?php echo base_url(); ?>dist/js/app.min.js"></script>
		<script src="<?php echo base_url(); ?>dist/js/demo.js"></script>
		<script src="<?php echo base_url(); ?>js/classie.js"></script>
		<script src="<?php echo base_url(); ?>plugins/iCheck/icheck.min.js"></script>
    <?php
      if ($js_files && is_array($js_files)) {
        foreach ($js_files AS $key) { ?>
          <script src="<?php echo base_url($key); ?>"></script>
    <?php
        }
      }
    ?>
  </body>
</html>