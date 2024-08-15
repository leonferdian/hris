<script>
$(document).ready(function() {
	
	//alert('l');
	/*$('#all').click(function() {
		location.href="?page=activity_stream";
	});
	$('#grade1').click(function() {
		location.href="?page=activity_stream&grade_level=grade1";
	});
	$('#grade2').click(function() {
		location.href="?page=activity_stream&grade_level=grade2";
	});
	$('#grade3').click(function() {
		location.href="?page=activity_stream&grade_level=grade3";
	});
	*/
	$(".select2").select2();
});
function view_act(){
	
	
	
	var slc_act = $("#slc_act").val();
	var slc_grade = $("#slc_grade").val();
	var leveling = $("#slc_grade").find(':selected').attr('data-action');
	var slc_user = $("#slc_user").val();
	var kind_act='';
	var kind_grade='';
	var kind_user='';
	//kind_act = new Array();
	/* i=0;
	$("input.cmb_act:checked").each(function(){
		kind_act[i]=$(this).val();
		i++;
	})*/
	if(slc_act==null){kind_act='';}else{kind_act=slc_act;}
	if(slc_grade==null){kind_grade='';}else{kind_grade=slc_grade;}
	if(slc_user==null){kind_user='';}else{kind_user=slc_user;}
	//alert(kind_grade);
	cmb_grade = new Array();
	i2=0;
	$("input.cmb_grade:checked").each(function(){
		cmb_grade[i2]=$(this).val();
		i2++;
	})
	//var grade = cmb_grade.replace(',', ' ');
	//alert(cmb_grade);
	$("#progress_act").show();
	$.ajax({
		type:"GET",
		url:"inc/activity_stream/view_act.php?kind_act="+kind_act+"&grade="+kind_grade+"&id_user="+kind_user,
		success:function(data){
			//alert(data);
			$("#progress_act").hide();
			$("#isi_activity").html(data);
		},
		error:function(msg){
			alert(msg);
		}
	   });
}

