		<div class="main-container ace-save-state" id="main-container">
			<script type="text/javascript">
				try{ace.settings.loadState('main-container')}catch(e){}
			</script>

			<div id="sidebar" class="sidebar responsive ace-save-state">
				<script type="text/javascript">
					try{ace.settings.loadState('sidebar')}catch(e){}
				</script>				

				<ul class="nav nav-list">
					 <?php include "menu_left.php";?>
				</ul><!-- /.nav-list -->
				<div class="sidebar-toggle sidebar-collapse" id="sidebar-collapse">
					<i id="sidebar-toggle-icon" class="ace-icon fa fa-angle-double-left ace-save-state" data-icon1="ace-icon fa fa-angle-double-left" data-icon2="ace-icon fa fa-angle-double-right"></i>
				</div>
				
			</div>

			<div class="main-content">
				<?php include "inc/inc.content.php";?>	
			</div>

			<div class="footer">
				<div class="footer-inner">
					<div class="footer-content">
						<span class="pull-left">
							Created By IT Team <?php echo date('Y');?>
						</span>
						<span class="pull-right">
				            Padmatirta Wisesa
				        </span>
					</div>
				</div>
			</div>

			<a href="#" id="btn-scroll-up" class="btn-scroll-up btn btn-sm btn-inverse">
				<i class="ace-icon fa fa-angle-double-up icon-only bigger-110"></i>
			</a>
			
		</div>
		<script src="assets/js/ace-elements.min.js"></script>
		<script src="assets/js/ace.min.js"></script>
		<script src="assets/js/notify.js"></script>
		<script src="assets/js/rowspanizer.js"></script>