<?php $sqlsrv_hris = DB::connection('sqlsrv_hris'); ?>
<?php
if (isset($_GET['no_hp']) && isset($_GET['kode_soal'])):

$sql_data = "SELECT id as id_peserta FROM [db_hris].[dbo].[table_biodata_pelamar] WHERE no_hp='" . $_GET['no_hp'] . "'";
$result_data = $sqlsrv_hris->query($sql_data);
$num_data = $sqlsrv_hris->num_rows($result_data);
$row_data = $sqlsrv_hris->fetch_array($result_data);
if (!isset($row_data['id_peserta'])): 
    echo '<script>$(document).ready(function () {exit();});</script>';
else:
echo '<input type="hidden" id="id_peserta" value="' . $row_data['id_peserta'] . '">';
$sql = "SELECT * FROM [db_hris].[dbo].[table_tes_soal_detail] WHERE kode_soal='" . $_GET['kode_soal'] . "' order by date_create";
$result = $sqlsrv_hris->query($sql);
$total_soal = $sqlsrv_hris->num_rows($result);
echo '<input type="hidden" id="kode_soal" value="' . $_GET['kode_soal'] . '">';
$no = 1;
?>
<div class="main-content-inner">
    <div class="breadcrumbs ace-save-state hide" id="breadcrumbs">
        <ul class="breadcrumb">
            <li>
                <i class="ace-icon fa fa-home home-icon"></i>
                <a href="?page=dashboard">Home</a>
            </li>
            <li class="active">Form Data Pelamar</li>
        </ul>
    </div>
    <div class="page-content">
        <div class="page-header">
            <h1>
                Form Data Pelamar
            </h1>
        </div>
        <div class="row">
            <div class="col-xs-12">
                <div class="widget-box">
                    <div class="widget-header">
                        <h4 class="widget-title lighter">Soal Tes</h4>
                    </div>
                    <div class="widget-body">
                        <div class="widget-main">
                            <div id="fuelux-wizard-container">
                                <div>
                                    <ul class="steps">
                                        <?php for ($i = 1; $i <= $total_soal; $i++): ?>
                                            <li data-step="<?php echo $i; ?>" class="active">
                                                <span class="step"><?php echo $i; ?></span>
                                                <span class="title"></span>
                                            </li>
                                        <?php endfor; ?>
                                        <li data-step="<?php echo ($total_soal + 1); ?>">
                                            <span class="step"><?php echo ($total_soal + 1); ?></span>
                                            <span class="title">Finish</span>
                                        </li>
                                    </ul>
                                </div>

                                <hr />

                                <div class="step-content pos-rel">
                                    <?php while ($row = $sqlsrv_hris->fetch_array($result)): ?>
                                        <div class="step-pane active" data-step="<?php echo $no; ?>">
                                            <h4 class="lighter block"></h4>

                                            <form class="form-horizontal" id="validation-form" method="get">
                                                <div class="form-group">
                                                    <!-- <label class="control-label col-xs-12 col-sm-3 no-padding-right" for="">&nbsp;</label> -->

                                                    <div class="col-xs-12 col-sm-9">
                                                        <div class="clearfix">
                                                            <input type="hidden" name="id_soal[]" value="<?php echo $row['id']; ?>">
                                                            <input type="hidden" name="nilai[]" value="<?php echo $row['nilai']; ?>">
                                                            <h4>
                                                                <p><?php echo $row['pertanyaan']; ?></p>
                                                            </h4>
                                                        </div>
                                                    </div>
                                                </div>

                                                <div class="space-2"></div>

                                                <div class="form-group">
                                                    <!-- <label class="control-label col-xs-12 col-sm-3 no-padding-right">&nbsp;</label> -->

                                                    <div class="col-xs-12 col-sm-9">
                                                        <div>
                                                            <label class="line-height-1 blue">
                                                                <input name="jawaban[]" value="a1" type="radio"
                                                                    class="ace" />
                                                                <span class="lbl"> <?php echo $row['a1']; ?></span>
                                                            </label>
                                                        </div>

                                                        <div>
                                                            <label class="line-height-1 blue">
                                                                <input name="jawaban[]" value="a2" type="radio"
                                                                    class="ace" />
                                                                <span class="lbl"> <?php echo $row['a2']; ?></span>
                                                            </label>
                                                        </div>

                                                        <div>
                                                            <label class="line-height-1 blue">
                                                                <input name="jawaban[]" value="a3" type="radio"
                                                                    class="ace" />
                                                                <span class="lbl"> <?php echo $row['a3']; ?></span>
                                                            </label>
                                                        </div>

                                                        <div>
                                                            <label class="line-height-1 blue">
                                                                <input name="jawaban[]" value="a4" type="radio"
                                                                    class="ace" />
                                                                <span class="lbl"> <?php echo $row['a4']; ?></span>
                                                            </label>
                                                        </div>

                                                        <div>
                                                            <label class="line-height-1 blue">
                                                                <input name="jawaban[]" value="a5" type="radio"
                                                                    class="ace" />
                                                                <span class="lbl"> <?php echo $row['a5']; ?></span>
                                                            </label>
                                                        </div>
                                                    </div>
                                                </div>
                                            </form>
                                        </div>
                                        <?php $no++; endwhile; ?>
                                    <div class="step-pane" data-step="<?php echo ($total_soal + 1); ?>">
                                        <div class="center">
                                            <h3 class="blue">Save Your Answer</h3>
                                            Are you sure to finish your test? <br> click save to send your answers.
                                        </div>
                                    </div>
                                </div>
                            </div>

                            <hr />
                            <div class="wizard-actions">
                                <button class="btn btn-prev">
                                    <i class="ace-icon fa fa-arrow-left"></i>
                                    Prev
                                </button>

                                <button class="btn btn-success btn-next" data-last="Save">
                                    Next
                                    <i class="ace-icon fa fa-arrow-right icon-on-right"></i>
                                </button>
                            </div>
                        </div><!-- /.widget-main -->
                    </div>
                </div>
            </div>
        </div>
    </div>
