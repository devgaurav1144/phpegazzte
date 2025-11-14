<?php defined('BASEPATH') OR exit('No direct script access allowed'); ?>
<style rel="stylesheet" nonce="8f0882ce3be14f201cadd0eff5726cbd">
	.btn_submit {
		top: 5px; padding: 10px;
	}
</style>
<!--  CONTENT  -->
<section id="content">
    <div class="page page-tables-footable">
        <!-- bradcome -->
        <div class="b-b mb-10">
            <div class="row">
                <div class="col-sm-6 col-xs-12">
                    <h1 class="h3 m-0">Gazette Publish</h1>
                    <small class="text-muted"></small>
                </div>
            </div>
        </div>
        <!-- row -->
        <div class="row">
            <div class="col-md-12">
                <section class="boxs">
                    <div class="boxs-body">
                        <?php if ($this->session->flashdata('success')) { ?>
                            <div class="alert alert-success alert-dismissable"><button aria-hidden="true" data-dismiss="alert" class="close" type="button">×</button><?php echo $this->session->flashdata('success'); ?></div>
                        <?php } ?>
                        <?php if ($this->session->flashdata('error')) { ?>
                            <div class="alert alert-success alert-dismissable"><button aria-hidden="true" data-dismiss="alert" class="close" type="button">×</button><?php echo $this->session->flashdata('error'); ?></div>
                        <?php } ?>
                        <div class="row">
                            <?php echo form_open('gazette_publish/update', array('method' => 'post')); ?>    
                                <div class="col-lg-3">
                                    <div class="form-group">
                                        <label class="control-label" for="input-group"></label>
                                        <select name="day_name" id="day_name" class="form-control">
                                            <option value="">Select Day</option>
                                            <option value="Sunday" <?php echo ($details->day_name == 'Sunday') ? 'selected' : ''; ?>>Sunday</option>
                                            <option value="Monday" <?php echo ($details->day_name == 'Monday') ? 'selected' : ''; ?>>Monday</option>
                                            <option value="Tuesday" <?php echo ($details->day_name == 'Tuesday') ? 'selected' : ''; ?>>Tuesday</option>
                                            <option value="Wednesday" <?php echo ($details->day_name == 'Wednesday') ? 'selected' : ''; ?>>Wednesday</option>
                                            <option value="Thursday" <?php echo ($details->day_name == 'Thursday') ? 'selected' : ''; ?>>Thursday</option>
                                            <option value="Friday" <?php echo ($details->day_name == 'Friday') ? 'selected' : ''; ?>>Friday</option>
                                            <option value="Saturday" <?php echo ($details->day_name == 'Saturday') ? 'selected' : ''; ?>>Saturday</option>
                                        </select>
                                        <?php if (form_error('day_name')) { ?>
                                            <span class="error"><?php echo form_error('day_name'); ?></span>
                                        <?php } ?>
                                    </div>
                                </div>
                                <div class="col-lg-2">
                                    <div class="form-group">
                                        <input type="submit" name="submit" value="submit" id="filter" class="btn btn-raised btn-success btn_submit"/>
                                    </div>
                                </div>
                            <?php echo form_close(); ?>
                        </div>     
                    </div>
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