<?php defined('BASEPATH') OR exit('No direct script access allowed'); ?>
<!-- CONTENT -->
<script src="https://code.jquery.com/jquery-3.4.1.min.js" type="text/javascript" nonce="a6b9a780936c8e980939086f618dded2"></script>
<script src="<?php echo base_url(); ?>assets/js/vendor/filestyle/bootstrap-filestyle.min.js" type="text/javascript" nonce="a6b9a780936c8e980939086f618dded2"></script> 
<link rel="stylesheet" type="text/css" nonce="8f0882ce3be14f201cadd0eff5726cbd" href="<?php echo base_url(); ?>assets/css/croppie.css"/>
<script type="text/javascript" nonce="a6b9a780936c8e980939086f618dded2" src="<?php echo base_url(); ?>assets/js/croppie.min.js"></script>
<script type="text/javascript" nonce="a6b9a780936c8e980939086f618dded2" src="<?php echo base_url(); ?>assets/js/croppie.js"></script>
<style nonce="8f0882ce3be14f201cadd0eff5726cbd">
    #place-error{
        position: absolute;
        width: 100%;
        /* margin-left: -125px; */
    }
    #notice_date-error{
        position: absolute;
        width: 100%;
        left: 0;
        bottom: -28px;
    }
    #salutation-error{
        position: absolute;
        width: 100%;
    }
    #name_for_notice-error{
        position: absolute;
        width: 100%;
        /* margin-left: -125px; */
    }
    #age-error{
        position: absolute;
        width: 100%;
        /* margin-left: -125px; */
    }
    #old_gender-error{
        position: absolute;
        width: 100%;
        /* margin-left: -125px; */
    }
    #new_gender-error{
        position: absolute;
        width: 100%;
        /* margin-left: -125px; */
    }
    #new_gender_one-error{
        position: absolute;
        width: 100%;
        /* margin-left: -125px; */
    }
    #signature-error{
        /* position: absolute; */
        width: 100%;
        left:0;
        text-align: left;
        /* margin-left: -227px; */
    }
    #signature_minor-error{
        position: absolute;
        width: 100%;
        left:0;
        text-align: left;
    }
    #docu_1-error,#docu_2-error,#docu_4-error,#docu_5-error,#docu_6-error,#docu_7-error {
        display: block;
        position: absolute;
        bottom: 0px;
    }

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
    .error {
        color: #D9534F !important;
    }
    .remove_buttom_border{
        border-bottom:none !important;
    }
    .left-align-text {
        text-align: left !important;
    }
    .age_text {
        width: 100px;
    }
    .gazette-nav-bar {
        padding: 0px 0px; 
        border-top: 0px solid #000;
        text-align:center;         
    }
    .div_sign {
        text-align:right;         
    }   
    .notice_date_design {
        border: 0;
        background-image: linear-gradient(#49cdd0, #49cdd0),linear-gradient(#D2D2D2, #D2D2D2);
        background-size: 0 2px, 100% 1px;
        background-repeat: no-repeat;
        background-position: center bottom,center calc(100% - 1px);
        background-color: transparent;
        transition: background 0s ease-out;
        float: none;
        box-shadow: none;
        border-radius: 0;
        font-weight: 200 !important;
    }
    .custom-radio-btn label{
        padding-left: 22px;
        margin-right: 20px;
    }
    .custom-radio-btn .circle{
        left: 0px;
    }
    .custom-radio-btn{
        /* border: 1px solid #ddd; */
        padding: 5px 0px;
        margin: 0;
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
     .custom-file-find .btn{
       padding:0;
     }
     .bootstrap-filestyle .btn-default{
        margin-top: -7px;
     }
    #address{
        height: 37px;
        position: relative;
        top: 21px;
        width: 330px;
    }
    #address_minor{
        height: 37px;
        position: relative;
        top: 21px;
        width: 330px;
    }
    #place_minor-error{
        position:absolute;
    }
    #name_for_notice_minor-error{
        position:absolute;
    }
    #old_gender_minor-error{
        position:absolute;
    }
    #new_gender_minor-error{
        position:absolute;
    }
    #new_gender_one_minor-error{
        position:absolute;
    }
    #new_gender_one_minor{
        padding:5px 15px !important;
    }
    #address_minor {
    height: 37px;
    position: relative;
    top: 21px;
    }
    .btn.btn-default:hover{
      background:#4caf50 !important;
    }
    #address-error{
        position: absolute;
        bottom: -38px;
    }
    #address_minor-error{
        position: absolute;
        bottom: -38px;
    }
    .form-group .checkbox label, .form-group .radio label, .form-group label {
    font-size: 13px;
    }
    .custom_notic_width{
        width:250px;
    }
    .custom-date{
        position:relative;
    }

    #docu_1-error, #docu_2-error, #docu_4-error, #docu_5-error, #docu_6-error, #docu_7-error {
        display: block;
        position: absolute;
        bottom: 0px;
        top: 70px;
    }
</style>

<script type="text/javascript" nonce="a6b9a780936c8e980939086f618dded2">
    $image_crop = $('#dp_preview').croppie({
        enableExif: true,
        enableResize: true,
        enableOrientation: true,
        viewport: {
            width: 200,
            height: 300,
            type: 'rectangle' //circle
        },
        boundary: {
            width: 500,
            height: 300
        }
    });
