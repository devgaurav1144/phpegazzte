<?php defined('BASEPATH') OR exit('No direct script access allowed'); ?>
<!-- CONTENT -->
<section id="content">
    <div class="page page-forms-validate">
        <!-- bradcome -->
        <ol class="breadcrumb">
            <li><a href="<?php echo base_url(); ?>applicants_login/dashboard">Dashboard</a></li>
            <li class="active">Profile</li>
            <!-- <li class="active">Add</li> -->
        </ol>

        <div class="bg-light lter b-b wrapper-md mb-10">
            <div class="row">
                <div class="col-sm-6 col-xs-12">
                    <h1 class="h3 m-0">Profile Details</h1>
                </div>
            </div>
        </div>
        <!-- row -->
        <div class="row">
            <div class="col-md-12">
                <section class="boxs">
                    <!-- boxs header -->
                    <div class="boxs-header">
                        <h3 class="custom-font hb-blush">Applicant Profile</h3>
                    </div>
                    
                    <?php echo form_open('applicants_login/profile', array('method' => 'post', 'id' => 'profile_form', 'role' => 'form', 'name' => 'profile_form')); ?>    
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
<!--                                <div class="col-md-6">
                                    <div class="form-group mobile_number">
                                        <select name="module_id" id="module_id" class="form-control" disabled>
                                            <option value="">Select Module</option>
                                            <?php // if (!empty($modules)) { ?>
                                                <?php // foreach ($modules as $module) { ?>
                                            <option value="<?php // echo $module->id; ?>" <?php // echo set_select('module_id', $module->id); ?> <?php // if($user_data->module_id == $module->id){ echo 'selected'; }else{ echo ''; } ?> ><?php // echo $module->module_name; ?></option>
                                                <?php // } ?>
                                            <?php // } ?>
                                        </select>
                                        <?php // if (form_error('module_id')) { ?>
                                            <span class="error"><?php // echo form_error('module_id'); ?></span>
                                        <?php // } ?>
                                    </div>
                                </div>-->
                                <div class="form-group col-md-6">
                                    <label for="name">Name: </label>
                                    <input type="text" name="name" id="name" class="form-control" required value="<?php echo $user_data->name; ?>" maxlength="30" autocomplete="off"/>
                                    <?php if (form_error('name')) { ?>
                                        <span class="error"><?php echo form_error('name'); ?></span>
                                    <?php } ?>
                                </div>
                            
                                <div class="form-group col-md-6">
                                    <label for="name">Father's Name: </label>
                                    <input type="text" name="f_name" id="f_name" class="form-control" required value="<?php echo $user_data->father_name; ?>" maxlength="30" autocomplete="off"/>
                                    <?php if (form_error('f_name')) { ?>
                                        <span class="error"><?php echo form_error('f_name'); ?></span>
                                    <?php } ?>
                                </div>
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