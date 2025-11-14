<?php defined('BASEPATH') OR exit('No direct script access allowed'); ?>
<!-- CONTENT -->
<section id="content">
    <div class="page page-forms-validate">
        <!-- bradcome -->
        <ol class="breadcrumb">
            <li><a href="<?php echo base_url(); ?>applicants_login/dashboard">Dashboard</a></li>
            <li class="active">Change Password</li>
        </ol>
        <!-- row -->
        <div class="row">
            <div class="col-md-12">
                <section class="boxs">
                    <div class="boxs-header">
                        <h3 class="custom-font hb-blue"><strong>Change Password</strong></h3>
                    </div>
                    <?php if ($this->session->flashdata('success')) { ?>
                        <div class="alert alert-success alert-dismissable"><button aria-hidden="true" data-dismiss="alert" class="close" type="button">×</button>
                            <?php echo $this->session->flashdata('success'); ?>
                        </div> 
                    <?php } ?>
                    <?php if ($this->session->flashdata('error')) { ?>
                        <div class="alert alert-danger alert-dismissable"><button aria-hidden="true" data-dismiss="alert" class="close" type="button">×</button>
                            <?php echo $this->session->flashdata('error'); ?>
                        </div>
                    <?php } ?>
                    
                    <?php echo form_open('user/change_password', array('method' => 'post', 'id' => 'form1_change_password', 'role' => 'form', 'name' => 'name1')); ?>
                        <input type="hidden" name="user_id" value="<?php echo $this->session->userdata('user_id'); ?>"/>
                        <div class="boxs-body">
                            <div class="row">
								<div class="form-group col-md-4">
                                    <label for="username">Current Password : </label>
                                    <input type="password" name="old_password" id="old_password" class="form-control" required autocomplete="off" value="<?php echo set_value('old_password'); ?>">
                                    <?php if (form_error('old_password')) { ?>
                                        <span class="error"><?php echo form_error('old_password'); ?></span>
                                    <?php } ?>
                                </div>
                                <div class="form-group col-md-4">
                                    <label for="username">New Password : </label>
                                    <input type="password" name="password" id="password" class="form-control pass_vali" required autocomplete="off" value="<?php echo set_value('password'); ?>">
                                    <?php if (form_error('password')) { ?>
                                        <span class="error"><?php echo form_error('password'); ?></span>
                                    <?php } ?>
                                </div>
                                <div class="form-group col-md-4">
                                    <label for="email">Confirm Password : </label>
                                    <input type="password" name="match_password" id="match_password" class="form-control pass_vali" required autocomplete="off" value="<?php echo set_value('match_password'); ?>">
                                    <?php if (form_error('match_password')) { ?>
                                        <span class="error"><?php echo form_error('match_password'); ?></span>
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