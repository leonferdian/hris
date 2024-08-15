<?php 
require('../../lib/database.php');

$row_count = 0;
$id = isset($_GET['id']) ? $_GET['id'] : '';
$jenis_id = isset($_GET['jenis_id']) ? $_GET['jenis_id'] : '';

if ($jenis_id == "id_webpages") {
    $table_menu = 'tbl_webpages';
    $tbl_display = 'webpage_display';
    $tbl_link = 'webpage_link';
    $tbl_include = 'webpage_include';
    $tbl_order = 'web_page_order';
    $tbl_access = 'webpage_acces';
    $tbl_icon ='webpage_icon';
    $tbl_case = 'web_page_case';
}
elseif ($jenis_id == "idmain_menu") {
    $table_menu = 'tbl_mainmenu';
    $index = 'mainmenu';
    $menu_access = 'mainmenu_acces';
    $parent = 'id_webpage';
}
elseif ($jenis_id == "id_submenu"){
    $table_menu = 'tbl_submenu';
    $index = 'submenu';
    $menu_access = 'submenu_access';
    $parent = 'id_mainmenu';
}

if($id) {
    $sql = "select * from $table_menu where $jenis_id='$id'";
    $stmt_exp = DB::connection('mysql_hris')->query($sql);  
    $row_me = $stmt_exp->fetch_array();
    $row_count = $stmt_exp->num_rows;
    #Berdasarkan desain tabel, jika struktur nama kolom berubah maka kode program juga harus diubah
    if ($row_me){
        if ($jenis_id == "id_webpages") {
            $display = $row_me[$tbl_display];
            $link = $row_me[$tbl_link];
            $include = $row_me[$tbl_include];
            $order = $row_me[$tbl_order];
            $access = $row_me[$tbl_access];
            $icon = $row_me[$tbl_icon];
            $case = $row_me[$tbl_case];
        } else {
            $display = $row_me[$index.'_display'];
            $link = $row_me[$index.'_link'];
            $include = $row_me[$index.'_include'];
            $order = $row_me[$index.'_order'];
            $access = $row_me[$menu_access];
            $case = $row_me[$index.'_case'];
            $parent_id = $row_me[$parent];
        }
    }
}

$parent_id = isset($parent_id) ? $parent_id : '';
$opt_webpage = '<option value="">None</option>';
$sql_wp = "SELECT id_webpages, webpage_display FROM tbl_webpages ORDER BY id_webpages";
$stmt_wp = DB::connection('mysql_hris')->query($sql_wp);
while ($row_wp = $stmt_wp->fetch_array()) {
    $s = $row_wp['id_webpages'] == $parent_id ? "selected" : "";
    $opt_webpage .= '<option value="'.$row_wp['id_webpages'].'" '.$s.'>'.$row_wp['webpage_display'].'</option>';
}

$opt_mainmenu = '<option value="">None</option>';
$sql_mm = "SELECT idmain_menu, mainmenu_display FROM tbl_mainmenu ORDER BY idmain_menu";
$stmt_mm = DB::connection('mysql_hris')->query($sql_mm);  
 while ($row_mm = $stmt_mm->fetch_array()) {
    $s = $row_mm['idmain_menu'] == $parent_id ? "selected" : "";
    $opt_mainmenu .= '<option value="'.$row_mm['idmain_menu'].'" '.$s.'>'.$row_mm['mainmenu_display'].'</option>';
}

