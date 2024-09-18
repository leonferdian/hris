<script>
	$(document).ready(function () {
		// if (!ace.vars['touch']) {
		// 	$('.chosen-select').chosen({
		// 		allow_single_deselect: true
		// 	});
		// }
		// $('.chosen-select').chosen();
		// $(window)
		// 	.off('resize.chosen')
		// 	.on('resize.chosen', function () {
		// 		$('.chosen-select').each(function () {
		// 			var $this = $(this);
		// 			$this.next().css({
		// 				'width': '100%'
		// 			});
		// 		})
		// 	}).trigger('resize.chosen');

		$('.chosen-select').css('width', '270px').select2({ allowClear: true })
        .on('change', function () {
            // $(this).closest('form').validate().element($(this));
        });

		$('#id-input-file-2').ace_file_input({
			no_file: 'No File ...',
			btn_choose: 'Choose',
			btn_change: 'Change',
			droppable: false,
			onchange: null,
			thumbnail: false //| true | large
			//whitelist:'gif|png|jpg|jpeg'
			//blacklist:'exe|php'
			//onchange:''
			//
		});

		//editables on first profile page
		$.fn.editable.defaults.mode = 'inline';
		$.fn.editableform.loading = "<div class='editableform-loading'><i class='ace-icon fa fa-spinner fa-spin fa-2x light-blue'></i></div>";
		$.fn.editableform.buttons = '<button type="submit" class="btn btn-info editable-submit"><i class="ace-icon fa fa-check"></i></button>' +
			'<button type="button" class="btn editable-cancel"><i class="ace-icon fa fa-times"></i></button>';

		//editables 

		//text editable
		$('#username')
			.editable({
				type: 'text',
				name: 'username'
			});
		//select2 editable
		var countries = [];
		$.each({
			"CA": "Canada",
			"IN": "India",
			"NL": "Netherlands",
			"TR": "Turkey",
			"US": "United States"
		}, function (k, v) {
			countries.push({
				id: k,
				text: v
			});
		});

		var cities = [];
		cities["CA"] = [];
		$.each(["Toronto", "Ottawa", "Calgary", "Vancouver"], function (k, v) {
			cities["CA"].push({
				id: v,
				text: v
			});
		});
		cities["IN"] = [];
		$.each(["Delhi", "Mumbai", "Bangalore"], function (k, v) {
			cities["IN"].push({
				id: v,
				text: v
			});
		});
		cities["NL"] = [];
		$.each(["Amsterdam", "Rotterdam", "The Hague"], function (k, v) {
			cities["NL"].push({
				id: v,
				text: v
			});
		});
		cities["TR"] = [];
		$.each(["Ankara", "Istanbul", "Izmir"], function (k, v) {
			cities["TR"].push({
				id: v,
				text: v
			});
		});
		cities["US"] = [];
		$.each(["New York", "Miami", "Los Angeles", "Chicago", "Wysconsin"], function (k, v) {
			cities["US"].push({
				id: v,
				text: v
			});
		});

		var currentValue = "NL";
		$('#country').editable({
			type: 'select2',
			value: 'NL',
			//onblur:'ignore',
			source: countries,
			select2: {
				'width': 140
			},
			success: function (response, newValue) {
				if (currentValue == newValue) return;
				currentValue = newValue;

				var new_source = (!newValue || newValue == "") ? [] : cities[newValue];

				//the destroy method is causing errors in x-editable v1.4.6+
				//it worked fine in v1.4.5
				/**			
				$('#city').editable('destroy').editable({
					type: 'select2',
					source: new_source
				}).editable('setValue', null);
				*/

				//so we remove it altogether and create a new element
				var city = $('#city').removeAttr('id').get(0);
				$(city).clone().attr('id', 'city').text('Select City').editable({
					type: 'select2',
					value: null,
					//onblur:'ignore',
					source: new_source,
					select2: {
						'width': 140
					}
				}).insertAfter(city); //insert it after previous instance
				$(city).remove(); //remove previous instance

			}
		});

		$('#city').editable({
			type: 'select2',
			value: 'Amsterdam',
			//onblur:'ignore',
			source: cities[currentValue],
			select2: {
				'width': 140
			}
		});



		//custom date editable
		$('#signup').editable({
			type: 'adate',
			date: {
				//datepicker plugin options
				format: 'yyyy/mm/dd',
				viewformat: 'yyyy/mm/dd',
				weekStart: 1

				//,nativeUI: true//if true and browser support input[type=date], native browser control will be used
				//,format: 'yyyy-mm-dd',
				//viewformat: 'yyyy-mm-dd'
			}
		})

		$('#age').editable({
			type: 'spinner',
			name: 'age',
			spinner: {
				min: 16,
				max: 99,
				step: 1,
				on_sides: true
				//,nativeUI: true//if true and browser support input[type=number], native browser control will be used
			}
		});


		$('#login').editable({
			type: 'slider',
			name: 'login',

			slider: {
				min: 1,
				max: 50,
				width: 100
				//,nativeUI: true//if true and browser support input[type=range], native browser control will be used
			},
			success: function (response, newValue) {
				if (parseInt(newValue) == 1)
					$(this).html(newValue + " hour ago");
				else $(this).html(newValue + " hours ago");
			}
		});

		$('#about').editable({
			mode: 'inline',
			type: 'wysiwyg',
			name: 'about',

			wysiwyg: {
				//css : {'max-width':'300px'}
			},
			success: function (response, newValue) { }
		});



		// *** editable avatar *** //
		try { //ie8 throws some harmless exceptions, so let's catch'em

			//first let's add a fake appendChild method for Image element for browsers that have a problem with this
			//because editable plugin calls appendChild, and it causes errors on IE at unpredicted points
			try {
				document.createElement('IMG').appendChild(document.createElement('B'));
			} catch (e) {
				Image.prototype.appendChild = function (el) { }
			}

			var nik = $('#nik').val();

			var last_gritter
			$('#avatar').editable({
				type: 'image',
				name: 'avatar',
				value: null,
				//onblur: 'ignore',  //don't reset or hide editable onblur?!
				image: {
					//specify ace file input plugin's options here
					btn_choose: 'Change Avatar',
					droppable: true,
					maxSize: 1100000, //~1000Kb

					//and a few extra ones here
					name: 'avatar', //put the field name here as well, will be used inside the custom plugin
					on_error: function (error_type) { //on_error function will be called when the selected file has a problem
						if (last_gritter) $.gritter.remove(last_gritter);
						if (error_type == 1) { //file format error
							last_gritter = $.gritter.add({
								title: 'File is not an image!',
								text: 'Please choose a jpg|gif|png image!',
								class_name: 'gritter-error gritter-center'
							});
						} else if (error_type == 2) { //file size rror
							last_gritter = $.gritter.add({
								title: 'File too big!',
								// text: 'Image size should not exceed 100Kb!',
								text: 'Image size should not exceed 1MB!',
								class_name: 'gritter-error gritter-center'
							});
						} else { //other error
						}
					},
					on_success: function () {
						$.gritter.removeAll();
					}
				},
				url: function (params) {
					// ***UPDATE AVATAR HERE*** //
					//for a working upload example you can replace the contents of this function with 
					//examples/profile-avatar-update.js

					var deferred = new $.Deferred

					var value = $('#avatar').next().find('input[type=hidden]:eq(0)').val();
					if (!value || value.length == 0) {
						deferred.resolve();
						return deferred.promise();
					}


					//dummy upload
					setTimeout(function () {
						if ("FileReader" in window) {
							//for browsers that have a thumbnail of selected image
							var thumb = $('#avatar').next().find('img').data('thumb');
							if (thumb) $('#avatar').get(0).src = thumb;
						}

						deferred.resolve({
							'status': 'OK'
						});

						if (last_gritter) $.gritter.remove(last_gritter);
						last_gritter = $.gritter.add({
							title: 'Avatar Updated!',
							text: 'Success upload profile photo.',
							class_name: 'gritter-info gritter-center'
						});

					}, parseInt(Math.random() * 800 + 800))

					return deferred.promise();

					// ***END OF UPDATE AVATAR HERE*** //
				},

				success: function (response, newValue) {
					// ***Custome Upload*** //	
					upload_avatar();
				}
			})
		} catch (e) { }

		try { //ie8 throws some harmless exceptions, so let's catch'em

			//first let's add a fake appendChild method for Image element for browsers that have a problem with this
			//because editable plugin calls appendChild, and it causes errors on IE at unpredicted points
			try {
				document.createElement('IMG').appendChild(document.createElement('B'));
			} catch (e) {
				Image.prototype.appendChild = function (el) { }
			}

			var nik = $('#nik').val();

			var last_gritter
			$('#foto_ktp').editable({
				type: 'image',
				name: 'foto_ktp',
				value: null,
				//onblur: 'ignore',  //don't reset or hide editable onblur?!
				image: {
					//specify ace file input plugin's options here
					btn_choose: 'Change Photo',
					droppable: true,
					maxSize: 1100000, //~1000Kb

					//and a few extra ones here
					name: 'foto_ktp', //put the field name here as well, will be used inside the custom plugin
					on_error: function (error_type) { //on_error function will be called when the selected file has a problem
						if (last_gritter) $.gritter.remove(last_gritter);
						if (error_type == 1) { //file format error
							last_gritter = $.gritter.add({
								title: 'File is not an image!',
								text: 'Please choose a jpg|gif|png image!',
								class_name: 'gritter-error gritter-center'
							});
						} else if (error_type == 2) { //file size rror
							last_gritter = $.gritter.add({
								title: 'File too big!',
								// text: 'Image size should not exceed 100Kb!',
								text: 'Image size should not exceed 1MB!',
								class_name: 'gritter-error gritter-center'
							});
						} else { //other error
						}
					},
					on_success: function () {
						$.gritter.removeAll();
					}
				},
				url: function (params) {
					// ***UPDATE AVATAR HERE*** //
					//for a working upload example you can replace the contents of this function with 
					//examples/profile-avatar-update.js

					var deferred = new $.Deferred

					var value = $('#foto_ktp').next().find('input[type=hidden]:eq(0)').val();
					if (!value || value.length == 0) {
						deferred.resolve();
						return deferred.promise();
					}


					//dummy upload
					setTimeout(function () {
						if ("FileReader" in window) {
							//for browsers that have a thumbnail of selected image
							var thumb = $('#foto_ktp').next().find('img').data('thumb');
							if (thumb) $('#foto_ktp').get(0).src = thumb;
						}

						deferred.resolve({
							'status': 'OK'
						});

						if (last_gritter) $.gritter.remove(last_gritter);
						last_gritter = $.gritter.add({
							title: 'Foto KTP Updated!',
							text: 'Success upload profile photo.',
							class_name: 'gritter-info gritter-center'
						});

					}, parseInt(Math.random() * 800 + 800))

					return deferred.promise();

					// ***END OF UPDATE AVATAR HERE*** //
				},

				success: function (response, newValue) {
					// ***Custome Upload*** //	
					upload_ktp();
				}
			})
		} catch (e) { }

		try { //ie8 throws some harmless exceptions, so let's catch'em

			//first let's add a fake appendChild method for Image element for browsers that have a problem with this
			//because editable plugin calls appendChild, and it causes errors on IE at unpredicted points
			try {
				document.createElement('IMG').appendChild(document.createElement('B'));
			} catch (e) {
				Image.prototype.appendChild = function (el) { }
			}

			var nik = $('#nik').val();

			var last_gritter
			$('#foto_sim').editable({
				type: 'image',
				name: 'foto_sim',
				value: null,
				//onblur: 'ignore',  //don't reset or hide editable onblur?!
				image: {
					//specify ace file input plugin's options here
					btn_choose: 'Change Photo',
					droppable: true,
					maxSize: 1100000, //~1000Kb

					//and a few extra ones here
					name: 'foto_sim', //put the field name here as well, will be used inside the custom plugin
					on_error: function (error_type) { //on_error function will be called when the selected file has a problem
						if (last_gritter) $.gritter.remove(last_gritter);
						if (error_type == 1) { //file format error
							last_gritter = $.gritter.add({
								title: 'File is not an image!',
								text: 'Please choose a jpg|gif|png image!',
								class_name: 'gritter-error gritter-center'
							});
						} else if (error_type == 2) { //file size rror
							last_gritter = $.gritter.add({
								title: 'File too big!',
								// text: 'Image size should not exceed 100Kb!',
								text: 'Image size should not exceed 1MB!',
								class_name: 'gritter-error gritter-center'
							});
						} else { //other error
						}
					},
					on_success: function () {
						$.gritter.removeAll();
					}
				},
				url: function (params) {
					// ***UPDATE AVATAR HERE*** //
					//for a working upload example you can replace the contents of this function with 
					//examples/profile-avatar-update.js

					var deferred = new $.Deferred

					var value = $('#foto_sim').next().find('input[type=hidden]:eq(0)').val();
					if (!value || value.length == 0) {
						deferred.resolve();
						return deferred.promise();
					}


					//dummy upload
					setTimeout(function () {
						if ("FileReader" in window) {
							//for browsers that have a thumbnail of selected image
							var thumb = $('#foto_sim').next().find('img').data('thumb');
							if (thumb) $('#foto_sim').get(0).src = thumb;
						}

						deferred.resolve({
							'status': 'OK'
						});

						if (last_gritter) $.gritter.remove(last_gritter);
						last_gritter = $.gritter.add({
							title: 'Foto SIM Updated!',
							text: 'Success upload profile photo.',
							class_name: 'gritter-info gritter-center'
						});

					}, parseInt(Math.random() * 800 + 800))

					return deferred.promise();

					// ***END OF UPDATE AVATAR HERE*** //
				},

				success: function (response, newValue) {
					// ***Custome Upload*** //	
					upload_sim();
				}
			})
		} catch (e) { }

		/**
		//let's display edit mode by default?
		var blank_image = true;//somehow you determine if image is initially blank or not, or you just want to display file input at first
		if(blank_image) {
			$('#avatar').editable('show').on('hidden', function(e, reason) {
				if(reason == 'onblur') {
					$('#avatar').editable('show');
					return;
				}
				$('#avatar').off('hidden');
			})
		}
		*/

		//another option is using modals
		$('#avatar2').on('click', function () {
			var modal =
				'<div class="modal fade">\
					  <div class="modal-dialog">\
					   <div class="modal-content">\
						<div class="modal-header">\
							<button type="button" class="close" data-dismiss="modal">&times;</button>\
							<h4 class="blue">Change Avatar</h4>\
						</div>\
						\
						<form class="no-margin">\
						 <div class="modal-body">\
							<div class="space-4"></div>\
							<div style="width:75%;margin-left:12%;"><input type="file" name="file-input" /></div>\
						 </div>\
						\
						 <div class="modal-footer center">\
							<button type="submit" class="btn btn-sm btn-success"><i class="ace-icon fa fa-check"></i> Submit</button>\
							<button type="button" class="btn btn-sm" data-dismiss="modal"><i class="ace-icon fa fa-times"></i> Cancel</button>\
						 </div>\
						</form>\
					  </div>\
					 </div>\
					</div>';


			var modal = $(modal);
			modal.modal("show").on("hidden", function () {
				modal.remove();
			});

			var working = false;

			var form = modal.find('form:eq(0)');
			var file = form.find('input[type=file]').eq(0);
			file.ace_file_input({
				style: 'well',
				btn_choose: 'Click to choose new avatar',
				btn_change: null,
				no_icon: 'ace-icon fa fa-picture-o',
				thumbnail: 'small',
				before_remove: function () {
					//don't remove/reset files while being uploaded
					return !working;
				},
				allowExt: ['jpg', 'jpeg', 'png', 'gif'],
				allowMime: ['image/jpg', 'image/jpeg', 'image/png', 'image/gif']
			});

			form.on('submit', function () {
				if (!file.data('ace_input_files')) return false;

				file.ace_file_input('disable');
				form.find('button').attr('disabled', 'disabled');
				form.find('.modal-body').append("<div class='center'><i class='ace-icon fa fa-spinner fa-spin bigger-150 orange'></i></div>");

				var deferred = new $.Deferred;
				working = true;
				deferred.done(function () {
					form.find('button').removeAttr('disabled');
					form.find('input[type=file]').ace_file_input('enable');
					form.find('.modal-body > :last-child').remove();

					modal.modal("hide");

					var thumb = file.next().find('img').data('thumb');
					if (thumb) $('#avatar2').get(0).src = thumb;

					working = false;
				});


				setTimeout(function () {
					deferred.resolve();
				}, parseInt(Math.random() * 800 + 800));

				return false;
			});

		});



		//////////////////////////////
		$('#profile-feed-1').ace_scroll({
			height: '250px',
			mouseWheelLock: true,
			alwaysVisible: true
		});

		$('a[ data-original-title]').tooltip();

		$('.easy-pie-chart.percentage').each(function () {
			var barColor = $(this).data('color') || '#555';
			var trackColor = '#E2E2E2';
			var size = parseInt($(this).data('size')) || 72;
			$(this).easyPieChart({
				barColor: barColor,
				trackColor: trackColor,
				scaleColor: false,
				lineCap: 'butt',
				lineWidth: parseInt(size / 10),
				animate: false,
				size: size
			}).css('color', barColor);
		});

		///////////////////////////////////////////

		//right & left position
		//show the user info on right or left depending on its position
		$('#user-profile-2 .memberdiv').on('mouseenter touchstart', function () {
			var $this = $(this);
			var $parent = $this.closest('.tab-pane');

			var off1 = $parent.offset();
			var w1 = $parent.width();

			var off2 = $this.offset();
			var w2 = $this.width();

			var place = 'left';
			if (parseInt(off2.left) < parseInt(off1.left) + parseInt(w1 / 2)) place = 'right';

			$this.find('.popover').removeClass('right left').addClass(place);
		}).on('click', function (e) {
			e.preventDefault();
		});


		///////////////////////////////////////////
		$('#user-profile-3')
			.find('input[type=file]').ace_file_input({
				style: 'well',
				btn_choose: 'Change avatar',
				btn_change: null,
				no_icon: 'ace-icon fa fa-picture-o',
				thumbnail: 'large',
				droppable: true,

				allowExt: ['jpg', 'jpeg', 'png', 'gif'],
				allowMime: ['image/jpg', 'image/jpeg', 'image/png', 'image/gif']
			})
			.end().find('button[type=reset]').on(ace.click_event, function () {
				$('#user-profile-3 input[type=file]').ace_file_input('reset_input');
			})
			.end().find('.date-picker').datepicker().next().on(ace.click_event, function () {
				$(this).prev().focus();
			})

		$('.input-mask-phone').mask('(999) 999-9999');

		$('#user-profile-3').find('input[type=file]').ace_file_input('show_file_list', [{
			type: 'image',
			name: $('#avatar').attr('src')
		}]);


		////////////////////
		//change profile
		$('[data-toggle="buttons"] .btn').on('click', function (e) {
			var target = $(this).find('input[type=radio]');
			var which = parseInt(target.val());
			$('.user-profile').parent().addClass('hide');
			$('#user-profile-' + which).parent().removeClass('hide');
		});



		/////////////////////////////////////
		$(document).one('ajaxloadstart.page', function (e) {
			//in ajax mode, remove remaining elements before leaving page
			try {
				$('.editable').editable('destroy');
			} catch (e) { }
			$('[class*=select2]').remove();
		});

		$('.input-mask-tahun').mask('9999');
		$('.input-mask-bulan').mask('9999');
		$('.input-mask-npwp').mask('99.999.999.9-999.999 ');

		$('#editor2').css({ 'height': '200px' }).ace_wysiwyg({
                toolbar_place: function (toolbar) {
                    return $(this).closest('.widget-box')
                        .find('.widget-header').prepend(toolbar)
                        .find('.wysiwyg-toolbar').addClass('inline');
                },
                toolbar:
                    [
                        'bold',
                        { name: 'italic', title: 'Change Title!', icon: 'ace-icon fa fa-leaf' },
                        'strikethrough',
                        null,
                        'insertunorderedlist',
                        'insertorderedlist',
                        null,
                        'justifyleft',
                        'justifycenter',
                        'justifyright'
                    ],
                speech_button: false
            });

		<?php if ($_SESSION['user_level'] != "Administrator"): ?>
			reject_access();
		<?php endif; ?>
	});

	function reject_access() {
		alert('Page Not Available');
		location.href = '?page=dashboard';
	}
