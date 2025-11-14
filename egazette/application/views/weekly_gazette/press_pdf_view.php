<?php defined('BASEPATH') OR exit('No direct script access allowed'); ?>
<!-- CONTENT -->
<section id="content">
    <div class="page page-forms-validate">
        <!-- bradcome -->
        <ol class="breadcrumb">
            <li><a href="<?php echo base_url(); ?>dashboard">Dashboard</a></li>
            <li><a href="<?php echo base_url(); ?>weekly_gazette">Weekly Gazette</a></li>
            <li class="active">Publish Weekly Gazette</li>
        </ol>

        <!-- row -->
        <div class="row">
            <div class="col-md-12">
                <section class="boxs">
                    <div class="boxs-header filter-with-add">
                        <h3 class="custom-font hb-blue"><strong>Publish Weekly Gazette</strong></h3>
                    </div>

					<?php if ($this->session->flashdata('success')) { ?>
                        <div class="alert alert-success alert-dismissable"><button aria-hidden="true" data-dismiss="alert" class="close" type="button">×</button><?php echo $this->session->flashdata('success'); ?></div>
                    <?php } ?>
                    <?php if ($this->session->flashdata('error')) { ?>
                        <div class="alert alert-danger alert-dismissable"><button aria-hidden="true" data-dismiss="alert" class="close" type="button">×</button><?php echo $this->session->flashdata('error'); ?></div>
                    <?php } ?>

                    <div class="boxs-body">
                        <div class="border_data">
                            <div class="row">
                                <div class="form-group col-md-6">
                                    <label for="email">Year : </label>
                                    <?php echo $details->year; ?>
                                </div>
                                <div class="form-group col-md-6">
                                    <label for="email">Week : </label>
                                    <?php echo $details->week; ?>
                                </div>
                            </div>
                            <div class="row">
                                <div class="pdf-container">
                                    <embed src="<?php echo base_url() . $details->signed_pdf_file_path; ?>" type="application/pdf" width="100%" height="650px" internalinstanceid="8">
                                </div>
                            </div>
                        </div>
                    </div>

                    <?php if (!empty($details) && $details->published != 1) { ?>
                        <?php echo form_open('weekly_gazette/press_published', array('name' => "sign_form", 'id' => "sign_form", 'role' => "form", 'enctype' => "multipart/form-data", 'method' => "post")); ?>
                            <input type="hidden" name="gazette_id" value="<?php echo $details->id; ?>"/>
                            <div class="boxs-footer text-right bg-tr-black lter dvd dvd-top">
                                <button type="submit" class="btn btn-raised btn-success" id="form4Submit">Publish</button>
                            </div>
                        <?php echo form_close(); ?>
                    <?php } ?>
                </section>
            </div>
        </div>
    </div>
</section>
<script src="https://code.jquery.com/jquery-1.12.4.min.js" type="text/javascript" nonce="a6b9a780936c8e980939086f618dded2"></script>
<script src="https://cdnjs.cloudflare.com/ajax/libs/pdfobject/2.1.1/pdfobject.min.js" type="text/javascript" nonce="a6b9a780936c8e980939086f618dded2"></script>

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