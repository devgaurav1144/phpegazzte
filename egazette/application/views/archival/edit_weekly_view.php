<?php defined('BASEPATH') OR exit('No direct script access allowed'); ?>
<!-- CONTENT -->
<style nonce="8f0882ce3be14f201cadd0eff5726cbd">
    .loader{
        position: fixed;
        left: 0px;
        top: 0px;
        width: 100%;
        height: 100%;
        z-index: 9999;
        display: none;
        background: url('<?php echo base_url(); ?>assets/images/loader.gif') 50% 50% no-repeat rgb(249,249,249);
    }
    .file_icons_view {
        font-size: 40px;
        padding: 10px;
    }
</style>
<div class="loader"></div>
<section id="content">
    <div class="page page-forms-validate">
        <!-- bradcome -->
        <div class="bg-light lter b-b wrapper-md mb-10">
            <div class="row">
                <div class="col-sm-6 col-xs-12">
                    <h1 class="h3 m-0"></h1>
                </div>
            </div>
        </div>
        <ol class="breadcrumb">
            <li><a href="<?php echo base_url(); ?>applicants_login/dashboard">Dashboard</a></li>
            <li><a href="<?php echo base_url(); ?>archival/weekly">Weekly Gazette (Archival)</a></li>
            <li class="active">Edit Archival Gazette</li>
        </ol>
        <!-- row -->
        <div class="row">
            <div class="col-md-12">
                <section class="boxs">
                    <div class="boxs-header">
                        <h3 class="custom-font hb-blue"><strong>Edit Archival Gazette</strong></h3>
                    </div>
                    
                    <?php echo form_open('', array('name' => "archival_add", 'role' => "form", 'id' => "archival_add", 'enctype' => "multipart/form-data", 'method' => "post")); ?>    
                        <div class="boxs-body">
                            

                            <div class="row">
                                <div class="form-group col-md-4">
                                    <label for="username">Gazette Type : <span class="asterisk">*</span></label>
                                    <select name="gazette_type_id" id="gazette_type_id" class="form-control" required>
                                        <option value="2">Weekly</option>
                                        
                                    </select>
                                    
                                </div>
                                <div class="form-group col-md-4">
                                    <label for="username">Week : <span class="asterisk">*</span></label>
                                    <select name="week_id" id="week_id" class="form-control" required>
                                        <option value="">Select Week</option>
                                        <option value="1" <?php if ($gz_dets->week_id == 1) { echo 'selected'; } ?>>Week 1</option>
                                        <option value="2" <?php if ($gz_dets->week_id == 2) { echo 'selected'; } ?>>Week 2</option>
                                        <option value="3" <?php if ($gz_dets->week_id == 3) { echo 'selected'; } ?>>Week 3</option>
                                        <option value="4" <?php if ($gz_dets->week_id == 4) { echo 'selected'; } ?>>Week 4</option>
                                        <option value="5" <?php if ($gz_dets->week_id == 5) { echo 'selected'; } ?>>Week 5</option>
                                    </select>
                                    <?php if (form_error('dept_id')) { ?>
                                        <span class="error"><?php echo form_error('dept_id'); ?></span>
                                    <?php } ?>
                                </div>
                                <div class="form-group col-md-4">
                                    <label for="email">Order/Notification Number : <span class="asterisk">*</span></label>
                                    <input type="text" name="notification_number" id="notification_number" class="form-control alpha_num_dash" placeholder="Enter Order Number/Notification" required value="<?php echo$gz_dets->notification_number ?>" autocomplete="off">
                                    <span class="help-block mb-0">Only (A-Za-z0-9(-,Dot,Comma)) these characters are allowed.</span>
                                    <?php if (form_error('notification_number')) { ?>
                                        <span class="error"><?php echo form_error('notification_number'); ?></span>
                                    <?php } ?>
                                </div>
                                
                            </div>
                            <div class="row">
                                
                                
                                <div class="form-group col-md-4">
                                    <label for="email">Gazette No : <span class="asterisk">*</span></label>
                                    <input name="gazette_no" id="gazette_no" class="form-control number_only" placeholder="Enter Gazette No" value="<?php echo $gz_dets->gazette_number; ?>" required="" autocomplete="off">
                                    <?php if (form_error('gazette_no')) { ?>
                                        <span class="error"><?php echo form_error('gazette_no'); ?></span>
                                    <?php } ?>
                                </div>
                                <div class="form-group col-md-4">
                                    <label for="email">Keywords : <span class="asterisk">*</span></label>
                                    <input name="keywords" id="keywords" class="form-control" placeholder="Use comma for multiple keywords" value="<?php echo $gz_dets->keywords ?>" required="" autocomplete="off">
                                    <?php if (form_error('keywords')) { ?>
                                        <span class="error"><?php echo form_error('keywords'); ?></span>
                                    <?php } ?>
                                </div>
                                <div class="form-group col-md-4">
                                    <label for="email">Published Date : <span class="asterisk">*</span></label>
                                    <input name="date" id="date" class="form-control" placeholder="DD-MM-YYYY" value="<?php echo date('d-m-Y', strtotime($gz_dets->published_date)) ?>" required="" autocomplete="off">
                                    <?php if (form_error('date')) { ?>
                                        <span class="error"><?php echo form_error('date'); ?></span>
                                    <?php } ?>
                                </div>
                            </div>
                            <div class="row">
                                
                                <div class="form-group col-md-4">
                                    <label for="email">Gazette (Official Copy): <span class="asterisk">*</span>
                                        <span class="file_icons_add">
                                            <i class="fa fa-file-pdf-o"></i>
                                        </span>
                                    </label>
                                    <div class="row fileupload-buttonbar clearfix">
                                        <div class="col-lg-10">
                                            <span class="btn btn-raised btn-success fileinput-button">
                                                <i class="glyphicon glyphicon-plus"></i>
                                                <span>Choose File</span>
                                                <input type="file" name="doc_files" accept=".pdf" >
                                            </span>
                                            
                                            <span class="files"></span>
                                        </div>
                                    </div>
                                    <label id="doc_files-error" class="error" for="doc_files"></label>
                                    <span class="help-block mb-0">Maximum 5 MB allowed.</span>
                                    <div class="clearfix"></div>
                                    <?php if (form_error('doc_files')) { ?>
                                        <span class="error"><?php echo form_error('doc_files'); ?></span>
                                    <?php } ?>
                                </div>
                                <div class="form-group col-md-4">
                                    <label for="email">Existing Gazette (Official Copy): </label><br>
                                    <span class="file_icons_view">
                                        <a href="<?php echo base_url() . $gz_dets->gazette_file; ?>" target="blank"><i class="fa fa-file-pdf-o"></i></a>
                                        <input type="hidden" name="existing_gz" value="<?php echo $gz_dets->gazette_file ?>">
                                        <input type="hidden" name="gz_id" value="<?php echo $gz_id ?>">
                                    </span>
                                </div>
                            </div>
                        </div>
                        <div class="boxs-footer text-right bg-tr-black lter dvd dvd-top">
                            <button type="submit" class="btn btn-raised btn-success" id="form4Submit">Submit</button>
                        </div>
                    <?php echo form_close(); ?>
                </section>
            </div>
        </div>
    </div>