</script>
<style>
	.input-xs {
		height: 22px !important;
		padding: 2px 5px;
		font-size: 12px;
		line-height: 1.5;
		border-radius: 3px;
	}
</style>
<style>
	.img-responsive {
		width: 150px;
		height: 170px;
	}

	.img-identity {
		width: 300px;
		height: 170px;
	}

	.editable-input.editable-image {
		width: 150px !important;
	}
</style>
<?php
$sqlsrv_hris = DB::connection('sqlsrv_hris');

if ($_GET['act'] == "edit_karyawan" || $_GET['act'] == "detail_karyawan"):
	$sql_cek = "SELECT [status_data] FROM [db_hris].[dbo].[table_karyawan_draft] WHERE nik = '" . $_GET['nik'] . "'";
	$stmt_cek = $sqlsrv_hris->query($sql_cek);
	$row_cek = $sqlsrv_hris->fetch_array($stmt_cek);

	$sql = "SELECT 
			a.*
			,b.[foto_profile]
			,b.[foto_ktp]
			,b.[foto_sim]
			,c.[status_data]
			,c.[alasan_reject]
			,c.[alasan_reject_hrd]
			,c.[note_update]
			FROM [db_hris].[dbo].[table_karyawan] a
			LEFT JOIN [db_hris].[dbo].[table_karyawan_foto] b
			ON a.nik = b.nik
			LEFT JOIN [db_hris].[dbo].[table_karyawan_draft] c
			ON a.nik = c.nik
			WHERE a.nik = '" . $_GET['nik'] . "'";

	if ($row_cek['status_data'] != "complete"):
		$sql = "SELECT 
			a.*
			,b.[foto_profile]
			,b.[foto_ktp]
			,b.[foto_sim]
			FROM [db_hris].[dbo].[table_karyawan_draft] a
			LEFT JOIN [db_hris].[dbo].[table_karyawan_foto_draft] b
			ON a.nik = b.nik
			WHERE a.nik = '" . $_GET['nik'] . "'";
	endif;
	
	$stmt = $sqlsrv_hris->query($sql);
	$row = $sqlsrv_hris->fetch_array($stmt);
