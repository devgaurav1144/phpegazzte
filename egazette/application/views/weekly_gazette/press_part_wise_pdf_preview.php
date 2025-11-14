<?php defined('BASEPATH') OR exit('No direct script access allowed'); ?>
<!-- CONTENT -->
<section id="content">
    <div class="page page-forms-validate">
        <!-- bradcome -->
        <ol class="breadcrumb">
            <li><a href="<?php echo base_url(); ?>dashboard">Dashboard</a></li>
            <li><a href="<?php echo base_url(); ?>weekly_gazette">Weekly Gazette</a></li>
            <li class="active">Merged Weekly Gazette</li>
        </ol>

        <!-- row -->
        <div class="row">
            <div class="col-md-12">
                <section class="boxs">
                    <div class="boxs-header filter-with-add">
                        <h3 class="custom-font hb-blue"><strong>View Merged Part Preview</strong></h3>
                        <div class="">
                            <!-- If admin user, can view the weekly gazette filter for approval -->
                            <?php if ($this->session->userdata('is_admin')) { ?>
                                <a href="<?php echo base_url(); ?>weekly_gazette/view_weekly_gazette" class="btn-bg btn btn-warning">Merge</a>
                            <?php } ?>
                        </div>
                    </div>

                    <div class="boxs-body">
                        <div class="border_data">
                            <div class="row">
                                <div class="form-group col-md-6">
                                    <label for="email">Part No : </label>
                                    <?php echo $part_name; ?>
                                </div>
                                <div class="form-group col-md-6">
                                    <label for="username">Section : </label>
                                    <?php echo $section_name; ?>
                                </div>
                            </div>
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
                            <div class="row">
                                <div class="pdf-container">
                                    <embed src="<?php echo base_url() . $pdf_file_path; ?>" type="application/pdf" width="100%" height="650px" internalinstanceid="8">
                                </div>
                            </div>
                        </div>
                    </div>
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