<?php defined('BASEPATH') OR exit('No direct script access allowed'); ?>
<!-- CONTENT -->
<section id="content">
    <div class="page page-forms-validate">
        <ol class="breadcrumb">
            <li><a href="<?php echo base_url(); ?>dashboard">Dashboard</a></li>
            <li><a href="<?php echo base_url(); ?>user/users">Nodal Officers</a></li>
            <li class="active">Edit Nodal Officer</li>
        </ol>
        <!-- bradcome -->
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
                        <h3 class="custom-font hb-blue"><strong>Edit Nodal Officer</strong></h3>
                    </div>
                    <?php echo form_open('user/edit/' . $user_details->id, array('name' => 'form1', 'role' => 'form', 'id' => 'form1', 'enctype' => 'multipart/form-data', 'method' => 'post')); ?>    
                        <div class="boxs-body">
                            <div class="row">
                                <div class="form-group col-md-6">
                                    <label for="username">Name : </label>
                                    <input type="text" name="name" id="name" class="form-control" required value="<?php echo $user_details->name; ?>">
                                    <?php if (form_error('name')) { ?>
                                        <span class="error"><?php echo form_error('name'); ?></span>
                                    <?php } ?>
                                </div>
                                <div class="form-group col-md-6">
                                    <label for="email">Designation : </label>
                                    <select name="designation" id="designation" class="form-control" required>
                                        <?php if (!empty($designations)) { ?>
                                            <?php foreach ($designations as $design) { ?>
                                                <option value="<?php echo $design->name; ?>" <?php echo ($user_details->designation == $design->name) ? 'selected' : ''; ?>><?php echo $design->name; ?></option>
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
                                    <label for="username">Username : </label>
                                    <input type="text" name="username" id="username" class="form-control" required value="<?php echo $user_details->username; ?>">
                                    <?php if (form_error('username')) { ?>
                                        <span class="error"><?php echo form_error('username'); ?></span>
                                    <?php } ?>
                                </div>
                                <div class="form-group col-md-6">
                                    <label for="email">Email : </label>
                                    <input type="text" name="email" id="email" class="form-control" required value="<?php echo $user_details->email; ?>">
                                    <?php if (form_error('email')) { ?>
                                        <span class="error"><?php echo form_error('email'); ?></span>
                                    <?php } ?>
                                </div>
                            </div>
                            <div class="row">
                                <div class="form-group col-md-6">
                                    <label for="username">Department Name : </label>
                                    <select name="dept_id" id="dept_id" class="form-control" required>
                                        <option value="">Select Department</option>
                                        <?php if (!empty($departments)) { ?>
                                            <?php foreach ($departments as $dept) { ?>
                                                <option value="<?php echo $dept->id; ?>" <?php echo ($dept->id == $user_details->dept_id) ? "selected" : ""; ?>><?php echo $dept->department_name; ?></option>
                                            <?php } ?>
                                        <?php } ?>
                                    </select>
                                    <?php if (form_error('dept_id')) { ?>
                                        <span class="error"><?php echo form_error('dept_id'); ?></span>
                                    <?php } ?>
                                </div>
                                <div class="form-group col-md-6">
                                    <label for="email">Mobile : </label>
                                    <input type="text" name="mobile" id="mobile" class="form-control" required value="<?php echo $user_details->mobile; ?>">
                                    <?php if (form_error('mobile')) { ?>
                                        <span class="error"><?php echo form_error('mobile'); ?></span>
                                    <?php } ?>
                                </div>
                            </div>
                            <div class="row">
                                <div class="form-group col-md-6">
                                    <label for="email">Employee ID (HRMS) : </label>
                                    <input type="text" name="gpf_no" id="gpf_no" class="form-control" required autocomplete="off" value="<?php echo $user_details->gpf_no; ?>">
                                    <?php if (form_error('gpf_no')) { ?>
                                        <span class="error"><?php echo form_error('gpf_no'); ?></span>
                                    <?php } ?>
                                </div>
                                <div class="form-group col-md-6">
                                    <label for="email">Login ID : </label>
                                    <input type="text" name="login_id" id="login_id" class="form-control" autocomplete="off" value="<?php echo $user_details->login_ID; ?>" readonly="">
                                    <?php if (form_error('login_id')) { ?>
                                        <span class="error"><?php echo form_error('login_id'); ?></span>
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