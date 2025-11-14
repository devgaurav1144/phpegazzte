<?php defined('BASEPATH') OR exit('No direct script access allowed'); ?>

<style nonce="8f0882ce3be14f201cadd0eff5726cbd">
    .upload_help {
        margin-top: -5px;
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
</style>
<div class="loader"></div>
<!-- CONTENT -->
<section id="content">
    <div class="page page-forms-validate">
        <!-- bradcome -->
        <ol class="breadcrumb">
            <li><a href="<?php echo base_url(); ?>dashboard">Dashboard</a></li>
            <li class="active">Add Extraordinary Gazette</li>
        </ol>
        <!-- <div class="bg-light lter b-b wrapper-md mb-10">
            <div class="row">
                <div class="col-sm-6 col-xs-12">
                    <h1 class="h3 m-0">Add Extraordinary Gazette</h1>
                </div>
            </div>
        </div> -->
        <!-- row -->
        <div class="row">
            <div class="col-md-12">
                <section class="boxs">
                    <div class="boxs-header">
                        <h3 class="custom-font hb-blue"><strong>Add Extraordinary Gazette</strong></h3>
                    </div>
                    
                    <?php echo form_open('gazette/add', array('name' => "form1", 'role' => "form", 'id' => "dept_gazetee_form", 'enctype' => "multipart/form-data", 'method' => "post")); ?>    
                        <div class="boxs-body">
                            <div class="row">
                                <input type="hidden" name="dept_id" value="<?php echo $dept_id; ?>"/>
                            </div>
                            <div class="row">
                                <div class="form-group col-md-6">
                                    <label for="govt_emp">Payment Type : <span class="asterisk">*</span></label>
                                    <div class="radio">
                                        <label>
                                            <input type="radio" id="payment_free" class="payments" name="payment" checked value="0">
                                            Free
                                        </label>
                                        <label>
                                            <input type="radio" id="payment_paid" class="payments" name="payment" value="1">
                                            Payment of Cost
                                        </label>
                                    </div>
                                </div>
                                <div class="form-group col-md-6">
                                    <label for="email">Subject : <span class="asterisk">*</span></label>
                                    <input type="text" name="subject" id="subject" class="form-control" required value="<?php echo set_value('subject'); ?>" autocomplete="off">
                                    <?php if (form_error('subject')) { ?>
                                        <span class="error"><?php echo form_error('subject'); ?></span>
                                    <?php } ?>
                                </div>
                                <input type="hidden" name="gazette_type_id" value="1"/>
                            </div>
                            <div class="row">
                                <div class="form-group col-md-6">
                                    <label for="username">Notification Type : <span class="asterisk">*</span></label>
                                    <select name="notification_type_id" id="notification_type_id" class="form-control" required>
                                        <option value="">Select Notification Type</option>
                                        <?php if (!empty($notification_types)) { ?>
                                            <?php foreach ($notification_types as $type) { ?>
                                            <option value="<?php echo $type->notification_type; ?>" <?php echo set_select('notification_type_id', $type->notification_type); ?>><?php echo $type->notification_type; ?></option>
                                            <?php } ?>
                                        <?php } ?>
                                    </select>
                                    <?php if (form_error('notification_type_id')) { ?>
                                        <span class="error"><?php echo form_error('notification_type_id'); ?></span>
                                    <?php } ?>
                                </div>
                                <div class="form-group col-md-6">
                                    <label for="email">Order Number : <span class="asterisk">*</span></label>
                                    <input type="text" name="notification_number" id="notification_number" class="form-control alpha_num_dash" required value="<?php echo set_value('notification_number'); ?>" autocomplete="off">
                                    <span class="help-block mb-0">Only (A-Za-z0-9(-,Dot,Comma,&)) these characters are allowed.</span>
                                    <?php if (form_error('notification_number')) { ?>
                                        <span class="error"><?php echo form_error('notification_number'); ?></span>
                                    <?php } ?>
                                </div>
                            </div>
                            <div class="row">
                                <div class="form-group col-md-6">
                                    <label for="email">Keywords : <span class="asterisk">*</span></label>
                                    <textarea name="keywords" id="keywords" class="form-control" placeholder="Use comma for multiple keywords" required="" autocomplete="off"><?php echo set_value('keywords'); ?></textarea>
                                    <?php if (form_error('keywords')) { ?>
                                        <span class="error"><?php echo form_error('keywords'); ?></span>
                                    <?php } ?>
                                </div>
                                <div class="form-group col-md-6">
                                    <label for="email">Gazette (Official Copy): <span class="asterisk">*</span>
                                        <span class="file_icons_add">
                                            <a href="<?php echo base_url() . "assets/pdf/extra-ordinary_dept_word_fomat.pdf";?>" target="_blank"><i class="fa fa-file-word-o"></i></a>
                                        </span>
                                    </label>
                                    <div class="row fileupload-buttonbar clearfix">
                                        <div class="col-lg-10">
                                            <span class="btn btn-raised btn-success fileinput-button">
                                                <i class="glyphicon glyphicon-plus"></i>
                                                <span>Choose File</span>
                                                <input type="file" name="doc_files" required="true"/>
                                            </span>
                                            <span class="files"></span>
                                        </div>
                                    </div>
                                    <label id="doc_files-error" class="error" for="doc_files"></label>
                                    <span class="help-block mb-0 upload_help">Maximum 30 MB allowed.</span>
                                    <div class="clearfix"></div>
                                    <?php if (form_error('doc_files')) { ?>
                                        <span class="error"><?php echo form_error('doc_files'); ?></span>
                                    <?php } ?>
                                </div>
                            </div>
                            <div class="row">
                                <div class="form-group col-md-6 sro">
                                    <label for="email">SRO No : <span class="asterisk">*</span></label>
                                    <div class="radio">
                                        <label>
                                            <input type="radio" id="sro_no_check" class="payments" name="sro_no_check" checked value="0">
                                            NO
                                        </label>
                                        <label>
                                            <input type="radio" id="sro_no_check" class="payments" name="sro_no_check" value="1">
                                            YES
                                        </label>
                                    </div>
                                </div>
                            </div>
                        </div>
                        <div class="boxs-footer text-right bg-tr-black lter dvd dvd-top">
                            <button type="submit" class="btn btn-raised btn-success" id="form4Submit">Save AS PDF</button>
                        </div>
                    <?php echo form_close(); ?>
                </section>
            </div>
        </div>
    </div>
</section>
<!--/ CONTENT -->
<script src="https://code.jquery.com/jquery-3.4.1.min.js" type="text/javascript" nonce="a6b9a780936c8e980939086f618dded2"></script>
<script type="text/javascript" nonce="8f0882ce3be14f201cadd0eff5726cbd">
    $(document).ready(function () {
        $(".payments").on('change', function () {
            var val = $("input[name='payment']:checked").val();
            if (val == 1) {
                $(".sro").hide();
                $("#sro_no_check").val(0);
            } else {
                $(".sro").show();
                $("#sro_no_check").val(0);
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