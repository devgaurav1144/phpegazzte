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
        background: url('<?php echo base_url(); ?>assets/images/loader.gif') 50% 50% no-repeat rgb(249,249,249);
    }
</style>

<script src="https://code.jquery.com/jquery-3.4.1.min.js" type="text/javascript" nonce="a6b9a780936c8e980939086f618dded2"></script>
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
        <div class="bg-light lter b-b wrapper-md mb-10">
            <div class="row">
                <div class="col-sm-6 col-xs-12">
                    <h1 class="h3 m-0">Change of Partnership </h1>
                </div>
            </div>
        </div>
        <!-- row -->
        <div class="row">
            <div class="col-md-12">
                <section class="boxs">
                    <div class="boxs-header">
                        <h3 class="custom-font hb-blush">Add Change of Partnership</h3>
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
                            <div class="form-group col-md-4">
                                <label for="partnership_firm_name"> Name of The Partnership Firm: <span class="asterisk">*</span></label>
                                <input type="text" id="partnership_firm_name" name="partnership_firm_name" placeholder="Enter Partnership Firm Name" class="form-control" maxlength="50" autocomplete="off"/>
                                <?php if (form_error('partnership_firm_name')) { ?>
                                    <span class="error"><?php echo form_error('partnership_firm_name'); ?></span>
                                <?php } ?>
                            </div>
                            <div class="form-group col-md-4">
                                <label for="partnership_registration_no">Registration No. of Partnership Firm : <span class="asterisk">*</span></label>
                                <input type="text" id="partnership_registration_no" name="partnership_registration_no" placeholder="Enter Registration No. Of Partnership Firm" class="form-control" maxlength="50" autocomplete="off"/>
                                <?php if (form_error('partnership_registration_no')) { ?>
                                    <span class="error"><?php echo form_error('partnership_registration_no'); ?></span>
                                <?php } ?>
                            </div>
                            <div class="form-group col-md-4">
                                <label for="state_id">State : <span class="asterisk">*</span></label>
                                <select name="state_id" id="state_id" class="form-control" required="">
                                    <option value="">Select State</option>
                                    <?php if (!empty($states)) { ?>
                                        <?php foreach ($states as $state) { ?>
                                            <option value="<?php echo $state->id; ?>"><?php echo $state->state_name; ?></option>
                                        <?php } ?>
                                    <?php } ?>
                                </select>
                                <?php if (form_error('state_id')) { ?>
                                    <span class="error"><?php echo form_error('state_id'); ?></span>
                                <?php } ?>
                            </div>
                            <div class="form-group col-md-4">
                                <label for="district_id">District : <span class="asterisk">*</span></label>
                                <select name="district_id" id="district_id" class="form-control" required="">
                                    <option value="">Select District</option>
                                    
                                </select>
                                <?php if (form_error('district_id')) { ?>
                                    <span class="error"><?php echo form_error('district_id'); ?></span>
                                <?php } ?>
                            </div>
                            <div class="form-group col-md-4">
                                <label for="police_station_id">Police Station : <span class="asterisk">*</span></label>
                                <select name="police_station_id" id="police_station_id" class="form-control" required="">
                                    <option value="">Select Police Station</option>
                                    
                                </select>
                                <?php if (form_error('police_station_id')) { ?>
                                    <span class="error"><?php echo form_error('police_station_id'); ?></span>
                                <?php } ?>
                            </div>
                            <div class="form-group col-md-4">
                                <label for="address_1">Address 1 : <span class="asterisk">*</span></label>
                                <input type="text" id="address_1" name="address_1" placeholder="Enter Address 1" class="form-control" maxlength="50" autocomplete="off"/>
                                <?php if (form_error('address_1')) { ?>
                                    <span class="error"><?php echo form_error('address_1'); ?></span>
                                <?php } ?>
                            </div>
                        </div>
                        <div class="row">
                            <div class="form-group col-md-4">
                                <label for="address_2">Address 2 : <span class="asterisk">*</span></label>
                                <input type="text" id="address_2" name="address_2" placeholder="Enter Address 2" class="form-control" maxlength="50" autocomplete="off"/>
                                <?php if (form_error('address_2')) { ?>
                                    <span class="error"><?php echo form_error('address_2'); ?></span>
                                <?php } ?>
                            </div>
                            <div class="form-group col-md-4">
                                <label for="pincode">Pincode : <span class="asterisk">*</span></label>
                                <input type="text" id="pincode" name="pincode" placeholder="Enter Pincode" class="form-control" maxlength="6" autocomplete="off"/>
                                <?php if (form_error('pincode')) { ?>
                                    <span class="error"><?php echo form_error('pincode'); ?></span>
                                <?php } ?>
                            </div>
                        </div>
                        <div class="row" > 
                            <?php
                            //print_r($tot_docus);
                            $file_type = "";
                            foreach ($tot_docus as $tot_docu) {
                                $file_type = "image/*"
                                ?>
                                <?php if ($tot_docu->document_name == 'PAN Card of Partners') { ?>
                                    <hr>
                                    <div class="col-md-12" id="app_pan">
                                        <div  class="col-md-6">
                                            <?php } else if ($tot_docu->document_name == 'Aadhaar Card of Partners') { ?>
                                            <div class="col-md-12" id="app_aadhar">
                                                <div  class="col-md-6">
                                                    <?php } else { ?>
                                                    <div class="col-md-6">
                                                    <?php } ?>
                                                    <label for="email"><?php echo trim(ucfirst($tot_docu->document_name)); ?><span class="asterisk">*</span>
                                                        <span class="file_icons_add">
                                                            <?php if ($tot_docu->document_name == 'Notice in Softcopy') {
                                                                $file_type = ".docx";
                                                                ?>
                                                                <i class="fa fa-file-word-o"></i>
                                                            <?php } ?>
                                                        </span>
                                                    </label>
                                                    <div class="row fileupload-buttonbar clearfix">
                                                        <div class="col-lg-6">
                                                            <span class="btn btn-raised btn-success fileinput-button">
                                                                <i class="glyphicon glyphicon-plus"></i>
                                                                <?php if ($tot_docu->document_name == 'Notice in Softcopy') { ?>
                                                                    <span>Choose File</span>
                                                                <?php } else { ?>
                                                                    <span>Choose File</span>
                                                                <?php } ?>
                                                                <?php if ($tot_docu->id == '4' || $tot_docu->id == '5') { ?>
                                                                    <input type="file" name="upload_<?php echo $tot_docu->id; ?>" id="upload_<?php echo $tot_docu->id . '_1'; ?>" accept="<?php echo $file_type; ?>">
                                                                    
                                                                    <input type="hidden" id="img_id_<?php echo $tot_docu->id . '_1'; ?>" name="img_id_<?php echo $tot_docu->id; ?>[]" /><?php } else { ?>
                                                                    <input type="file" name="upload_<?php echo $tot_docu->id; ?>" id="upload_<?php echo $tot_docu->id; ?>" >
                                                                    
                                                                    <input type="hidden" id="img_id_<?php echo $tot_docu->id; ?>" name="img_id_<?php echo $tot_docu->id; ?>[]" />

                                                                <?php } ?>
                                                                <input type="hidden" id="count" name ="count" value="<?php echo $count; ?>" />
                                                            </span>
                                                            <span class="files_<?php echo $tot_docu->id; ?>"></span>
                                                        </div>
                                                        <?php if ($tot_docu->document_name == 'PAN Card of Partners') { ?>
                                                            <!-- <a id="<?php echo $tot_docu->id; ?>" class="btn_add_pan "><i class="fa fa-plus" ></i></a>    -->
                                                    <?php } else if ($tot_docu->document_name == 'Aadhaar Card of Partners') { ?>
                                                            <!-- <a id="<?php echo $tot_docu->id; ?>" class="btn_add_aadhar "><i class="fa fa-plus" ></i></a>   -->
                                                    <?php } ?>
                                                    </div>
                                                    <?php if ($tot_docu->document_name == 'Notice in Softcopy') { ?>
                                                        <span class="help-block mb-0">Maximum 5 MB allowed.</span>
                                                        <span class="error_message_word_<?php echo $tot_docu->id; ?>">Please upload only MS Word (.docx) file.</span>
                                                    <?php } else { ?>
                                                        <span class="help-block mb-0">Maximum 1 MB allowed
                                                            (Only JPEG/PNG/JPG/PDF allowed)</span>
                                                        <span class="error_message_image_<?php echo $tot_docu->id; ?>">Please upload only JPEG/PNG/JPG/PDF image.</span>
                                                        
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

                                                        $('#upload_<?php echo $tot_docu->id; ?>' + '_1').change(function () {
                                                            
                                                            var input_file = $('#upload_<?php echo $tot_docu->id; ?>' + '_1').prop('files')[0];
                                                            var ext = input_file['name'].substring(input_file['name'].lastIndexOf('.') + 1).toLowerCase();

                                                            if (input_file && (ext == "png" || ext == "jpeg" || ext == "jpg" || ext == "pdf")) {
                                                                // Display filename in the span help block
                                                                $('.files_<?php echo $tot_docu->id; ?>').html($(this).val().split('\\').pop());
                                                                $('.error_message_image_<?php echo $tot_docu->id; ?>').hide();

                                                                var id = <?php echo $tot_docu->id; ?>;

                                                                var reader = new FileReader();
                                                                reader.onload = function (e) {

                                                                };
                                                                reader.readAsDataURL(input_file);
                                                                upload_notification_file(input_file, id, '1');
                                                            } else {
                                                                $('.error_message_image_<?php echo $tot_docu->id; ?>').css('color', 'red');
                                                                $('.error_message_image_<?php echo $tot_docu->id; ?>').show();
                                                            }
                                                        });

                                                        $(document).on('change', '#upload_<?php echo $tot_docu->id; ?>' + '_2', function () {
                //                                    $('#upload_<?php echo $tot_docu->id; ?>'+'_2').on('Change',function () {

                                                            var input_file = $('#upload_<?php echo $tot_docu->id; ?>' + '_2').prop('files')[0];
                                                            var ext = input_file['name'].substring(input_file['name'].lastIndexOf('.') + 1).toLowerCase();

                                                            if (input_file && (ext == "png" || ext == "jpeg" || ext == "jpg" || ext == "pdf")) {

                                                                $('.error_message_image_<?php echo $tot_docu->id; ?>').hide();

                                                                var id = <?php echo $tot_docu->id; ?>;

                                                                var reader = new FileReader();
                                                                reader.onload = function (e) {

                                                                };
                                                                reader.readAsDataURL(input_file);
                                                                upload_notification_file(input_file, id, '2');
                                                            } else {
                                                                $('.error_message_image_<?php echo $tot_docu->id; ?>').css('color', 'red');
                                                                $('.error_message_image_<?php echo $tot_docu->id; ?>').show();
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
                                                                    //alert(<?php //echo $tot_docu->id;  ?>);
                                                                    var json = JSON.parse(msg);
                                                                    //console.log(json);
                                                                    if (json['success']) {
                                                                        $('.loader').hide();
                                                                        $(".notif_file").hide();
                                                                        $(".notif_file").html();

                                                                        if (id == 4 || id == 5) {
                                                                            //alert(id);
                                                                            if (sub_id == 1) {
                                                                                $('#img_id_' + id + '_1').val(json['success']);
                                                                            } else {
                                                                                $('#img_id_' + id + '_2').val(json['success']);
                                                                            }
                                                                        } else {
                                                                            $('#img_id_' + id).val(json['success']);
                                                                        }

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
                                        <?php if ($tot_docu->document_name == 'PAN Card of Partners') { ?>
                                                </div>
                                        <?php } else if ($tot_docu->document_name == 'Aadhaar Card of Partners') { ?>
                                            </div>
                                        <?php } else { ?>

                                    <?php } ?>
                                <?php } ?>
                                </div>
                                <div id="error_msg" class="error"></div>
                                <div class="boxs-footer text-right bg-tr-black lter dvd dvd-top">
                                    <button type="submit" class="btn btn-raised btn-success" id="form4Submit">Submit</button>
                                </div>
                                <?php echo form_close(); ?>
                                </section>
                                <input type="hidden" id="pan_text" name ="pan_text" value="2" />
                                <input type="hidden" id="aadhar_text" name ="aadhar_text" value="2" /></inpur>
                            </div>
                        </div>
                    </div>
                </section>

                <script src="https://code.jquery.com/jquery-3.4.1.min.js" type="text/javascript" nonce="a6b9a780936c8e980939086f618dded2"></script>
                <script type="text/javascript" nonce="a6b9a780936c8e980939086f618dded2">

                    $(document).ready(function () {
                        /*
                         * script for image cropping
                         */
                        aadhar_card_load('5');
                        pan_card_load('4');
                        $('.btn_add_pan').click(function () {
                            var id = $(this).attr('id');
                            pan_card_load(id);
                        });


                        $('.btn_add_aadhar').click(function () {
                            var id = $(this).attr('id');
                            aadhar_card_load(id);
                        });

                        //alert('ok');
                        $('#form_pa').validate({
                            // initialize the plugin

                            rules: {
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
                                    maxlength: 50
                                },
                                upload_1: {
                                    required: true
                                },
                                upload_2: {
                                    required: true
                                },
                                upload_3: {
                                    required: true
                                },
                                upload_6: {
                                    required: true
                                },
                                upload_7: {
                                    required: true
                                },
                                partnership_firm_name :{
                                    required: true,
                                    minlength: 5,
                                    maxlength: 30
                                },
                                partnership_registration_no:{
                                    required: true,
                                    minlength: 10,
                                    maxlength: 20
                                },
                                pincode:{
                                    required: true,
                                    minlength: 6,
                                    maxlength: 6 
                                }
                            },
                            messages: {
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
                                    required: "Please upload original partnership deed"
                                },
                                upload_2: {
                                    required: "Please upload deed of reconstition of partnership"
                                },
                                upload_3: {
                                    required: "Please upload IGR certificate"
                                },
                                upload_6: {
                                    required: "Please upload original newspaper advertisement"
                                },
                                upload_7: {
                                    required: "Please upload notice in softcopy"
                                }
                                partnership_firm_name :{
                                    required: "Please select Partnership Firm"
                                    minlength: "Please enter minimum 5 characters"
                                    maxlength: "Please enter maximummum 30 characters"
                                },
                                partnership_registration_no:{
                                    required: 
                                    minlength: "Please enter minimum 5 characters"
                                    maxlength: "Please enter maximum 20 characters"
                                },
                                pincode:{
                                    required: "Please select Pincode"
                                    minlength: "Please enter minimum 6 characters"
                                    maxlength: "Please enter maximum 6 characters"
                                }
                            },
                            submitHandler: function (form) {
                                $('.loader').show();

                                var count = $('#count').val();
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
                                        var json = JSON.parse(msg);
                                        var count = $('#count').val();

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

                    function pan_card_load(id) {

                        var pan_text = $('#pan_text').val();
                        //alert(pan_text);
                        $.ajax({
                            type: "POST",
                            url: "<?php echo base_url(); ?>applicants_login/add_pan",
                            data: {
                                'id': id, 'pan_text': pan_text,
                                '<?php echo $this->security->get_csrf_token_name(); ?>': '<?php echo $this->security->get_csrf_hash(); ?>'
                            },
                            success: function (html) {
                                console.log(html);
                                $('#app_pan').append(html);
                                var pan_text = $('#pan_text').val();
                                var final_incre = pan_text + 1;
                                $('#pan_text').val(final_incre);

                            },
                            error: function (data) {
                                location.reload();
                            }

                        });
                    }


                    function aadhar_card_load(id) {

                        var aadhar_text = $('#aadhar_text').val();
                        //alert(id);
                        $.ajax({
                            type: "POST",
                            url: "<?php echo base_url(); ?>applicants_login/add_aadhar",
                            data: {
                                'id': id, 'aadhar_text': aadhar_text,
                                '<?php echo $this->security->get_csrf_token_name(); ?>': '<?php echo $this->security->get_csrf_hash(); ?>'
                            },
                            success: function (html) {
                                //alert(html);
                                $('#app_aadhar').append(html);
                                var aadhar_text = $('#aadhar_text').val();
                                var final_incre = aadhar_text + 1;
                                $('#aadhar_text').val(final_incre);
                            },
                            error: function (data) {
                                //location.reload();
                            }

                        });
                    }
                    $("#state_id").on('change', function () {
                        $(".loader").show();
                        var id = $(this).val();
                        $.ajax({
                            type: "GET",
                            url: "<?php echo base_url('applicants_login/get_districts_list/') ?>",
                            data: {
                                id: id
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
                            type: "GET",
                            url: "<?php echo base_url('applicants_login/get_police_station_list/') ?>",
                            data: {
                                id: id
                            },
                            success: function (data) {

                                $('#police_station_id').html(data);
                                $(".loader").hide();
                            }
                        });
                    });
                </script>