?>
<div class="row">
	<div class="col-sm-12">
		<div class="tabbable">
				<ul class="nav nav-tabs padding-12 tab-color-blue background-blue" id="myTab4">
					<li class="active"><a data-toggle="tab" href="#form_add">Menu</a></li>
				</ul>
				<div class="tab-content">
					<div id="form_add" class="tab-pane in active">
                        <br>
						<div class="row" style="margin:5px;">
                            <form class="form-horizontal" method="post" action="inc/menus/proc_master.php?act=<?php echo !empty($id) ? "edit&jenis_id=$jenis_id&id=$id" : "save"; ?>" id="submit_form" enctype="multipart/form-data">		
                                <?php if($row_count > 0): ?>
                                <div class="form-group">
                                    <label for="id" class="col-sm-4 control-label">ID</label>
                                    <div class="col-sm-6">                                           
                                        <input type="text" class="form-control input-sm" id="id" name="id" value="<?php echo $row_me[$jenis_id]; ?>" readonly>                                                                                             
                                    </div>
                                </div>
                                <?php endif; ?>
                                <div class="form-group">
                                    <label for="display" class="col-sm-4 control-label">Menu Display</label>
                                    <div class="col-sm-6">                                           
                                        <input type="text" class="form-control input-sm" id="display" name="display" value="<?php if($row_count > 0) echo $display;  ?>">                                                                                             
                                    </div>
                                </div>
                                <?php if($row_count == 0): ?>
                                <div class="form-group">
                                    <label for="type" class="col-sm-4 control-label" required>Type</label>
                                    <div class="col-sm-6">              
                                        <select class="chosen-select4 form-control input-sm" id="type" name="type">
                                            <option>Select Menu Type</option>
                                            <option value="webpage">Webpage Menu</option>
                                            <option value="mainmenu">Main Menu</option>
                                            <option value="submenu">Submenu</option>
                                        </select>                                                                                             
                                    </div>
                                </div>
                                <?php endif; ?>
                                <?php if($jenis_id == "idmain_menu" || $row_count == 0): ?>
                                <div class="form-group" id="mainmenu" <?php if ($row_count == 0) echo 'style="display: none;"'; ?>>
                                    <label for="webpage_parent" class="col-sm-4 control-label">Webpage Parent</label>
                                    <div class="col-sm-6">              
                                        <select class="chosen-select4 form-control input-sm" id="webpage_parent" name="webpage_parent">
                                            <?php echo $opt_webpage; ?>
                                        </select>                                                                                             
                                    </div>
                                </div>
                                <?php endif; ?>
                                <?php if($jenis_id == "id_submenu" || $row_count == 0): ?>
                                <div class="form-group" id="submenu" <?php if ($row_count == 0) echo 'style="display: none;"'; ?>>
                                    <label for="mainmenu_parent" class="col-sm-4 control-label">Mainmenu Parent</label>
                                    <div class="col-sm-6">              
                                        <select class="chosen-select4 form-control input-sm" id="mainmenu_parent" name="mainmenu_parent">
                                            <?php echo $opt_mainmenu; ?>
                                        </select>                                                                                             
                                    </div>
                                </div>
                                <?php endif; ?>
                                <div class="form-group">
                                    <label for="link" class="col-sm-4 control-label">Link</label>
                                    <div class="col-sm-6">                                           
                                        <input type="text" class="form-control input-sm" id="link" name="link" value="<?php if($row_count > 0) echo $link;  ?>">                                                                                             
                                    </div>
                                </div>
                                <div class="form-group">
                                    <label for="include" class="col-sm-4 control-label">Include</label>
                                    <div class="col-sm-6">                                           
                                        <input type="text" class="form-control input-sm" id="include" name="include" value="<?php if($row_count > 0) echo $include;  ?>">                                                                                             
                                    </div>
                                </div>
                                <div class="form-group">
                                    <label for="order" class="col-sm-4 control-label">Order</label>
                                    <div class="col-sm-6">                                           
                                        <input type="text" class="form-control input-sm" id="order" name="order" value="<?php if($row_count > 0) echo $order;  ?>">                                                                                             
                                    </div>
                                </div>
                                <?php if ($jenis_id == "id_webpages" || $row_count == 0): ?>
                                <div class="form-group" id="webpage" <?php if ($row_count == 0)  echo 'style="display: none;"'; ?>>
                                    <label for="order" class="col-sm-4 control-label">Icon</label>
                                    <div class="col-sm-6">                                           
                                        <input type="text" class="form-control input-sm" id="icon" name="icon" value="<?php if($row_count > 0) echo $icon;  ?>">                                                                                             
                                    </div>
                                </div>
                                <?php endif; ?>
                                <div class="form-group">
                                    <label for="case" class="col-sm-4 control-label">Case</label>
                                    <div class="col-sm-6">                                           
                                        <input type="text" class="form-control input-sm" id="case" name="case" value="<?php if($row_count > 0) echo $case;  ?>">                                                                                             
                                    </div>
                                </div>
                                <div class="form-group">
                                    <label class="col-sm-4 control-label"></label>
                                    <div class="col-sm-6">
                                        <label for="access">
                                            <input type="checkbox" id="access" name="access" value="1" class="flat-red"<?php if(isset($access) && $access) echo ' checked';?>>
                                            Active
                                        </label>
                                    </div>
                                </div>
                                <div class="form-group">
                                    <label class="col-sm-4 control-label"></label>
                                    <div class="col-sm-6">
                                        <button class="btn btn-sm" data-dismiss="modal">
                                            <i class="ace-icon fa fa-times"></i>
                                            Cancel
                                        </button>

                                        <button type="submit" class="btn btn-sm btn-primary" onclick="save()">
                                            <i class="ace-icon fa fa-check"></i>
                                            Save
                                        </button>
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

<style>
.modal .modal-dialog {
	width: 40%;
	height: 80%;
}
</style>

<?php if ($row_count == 0): ?>
    <style>
        #webpage, #mainmenu, #submenu {
            display: none;
        }
    </style>
<?php endif; ?>

<script>
    $(document).ready(function() {
        $('#type').change( function(event) {
            var type = $(this).val();
            display_div(type);
        });
    });

    function display_div(show){
    document.getElementById('webpage').style.display = "none";
    document.getElementById('mainmenu').style.display = "none";
    document.getElementById('submenu').style.display = "none";
    document.getElementById(show).style.display = "block";
    }
</script>

<script>
    $("#submit_form").submit(function(event) {
        /* stop form from submitting normally */
        event.preventDefault();
        /* get the action attribute from the <form action=""> element */
        var form = $( this ),
        action_url = form.attr( 'action' );
        $.ajax({
                method: "POST",
                url: action_url,
                data: $("#submit_form").serialize()
              })
            .done(function(){
                $.notify("Data Saved", "success");
                document.getElementById("submit_form").reset();
            })
            .fail(function() {
                $.notify("Error Processing Data!", "error");
            })
            .always(function() {
                $('#modal_bootstrap').modal('hide');
                load_data();
            });
    });

    function load_data() {
        var queue = $('#progress1');
        queue.show();

        $.ajax({
            type:"GET",
            url:"inc/menus/view_table.php",
            success:function(data){											
                $("#get_table").html(data);
                queue.hide();
            },
            error:function(msg){
                alert(msg);
            }
        });
    }
</script>