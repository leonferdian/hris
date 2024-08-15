<script>
	function save_hak_akses(id_user) {

		var num_webpages = $("#num_webpages").val();
		id_webpage = new Array();
		i1 = 0;
		$("input.webpages:checked").each(function() {
			id_webpage[i1] = $(this).val();
			i1++;
		});
		//view webpage
		act_webpage = 'view_webpage';
		
		$.ajax({
			type: "POST",
			url: "inc/administrator/proc_menu_access.php",
			data: "act=" + act_webpage + "&id_user=" + id_user + "&id_webpage=" + id_webpage,
			success: function(responseText) {
				// alert(responseText);
				// location.href="?mm=hak_akses&detail_page=hak_akses_menu&user_id="+id_user;
			},
			error: function(data) {
				//alert(data);$("#progress").hide(); 
			}
		});

		

		add_webpage = new Array();
		i2 = 0;
		$("input.add_webpages:checked").each(function() {
			add_webpage[i2] = $(this).val();
			i2++;
		});
		
		//add webpage
		act_add_webpage = 'add_webpage';
		$.ajax({
			type: "POST",
			url: "inc/administrator/proc_menu_access.php",
			data: "act=" + act_add_webpage + "&id_user=" + id_user + "&id_webpage=" + add_webpage,
			success: function(responseText) {
				//alert(responseText);
				//location.href="?mm=hak_akses&detail_page=hak_akses_menu&user_id="+id_user;
			},
			error: function(data) {
				//alert(data);$("#progress").hide();
			}
		});

		

		update_webpage = new Array();
		i3 = 0;
		$("input.update_webpages:checked").each(function() {
			update_webpage[i3] = $(this).val();
			i3++;
		});
		//edit webpage
		act_edit_webpage = 'edit_webpage';
		$.ajax({
			type: "POST",
			url: "inc/administrator/proc_menu_access.php",
			data: "act=" + act_edit_webpage + "&id_user=" + id_user + "&id_webpage=" + update_webpage,
			success: function(responseText) {
				//alert(responseText);
				//location.href="?mm=hak_akses&detail_page=hak_akses_menu&user_id="+id_user;
			},
			error: function(data) {
				//alert(data);$("#progress").hide(); 
			}
		});

		

		del_webpage = new Array();
		i4 = 0;
		$("input.del_webpages:checked").each(function() {
			del_webpage[i4] = $(this).val();
			i4++;
		});
		//del webpage
		act_del_webpage = 'del_webpage';
		$.ajax({
			type: "POST",
			url: "inc/administrator/proc_menu_access.php",
			data: "act=" + act_del_webpage + "&id_user=" + id_user + "&id_webpage=" + del_webpage,
			success: function(responseText) {
				//alert(responseText);
				//location.href="?mm=hak_akses&detail_page=hak_akses_menu&user_id="+id_user;
			},
			error: function(data) {
				//alert(data);$("#progress").hide(); 
			}
		});

		

		id_mainmenu = new Array();
		j1 = 0;
		$("input.mainmenu:checked").each(function() {
			id_mainmenu[j1] = $(this).val();
			j1++;
		});
		
		//view mainmenu
		act_mainmenu = 'view_mainmenu';
		
		$.ajax({
			type: "POST",
			url: "inc/administrator/proc_menu_access.php",
			data: "act=" + act_mainmenu + "&id_user=" + id_user + "&id_mainmenu=" + id_mainmenu,
			success: function(responseText) {
				//alert(responseText);
				//location.href="?mm=hak_akses&detail_page=hak_akses_menu&user_id="+id_user;
			},
			error: function(data) {
				//alert(data);$("#progress").hide(); 
			}
		});

		

		add_mainmenu = new Array();
		j2 = 0;
		$("input.add_mainmenu:checked").each(function() {
			add_mainmenu[j2] = $(this).val();
			j2++;
		});

		//add mainmenu
		act_add_mainmenu = 'add_mainmenu';
		$.ajax({
			type: "POST",
			url: "inc/administrator/proc_menu_access.php",
			data: "act=" + act_add_mainmenu + "&id_user=" + id_user + "&id_mainmenu=" + add_mainmenu,
			success: function(responseText) {
				//alert(responseText);
				//location.href="?mm=hak_akses&detail_page=hak_akses_menu&user_id="+id_user;
			},
			error: function(data) {
				//alert(data);$("#progress").hide(); 
			}
		});

		

		update_mainmenu = new Array();
		j3 = 0;
		$("input.update_mainmenu:checked").each(function() {
			update_mainmenu[j3] = $(this).val();
			j3++;
		});
		//update mainmenu
		act_update_mainmenu = 'update_mainmenu';
		$.ajax({
			type: "POST",
			url: "inc/administrator/proc_menu_access.php",
			data: "act=" + act_update_mainmenu + "&id_user=" + id_user + "&id_mainmenu=" + update_mainmenu,
			success: function(responseText) {
				//alert(responseText);
				//location.href="?mm=hak_akses&detail_page=hak_akses_menu&user_id="+id_user;
			},
			error: function(data) {
				//alert(data);$("#progress").hide(); 
			}
		});

		

		del_mainmenu = new Array();
		j4 = 0;
		$("input.del_mainmenu:checked").each(function() {
			del_mainmenu[j4] = $(this).val();
			j4++;
		});
		//del mainmenu
		act_del_mainmenu = 'del_mainmenu';
		$.ajax({
			type: "POST",
			url: "inc/administrator/proc_menu_access.php",
			data: "act=" + act_del_mainmenu + "&id_user=" + id_user + "&id_mainmenu=" + del_mainmenu,
			success: function(responseText) {
				//alert(responseText);
				//location.href="?mm=hak_akses&detail_page=hak_akses_menu&user_id="+id_user;
			},
			error: function(data) {
				//alert(data);$("#progress").hide(); 
			}
		});

		

		id_submenu = new Array();
		k1 = 0;
		$("input.submenu:checked").each(function() {
			id_submenu[k1] = $(this).val();
			k1++;
		});
		//view submenu
		act_submenu = 'view_submenu';
		$.ajax({
			type: "POST",
			url: "inc/administrator/proc_menu_access.php",
			data: "act=" + act_submenu + "&id_user=" + id_user + "&id_submenu=" + id_submenu,
			success: function(responseText) {
				//alert(responseText);
				//location.href="?mm=hak_akses&detail_page=hak_akses_menu&user_id="+id_user;
			},
			error: function(data) {
				//alert(data);$("#progress").hide(); 
			}
		});

		//

		add_submenu = new Array();
		k2 = 0;
		$("input.add_submenu:checked").each(function() {
			add_submenu[k2] = $(this).val();
			k2++;
		});
		//add submenu
		act_add_submenu = 'add_submenu';
		$.ajax({
			type: "POST",
			url: "inc/administrator/proc_menu_access.php",
			data: "act=" + act_add_submenu + "&id_user=" + id_user + "&id_submenu=" + add_submenu,
			success: function(responseText) {
				//alert(responseText);
				//location.href="?mm=hak_akses&detail_page=hak_akses_menu&user_id="+id_user;
			},
			error: function(data) {
				//alert(data);$("#progress").hide(); 
			}
		});

		//

		update_submenu = new Array();
		k3 = 0;
		$("input.update_submenu:checked").each(function() {
			update_submenu[k3] = $(this).val();
			k3++;
		});
		//update submenu
		act_update_submenu = 'update_submenu';
		$.ajax({
			type: "POST",
			url: "inc/administrator/proc_menu_access.php",
			data: "act=" + act_update_submenu + "&id_user=" + id_user + "&id_submenu=" + update_submenu,
			success: function(responseText) {
				//alert(responseText);
				//location.href="?mm=hak_akses&detail_page=hak_akses_menu&user_id="+id_user;
			},
			error: function(data) {
				//alert(data);$("#progress").hide(); 
			}
		});

		//

		del_submenu = new Array();
		k4 = 0;
		$("input.del_submenu:checked").each(function() {
			del_submenu[k4] = $(this).val();
			k4++;
		});
		//del submenu
		act_del_submenu = 'del_submenu';
		$.ajax({
			type: "POST",
			url: "inc/administrator/proc_menu_access.php",
			data: "act=" + act_del_submenu + "&id_user=" + id_user + "&id_submenu=" + del_submenu,
			success: function(responseText) {
				alert('Success To Update');
				location.href="?mm=hak_akses&detail_page=hak_akses_menu&user_id="+id_user;
			},
			error: function(data) {
				//alert(data);$("#progress").hide(); 
			}
		}); 

		//
		//alert(del_submenu);
	}

	function hideAll() {
		$(".list_page").hide();
		$(".list_submenu").hide();
	}
	$(document).ready(function() {
		//alert('ss');
		hideAll();

	});
	$(document).on('click', '.button_expand', function() {
		//$(this).parents('tr').remove();    
		var target_string = $(this).attr("data-target");

		var button_state = $(this).attr("data-state");
		//alert(target_string);
		if (button_state == "hide") {
			$('.' + target_string).show();
			$(this).attr("data-state", "show");
		} else {
			$('.' + target_string).hide();
			$(this).attr("data-state", "hide");		
		}
		return false;
	});

	$(document).on('click', '.button_expand2', function() {
		//$(this).parents('tr').remove();    
		var target_string = $(this).attr("data-target");

		var button_state = $(this).attr("data-state");
		//alert(target_string);
		if (button_state == "hide") {
			$('.' + target_string).show();
			$(this).attr("data-state", "show");
		} else {
			$('.' + target_string).hide();
			$(this).attr("data-state", "hide");
		}
		return false;
	});
