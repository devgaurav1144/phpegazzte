<?php defined('BASEPATH') OR exit('No direct script access allowed'); ?>
<!-- CONTENT -->
<section id="content">
    <div class="page page-forms-validate">
        <!-- bradcome -->
        <ol class="breadcrumb">
            <li><a href="<?php echo base_url(); ?>dashboard">Dashboard</a></li>
            <li><a href="<?php echo base_url(); ?>weekly_gazette">Weekly Gazette</a></li>
            <li class="active">Publish Weekly Gazette Preview</li>
        </ol>

        <!-- row -->
        <div class="row">
            <div class="col-md-12">
                <section class="boxs">
                    <div class="boxs-header">
                        <h3 class="custom-font hb-blue"><strong>Publish Weekly Gazette Preview</strong></h3>
                    </div>
                    <div class="boxs-body">
                        <div class="border_data">
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

                        <div class="boxs-footer text-right bg-tr-black lter dvd dvd-top">
                            <?php if (!empty($pdf_details->signed_pdf_file_path)) { ?>
                                <a href="<?php echo base_url(); ?>weekly_gazette/press_final_esign_pdf_view/<?php echo $final_pdf_id; ?>" class="btn btn-raised btn-success">View Sign PDF</a>
                            <?php } ?>
                            <?php
                                $pdftext = file_get_contents($pdf_file_path);
                                $num_pag = preg_match_all("/\/Page\W/", $pdftext, $dummy);
                                $window_path = str_replace('/', '\\', $pdf_file_path);
                                $pdf_path = substr($pdf_file_path, 1);
                            ?>
                            <?php if (empty($pdf_details->signed_pdf_file_path)) { ?>
                                <a href="http://localhost:81/StartPage.aspx?files=<?php echo $pdf_path; ?>&page=<?php echo $num_pag; ?>&gazette_id=<?php echo $final_pdf_id; ?>&type=press&category=weekly&signed_name=<?php echo $signed_name; ?>&designation=<?php echo $designation; ?>" class="btn btn-raised btn-info dept_sign_btn" id="sign_pdf_btn">Sign PDF<div class="ripple-container"></div></a>
                            <?php } ?>
                        </div>
                    </div>
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