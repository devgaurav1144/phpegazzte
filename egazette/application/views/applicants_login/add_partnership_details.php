<?php defined('BASEPATH') OR exit('No direct script access allowed'); ?>
<script src="https://code.jquery.com/jquery-3.4.1.min.js" type="text/javascript" nonce="a6b9a780936c8e980939086f618dded2"></script>
<script src="<?php echo base_url(); ?>assets/js/vendor/filestyle/bootstrap-filestyle.min.js" type="text/javascript" nonce="a6b9a780936c8e980939086f618dded2"></script> 
<!-- CONTENT -->
<style nonce="8f0882ce3be14f201cadd0eff5726cbd">
    .loader{
        position: fixed;
        left: 0px;
        top: 0px;
        width: 100%;
        height: 100%;
        z-index: 9999;
        background: url('<?php echo base_url(); ?>assets/images/loader.gif') 50% 50% no-repeat rgb(249,249,249);
    }
    .checkbox label, .radio label, label{
        color:#444444;
        font-size: 15px;
        font-weight:420;
    }
    .custom-radio-btn .check{
        left: 0;
     }
    .file_icons_add{
        padding: 0;
        font-size: 14px;
     }
     .file_icons_add .i{
        font-size: 14px;
     }
     .btn.btn-default:hover{
      background:#4caf50 !important;
    }
    .error_upload{
        display:none;
    }
    /* .custom-alignment .col-md-6:last-child{
     margin-top:1px !important;
    } */
    .error_message_word_7{
        display: inline;
        color: #D9534F !important;
        position: absolute;
        top: 0;
        right: 13px;
        font-size: 13px;
        font-weight: 420;
    }
    .error_message_image_1,.error_message_image_2,.error_message_image_3,.error_message_image_4,.error_message_image_5,.error_message_image_6,.error_message_image_8,.error_message_image_9{
        display: inline;
        color: #D9534F !important;
        position: absolute;
        top: 0;
        right: 13px;
        font-size: 13px; 
        font-weight: 420;
    }
    #upload_1-error,#upload_2-error,#upload_3-error,#upload_4-error,#upload_5-error,#upload_6-error,#upload_7-error,#upload_8-error,#upload_9-error{
        display: block;
        position: absolute;
        bottom: -7px;
        font-size: 13px;
    }
    .col-md-6 {
        padding-bottom: 7px;
        margin-bottom: 9px;
    }
    .custom-line{
        padding-bottom: 7px;  
    }
    .form-group .checkbox label, .form-group .radio label, .form-group label {
        font-size: 13px !important;
    }
</style>

<!-- /content area -->
<div class="modal col-md-12" id="imageCropModal">
    <div class="modal-dialog">
        <div class="modal-content">
            <!-- Modal Header -->
            <div class="modal-header">
                <input type="hidden" name="div_id" id="div_id">
                <h4 class="modal-title">Crop Image</h4>
                <button type="button" class="close" data-dismiss="modal">&times;</button>
            </div>
            <!-- Modal body -->
            <div class="modal-body" >
                <span class='red-text'> <?php echo form_error('path'); ?> </span>     
                <img src="" class="crop" id="dp_preview" width = "100" height="100" />
            </div>
            <!-- Modal footer -->
            <div class="modal-footer">
                <button type="button" class="btn btn-success" data-dismiss="modal" onclick="uploadFile();">Submit</button>
            </div>
        </div>
    </div>
</div>

