<?php
$web_page = DB::connection('mysql_hris')->query("SELECT * FROM tbl_webpages WHERE id_webpages in (SELECT id_webpage FROM tbl_hakmenu_webpage WHERE id_user = '" . $_SESSION['id_user'] . "') and webpage_acces = 1 order by web_page_order asc ");
while ($dweb_page = $web_page->fetch_array()):
	$main_menu = DB::connection('mysql_hris')->query("SELECT * FROM tbl_mainmenu WHERE id_webpage = '" . $dweb_page['id_webpages'] . "' and idmain_menu 
		in (SELECT id_mainmenu FROM tbl_hakmenu_mainmenu WHERE id_user = '" . $_SESSION['id_user'] . "') and mainmenu_acces = 1 order by mainmenu_order asc");
	$num_mainmenu = $main_menu->num_rows;
	?>
	<li <?php if (isset($_GET['page']) and $_GET['page'] == $dweb_page['web_page_case']) { ?>class="active" <?php } ?>>
		<a href="<?php echo $dweb_page['webpage_link']; ?>" <?php if ($num_mainmenu != 0) { ?> class="dropdown-toggle" <?php } ?>>
			<i class="<?php echo $dweb_page['webpage_icon']; ?>"></i>
			<span class="menu-text"><?php echo $dweb_page['webpage_display']; ?></span>
			<?php if ($num_mainmenu != 0) {
				echo "<b class='arrow fa fa-angle-down'></b>";
			} ?>
		</a>

		<?php

		if ($num_mainmenu != 0) {
			echo "<ul class='submenu'>";
			while ($dmain_menu = $main_menu->fetch_array()) {
				$submenu = DB::connection('mysql_hris')->query("SELECT * FROM tbl_submenu WHERE id_mainmenu = '" . $dmain_menu['idmain_menu'] . "' and id_submenu in (SELECT id_submenu FROM tbl_hakmenu_submenu WHERE id_user = '" . $_SESSION['id_user'] . "') and submenu_access = 1 order by submenu_order asc");
				$num_submenu = $submenu->num_rows;
				?>
			<li class="">
				<a href="<?php echo $dmain_menu['mainmenu_link']; ?>" <?php if ($num_submenu != 0) { ?> class="dropdown-toggle" <?php } ?>>
					<?php echo $dmain_menu['mainmenu_display']; ?>
					<?php if ($num_submenu != 0) {
						echo " <b class='arrow fa fa-angle-down'></b>";
					} ?>
				</a>

				<?php if ($num_submenu != 0) {
					echo "<ul class='submenu'>";
					while ($dsubmenu = $submenu->fetch_array()) {
						?>
					<li class="">
						<a href="<?php echo $dsubmenu['submenu_link']; ?>">
							<i class="menu-icon fa fa-caret-right"></i>
							<?php echo $dsubmenu['submenu_display']; ?>
						</a>
					</li>
				<?php }
					echo "</ul>";
				} ?>

			</li>

		<?php }
			echo "</ul>";
		} ?>
	</li>

<?php endwhile; ?>