</script>
<?php 
if(isset($_GET['grade_level']) and $_GET['grade_level']=='grade1'){
	include"grade_level1.php";
}
else if(isset($_GET['grade_level']) and $_GET['grade_level']=='grade2'){
	include"grade_level2.php";
}
else if(isset($_GET['grade_level']) and $_GET['grade_level']=='grade3'){
	include"grade_level3.php";
}
else if(isset($_GET['act']) and $_GET['act']=='det_actstream'){
	include"det_actstream.php";
}
else if(isset($_GET['act']) and $_GET['act']=='all_notification'){
	include"all_notification.php";
}
else{
	$company = mysqli_query($db_new_nva,"SELECT * from tbl_master_company where id_company= '".$_SESSION['company']."' ");
	$dcompany = mysqli_fetch_array($company)
	?>
<div class="content animate-panel">
<div class="row">
            <div class="col-lg-12 text-center m-t-md">
                <h2>
                    Welcome To <?php echo $dcompany['name_company'];?> Task Management
                </h2>

                <p>
                    TMS is<strong> Task Management System.</strong> App to manage Employee Activity, Task, Project And Agenda
                </p>
            </div>
</div>
<div class="row">
            <div class="col-lg-12">
                <div class="hpanel">
                    <div class="panel-heading">
                        <div class="panel-tools">
                            <a class="showhide"><i class="fa fa-chevron-up"></i></a>
                            <a class="closebox"><i class="fa fa-times"></i></a>
                        </div>
                        Dashboard information and statistics
                    </div>
                    <div class="panel-body">
                        <div class="row">
                            <div class="col-md-3 text-center">
                                <div class="small">
                                    <i class="fa fa-bolt"></i> User Visit
                                </div>
                                <div>
                                    <h1 class="font-extra-bold m-t-xl m-b-xs">
									<?php 
									$sql_visite = mysqli_query($db_new_nva,"select count(email) as jml_visitor from tbl_visitor where month(tgl_visitor)='".date('m')."' and email = '".$_SESSION['username']."'");
									$jml_visitor = mysqli_fetch_array($sql_visite);
									echo $jml_visitor['jml_visitor'];
									?>
                                       
                                    </h1>
                                    <small>Visite In This month</small>
                                </div>
                                <div class="small m-t-xl">
							   <?php 
							   $start_act = mysqli_query($db_new_nva,"SELECT DISTINCT DATE_FORMAT(v.tgl_visitor,'%Y-%m-%d') as tgl FROM tbl_visitor v WHERE v.email = '".$_SESSION['username']."' ORDER BY DATE_FORMAT(v.tgl_visitor,'%Y-%m-%d') ASC LIMIT 1");
							   $dstart_act = mysqli_fetch_array($start_act);
							   ?>
                                    <i class="fa fa-clock-o"></i> Data from <?php echo date("F", strtotime($dstart_act['tgl']));?>
                                </div>
                            </div>
                            <div class="col-md-6">
                                <div class="text-center small">
                                    <i class="fa fa-laptop"></i> Active users in current month (<?php echo date('F');?>)
                                </div>
                                <div class="flot-chart" style="height: 160px">
                                    <div class="flot-chart-content" id="flot-line-chart"></div>
                                </div><br>
								
								
								<div class="col-sm-12" style="display:none;">
								<div class="small">
                                    <i class="fa fa-clock-o"></i> Active duration
                                </div>
                                <div>
									<?php
									function format_interval(DateInterval $interval) {
										$result = "";
										if ($interval->y) { $result .= $interval->format("%y years "); }
										if ($interval->m) { $result .= $interval->format("%m months "); }
										if ($interval->d) { $result .= $interval->format("%d days "); }
										if ($interval->h) { $result .= $interval->format("%h hours "); }
										if ($interval->i) { $result .= $interval->format("%i minutes "); }
										if ($interval->s) { $result .= $interval->format("%s seconds "); }

										return $result;
									}

									$first_date = new DateTime(date("Y-m-d", strtotime($dstart_act['tgl'])));
									$second_date = new DateTime(date("Y-m-d"));

									$difference = $first_date->diff($second_date);
									$last_login = mysqli_query($db_new_nva,"SELECT user_lastlogin FROM `user` where id_user = '".$_SESSION['id_user']."' ");
									$dlast_login = mysqli_fetch_array($last_login);
									?>
                                    <h1 class="font-extra-bold m-t-xl m-b-xs">
                                        <?php echo format_interval($difference);?>
                                    </h1>
                                   
                                </div>
                                <div class="small m-t-xl">
                                    <i class="fa fa-clock-o"></i> Last active in <?php echo $dlast_login['user_lastlogin'];?>
                                </div>
								<!--<label><b>Filter By Grade </b></label>
								<label class="checkbox-inline"> 
								<input type="checkbox" value=" or g.up1 = <?php echo "'".$_SESSION['id_user']."'";?>" id="grade1" class="cmb_grade" name="cmb_grade[]" checked> 1 </label> <label class="checkbox-inline">
								<input type="checkbox" value="or g.up2 = <?php echo "'".$_SESSION['id_user']."'";?>" id="grade2" class="cmb_grade" name="cmb_grade[]"> 2 </label> <label class="checkbox-inline">
								<input type="checkbox" value="or g.up3 = <?php echo "'".$_SESSION['id_user']."'";?>" id="grade3" class="cmb_grade" name="cmb_grade[]"> 3 </label>
								
								
								<label class="checkbox-inline"> 
								<select class="form-control m-b" name="slc_act" id="slc_act">
									<option value=''>--All Activity--</option>
									<option value='Scheduled'>Scheduled Work Report</option>
									<option value='Unscheduled'>Unscheduled Work Report</option>
									<option value='Planned Work Report'>Planned Work Report</option>
									<option value='Unplanned Work Report'>Unplanned Work Report</option>
									<option value='Add User'>Add User</option>
								</select>
								</label><br>
								--><br>
								
								<!--<label class="checkbox-inline"> 
								<input type="checkbox" value="'Scheduled'" id="act1" class="cmb_act" name="cmb_act[]"> Scheduled</label> <label class="checkbox-inline">
								<input type="checkbox" value="'Unscheduled'" id="act1" class="cmb_act" name="cmb_act[]" > Unscheduled</label> <label class="checkbox-inline">
								<input type="checkbox" value="'Planned Work Report'" id="act1" class="cmb_act" name="cmb_act[]" > Planned</label> <label class="checkbox-inline">
								<input type="checkbox" value="'Unplanned Work Report'" id="act2" class="cmb_act" name="cmb_act[]"> Unplanned </label> <label class="checkbox-inline">
								<button data-toggle="button" class="btn btn-xs btn-warning" type="button" onclick="view_act()">View Activity</button>-->
								
															
														
								
								</div>
								
                            </div>
                            <div class="col-md-3 text-center">
								
								<dl class="dl-horizontal" style="text-align:left">
								 <dt><b>Filter By Grade </b></dt>
								 <dd>
									<select style="display:none;">
									<option data-action="1" value=" or g.up1 = <?php echo "'".$_SESSION['id_user']."'";?>" selected>1</option>
									<option data-action="2" value="or g.up2 = <?php echo "'".$_SESSION['id_user']."'";?>">2</option>
									<option data-action="3" value="or g.up3 = <?php echo "'".$_SESSION['id_user']."'";?>">3</option>
									<option data-action="4" value="or g.up4 = <?php echo "'".$_SESSION['id_user']."'";?>">4</option>
									<option data-action="5" value="or g.up5 = <?php echo "'".$_SESSION['id_user']."'";?>">5</option>
									<option data-action="6" value="or g.up6 = <?php echo "'".$_SESSION['id_user']."'";?>">6</option>
									</select>
									<select class="selectpicker" title="Select Activity ..." data-width="100%" multiple data-actions-box="true" data-live-search="true" ng-model="model.select" id="slc_grade" name="slc_grade">
									<option data-action="1" value="1">1</option>
									<option data-action="2" value="2">2</option>
									<option data-action="3" value="3">3</option>
									<option data-action="4" value="4">4</option>
									<option data-action="5" value="5">5</option>
									<option data-action="6" value="6">6</option>
									</select>
								 </dd><br>
								 <dt><b>Filter By Activity </b></dt>
								 <dd>
									<select class="selectpicker" title="Select Activity ..." data-width="100%" multiple data-actions-box="true" data-live-search="true" ng-model="model.select" id="slc_act" name="slc_act" >
									<option value="'Scheduled'">Scheduled Work Plan</option>
									<option value="'Unscheduled'">Unscheduled Work Plan</option>
									<option value="'Planned Work Report'">Planned Work Report</option>
									<option value="'Unplanned Work Report'">Unplanned Work Report</option>
									<option value="'Add User'">Add User</option>
									<option value="'Task'">Task</option>
									</select>
								 </dd><br>
								  <dt><b>Filter By User </b></dt>
								  <dd>
									<select class="select2 form-control" multiple="multiple" id="slc_user" placeholder="Type To Search" width="90">
											<?php 
											$sql_person = mysqli_query($db_new_nva,"SELECT * 
											FROM
											(
											-- ========================
											SELECT u.id_user,u.nama,u.user_leveling  FROM USER AS u 
											INNER JOIN tbl_grade AS g ON u.id_user = g.id_user 
											WHERE g.up1 = '".$_SESSION['id_user']."'  OR g.up2 = '".$_SESSION['id_user']."' OR g.up3='".$_SESSION['id_user']."' OR g.up4='".$_SESSION['id_user']."' OR g.up5='".$_SESSION['id_user']."' 
											OR g.up6='".$_SESSION['id_user']."' OR u.id_user='".$_SESSION['id_user']."' 
											UNION ALL
											-- --------------------
											SELECT u.id_user,u.nama, u.user_leveling FROM USER AS u
											INNER JOIN tbl_grade AS g ON u.id_user = g.id_user
											WHERE 
											g.up1 IN( SELECT user_controlled  FROM tbl_pengawas AS p WHERE user_pengawas='".$_SESSION['id_user']."') 
											 OR g.up2 IN( SELECT user_controlled  FROM tbl_pengawas AS p WHERE user_pengawas='".$_SESSION['id_user']."')  
											  OR g.up3 IN( SELECT user_controlled  FROM tbl_pengawas AS p WHERE user_pengawas='".$_SESSION['id_user']."')   
											-- =======================
											UNION ALL
											SELECT u.id_user,u.nama, u.user_leveling FROM USER AS u
											INNER JOIN tbl_pengawas AS p ON u.id_user = p.user_controlled
											WHERE p.user_pengawas='".$_SESSION['id_user']."'

											  ) AS xyxy
											  GROUP BY nama,user_leveling");
											?>
											<?php while($dperson = mysqli_fetch_array($sql_person)){?>
												<option  value="<?php echo $dperson['id_user'];?>"><?php echo $dperson['nama'];?></option>
											<?php }?>
											   
									</select>
								 </dd>
								 <br>
								 <dd>
									 <button data-toggle="button" class="btn btn-xs btn-warning" type="button" onclick="view_act()">View Activity</button>
									 <span id="progress_act" style="display:none"><img src="images/loading.gif" width="20" /></span>
								 </dd>
								</dl>	
                                
                            </div>
                        </div>
                    </div>
                    
                </div>
            </div>
</div>

<div class="row" id="isi_activity">
<div id="isi_act">

</div>
 <div class="col-md-12" id="loading-info" style="display:none">	
 <span id="progress_load" ><img src="images/loading.gif" width="20" /> Please Wait...</span>
 </div>


	
	
</div>
</div>

<script>

    $(function () {

        /**
         * Flot charts data and options
         */
		 <?php 
		 $sql_visit = mysqli_query($db_new_nva,"SELECT DISTINCT DATE_FORMAT(v.tgl_visitor,'%d') AS tgl,
		(SELECT COUNT(email) FROM tbl_visitor WHERE DAY(tgl_visitor)= DATE_FORMAT(v.tgl_visitor,'%d') AND MONTH(tgl_visitor)='".date('m')."' AND email = '".$_SESSION['username']."') AS jml
		 FROM tbl_visitor v WHERE MONTH(v.tgl_visitor)='".date('m')."' AND v.email = '".$_SESSION['username']."' ");
		 $num_visit = mysqli_num_rows($sql_visit);
		 $angka_visit = 1;
		 $koma_visit = '';
		 ?>
        var data1 = [
		<?php while($dvisite = mysqli_fetch_array($sql_visit)){
			if($angka_visit!=$num_visit){$koma_visit=',';}else{$koma_visit='';}
			?>
		[<?php echo $dvisite['tgl'];?>, <?php echo $dvisite['jml'];?>]<?php echo $koma_visit;?> 
		<?php $angka_visit++;}?>
		];
        var data2 = [ [0, 56], [1, 49], [2, 41], [3, 38], [4, 46], [5, 67], [6, 57], [7, 59] ];

        var chartUsersOptions = {
            series: {
                splines: {
                    show: true,
                    tension: 0.4,
                    lineWidth: 1,
                    fill: 0.4
                },
            },
            grid: {
                tickColor: "#f0f0f0",
                borderWidth: 1,
                borderColor: 'f0f0f0',
                color: '#6a6c6f'
            },
            colors: [ "#62cb31", "#efefef"],
        };

        $.plot($("#flot-line-chart"), [data1], chartUsersOptions);

        /**
         * Flot charts 2 data and options
         */
        

        



    });
// load auto
var track_page = 1; //track user scroll as page number, right now page number is 1
var loading  = false; //prevents multiple loads

load_contents(track_page); //initial content load

$(window).scroll(function() { //detect page scroll
	if($(window).scrollTop() + $(window).height() >= $(document).height()) { //if user scrolled to bottom of the page
	    
		track_page++; //page number increment
		load_contents(track_page); //load content	
	}
});		
//Ajax load function
function load_contents(track_page){
    if(loading == false){
		loading = true;  //set loading flag on
		$('#loading-info').show(); //show loading animation 
		
		$.post( 'inc/activity_stream/fetch_load.php', {'page': track_page}, function(data){
			loading = false; //set loading flag off once the content is loaded
			if(data.trim().length == 0){
				//notify user if nothing to load
				$('#loading-info').html("No more records!");
				return;
			}
			$('#loading-info').hide(); //hide loading animation once data is received
			$("#isi_act").append(data); //append data into #results element
		
		}).fail(function(xhr, ajaxOptions, thrownError) { //any errors?
			alert(thrownError); //alert with HTTP error
		})

		
		
	}
}
</script>
<?php }?>

