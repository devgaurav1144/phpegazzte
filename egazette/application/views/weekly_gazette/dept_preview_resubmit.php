<?php defined('BASEPATH') OR exit('No direct script access allowed'); ?>
<!-- CONTENT -->
<section id="content">
    <div class="page page-forms-validate">
        <!-- bradcome -->
        <ol class="breadcrumb">
            <li><a href="<?php echo base_url(); ?>dashboard">Dashboard</a></li>
            <li><a href="<?php echo base_url(); ?>weekly_gazette">Weekly Gazette</a></li>
            <li class="active">Resubmit Weekly Gazette</li>
        </ol>
        
        <!-- row -->
        <div class="row">
            <div class="col-md-12">
                <section class="boxs">
                    <div class="boxs-header">
                        <h3 class="custom-font hb-blue"><strong>Resubmit Weekly Gazette</strong></h3>
                    </div>
                    <div class="row">
                        <div class="pdf-container">
                            <embed src="<?php echo base_url() . $details->dept_pdf_file_path; ?>" type="application/pdf" width="100%" height="650px" internalinstanceid="8">
                        </div>
                    </div>
                    <?php
                        $pdftext = file_get_contents($details->dept_pdf_file_path);
                        $num_pag = preg_match_all("/\/Page\W/", $pdftext, $dummy);
                        $window_path = str_replace('/', '\\', $details->dept_pdf_file_path);
                        $pdf_path = substr($details->dept_pdf_file_path, 1);
                    ?>
                    <div class="boxs-footer text-right bg-tr-black lter dvd dvd-top">
                        <?php if (empty($details->dept_signed_pdf_path)) { ?>
                            <div class="checkbox align_left">
                                <label class="checkbox checkbox-custom">
                                    <input type="checkbox" name="declaration" id="declaration" data-parsley-trigger="change" required="" data-parsley-multiple="agree" data-parsley-id="7279"><span class="checkbox-material"></span> <span class="text-info">Verified & Certified that the Contents Uploaded for Gazette Publication have been prepared in accordance to the Guidelines provided in the User Manual & correct in all respects including its format & page setup.</span></label><ul class="parsley-errors-list" id="parsley-id-multiple-agree"></ul>
                            </div>
                            <div class="action_btn">
                                <a href="http://localhost:81/StartPage.aspx?files=<?php echo $pdf_path; ?>&page=<?php echo $num_pag; ?>&gazette_id=<?php echo $details->gazette_id; ?>&type=dept&category=weekly&signed_name=<?php echo $signed_name; ?>&designation=<?php echo $signed_designation; ?>&dept=<?php echo $details->dept_id; ?>&part_id=<?php echo $details->part_id; ?>" class="btn btn-raised btn-info dept_sign_btn" id="sign_pdf_btn">Sign PDF<div class="ripple-container"></div></a>
                            </div>
                        <?php } ?>
                    </div>
                    <?php if (!empty($details->dept_signed_pdf_path)) { ?>
                        <div class="boxs-footer text-right bg-tr-black lter dvd dvd-top">
                            <a href="<?php echo base_url() . $details->dept_signed_pdf_path; ?>" class="btn btn-raised btn-success pull-right" target="_blank">View Signed PDF</a>
                            <?php echo form_open('weekly_gazette/resubmit_dept_gazette/' . $gazette_id, array('name' => "sign_form", 'id' => "sign_form", 'role' => "form", 'enctype' => "multipart/form-data", 'method' => "post")); ?>
                                <input type="hidden" name="gazette_id" value="<?php echo $gazette_id; ?>"/>
                                <input type="hidden" name="dept_id" value="<?php echo $details->dept_id; ?>"/>
                                <input type="hidden" name="part_id" value="<?php echo $details->part_id; ?>"/>
                                <input type="hidden" name="user_id" value="<?php echo $details->user_id; ?>"/>
                                <button type="submit" class="btn btn-raised btn-success" id="form4Submit">Publish</button>
                            <?php echo form_close(); ?>
                        </div>
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