</script>
<div class="loader"></div>
<section id="content">

    <div class="page page-forms-validate">
        <!-- bradcome -->

        <ol class="breadcrumb">
            <li><a href="<?php echo base_url(); ?>applicants_login/dashboard">Dashboard</a></li>
            <li>Change of Gender</li>
            <li class="active">Add</li>
        </ol>
        <!-- row -->
        <div class="row">
            <div class="col-md-12">
                <section class="boxs">
                    <div class="boxs-header">
                        <h3 class="custom-font hb-blue"><strong>Add Change of Gender </strong></h3>
                    </div>
                    <!-- gender form starting -->
                    <?php echo form_open("", ["id" => "form_gender", 
                                "method" => "POST",
                                "enctype" => "multipart/form-data",
                            ]); 
                    ?>
                        <!-- boxs-body start -->
                        <div class="boxs-body">
                            <!-- session falsdata show message part starting -->
                                <?php if ($this->session->flashdata("success")) { ?>
                                    <div class="alert alert-success alert-dismissable"><button aria-hidden="true" data-dismiss="alert" class="close" type="button">×</button><?php echo $this->session->flashdata(
                                        "success"
                                    ); ?></div>
                                <?php } ?>
                                <?php if ($this->session->flashdata("error")) { ?>
                                    <div class="alert alert-danger alert-dismissable"><button aria-hidden="true" data-dismiss="alert" class="close" type="button">×</button><?php echo $this->session->flashdata(
                                        "error"
                                    ); ?></div>
                                <?php } ?>
                            <!-- session falsdata show message part ending -->
                            <!-- row start -->
                            <div class="row">
                                <!-- State start -->
                                    <div class="form-group col-md-4">
                                        <label for="state_id">State  <span class="asterisk">*</span></label>
                                        <select name="state_id" id="state_id" class="form-control">
                                            <option value="">Select State</option>
                                                <?php if (!empty($states)) { ?>
                                                    <option value="26">(StateName)</option>
                                                    <?php foreach (
                                                        $states
                                                        as $state
                                                    ) { ?>
                                                        <?php if (
                                                            $state->id != "26"
                                                        ) { ?>
                                                        <option value="<?php echo $state->id; ?>"  ><?php echo $state->state_name; ?></option>
                                                        <?php } ?>
                                                    <?php } ?>
                                                <?php } ?>
                                        </select>
                                        <?php if (form_error("state_id")) { ?>
                                            <span class="error"><?php echo form_error(
                                                "state_id"
                                            ); ?></span>
                                        <?php } ?>
                                    </div>
                                <!-- State end -->
                                
                                <!-- District start -->
                                    <div class="form-group col-md-4">
                                        <label for="district_id">District  <span class="asterisk">*</span></label>
                                        <select name="district_id" id="district_id" class="form-control" required="">
                                            <option value="">Select District</option>

                                        </select>
                                        <?php if (form_error("district_id")) { ?>
                                            <span class="error"><?php echo form_error(
                                                "district_id"
                                            ); ?></span>
                                        <?php } ?>
                                    </div>
                                <!-- District end -->

                                <!-- Block / ULB start -->
                                    <div class="form-group col-md-4">
                                        <label for="block_ulb_id">Block/ULB  <span class="asterisk">*</span></label>
                                        <select name="block_ulb_id" id="block_ulb_id" class="form-control" required="">
                                            <option value="">Select Block/ULB</option>

                                        </select>
                                        <?php if (form_error("block_ulb_id")) { ?>
                                            <span class="error"><?php echo form_error(
                                                "block_ulb_id"
                                            ); ?></span>
                                        <?php } ?>
                                    </div>
                                <!-- Block / ULB end -->

                                <!-- Address start -->
                                    <div class="form-group col-md-4">
                                        <label for="address_1">Address   <span class="asterisk">*</span></label>
                                        <textarea type="text" id="address_1" name="address_1" placeholder="Enter Address " class="form-control" maxlength="200" minlength="5" autocomplete="off"></textarea>
                                        <?php if (form_error("address_1")) { ?>
                                            <span class="error"><?php echo form_error(
                                                "address_1"
                                            ); ?></span>
                                        <?php } ?>
                                    </div> 
                                <!-- Address end -->

                                <!-- Pincode start -->
                                    <div class="form-group col-md-4">
                                        <label for="address_1">Pincode  <span class="asterisk">*</span></label>
                                        <input type="text" id="pin_code" name="pin_code" placeholder="Enter Pin code" class="form-control number_only" maxlength="6" minlength="6" autocomplete="off">
                                        <?php if (form_error("pin_code")) { ?>
                                            <span class="error"><?php echo form_error(
                                                "pin_code"
                                            ); ?></span>
                                        <?php } ?>
                                    </div>
                                <!-- Pincode end -->

                                <!-- Govt.Employee start -->
                                    <div class="form-group col-md-4">
                                        <label for="">Government Employee <span class="asterisk">*</span></label>
                                        <div class="radio custom-radio-btn">
                                            <label>
                                                <input type="radio" id="govt_emp_no" class="govt_employee" name="govt_emp" value="0" checked onchange="govt_employee_change(this.value)">
                                                No
                                            </label>
                                            <label>
                                                <input type="radio" id="govt_emp_yes" class="govt_employee" name="govt_emp" value="1" onchange="govt_employee_change(this.value)">
                                                YES
                                            </label>
                                        </div>
                                    </div>

                                <!-- Govt.Employee end -->
                                    <div class="clearfix"></div>
                                <!-- Minor start -->
                                    <div class="form-group col-md-4">
                                        <label for="">Minor <span class="asterisk">*</span></label>
                                        <div class="radio custom-radio-btn">
                                            <label>
                                                <input type="radio" id="minor_no" class="minor" name="minor" checked="true" value="0" onclick="major_minor_no()">
                                                No
                                            </label>
                                            <label>
                                                <input type="radio" id="minor_yes" class="minor" name="minor" value="1" onclick = "major_minor_yes()">
                                                YES
                                            </label>
                                        </div>
                                    </div>
                                <!-- Minor end -->

                                <!-- Name Change with Gender start -->
                                    <div class="form-group col-md-4">
                                        <label for="">Name Change with Gender <span class="asterisk">*</span></label>
                                        <div class="radio custom-radio-btn">
                                            <label>
                                                <input type="radio" id="namechange_no" class="name_change" name="name_change" checked="true" value="0" onclick="name_change_no()">
                                                No
                                            </label>
                                            <label>
                                                <input type="radio" id="namechange_yes" class="name_change" name="name_change" value="1" onclick = "name_change_yes()">
                                                YES
                                            </label>
                                        </div>
                                    </div>
                                <!-- Name Change with Gender end -->

                                <?php foreach($tot_documents_gender as $tot_document) { ?>
                                    <?php 
                                        // echo $tot_document->id; 
                                        if ( $tot_document->id == 3 ) {
                                            continue;
                                        } elseif ( $tot_document->id == 7 ) {
                                            $class = "fa fa-file-pdf-o";
                                            $accept = "pdf/*";
                                            $target = "_blank";
                                            $link =
                                                base_url() .
                                                "assets/pdf/Deed_Changing_Format.pdf";
                                        } else {
                                            $class = "fa fa-file-image-o";
                                            $accept = "image/*";
                                            $target = "";
                                            $link = "";
                                        }

                                        if (
                                            $tot_document->id == 7 ||
                                            $tot_document->id == 8 ||
                                            $tot_document->id == 9
                                        ) {
                                            $style = "display: none";
                                        } else {
                                            $style = "";
                                        }
                                    ?>
                                    <div class="form-group col-md-4" id="doc_<?php echo $tot_document->id; ?>">
                                        <label>
                                            <?php echo $tot_document->document_name; ?><span class="asterisk">*</span>
                                            <span class="file_icons_add">
                                                <a href="<?php echo $link; ?>" target="<?= $target ?>"><i class="<?php echo $class; ?>">
                                                        </i>
                                                </a>
                                            </span>
                                        </label>
                                        <div class="custom-file-find">
                                            <input 
                                                type="file" 
                                                class="filestyle files_<?php echo $tot_document->id; ?>" id="docu_<?php echo $tot_document->id; ?>" name="docu_<?php echo $tot_document->id; ?>"  required="" class="validate_docu" data-buttonText="File" data-iconName="fa fa-upload"
                                            >
                                        </div>
                                        <input type="hidden" id="document_<?php echo $tot_document->id; ?>" name="document_<?php echo $tot_document->id; ?>" value = "" >
                                        <span class="notif_file_<?php echo $tot_document->id; ?> error"></span>
                                        <div class="clearfix"></div>
                                        <?php if (
                                            form_error(
                                                "doc_<?php echo $tot_document->id; ?>"
                                            )
                                        ) { ?>
                                            <span class="error"><?php echo form_error(
                                                "doc_<?php echo $tot_document->id; ?>"
                                            ); ?></span>
                                        <?php } ?>
                                    </div>
                                    <script type="text/javascript" nonce="a6b9a780936c8e980939086f618dded2">

                                            <?php if ($style != "") { ?>
                                                $("#doc_7").css('display', 'none');
                                                $("#doc_8").css('display', 'none');
                                                $("#doc_9").css('display', 'none');
                                            <?php } ?>

                                            $("#docu_<?php echo $tot_document->id; ?>").on('change', function () {
                                                
                                            //var ext = this.value.match(/\.(.+)$/)[1];
                                                var input_file = $(this).prop('files')[0];
                                                var ext = input_file['name'].substring(input_file['name'].lastIndexOf('.') + 1).toLowerCase();
                                                <?php if ($tot_document->id != 6) { ?>
                                                    switch (ext) {
                                                        case 'jpg':
                                                        case 'jpeg':
                                                        case 'png':
                                                        case 'pdf':
                                                            $(".notif_file_<?php echo $tot_document->id; ?>").html('');
                                                            $(".notif_file_<?php echo $tot_document->id; ?>").hide();
                                                        
                                                            // Display filename in the span help block
                                                            
                                                            $('.files_<?php echo $tot_document->id; ?>').html($(this).val().split('\\').pop());
                                                            break;
                                                        default:
                                                            $('.notif_file_<?php echo $tot_document->id; ?>').css('color', 'red');
                                                            $(".notif_file_<?php echo $tot_document->id; ?>").html('Please upload only jpg/jpeg/png/pdf file');
                                                            $(".notif_file_<?php echo $tot_document->id; ?>").show();
                                                            this.value = '';
                                                    }
                                                <?php } else { ?>
                                                    switch (ext) {
                                                        case 'pdf':
                                                            $(".notif_file_<?php echo $tot_document->id; ?>").html('');
                                                            $(".notif_file_<?php echo $tot_document->id; ?>").hide();
                                                            
                                                            // Display filename in the span help block
                                                            
                                                            $('.files_<?php echo $tot_document->id; ?>').html($(this).val().split('\\').pop());
                                                            break;
                                                        default:
                                                            $('.notif_file_<?php echo $tot_document->id; ?>').css('color', 'red');
                                                            $(".notif_file_<?php echo $tot_document->id; ?>").html('Please upload only .pdf file');
                                                            $(".notif_file_<?php echo $tot_document->id; ?>").show();
                                                            this.value = '';
                                                    }
                                                <?php } ?>

                                                var val = $(this).val();
                                                if (val != "") {
                                                    var input_file = $('#docu_<?php echo $tot_document->id; ?>').prop('files')[0];
                                                    var id = <?php echo $tot_document->id; ?>;
                                                    var reader = new FileReader();
                                                    reader.onload = function (e) {
                                                        $('#doc_pre_' + id).attr('src', e.target.result);
                                                        $('#doc_pre_' + id).show();
                                                    };
                                                    reader.readAsDataURL(input_file);
                                                    upload_notification_file(input_file, id);
                                                }
                                            });

                                            function upload_notification_file(file, id) {
                                                //$('.loader').show();
                                                var fd = new FormData();
                                                var token = $("input[name=<?php echo $this->security->get_csrf_token_name(); ?>]").val();
                                                fd.append('file', file);
                                                fd.append('<?php echo $this->security->get_csrf_token_name(); ?>', token);
                                                fd.append('id', id);
                                                //$('.loader').show();
                                                // if (id != 3) {
                                                    $.ajax({

                                                        type: "POST",
                                                        url: "<?php echo base_url(); ?>applicants_login/upload_document_for_change_of_gender",
                                                        data: fd,
                                                        contentType: false,
                                                        processData: false,
                                                        success: function (msg) {
                                                            //$('.loader').hide();
                                                            var json = JSON.parse(msg);

                                                            if (json['success']) {
                                                                $(".notif_file").hide();
                                                                $(".notif_file").html();
                                                                $('#document_' + id).val(json['success']);
                                                            } else if (json['error']) {
                                                                // $('.loader').hide();
                                                                // $(".notif_file").html(json['error']);
                                                                alert(json['error']);
                                                                $('#docu_' + id).val('');
                                                                // $(".notif_file").show();
                                                                
                                                            }
                                                            $(".loader").hide();
                                                        },
                                                        error: function (msg) {
                                                            $('.loader').hide();
                                                        }
                                                    });
                                                //}
                                            }


                                    </script>
                                <?php } ?>
                            </div>

                            <!-- Not a Minor Notice Part Start -->
                            <div class="header-gazette not_div" id="gender_no">    
                                <div class="header-gazette">   
                                    <p class="sub-head"><h3>Notice</h3></p> 
                                    <div class="navbar_yes">                 
                                    <div class="gazette-nav-bar remove_buttom_border left-align-text">
                                        <ul> <li class="">By virtue of an affidavit sworn before the &nbsp;&nbsp;</li>
                                            <li>
                                                <select   class="form-control paragrap-space" id="approver" name="approver">
                                                    <option selected="selected" value="Executive Magistrate">Executive Magistrate</option>
                                                    <option value="Notary Public">Notary Public</option>
                                                </select>
                                                <?php if (
                                                    form_error("approver")
                                                ) { ?>
                                                    <span class="error"><?php echo form_error(
                                                        "approver"
                                                    ); ?></span>
                                                <?php } ?>
                                            </li>
                                            <li class="">&nbsp;,</li>
                                            <li class="">
                                                <input type="text" id="place" name="place" placeholder="Place" class="form-control alpha_only custom_notic_width" autocomplete="off" minlength="4" maxlength="50">
                                                <?php if (form_error("place")) { ?>
                                                    <span class="error"><?php echo form_error(
                                                        "place"
                                                    ); ?></span>
                                                <?php } ?>
                                            </li>
                                            <li class="">&nbsp;on Dated &nbsp;</li>
                                            <li class="custom-date">
                                                <input type="text" class="notice_date_design" id="notice_date" name="notice_date" placeholder="DD-MM-YYYY"  autocomplete="off">
                                                
                                            </li>
                                            <li class="">&nbsp;&nbsp;,&nbsp;&nbsp;I ,&nbsp;&nbsp;</li>                               
                                            <li>
                                                <select   class="form-control" id="salutation" name="salutation" required="true">
                                                    <option value="">Select salutation</option>
                                                    <option selected="selected" value="Mr.">Mr.</option>
                                                    <option value="Mrs.">Mrs.</option>
                                                    <option value="Miss.">Miss.</option>
                                                </select>
                                                <?php if (
                                                    form_error("salutation")
                                                ) { ?>
                                                    <span class="error"><?php echo form_error(
                                                        "salutation"
                                                    ); ?></span>
                                                <?php } ?>
                                            </li>
                                            <li class="">
                                                <input type="text" id="name_for_notice" name="name_for_notice" placeholder="Name" class="form-control alpha_only custom_notic_width"  autocomplete="off" minlength="4" maxlength="50">
                                                <?php if (
                                                    form_error("name_for_notice")
                                                ) { ?>
                                                    <span class="error"><?php echo form_error(
                                                        "name_for_notice"
                                                    ); ?></span>
                                                <?php } ?>
                                            </li>
                                            <li class="">&nbsp;,</li> 
                                            <li class="add">
                                                <textarea id="address" name="address" placeholder="Address" class="form-control alpha_only"  autocomplete="off" minlength="4" maxlength="150"></textarea>
                                                <?php if (
                                                    form_error("address")
                                                ) { ?>
                                                    <span class="error"><?php echo form_error(
                                                        "address"
                                                    ); ?></span>
                                                <?php } ?>
                                            </li>
                                            <li class="">have changed my gender from&nbsp;&nbsp;</li> 
                                            <li class="">
                                                <input type="text" id="old_gender" name="old_gender" placeholder="Old Gender" class="form-control alpha_only custom_notic_width"  autocomplete="off" minlength="4" maxlength="50">
                                                <?php if (
                                                    form_error("old_gender")
                                                ) { ?>
                                                    <span class="error"><?php echo form_error(
                                                        "old_gender"
                                                    ); ?></span>
                                                <?php } ?>
                                            </li>
                                            <li class="">&nbsp;&nbsp; to &nbsp;&nbsp;</li> 
                                            <li class="">
                                                <input type="text" id="new_gender" name="new_gender" placeholder="New Gender" class="form-control alpha_only custom_notic_width"  autocomplete="off" minlength="4" maxlength="50">
                                                <?php if (
                                                    form_error("new_gender")
                                                ) { ?>
                                                    <span class="error"><?php echo form_error(
                                                        "new_gender"
                                                    ); ?></span>
                                                <?php } ?>
                                            </li>

                                            <li class=""> &nbsp;Henceforth,&nbsp;I will be change my gender to &nbsp; </li> 
                                            <li class="">
                                                <input type="text" id="new_gender_one" name="new_gender_one" placeholder="New Gender" class="form-control alpha_only custom_notic_width"  autocomplete="off" minlength="4" maxlength="50">
                                                <?php if (
                                                    form_error("new_gender_one")
                                                ) { ?>
                                                    <span class="error"><?php echo form_error(
                                                        "new_gender_one"
                                                    ); ?></span>
                                                <?php } ?>
                                            </li>
                                            <li class="">for all purposes. &nbsp;&nbsp;</li>
                                            <br><br>
                                        </ul>
                                        <ul class="div_sign remove_buttom_border">
                                            <li >
                                                <input type="text" id="signature" name="signature" placeholder="Signature" class="form-control alpha_only custom_notic_width paragrap-space"  autocomplete="off" minlength="4" maxlength="50">
                                                <?php if (
                                                    form_error("signature")
                                                ) { ?>
                                                    <span class="error"><?php echo form_error(
                                                        "signature"
                                                    ); ?></span>
                                                <?php } ?>
                                            </li>
                                        </ul>
                                    </div>
                                    </div>
                                </div> 
                            </div>
                            <!-- Not a Minor Notice Part End -->

                            <!-- Minor Notice Part Start -->
                            <div class="header-gazette not_div" id="gender_yes" style="display: none;">    
                                <div class="header-gazette">   
                                    <p class="sub-head"><h3>Notice</h3></p>                   
                                    <div class="gazette-nav-bar remove_buttom_border left-align-text">
                                        <ul> <li class="">By virtue of an affidavit sworn before the &nbsp;&nbsp;</li>
                                            <li>
                                                <select   class="form-control paragrap-space" id="approver_minor" name="approver_minor">
                                                    <!--                                        <option value="">Select executive</option>-->
                                                    <option selected="selected" value="Executive Magistrate">Executive Magistrate</option>
                                                    <option value="Notary Public">Notary Public</option>
                                                </select>
                                                <?php if (
                                                    form_error("approver_minor")
                                                ) { ?>
                                                    <span class="error"><?php echo form_error(
                                                        "approver_minor"
                                                    ); ?></span>
                                                <?php } ?>
                                            </li>
                                            <li class="">&nbsp;,</li>
                                            <li class="">
                                                <input type="text" id="place_minor" name="place_minor" placeholder="Place" class="form-control alpha_only" autocomplete="off" minlength="4" maxlength="50">
                                                <?php if (
                                                    form_error("place_minor")
                                                ) { ?>
                                                    <span class="error"><?php echo form_error(
                                                        "place_minor"
                                                    ); ?></span>
                                                <?php } ?>
                                            </li>
                                            <li class="">&nbsp;on Dated &nbsp;</li>
                                            <li class="">
                                                    <input type="text" id="notice_date_minor" name="notice_date_minor" placeholder="DD-MM-YYYY" class="form-control"  autocomplete="off">
                                                    <?php if (
                                                        form_error("notice_date_minor")
                                                    ) { ?>
                                                        <span class="error"><?php echo form_error(
                                                            "notice_date_minor"
                                                        ); ?></span>
                                                    <?php } ?>
                                                </li>
                                            <li class="">&nbsp;&nbsp;,&nbsp;&nbsp;I ,&nbsp;&nbsp;</li>                               
                                            <li>
                                                <select   class="form-control" id="salutation_minor" name="salutation_minor">
                                                    <option value="">Select salutation</option>
                                                    <option selected="selected" value="Mr.">Mr.</option>
                                                    <option value="Mrs.">Mrs.</option>
                                                    <option value="Miss.">Miss.</option>
                                                </select>
                                                <?php if (
                                                    form_error("salutation_minor")
                                                ) { ?>
                                                    <span class="error"><?php echo form_error(
                                                        "salutation_minor"
                                                    ); ?></span>
                                                <?php } ?>
                                            </li>
                                            <li class="">
                                                <input type="text" id="name_for_notice_minor" name="name_for_notice_minor" placeholder="Name" class="form-control alpha_only custom_notic_width"  autocomplete="off" minlength="4" maxlength="50">
                                                <?php if (
                                                    form_error("name_for_notice_minor")
                                                ) { ?>
                                                    <span class="error"><?php echo form_error(
                                                        "name_for_notice_minor"
                                                    ); ?></span>
                                                <?php } ?>
                                            </li>
                                            <!-- <li class="">&nbsp;Address&nbsp;&nbsp;</li>  -->
                                            <li class="">&nbsp;,</li>
                                            <li class="">
                                                <textarea id="address_minor" placeholder="address" name="address_minor"class="form-control alpha_only"  autocomplete="off" minlength="4" maxlength="150"></textarea>
                                                <?php if (
                                                    form_error("address_minor")
                                                ) { ?>
                                                    <span class="error"><?php echo form_error(
                                                        "address_minor"
                                                    ); ?></span>
                                                <?php } ?>
                                            </li>
                                            <li class="">have changed my&nbsp;&nbsp;</li> 
                                            <li>
                                                <select   class="form-control" id="son_daughter" name="son_daughter">
                                                    <option value="Son's">Son's</option>
                                                    <option value="Daughter's">Daughter's</option>
                                                </select>
                                                <?php if (
                                                    form_error("son_daughter")
                                                ) { ?>
                                                    <span class="error"><?php echo form_error(
                                                        "son_daughter"
                                                    ); ?></span>
                                                <?php } ?>
                                            </li>
                                            <li class="">&nbsp;&nbsp;gender from&nbsp;&nbsp;</li> 
                                            <li class="">
                                                <input type="text" id="old_gender_minor" name="old_gender_minor" placeholder="Old Gender" class="form-control alpha_only"  autocomplete="off" minlength="4" maxlength="50">
                                                <?php if (
                                                    form_error("old_gender_minor")
                                                ) { ?>
                                                    <span class="error"><?php echo form_error(
                                                        "old_gender_minor"
                                                    ); ?></span>
                                                <?php } ?>
                                            </li>
                                            <li class="">&nbsp;&nbsp; to &nbsp;&nbsp;</li> 
                                            <li class="">
                                                <input type="text" id="new_gender_minor" name="new_gender_minor" placeholder="New Gender" class="form-control alpha_only"  autocomplete="off" minlength="4" maxlength="50">
                                                <?php if (
                                                    form_error("new_gender_minor")
                                                ) { ?>
                                                    <span class="error"><?php echo form_error(
                                                        "new_gender_minor"
                                                    ); ?></span>
                                                <?php } ?>
                                            </li>
                                            <li>.</li>
                                            <li class="">&nbsp;&nbsp;Henceforth,&nbsp;&nbsp;</li> 
                                            <li>&nbsp;&nbsp;</li>
                                            <li>
                                                <select   class="form-control" id="gender" name="gender">
                                                    <option value="He">He</option>
                                                    <option value="She">She</option>
                                                </select>
                                                <?php if (form_error("gender")) { ?>
                                                    <span class="error"><?php echo form_error(
                                                        "gender"
                                                    ); ?></span>
                                                <?php } ?>
                                            </li>
                                            <li>&nbsp;&nbsp;will be change&nbsp;&nbsp;</li> 
                                            <li>
                                                <select   class="form-control" id="gender_his_her" name="gender_his_her">
                                                    <option value="his">his</option>
                                                    <option value="her">her</option>
                                                </select>
                                                <?php if (form_error("gender_his_her")) { ?>
                                                    <span class="error"><?php echo form_error(
                                                        "gender_his_her"
                                                    ); ?></span>
                                                <?php } ?>
                                            </li>
                                             <li>gender as</li>
                                            <li class="">
                                                <input type="text" id="new_gender_one_minor" name="new_gender_one_minor" placeholder="New Gender" class="form-control alpha_only"  autocomplete="off" minlength="4" maxlength="50">
                                                <?php if (
                                                    form_error("new_gender_one_minor")
                                                ) { ?>
                                                    <span class="error"><?php echo form_error(
                                                        "new_gender_one_minor"
                                                    ); ?></span>
                                                <?php } ?>
                                            </li>
                                            <li class="">for all purposes. &nbsp;&nbsp;</li>
                                            <br><br>
                                        </ul>
                                        <ul class="div_sign remove_buttom_border">
                                            <li >
                                                <input type="text" id="signature_minor" name="signature_minor" placeholder="Signature" class="form-control alpha_only custom_notic_width paragrap-space"  autocomplete="off" minlength="4" maxlength="50">
                                                <?php if (
                                                    form_error("signature_minor")
                                                ) { ?>
                                                    <span class="error"><?php echo form_error(
                                                        "signature_minor"
                                                    ); ?></span>
                                                <?php } ?>
                                            </li>
                                        </ul>
                                    </div>
                                </div> 
                            </div>
                            <!-- Minor Notice Part End -->

                            <!-- Name & Gender Change Notice Part Start -->
                            <div class="header-gazette not_div" id="name_gender_change_yes" style="display: none;">    
                                <div class="header-gazette">   
                                    <p class="sub-head"><h3>Notice</h3></p> 
                                    <div class="navbar_yes">                 
                                        <div class="gazette-nav-bar remove_buttom_border left-align-text">
                                            <ul>
                                                <li class="">By virtue of an affidavit sworn before the &nbsp;&nbsp;</li>
                                                <li>
                                                    <select class="form-control paragrap-space" id="name_gender_approver" name="name_gender_approver">
                                                        <option selected="selected" value="Executive Magistrate">Executive Magistrate</option>
                                                        <option value="Notary Public">Notary Public</option>
                                                    </select>
                                                    <?php if (form_error("name_gender_approver")) { ?>
                                                        <span class="error"><?php echo form_error("name_gender_approver"); ?></span>
                                                    <?php } ?>
                                                </li>
                                                <li>,&nbsp;&nbsp;</li>
                                                <li class="">
                                                    <input type="text" id="name_gender_place" name="name_gender_place" placeholder="Place" class="form-control alpha_only custom_notic_width" autocomplete="off" minlength="4" maxlength="50">
                                                    <?php if (form_error("name_gender_place")) { ?>
                                                        <span class="error"><?php echo form_error("name_gender_place"); ?></span>
                                                    <?php } ?>
                                                </li>
                                                <li>,&nbsp;&nbsp;</li>
                                                <li class="">&nbsp;on Dated &nbsp;</li>
                                                <li class="custom-date">
                                                    <input type="text" class="notice_date_design" id="name_gender_notice_date" name="name_gender_notice_date" placeholder="DD-MM-YYYY" autocomplete="off">
                                                </li>
                                                <li class="">&nbsp;&nbsp;,&nbsp;&nbsp;I ,&nbsp;&nbsp;</li>                               
                                                <li>
                                                    <select class="form-control" id="name_gender_salutation" name="name_gender_salutation" required="true">
                                                        <option value="">Select salutation</option>
                                                        <option selected="selected" value="Mr.">Mr.</option>
                                                        <option value="Mrs.">Mrs.</option>
                                                        <option value="Miss.">Miss.</option>
                                                    </select>
                                                    <?php if (form_error("name_gender_salutation")) { ?>
                                                        <span class="error"><?php echo form_error("name_gender_salutation"); ?></span>
                                                    <?php } ?>
                                                </li>
                                                <li class="">
                                                    <input type="text" id="name_gender_notice" name="name_gender_notice" placeholder="Name" class="form-control alpha_only custom_notic_width" autocomplete="off" minlength="4" maxlength="50">
                                                    <?php if (form_error("name_gender_notice")) { ?>
                                                        <span class="error"><?php echo form_error("name_gender_notice"); ?></span>
                                                    <?php } ?>
                                                </li>
                                                <li class="">&nbsp;,</li> 
                                                <li class="add">
                                                    <textarea id="name_gender_address" name="name_gender_address" placeholder="Address" class="form-control alpha_only" autocomplete="off" minlength="4" maxlength="150"></textarea>
                                                    <?php if (form_error("name_gender_address")) { ?>
                                                        <span class="error"><?php echo form_error("name_gender_address"); ?></span>
                                                    <?php } ?>
                                                </li>
                                                <li class="">, have changed my name from&nbsp;&nbsp;</li> 
                                                <li class="">
                                                    <input type="text" id="old_name_two" name="old_name_two" placeholder="Old Name" class="form-control alpha_only custom_notic_width" autocomplete="off" minlength="4" maxlength="50">
                                                    <?php if (form_error("old_name_two")) { ?>
                                                        <span class="error"><?php echo form_error("old_name_two"); ?></span>
                                                    <?php } ?>
                                                </li>
                                                <li class="">&nbsp;&nbsp; to &nbsp;&nbsp;</li> 
                                                <li class="">
                                                    <input type="text" id="new_name_two" name="new_name_two" placeholder="New name" class="form-control alpha_only custom_notic_width" autocomplete="off" minlength="4" maxlength="50">
                                                    <?php if (form_error("new_name_two")) { ?>
                                                        <span class="error"><?php echo form_error("new_name_two"); ?></span>
                                                    <?php } ?>
                                                </li>
                                                <li class="">, and changed my gender from&nbsp;&nbsp;</li> 
                                                <li class="">
                                                    <input type="text" id="old_gender_two" name="old_gender_two" placeholder="Old Gender" class="form-control alpha_only custom_notic_width" autocomplete="off" minlength="4" maxlength="50">
                                                    <?php if (form_error("old_gender_two")) { ?>
                                                        <span class="error"><?php echo form_error("old_gender_two"); ?></span>
                                                    <?php } ?>
                                                </li>
                                                <li class="">&nbsp;&nbsp; to &nbsp;&nbsp;</li> 
                                                <li class="">
                                                    <input type="text" id="new_gender_two" name="new_gender_two" placeholder="New Gender" class="form-control alpha_only custom_notic_width" autocomplete="off" minlength="4" maxlength="50">
                                                    <?php if (form_error("new_gender_two")) { ?>
                                                        <span class="error"><?php echo form_error("new_gender_two"); ?></span>
                                                    <?php } ?>
                                                </li>

                                                <li class=""> &nbsp;. Henceforth,&nbsp;I shall be known as&nbsp; </li> 
                                                <li class="">
                                                    <input type="text" id="new_name_three" name="new_name_three" placeholder="New name" class="form-control alpha_only custom_notic_width" autocomplete="off" minlength="4" maxlength="50">
                                                    <?php if (form_error("new_name_three")) { ?>
                                                        <span class="error"><?php echo form_error("new_name_three"); ?></span>
                                                    <?php } ?>
                                                </li>
                                                <li class="">, and I will change my gender to </li>
                                                <li class="">
                                                    <input type="text" id="new_gender_three" name="new_gender_three" placeholder="New Gender" class="form-control alpha_only custom_notic_width" autocomplete="off" minlength="4" maxlength="50">
                                                    <?php if (form_error("new_gender_three")) { ?>
                                                        <span class="error"><?php echo form_error("new_gender_three"); ?></span>
                                                    <?php } ?>
                                                </li>
                                                <li class=""> for all purposes. &nbsp;&nbsp;</li>
                                                <br><br>
                                            </ul>
                                            <ul class="div_sign remove_buttom_border">
                                                <li >
                                                    <input type="text" id="name_gender_notice_signature" name="name_gender_notice_signature" placeholder="Signature" class="form-control alpha_only custom_notic_width paragrap-space" autocomplete="off" minlength="4" maxlength="50">
                                                    <?php if (form_error("name_gender_notice_signature")) { ?>
                                                        <span class="error"><?php echo form_error("name_gender_notice_signature"); ?></span>
                                                    <?php } ?>
                                                </li>
                                            </ul>
                                        </div>
                                    </div>
                                </div> 
                            </div>
                            <!-- Name & Gender Change Notice Part end -->

                            <!-- Minor Yes and Name with gender change Yes start -->
                            <div class="header-gazette not_div" id="minor_yes_name_with_gender_change_yes" style="display: none;">    
                                <div class="header-gazette">   
                                    <p class="sub-head"><h3>Notice</h3></p>                   
                                    <div class="gazette-nav-bar remove_buttom_border left-align-text">
                                        <ul> <li class="">By virtue of an affidavit sworn before the &nbsp;&nbsp;</li>
                                            <li>
                                                <select   class="form-control paragrap-space" id="name_gender_minor_yes_approver" name="name_gender_minor_yes_approver">
                                                    <option selected="selected" value="Executive Magistrate">Executive Magistrate</option>
                                                    <option value="Notary Public">Notary Public</option>
                                                </select>
                                                <?php if (
                                                    form_error("name_gender_minor_yes_approver")
                                                ) { ?>
                                                    <span class="error"><?php echo form_error(
                                                        "name_gender_minor_yes_approver"
                                                    ); ?></span>
                                                <?php } ?>
                                            </li>
                                            <li class="">&nbsp;,</li>
                                            <li class="">
                                                <input type="text" id="name_gender_minor_yes_place" name="name_gender_minor_yes_place" placeholder="Place" class="form-control alpha_only" autocomplete="off" minlength="4" maxlength="50">
                                                <?php if (
                                                    form_error("name_gender_minor_yes_place")
                                                ) { ?>
                                                    <span class="error"><?php echo form_error(
                                                        "name_gender_minor_yes_place"
                                                    ); ?></span>
                                                <?php } ?>
                                            </li>
                                            <li class="">&nbsp;on Dated &nbsp;</li>
                                            <li class="">
                                                    <input type="date" id="name_gender_minor_yes_date" name="name_gender_minor_yes_date" placeholder="DD-MM-YYYY" class="form-control"  autocomplete="off">
                                                    <?php if (
                                                        form_error("name_gender_minor_yes_date")
                                                    ) { ?>
                                                        <span class="error"><?php echo form_error(
                                                            "name_gender_minor_yes_date"
                                                        ); ?></span>
                                                    <?php } ?>
                                                </li>
                                            <li class="">&nbsp;&nbsp;,&nbsp;&nbsp;I ,&nbsp;&nbsp;</li>                               
                                            <li>
                                                <select   class="form-control" id="name_gender_minor_yes_salutation" name="name_gender_minor_yes_salutation">
                                                    <option value="">Select salutation</option>
                                                    <option selected="selected" value="Mr.">Mr.</option>
                                                    <option value="Mrs.">Mrs.</option>
                                                    <option value="Miss.">Miss.</option>
                                                </select>
                                                <?php if (
                                                    form_error("name_gender_minor_yes_salutation")
                                                ) { ?>
                                                    <span class="error"><?php echo form_error(
                                                        "name_gender_minor_yes_salutation"
                                                    ); ?></span>
                                                <?php } ?>
                                            </li>
                                            <li class="">
                                                <input type="text" id="name_gender_minor_yes_notice" name="name_gender_minor_yes_notice" placeholder="Name" class="form-control alpha_only custom_notic_width"  autocomplete="off" minlength="4" maxlength="50">
                                                <?php if (
                                                    form_error("name_gender_minor_yes_notice")
                                                ) { ?>
                                                    <span class="error"><?php echo form_error(
                                                        "name_gender_minor_yes_notice"
                                                    ); ?></span>
                                                <?php } ?>
                                            </li>
                                            <li class="">&nbsp;,</li>
                                            <li class="">
                                                <textarea id="name_gender_minor_yes_address" placeholder="address" name="name_gender_minor_yes_address"class="form-control alpha_only"  autocomplete="off" minlength="4" maxlength="150"></textarea>
                                                <?php if (
                                                    form_error("name_gender_minor_yes_address")
                                                ) { ?>
                                                    <span class="error"><?php echo form_error(
                                                        "name_gender_minor_yes_address"
                                                    ); ?></span>
                                                <?php } ?>
                                            </li>
                                            <li class="">have changed my&nbsp;&nbsp;</li> 
                                            <li>
                                                <select   class="form-control" id="name_gender_minor_yes_son_daughter" name="name_gender_minor_yes_son_daughter">
                                                    <option value="Son's">Son's</option>
                                                    <option value="Daughter's">Daughter's</option>
                                                </select>
                                                <?php if (
                                                    form_error("name_gender_minor_yes_son_daughter")
                                                ) { ?>
                                                    <span class="error"><?php echo form_error(
                                                        "name_gender_minor_yes_son_daughter"
                                                    ); ?></span>
                                                <?php } ?>
                                            </li>
                                            <li class="">&nbsp;&nbsp;gender from&nbsp;&nbsp;</li> 
                                            <li class="">
                                                <input type="text" id="old_minor_gender_three" name="old_minor_gender_three" placeholder="Old Gender" class="form-control alpha_only"  autocomplete="off" minlength="4" maxlength="50">
                                                <?php if (
                                                    form_error("old_minor_gender_three")
                                                ) { ?>
                                                    <span class="error"><?php echo form_error(
                                                        "old_minor_gender_three"
                                                    ); ?></span>
                                                <?php } ?>
                                            </li>
                                            <li class="">&nbsp;&nbsp; to &nbsp;&nbsp;</li> 
                                            <li class="">
                                                <input type="text" id="new_minor_gender_three" name="new_minor_gender_three" placeholder="New Gender" class="form-control alpha_only"  autocomplete="off" minlength="4" maxlength="50">
                                                <?php if (
                                                    form_error("new_minor_gender_three")
                                                ) { ?>
                                                    <span class="error"><?php echo form_error(
                                                        "new_minor_gender_three"
                                                    ); ?></span>
                                                <?php } ?>
                                            </li>
                                            <li class="">&nbsp;&nbsp;and, name from&nbsp;&nbsp;</li> 
                                            <li class="">
                                                <input type="text" id="old_minor_name_three" name="old_minor_name_three" placeholder="Old Name" class="form-control alpha_only"  autocomplete="off" minlength="4" maxlength="50">
                                                <?php if (
                                                    form_error("old_minor_name_three")
                                                ) { ?>
                                                    <span class="error"><?php echo form_error(
                                                        "old_minor_name_three"
                                                    ); ?></span>
                                                <?php } ?>
                                            </li>
                                            <li class="">&nbsp;&nbsp; to &nbsp;&nbsp;</li> 
                                            <li class="">
                                                <input type="text" id="new_minor_name_three" name="new_minor_name_three" placeholder="New Name" class="form-control alpha_only"  autocomplete="off" minlength="4" maxlength="50">
                                                <?php if (
                                                    form_error("new_minor_name_three")
                                                ) { ?>
                                                    <span class="error"><?php echo form_error(
                                                        "new_minor_name_three"
                                                    ); ?></span>
                                                <?php } ?>
                                            </li>
                                            <li>.</li>
                                            <li class=""> &nbsp;Henceforth,&nbsp; </li> 
                                            <li>&nbsp;&nbsp;</li>
                                            <li>
                                                <select class="form-control" id="he_she_gender" name="he_she_gender">
                                                    <option value="He">He</option>
                                                    <option value="She">She</option>
                                                </select>
                                                <?php if (form_error("he_she_gender")) { ?>
                                                    <span class="error"><?php echo form_error(
                                                        "he_she_gender"
                                                    ); ?></span>
                                                <?php } ?>
                                            </li>
                                            <li> will be known as&nbsp; </li> 
                                                <li class="">
                                                    <input type="text" id="new_minor_name_four" name="new_minor_name_four" placeholder="New name" class="form-control alpha_only custom_notic_width" autocomplete="off" minlength="4" maxlength="50">
                                                    <?php if (form_error("new_minor_name_four")) { ?>
                                                        <span class="error"><?php echo form_error("new_minor_name_four"); ?></span>
                                                    <?php } ?>
                                                </li>
                                                <li class=""> and change&nbsp;</li> 
                                                <li>&nbsp;&nbsp;</li>
                                                <li>
                                                    <select   class="form-control" id="his_her_gender" name="his_her_gender">
                                                        <option value="his">his</option>
                                                        <option value="her">her</option>
                                                    </select>
                                                    <?php if (form_error("his_her_gender")) { ?>
                                                        <span class="error"><?php echo form_error(
                                                            "his_her_gender"
                                                        ); ?></span>
                                                    <?php } ?>
                                                </li>
                                                <li>gender to </li>
                                                <li class="">
                                                    <input type="text" id="new_minor_gender_four" name="new_minor_gender_four" placeholder="New Gender" class="form-control alpha_only custom_notic_width" autocomplete="off" minlength="4" maxlength="50">
                                                    <?php if (form_error("new_minor_gender_four")) { ?>
                                                        <span class="error"><?php echo form_error("new_minor_gender_four"); ?></span>
                                                    <?php } ?>
                                                </li>
                                                <li class=""> for all purposes. &nbsp;&nbsp;</li>
                                            <br><br>
                                        </ul>
                                        <ul class="div_sign remove_buttom_border">
                                            <li >
                                                <input type="text" id="name_gender_minor_signature" name="name_gender_minor_signature" placeholder="Signature" class="form-control alpha_only custom_notic_width paragrap-space"  autocomplete="off" minlength="4" maxlength="50">
                                                <?php if (
                                                    form_error("name_gender_minor_signature")
                                                ) { ?>
                                                    <span class="error"><?php echo form_error(
                                                        "name_gender_minor_signature"
                                                    ); ?></span>
                                                <?php } ?>
                                            </li>
                                        </ul>
                                    </div>
                                </div> 
                            </div>
                            <!-- Minor Yes and Name with gender change Yes end -->

                            <div class="boxs-footer text-right bg-tr-black lter dvd dvd-top">
                                <button type="submit" class="btn btn-raised btn-success change-name-sur-submit" id="form4Submit">Submit</button>
                            </div>
                            <!-- row end -->
                        </div>
                        <!-- boxs-body end -->
                    <?php echo form_close(); ?>
                    <!-- gender form ending -->
                </section>
            </div>
        </div>
