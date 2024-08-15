<?php 
if (isset($_GET['act']) and $_GET['act'] == 'add_new'):
	include "inc/administrator/add_user.php";
elseif (isset($_GET['act']) and $_GET['act'] == 'edit_user'):
	include "inc/administrator/edit_user.php";
elseif (isset($_GET['act']) and $_GET['act'] == 'detail_user'):
	include "inc/administrator/detail_user.php";
elseif (isset($_GET['act']) and $_GET['act'] == 'change_password'):
	include "inc/administrator/change_password.php";
else:
 ?>
				<script>
					function non_aktif(id_user, nama_user) {
						if (confirm('Are you sure?')) {
							var act = 'non_aktif';
							$.ajax({
								type: "POST",
								url: "inc/administrator/proc_create_user.php",
								data: "id_user=" + id_user + "&act=" + act + "&nama_user=" + nama_user,
								success: function (responseText) {

									alert('User Non Aktif');
									location.href = "?mm=daftar_user";
									//detail pembinaan

									//
								},
								error: function (data) {
									alert(data); $("#progress").hide();
								}
							});
						} else { }
					}
				</script>

				<script type="text/javascript">
					$(document).ready(function () {

					});
					jQuery(function ($) {
						//initiate dataTables plugin
						var myTable =
							$('#dynamic-table')
								//.wrap("<div class='dataTables_borderWrap' />")   //if you are applying horizontal scrolling (sScrollX)
								.DataTable({
									bAutoWidth: false,
									"aoColumns": [
										{ "bSortable": false },
										null, null, null,
										{ "bSortable": false }
									],
									"aaSorting": [],


									//"bProcessing": true,
									//"bServerSide": true,
									//"sAjaxSource": "http://127.0.0.1/table.php"	,

									//,
									//"sScrollY": "200px",
									//"bPaginate": false,

									//"sScrollX": "100%",
									//"sScrollXInner": "120%",
									//"bScrollCollapse": true,
									//Note: if you are applying horizontal scrolling (sScrollX) on a ".table-bordered"
									//you may want to wrap the table inside a "div.dataTables_borderWrap" element

									//"iDisplayLength": 50


									select: {
										style: 'multi'
									}
								});



						//$.fn.dataTable.Buttons.defaults.dom.container.className = 'dt-buttons btn-overlap btn-group btn-overlap';



					})
				</script>
				<div class="main-content-inner">
					<div class="breadcrumbs ace-save-state" id="breadcrumbs">
						<ul class="breadcrumb">
							<li>
								<i class="ace-icon fa fa-home home-icon"></i>
								<a href="?page=dashboard">Home</a>
							</li>
							<li class="active">List user</li>
						</ul>
					</div>
					<div class="page-content">
						<div class="page-header">
							<h1>
								List User<small>/ <a href="?mm=daftar_user&act=add_new" title="Create User"> Add User</a></small>
							</h1>
						</div>
						<div class="row">
							<div class="col-xs-12">
								<div>
									<table id="dynamic-table" class="table table-striped table-bordered table-hover">
										<thead>
											<tr>
												<th style="width:25px;">#</th>
												<th>Nama</th>
												<th>User Level</th>
												<th>Status</th>
												<th class="center">Action</th>

											</tr>
										</thead>
										<tbody>
								<?php
								$user = DB::connection('mysql_hris')->query("select * from user order by nama asc");
								$no = 1;
								while ($duser = $user->fetch_array()):
									?>
												<tr>
													<td><?php echo $no; ?>. </td>
													<td><a
															href="?mm=daftar_user&act=edit_user&id_user=<?php echo $duser['id_user']; ?>"><?php echo $duser['nama']; ?></a>
													</td>
													<td><?php echo $duser['user_level']; ?></td>
													<td><?php echo $duser['user_status']; ?></td>
													<td class="center">
														<div class="btn-group">
															<button data-toggle="dropdown" class="btn dropdown-toggle">
																Act
																<span class="ace-icon fa fa-caret-down icon-on-right"></span>
															</button>
															<ul class="dropdown-menu dropdown-default">
																<li><a href="?mm=daftar_user&act=detail_user&id_user=<?php echo $duser['id_user']; ?>">Detail User</a></li>
																<li class="divider"></li>
																<li><a href="#" onclick="non_aktif('<?php echo $duser['id_user']; ?>','<?php echo $duser['nama']; ?>')">Non Aktifkan User</a></li>
															</ul>
														</div>
													</td>
												</tr>
								<?php 
									$no++;
								endwhile; 
								?>
										</tbody>
									</table>
								</div>
							</div>
						</div>
					</div>
				</div>
<?php endif; ?>