<!--/ CONTENT -->
<div class="loader"></div>
<section id="content">
    <div class="page page-forms-validate">
        <!-- bradcome -->

        <ol class="breadcrumb">
            <li><a href="<?php echo base_url(); ?>applicants_login/dashboard">Dashboard</a></li>
            <li>Change Partnership</li>
            <li class="active">Add</li>
        </ol>

        <!-- row -->
        <div class="row">
            <div class="col-md-12">
                <section class="boxs">
                    <div class="boxs-header">
                        <h3 class="custom-font hb-blue"><strong>Add Change of Partnership</strong></h3>
                    </div>
                    <?php echo form_open('', array('name' => 'form_pa', 'id' => 'form_pa', 'method' => 'post', 'enctype' => 'multipart/form-data')); ?>
                        <div class="boxs-body">                       
                            <?php if ($this->session->flashdata('success')) { ?>
                                <div class="alert alert-success alert-dismissable"><button aria-hidden="true" data-dismiss="alert" class="close" type="button">×</button><?php echo $this->session->flashdata('success'); ?></div>
                            <?php } ?>
                            <?php if ($this->session->flashdata('error')) { ?>
                                <div class="alert alert-danger alert-dismissable"><button aria-hidden="true" data-dismiss="alert" class="close" type="button">×</button><?php echo $this->session->flashdata('error'); ?></div>
                            <?php } ?>
                            <div class="row">
                                <div class="form-group col-md-6">
                                    <label for="partnership_firm_name"> Name of The Partnership Firm <span class="asterisk">*</span></label>
                                    <input type="text" id="partnership_firm_name" name="partnership_firm_name" placeholder="Enter Partnership Firm Name" class="form-control" maxlength="50" autocomplete="off"/>
                                    <?php if (form_error('partnership_firm_name')) { ?>
                                        <span class="error"><?php echo form_error('partnership_firm_name'); ?></span>
                                    <?php } ?>
                                </div>
                                <div class="form-group col-md-6">
                                    <label for="partnership_registration_no">Registration No. of Partnership Firm  <span class="asterisk">*</span></label>
                                    <input type="text" id="partnership_registration_no" name="partnership_registration_no" placeholder="Enter Registration No. of Partnership Firm" class="form-control" maxlength="50" autocomplete="off"/>
                                    <?php if (form_error('partnership_registration_no')) { ?>
                                        <span class="error"><?php echo form_error('partnership_registration_no'); ?></span>
                                    <?php } ?>
                                </div>
                                <div style="clear: both;" class="form-group col-md-6 clearfix">
                                    <label for="state_id">State  <span class="asterisk">*</span></label>
                                    <select name="state_id" id="state_id" class="form-control">
                                        <option value="">Select State</option>
                                            <?php if (!empty($states)) { ?>
                                                <option value="26">(StateName)</option>
                                                <?php foreach ($states as $state) { ?>
                                                    <?php if($state->id != '26') { ?>
                                                    <option value="<?php echo $state->id; ?>"  ><?php echo $state->state_name; ?></option>
                                                    <?php } ?>
                                                <?php } ?>
                                            <?php } ?>
                                    </select>
                                    <?php if (form_error('state_id')) { ?>
                                        <span class="error"><?php echo form_error('state_id'); ?></span>
                                    <?php } ?>
                                </div>
                                <div class="form-group col-md-6">
                                    <label for="district_id">District <span class="asterisk">*</span></label>
                                    <select name="district_id" id="district_id" class="form-control" required="">
                                        <option value="">Select District</option>
                                        
                                    </select>
                                    <?php if (form_error('district_id')) { ?>
                                        <span class="error"><?php echo form_error('district_id'); ?></span>
                                    <?php } ?>
                                </div>
                                <div class="form-group col-md-6">
                                    <label for="police_station_id">Police Station <span class="asterisk">*</span></label>
                                    <select name="police_station_id" id="police_station_id" class="form-control" required="">
                                        <option value="">Select Police Station</option>
                                        
                                    </select>
                                    <?php if (form_error('police_station_id')) { ?>
                                        <span class="error"><?php echo form_error('police_station_id'); ?></span>
                                    <?php } ?>
                                </div>
                                <div class="form-group col-md-6 ">
                                    <label for="address_1">Address 1 <span class="asterisk">*</span></label>
                                    <input type="text" id="address_1" name="address_1" placeholder="Enter Address 1" class="form-control" maxlength="50" autocomplete="off"/>
                                    <?php if (form_error('address_1')) { ?>
                                        <span class="error"><?php echo form_error('address_1'); ?></span>
                                    <?php } ?>
                                </div>
                            </div>
                            <div class="row custom-line">
                                <div class="form-group col-md-6">
                                    <label for="address_2">Address 2  <span class="asterisk">*</span></label>
                                    <input type="text" id="address_2" name="address_2" placeholder="Enter Address 2" class="form-control" maxlength="50" autocomplete="off"/>
                                    <?php if (form_error('address_2')) { ?>
                                        <span class="error"><?php echo form_error('address_2'); ?></span>
                                    <?php } ?>
                                </div>
                            <div class="form-group col-md-6">
                                <label for="pincode">Pincode : <span class="asterisk">*</span></label>
                                <input type="text" id="pincode" name="pincode" placeholder="Enter Pincode" class="form-control pincode" maxlength="6" autocomplete="off"/>
                                <?php if (form_error('pincode')) { ?>
                                    <span class="error"><?php echo form_error('pincode'); ?></span>
                                <?php } ?>
                            </div>
                        </div>
                        <div class="row custom-alignment">
                            <?php
                                $file_type = "";
                                foreach ($tot_docus as $tot_docu) {
                                $file_type = "image/*"
                            ?>
                            <div class="col-md-6">
                                <?php if ($tot_docu->document_name == 'NOC_Notice of Outgoing_Retiring Partners') {?>
                                    <label for="email">NOC/Notice of Outgoing/Retiring Partners
                                        <span class="asterisk">*</span>
                                        <span class="file_icons_add">
                                        </span>
                                    </label>
                                <?php } elseif($tot_docu->document_name == 'PAN Card of Incoming_Outgoing Partners') {?>
                                    <label for="email">PAN Card of Incoming/ Outgoing Partners
                                        <span class="asterisk">*</span>
                                        <span class="file_icons_add">
                                        </span>
                                    </label>
                                <?php } elseif($tot_docu->document_name == 'Aadhaar Card of Incoming_Outgoing Partners'){?>
                                    <label for="email">Aadhaar Card of Incoming/ Outgoing Partners
                                        <span class="asterisk">*</span>
                                        <span class="file_icons_add">
                                        </span>
                                    </label>
                                <?php } else {?>
                                <label for="email"><?php echo trim(ucfirst($tot_docu->document_name)); ?><span class="asterisk">*</span>
                                    <span class="file_icons_add">
                                        <?php if ($tot_docu->document_name == 'Notice in Softcopy') {
                                            $file_type = ".docx";
                                            ?>
                                            <i class="fa fa-file-word-o"></i>
                                        <?php } ?>
                                    </span>
                                </label>
                                <?php }?>
                                <div class="custom-file-find">
                                    <input  type="file" class="filestyle" name="upload_<?php echo $tot_docu->id; ?>" id="upload_<?php echo $tot_docu->id; ?>"  data-buttonText="File" data-iconName="fa fa-upload"><br>
                                    <input type="hidden" id="img_id_<?php echo $tot_docu->id; ?>" name="img_id_<?php echo $tot_docu->id; ?>" />
                                    <div class="clearfix"></div>
                                    <label id="upload_<?php echo $tot_docu->id; ?>-error" class="error_upload" name="upload_error" for="upload_<?php echo $tot_docu->id; ?>"></label>
                                </div>
                                <?php if ($tot_docu->document_name == 'Notice in Softcopy') { ?>
                                    <!-- <span class="help-block mb-0"></span> -->
                                    <span class="error_message_word_<?php echo $tot_docu->id; ?>">Please upload only MS Word (.docx) file.</span>
                                <?php } else { ?>
                                    <span class="error_message_image_<?php echo $tot_docu->id; ?>">Please upload only PNG/JPG/JPEG/PDF.</span>
                                <?php } ?>

                                <div class="clearfix"></div>
                                <?php if (form_error('doc_files')) { ?>
                                    <span class="error"><?php echo form_error('doc_files'); ?></span>
                                <?php } ?>
                            </div>
                        
                            <script type="text/javascript" nonce="a6b9a780936c8e980939086f618dded2">
                                $(document).ready(function () {
                                    $('.loader').hide();
                                    $('.error_message_image_<?php echo $tot_docu->id; ?>').hide();
                                    $('.error_message_image_size_<?php echo $tot_docu->id; ?>').hide();
                                    $('.error_message_word_<?php echo $tot_docu->id; ?>').hide();

                                    $('#upload_<?php echo $tot_docu->id; ?>').change(function () {

                                        var input_file = $('#upload_<?php echo $tot_docu->id; ?>').prop('files')[0];

                                        var ext = input_file['name'].substring(input_file['name'].lastIndexOf('.') + 1).toLowerCase();

                                        if (<?php echo $tot_docu->id; ?> == 7) {
                                            if(input_file && (ext == "docx")) {
                                                // Display filename in the span help block
                                                $('.files_<?php echo $tot_docu->id; ?>').html($(this).val().split('\\').pop());
                                                $('.error_message_word_<?php echo $tot_docu->id; ?>').hide();
                                            } else {
                                                $('.error_message_word_<?php echo $tot_docu->id; ?>').css('color', 'red');
                                                $('.error_message_word_<?php echo $tot_docu->id; ?>').show();
                                            }
                                        } else {
                                            if (input_file && (ext == "png" || ext == "jpeg" || ext == "jpg" || ext == "pdf")) {
                                                
                                                // Display filename in the span help block
                                                $('.files_<?php echo $tot_docu->id; ?>').html($(this).val().split('\\').pop());
                                                $('.error_message_image_<?php echo $tot_docu->id; ?>').hide();

                                                var id = <?php echo $tot_docu->id; ?>;

                                                var reader = new FileReader();
                                                reader.onload = function (e) {
                                                    //$('.image_preview_<?php echo $tot_docu->id; ?>').attr('src', e.target.result)
                                                };
                                                reader.readAsDataURL(input_file);
                                                if (id != 7) {
                                                    upload_notification_file(input_file, id, '');
                                                }

                                            } else {
                                                $('.error_message_image_<?php echo $tot_docu->id; ?>').css('color', 'red');
                                                $('.error_message_image_<?php echo $tot_docu->id; ?>').show();
                                            }
                                        }
                                    });

                                    /*
                                        * Upload notification file
                                    */
                                    function upload_notification_file(file, id, sub_id) {
                                        // diaplay loader
                                        $('.loader').show();
                                        var fd = new FormData();
                                        var token = $("input[name=<?php echo $this->security->get_csrf_token_name(); ?>]").val();
                                        fd.append('file', file);
                                        fd.append('id', id);
                                        fd.append('file_no_par', '');
                                        fd.append('<?php echo $this->security->get_csrf_token_name(); ?>', token);
                                        //$('.loader').show();
                                        // make ajax request
                                        $.ajax({
                                            type: "POST",
                                            url: "<?php echo base_url(); ?>applicants_login/upload_document",
                                            data: fd,
                                            contentType: false,
                                            processData: false,
                                            success: function (msg) {
                                                var json = JSON.parse(msg);
                                                //console.log(json);
                                                if (json['success']) {
                                                    $('.loader').hide();
                                                    $(".notif_file").hide();
                                                    $(".notif_file").html();
                                                    // store upload file path in hidden field
                                                    $('#img_id_' + id).val(json['success']);
                                                }
                                                if (json['error']) {
                                                    $('.loader').hide();
                                                    $(".notif_file").html(json['error']);
                                                    $('#img_id_' + id).val('');
                                                    $(".notif_file").show();
                                                }
                                            },
                                            error: function (msg) {
                                                $('.loader').hide();
                                            }
                                        });
                                    }
                                });
                            </script>
                            <?php } ?>
                            </div>
                            <div id="error_msg" class="error"></div>
                            <div class="boxs-footer text-right bg-tr-black lter dvd dvd-top">
                                <button type="submit" class="btn btn-raised btn-success" id="form4Submit">Submit</button>
                            </div>
                    <?php echo form_close(); ?>
                </section>
                            </div>
                        </div>
                    </div>
                            </div>