</div>
</section>


<!-- <script src="https://code.jquery.com/jquery-3.4.1.min.js" type="text/javascript" nonce="a6b9a780936c8e980939086f618dded2"></script> -->
<script src="https://ajax.googleapis.com/ajax/libs/jquery/3.5.1/jquery.min.js" nonce="8f0882ce3be14f201cadd0eff5726cbd"></script>
<script src="https://cdn.jsdelivr.net/npm/jquery-validation@1.19.3/dist/jquery.validate.js"></script>
<script src="https://cdn.jsdelivr.net/npm/jquery-validation@1.19.3/dist/additional-methods.min.js"></script>
<script src="https://cdn.jsdelivr.net/jquery.validation/1.16.0/jquery.validate.min.js"></script>

<script>
    $(document).ready(function () { // document ready start

        // notice code
        $("#new_gender_minor").keyup(function(){
            let get_new_gender = $("#new_gender_minor").val();
            $("#new_gender_one_minor").val(get_new_gender);
        });

        $("#name_for_notice_minor").keyup(function(){
            let get_new_gender_1 = $("#name_for_notice_minor").val();
            $("#signature_minor").val(get_new_gender_1);
        });

        $("#name_for_notice").keyup(function(){
            let get_new_gender_2 = $("#name_for_notice").val();
            $("#signature").val(get_new_gender_2);
        });

        $('#new_gender').keyup(function () {
            let get_new_gender_3 = $('#new_gender').val();
            $("#new_gender_one").val(get_new_gender_3);
        });

        $("#name_gender_notice").on("input", function() {
            let newName = $(this).val();
            $("#name_gender_notice_signature").val(newName);
        });

        $("#new_name_two").on("input", function() {
            let newNameTwo = $(this).val();
            $("#new_name_three").val(newNameTwo);
        });

        $("#new_gender_two").on("input", function() {
            let newGenderTwo = $(this).val();
            $("#new_gender_three").val(newGenderTwo);
        });

        // Notice part jquery of minor_yes_name_and_gender_change_yes start
        $("#name_gender_minor_yes_notice").keyup(function(){
            let get_new_gender_4 = $("#name_gender_minor_yes_notice").val();
            $("#name_gender_minor_signature").val(get_new_gender_4);
        });

        $("#new_minor_gender_three").keyup(function(){
            let get_new_gender_4 = $("#new_minor_gender_three").val();
            $("#new_minor_gender_four").val(get_new_gender_4);
        });

        $("#new_minor_name_three").keyup(function(){
            let get_new_gender_4 = $("#new_minor_name_three").val();
            $("#new_minor_name_four").val(get_new_gender_4);
        });
        
        // Notice part jquery of minor_yes_name_and_gender_change_yes end


        /*
            * Restrict alphabetic input only using class number_only in the input field
            */
        $('input.alpha_only').keypress(function (e) {
            var regex = new RegExp("^[a-zA-Z\ ]$");
            var str = String.fromCharCode(!e.charCode ? e.which : e.charCode);
            if (regex.test(str)) {
                return true;
            }
            e.preventDefault();
            return false;
        });

        /*
            * Restrict number input only using class number_only in the input field
            */
        $('input.number_only').keyup(function (e) {
            if (/\D/g.test(this.value)) {
                // Filter non-digits from input value.
                this.value = this.value.replace(/\D/g, '');
            }
        });

        $("#notice_date").datepicker({
            dateFormat: 'dd-mm-yy',
            autoclose: true,
            todayHighlight: true,
            maxDate: new Date(),
            onSelect: function (selected) {
                $("#t_date").datepicker("option", "minDate", selected)
            }
        });

        $("#name_gender_notice_date").datepicker({
            dateFormat: 'dd-mm-yy',
            autoclose: true,
            todayHighlight: true,
            maxDate: new Date(),
            onSelect: function (selected) {
                $("#t_date").datepicker("option", "minDate", selected)
            }
        });



        $("#notice_date_minor").datepicker({
            dateFormat: 'dd-mm-yy',
            autoclose: true,
            todayHighlight: true,
            maxDate: new Date(),
            onSelect: function (selected) {
                $("#t_date").datepicker("option", "minDate", selected)
            }
        });

        

        // filesize code
        $.validator.addMethod('filesize', function (value, element, param) {
            return this.optional(element) || (element.files[0].size <= param)
        }, 'File size must be less than {0} bytes');

        $('#form_gender').validate({
                rules: {
                    state_id: {
                        required: true,
                    },
                        district_id: {
                        required: true
                    },
                    block_ulb_id: {
                        required: true
                    },
                    address_1: {
                        required: true,
                        minlength: 5,
                        maxlength: 200
                    },
                    pin_code: {
                        required: true,
                        minlength: 6,
                        maxlength: 6
                    },
                    govt_emp: {
                        required: true
                    },
                    minor: {
                        required: true
                    },
                    docu_1: {
                        required: true,
                        filesize: 5242880
                    },
                    docu_2: {
                        required: true,
                        filesize: 5242880
                    },
    
                    docu_4: {
                        required: true,
                        filesize: 5242880
                    },
                    docu_5: {
                        required: true,
                        filesize: 5242880
                    },
                    docu_6: {
                        required: true,
                        filesize: 5242880
                    },
                    docu_7: {
                        required: true,
                        filesize: 5242880
                    },
                    docu_8: {
                        required: true,
                        filesize: 5242880
                    },
                    docu_9: {
                        required: true,
                        filesize: 5242880
                    },
                    // approver:{
                    //     required: true,
                    // },
                    place: {
                        required: true,
                        minlength: 4,
                        maxlength: 50
                    },
                    notice_date: {
                        required: true
                    },
                    salutation: {
                        required: true
                    },
                    name_for_notice: {
                        required: true,
                        minlength: 4,
                        maxlength: 50
                    },
                    address: {
                        required: true
                    },
                    son_daughter:{
                        // required: {
                        //     depends: function () {
                        //         return !($("input[type='radio'].minor[value='1']").is(":checked"));
                        //     }
                        // }
                        required:true
                    },
                    gender:{
                        // required: {
                        //     depends: function () {
                        //         return !($("input[type='radio'].minor[value='1']").is(":checked"));
                        //     }
                        // }
                        required:true
                    },
                    name_for_notice_minor: {
                        required: true,
                        minlength: 4,
                        maxlength: 50,
                        // equalTo:"#old_gender_minor"
                    },
                    old_gender: {
                        required: true,
                        minlength: 4,
                        maxlength: 50
                    },
                    new_gender: {
                        required: true,
                        minlength: 4,
                        maxlength: 50
                    },
                    new_gender_one: {
                        required: true,
                        minlength: 4,
                        maxlength: 50,
                        equalTo: "#new_gender"
                    },
                    signature: {
                        required: true,
                        minlength: 4,
                        maxlength: 50,
                        equalTo: "#name_for_notice"
                    },
                    place_minor:{
                        required:true
                    },
                    address_minor: {
                        required: true
                    },
                    old_gender_minor: {
                        required: true,
                        minlength: 4,
                        maxlength: 50
                    },
                    new_gender_minor: {
                        required: true,
                        minlength: 4,
                        maxlength: 50
                    },
                    new_gender_one_minor: {
                        required: true,
                        minlength: 4,
                        maxlength: 50,
                        equalTo: "#new_gender_minor"
                    },
                    signature_minor: {
                        required: true,
                        minlength: 4,
                        maxlength: 50,
                        equalTo: "#name_for_notice_minor"
                    }, //
                    name_gender_yes_approver: {
                        reuired: true
                    },
                    name_gender_place: {
                        required: true,
                        minlength: 4,
                        maxlength: 50
                    },
                    name_gender_notice_date: {
                        required: true
                    },
                    // name_gender_salutation: {
                    //     required: true
                    // },
                    name_gender_notice: {
                        required: true,
                        minlength: 4,
                        maxlength: 50
                    },
                    name_gender_address: {
                        required: true,
                        minlength: 4,
                        maxlength: 50
                    },
                    old_name_two: {
                        required: true,
                        minlength: 4,
                        maxlength: 50
                    },
                    new_name_two: {
                        required: true,
                        minlength: 4,
                        maxlength: 50
                    },
                    old_gender_two: {
                        required: true,
                        minlength: 4,
                        maxlength: 50
                    },
                    new_gender_two: {
                        required: true,
                        minlength: 4,
                        maxlength: 50
                    },
                    new_name_three: {
                        required: true,
                        minlength: 4,
                        maxlength: 50,
                        equalTo: '#new_name_two'
                    },
                    new_gender_three: {
                        required: true,
                        minlength: 4,
                        maxlength: 50,
                        equalTo: '#new_gender_two'
                    },
                    name_gender_notice_signature: {
                        required: true,
                        minlength: 4,
                        maxlength: 50,
                        equalTo: '#name_gender_notice'
                    },
                    //Need to implement the validation of new notice
                    name_gender_minor_yes_place: {
                        required: true,
                        minlength: 4,
                        maxlength: 50
                    },
                    name_gender_minor_yes_date: {
                        required: true,
                    },
                    name_gender_minor_yes_notice: {
                        required: true,
                        minlength: 4,
                        maxlength: 50
                    },
                    name_gender_minor_yes_address: {
                        required: true,
                        minlength: 4,
                        maxlength: 50
                    },
                    old_minor_gender_three: {
                        required: true,
                        minlength: 4,
                        maxlength: 50
                    },
                    new_minor_gender_three: {
                        required: true,
                        minlength: 4,
                        maxlength: 50
                    },
                    old_minor_name_three: {
                        required: true,
                        minlength: 4,
                        maxlength: 50
                    },
                    new_minor_name_three: {
                        required: true,
                        minlength: 4,
                        maxlength: 50
                    },
                    new_minor_name_four: {
                        required: true,
                        minlength: 4,
                        maxlength: 50,
                        equalTo: '#new_minor_name_three'
                    },
                    new_minor_gender_four: {
                        required: true,
                        minlength: 4,
                        maxlength: 50,
                        equalTo: '#new_minor_gender_three'
                    },
                    name_gender_minor_signature: {
                        required: true,
                        minlength: 4,
                        maxlength: 50,
                        equalTo: '#name_gender_minor_yes_notice'
                    },
                },
                messages: {
                    state_id: {
                        required: "Please select state"
                    },
                    district_id: {
                        required: "Please select district"
                    },
                    block_ulb_id: {
                        required: "Please select Block/ULB"
                    },
                    address_1: {
                        required: "Please enter address",
                        minlength: "Please enter minimum 5 characters",
                        maxlength: "Please enter maximum 200 characters"
                    },
                    // govt_emp: {
                    //     required: "Please select government employee"
                    // },
                    pin_code: {
                        required: "Please enter pincode",
                        minlength: "Pincode must be 6 digit",
                        maxlength: "Pincode must be 6 digit"
                    },
                    // minor: {
                    //     required: "Please select minor"
                    // },
                    docu_1: {
                        required: "Please select scanned copy of affidavit",
                        filesize: "File size must be less than 5 MB"
                    },
                    docu_2: {
                        required: "Please select scanned copy of original newspaper",
                        filesize: "File size must be less than 5 MB"

                    },
    
                    docu_4: {
                        required: "Please select scanned copy of medical certificate",
                        filesize: "File size must be less than 5 MB"
                    },
                    docu_5: {
                        required: "Please select scanned copy of ID proof",
                        filesize: "File size must be less than 5 MB"
                    },
                    docu_6: {
                        required: "Please select scanned copy of address proof",
                        filesize: "File size must be less than 5 MB"
                    },
                    docu_7: {
                        required: "Please select scanned copy of deed changing form",
                        filesize: "File size must be less than 5 MB"
                    },
                    
                    docu_8: {
                        required: "Please select scanned copy of age proof",
                        filesize: "File size must be less than 5 MB"
                    },
                    docu_9: {
                        required: "Please select scanned copy of approval authority",
                        filesize: "File size must be less than 5 MB"
                    },
                    // approver:{
                    // required: "This is a required field"
                    // },
                    place: {
                        required: "Please enter place",
                        minlength: "Please enter minimum 4 characters",
                        maxlength: "Please enter minimum 50 characters",
                    },
                    notice_date: {
                        required: "Please select date"
                    },
                    salutation: {
                        required: "Please select salutation"
                    },
                    name_for_notice: {
                        required: "Please enter Name",
                        minlength: "Please enter minimum 4 characters",
                        maxlength: "Please enter minimum 50 characters",
                    },
                    address: {
                        required: "Please enter address"
                    },
                    son_daughter:{
                        required:"Please enter Relation"
                    },
                    gender: {
                        required: "Please enter gender"
                    },
                    old_gender: {
                        required: "Please enter old gender",
                        minlength: "Please enter minimum 4 characters",
                        maxlength: "Please enter minimum 50 characters",
                    },
                    new_gender: {
                        required: "Please enter new gender",
                        minlength: "Please enter minimum 4 characters",
                        maxlength: "Please enter minimum 50 characters"
                    },
                    new_gender_one: {
                        required: "Please enter new gender",
                        minlength: "Please enter minimum 4 characters",
                        maxlength: "Please enter minimum 50 characters",
                        equalTo: "Gender must be same as New Gender"
                    },
                    signature: {
                        required: "Please enter signature",
                        minlength: "Please enter minimum 4 characters",
                        maxlength: "Please enter minimum 50 characters",
                        equalTo: "Signature must be same as Name"
                    },
                    name_for_notice_minor: {
                        required: "Please enter name",
                        minlength: "Please enter minimum 4 characters",
                        maxlength: "Please enter minimum 50 characters",
                    },
                    place_minor:{
                        required: "Please enter place"
                    },
                    address_minor: {
                        required: "Please enter address"
                    },
                    old_gender_minor:{
                        required: "Please enter old gender"
                    },
                    new_gender_minor:{
                        required: "Please enter new gender"
                    },
                    new_gender_one_minor:{
                        required: "Please enter new gender",
                        equalTo: "Gender must be same as New Gender"  
                    },
                    signature_minor:{
                        required: "Please enter signature",
                        equalTo: "Signature must be same as Name" 
                    },//End Of Minor Yes
                    name_gender_approver: {
                        required: "Please enter approver"
                    },
                    name_gender_place: {
                        required: "Please enter address",
                        minlength: "Please enter minimum 4 characters",
                        maxlength: "Please enter minimum 50 characters",
                    },
                    name_gender_notice_date: {
                        required: 'Please Enter Date'
                    },
                    name_gender_salutation: {
                        required: "Please enter salutation"
                    },
                    name_gender_notice: {
                        required: 'Please Enter Name'
                    },
                    name_gender_address: {
                        required: "Please enter address",
                        minlength: "Please enter minimum 4 characters",
                        maxlength: "Please enter minimum 50 characters",
                    },
                    old_name_two: {
                        required: 'Please Enter Old Name'
                    },
                    new_name_two: {
                        required: 'Please Enter New Name'
                    },
                    old_gender_two: {
                        required: 'Please Enter Old Gender'
                    },
                    new_gender_two: {
                        required: 'Please Enter New Gender'
                    },
                    new_name_three: {
                        required: 'Please Enter Name',
                        equalTo: 'Name must be same as New Name'
                    },
                    new_gender_three: {
                        required: 'Please Enter New Gender',
                        equalTo: 'Gender must be same as New Gender'
                    },
                    name_gender_notice_signature: {
                        required: "Please enter signature",
                        equalTo: "Signature must be same as Name" 
                    }, // End of Name Gender Yes

                    name_gender_minor_yes_place: {
                        required: 'Please Enter Place',
                        minlength: "Please enter minimum 4 characters",
                        maxlength: "Please enter minimum 50 characters",
                    },
                    name_gender_minor_yes_date: {
                        required: 'Please Enter Date',
                    },
                    name_gender_minor_yes_notice: {
                        required: 'Please Enter Name',
                        minlength: "Please enter minimum 4 characters",
                        maxlength: "Please enter minimum 50 characters",
                    },
                    name_gender_minor_yes_address: {
                        required: 'Please Enter Address',
                        minlength: "Please enter minimum 4 characters",
                        maxlength: "Please enter minimum 50 characters",
                    },
                    old_minor_gender_three: {
                        required: 'Please Enter Gender of the Minor',
                        minlength: "Please enter minimum 4 characters",
                        maxlength: "Please enter minimum 50 characters",
                    },
                    new_minor_gender_three: {
                        required: 'Please Enter New Gender of Minor',
                        minlength: "Please enter minimum 4 characters",
                        maxlength: "Please enter minimum 50 characters",
                    },
                    old_minor_name_three: {
                        required: 'Please Enter Old Name of the Minor',
                        minlength: "Please enter minimum 4 characters",
                        maxlength: "Please enter minimum 50 characters",
                    },
                    new_minor_name_three: {
                        required: 'Please Enter New Name of the Minor',
                        minlength: "Please enter minimum 4 characters",
                        maxlength: "Please enter minimum 50 characters",
                    },
                    new_minor_name_four: {
                        required: 'Please Enter New Name of the Minor',
                        minlength: "Please enter minimum 4 characters",
                        maxlength: "Please enter minimum 50 characters",
                        equalTo: "Name must be equal to New Name",
                    },
                    new_minor_gender_four: {
                        required: 'Please Enter New Gender of the Minor',
                        minlength: "Please enter minimum 4 characters",
                        maxlength: "Please enter minimum 50 characters",
                        equalTo: "Gender must be equal to New Gender",

                    },
                    name_gender_minor_signature: {
                        required: 'Please Enter Signature',
                        minlength: "Please enter minimum 4 characters",
                        maxlength: "Please enter minimum 50 characters",
                        equalTo: "Signature must be equal to Name",
                    },
                },
                submitHandler: function (form, event) {
                    event.preventDefault();
                    console.log("Submitting form...no-1");
                    // break;
                    // exit;
                    // debugger;
                    $('.loader').show();
                    console.log("Submitting form .... no-2");
                    $.ajax({
                        type: "POST",
                        url: "<?php echo base_url(); ?>applicants_login/insert_change_of_gender",
                        data: new FormData(form),
                        dataType: 'json',
                        contentType: false,
                        processData: false,
                        success: function (json) {
                        console.log(json);
                            if (json['redirect']) {
                                $('.loader').hide();
                                window.location = json['redirect'];
                            } else if (json['error']) {
                                $('.loader').hide();
                                if (json['error']['message']) {
                                    $('#form_gender').prepend('<div class="alert bg-danger"><button type="button" class="close" data-dismiss="alert"><span>×</span><span class="sr-only">Close</span></button><span class="text-semibold"></span>' + json['error']['warning'] + '</div>');
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
                        error: function(error){
                            console.log(error);
                        }
                    });
                }
        });

        document.getElementById("he_she_gender").addEventListener("change", function() {
            var heSheValue = this.value;
            var hisHerSelect = document.getElementById("his_her_gender");
            
            if (heSheValue === "He") {
                hisHerSelect.value = "his";
            } else if (heSheValue === "She") {
                hisHerSelect.value = "her";
            }
        });

        document.getElementById("name_gender_minor_yes_son_daughter").addEventListener("change", function() {
            var sonDaughterValue = this.value;
            var heSheSelect = document.getElementById("he_she_gender");
            var hisHerSelect = document.getElementById("his_her_gender");

            if (sonDaughterValue === "Son's") {
                heSheSelect.value = "He";
                hisHerSelect.value = "his";
            } else if (sonDaughterValue === "Daughter's") {
                heSheSelect.value = "She";
                hisHerSelect.value = "her";
            }
        });

        // Add event listener for son_daughter change
        document.getElementById("son_daughter").addEventListener("change", function() {
            var sonDaughterValue = this.value;
            var heSheSelect = document.getElementById("gender");
            var hisHerSelect = document.getElementById("gender_his_her");

            if (sonDaughterValue === "Son's") {
                heSheSelect.value = "He";
                hisHerSelect.value = "his";
            } else if (sonDaughterValue === "Daughter's") {
                heSheSelect.value = "She";
                hisHerSelect.value = "her";
            }
        });

        // Add event listener for gender change
        document.getElementById("gender").addEventListener("change", function() {
            var heSheValue = this.value;
            var hisHerSelect = document.getElementById("gender_his_her");
            
            if (heSheValue === "He") {
                hisHerSelect.value = "his";
            } else if (heSheValue === "She") {
                hisHerSelect.value = "her";
            }
        });



        
    }); // document ready end

        // display file name code
        $("input[name='doc_files']").change(function (e) {
            $('.files').html($(this).val().split('\\').pop());
        });

        // get state code
        $("#state_id").on('change', function () {
            $(".loader").show();
            var id = $(this).val();
            $.ajax({
                type: "POST",
                url: "<?php echo base_url(); ?>applicants_login/get_districts_list",
                data: {
                    id: id,
                    "<?php echo $this->security->get_csrf_token_name(); ?>": "<?php echo $this->security->get_csrf_hash(); ?>"
                },
                success: function (data) {
                    $('#district_id').html(data);
                    $(".loader").hide();
                }
            });
        });

        // get district code
        $("#district_id").on('change', function () {
            $(".loader").show();
            var id = $(this).val();
            $.ajax({
                type: "POST",
                url: "<?php echo base_url(); ?>applicants_login/get_block_ulb",
                data: {
                    id: id,
                    "<?php echo $this->security->get_csrf_token_name(); ?>": "<?php echo $this->security->get_csrf_hash(); ?>"
                },
                success: function (data) {
                    $('#block_ulb_id').html(data);
                    $(".loader").hide();
                }
            });
        });
        // let isMajorMinorYes = false;
        // let isNameChangeYes = false;
        // let isGovtEmployeeYes = false;



        // // Major Minor Notice Change
        // function major_minor_no() {
        //     let genderNo = document.getElementById("gender_no");
        //     let genderYes = document.getElementById("gender_yes");
        //     let nameGenderChangeYes = document.getElementById("name_gender_change_yes");
        //     genderNo.style.display = "block";
        //     genderYes.style.display = "none";
        //     nameGenderChangeYes.style.display = "none";

        //     isMajorMinorYes = false;
        //     checkBothConditions();
        // }

        // function major_minor_yes() {
        //     let genderNo = document.getElementById("gender_no");
        //     let genderYes = document.getElementById("gender_yes");
        //     genderNo.style.display = "none";
        //     genderYes.style.display = "block";

        //     isMajorMinorYes = true;
        //     checkBothConditions();
        // }

        // // Name Change Notice
        // function name_change_no() {
        //     let genderNo = document.getElementById("gender_no");
        //     let genderYes = document.getElementById("gender_yes");
        //     let nameGenderChangeYes = document.getElementById("name_gender_change_yes");
        //     genderNo.style.display = 'block';
        //     genderYes.style.display = 'none';
        //     nameGenderChangeYes.style.display = 'none';

        //     isNameChangeYes = false;
        //     checkBothConditions();
        // }

        // function name_change_yes() {
        //     let genderNo = document.getElementById("gender_no");
        //     let nameGenderChangeYes = document.getElementById("name_gender_change_yes");

        //     genderNo.style.display = 'none';
        //     nameGenderChangeYes.style.display = 'block';

        //     document.getElementById("gender_yes").style.display = 'none';

        //     isNameChangeYes = true;
        //     checkBothConditions();
        // }

        // function checkBothConditions() {
        //     let genderYes = document.getElementById("gender_yes");
        //     let nameGenderChangeYes = document.getElementById("name_gender_change_yes");
        //     let minorYesNameWithGenderChangeYes = document.getElementById("minor_yes_name_with_gender_change_yes");

        //     if (isMajorMinorYes && isNameChangeYes) {
        //         genderYes.style.display = 'none';
        //         nameGenderChangeYes.style.display = 'none';
        //         minorYesNameWithGenderChangeYes.style.display = 'block';
        //     } else {
        //         minorYesNameWithGenderChangeYes.style.display = 'none';
        //     }
        // }

        // function govt_employee_change(value) {
        //     let genderNo = document.getElementById("gender_no");
        //     let genderYes = document.getElementById("gender_yes");
        //     let minorYesNameWithGenderChangeYes = document.getElementById("minor_yes_name_with_gender_change_yes");
        //     let nameGenderChangeYes = document.getElementById("name_gender_change_yes");
            

        //     if (value === "1") {  // If YES selected
        //         // Reset flags
        //         isMajorMinorYes = false;
        //         isNameChangeYes = true;

        //         // Reset visibility
        //         genderNo.style.display = "block";
        //         genderYes.style.display = "none";
        //         minorYesNameWithGenderChangeYes.style.display = "none";
        //         // nameGenderChangeYes.style.display = "block";
        //     } else {
        //         // If "No", let other flags and conditions decide
        //         checkBothConditions();
        //     }
        // }


        let isMajorMinorYes = false;
        let isNameChangeYes = false;
        let isGovtEmployeeYes = false;

        // Major Minor Notice Change
        function major_minor_no() {
            let genderNo = document.getElementById("gender_no");
            let genderYes = document.getElementById("gender_yes");
            let nameGenderChangeYes = document.getElementById("name_gender_change_yes");

            genderNo.style.display = "block";
            genderYes.style.display = "none";
            nameGenderChangeYes.style.display = "none";

            isMajorMinorYes = false;
            checkBothConditions();
        }

        function major_minor_yes() {
            let genderNo = document.getElementById("gender_no");
            let genderYes = document.getElementById("gender_yes");

            genderNo.style.display = "none";
            genderYes.style.display = "block";

            isMajorMinorYes = true;
            checkBothConditions();
        }

        // Name Change Notice
        function name_change_no() {
            let genderNo = document.getElementById("gender_no");
            let genderYes = document.getElementById("gender_yes");
            let nameGenderChangeYes = document.getElementById("name_gender_change_yes");

            genderNo.style.display = 'block';
            genderYes.style.display = 'none';
            nameGenderChangeYes.style.display = 'none';

            isNameChangeYes = false;
            checkBothConditions();
        }

        function name_change_yes() {
            let genderNo = document.getElementById("gender_no");
            let nameGenderChangeYes = document.getElementById("name_gender_change_yes");
            let genderYes = document.getElementById("gender_yes");

            genderNo.style.display = 'none';
            nameGenderChangeYes.style.display = 'block';
            genderYes.style.display = 'none';

            isNameChangeYes = true;
            checkBothConditions();
        }

        // Govt Employee Change
        function govt_employee_change(value) {
            let genderNo = document.getElementById("gender_no");
            let genderYes = document.getElementById("gender_yes");
            let minorYesNameWithGenderChangeYes = document.getElementById("minor_yes_name_with_gender_change_yes");
            let nameGenderChangeYes = document.getElementById("name_gender_change_yes");

            if (value === "1") {
                isGovtEmployeeYes = true;

                // When Govt Employee is Yes, and Name Change is Yes, show only nameGenderChangeYes
                if (isNameChangeYes) {
                    genderNo.style.display = "none";
                    genderYes.style.display = "none";
                    minorYesNameWithGenderChangeYes.style.display = "none";
                    nameGenderChangeYes.style.display = "block";
                } else {
                    // Reset if name change is not yet selected
                    genderNo.style.display = "block";
                    genderYes.style.display = "none";
                    nameGenderChangeYes.style.display = "none";
                    minorYesNameWithGenderChangeYes.style.display = "none";
                }
            } else {
                isGovtEmployeeYes = false;
                checkBothConditions(); // revert back to standard conditions
            }
        }

        // Condition Checker
        function checkBothConditions() {
            let genderYes = document.getElementById("gender_yes");
            let nameGenderChangeYes = document.getElementById("name_gender_change_yes");
            let minorYesNameWithGenderChangeYes = document.getElementById("minor_yes_name_with_gender_change_yes");
            let genderNo = document.getElementById("gender_no");

            if (isGovtEmployeeYes && isNameChangeYes) {
                // Special case: show only nameGenderChangeYes
                genderNo.style.display = "none";
                genderYes.style.display = "none";
                minorYesNameWithGenderChangeYes.style.display = "none";
                nameGenderChangeYes.style.display = "block";
                return;
            }

            if (isMajorMinorYes && isNameChangeYes) {
                genderYes.style.display = 'none';
                nameGenderChangeYes.style.display = 'none';
                minorYesNameWithGenderChangeYes.style.display = 'block';
                genderNo.style.display = "none";
            } else if (isNameChangeYes) {
                nameGenderChangeYes.style.display = 'block';
                genderYes.style.display = 'none';
                minorYesNameWithGenderChangeYes.style.display = 'none';
                genderNo.style.display = "none";
            } else if (isMajorMinorYes) {
                genderYes.style.display = 'block';
                nameGenderChangeYes.style.display = 'none';
                minorYesNameWithGenderChangeYes.style.display = 'none';
                genderNo.style.display = "none";
            } else {
                // Default fallback
                genderNo.style.display = 'block';
                genderYes.style.display = 'none';
                nameGenderChangeYes.style.display = 'none';
                minorYesNameWithGenderChangeYes.style.display = 'none';
            }
        }


        $(".govt_employee").on('change', function () {
            var val = $("input[name='govt_emp']:checked").val();
            if (val == "1") {
                $("#minor_no").prop('checked', true);
                $("#minor_yes").attr('disabled', true);
                // $("#namechange_yes").attr('disabled', true);
                $("#doc_7").css('display', 'block');
                $("#doc_9").css('display', 'block');
                $("#doc_8").css('display', 'none');
                $("#doc_6").css('display', 'block');
            } else {
                $('#minor_yes').attr('disabled', false);
                $("#namechange_yes").attr('disabled', false);
                $("#doc_6").css('display', 'block');  
                $("#doc_7").css('display', 'none');  
                $("#doc_9").css('display', 'none');  
                $("#doc_8").css('display', 'none'); 
            }
        });


        


        // minor code
        $(".minor").on('change', function () {
            var val = $("input[name='minor']:checked").val();
            if (val == 1) {
                $("#doc_8").css('display', 'block');
            } else {
                $("#doc_8").css('display', 'none');
            }
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