endif;
?>
<div class="main-content-inner">
	<div class="breadcrumbs ace-save-state" id="breadcrumbs">
		<ul class="breadcrumb">
			<li>
				<i class="ace-icon fa fa-home home-icon"></i>
				<a href="?page=dashboard">Home</a>
			</li>
			<li class="active">Data Karyawan</li>
		</ul>
	</div>
	<div class="page-content">
		<div class="page-header">
			<div class="pull-right">
				<h4>Status Data: <?php echo $row['status_data']; ?></h4>
			</div>
			<h1 style="text-align: left;font-weight: bold; color:#646464">
				Form Data Karyawan
			</h1>
		</div>
		<div class="row">
			<div class="col-xs-12">
				<div class="col-xs-12 col-sm-12 pricing-box">
					<div class="widget-box widget-color-purple">
						<div class="widget-header">
							<h3 class="widget-title bigger lighter">
								Informasi Identitas
							</h3>
						</div>
						<div class="widget-body">
							<div class="widget-main">
								<div class="row">
									<div class="col-xs-12">
										<div class="widget-body">
											<div class="col-md-4 widget_1_box2" style="">
												<form class="form-horizontal">
													<div class="form-group">
														<label class="col-sm-4 control-label">Profile Picture</label>
														<span class="profile-picture">
															<?php if ($_GET['act'] == "edit_karyawan" || $_GET['act'] == "detail_karyawan"): ?>
																<?php if ($row['foto_profile'] != ""): ?>
																	<br>
																	<img id="avatar" class="editable img-responsive"
																		alt="Alex's Avatar"
																		src="image_upload/foto_profile/<?php echo $row['foto_profile']; ?>" />
																<?php else: ?>
																	<img id="avatar" class="editable img-responsive"
																		alt="Alex's Avatar"
																		src="assets/images/avatars/profile-pic.jpg" />
																<?php endif; ?>
															<?php else: ?>
																<img id="avatar" class="editable img-responsive" alt="Alex's Avatar" src="assets/images/avatars/profile-pic.jpg" />
															<?php endif; ?>
														</span>
													</div>
												</form>
											</div>
											<div class="col-md-4 widget_1_box2" style="">
												<form class="form-horizontal"></form>
													<div class="form-group">
														<label class="col-sm-4 control-label">Foto KTP</label>
														<span class="foto-ktp">
															<?php if ($_GET['act'] == "edit_karyawan" || $_GET['act'] == "detail_karyawan"): ?>
																<?php if ($row['foto_ktp'] != ""): ?>
																	<img id="foto_ktp" class="editable img-identity"
																		alt="Alex's Avatar"
																		src="image_upload/foto_ktp/<?php echo $row['foto_ktp']; ?>" />
																<?php else: ?>
																	<img id="foto_ktp" class="editable img-responsive"
																		alt="Alex's Avatar"
																		src="assets/images/avatars/profile-pic.jpg" />
																<?php endif; ?>
															<?php else: ?>
																<img id="foto_ktp" class="editable img-responsive" alt="Alex's Avatar" src="assets/images/avatars/profile-pic.jpg" />
															<?php endif; ?>
														</span>
													</div>
												</form>
											</div>
											<div class="col-md-4 widget_1_box2" style="">
												<form class="form-horizontal"></form>
													<div class="form-group">
														<label class="col-sm-4 control-label">Foto SIM</label>
														<span class="foto-sim">
															<?php if ($_GET['act'] == "edit_karyawan" || $_GET['act'] == "detail_karyawan"): ?>
																<?php if ($row['foto_sim'] != ""): ?>
																	<img id="foto_sim" class="editable img-identity"
																		alt="Alex's Avatar"
																		src="image_upload/foto_sim/<?php echo $row['foto_sim']; ?>" />
																<?php else: ?>
																	<img id="foto_sim" class="editable img-responsive"
																		alt="Alex's Avatar"
																		src="assets/images/avatars/profile-pic.jpg" />
																<?php endif; ?>
															<?php else: ?>
																<img id="foto_sim" class="editable img-responsive" alt="Alex's Avatar" src="assets/images/avatars/profile-pic.jpg" />
															<?php endif; ?>
														</span>
													</div>
												</form>
											</div>
										</div>
									</div>
								</div>
							</div>
						</div>
					</div>
				</div>
			</div>
		</div>
		<div class="row">
			<div class="col-xs-12">
				<div class="col-xs-12 col-sm-12 pricing-box">
					<div class="widget-box widget-color-orange">
						<div class="widget-header">
							<h3 class="widget-title bigger lighter">
								Informasi Umum Karyawan
							</h3>
						</div>
						<div class="widget-body">
							<div class="widget-main">
								<div class="row">
									<div class="col-xs-12">
										<div class="widget-body">
											<div class="col-md-4 widget_1_box2" style="">
												<form class="form-horizontal">
													<div class="form-group">
														<label for="nama_pt" class="col-sm-4 control-label">Nama PT</label>
														<div class="col-sm-8">
															<select class="form-control input-sm chosen-select"
																name="nama_pt" id="nama_pt"
																data-placeholder="Choose a State..." onchange="" <?php echo  $_GET['act'] == "detail_karyawan" ? "disabled" : ""; ?>>
																<?php
																$sql_pt = "SELECT * FROM [db_hris].[dbo].[table_master_pt] ORDER BY nama_pt asc";
																$stmt_pt = $sqlsrv_hris->query($sql_pt);
																while ($row_pt = $sqlsrv_hris->fetch_array($stmt_pt)):
																	$selected_pt = isset($row['nama_pt']) && $row_pt['nama_pt'] == $row['nama_pt'] ? " selected" : "";
																	echo '<option value="' . $row_pt['nama_pt'] . '" ' . $selected_pt . '>' . $row_pt['nama_pt'] . '</option>';
																endwhile;
																?>
															</select>
														</div>
													</div>
													<div class="form-group">
														<label for="entity"
															class="col-sm-4 control-label">Entity</label>
														<div class="col-sm-8">
															<select class="form-control input-sm chosen-select"
																name="entity" id="entity"
																data-placeholder="Choose a State..." onchange="" <?php echo  $_GET['act'] == "detail_karyawan" ? "disabled" : ""; ?>>
																<?php
																$sql_entity = "SELECT * FROM [db_hris].[dbo].[table_master_entity] ORDER BY nama_entity asc";
																$stmt_entity = $sqlsrv_hris->query($sql_entity);
																while ($row_entity = sqlsrv_fetch_array($stmt_entity)):
																	$selected_entity = isset($row['entity']) && $row_entity['nama_entity'] == $row['entity'] ? " selected" : "";
																	echo '<option value="' . $row_entity['nama_entity'] . '" ' . $selected_entity . '>' . $row_entity['nama_entity'] . '</option>';
																endwhile;
																?>
															</select>
														</div>
													</div>
													<div class="form-group">
														<label for="depo" class="col-sm-4 control-label">Depo</label>
														<div class="col-sm-8">
															<select class="form-control input-sm chosen-select"
																name="depo" id="depo"
																data-placeholder="Choose a State..." onchange="" <?php echo  $_GET['act'] == "detail_karyawan" ? "disabled" : ""; ?>>
																<?php
																$sql_depo = "SELECT cab_name as depo from [db_ftm].[dbo].[cabang]
																					union
																					SELECT pembagian3_nama as depo from [db_fin_pro].[dbo].[pembagian3]";
																$stmt_depo = $sqlsrv_hris->query($sql_depo);
																while ($row_depo = sqlsrv_fetch_array($stmt_depo)):
																	$selected_depo = isset($row['depo']) && trim($row_depo['depo']) == trim($row['depo']) ? " selected" : "";
																	echo '<option value="' . $row_depo['depo'] . '" ' . $selected_depo . '>' . $row_depo['depo'] . '</option>';
																endwhile;
																?>
															</select>
														</div>
													</div>
													<div class="form-group">
														<label for="nama" class="col-sm-4 control-label">Nama</label>
														<div class="col-sm-8">
															<input type="text" class="form-control input-sm" id="nama"
																placeholder="" <?php echo  $_GET['act'] == "detail_karyawan" ? "readonly" : ""; ?>
																value="<?php echo $_GET['act'] == "edit_karyawan" || $_GET['act'] == "detail_karyawan" ? $row['nama'] : ""; ?>">
														</div>
													</div>
													<div class="form-group">
														<label for="pin" class="col-sm-4 control-label">Pin</label>
														<div class="col-sm-8">
															<input type="text" class="form-control input-sm" id="pin"
																placeholder="" <?php echo  $_GET['act'] == "detail_karyawan" ? "readonly" : ""; ?>
																value="<?php echo $_GET['act'] == "edit_karyawan" || $_GET['act'] == "detail_karyawan" ? $row['pin'] : ""; ?>">
														</div>
													</div>
													<div class="form-group">
														<label for="nik" class="col-sm-4 control-label">NIK</label>
														<div class="col-sm-8">
															<input type="text" class="form-control input-sm" id="nik"
																placeholder="" <?php echo  $_GET['act'] == "detail_karyawan" ? "readonly" : ""; ?>
																value="<?php echo $_GET['act'] == "edit_karyawan" || $_GET['act'] == "detail_karyawan" ? $row['nik'] : ""; ?>">
														</div>
													</div>
													<div class="form-group">
														<label for="kategori_karyawan"
															class="col-sm-4 control-label">Kategori Karyawan</label>
														<div class="col-sm-8">
															<input type="text" class="form-control input-sm"
																id="kategori_karyawan" placeholder="" <?php echo  $_GET['act'] == "detail_karyawan" ? "readonly" : ""; ?>
																value="<?php echo $_GET['act'] == "edit_karyawan" || $_GET['act'] == "detail_karyawan" ? $row['kategori_karyawan'] : ""; ?>">
														</div>
													</div>
													<div class="form-group">
														<label for="nama_outsourcing"
															class="col-sm-4 control-label">Nama Outsourcing</label>
														<div class="col-sm-8">
															<input type="text" class="form-control input-sm"
																id="nama_outsourcing" placeholder="" <?php echo  $_GET['act'] == "detail_karyawan" ? "readonly" : ""; ?>
																value="<?php echo $_GET['act'] == "edit_karyawan" || $_GET['act'] == "detail_karyawan" ? $row['nama_outsourcing'] : ""; ?>">
														</div>
													</div>
													<div class="form-group">
														<label for="lama_kerja_th" class="col-sm-4 control-label">Lama Tahun Kerja</label>
														<div class="col-sm-8">
															<input type="text" class="form-control input-sm"
																id="lama_kerja_th" placeholder="" <?php echo  $_GET['act'] == "detail_karyawan" ? "readonly" : ""; ?>
																value="<?php echo $_GET['act'] == "edit_karyawan" || $_GET['act'] == "detail_karyawan" ? $row['lama_kerja_th'] : ""; ?>">
														</div>
													</div>
													<div class="form-group">
														<label for="lama_kerja_bln" class="col-sm-4 control-label">Lama Bulan Kerja</label>
														<div class="col-sm-8">
															<input type="text" class="form-control input-sm"
																id="lama_kerja_bln" placeholder="" <?php echo  $_GET['act'] == "detail_karyawan" ? "readonly" : ""; ?>
																value="<?php echo $_GET['act'] == "edit_karyawan" || $_GET['act'] == "detail_karyawan" ? $row['lama_kerja_bln'] : ""; ?>">
														</div>
													</div>
												</form>
											</div>
											<div class="col-md-4 widget_1_box2" style="">
												<form class="form-horizontal">
													<div class="form-group">
														<label for="status_pkwt" class="col-sm-4 control-label">Status PKWT</label>
														<div class="col-sm-8">
															<input type="text" class="form-control input-sm"
																id="status_pkwt" placeholder="" <?php echo  $_GET['act'] == "detail_karyawan" ? "readonly" : ""; ?>
																value="<?php echo $_GET['act'] == "edit_karyawan" || $_GET['act'] == "detail_karyawan" ? $row['status_pkwt'] : ""; ?>">
														</div>
													</div>
													<div class="form-group">
														<label for="inputPassword3"
															class="col-sm-4 control-label">Status Kontrak kerja</label>
														<div class="col-sm-8">
															<select class="form-control input-sm chosen-select"
																name="status_kontrak_kerja" id="status_kontrak_kerja"
																data-placeholder="Choose a State..." onchange="" <?php echo  $_GET['act'] == "detail_karyawan" ? "disabled" : ""; ?>>
																<option value="PKWTT" <?php echo isset($row['status_kontrak_kerja']) && trim($row['status_kontrak_kerja']) == "PKWTT" ? " selected" : ""; ?>>PKWTT</option>
																<option value="OUTSOURCING" <?php echo isset($row['status_kontrak_kerja']) && trim($row['status_kontrak_kerja']) == "OUTSOURCING" ? " selected" : ""; ?>>OUTSOURCING</option>
																<option value="PKWT" <?php echo isset($row['status_kontrak_kerja']) && trim($row['status_kontrak_kerja']) == "PKWT" ? " selected" : ""; ?>>PKWT</option>
															</select>
														</div>
													</div>
													<div class="form-group">
														<label for="tgl_akhir_pkwt" class="col-sm-4 control-label">Tgl Akhir PKWT</label>
														<div class="col-sm-8">
															<input type="text" class="form-control input-sm datepicker"
																id="tgl_akhir_pkwt" placeholder="" <?php echo  $_GET['act'] == "detail_karyawan" ? "readonly" : ""; ?>
																value="<?php echo isset($row['tgl_akhir_pkwt']) && $row['tgl_akhir_pkwt'] != "" ? date_format($row['tgl_akhir_pkwt'], "d/m/Y") : ""; ?>">
														</div>
													</div>
													<div class="form-group">
														<label for="nama_grade" class="col-sm-4 control-label">Nama Grade</label>
														<div class="col-sm-8">
															<input type="text" class="form-control input-sm"
																id="nama_grade" placeholder="" <?php echo  $_GET['act'] == "detail_karyawan" ? "readonly" : ""; ?>
																value="<?php echo $_GET['act'] == "edit_karyawan" || $_GET['act'] == "detail_karyawan" ? $row['nama_grade'] : ""; ?>">
														</div>
													</div>
													<div class="form-group">
														<label for="sub_grade"
															class="col-sm-4 control-label">Subgrade</label>
														<div class="col-sm-8">
															<input type="text" class="form-control input-sm"
																id="sub_grade" placeholder="" <?php echo  $_GET['act'] == "detail_karyawan" ? "readonly" : ""; ?>
																value="<?php echo $_GET['act'] == "edit_karyawan" || $_GET['act'] == "detail_karyawan" ? $row['sub_grade'] : ""; ?>">
														</div>
													</div>
													<div class="form-group">
														<label for="tahun" class="col-sm-4 control-label">Tahun</label>
														<div class="col-sm-8">
															<input type="text"
																class="form-control input-sm input-mask-tahun"
																id="tahun" placeholder="1999"
																value="<?php echo $_GET['act'] == "edit_karyawan" || $_GET['act'] == "detail_karyawan" ? $row['tahun'] : ""; ?>">
														</div>
													</div>
													<div class="form-group">
														<label for="grade_level" class="col-sm-4 control-label">Grade Level</label>
														<div class="col-sm-8">
															<input type="text" class="form-control input-sm"
																id="grade_level" placeholder="" <?php echo  $_GET['act'] == "detail_karyawan" ? "readonly" : ""; ?>
																value="<?php echo $_GET['act'] == "edit_karyawan" || $_GET['act'] == "detail_karyawan" ? $row['grade_level'] : ""; ?>">
														</div>
													</div>
													<div class="form-group">
														<label for="skema_insentif" class="col-sm-4 control-label">Skema Insentif</label>
														<div class="col-sm-8">
															<input type="text" class="form-control input-sm"
																id="skema_insentif" placeholder="" <?php echo  $_GET['act'] == "detail_karyawan" ? "readonly" : ""; ?>
																value="<?php echo $_GET['act'] == "edit_karyawan" || $_GET['act'] == "detail_karyawan" ? $row['skema_insentif'] : ""; ?>">
														</div>
													</div>
													<div class="form-group">
														<label for="THP" class="col-sm-4 control-label">THP</label>
														<div class="col-sm-8">
															<input type="text" class="form-control input-sm" id="THP"
																placeholder="" <?php echo  $_GET['act'] == "detail_karyawan" ? "readonly" : ""; ?>
																value="<?php echo $_GET['act'] == "edit_karyawan" || $_GET['act'] == "detail_karyawan" ? $row['THP'] : ""; ?>">
														</div>
													</div>
													<div class="form-group">
														<label for="potongan_bpjs_tk"
															class="col-sm-4 control-label">Potongan BPJSTK</label>
														<div class="col-sm-8">
															<input type="text" class="form-control input-sm"
																id="potongan_bpjs_tk" placeholder="" <?php echo  $_GET['act'] == "detail_karyawan" ? "readonly" : ""; ?>
																value="<?php echo $_GET['act'] == "edit_karyawan" || $_GET['act'] == "detail_karyawan" ? $row['potongan_bpjs_tk'] : ""; ?>">
														</div>
													</div>
												</form>
											</div>
											<div class="col-md-4 widget_1_box2" style="">
												<form class="form-horizontal">
													<div class="form-group">
														<label for="potongan_bpjs_kes"
															class="col-sm-4 control-label">Potongan BPJSKES</label>
														<div class="col-sm-8">
															<input type="text" class="form-control input-sm"
																id="potongan_bpjs_kes" placeholder="" <?php echo  $_GET['act'] == "detail_karyawan" ? "readonly" : ""; ?>
																value="<?php echo $_GET['act'] == "edit_karyawan" || $_GET['act'] == "detail_karyawan" ? $row['potongan_bpjs_kes'] : ""; ?>">
														</div>
													</div>
													<div class="form-group">
														<label for="bagian"
															class="col-sm-4 control-label">Bagian</label>
														<div class="col-sm-8">
															<select class="form-control input-sm chosen-select"
																name="bagian" id="bagian"
																data-placeholder="Choose a State..." onchange="" <?php echo  $_GET['act'] == "detail_karyawan" ? "disabled" : ""; ?>>
																<?php
																$sql_bagian = "SELECT * FROM [db_hris].[dbo].[table_master_bagian] ORDER BY nama_bagian asc";
																$stmt_bagian = $sqlsrv_hris->query($sql_bagian);
																while ($row_bagian = sqlsrv_fetch_array($stmt_bagian)):
																	$selected_bagian = isset($row['bagian']) && $row_bagian['nama_bagian'] == trim($row['bagian']) ? " selected" : "";
																	echo '<option value="' . $row_bagian['nama_bagian'] . '" ' . $selected_bagian . '>' . $row_bagian['nama_bagian'] . '</option>';
																endwhile;
																?>
															</select>
														</div>
													</div>
													<div class="form-group">
														<label for="inputPassword3" class="col-sm-4 control-label">Sub Bagian</label>
														<div class="col-sm-8">
															<select class="form-control input-sm chosen-select"
																name="sub_bagian" id="sub_bagian"
																data-placeholder="Choose a State..." onchange="" <?php echo  $_GET['act'] == "detail_karyawan" ? "disabled" : ""; ?>>
																<?php
																$sql_sub_bagian = "SELECT * FROM [db_hris].[dbo].[table_master_sub_bagian] ORDER BY nama_sub_bagian asc";
																$stmt_sub_bagian = $sqlsrv_hris->query($sql_sub_bagian);
																while ($row_sub_bagian = sqlsrv_fetch_array($stmt_sub_bagian)):
																	$selected_sub_bagian = isset($row['sub_bagian']) && $row_sub_bagian['nama_sub_bagian'] == trim($row['sub_bagian']) ? " selected" : "";
																	echo '<option value="' . $row_sub_bagian['nama_sub_bagian'] . '" ' . $selected_sub_bagian . '>' . $row_sub_bagian['nama_sub_bagian'] . '</option>';
																endwhile;
																?>
															</select>
														</div>
													</div>
													<div class="form-group">
														<label for="seksi" class="col-sm-4 control-label">Seksi</label>
														<div class="col-sm-8">
															<select class="form-control input-sm chosen-select"
																name="seksi" id="seksi"
																data-placeholder="Choose a State..." onchange="" <?php echo  $_GET['act'] == "detail_karyawan" ? "disabled" : ""; ?>>
																<?php
																$sql_seksi = "SELECT * FROM [db_hris].[dbo].[table_master_seksi] ORDER BY nama_seksi asc";
																$stmt_seksi = $sqlsrv_hris->query($sql_seksi);
																while ($row_seksi = sqlsrv_fetch_array($stmt_seksi)):
																	$selected_seksi = isset($row['seksi']) && $row_seksi['nama_seksi'] == trim($row['seksi']) ? " selected" : "";
																	echo '<option value="' . $row_seksi['nama_seksi'] . '" ' . $selected_seksi . '>' . $row_seksi['nama_seksi'] . '</option>';
																endwhile;
																?>
															</select>
														</div>
													</div>
													<div class="form-group">
														<label for="jabatan"
															class="col-sm-4 control-label">Jabatan</label>
														<div class="col-sm-8">
															<select class="form-control input-sm chosen-select"
																name="jabatan" id="jabatan"
																data-placeholder="Choose a State..." onchange="" <?php echo  $_GET['act'] == "detail_karyawan" ? "disabled" : ""; ?>>
																<?php
																$sql_jabatan = "SELECT * FROM [db_hris].[dbo].[table_master_jabatan] ORDER BY nama_jabatan asc";
																$stmt_jabatan = $sqlsrv_hris->query($sql_jabatan);
																while ($row_jabatan = sqlsrv_fetch_array($stmt_jabatan)):
																	$selected_jabatan = isset($row['jabatan']) && $row_jabatan['nama_jabatan'] == trim($row['jabatan']) ? " selected" : "";
																	echo '<option value="' . $row_jabatan['nama_jabatan'] . '" ' . $selected_jabatan . '>' . $row_jabatan['nama_jabatan'] . '</option>';
																endwhile;
																?>
															</select>
														</div>
													</div>
													<div class="form-group">
														<label for="divisi"
															class="col-sm-4 control-label">Divisi</label>
														<div class="col-sm-8">
															<select class="form-control input-sm chosen-select"
																name="divisi" id="divisi"
																data-placeholder="Choose a State..." onchange="" <?php echo  $_GET['act'] == "detail_karyawan" ? "disabled" : ""; ?>>
																<?php
																$sql_divisi = "SELECT * FROM [db_hris].[dbo].[table_master_divisi] ORDER BY nama_divisi asc";
																$stmt_divisi = $sqlsrv_hris->query($sql_divisi);
																while ($row_divisi = sqlsrv_fetch_array($stmt_divisi)):
																	$selected_divisi = isset($row['divisi']) && $row_divisi['nama_divisi'] == trim($row['divisi']) ? "selected" : "";
																	echo '<option value="' . $row_divisi['nama_divisi'] . '" ' . $selected_divisi . '>' . $row_divisi['nama_divisi'] . '</option>';
																endwhile;
																?>
															</select>
														</div>
													</div>
													<div class="form-group">
														<label for="brand"
															class="col-sm-4 control-label">Brand</label>
														<div class="col-sm-8">
															<select class="form-control input-sm chosen-select"
																name="brand" id="brand"
																data-placeholder="Choose a State..." onchange="" <?php echo  $_GET['act'] == "detail_karyawan" ? "disabled" : ""; ?>>
																<?php
																$sql_brand = "SELECT * FROM [db_hris].[dbo].[table_master_brand] ORDER BY nama_brand asc";
																$stmt_brand = $sqlsrv_hris->query($sql_brand);
																while ($row_brand = sqlsrv_fetch_array($stmt_brand)):
																	$selected_brand = isset($row['brand']) && $row_brand['nama_brand'] == trim($row['brand']) ? " selected" : "";
																	echo '<option value="' . $row_brand['nama_brand'] . '" ' . $selected_brand . '>' . $row_brand['nama_brand'] . '</option>';
																endwhile;
																?>
															</select>
														</div>
													</div>
													<div class="form-group">
														<label for="active"
															class="col-sm-4 control-label">Active</label>
														<div class="col-sm-8">
															<select class="form-control input-sm chosen-select"
																name="active" id="active"
																data-placeholder="Choose a State..." onchange="" <?php echo  $_GET['act'] == "detail_karyawan" ? "disabled" : ""; ?>>
																<option value="Y" <?php echo isset($row['active']) && trim($row['active']) == "Y" ? " selected" : ""; ?>>Ya</option>
																<option value="T" <?php echo isset($row['active']) && trim($row['active']) == "T" ? " selected" : ""; ?>>Tidak</option>
															</select>
														</div>
													</div>
													<div class="form-group">
														<label for="kode_finger" class="col-sm-4 control-label">Kode Finger</label>
														<div class="col-sm-8">
															<input type="text" class="form-control input-sm"
																id="kode_finger" placeholder="" <?php echo  $_GET['act'] == "detail_karyawan" ? "readonly" : ""; ?>
																value="<?php echo $_GET['act'] == "edit_karyawan" || $_GET['act'] == "detail_karyawan" ? $row['kode_finger'] : ""; ?>">
														</div>
													</div>
												</form>
											</div>
										</div>
									</div>
								</div>
							</div>
						</div>
					</div>
				</div>
			</div>
		</div>
		<div class="row">
			<div class="col-xs-12">
				<div class="col-xs-12 col-sm-12 pricing-box">
					<div class="widget-box widget-color-green">
						<div class="widget-header">
							<h3 class="widget-title bigger lighter">
								Informasi Pribadi
								<small style="color:#ffffff">Informasi Pribadi Karyawan</small>
							</h3>
						</div>
						<div class="widget-body">
							<div class="widget-main">
								<div class="row">
									<div class="col-xs-12">
										<div class="widget-body">
											<div class="col-md-4 widget_1_box2" style="">
												<form class="form-horizontal">
													<div class="form-group">
														<label for="tempat_lahir"
															class="col-sm-4 control-label">Tempat Lahir</label>
														<div class="col-sm-8">
															<input type="text" class="form-control input-sm"
																id="tempat_lahir" placeholder="" <?php echo  $_GET['act'] == "detail_karyawan" ? "readonly" : ""; ?>
																value="<?php echo $_GET['act'] == "edit_karyawan" || $_GET['act'] == "detail_karyawan" ? $row['tempat_lahir'] : ""; ?>">
														</div>
													</div>
													<div class="form-group">
														<label for="tgl_lahir"
															class="col-sm-4 control-label">Tanggal Lahir</label>
														<div class="col-sm-8">
															<input type="text" class="form-control input-sm datepicker"
																id="tgl_lahir" placeholder="" <?php echo  $_GET['act'] == "detail_karyawan" ? "readonly" : ""; ?>
																value="<?php echo isset($row['tgl_lahir']) && $row['tgl_lahir'] != "" ? date_format($row['tgl_lahir'], "d/m/Y") : ""; ?>">
														</div>
													</div>
													<div class="form-group">
														<label for="j_pengenal"
															class="col-sm-4 control-label">Jenis Pengenal</label>
														<div class="col-sm-8">
															<input type="text" class="form-control input-sm"
																id="j_pengenal" placeholder="" <?php echo  $_GET['act'] == "detail_karyawan" ? "readonly" : ""; ?>
																value="<?php echo $_GET['act'] == "edit_karyawan" || $_GET['act'] == "detail_karyawan" ? $row['j_pengenal'] : ""; ?>">
														</div>
													</div>
													<div class="form-group">
														<label for="no_pengenal"
															class="col-sm-4 control-label">No Pengenal</label>
														<div class="col-sm-8">
															<input type="text" class="form-control input-sm"
																id="no_pengenal" placeholder="" <?php echo  $_GET['act'] == "detail_karyawan" ? "readonly" : ""; ?>
																value="<?php echo $_GET['act'] == "edit_karyawan" || $_GET['act'] == "detail_karyawan" ? $row['no_pengenal'] : ""; ?>">
														</div>
													</div>
													<div class="form-group">
														<label for="no_kk"
															class="col-sm-4 control-label">No KK</label>
														<div class="col-sm-8">
															<input type="text" class="form-control input-sm"
																id="no_kk" placeholder="" <?php echo  $_GET['act'] == "detail_karyawan" ? "readonly" : ""; ?>
																value="<?php echo $_GET['act'] == "edit_karyawan" || $_GET['act'] == "detail_karyawan" ? $row['no_kk'] : ""; ?>">
														</div>
													</div>
													<div class="form-group">
														<label for="alamat_ktp"
															class="col-sm-4 control-label">Alamat KTP</label>
														<div class="col-sm-8">
															<input type="text" class="form-control input-sm"
																id="alamat_ktp" placeholder="" <?php echo  $_GET['act'] == "detail_karyawan" ? "readonly" : ""; ?>
																value="<?php echo $_GET['act'] == "edit_karyawan" || $_GET['act'] == "detail_karyawan" ? $row['alamat_ktp'] : ""; ?>">
														</div>
													</div>
													<div class="form-group">
														<label for="kota"
															class="col-sm-4 control-label">Kota</label>
														<div class="col-sm-8">
															<input type="text" class="form-control input-sm"
																id="kota" placeholder="" <?php echo  $_GET['act'] == "detail_karyawan" ? "readonly" : ""; ?>
																value="<?php echo $_GET['act'] == "edit_karyawan" || $_GET['act'] == "detail_karyawan" ? $row['kota'] : ""; ?>">
														</div>
													</div>
													<div class="form-group">
														<label for="alamat_domisili"
															class="col-sm-4 control-label">Alamat Domisili</label>
														<div class="col-sm-8">
															<input type="text" class="form-control input-sm"
																id="alamat_domisili" placeholder="" <?php echo  $_GET['act'] == "detail_karyawan" ? "readonly" : ""; ?>
																value="<?php echo $_GET['act'] == "edit_karyawan" || $_GET['act'] == "detail_karyawan" ? $row['alamat_domisili'] : ""; ?>">
														</div>
													</div>
													<div class="form-group">
														<label for="kota_domisili"
															class="col-sm-4 control-label">Kota Domisili</label>
														<div class="col-sm-8">
															<input type="text" class="form-control input-sm"
																id="kota_domisili" placeholder="" <?php echo  $_GET['act'] == "detail_karyawan" ? "readonly" : ""; ?>
																value="<?php echo $_GET['act'] == "edit_karyawan" || $_GET['act'] == "detail_karyawan" ? $row['kota_domisili'] : ""; ?>">
														</div>
													</div>
													<div class="form-group">
														<label for="telepon"
															class="col-sm-4 control-label">Telepon</label>
														<div class="col-sm-8">
															<input type="text" class="form-control input-sm"
																id="telepon" placeholder="" <?php echo  $_GET['act'] == "detail_karyawan" ? "readonly" : ""; ?>
																value="<?php echo $_GET['act'] == "edit_karyawan" || $_GET['act'] == "detail_karyawan" ? $row['handphone'] : ""; ?>">
														</div>
													</div>
													<div class="form-group">
														<label for="handphone"
															class="col-sm-4 control-label">Handphone</label>
														<div class="col-sm-8">
															<input type="text" class="form-control input-sm"
																id="handphone" placeholder="" <?php echo  $_GET['act'] == "detail_karyawan" ? "readonly" : ""; ?>
																value="<?php echo $_GET['act'] == "edit_karyawan" || $_GET['act'] == "detail_karyawan" ? $row['handphone'] : ""; ?>">
														</div>
													</div>
												</form>
											</div>
											<div class="col-md-4 widget_1_box2" style="">
												<form class="form-horizontal">
													<div class="form-group">
														<label for="email"
															class="col-sm-4 control-label">Email</label>
														<div class="col-sm-8">
															<input type="email" class="form-control input-sm"
																id="email" placeholder="" <?php echo  $_GET['act'] == "detail_karyawan" ? "readonly" : ""; ?>
																value="<?php echo $_GET['act'] == "edit_karyawan" || $_GET['act'] == "detail_karyawan" ? $row['email'] : ""; ?>">
														</div>
													</div>
													<div class="form-group">
														<label for="agama"
															class="col-sm-4 control-label">Agama</label>
														<div class="col-sm-8">
															<input type="text" class="form-control input-sm"
																id="agama" placeholder="" <?php echo  $_GET['act'] == "detail_karyawan" ? "readonly" : ""; ?>
																value="<?php echo $_GET['act'] == "edit_karyawan" || $_GET['act'] == "detail_karyawan" ? $row['agama'] : ""; ?>">
														</div>
													</div>
													<div class="form-group">
														<label for="pendidikan"
															class="col-sm-4 control-label">Pendidikan</label>
														<div class="col-sm-8">
															<input type="text" class="form-control input-sm"
																id="pendidikan" placeholder="" <?php echo  $_GET['act'] == "detail_karyawan" ? "readonly" : ""; ?>
																value="<?php echo $_GET['act'] == "edit_karyawan" || $_GET['act'] == "detail_karyawan" ? $row['pendidikan'] : ""; ?>">
														</div>
													</div>
													<div class="form-group">
														<label for="institusi"
															class="col-sm-4 control-label">Institusi</label>
														<div class="col-sm-8">
															<input type="text" class="form-control input-sm"
																id="institusi" placeholder="" <?php echo  $_GET['act'] == "detail_karyawan" ? "readonly" : ""; ?>
																value="<?php echo $_GET['act'] == "edit_karyawan" || $_GET['act'] == "detail_karyawan" ? $row['institusi'] : ""; ?>">
														</div>
													</div>
													<div class="form-group">
														<label for="tgl_awal_kerja"
															class="col-sm-4 control-label">Tanggal Awal Kerja</label>
														<div class="col-sm-8">
															<input type="text" class="form-control input-sm datepicker"
																id="tgl_awal_kerja" placeholder="" <?php echo  $_GET['act'] == "detail_karyawan" ? "readonly" : ""; ?>
																value="<?php echo isset($row['tgl_awal_kerja']) && $row['tgl_awal_kerja'] != "" ? date_format($row['tgl_awal_kerja'], "d/m/Y") : ""; ?>">
														</div>
													</div>
													<div class="form-group">
														<label for="tgl_akhir_kerja"
															class="col-sm-4 control-label">Tanggal Akhir Kerja</label>
														<div class="col-sm-8">
															<input type="text" class="form-control input-sm datepicker"
																id="tgl_akhir_kerja" placeholder="" <?php echo  $_GET['act'] == "detail_karyawan" ? "readonly" : ""; ?>
																value="<?php echo isset($row['tgl_akhir_kerja']) && $row['tgl_akhir_kerja'] != "" ? date_format($row['tgl_akhir_kerja'], "d/m/Y") : ""; ?>">
														</div>
													</div>
													<div class="form-group">
														<label for="status"
															class="col-sm-4 control-label">Status</label>
														<div class="col-sm-8">
															<input type="text" class="form-control input-sm"
																id="status" placeholder="" <?php echo  $_GET['act'] == "detail_karyawan" ? "readonly" : ""; ?>
																value="<?php echo $_GET['act'] == "edit_karyawan" || $_GET['act'] == "detail_karyawan" ? $row['status'] : ""; ?>">
														</div>
													</div>
													<div class="form-group">
														<label for="jenis_kelamin"
															class="col-sm-4 control-label">Jenis Kelamin</label>
														<div class="col-sm-8">
															<select class="form-control input-sm chosen-select"
																name="jenis_kelamin" id="jenis_kelamin"
																data-placeholder="Choose a State..." onchange="" <?php echo  $_GET['act'] == "detail_karyawan" ? "disabled" : ""; ?>>
																<option value="PRIA" <?php echo isset($row['jenis_kelamin']) && trim($row['jenis_kelamin']) == "PRIA" ? "selected" : ""; ?>>PRIA</option>
																<option value="WANITA" <?php echo isset($row['jenis_kelamin']) && trim($row['jenis_kelamin']) == "WANITA" ? "selected" : ""; ?>>WANITA</option>
															</select>
														</div>
													</div>
													<div class="form-group">
														<label for="bank" class="col-sm-4 control-label">Bank</label>
														<div class="col-sm-8">
															<input type="text" class="form-control input-sm"
																id="bank" placeholder="" <?php echo  $_GET['act'] == "detail_karyawan" ? "readonly" : ""; ?>
																value="<?php echo $_GET['act'] == "edit_karyawan" || $_GET['act'] == "detail_karyawan" ? $row['bank'] : ""; ?>">
														</div>
													</div><div class="form-group">
														<label for="no_rekening" class="col-sm-4 control-label">No
															Rekening</label>
														<div class="col-sm-8">
															<input type="text" class="form-control input-sm"
																id="no_rekening" placeholder="" <?php echo  $_GET['act'] == "detail_karyawan" ? "readonly" : ""; ?>
																value="<?php echo $_GET['act'] == "edit_karyawan" || $_GET['act'] == "detail_karyawan" ? $row['no_rekening'] : ""; ?>">
														</div>
													</div>
												</form>
											</div>
											<div class="col-md-4 widget_1_box2" style="">
												<form class="form-horizontal">
													<div class="form-group">
														<label for="status_pph_21"
															class="col-sm-4 control-label">Status
															PPH 21</label>
														<div class="col-sm-8">
															<select class="form-control input-sm chosen-select"
																name="status_pph_21" id="status_pph_21"
																data-placeholder="Choose a State..." onchange="" <?php echo  $_GET['act'] == "detail_karyawan" ? "disabled" : ""; ?>>
																<option value="K/0" <?php echo isset($row['status_pph_21']) && trim($row['status_pph_21']) == "K/0" ? " selected" : ""; ?>>K/0</option>
																<option value="K/1" <?php echo isset($row['status_pph_21']) && trim($row['status_pph_21']) == "K/1" ? " selected" : ""; ?>>K/1</option>
																<option value="K/2" <?php echo isset($row['status_pph_21']) && trim($row['status_pph_21']) == "K/2" ? " selected" : ""; ?>>K/2</option>
																<option value="K/3" <?php echo isset($row['status_pph_21']) && trim($row['status_pph_21']) == "K/3" ? " selected" : ""; ?>>K/3</option>
																<option value="TK" <?php echo isset($row['status_pph_21']) && trim($row['status_pph_21']) == "TK" ? " selected" : ""; ?>>TK</option>
																<option value="TK/0" <?php echo isset($row['status_pph_21']) && trim($row['status_pph_21']) == "TK/0" ? " selected" : ""; ?>>TK/0</option>
																<option value="TK/1" <?php echo isset($row['status_pph_21']) && trim($row['status_pph_21']) == "TK/1" ? " selected" : ""; ?>>TK/1</option>
																<option value="TK/2" <?php echo isset($row['status_pph_21']) && trim($row['status_pph_21']) == "TK/2" ? " selected" : ""; ?>>TK/2</option>
																<option value="TK/3" <?php echo isset($row['status_pph_21']) && trim($row['status_pph_21']) == "TK/3" ? " selected" : ""; ?>>TK/3</option>
																<option value="-" <?php echo isset($row['status_pph_21']) && trim($row['status_pph_21']) == "-" ? " selected" : ""; ?>>-</option>
															</select>
														</div>
													</div>
													<div class="form-group">
														<label for="no_npwp" class="col-sm-4 control-label">No.
															NPWP</label>
														<div class="col-sm-8">
															<input type="text" class="form-control input-sm input-mask-npwp"
																id="no_npwp" placeholder="" <?php echo  $_GET['act'] == "detail_karyawan" ? "readonly" : ""; ?>
																value="<?php echo $_GET['act'] == "edit_karyawan" || $_GET['act'] == "detail_karyawan" ? $row['no_npwp'] : ""; ?>">
														</div>
													</div>
													<div class="form-group">
														<label for="no_bpjs_kes" class="col-sm-4 control-label">No.
															BPJS Kes</label>
														<div class="col-sm-8">
															<input type="text" class="form-control input-sm"
																id="no_bpjs_kes" placeholder="" <?php echo  $_GET['act'] == "detail_karyawan" ? "readonly" : ""; ?>
																value="<?php echo $_GET['act'] == "edit_karyawan" || $_GET['act'] == "detail_karyawan" ? $row['no_bpjs_kes']  : ""; ?>">
														</div>
													</div>
													<div class="form-group">
														<label for="no_bpjs_tk" class="col-sm-4 control-label">No.
															BPJS TK</label>
														<div class="col-sm-8">
															<input type="text" class="form-control input-sm"
																id="no_bpjs_tk" placeholder="" <?php echo  $_GET['act'] == "detail_karyawan" ? "readonly" : ""; ?>
																value="<?php echo $_GET['act'] == "edit_karyawan" || $_GET['act'] == "detail_karyawan" ? $row['no_bpjs_tk'] : ""; ?>">
														</div>
													</div>
												</form>
											</div>
										</div>
									</div>
								</div>
							</div>
						</div>
					</div>
				</div>
			</div>
		</div>
		<div class="row">
			<div class="col-xs-12">
				<div class="col-xs-12 col-sm-12 pricing-box">
					<div class="widget-box widget-color-blue">
						<div class="widget-header">
							<h3 class="widget-title bigger lighter">
								Informasi Relasi
								<small style="color:#ffffff">Informasi Relasi, Keluarga</small>
							</h3>
						</div>
						<div class="widget-body">
							<div class="widget-main">
								<div class="row">
									<div class="col-xs-12">
										<div class="widget-body">
											<div class="col-md-4 widget_1_box2" style="">
												<form class="form-horizontal">
													<div class="form-group">
														<label for="nama_pasangan" class="col-sm-4 control-label">Nama
															Pasangan</label>
														<div class="col-sm-8">
															<input type="text" class="form-control input-sm"
																id="nama_pasangan" placeholder="" <?php echo  $_GET['act'] == "detail_karyawan" ? "readonly" : ""; ?>
																value="<?php echo $_GET['act'] == "edit_karyawan" || $_GET['act'] == "detail_karyawan" ? $row['nama_pasangan'] : ""; ?>">
														</div>
													</div>
													<div class="form-group">
														<label for="telp_pasangan" class="col-sm-4 control-label">Telp
															Pasangan</label>
														<div class="col-sm-8">
															<input type="text" class="form-control input-sm"
																id="telp_pasangan" placeholder="" <?php echo  $_GET['act'] == "detail_karyawan" ? "readonly" : ""; ?>
																value="<?php echo $_GET['act'] == "edit_karyawan" || $_GET['act'] == "detail_karyawan" ? $row['telp_pasangan'] : ""; ?>">
														</div>
													</div>
													<div class="form-group">
														<label for="tgl_lahir_pasangan"
															class="col-sm-4 control-label">Tanggal Lahir Pasangan</label>
														<div class="col-sm-8">
															<input type="text" class="form-control input-sm datepicker"
																id="tgl_lahir_pasangan" placeholder="" <?php echo  $_GET['act'] == "detail_karyawan" ? "readonly" : ""; ?>
																value="<?php echo isset($row['tgl_lahir_pasangan']) && $row['tgl_lahir_pasangan'] != "" ? date_format($row['tgl_lahir_pasangan'], "d/m/Y") : ""; ?>">
														</div>
													</div>
													<div class="form-group">
														<label for="nama_kerja_pasangan" class="col-sm-4 control-label">Nama
															Kerja Pasangan</label>
														<div class="col-sm-8">
															<input type="text" class="form-control input-sm"
																id="nama_kerja_pasangan" placeholder="" <?php echo  $_GET['act'] == "detail_karyawan" ? "readonly" : ""; ?>
																value="<?php echo $_GET['act'] == "edit_karyawan" || $_GET['act'] == "detail_karyawan" ? $row['nama_kerja_pasangan'] : ""; ?>">
														</div>
													</div>
													<div class="form-group">
														<label for="lokasi_kerja_pasangan"
															class="col-sm-4 control-label">Lokasi Kerja Pasangan</label>
														<div class="col-sm-8">
															<input type="text" class="form-control input-sm"
																id="lokasi_kerja_pasangan" placeholder="" <?php echo  $_GET['act'] == "detail_karyawan" ? "readonly" : ""; ?>
																value="<?php echo $_GET['act'] == "edit_karyawan" || $_GET['act'] == "detail_karyawan" ? $row['lokasi_kerja_pasangan'] : ""; ?>">
														</div>
													</div>
													<div class="form-group">
														<label for="nama_anak1"
															class="col-sm-4 control-label"><b>Nama Anak 1</b></label>
														<div class="col-sm-8">
															<input type="text" class="form-control input-sm"
																id="nama_anak1" placeholder="" <?php echo  $_GET['act'] == "detail_karyawan" ? "readonly" : ""; ?>
																value="<?php echo $_GET['act'] == "edit_karyawan" || $_GET['act'] == "detail_karyawan" ? $row['nama_anak1'] : ""; ?>">
														</div>
													</div>
													<div class="form-group">
														<label for="tgl_lahir_anak1" class="col-sm-4 control-label">Tgl
															Lahir 1</label>
														<div class="col-sm-8">
															<input type="text" class="form-control input-sm datepicker"
																id="tgl_lahir_anak1" placeholder="" <?php echo  $_GET['act'] == "detail_karyawan" ? "readonly" : ""; ?>
																value="<?php echo isset($row['tgl_lahir_anak1']) && $row['tgl_lahir_anak1'] != "" ? date_format($row['tgl_lahir_anak1'], "d/m/Y") : ""; ?>">
														</div>
													</div>
													<div class="form-group">
														<label for="gender_anak1"
															class="col-sm-4 control-label">Gender anak 1</label>
														<div class="col-sm-8">
															<select class="form-control input-sm chosen-select"
																name="gender_anak1" id="gender_anak1"
																data-placeholder="Choose a State..." onchange="" <?php echo  $_GET['act'] == "detail_karyawan" ? "disabled" : ""; ?>>
																<option value="L" <?php echo isset($row['gender_anak1']) && trim($row['gender_anak1']) == "L" ? "selected" : ""; ?>>Laki-laki</option>
																<option value="P" <?php echo isset($row['gender_anak1']) && trim($row['gender_anak1']) == "P" ? "selected" : ""; ?>>Perempuan</option>
															</select>
														</div>
													</div>
													<div class="form-group">
														<label for="nama_anak2"
															class="col-sm-4 control-label"><b>Nama Anak 2</b></label>
														<div class="col-sm-8">
															<input type="text" class="form-control input-sm"
																id="nama_anak2" placeholder="" <?php echo  $_GET['act'] == "detail_karyawan" ? "readonly" : ""; ?>
																value="<?php echo $_GET['act'] == "edit_karyawan" || $_GET['act'] == "detail_karyawan" ? $row['nama_anak2'] : ""; ?>">
														</div>
													</div>
													<div class="form-group">
														<label for="tgl_lahir_anak2" class="col-sm-4 control-label">Tgl
															Lahir 2</label>
														<div class="col-sm-8">
															<input type="text" class="form-control input-sm datepicker"
																id="tgl_lahir_anak2" placeholder="" <?php echo  $_GET['act'] == "detail_karyawan" ? "readonly" : ""; ?>
																value="<?php echo isset($row['tgl_lahir_anak2']) && $row['tgl_lahir_anak2'] != "" ? date_format($row['tgl_lahir_anak2'], "d/m/Y") : ""; ?>">
														</div>
													</div>
													<div class="form-group">
														<label for="gender_anak2"
															class="col-sm-4 control-label">Gender Anak 2</label>
														<div class="col-sm-8">

															<select class="form-control input-sm chosen-select"
																name="gender_anak2" id="gender_anak2"
																data-placeholder="Choose a State..." onchange="" <?php echo  $_GET['act'] == "detail_karyawan" ? "disabled" : ""; ?>>
																<option value="L" <?php echo isset($row['gender_anak2']) && trim($row['gender_anak2']) == "L" ? "selected" : ""; ?>>Laki-laki</option>
																<option value="P" <?php echo isset($row['gender_anak2']) && trim($row['gender_anak2']) == "P" ? "selected" : ""; ?>>Perempuan</option>
															</select>

														</div>
													</div>
												</form>
											</div>
											<div class="col-md-4 widget_1_box2" style="">
												<form class="form-horizontal">
													<div class="form-group">
														<label for="nama_anak3"
															class="col-sm-4 control-label"><b>Nama Anak 3</b></label>
														<div class="col-sm-8">
															<input type="text" class="form-control input-sm"
																id="nama_anak3" placeholder="" <?php echo  $_GET['act'] == "detail_karyawan" ? "readonly" : ""; ?>
																value="<?php echo $_GET['act'] == "edit_karyawan" || $_GET['act'] == "detail_karyawan" ? $row['nama_anak3'] : ""; ?>">
														</div>
													</div>
													<div class="form-group">
														<label for="tgl_lahir_anak3" class="col-sm-4 control-label">Tgl
															Lahir 3</label>
														<div class="col-sm-8">
															<input type="text" class="form-control input-sm datepicker"
																id="tgl_lahir_anak3" placeholder="" <?php echo  $_GET['act'] == "detail_karyawan" ? "readonly" : ""; ?>
																value="<?php echo isset($row['tgl_lahir_anak3']) && $row['tgl_lahir_anak3'] != "" ? date_format($row['tgl_lahir_anak3'], "d/m/Y") : ""; ?>">
														</div>
													</div>
													<div class="form-group">
														<label for="gender_anak3"
															class="col-sm-4 control-label">Gender Anak 3</label>
														<div class="col-sm-8">
															<select class="form-control input-sm chosen-select"
																name="gender_anak3" id="gender_anak3"
																data-placeholder="Choose a State..." onchange="" <?php echo  $_GET['act'] == "detail_karyawan" ? "disabled" : ""; ?>>
																<option value="L" <?php echo isset($row['gender_anak3']) && trim($row['gender_anak3']) == "L" ? "selected" : ""; ?>>Laki-laki</option>
																<option value="P" <?php echo isset($row['gender_anak3']) && trim($row['gender_anak3']) == "P" ? "selected" : ""; ?>>Perempuan</option>
															</select>
														</div>
													</div>
													<div class="form-group">
														<label for="wajib_jaminan"
															class="col-sm-4 control-label">Wajib Jaminan</label>
														<div class="col-sm-8">
															<select class="form-control input-sm chosen-select"
																name="wajib_jaminan" id="wajib_jaminan"
																data-placeholder="Choose a State..." onchange="" <?php echo  $_GET['act'] == "detail_karyawan" ? "disabled" : ""; ?>>
																<option value="Y" <?php echo isset($row['wajib_jaminan']) && trim($row['wajib_jaminan']) == "Y" ? "selected" : ""; ?>>Ya</option>
																<option value="T" <?php echo isset($row['wajib_jaminan']) && trim($row['wajib_jaminan']) == "T" ? "selected" : ""; ?>>Tidak</option>
															</select>
														</div>
													</div>
													<div class="form-group">
														<label for="nama_jaminan" class="col-sm-4 control-label">Nama
															Jaminan</label>
														<div class="col-sm-8">
															<input type="text" class="form-control input-sm"
																id="nama_jaminan" placeholder="" <?php echo  $_GET['act'] == "detail_karyawan" ? "readonly" : ""; ?>
																value="<?php echo $_GET['act'] == "edit_karyawan" || $_GET['act'] == "detail_karyawan" ? $row['nama_jaminan'] : ""; ?>">
														</div>
													</div>
													<div class="form-group">
														<label for="no_jaminan" class="col-sm-4 control-label">No.
															Jaminan</label>
														<div class="col-sm-8">
															<input type="text" class="form-control input-sm"
																id="no_jaminan" placeholder="" <?php echo  $_GET['act'] == "detail_karyawan" ? "readonly" : ""; ?>
																value="<?php echo $_GET['act'] == "edit_karyawan" || $_GET['act'] == "detail_karyawan" ? $row['no_jaminan'] : ""; ?>">
														</div>
													</div>
													<div class="form-group">
														<label for="terdaftar_bpjs_kes"
															class="col-sm-4 control-label">Terdaftar BPJS Kes</label>
														<div class="col-sm-8">
															<select class="form-control input-sm chosen-select"
																name="terdaftar_bpjs_kes" id="terdaftar_bpjs_kes"
																data-placeholder="Choose a State..." onchange="" <?php echo  $_GET['act'] == "detail_karyawan" ? "disabled" : ""; ?>>
																<option value="TERDAFTAR" <?php echo isset($row['terdaftar_bpjs_kes']) && trim($row['terdaftar_bpjs_kes']) == "TERDAFTAR" ? "selected" : ""; ?>>TERDAFTAR</option>
																<option value="TIDAK TERDAFTAR" <?php echo isset($row['terdaftar_bpjs_kes']) && trim($row['terdaftar_bpjs_kes']) == "TIDAK TERDAFTAR" ? "selected" : ""; ?>>TIDAK TERDAFTAR</option>
															</select>
														</div>
													</div>
													<div class="form-group">
														<label for="terdaftar_bpjs_tk"
															class="col-sm-4 control-label">Terdaftar BPJS TK</label>
														<div class="col-sm-8">
															<select class="form-control input-sm chosen-select"
																name="terdaftar_bpjs_tk" id="terdaftar_bpjs_tk"
																data-placeholder="Choose a State..." onchange="" <?php echo  $_GET['act'] == "detail_karyawan" ? "disabled" : ""; ?>>
																<option value="TERDAFTAR" <?php echo isset($row['terdaftar_bpjs_tk']) && trim($row['terdaftar_bpjs_tk']) == "TERDAFTAR" ? "selected" : ""; ?>>TERDAFTAR</option>
																<option value="TIDAK TERDAFTAR" <?php echo isset($row['terdaftar_bpjs_tk']) && trim($row['terdaftar_bpjs_tk']) == "TIDAK TERDAFTAR" ? "selected" : ""; ?>>TIDAK TERDAFTAR</option>
															</select>
														</div>
													</div>
													<div class="form-group">
														<label for="terdaftar_bpjs_tk"
															class="col-sm-4 control-label">Note Update</label>
														<div class="col-sm-8">
															<div class="widget-box widget-color-green">
																<div class="widget-header widget-header-small"> </div>
																<div class="widget-body">
																	<div class="widget-main no-padding">
																		<div class="wysiwyg-editor" id="<?php echo $_GET['act'] == "detail" ? "" : "editor2"; ?>">
																			<?php echo $_GET['act'] == "edit_karyawan" || $_GET['act'] == "detail_karyawan" ? trim($row['note_update']) : ""; ?>
																		</div>
																	</div>
																</div>
															</div>
														</div>
													</div>
												</form>
											</div>
											<div class="col-md-4 widget_1_box2" style="">
												<form class="form-horizontal">
													<div class="form-group">
														<label for="alasan_reject" class="col-sm-4 control-label">Note Reject</label>
														<div class="col-sm-8">
															<div class="widget-box widget-color-green">
																<div class="widget-header widget-header-small"> </div>
																<div class="widget-body">
																	<div class="widget-main no-padding">
																		<div class="wysiwyg-editor" id="<?php echo $_GET['act'] == "detail" ? "" : "editor3"; ?>">
																			<?php echo $_GET['act'] == "edit_karyawan" || $_GET['act'] == "detail_karyawan" ? trim($row['alasan_reject']) : ""; ?>
																		</div>
																	</div>
																</div>
															</div>
														</div>
													</div>
													<div class="form-group">
														<label for="alasan_reject_hrd" class="col-sm-4 control-label">Note HR</label>
														<div class="col-sm-8">
															<div class="widget-box widget-color-green">
																<div class="widget-header widget-header-small"> </div>
																<div class="widget-body">
																	<div class="widget-main no-padding">
																		<div class="wysiwyg-editor" id="<?php echo $_GET['act'] == "detail" ? "" : "editor4"; ?>">
																			<?php echo $_GET['act'] == "edit_karyawan" || $_GET['act'] == "detail_karyawan" ? trim($row['alasan_reject_hrd']) : ""; ?>
																		</div>
																	</div>
																</div>
															</div>
														</div>
													</div>
												</form>
											</div>
										</div>
									</div>
								</div>
							</div>
						</div>
					</div>
				</div>
			</div>
		</div>
		<br><br>
		<div class="col-md-12">
			<div class="pull-right <?php echo  $_GET['act'] == "detail_karyawan" ? "hide" : ""; ?>" style="">
				<div class="form-group">
				<div id="ProgressDialog" style="display:none">
                    <h4 class="header smaller lighter blue">
                        <i class="ace-icon fa fa-spinner fa-spin orange bigger-125"></i> Save data...
                    </h4>
                </div>
					<button type="button" class="btn btn-primary" onclick="save_karyawan()">
						<i class="ace-icon fa fa-save align-top bigger-125"></i> Save
					</button>
				</div>
			</div>
		</div>
	</div>