</script>
<?php
$sql_user="SELECT * FROM user where id_user = '" . $_GET['user_id'] . "'";
$user = DB::connection('mysql_hris')->query($sql_user);  
$duser = $user->fetch_array();
?>
<div class="main-content-inner">
	<div class="breadcrumbs ace-save-state" id="breadcrumbs">
		<ul class="breadcrumb">
			<li>
				<i class="ace-icon fa fa-home home-icon"></i>
				<a href="?page=dashboard">Home</a>
			</li>
			<li>				
				<a href="?mm=hak_akses">Hak Akses user</a>
			</li>
			<li class="active">Hak Akses Menu</li>
		</ul>
	</div>
	<div class="page-content">
		<div class="page-header">
			<h1>
				Hak Akses Menu<small>- <?php echo $duser['nama']; ?></small>
			</h1>
		</div>
	</div>
	<div class="row">
		<div class="col-xs-12">
			<div class="widget-body">
				<table class="table table-striped table-bordered table-hover">
					<thead>
						<tr>
							<th style="text-align: center;">#</th>
							<th style="text-align: center;">Main Menu</th>
							<th style="text-align: center;">Sub menu</th>
							<th style="text-align: center;">Third Menu</th>
							<th style="text-align: center;">View</th>
							<th style="text-align: center;">Add</th>
							<th style="text-align: center;">Update</th>
							<th style="text-align: center;">Delete</th>
						</tr>
					</thead>
					<tbody>
						<?php

						$sql1="SELECT * FROM tbl_webpages where webpage_acces = '1' order by web_page_order asc";
						$webpages =  DB::connection('mysql_hris')->query($sql1);  
						$num_webpages = $webpages->num_rows;
						$no = 1;
						?>
						<input type="hidden" name="num_webpages" id="num_webpages" value="<?php echo $num_webpages; ?>">
						<?php
						$user = DB::connection('mysql_hris')->query($sql_user);  

						while ($dwebpages = $webpages->fetch_array()) {
							
							$cek_akses_webpages = DB::connection('mysql_hris')->query("SELECT * from tbl_hakmenu_webpage where id_webpage = '" . $dwebpages['id_webpages'] . "' and id_user = '" . $_GET['user_id'] . "'");
							$akses_webpages =  $cek_akses_webpages->num_rows;
							$cek_add_webpages = DB::connection('mysql_hris')->query("SELECT * from tbl_hakmenu_webpage where id_webpage = '" . $dwebpages['id_webpages'] . "' and id_user = '" . $_GET['user_id'] . "' and `add` = 1");
							$add_akses_webpages =  $cek_add_webpages->num_rows;
							$cek_update_webpages = DB::connection('mysql_hris')->query("SELECT * from tbl_hakmenu_webpage where id_webpage = '" . $dwebpages['id_webpages'] . "' and id_user = '" . $_GET['user_id'] . "' and `update` = 1");
							$update_akses_webpages =  $cek_update_webpages->num_rows;
							$cek_del_webpages = DB::connection('mysql_hris')->query("SELECT * from tbl_hakmenu_webpage where id_webpage = '" . $dwebpages['id_webpages'] . "' and id_user = '" . $_GET['user_id'] . "' and `delete` = 1");
							$del_akses_webpages =  $cek_del_webpages->num_rows;
							$mainmenu =  DB::connection('mysql_hris')->query("SELECT * FROM tbl_mainmenu where id_webpage = '" . $dwebpages['id_webpages'] . "' and mainmenu_acces='1' order by mainmenu_order asc");
							$num_mainmenu = $mainmenu->num_rows;
							?>
							<tr>
								<td><?php echo $no; ?> <?php if ($num_mainmenu != 0) { ?><a href="#" class="btn-lg button_expand" data-target="list_page<?php echo $no; ?>" data-state="hide">+</a><?php } ?></td>
								<td><?php echo $dwebpages['webpage_display']; ?></td>
								<td></td>
								<td></td>
								<td align="center"> <input type='checkbox' id="<?php echo "view_webpages" . $no; ?>" name="webpages[]" class="webpages" value="<?php echo $dwebpages['id_webpages']; ?>" <?php if ($akses_webpages != 0) {
																																																			echo "checked ";
																																																		} ?> /></td>
								<td align="center"> <input type='checkbox' id="<?php echo "add_webpages" . $no; ?>" name="add_webpages[]" class="add_webpages" value="<?php echo $dwebpages['id_webpages']; ?>" <?php if ($add_akses_webpages != 0) {
																																																				echo "checked ";
																																																			} ?> /></td>
								<td align="center"> <input type='checkbox' id="<?php echo "update_webpages" . $no; ?>" name="update_webpages[]" class="update_webpages" value="<?php echo $dwebpages['id_webpages']; ?>" <?php if ($update_akses_webpages != 0) {
																																																							echo "checked ";
																																																						} ?> /></td>
								<td align="center"> <input type='checkbox' id="<?php echo "delete_webpages" . $no; ?>" name="del_webpages[]" class="del_webpages" value="<?php echo $dwebpages['id_webpages']; ?>" <?php if ($del_akses_webpages != 0) {
																																																					echo "checked ";
																																																				} ?> /></td>
							</tr>
							<?php
							${'mainmenu' . $dwebpages['webpage_display']} = 1;
							while ($dmainmenu = $mainmenu->fetch_array()) {

								$cek_akses_mainmenu = DB::connection('mysql_hris')->query("SELECT * from tbl_hakmenu_mainmenu where id_mainmenu = '" . $dmainmenu['idmain_menu'] . "' and id_user = '" . $_GET['user_id'] . "'");
								$akses_mainmenu =  $cek_akses_mainmenu->num_rows;
								$cek_add_mainmenu = DB::connection('mysql_hris')->query("SELECT * from tbl_hakmenu_mainmenu where id_mainmenu = '" . $dmainmenu['idmain_menu'] . "' and id_user = '" . $_GET['user_id'] . "' and `add` = 1");
								$add_akses_mainmenu =  $cek_add_mainmenu->num_rows;
								$cek_update_mainmenu = DB::connection('mysql_hris')->query("SELECT * from tbl_hakmenu_mainmenu where id_mainmenu = '" . $dmainmenu['idmain_menu'] . "' and id_user = '" . $_GET['user_id'] . "' and `update` = 1");
								$update_akses_mainmenu =  $cek_update_mainmenu->num_rows;
								$cek_del_mainmenu = DB::connection('mysql_hris')->query("SELECT * from tbl_hakmenu_mainmenu where id_mainmenu = '" . $dmainmenu['idmain_menu'] . "' and id_user = '" . $_GET['user_id'] . "' and `delete` = 1");
								$del_akses_mainmenu =  $cek_del_mainmenu->num_rows;
								$submenu =  DB::connection('mysql_hris')->query("SELECT * FROM tbl_submenu where id_mainmenu = '" . $dmainmenu['idmain_menu'] . "' and submenu_access = '1' order by submenu_order asc");
								$num_submenu = $submenu->num_rows;
								?>
								<tr style="background-color:#d2df60" class="list_page<?php echo $no; ?> list_page">
									<td colspan='2'></td>
									<td><?php echo $dmainmenu['mainmenu_display']; ?>
										<?php if ($num_submenu != 0) { ?><a href="#" class="btn-lg button_expand2" data-target="list_submenu<?php echo $dmainmenu['idmain_menu']; ?>" data-state="hide">+</a><?php } ?>
									</td>
									<td></td>
									<td align="center"> <input type='checkbox' id="<?php echo "view_mainmenu" . $dwebpages['id_webpages'] . ${'mainmenu' . $dwebpages['webpage_display']}; ?>" name="mainmenu[]" class="mainmenu" value="<?php echo $dmainmenu['idmain_menu']; ?>" <?php if ($akses_mainmenu != 0) {
																																																																				echo "checked ";
																																																																			} ?> /></td>
									<td align="center"> <input type='checkbox' id="<?php echo "add_mainmenu" . $dwebpages['id_webpages'] . ${'mainmenu' . $dwebpages['webpage_display']}; ?>" name="add_mainmenu[]" class="add_mainmenu" value="<?php echo $dmainmenu['idmain_menu']; ?>" <?php if ($add_akses_mainmenu != 0) {
																																																																						echo "checked ";
																																																																					} ?> /></td>
									<td align="center"> <input type='checkbox' id="<?php echo "update_mainmenu" . $dwebpages['id_webpages'] . ${'mainmenu' . $dwebpages['webpage_display']}; ?>" name="update_mainmenu[]" class="update_mainmenu" value="<?php echo $dmainmenu['idmain_menu']; ?>" <?php if ($update_akses_mainmenu != 0) {
																																																																								echo "checked ";
																																																																							} ?> /></td>
									<td align="center"> <input type='checkbox' id="<?php echo "delete_mainmenu" . $dwebpages['id_webpages'] . ${'mainmenu' . $dwebpages['webpage_display']}; ?>" name="del_mainmenu[]" class="del_mainmenu" value="<?php echo $dmainmenu['idmain_menu']; ?>" <?php if ($del_akses_mainmenu != 0) {
																																																																							echo "checked ";
																																																																						} ?> /></td>
								</tr>
								<?php
								${'submenu' . $dmainmenu['mainmenu_display']} = 1;
								while ($dsubmenu = $submenu->fetch_array()) {
									$cek_akses_submenu = DB::connection('mysql_hris')->query("SELECT * from tbl_hakmenu_submenu where id_submenu = '" . $dsubmenu['id_submenu'] . "' and id_user = '" . $_GET['user_id'] . "'");
									$akses_submenu =  $cek_akses_submenu->num_rows;
									$cek_add_submenu = DB::connection('mysql_hris')->query("SELECT * from tbl_hakmenu_submenu where id_submenu = '" . $dsubmenu['id_submenu'] . "' and id_user = '" . $_GET['user_id'] . "' and `add` = 1");
									$add_akses_submenu =  $cek_add_submenu->num_rows;
									$cek_update_submenu = DB::connection('mysql_hris')->query("SELECT * from tbl_hakmenu_submenu where id_submenu = '" . $dsubmenu['id_submenu'] . "' and id_user = '" . $_GET['user_id'] . "' and `update` = 1");
									$update_akses_submenu =  $cek_update_submenu->num_rows;
									$cek_del_submenu = DB::connection('mysql_hris')->query("SELECT * from tbl_hakmenu_submenu where id_submenu = '" . $dsubmenu['id_submenu'] . "' and id_user = '" . $_GET['user_id'] . "' and `delete` = 1");
									$del_akses_submenu =  $cek_del_submenu->num_rows;
									?>
									<tr class="list_submenu<?php echo $dmainmenu['idmain_menu']; ?> list_submenu">
										<td colspan='3'></td>
										<td style="background-color:#f97c96"><?php echo $dsubmenu['submenu_display']; ?></td>
										<td style="background-color:#f97c96" align="center"> <input type='checkbox' id="<?php echo "view_submenu" . $dmainmenu['idmain_menu'] . ${'submenu' . $dmainmenu['mainmenu_display']}; ?>" name="submenu[]" class="submenu" value="<?php echo $dsubmenu['id_submenu']; ?>" <?php if ($akses_submenu != 0) {
																																																																												echo "checked ";
																																																																											} ?> /></td>
										<td style="background-color:#f97c96" align="center"> <input type='checkbox' id="<?php echo "add_submenu" . $dmainmenu['idmain_menu'] . ${'submenu' . $dmainmenu['mainmenu_display']}; ?>" name="add_submenu[]" class="add_submenu" value="<?php echo $dsubmenu['id_submenu']; ?>" <?php if ($add_akses_submenu != 0) {
																																																																														echo "checked ";
																																																																													} ?> /></td>
										<td style="background-color:#f97c96" align="center"> <input type='checkbox' id="<?php echo "update_submenu" . $dmainmenu['idmain_menu'] . ${'submenu' . $dmainmenu['mainmenu_display']}; ?>" name="update_submenu[]" class="update_submenu" value="<?php echo $dsubmenu['id_submenu']; ?>" <?php if ($update_akses_submenu != 0) {
																																																																																echo "checked ";
																																																																															} ?> /></td>
										<td style="background-color:#f97c96" align="center"> <input type='checkbox' id="<?php echo "delete_submenu" . $dmainmenu['idmain_menu'] . ${'submenu' . $dmainmenu['mainmenu_display']}; ?>" name="del_submenu[]" class="del_submenu" value="<?php echo $dsubmenu['id_submenu']; ?>" <?php if ($del_akses_submenu != 0) {
																																																																															echo "checked ";
																																																																														} ?> /></td>
									</tr>
									<?php ${'submenu' . $dmainmenu['mainmenu_display']}++;
								} ?>
								<?php ${'mainmenu' . $dwebpages['webpage_display']}++;
							} ?>
							<?php $no++;
						} ?>
					</tbody>
				</table>
				<p align="center"><button type="button" class="btn btn-outline btn-primary" onclick="save_hak_akses('<?php echo $_GET['user_id']; ?>')">Save</button>
					<span id="progress" style="display:none"><img src="images/loading.gif" width="20" /> Please Wait...</span>
				</p>
			</div>
		</div>
	</div>
</div>