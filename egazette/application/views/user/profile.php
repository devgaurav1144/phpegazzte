<?php defined('BASEPATH') OR exit('No direct script access allowed'); ?>
<!-- CONTENT -->
<section id="content">
    <div class="page page-forms-validate">
        <!-- bradcome -->
        <ol class="breadcrumb">
            <li><a href="<?php echo base_url(); ?>applicants_login/dashboard">Dashboard</a></li>
            <li class="active">Profile Details</li>
        </ol>
        <!-- row -->
        <div class="row">
            <div class="col-md-12">
                <section class="boxs">
                    <!-- boxs header -->
                    <div class="boxs-header">
                        <h3 class="custom-font hb-blue"><strong>User Profile</strong></h3>
                    </div>
                    
                    <?php echo form_open('user/profile', array('method' => 'post', 'id' => 'form2', 'role' => 'form', 'name' => 'form2')); ?>    
                        <div class="boxs-body">
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
                            <div class="row">
                                <div class="form-group col-md-6">
                                    <label for="name">Name: </label>
                                    <input type="text" name="name" id="name" class="form-control" required value="<?php echo $user_data->name; ?>" maxlength="30" autocomplete="off"/>
                                    <?php if (form_error('name')) { ?>
                                        <span class="error"><?php echo form_error('name'); ?></span>
                                    <?php } ?>
                                </div>
                                <div class="form-group col-md-6">
                                    <label for="contactemail">Username: </label>
                                    <input type="text" name="username" id="username" class="form-control" value="<?php echo $user_data->username; ?>" readonly/>
                                </div>
                            </div>
                            <div class="row">
                                <div class="form-group col-md-6">
                                    <label for="website">Designation: </label>
                                    <input type="text" name="designation" id="designation" class="form-control" value="<?php echo $user_data->designation; ?>" readonly/>
                                </div>
                                <div class="form-group col-md-6">
                                    <label for="message">Department: </label>
                                    <input type="text" name="department" id="department" class="form-control" value="<?php echo $user_data->department_name; ?>" readonly/>
                                </div>
                            </div>
                            <div class="row">
                                <div class="form-group col-md-6">
                                    <label for="website">Email: </label>
                                    <input type="email" name="email" id="email" class="form-control" value="<?php echo $user_data->email; ?>" required="" maxlength="96" autocomplete="off"/>
                                    <?php if (form_error('email')) { ?>
                                        <span class="error"><?php echo form_error('email'); ?></span>
                                    <?php } ?>
                                </div>
                                <div class="form-group col-md-6">
                                    <label for="message">Mobile: </label>
                                    <input type="text" class="form-control" name="mobile" id="mobile" value="<?php echo $user_data->mobile; ?>" required="" maxlength="10" autocomplete="off"/>
                                    <?php if (form_error('mobile')) { ?>
                                        <span class="error"><?php echo form_error('mobile'); ?></span>
                                    <?php } ?>
                                </div>
                            </div>
                        </div>
                        <div class="boxs-footer text-right bg-tr-black lter dvd dvd-top">
                            <button type="submit" class="btn btn-raised btn-success" id="form2Submit">Submit</button>
                        </div>
                    <?php echo form_close(); ?>
                </section>
            </div>
        </div>
    </div>
</section>

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