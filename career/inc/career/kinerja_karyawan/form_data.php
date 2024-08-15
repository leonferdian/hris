<?php
$sqlsrv_hris = DB::connection('sqlsrv_hris');
$sql_select = "select * FROM [db_hris].[dbo].[table_penilaian_karyawan] where kode_penilaian = '" . $_GET['kode_penilaian'] . "'";
$stmt_select = $sqlsrv_hris->query($sql_select);
$num_select = $sqlsrv_hris->num_rows($stmt_select);
$row_select = $sqlsrv_hris->fetch_array($stmt_select);
$stmt_detail = $sqlsrv_hris->query($sql_select);
$no = 1;
if ($num_select > 0):
    ?>
    <div class="main-content-inner">
        <div class="breadcrumbs ace-save-state hide" id="breadcrumbs">
            <ul class="breadcrumb">
                <li>
                    <i class="ace-icon fa fa-home home-icon"></i>
                    <a href="?page=dashboard">Home</a>
                </li>
                <li class="active">Form Penilaian Hasil Kinerja Karyawan</li>
            </ul>
        </div>
        <div class="page-content">
            <div class="page-header">
                <div class="pull-right">
                    <a href="?page=kinerja_karyawan"> <i class="fa fa-arrow-left"></i> Back to main page</a>
                </div>
                <h1>
                    Form Penilaian Hasil Kinerja Karyawan
                </h1>
            </div>
            <div class="row">
                <div class="col-xs-12">
                    <div class="widget-body">
                        <span id="progress" style="display:none"><img src="images/loading.gif" width="20" /> Please Wait...
                        </span>
                        <div id="list_vol">
                            <form id="dataForm" class="form-horizontal">
                                <input type="hidden" name="kode_penilaian" value="<?php echo $row_select['kode_penilaian']; ?>" />
                                <div class="col-xs-6">
                                    <div class="form-group">
                                        <label class="col-sm-2">Judul</label>
                                        <div class="col-sm-5">
                                            <input type="text" class="form-control input-sm" id="nama" name="nama"
                                                value="<?php echo trim($row_select['nama']); ?>" readonly>
                                        </div>
                                    </div>
                                    <div class="form-group">
                                        <label class="col-sm-2">Divisi</label>
                                        <div class="col-sm-5">
                                            <input type="text" class="form-control input-sm" id="divisi" name="divisi"
                                                value="<?php echo trim($row_select['divisi']); ?>" readonly>
                                        </div>
                                    </div>
                                    <div class="form-group">
                                        <label class="col-sm-2">Tahun</label>
                                        <div class="col-sm-5">
                                            <input type="text" class="form-control input-sm" id="tahun" name="tahun"
                                                value="<?php echo trim($row_select['tahun']); ?>" readonly>
                                        </div>
                                    </div>
                                </div>
                                <div class="col-xs-6">
                                    <div class="form-group">
                                        <label class="col-sm-2">Nama Karyawan</label>
                                        <div class="col-sm-5">
                                            <select class="form-control input-sm select2" name="nama_karyawan"
                                                id="nama_karyawan" data-placeholder="Pilih Karyawan" onchange="">
                                                <option value="">Select Karyawan</option>
                                                <?php
                                                $sql = "SELECT * FROM [db_hris].[dbo].[table_karyawan] order by [nama]";
                                                $stmt = $sqlsrv_hris->query($sql);
                                                while ($row = $sqlsrv_hris->fetch_array($stmt)):
                                                    echo '<option data-nik="' . $row['nik'] . '" data-jabatan="' . $row['jabatan'] . '" data-tahun="' . $row['lama_kerja_th'] . '" data-bulan="' . $row['lama_kerja_bln'] . '" value="' . $row['nama'] . '">' . strtoupper($row['nama']) . '</option>';
                                                endwhile;
                                                ?>
                                            </select>
                                            <input type="hidden" id="nik" name="nik" />
                                        </div>
                                    </div>
                                    <div class="form-group">
                                        <label class="col-sm-2">Jabatan</label>
                                        <div class="col-sm-5">
                                            <input type="text" class="form-control input-sm" id="jabatan" name="jabatan" readonly>
                                        </div>
                                    </div>
                                    <div class="form-group">
                                        <label class="col-sm-2">Masa Kerja</label>
                                        <div class="col-sm-5">
                                            <input type="text" class="form-control input-sm" id="masa_kerja" name="masa_kerja" readonly>
                                        </div>
                                    </div>
                                </div>
                                <div class="col-xs-12">
                                    <hr>
                                    <h3>Score Detail</h3>
                                    <ul>
                                        <li><i>1 = Bad</i></li>
                                        <li><i>2 = Average</i></li>
                                        <li><i>3 = Good</i></li>
                                        <li><i>4 = Very Good</i></li>
                                        <li><i>5 = Excellent</i></li>
                                    </ul>
                                    <hr>
                                </div>
                                <div id="detail_soal">
                                    <?php while ($row_detail = $sqlsrv_hris->fetch_array($stmt_detail)): ?>
                                        <div id="isi_soal" class="col-xs-7">
                                            <div class="form-group">
                                                <label class="col-sm-2">
                                                    <b>No.<?php echo $no; ?></b>
                                                    <input type="hidden" name="id_penilaian[]"
                                                        value="<?php echo $row_detail['id']; ?>" />
                                                </label>
                                                <label class="col-sm-5">
                                                    <b><?php echo trim($row_detail['penilaian']); ?></b>
                                                </label>
                                            </div>
                                            <div class="form-group">
                                                <label class="col-sm-2">Category</label>
                                                <div class="col-xs-5">
                                                    <span><?php echo trim($row_detail['category']); ?></span>
                                                </div>
                                            </div>
                                            <div class="form-group">
                                                <label class="col-sm-2">Kriteria</label>
                                                <div class="col-xs-5">
                                                    <span><?php echo trim($row_detail['kriteria_scoring']); ?></span>
                                                </div>
                                            </div>
                                            <div class="form-group">
                                                <label class="col-sm-2">Score</label>
                                                <div class="col-xs-5">
                                                    <div class="rating inline"></div>
                                                </div>
                                            </div>
                                            <div class="form-group">
                                                <label class="col-sm-2">Grade</label>
                                                <div class="col-sm-5">
                                                    <input type="text" class="form-control input-sm" name="grade[]">
                                                </div>
                                            </div>
                                            <div class="form-group">
                                                <label class="col-sm-2">Nilai</label>
                                                <div class="col-sm-5">
                                                    <input type="text" class="form-control input-sm" name="nilai[]">
                                                </div>
                                            </div>
                                            <hr>
                                        </div>
                                    <?php $no++; endwhile; ?>
                                </div>
                                <div class="col-xs-12">
                                    <div class="form-group">
                                        <label class="col-sm-5 control-label">
                                        </label>
                                        <div class="col-sm-2">
                                            <input type="hidden" name="act" value="<?php echo "add_data"; ?>">
                                            <div class="pull-right">
                                                <button class="btn btn-sm btn-primary" onclick="">
                                                    <i class="ace-icon fa fa-check"></i>
                                                    Submit
                                                </button>
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
    <script>
        $(document).ready(function () {
            $('.select2').css('width', '270px').select2({ allowClear: true })
                .on('change', function () {
                    // $(this).closest('form').validate().element($(this));
                });

            $('.datepicker').datepicker({
                Format: 'YYYY-MM-DD',
                autoclose: true,
                todayHighlight: true
            })
                .prev().on(ace.click_event, function () {
                    $(this).next().focus();
                });

            $('.rating').raty({
				'cancel' : true,
				'half': false,
				'starType' : 'i',
                'scoreName': 'score[]'
					/**,
					
					'click': function() {
						setRatingColors.call(this);
					},
					'mouseover': function() {
						setRatingColors.call(this);
					},
					'mouseout': function() {
						setRatingColors.call(this);
					}*/
			})//.find('i:not(.star-raty)').addClass('grey');

            $('#nama_karyawan').change(function () { 
                var selectedOption = $(this).find('option:selected');
                var nik = selectedOption.data('nik');
                var jabatan = selectedOption.data('jabatan');
                var tahun = selectedOption.data('tahun');
                var bulan = selectedOption.data('bulan');
                $('#nik').val(nik);
                $('#jabatan').val(jabatan);
                $('#masa_kerja').val(tahun + " tahun " + bulan + " bulan");
            });

            get_nik();

            $(".knob").knob();

            $('#dataForm').submit(function (e) {
                e.preventDefault();

                $.ajax({
                    url: 'inc/career/kinerja_karyawan/add_edit_delete.php',
                    method: 'POST',
                    data: $(this).serialize(),
                    success: function (response) {
                        alert(response);
                        // $('#dataForm')[0].reset();
                        if (response.trim() == "Data Berhasil Disimpan") {
                            location.href = "?page=kinerja_karyawan";
                        }
                    }
                });
            });
        });

        function get_nik() {
            var selectedOption = $(this).find('option:selected');
            var nik = selectedOption.data('nik');
            var jabatan = selectedOption.data('jabatan');
            var tahun = selectedOption.data('tahun');
            var bulan = selectedOption.data('bulan');
            $('#nik').val(nik);
            $('#jabatan').val(jabatan);
            $('#masa_kerja').val(tahun + " tahun " + bulan + " bulan");
        }
    </script>