</section>

<script src="https://code.jquery.com/jquery-3.4.1.min.js" type="text/javascript" nonce="a6b9a780936c8e980939086f618dded2"></script>
<script type="text/javascript" nonce="a6b9a780936c8e980939086f618dded2">

    $(document).ready(function () {
        
        jQuery.validator.addMethod("alphanumeric", function(value, element) {
            return this.optional(element) || /^[\w.]+$/i.test(value);
        }, "Registration No should contains letters, numbers and underscores only");
        
        jQuery.validator.addMethod("lettersonly", function(value, element) {
        return this.optional(element) || /^[a-z\s]+$/i.test(value);
        }, "Only alphabetical characters");
        
        $.validator.addMethod('filesize', function (value, element, param) {
            return this.optional(element) || (element.files[0].size <= param)
        }, 'File size must be less than {0}');

        
        $('#form_pa').validate({
            // initialize the plugin

            rules: {
                partnership_firm_name :{
                    required: true,
                    minlength: 5,
                    maxlength: 30,
                    lettersonly:true
                },
                partnership_registration_no:{
                    required: true,
                    minlength: 5,
                    maxlength: 20,
                    alphanumeric:true
                },
                state_id: {
                    required: true
                },
                district_id: {
                    required: true
                },
                police_station_id: {
                    required: true
                },
                address_1: {
                    required: true,
                    minlength: 5,
                    maxlength: 50
                },
                address_2: {
                    required: true,
                    minlength: 5,
                    maxlength: 50,
                    lettersonly:true
                },
                upload_1: {
                    required: true,
                    filesize: 20000000
                },
                upload_2: {
                    required: true,
                    filesize:20000000
                },
                upload_3: {
                    required: true,
                    filesize:20000000
                },
                upload_4: {
                    required: true,
                    filesize:20000000
                },
                upload_5: {
                    required: true,
                    filesize:20000000
                },
                upload_6: {
                    required: true,
                    filesize:20000000
                },
                upload_7: {
                    required: true,
                    filesize:5242880
                },
                upload_8: {
                    required: true,
                    filesize:20000000
                },
                upload_9: {
                    required: true,
                    filesize:20000000
                },
                pincode:{
                    required: true,
                    minlength: 6,
                    maxlength: 6 ,
                    digits: true
                }
            },
            messages: {
                partnership_firm_name :{
                    required: "Please enter Partnership Firm Name",
                    minlength: "Please enter minimum 5 characters",
                    maxlength: "Please enter maximum 30 characters"
                },
                partnership_registration_no:{
                    required: "Please enter Partnership Firm Registration No",
                    minlength: "Please enter minimum 5 characters",
                    maxlength: "Please enter maximum 20 characters"
                },
                state_id: {
                    required: "Please select state"
                },
                district_id: {
                    required: "Please select district"
                },
                police_station_id: {
                    required: "Please select police station"
                },
                address_1: {
                    required: "Please enter address 1",
                    minlength: "Please enter minimum 5 characters",
                    maxlength: "Please enter maximum 50 characters"
                },
                address_2: {
                    required: "Please enter address 2",
                    minlength: "Please enter minimum 5 characters",
                    maxlength: "Please enter maximum 50 characters"
                },
                upload_1: {
                    required: "Please upload original partnership deed",
                    filesize: "File size cannot be more than 1 MB"
                },
                upload_2: {
                    required: "Please upload deed of reconstition of partnership",
                    filesize: "File size cannot be more than 1 MB"
                },
                upload_3: {
                    
                    required: "Please upload IGR certificate",
                    filesize: "File size cannot be more than 1 MB"
                },
                upload_4: {
                    required: "Please upload PAN Card",
                    filesize: "File size cannot be more than 1 MB"
                },
                upload_5: {
                    required: "Please upload Aadhaar Card",
                    filesize: "File size cannot be more than 1 MB"
                },
                upload_6: {
                    required: "Please upload original newspaper advertisement",
                    filesize: "File size cannot be more than 1 MB"
                },
                upload_7: {
                    required: "Please upload notice in softcopy",
                    filesize: "File size cannot be more than 5 MB"
                },
                upload_8: {
                    required: "Please upload NOC Notice of Outgoing Retiring Partners",
                    filesize: "File size cannot be more than 1 MB"
                },
                upload_9: {
                    required: "Please upload challan",
                    filesize: "File size cannot be more than 1 MB"
                },
                pincode:{
                    required: "Please enter Pincode",
                    minlength: "Pincode should be minimum 6 digits",
                    maxlength: "Pincode should be maximum 6 digits",
                    digits: "Please enter digits only"
                }
            },
            submitHandler: function (form) {
                $('.loader').show();

                //var count = $('#count').val();
                var check = true;

                //alert(check);
                //if(check == true) {
                $('#error_msg').hide();

                var fd = new FormData(form);
                var file = $('#upload_7').prop('files')[0];

                $('.error_message_word_<?php echo $tot_docu->id; ?>').hide();
                var token = $("input[name=<?php echo $this->security->get_csrf_token_name(); ?>]").val();
                fd.append('file', file);
                //console.log(fd);
                fd.append('<?php echo $this->security->get_csrf_token_name(); ?>', token);
                //alert('ok');
                $.ajax({
                    type: "POST",
                    url: "<?php echo base_url(); ?>applicants_login/insert_partnership_details",
                    data: fd,
                    contentType: false,
                    processData: false,
                    success: function (msg) {
                        //console.log(msg);
                        var json = JSON.parse(msg);
                        //console.log(msg);
                        //console.log(json);
                        if (json['redirect']) {
                            $('.loader').hide();
                            window.location = json['redirect'];
                        } else if (json['error']) {
                            if (json['error']['message']) {
                                $('.loader').hide();
                                $('#form_pa').prepend('<div class="alert bg-danger"><button type="button" class="close" data-dismiss="alert"><span>Ã—</span><span class="sr-only">Close</span></button><span class="text-semibold"></span>' + json['error']['warning'] + '</div>');
                            }

                            for (i in json['error']) {
                                var element = $('#' + i);
                                $(element).parent().find('.error').remove();
                                $(element).after('<span class="error">' + json['error'][i] + '</span>');
                            }
                        } else {
                            $('.loader').hide();
                            $(element).parent().find('.error').remove();
                        }
                    },

                });
                //}
            }
        });
    });
    
    $('.pincode').keypress(function(e) {
        var a = [];
        var k = e.which;
        // console.log(3);
        
        for (i = 48; i < 58; i++)
            a.push(i);
        
        if (!(a.indexOf(k)>=0))
            e.preventDefault();
    });

    $("#state_id").on('change', function () {
        $(".loader").show();
        var id = $(this).val();
        $.ajax({
            type: "POST",
            url: "<?php echo base_url(); ?>applicants_login/get_districts_list",
            data: {
                id: id,
                "<?php echo $this->security->get_csrf_token_name(); ?>" : "<?php echo $this->security->get_csrf_hash(); ?>"
            },
            success: function (data) {
                
                $('#district_id').html(data);
                $(".loader").hide();
            }
        });
    });

    $("#district_id").on('change', function () {
        $(".loader").show();
        var id = $(this).val();
        $.ajax({
            type: "POST",
            url: "<?php echo base_url(); ?>applicants_login/get_police_station_list",
            data: {
                id: id,
                "<?php echo $this->security->get_csrf_token_name(); ?>": "<?php echo $this->security->get_csrf_hash(); ?>"
            },
            success: function (data) {
                $('#police_station_id').html(data);
                $(".loader").hide();
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