</section>
<!--/ CONTENT -->
<script src="https://code.jquery.com/jquery-3.4.1.min.js" type="text/javascript" nonce="a6b9a780936c8e980939086f618dded2"></script>
<script type="text/javascript" nonce="a6b9a780936c8e980939086f618dded2">
    $(document).ready(function () {
        $("#date").datepicker({
            dateFormat: 'dd-mm-yy',
            autoclose: true,
            todayHighlight: true,
            maxDate: new Date(),
        });
        
        $(".week").hide();
        $(".ext").hide();
        
        $("#gazette_type_id").on('change', function () {
            var val = $(this).val();
            if (val == 1) {
                $(".week").hide();
                $(".ext").show();
            } else if (val == 2) {
                $(".week").show();
                $(".ext").hide();
            } else {
                $(".week").hide();
                $(".ext").hide();
            }
        });
        
        $(document).on('keyup', 'input.number_only', function (e) {
            if (/\D/g.test(this.value)) {
                // Filter non-digits from input value.
                this.value = this.value.replace(/\D/g, '');
            }
        });
        
        $.validator.addMethod('filesize', function (value, element, param) {
            return this.optional(element) || (element.files[0].size <= param)
        }, 'File size must be less than 5 MB');

        $.validator.addMethod('filetype', function (value, element, param) {
            if (element.value != '') {
                var ext = element.value.match(/\.(.+)$/)[1];
                return this.optional(element) || (ext == param)
            } else {
                return true;
            }
        }, 'Please select only PDF (.pdf) file');
        
        $("#archival_add").validate({
            rules : {
                gazette_type_id : {
                    required : true
                },
                dept_id : {
                    required : true
                },
                notification_type_id : {
                    required : true
                },
                notification_number : {
                    required : true,
                    minlength : 5,
                    maxlength : 50
                },
                gazette_no : {
                    required : true,
                    minlength : 5,
                    maxlength : 50
                },
                subject : {
                    required : true,
                    minlength : 5,
                    maxlength : 50
                },
                keywords : {
                    required : true,
                    minlength : 5,
                    maxlength : 50
                },
                date : {
                    required : true
                },
                doc_files : {
                    filetype : 'pdf',
                    filesize : 5000000
                }
            },
            messages : {
                gazette_type_id : {
                    required : "Please select gazette type"
                },
                dept_id : {
                    required : "Please select department"
                },
                notification_type_id : {
                    required : "Please select notification type"
                },
                notification_number : {
                    required : "Please enter order/notification number",
                    minlength : "Order/Notification number should be minimum 5 characters",
                    maxlength : "Order/Notification number should be maximum 50 characters"
                },
                gazette_no : {
                    required : "Please enter gazette number",
                    minlength : "Gazette number should be minimum 5 characters",
                    maxlength : "Gazette number should be maximum 50 characters"
                },
                subject : {
                    required : "Please enter subject",
                    minlength : "Subject should be minimum 5 characters",
                    maxlength : "Subject should be maximum 50 characters"
                },
                keywords : {
                    required : "Please enter keywords",
                    minlength : "Keywords should be minimum 5 characters",
                    maxlength : "Keywords should be maximum 50 characters"
                },
                date : {
                    required : "Please select date"
                },
                doc_files : {
                    
                }
            },
            submitHandler: function (form) {
                $(".loader").show();
                var fd = new FormData(form);
                var token = $("input[name=<?php echo $this->security->get_csrf_token_name(); ?>]").val();
                fd.append('<?php echo $this->security->get_csrf_token_name(); ?>', token);
                $.ajax({
                    type: "POST",
                    url: "<?php echo base_url(); ?>archival/edit",
                    data: fd,
                    dataType: 'json',
                    contentType: false,
                    processData:false,
                    success: function (json) {
                        if (json['redirect']) {
                            window.location = json['redirect'];
                        } else if (json['error']) {
                            if (json['error']['message']) {
                                $('#archival_add').prepend('<div class="alert bg-danger"><button type="button" class="close" data-dismiss="alert"><span>×</span><span class="sr-only">Close</span></button><span class="text-semibold"></span>' + json['error']['warning'] + '</div>');
                            }
                            
                            for (i in json['error']) {
                                var element = $('#' + i);
                                $(element).parent().find('.error').remove();
                                $(element).after('<label id="' + i +'-error" class="error">' + json['error'][i] + '</label>');
                            }
                        } else {
                            $(element).parent().find('.error').remove();
                        }
                        $(".loader").hide();
                    }
                });
            }
        });
    });
</script>

<?php
    $userLocation = '';
    if ($this->session->userdata('is_applicant')) {
        $userLocation = 'applicants_login/logout';
    } else if ($this->session->userdata('is_igr')) {
        $userLocation = 'Igr_user/logout';
    } else if ($this->session->userdata('is_c&t')) {
        $userLocation = 'Commerce_transport_department/logout';
    } else {
        $userLocation = 'user/logout';
    }
?>
<script>
    var inactivityTimeout;

    function resetInactivityTimeout() {
        clearTimeout(inactivityTimeout);
        inactivityTimeout = setTimeout(function() {
            window.location.href = "<?php echo base_url($userLocation); ?>";
        }, 15 * 60 * 1000); 
    }

    document.addEventListener("mousemove", resetInactivityTimeout);
    document.addEventListener("keypress", resetInactivityTimeout);

    // Initialize timeout on page load
    resetInactivityTimeout();
</script>