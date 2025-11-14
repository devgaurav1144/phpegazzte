<?php defined('BASEPATH') OR exit('No direct script access allowed'); ?>
<!-- CONTENT -->
<section id="content">
    <div class="page page-forms-validate">
        <!-- bradcome -->
        <div class="bg-light lter b-b wrapper-md mb-10">
            <div class="row">
                <div class="col-sm-6 col-xs-12">
                    <h1 class="h3 m-0">SMS Settings</h1>
                </div>
            </div>
        </div>
        <!-- row -->
        <div class="row">
            <div class="col-md-12">
                <section class="boxs">
                    <div class="boxs-header">
                        <h3 class="custom-font hb-blush">SMS Settings</h3>
                    </div>
                    <?php echo form_open('settings/sms_gateway', array('name' => "form1", 'role' => "form", 'id' => "sms_form", 'enctype' => "multipart/form-data", 'method' => "post")); ?>
                        <div class="boxs-body">
                            <?php if ($this->session->flashdata('success')) { ?>
                                <div class="alert alert-success alert-dismissable"><button aria-hidden="true" data-dismiss="alert" class="close" type="button">×</button><?php echo $this->session->flashdata('success'); ?></div>
                            <?php } ?>
                            <?php if ($this->session->flashdata('error')) { ?>
                                <div class="alert alert-success alert-dismissable"><button aria-hidden="true" data-dismiss="alert" class="close" type="button">×</button><?php echo $this->session->flashdata('error'); ?></div>
                            <?php } ?>

                            <div class="row">
                                <div class="form-group col-md-4">
                                    <label for="username">API Key : <span class="asterisk">*</span></label>
                                    <input type="text" name="api_key" id="host" class="form-control" required value="<?php if($api_key){echo $api_key;}else{echo set_value('api_key');} ?>">
                                    <?php if (form_error('api_key')) { ?>
                                        <span class="error"><?php echo form_error('api_key'); ?></span>
                                    <?php } ?>
                                </div>
                                <div class="form-group col-md-4">
                                    <label for="username">Endpoint URL : <span class="asterisk">*</span></label>
                                    <input type="url" name="endpoint_url" id="endpoint_url" class="form-control" required value="<?php if($endpoint_url){echo $endpoint_url;}else{ echo set_value('endpoint_url');} ?>" autocomplete="off" placeholder="http://google.com">
                                    <?php if (form_error('endpoint_url')) { ?>
                                        <span class="error"><?php echo form_error('endpoint_url'); ?></span>
                                    <?php } ?>
                                </div>
                                <div class="form-group col-md-4">
                                    <label for="email">Sender ID : <span class="asterisk">*</span></label>
                                    <input type="text" name="sender_id" id="sender_id" class="form-control" required value="<?php if($sender_id){echo $sender_id;}else{ echo set_value('sender_id');} ?>" autocomplete="off">
                                    <?php if (form_error('sender_id')) { ?>
                                        <span class="error"><?php echo form_error('sender_id'); ?></span>
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