</div>
<script>
	$('.datepicker').datepicker({
		autoclose: true,
		todayHighlight: true
	});

    function PDialog() {
        $("#ProgressDialog").dialog({
            autoOpen: true,
            closeOnEscape: false,
            draggable: false,
            resizable: false,
            modal: true,
            buttons: []
        });

        $(".ui-dialog-titlebar-close").hide();
    }

    function save_karyawan() {
		var nama_pt = $("#nama_pt").val();
		var entity = $("#entity").val();
		var depo = $("#depo").val();
		var pin = $("#pin").val();
		var nik = $("#nik").val();
		var kategori_karyawan = $("#kategori_karyawan").val();
		var nama_outsourcing = $("#nama_outsourcing").val();
		var nama = $("#nama").val();
		var lama_kerja_th = $("#lama_kerja_th").val();
		var lama_kerja_bln = $("#lama_kerja_bln").val();
		var status_pkwt = $("#status_pkwt").val();
		var status_kontrak_kerja = $("#status_kontrak_kerja").val();
		var tgl_akhir_pkwt = $("#tgl_akhir_pkwt").val();
		var nama_grade = $("#nama_grade").val();
		var sub_grade = $("#sub_grade").val();
		var tahun = $("#tahun").val();
		var grade_level = $("#grade_level").val();
		var skema_insentif = $("#skema_insentif").val();
		var thp = $("#THP").val();
		var potongan_bpjs_tk = $("#potongan_bpjs_tk").val();
		var potongan_bpjs_kes = $("#potongan_bpjs_kes").val();
		var bagian = $("#bagian").val();
		var sub_bagian = $("#sub_bagian").val();
		var seksi = $("#seksi").val();
		var jabatan = $("#jabatan").val();
		var divisi = $("#divisi").val();
		var brand = $("#brand").val();
		var active = $("#active").val();
		var kode_finger = $("#kode_finger").val();
		var tempat_lahir = $("#tempat_lahir").val();
		var tgl_lahir = $("#tgl_lahir").val();
		var j_pengenal = $("#j_pengenal").val();
		var no_pengenal = $("#no_pengenal").val();
		var no_kk = $("#no_kk").val();
		var alamat_ktp = $("#alamat_ktp").val();
		var kota = $("#kota").val();
		var alamat_domisili = $("#alamat_domisili").val();
		var kota_domisili = $("#kota_domisili").val();
		var telepon = $("#telepon").val();
		var handphone = $("#handphone").val();
		var email = $("#email").val();
		var agama = $("#agama").val();
		var pendidikan = $("#pendidikan").val();
		var institusi = $("#institusi").val();
		var tgl_awal_kerja = $("#tgl_awal_kerja").val();
		var tgl_akhir_kerja = $("#tgl_akhir_kerja").val();
		var status = $("#status").val();
		var jenis_kelamin = $("#jenis_kelamin").val();
		var bank = $("#bank").val();
		var no_rekening = $("#no_rekening").val();
		var status_pph_21 = $("#status_pph_21").val();
		var no_npwp = $("#no_npwp").val();
		var no_bpjs_kes = $("#no_bpjs_kes").val();
		var no_bpjs_tk = $("#no_bpjs_tk").val();
		var nama_pasangan = $("#nama_pasangan").val();
		var telp_pasangan = $("#telp_pasangan").val();
		var tgl_lahir_pasangan = $("#tgl_lahir_pasangan").val();
		var nama_kerja_pasangan = $("#nama_kerja_pasangan").val();
		var lokasi_kerja_pasangan = $("#lokasi_kerja_pasangan").val();
		var nama_anak1 = $("#nama_anak1").val();
		var tgl_lahir_anak1 = $("#tgl_lahir_anak1").val();
		var gender_anak1 = $("#gender_anak1").val();
		var nama_anak2 = $("#nama_anak2").val();
		var tgl_lahir_anak2 = $("#tgl_lahir_anak2").val();
		var gender_anak2 = $("#gender_anak2").val();
		var nama_anak3 = $("#nama_anak3").val();
		var tgl_lahir_anak3 = $("#tgl_lahir_anak3").val();
		var gender_anak3 = $("#gender_anak3").val();
		var wajib_jaminan = $("#wajib_jaminan").val();
		var nama_jaminan = $("#nama_jaminan").val();
		var no_jaminan = $("#no_jaminan").val();
		var terdaftar_bpjs_kes = $("#terdaftar_bpjs_kes").val();
		var terdaftar_bpjs_tk = $("#terdaftar_bpjs_tk").val();
		var note_update = $("#editor2").html();
		var foto_profile = "YA";
		var foto_ktp = "YA";
		var foto_sim = "YA";

		if (pin == "" || nik  == "" || kategori_karyawan  == "" || nama  == "" || lama_kerja_th  == "" || lama_kerja_bln  == "" || status_pkwt  == "" || nama_grade  == "" || sub_grade  == "" || tahun  == "" || grade_level  == "" || skema_insentif  == "" || thp  == "" || potongan_bpjs_tk  == "" || potongan_bpjs_kes  == "" || kode_finger  == "" || tempat_lahir  == "" || tgl_lahir  == "" || j_pengenal  == "" || no_pengenal  == "" || no_kk  == "" || alamat_ktp  == "" || kota  == "" || alamat_domisili  == "" || kota_domisili  == "" || telepon  == "" || handphone  == "" || email  == "" || agama  == "" || pendidikan  == "" || institusi  == "" || tgl_awal_kerja  == "" || status  == "" || bank  == "" || no_rekening  == "" || no_npwp  == "" || no_bpjs_kes  == "" || no_bpjs_tk  == "" || nama_pasangan  == "" || telp_pasangan  == "" || nama_kerja_pasangan  == "" || lokasi_kerja_pasangan  == "" || nama_anak1  == "" || nama_anak2  == "" || nama_anak3  == "" || nama_jaminan == "" || no_jaminan == "") {
			alert('Please fill all the required fields.');
		} else {

			PDialog();

			$.ajax({
				type: "POST",
				url: "inc/karyawan/data_karyawan_draft/proc_karyawan.php",
				traditional: true,
				data: {
					act: "save_karyawan",
					nama_pt : nama_pt,
					entity : entity,
					depo : depo,
					pin : pin,
					nik : nik,
					kategori_karyawan : kategori_karyawan,
					nama_outsourcing : nama_outsourcing,
					nama : nama,
					lama_kerja_th : lama_kerja_th,
					lama_kerja_bln : lama_kerja_bln,
					status_pkwt : status_pkwt,
					status_kontrak_kerja : status_kontrak_kerja,
					tgl_akhir_pkwt : tgl_akhir_pkwt,
					nama_grade : nama_grade,
					sub_grade : sub_grade,
					tahun : tahun,
					grade_level : grade_level,
					skema_insentif : skema_insentif,
					THP : thp,
					potongan_bpjs_tk : potongan_bpjs_tk,
					potongan_bpjs_kes : potongan_bpjs_kes,
					bagian : bagian,
					sub_bagian : sub_bagian,
					seksi : seksi,
					jabatan : jabatan,
					divisi : divisi,
					brand : brand,
					active : active,
					kode_finger : kode_finger,
					tempat_lahir : tempat_lahir,
					tgl_lahir : tgl_lahir,
					j_pengenal : j_pengenal,
					no_pengenal : no_pengenal,
					no_kk : no_kk,
					alamat_ktp : alamat_ktp,
					kota : kota,
					alamat_domisili : alamat_domisili,
					kota_domisili : kota_domisili,
					telepon : telepon,
					handphone : handphone,
					email : email,
					agama : agama,
					pendidikan : pendidikan,
					institusi : institusi,
					tgl_awal_kerja : tgl_awal_kerja,
					tgl_akhir_kerja : tgl_akhir_kerja,
					status : status,
					jenis_kelamin : jenis_kelamin,
					bank : bank,
					no_rekening : no_rekening,
					status_pph_21 : status_pph_21,
					no_npwp : no_npwp,
					no_bpjs_kes : no_bpjs_kes,
					no_bpjs_tk : no_bpjs_tk,
					nama_pasangan : nama_pasangan,
					telp_pasangan : telp_pasangan,
					tgl_lahir_pasangan : tgl_lahir_pasangan,
					nama_kerja_pasangan : nama_kerja_pasangan,
					lokasi_kerja_pasangan : lokasi_kerja_pasangan,
					nama_anak1 : nama_anak1,
					tgl_lahir_anak1 : tgl_lahir_anak1,
					gender_anak1 : gender_anak1,
					nama_anak2 : nama_anak2,
					tgl_lahir_anak2 : tgl_lahir_anak2,
					gender_anak2 : gender_anak2,
					nama_anak3 : nama_anak3,
					tgl_lahir_anak3 : tgl_lahir_anak3,
					gender_anak3 : gender_anak3,
					wajib_jaminan : wajib_jaminan,
					nama_jaminan : nama_jaminan,
					no_jaminan : no_jaminan,
					foto_profile : foto_profile,
					foto_ktp : foto_ktp,
					foto_sim : foto_sim,
					terdaftar_bpjs_kes : terdaftar_bpjs_kes,
					terdaftar_bpjs_tk : terdaftar_bpjs_tk,
					note_update : note_update,
				},
				success: function(data) {
					if (data.trim() == 'Data Saved') {
						$.gritter.add({
							// (string | mandatory) the heading of the notification
							title: data.trim(),
							// (string | mandatory) the text inside the notification
							// text: 'This will fade out after a certain amount of time. Vivamus eget tincidunt velit. Cum sociis natoque penatibus et <a href="#" class="orange">magnis dis parturient</a> montes, nascetur ridiculus mus.',
							class_name: 'gritter-info' + ' gritter-light'
						});

						$("#ProgressDialog").dialog("close");
				
						location.href="?sm=data_karyawan_draft";
						setTimeout(function() {
							$.gritter.removeAll();
						}, 5000);
					}
					
				},
				error: function(msg) {
					alert(msg);
				}
			});
		}
    }

	function upload_avatar() {
		var fd = new FormData();
		var total_foto = $('input[name="avatar"]').get(0).files.length;
		var nik = $('#nik').val();
		var d = new Date();
		var gtime = d.getTime();
		var nama_foto = 'foto_profile-' + gtime;
		var jenis = 'foto_profile';
		fd.append('nik', nik);
		fd.append('nama_foto', nama_foto);
		fd.append('act', "save_foto");
		fd.append('jenis', jenis);
		// for (var x = 0; x < total_foto; x++) {
		var file_foto = $('input[name="avatar"]').get(0).files[0];
		// console.log(file_foto);
		fd.append('file_foto', file_foto);
		// }
		fd.append('total_foto', total_foto);
		fd.append('gtime', gtime);
		if (nik == "") {
			alert('NIK harus diisi terlebih dahulu');
			$('.profile-picture').html('<img id="avatar" class="editable img-responsive" alt="Alexs Avatar" src="assets/images/avatars/profile-pic.jpg" />');
			try { //ie8 throws some harmless exceptions, so let's catch'em

				//first let's add a fake appendChild method for Image element for browsers that have a problem with this
				//because editable plugin calls appendChild, and it causes errors on IE at unpredicted points
				try {
					document.createElement('IMG').appendChild(document.createElement('B'));
				} catch (e) {
					Image.prototype.appendChild = function (el) { }
				}

				var nik = $('#nik').val();

				var last_gritter
				$('#avatar').editable({
					type: 'image',
					name: 'avatar',
					value: null,
					//onblur: 'ignore',  //don't reset or hide editable onblur?!
					image: {
						//specify ace file input plugin's options here
						btn_choose: 'Change Avatar',
						droppable: true,
						maxSize: 1100000, //~1000Kb

						//and a few extra ones here
						name: 'avatar', //put the field name here as well, will be used inside the custom plugin
						on_error: function (error_type) { //on_error function will be called when the selected file has a problem
							if (last_gritter) $.gritter.remove(last_gritter);
							if (error_type == 1) { //file format error
								last_gritter = $.gritter.add({
									title: 'File is not an image!',
									text: 'Please choose a jpg|gif|png image!',
									class_name: 'gritter-error gritter-center'
								});
							} else if (error_type == 2) { //file size rror
								last_gritter = $.gritter.add({
									title: 'File too big!',
									// text: 'Image size should not exceed 100Kb!',
									text: 'Image size should not exceed 1MB!',
									class_name: 'gritter-error gritter-center'
								});
							} else { //other error
							}
						},
						on_success: function () {
							$.gritter.removeAll();
						}
					},
					url: function (params) {
						// ***UPDATE AVATAR HERE*** //
						//for a working upload example you can replace the contents of this function with 
						//examples/profile-avatar-update.js

						var deferred = new $.Deferred

						var value = $('#avatar').next().find('input[type=hidden]:eq(0)').val();
						if (!value || value.length == 0) {
							deferred.resolve();
							return deferred.promise();
						}


						//dummy upload
						setTimeout(function () {
							if ("FileReader" in window) {
								//for browsers that have a thumbnail of selected image
								var thumb = $('#avatar').next().find('img').data('thumb');
								if (thumb) $('#avatar').get(0).src = thumb;
							}

							deferred.resolve({
								'status': 'OK'
							});

							if (last_gritter) $.gritter.remove(last_gritter);
							last_gritter = $.gritter.add({
								title: 'Avatar Updated!',
								text: 'Success upload profile photo.',
								class_name: 'gritter-info gritter-center'
							});

						}, parseInt(Math.random() * 800 + 800))

						return deferred.promise();

						// ***END OF UPDATE AVATAR HERE*** //
					},

					success: function (response, newValue) {
						// ***Custome Upload*** //	
						upload_avatar();
					}
				})
			} catch (e) { }
		} else {
			$.ajax({
				type: "POST",
				processData: false, //important
				contentType: false, //important	
				url: "inc/karyawan/data_karyawan_draft/proc_karyawan.php",
				data: fd,
				success: function (responseText) {
					// $.gritter.add({
					// 	title: responseText.trim(),
					// 	class_name: 'gritter-info' + ' gritter-light'
					// });
				},
				error: function (data) {
					alert(data);
					$("#progress").hide();
				}
			});
		}
	}

	function upload_ktp() {
		var fd = new FormData();
		var total_foto = $('input[name="foto_ktp"]').get(0).files.length;
		var nik = $('#nik').val();
		var d = new Date();
		var gtime = d.getTime();
		var nama_foto = 'foto_ktp-' + gtime;
		var jenis = 'foto_ktp';
		fd.append('nik', nik);
		fd.append('nama_foto', nama_foto);
		fd.append('act', "save_foto");
		fd.append('jenis', jenis);
		// for (var x = 0; x < total_foto; x++) {
		var file_foto = $('input[name="foto_ktp"]').get(0).files[0];
		// console.log(file_foto);
		fd.append('file_foto', file_foto);
		// }
		fd.append('total_foto', total_foto);
		fd.append('gtime', gtime);

		if (nik == "") {
			alert('NIK harus diisi terlebih dahulu');
			$('.foto-ktp').html('<img id="foto_ktp" class="editable img-responsive" alt="Alexs Avatar" src="assets/images/avatars/profile-pic.jpg" />');
			try { //ie8 throws some harmless exceptions, so let's catch'em

				//first let's add a fake appendChild method for Image element for browsers that have a problem with this
				//because editable plugin calls appendChild, and it causes errors on IE at unpredicted points
				try {
					document.createElement('IMG').appendChild(document.createElement('B'));
				} catch (e) {
					Image.prototype.appendChild = function (el) { }
				}

				var nik = $('#nik').val();

				var last_gritter
				$('#foto_ktp').editable({
					type: 'image',
					name: 'foto_ktp',
					value: null,
					//onblur: 'ignore',  //don't reset or hide editable onblur?!
					image: {
						//specify ace file input plugin's options here
						btn_choose: 'Change Photo',
						droppable: true,
						maxSize: 1100000, //~1000Kb

						//and a few extra ones here
						name: 'foto_ktp', //put the field name here as well, will be used inside the custom plugin
						on_error: function (error_type) { //on_error function will be called when the selected file has a problem
							if (last_gritter) $.gritter.remove(last_gritter);
							if (error_type == 1) { //file format error
								last_gritter = $.gritter.add({
									title: 'File is not an image!',
									text: 'Please choose a jpg|gif|png image!',
									class_name: 'gritter-error gritter-center'
								});
							} else if (error_type == 2) { //file size rror
								last_gritter = $.gritter.add({
									title: 'File too big!',
									// text: 'Image size should not exceed 100Kb!',
									text: 'Image size should not exceed 1MB!',
									class_name: 'gritter-error gritter-center'
								});
							} else { //other error
							}
						},
						on_success: function () {
							$.gritter.removeAll();
						}
					},
					url: function (params) {
						// ***UPDATE AVATAR HERE*** //
						//for a working upload example you can replace the contents of this function with 
						//examples/profile-avatar-update.js

						var deferred = new $.Deferred

						var value = $('#foto_ktp').next().find('input[type=hidden]:eq(0)').val();
						if (!value || value.length == 0) {
							deferred.resolve();
							return deferred.promise();
						}


						//dummy upload
						setTimeout(function () {
							if ("FileReader" in window) {
								//for browsers that have a thumbnail of selected image
								var thumb = $('#foto_ktp').next().find('img').data('thumb');
								if (thumb) $('#foto_ktp').get(0).src = thumb;
							}

							deferred.resolve({
								'status': 'OK'
							});

							if (last_gritter) $.gritter.remove(last_gritter);
							last_gritter = $.gritter.add({
								title: 'Foto KTP Updated!',
								text: 'Success upload profile photo.',
								class_name: 'gritter-info gritter-center'
							});

						}, parseInt(Math.random() * 800 + 800))

						return deferred.promise();

						// ***END OF UPDATE AVATAR HERE*** //
					},

					success: function (response, newValue) {
						// ***Custome Upload*** //	
						upload_ktp();
					}
				})
			} catch (e) { }
		} else {
			$.ajax({
				type: "POST",
				processData: false, //important
				contentType: false, //important	
				url: "inc/karyawan/data_karyawan_draft/proc_karyawan.php",
				data: fd,
				success: function (responseText) {
					// $.gritter.add({
					// 	title: responseText.trim(),
					// 	class_name: 'gritter-info' + ' gritter-light'
					// });
				},
				error: function (data) {
					alert(data);
					$("#progress").hide();
				}
			});
		}
	}

	function upload_sim() {
		var fd = new FormData();
		var total_foto = $('input[name="foto_sim"]').get(0).files.length;
		var nik = $('#nik').val();
		var d = new Date();
		var gtime = d.getTime();
		var nama_foto = 'foto_sim-' + gtime;
		var jenis = 'foto_sim';
		fd.append('nik', nik);
		fd.append('nama_foto', nama_foto);
		fd.append('act', "save_foto");
		fd.append('jenis', jenis);
		// for (var x = 0; x < total_foto; x++) {
		var file_foto = $('input[name="foto_sim"]').get(0).files[0];
		// console.log(file_foto);
		fd.append('file_foto', file_foto);
		// }
		fd.append('total_foto', total_foto);
		fd.append('gtime', gtime);
		if (nik == "") {
			alert('NIK harus diisi terlebih dahulu');
			$('.foto-sim').html('<img id="foto_sim" class="editable img-responsive" alt="Alexs Avatar" src="assets/images/avatars/profile-pic.jpg" />');
			try { //ie8 throws some harmless exceptions, so let's catch'em

				//first let's add a fake appendChild method for Image element for browsers that have a problem with this
				//because editable plugin calls appendChild, and it causes errors on IE at unpredicted points
				try {
					document.createElement('IMG').appendChild(document.createElement('B'));
				} catch (e) {
					Image.prototype.appendChild = function (el) { }
				}

				var nik = $('#nik').val();

				var last_gritter
				$('#foto_sim').editable({
					type: 'image',
					name: 'foto_sim',
					value: null,
					//onblur: 'ignore',  //don't reset or hide editable onblur?!
					image: {
						//specify ace file input plugin's options here
						btn_choose: 'Change Photo',
						droppable: true,
						maxSize: 1100000, //~1000Kb

						//and a few extra ones here
						name: 'foto_sim', //put the field name here as well, will be used inside the custom plugin
						on_error: function (error_type) { //on_error function will be called when the selected file has a problem
							if (last_gritter) $.gritter.remove(last_gritter);
							if (error_type == 1) { //file format error
								last_gritter = $.gritter.add({
									title: 'File is not an image!',
									text: 'Please choose a jpg|gif|png image!',
									class_name: 'gritter-error gritter-center'
								});
							} else if (error_type == 2) { //file size rror
								last_gritter = $.gritter.add({
									title: 'File too big!',
									// text: 'Image size should not exceed 100Kb!',
									text: 'Image size should not exceed 1MB!',
									class_name: 'gritter-error gritter-center'
								});
							} else { //other error
							}
						},
						on_success: function () {
							$.gritter.removeAll();
						}
					},
					url: function (params) {
						// ***UPDATE AVATAR HERE*** //
						//for a working upload example you can replace the contents of this function with 
						//examples/profile-avatar-update.js

						var deferred = new $.Deferred

						var value = $('#foto_sim').next().find('input[type=hidden]:eq(0)').val();
						if (!value || value.length == 0) {
							deferred.resolve();
							return deferred.promise();
						}


						//dummy upload
						setTimeout(function () {
							if ("FileReader" in window) {
								//for browsers that have a thumbnail of selected image
								var thumb = $('#foto_sim').next().find('img').data('thumb');
								if (thumb) $('#foto_sim').get(0).src = thumb;
							}

							deferred.resolve({
								'status': 'OK'
							});

							if (last_gritter) $.gritter.remove(last_gritter);
							last_gritter = $.gritter.add({
								title: 'Foto SIM Updated!',
								text: 'Success upload profile photo.',
								class_name: 'gritter-info gritter-center'
							});

						}, parseInt(Math.random() * 800 + 800))

						return deferred.promise();

						// ***END OF UPDATE AVATAR HERE*** //
					},

					success: function (response, newValue) {
						// ***Custome Upload*** //	
						upload_sim();
					}
				})
			} catch (e) { }
		} else {
			$.ajax({
				type: "POST",
				processData: false, //important
				contentType: false, //important	
				url: "inc/karyawan/data_karyawan_draft/proc_karyawan.php",
				data: fd,
				success: function (responseText) {
					// $.gritter.add({
					// 	title: responseText.trim(),
					// 	class_name: 'gritter-info' + ' gritter-light'
					// });
				},
				error: function (data) {
					alert(data);
					$("#progress").hide();
				}
			});
		}
	}
</script>