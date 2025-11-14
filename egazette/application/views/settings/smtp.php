<?php defined('BASEPATH') OR exit('No direct script access allowed'); ?>
<!-- CONTENT -->
<section id="content">
    <div class="page page-forms-validate">
        <!-- bradcome -->
        <div class="bg-light lter b-b wrapper-md mb-10">
            <div class="row">
                <div class="col-sm-6 col-xs-12">
                    <h1 class="h3 m-0">SMTP Settings</h1>
                </div>
            </div>
        </div>
        <!-- row -->
        <div class="row">
            <div class="col-md-12">
                <section class="boxs">
                    <div class="boxs-header">
                        <h3 class="custom-font hb-blush">SMTP Settings</h3>
                    </div>
                    <?php echo form_open('settings/smtp', array('name' => "form1", 'role' => "form", 'id' => "smtp_form", 'enctype' => "multipart/form-data", 'method' => "post")); ?>
                        <div class="boxs-body">
                            <?php if ($this->session->flashdata('success')) { ?>
                                <div class="alert alert-success alert-dismissable"><button aria-hidden="true" data-dismiss="alert" class="close" type="button">×</button><?php echo $this->session->flashdata('success'); ?></div>
                            <?php } ?>
                            <?php if ($this->session->flashdata('error')) { ?>
                                <div class="alert alert-success alert-dismissable"><button aria-hidden="true" data-dismiss="alert" class="close" type="button">×</button><?php echo $this->session->flashdata('error'); ?></div>
                            <?php } ?>

                            <div class="row">
                                <div class="form-group col-md-6">
                                    <label for="username">Host : <span class="asterisk">*</span></label>
                                    <input type="text" name="host" id="host" class="form-control" required value="<?php echo $host; ?>">
                                    <?php if (form_error('host')) { ?>
                                        <span class="error"><?php echo form_error('host'); ?></span>
                                    <?php } ?>
                                </div>

                                <div class="form-group col-md-6">
                                    <label for="username">Protocol : <span class="asterisk">*</span></label>
                                    <select name="protocol" id="protocol" class="form-control" required>
                                        <option value="ssl" <?php if($protocol=='ssl'){echo "selected";}else{ echo set_select('protocol', 'ssl');}  ?>>SSL</option>
                                        <option value="tls" <?php if($protocol=='tls'){echo "selected";}else{ echo set_select('protocol', 'tls');}  ?>>TLS</option>
                                    </select>
                                    <?php if (form_error('protocol')) { ?>
                                        <span class="error"><?php echo form_error('protocol'); ?></span>
                                    <?php } ?>
                                </div>
                            </div>
                            <div class="row">
                                <div class="form-group col-md-6">
                                    <label for="email">Username : <span class="asterisk">*</span></label>
                                    <input type="text" name="username" id="username" class="form-control" required value="<?php echo $username ?>" autocomplete="off">
                                    <?php if (form_error('username')) { ?>
                                        <span class="error"><?php echo form_error('username'); ?></span>
                                    <?php } ?>
                                </div>
                                <div class="form-group col-md-6">
                                    <label for="username">Password : <span class="asterisk">*</span></label>
                                    <input type="password" name="password" id="password" class="form-control" required value="<?php echo $password ?>" autocomplete="off">
                                    <?php if (form_error('password')) { ?>
                                        <span class="error"><?php echo form_error('password'); ?></span>
                                    <?php } ?>
                                </div>
                            </div>
                            <div class="row">
                                <div class="form-group col-md-6">
                                    <label for="email">Port : <span class="asterisk">*</span></label>
                                    <input type="text" name="port" id="port" class="form-control" required value="<?php echo $port ?>" autocomplete="off">
                                    <?php if (form_error('port')) { ?>
                                        <span class="error"><?php echo form_error('port'); ?></span>
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