<?php defined('BASEPATH') OR exit('No direct script access allowed'); ?>
<style rel="stylesheet" nonce="8f0882ce3be14f201cadd0eff5726cbd">
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
    .mandatory {
        color: red;
    }
</style>
<div class="loader"></div>
<!-- CONTENT -->
<section id="content">
    <div class="page page-forms-validate">
        <!-- bradcome -->
        <!-- bradcome -->
        <ol class="breadcrumb">
            <li><a href="<?php echo base_url(); ?>dashboard">Dashboard</a></li>
            <li><a href="<?php echo base_url(); ?>user/users">Nodal Officers</a></li>
            <li class="active">Add Nodal Officer</li>
        </ol>
        <!-- <div class="bg-light lter b-b wrapper-md mb-10">
            <div class="row">
                <div class="col-sm-6 col-xs-12">
                    <h1 class="h3 m-0">Nodal Officer</h1>
                </div>
            </div>
        </div> -->
        <!-- row -->
        <div class="row">
            <div class="col-md-12">
                <section class="boxs">
                    <div class="boxs-header">
                        <h3 class="custom-font hb-blue"><strong>Add Nodal Officer</strong></h3>
                    </div>
                    
                    <?php echo form_open('user/add_nodal_officers', array('name' => 'form1', 'role' => 'form', 'id' => 'add_nodal_officer', 'method' => 'post', 'enctype' => 'multipart/form-data')); ?>
                        <div class="boxs-body">
                            <div class="row">
                                <div class="form-group col-md-6">
                                    <label for="username">Name <span class="mandatory">*</span></label>
                                    <input type="text" name="name" id="name" class="form-control" required autocomplete="off" value="<?php echo set_value('name'); ?>" maxlength="40">
                                    <?php if (form_error('name')) { ?>
                                        <span class="error"><?php echo form_error('name'); ?></span>
                                    <?php } ?>
                                </div>
                                <div class="form-group col-md-6">
                                    <label for="email">Designation <span class="mandatory">*</span></label>
                                    <select name="designation" id="designation" class="form-control" required>
                                        <?php if (!empty($designations)) { ?>
                                            <?php foreach ($designations as $design) { ?>
                                                <option value="<?php echo $design->name; ?>" <?php echo set_select('designation', $design->name); ?>><?php echo $design->name; ?></option>
                                            <?php } ?>
                                        <?php } ?>
                                    </select>
                                    <?php if (form_error('designation')) { ?>
                                        <span class="error"><?php echo form_error('designation'); ?></span>
                                    <?php } ?>
                                </div>
                            </div>
                            <div class="row">
                                <div class="form-group col-md-6">
                                    <label for="username">Username <span class="mandatory">*</span></label>
                                    <input type="text" name="username" id="username" class="form-control" required autocomplete="off" value="<?php echo set_value('username'); ?>" maxlength="10">
                                    <?php if (form_error('username')) { ?>
                                        <span class="error"><?php echo form_error('username'); ?></span>
                                    <?php } ?>
                                </div>
                                <div class="form-group col-md-6">
                                    <label for="email">Email <span class="mandatory">*</span></label>
                                    <input type="text" name="email" id="email" class="form-control" required autocomplete="off" value="<?php echo set_value('email'); ?>" maxlength="96">
                                    <?php if (form_error('email')) { ?>
                                        <span class="error"><?php echo form_error('email'); ?></span>
                                    <?php } ?>
                                </div>
                            </div>
                            <div class="row">
                                <div class="form-group col-md-6">
                                    <label for="username">Department <span class="mandatory">*</span></label>
                                    <select name="dept_id" id="dept_id" class="form-control" required>
                                        <option value="">Select Department</option>
                                        <?php if (!empty($departments)) { ?>
                                            <?php foreach ($departments as $dept) { ?>
                                                <option value="<?php echo $dept->id; ?>" <?php echo set_select('dept_id', $dept->id); ?>><?php echo $dept->department_name; ?></option>
                                            <?php } ?>
                                        <?php } ?>
                                    </select>
                                    <?php if (form_error('dept_id')) { ?>
                                        <span class="error"><?php echo form_error('dept_id'); ?></span>
                                    <?php } ?>
                                </div>
                                <div class="form-group col-md-6">
                                    <label for="email">Mobile <span class="mandatory">*</span></label>
                                    <input type="text" name="mobile" id="mobile" class="form-control" required autocomplete="off" value="<?php echo set_value('mobile'); ?>" maxlength="10">
                                    <?php if (form_error('mobile')) { ?>
                                        <span class="error"><?php echo form_error('mobile'); ?></span>
                                    <?php } ?>
                                </div>
                            </div>
                            <div class="row">
                                <div class="form-group col-md-6">
                                    <label for="email">Employee ID (HRMS) <span class="mandatory">*</span></label>
                                    <input type="text" name="gpf_no" id="gpf_no" class="form-control" required autocomplete="off" value="<?php echo set_value('gpf_no'); ?>" maxlength="6">
                                    <?php if (form_error('gpf_no')) { ?>
                                        <span class="error"><?php echo form_error('gpf_no'); ?></span>
                                    <?php } ?>
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