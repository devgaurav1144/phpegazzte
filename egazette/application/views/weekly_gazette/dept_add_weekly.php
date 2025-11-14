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
            <li><a href="<?php echo base_url(); ?>weekly_gazette">Weekly Gazette</a></li>
            <li class="active">Add Weekly Gazette</li>
        </ol>
        <!-- row -->
        <div class="row">
            <div class="col-md-12">
                <section class="boxs">
                    <div class="boxs-header">
                        <h3 class="custom-font hb-blue"><strong>Add Weekly Gazette</strong></h3>
                    </div>
                    <?php echo form_open('weekly_gazette/add', array('name' => "form1", 'role' => "form", 'id' => "weekly_gazetee_form",  'enctype' => "multipart/form-data", 'method' => "post")); ?>   
                        <div class="boxs-body">
                            <?php if ($this->session->flashdata('success')) { ?>
                                <div class="alert alert-success alert-dismissable"><button aria-hidden="true" data-dismiss="alert" class="close" type="button">×</button><?php echo $this->session->flashdata('success'); ?></div>
                            <?php } ?>
                            <?php if ($this->session->flashdata('error')) { ?>
                                <div class="alert alert-danger alert-dismissable"><button aria-hidden="true" data-dismiss="alert" class="close" type="button">×</button><?php echo $this->session->flashdata('error'); ?></div>
                            <?php } ?>
                            <input type="hidden" name="dept_id" value="<?php echo $dept_id; ?>"/>
                            <?php if (form_error('dept_id')) { ?>
                                <span class="error"><?php echo form_error('dept_id'); ?></span>
                            <?php } ?>
                            <input type="hidden" name="gazette_type_id" value="2"/>
                            <div class="row">
                                <div class="form-group col-md-6">
                                    <label for="email">Part : <span class="asterisk">*</span></label>
                                    <select name="part_id" id="part_id" class="form-control">
                                        <option value="">Select Part</option>
                                        <?php foreach ($parts as $part) { ?>
                                            <option value="<?php echo $part->id; ?>" <?php echo set_select('part_id', $part->id); ?>><?php echo $part->part_name; ?></option>
                                        <?php } ?>
                                        <?php if (form_error('part_id')) { ?>
                                            <span class="error"><?php echo form_error('part_id');  ?></span>
                                        <?php } ?>
                                    </select>
                                </div>
                                <div class="form-group col-md-6">
                                    <label for="email">Section : <span class="asterisk">*</span></label>
                                    <textarea type="text" name="section" id="section" class="form-control" readonly="" value="<?php echo set_value('section'); ?>"></textarea>
                                </div>
                            </div>
                            <div class="row">
                                <div class="form-group col-md-6">
                                    <label for="email">Subject : <span class="asterisk">*</span></label>
                                    <input type="text" name="subject" id="subject" class="form-control" required value="<?php echo set_value('subject'); ?>" autocomplete="off">
                                    <?php if (form_error('subject')) { ?>
                                        <span class="error"><?php echo form_error('subject'); ?></span>
                                    <?php } ?>
                                </div>
                                <div class="form-group col-md-6">
                                    <label for="email">Keywords : <span class="asterisk">*</span></label>
                                    <textarea name="keywords" id="keywords" class="form-control" placeholder="Use comma for multiple keywords" required="" autocomplete="off"><?php echo set_value('keywords'); ?></textarea>
                                    <?php if (form_error('keywords')) { ?>
                                        <span class="error"><?php echo form_error('keywords'); ?></span>
                                    <?php } ?>
                                </div>
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
                                    <label for="email">Order/Notification Number : <span class="asterisk">*</span></label>
                                    <input type="text" name="notification_number" id="notification_number" class="form-control alpha_num_dash" required value="<?php echo set_value('notification_number'); ?>" autocomplete="off">
                                    <span class="help-block mb-0">Only (A-Za-z0-9(-,Dot,Comma, &)) characters are allowed.</span>
                                    <?php if (form_error('notification_number')) { ?>
                                        <span class="error"><?php echo form_error('notification_number'); ?></span>
                                    <?php } ?>
                                </div>
                            </div>
                            <div class="row">
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
                                                <input type="file" name="doc_files" required=""/>
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