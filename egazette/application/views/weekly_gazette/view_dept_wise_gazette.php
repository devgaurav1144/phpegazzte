<?php defined('BASEPATH') OR exit('No direct script access allowed'); ?>
<style rel="stylesheet" nonce="8f0882ce3be14f201cadd0eff5726cbd">
	.dept_name {
		background: #eef4f7; font-weight: bold;
	}
</style>
<!-- CONTENT -->
<section id="content">
    <div class="page page-forms-validate">
        <!-- bradcome -->
        <ol class="breadcrumb">
            <li><a href="<?php echo base_url(); ?>dashboard">Dashboard</a></li>
            <li><a href="<?php echo base_url(); ?>weekly_gazette">Weekly Gazette</a></li>
            <li class="active">View Department Part Gazette Details</li>
        </ol>

        <!-- row -->
        <div class="row">
            <div class="col-md-12">
                <section class="boxs">
                    <div class="boxs-header filter-with-add">
                        <h3 class="custom-font hb-blue"><strong>View Department Part Gazette Details</strong></h3>
                        <div class="">
                            <a href="<?php echo base_url(); ?>weekly_gazette/view_weekly_gazette" class="btn-bg btn btn-warning">View Merge</a>
                        </div>
                    </div>
                    <?php echo form_open('weekly_gazette/press_add', array('class' => "form1", 'name' => "form1", 'method' => "post", 'enctype' => "multipart/form-data")); ?>    
                        <div class="boxs-body">
                            <div class="row">
                                <div class="form-group col-md-6">
                                    <label for="email">Year : </label>
                                    <?php echo $year; ?>
                                </div>
                                <div class="form-group col-md-6">
                                    <label for="email">Week : </label>
                                    <?php echo $week; ?>
                                </div>
                            </div>
                            <?php if (!empty($dept_lists)) {
                                $cntr = 1; ?>
                                    <?php foreach ($dept_lists as $list) { ?>
                                    <div class="panel-group" id="accordion">
                                        <div class="panel panel-default">
                                            <div class="panel-heading">
                                                <h4 class="panel-title">
                                                    <a data-toggle="collapse" data-parent="#accordion" href="#collapse<?php echo $cntr; ?>" class="dept_name"><?php echo $list->department_name; ?></a>
                                                </h4>
                                            </div>
                                            <div id="collapse<?php echo $cntr; ?>" class="panel-collapse collapse <?php echo ($cntr == 1) ? 'in' : ''; ?>">
                                                <div class="panel-body panel-body-no-padding">
<!--                                                    <div class="row" style="background: #eef4f7; font-weight: bold;">
                                                        <div class="form-group col-md-6">
                                                            <label for="username">Department Name : </label>
                                                            <?php echo $list->department_name; ?>
                                                        </div>
                                                        <div class="form-group col-md-6">
                                                            <label for="email">Created Datetime : </label>
                                                            <?php echo get_formatted_datetime($list->created_at); ?>
                                                        </div>
                                                    </div>-->
                                                <div class="border_data">
                                                    <div class="row">
                                                        <div class="form-group col-md-6">
                                                            <label for="email">Part No : </label>
                                                            <?php echo $list->part_name; ?>
                                                        </div>
                                                        <div class="form-group col-md-6">
                                                            <label for="username">Section : </label>
                                                            <?php echo $list->section_name; ?>
                                                        </div>
                                                    </div>
                                                    <div class="row">
                                                        <div class="form-group col-md-6">
                                                            <label for="email">Subject : </label>
                                                            <?php echo $list->subject; ?>
                                                        </div>
                                                        <div class="form-group col-md-6">
                                                            <label for="username">Dept. Gazette (Signed PDF) : </label><br/>
                                                            <a href="<?php echo base_url() . $list->dept_signed_pdf_path; ?>" target="_blank" class="file_icons"><i class="fa fa-file-pdf-o"></i></a>
                                                        </div>
                                                    </div>
                                                    <div class="row">
                                                        <div class="pdf-container">
                                                            <embed src="<?php echo base_url() . $list->dept_signed_pdf_path; ?>" type="application/pdf" width="100%" height="650px" internalinstanceid="8">
                                                        </div>
                                                    </div>
                                                </div>
                                                </div>
                                            </div>
                                        </div>
                                    </div>
                                    <?php $cntr++;
                                } ?>
                            </div>
                            <input type="hidden" name="part_id" value="<?php echo $part_id; ?>"/>
                            <input type="hidden" name="year" value="<?php echo $year; ?>"/>
                            <input type="hidden" name="week" value="<?php echo $week; ?>"/>
                            <?php if ($merged_available) { ?>
                                <div class="boxs-footer text-right bg-tr-black lter dvd dvd-top">
                                    <button type="submit" class="btn btn-raised btn-success" id="form4Submit">Merge</button>
                                </div>
                            <?php } ?>
                        <?php } else { ?>
                            <p class="text-center">No data to display</p>
                        <?php } ?>
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