<?php else: ?>
    <div class="main-content">
        <div class="main-content-inner">
            <div class="breadcrumbs ace-save-state" id="breadcrumbs">
                <ul class="breadcrumb">
                    <li>
                        <i class="ace-icon fa fa-home home-icon"></i>
                        <a href="#">Home</a>
                    </li>

                    <li>
                        <a href="#">Other Pages</a>
                    </li>
                    <li class="active">Error 404</li>
                </ul><!-- /.breadcrumb -->

                <div class="nav-search" id="nav-search">
                    <form class="form-search">
                        <span class="input-icon">
                            <input type="text" placeholder="Search ..." class="nav-search-input" id="nav-search-input"
                                autocomplete="off" />
                            <i class="ace-icon fa fa-search nav-search-icon"></i>
                        </span>
                    </form>
                </div><!-- /.nav-search -->
            </div>

            <div class="page-content">
                <div class="row">
                    <div class="col-xs-12">
                        <!-- PAGE CONTENT BEGINS -->

                        <div class="error-container">
                            <div class="well">
                                <h1 class="grey lighter smaller">
                                    <span class="blue bigger-125">
                                        <i class="ace-icon fa fa-sitemap"></i>
                                        404
                                    </span>
                                    Page Not Found
                                </h1>

                                <hr />
                                <h2>Error: Form not found</h2><small> Make sure your code is right!</small>

                                <div>
                                    <form class="form-search">
                                        <span class="input-icon align-middle">
                                            <i class="ace-icon fa fa-search"></i>

                                            <input type="text" class="search-query" placeholder="Give it a search..." />
                                        </span>
                                        <button class="btn btn-sm" type="button">Go!</button>
                                    </form>

                                    <div class="space"></div>
                                    <h4 class="smaller">Try one of the following:</h4>

                                    <ul class="list-unstyled spaced inline bigger-110 margin-15">
                                        <li>
                                            <i class="ace-icon fa fa-hand-o-right blue"></i>
                                            Re-check the url for typos
                                        </li>

                                        <li>
                                            <i class="ace-icon fa fa-hand-o-right blue"></i>
                                            Read the faq
                                        </li>

                                        <li>
                                            <i class="ace-icon fa fa-hand-o-right blue"></i>
                                            Tell us about it
                                        </li>
                                    </ul>
                                </div>

                                <hr />
                                <div class="space"></div>

                                <div class="center">
                                    <a href="javascript:history.back()" class="btn btn-grey">
                                        <i class="ace-icon fa fa-arrow-left"></i>
                                        Go Back
                                    </a>

                                    <!-- <a href="#" class="btn btn-primary">
                                        <i class="ace-icon fa fa-tachometer"></i>
                                        Dashboard
                                    </a> -->
                                </div>
                            </div>
                        </div>

                        <!-- PAGE CONTENT ENDS -->
                    </div><!-- /.col -->
                </div><!-- /.row -->
            </div><!-- /.page-content -->
        </div>
    </div><!-- /.main-content -->
<?php endif; ?>