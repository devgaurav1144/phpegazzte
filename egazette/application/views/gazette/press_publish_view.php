<?php defined('BASEPATH') OR exit('No direct script access allowed'); ?>
<!-- CONTENT -->
<section id="content">
    <div class="page page-forms-validate">
        <!-- bradcome -->
        <ol class="breadcrumb">
            <li><a href="<?php echo base_url(); ?>dashboard">Dashboard</a></li>
            <li><a href="<?php echo base_url(); ?>gazette">Extraordinary Gazette</a></li>
            <li class="active">Preview Extraordinary Gazette Details</li>
        </ol>
        <!-- <div class="bg-light lter b-b wrapper-md mb-10">
            <div class="row">
                <div class="col-sm-6 col-xs-12">
                    <h1 class="h3 m-0">Publish Gazette</h1>
                </div>
            </div>
        </div> -->
        <!-- row -->
        <div class="row">
            <div class="col-md-12">
                <section class="boxs">
                    <div class="boxs-header">
                        <h3 class="custom-font hb-blue"><strong>Publish Preview Extraordinary Gazette</strong></h3>
                    </div>
                    
                    <?php echo form_open('gazette/press_publish_view/' . $gazette_id, array('role' => "form", 'method' => "post", 'id' => "gazette_form")); ?>    
                        <input type="hidden" name="gazette_id" value="<?php echo $gazette_id; ?>"/>
                        <div class="gazette-main" id="gazette_main_container">
                            <style rel="stylesheet" nonce="8f0882ce3be14f201cadd0eff5726cbd">
                                .gazette-main {
                                    width: 900px;
                                    /*height: 870px;*/
                                    margin: 0 auto;
                                }
                                .header-gazette{
                                    text-align: center;
                                }
                                .header-gazette span img{
                                    width: 100px;
                                }
                                .header-gazette h1 {
                                    font-family: Arial, sans-serif;
                                    font-size: 46px;
                                    margin-bottom: 30px;
                                }
                                .header-gazette h1 span{
                                    position: relative;
                                    top: 36px;
                                    margin-left: 15px;
                                }
                                .header-gazette h1 i {
                                    letter-spacing: 11px;
                                    font-style: normal;
                                }
                                .sub-head span{
                                    display: block;
                                }
                                .sub-head {
                                    font-family: "Times New Roman", Times, serif;
                                    text-align: center;
                                    line-height: 20px;
                                    font-size: 16px;
                                    font-weight: 600;
                                    margin-top: 0px;
                                    margin-bottom: 5px;
                                }
                                .gazette-nav-bar {
                                    width: 100%;
                                    background-color: #ffffff;
                                    padding: 3px 0px;
                                    border-top: 3px solid #000;
                                    border-bottom: 3px solid #000;
                                    position: relative;
                                }
                                .gazette-nav-bar ul{
                                    border-top: 1px solid #000;
                                    border-bottom: 1px solid #000;
                                }
                                .gazette-nav-bar input {
                                    border: none;
                                    padding: 7px 0px;
                                    font-size: 16px;
                                    font-weight: 600;
                                    font-family: "Times New Roman", Times, serif;
                                    letter-spacing: 2.5px;
                                }
                                footer p {
                                    font-family: "Times New Roman", Times, serif;
                                    font-size: 16px;
                                    line-height: 19px;
                                    text-align: center;
                                    margin-top: 5px;
                                }
                                footer p span{
                                    display: block;
                                }
                                .main-para h2{
                                    font-family: Arial, sans-serif;
                                    font-size: 15px;
                                }
                                .main-para p{
                                    font-family: Arial, sans-serif;
                                    font-size: 15px;
                                }
                                .gazette-nav-bar ul li {
                                    display: inline-block;
                                    font-size: 16px;
                                    font-family: "Times New Roman", Times, serif;
                                    font-weight: 600;
                                    width: 100%;
                                    padding: 7px 0px;
                                }
                                .gazette-nav-bar ul {
                                    margin-top: 0px;
                                    margin-bottom: 0px;
                                    padding-left: 0;
                                }
                                .number {
                                    width: 130px;
                                    padding-left: 33px;
                                }
                                .number input{
                                    width: 100%;
                                }
                                .city {
                                    letter-spacing: 2.5px;
                                    width: 98px;
                                    margin-right: 15px;
                                }
                                .day {
                                    width: 347px;
                                }
                                .day input {
                                    width: 100%;
                                }
                                .month {
                                    width: 291px;
                                }
                                .month input{
                                    width: 100%;
                                }
                                .year {
                                    width: 80px;
                                }
                                .year input{
                                    width: 100%;
                                }
                                footer{
                                        margin-top: 30px;
                                }
                                
                                footer .line {
                                    width: 100px;
                                    text-align: center;
                                    margin: 0 auto;
                                    display: block;
                                    border-top: 1px solid #000;
                                }
                            </style>
<!--                            <div class="header-gazette">
                                <img src="<?php echo base_url(); ?>assets/images/header.png">
                                <p class="sub-head"> EXTRAORDINARY <span>PUBLISHED BY AUTHORITY</span></p>
                                <div class="gazette-nav-bar">
                                    <ul>
                                        <li class="number">
                                            No. <?php echo $details->sl_no; ?>, &nbsp; CUTTACK, &nbsp;<?php echo $details->issue_date; ?>, &nbsp;<?php echo $details->sakabda_date; ?>
                                        </li>
                                    </ul>
                                </div>
                            </div>-->
                            <input type="hidden" name="gazette_id" value="<?php echo $gazette_id; ?>"/>
                            <div class="pdf-container">
                                <embed src="<?php echo base_url() . $details->press_pdf_file_path; ?>" type="application/pdf" width="100%" height="700px" internalinstanceid="8">
                            </div>
<!--                            <footer>
                                <div class="line"></div>
                                <p>Uploaded and E-Published by the Directorate of Printing, Stationery and Publication, (StateName), Cuttack-10</p><p></p>
                            </footer>-->
                        </div>
                        <div class="boxs-footer text-right bg-tr-black lter dvd dvd-top">
                            <button type="submit" class="btn btn-raised btn-success" id="form4Submit">Preview</button>
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