</div>
<?php endif; ?>
<script>
    $(document).ready(function () {
        $('.chosen-select').css('width', '450px').select2({ allowClear: true })
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

        $('.select2').css('width', '200px').select2({ allowClear: true })
            .on('change', function () {
                $(this).closest('form').validate().element($(this));
            });


        var $validation = true;

        $('#fuelux-wizard-container')
            .ace_wizard({
                //step: 2 //optional argument. wizard will jump to step "2" at first
                //buttons: '.wizard-actions:eq(0)'
            })
            .on('actionclicked.fu.wizard', function (e, info) {
                if (info.step == 1 && $validation) {
                    if (!$('#validation-form').valid()) e.preventDefault();
                }
            })
            //.on('changed.fu.wizard', function() {
            //})
            .on('finished.fu.wizard', function (e) {
                // bootbox.dialog({
                //     message: "Thank you! Your information was successfully saved!",
                //     buttons: {
                //         "success": {
                //             "label": "OK",
                //             "className": "btn-sm btn-primary"
                //         }
                //     }
                // });

                save();
            }).on('stepclick.fu.wizard', function (e) {
                //e.preventDefault();//this will prevent clicking and selecting steps
            });


        //jump to a step
        /**
        var wizard = $('#fuelux-wizard-container').data('fu.wizard')
        wizard.currentStep = 3;
        wizard.setState();
        */

        //determine selected step
        //wizard.selectedItem().step



        //hide or show the other form which requires validation
        //this is for demo only, you usullay want just one form in your application
        $('#skip-validation').removeAttr('checked').on('click', function () {
            $validation = this.checked;
            if (this.checked) {
                $('#sample-form').hide();
                $('#validation-form').removeClass('hide');
            }
            else {
                $('#validation-form').addClass('hide');
                $('#sample-form').show();
            }
        })



        //documentation : http://docs.jquery.com/Plugins/Validation/validate


        $.mask.definitions['~'] = '[+-]';
        $('#phone').mask('(999) 999-9999');

        jQuery.validator.addMethod("phone", function (value, element) {
            return this.optional(element) || /^\(\d{3}\) \d{3}\-\d{4}( x\d{1,6})?$/.test(value);
        }, "Enter a valid phone number.");

        $('#validation-form').validate({
            errorElement: 'div',
            errorClass: 'help-block',
            focusInvalid: false,
            ignore: "",
            rules: {
                email: {
                    required: true,
                    email: true
                },
                password: {
                    required: true,
                    minlength: 5
                },
                password2: {
                    required: true,
                    minlength: 5,
                    equalTo: "#password"
                },
                name: {
                    required: true
                },
                phone: {
                    required: true,
                    phone: 'required'
                },
                url: {
                    required: true,
                    url: true
                },
                comment: {
                    required: true
                },
                state: {
                    required: true
                },
                platform: {
                    required: true
                },
                subscription: {
                    required: true
                },
                gender: {
                    required: true,
                },
                agree: {
                    required: true,
                }
            },

            messages: {
                email: {
                    required: "Please provide a valid email.",
                    email: "Please provide a valid email."
                },
                password: {
                    required: "Please specify a password.",
                    minlength: "Please specify a secure password."
                },
                state: "Please choose state",
                subscription: "Please choose at least one option",
                gender: "Please choose gender",
                agree: "Please accept our policy"
            },


            highlight: function (e) {
                $(e).closest('.form-group').removeClass('has-info').addClass('has-error');
            },

            success: function (e) {
                $(e).closest('.form-group').removeClass('has-error');//.addClass('has-info');
                $(e).remove();
            },

            errorPlacement: function (error, element) {
                if (element.is('input[type=checkbox]') || element.is('input[type=radio]')) {
                    var controls = element.closest('div[class*="col-"]');
                    if (controls.find(':checkbox,:radio').length > 1) controls.append(error);
                    else error.insertAfter(element.nextAll('.lbl:eq(0)').eq(0));
                }
                else if (element.is('.select2')) {
                    error.insertAfter(element.siblings('[class*="select2-container"]:eq(0)'));
                }
                else if (element.is('.chosen-select')) {
                    error.insertAfter(element.siblings('[class*="chosen-container"]:eq(0)'));
                }
                else error.insertAfter(element.parent());
            },

            submitHandler: function (form) {
            },
            invalidHandler: function (form) {
            }
        });




        $('#modal-wizard-container').ace_wizard();
        $('#modal-wizard .wizard-actions .btn[data-dismiss=modal]').removeAttr('disabled');


        /**
        $('#date').datepicker({autoclose:true}).on('changeDate', function(ev) {
            $(this).closest('form').validate().element($(this));
        });
        
        $('#mychosen').chosen().on('change', function(ev) {
            $(this).closest('form').validate().element($(this));
        });
        */


        $(document).one('ajaxloadstart.page', function (e) {
            //in ajax mode, remove remaining elements before leaving page
            $('[class*=select2]').remove();
        });
    });

    function save() {
        var id_peserta = $("#id_peserta").val();
        var id_soal = $('input[name="id_soal[]"]').map(function () {
            return $(this).val();
        }).get();
        var jawaban = $('input[name="jawaban[]"]:checked').map(function () {
            return $(this).val();
        }).get();
        var nilai = $('input[name="nilai[]"]').map(function () {
            return $(this).val();
        }).get();

        var kode_soal = $('#kode_soal').val();

        var fd = new FormData();    
        fd.append('id_peserta', id_peserta);
        fd.append('kode_soal', kode_soal);
        fd.append('id_soal', id_soal);
        fd.append('jawaban', jawaban);
        fd.append('nilai', nilai);
        fd.append('act', 'save_tes');

        $.ajax({
            type: "POST",
            url: "inc/career/data_pelamar/add_edit_delete.php",
            // traditional: true,
            data: fd,
            processData: false,
            contentType: false,
            // data: {
            //     act: 'save_tes',
            //     id_peserta: id_peserta,
            //     id_soal: id_soal,
            //     jawaban: jawaban,
            //     nilai: nilai,
            //     kode_soal: kode_soal
            // },
            success: function (data) {
                alert(data);
                if (data.trim() == "Data Berhasil Disimpan") {
                    location.href = "?page=data_pelamar&act=finish";
                } else {
                    // location.href = "home.php";
                }
            },
            error: function (msg) {
                alert(msg);
            }
        });
    }

    function exit() {
        location.href = "home.php";
    }
    
</script>
<?php endif; ?>