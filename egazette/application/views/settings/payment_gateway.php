<?php defined('BASEPATH') OR exit('No direct script access allowed'); ?>
<!-- CONTENT -->
<section id="content">
    <div class="page page-forms-validate">
        <!-- bradcome -->
        <div class="bg-light lter b-b wrapper-md mb-10">
            <div class="row">
                <div class="col-sm-6 col-xs-12">
                    <h1 class="h3 m-0">Payment Gatway Settings</h1>
                </div>
            </div>
        </div>
        <!-- row -->
        <div class="row">
            <div class="col-md-12">
                <section class="boxs">
                    <div class="boxs-header">
                        <h3 class="custom-font hb-blush">Payment Gateway Settings</h3>
                    </div>
                        <?php echo form_open('settings/payment_gateway', array('name' => "form1", 'role' => "form", 'id' => "smtp_form", 'enctype' => "multipart/form-data", 'method' => "post")); ?>
                        <div class="boxs-body">
                            <div class="row">
                                <div class="form-group col-md-4">
                                    <label for="username">API Key : <span class="asterisk">*</span></label>
                                    <input type="text" name="api_key" id="api_key" class="form-control" required value="<?php if($api_key){echo $api_key;}else{echo set_value('api_key');} ?>">
                                    <?php if (form_error('api_key')) { ?>
                                        <span class="error"><?php echo form_error('api_key'); ?></span>
                                    <?php } ?>
                                </div>
                                <div class="form-group col-md-4">
                                    <label for="username">Token : <span class="asterisk">*</span></label>
                                    <input type="text" name="pay_token" id="pay_token" class="form-control" required value="<?php if($pay_token){echo $pay_token;}else{echo set_value('pay_token');} ?>" autocomplete="off">
                                    <?php if (form_error('pay_token')) { ?>
                                        <span class="error"><?php echo form_error('pay_token'); ?></span>
                                    <?php } ?>
                                </div>
                                <div class="form-group col-md-4">
                                    <label for="email">Salt : <span class="asterisk">*</span></label>
                                    <input type="text" name="pay_salt" id="pay_salt" class="form-control" required value="<?php if($pay_salt){echo $pay_salt;}else{echo set_value('pay_salt');} ?>" autocomplete="off">
                                    <?php if (form_error('pay_salt')) { ?>
                                        <span class="error"><?php echo form_error('pay_salt'); ?></span>
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