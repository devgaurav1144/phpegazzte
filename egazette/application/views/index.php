<?php defined('BASEPATH') OR exit('No direct script access allowed'); ?>
<!DOCTYPE html>
<html class="no-js">
    <head>
        <meta charset="utf-8">
        <meta http-equiv="X-UA-Compatible" content="IE=edge">
        <meta name="viewport" content="width=device-width, initial-scale=1">
        <title>eGazette | Government of (StateName)</title>
        <meta charset="UTF-8">
        <meta name="viewport" content="initial-scale=1.0, maximum-scale=1.0, user-scalable=no" />
        <?php include_once 'website/include/header-scripts-style.php'; ?>
        <style rel="stylesheet" nonce="8f0882ce3be14f201cadd0eff5726cbd">
            .view_more_message {
                font-family: Muli, sans-serif; font-weight: bold;
            }
            .no_document {
                text-align: center;
            }
        </style>
    </head>
    <body class="home-body">
        <?php include_once 'website/include/header-menu.php'; ?>
        <section class="banner">
            <div class="owl-carousel home-slider">
                <div class="item">
                    <img src="<?php echo base_url(); ?>assets/frontend/images/banner-new.jpg"/>
                </div>
                <!-- <div class="item">
                    <img src="<?php echo base_url(); ?>assets/frontend/images/1st_banner-new.jpg"/>
                </div> -->
                <div class="item">
                    <img src="<?php echo base_url(); ?>assets/frontend/images/2st_banner-new.jpg"/>
                </div>
                <!-- <div class="item">
                    <img src="//<?php echo base_url(); ?>assets/frontend/images/3st_banner-new.jpg"/>
                </div> -->
            </div>
        </section>
        <section class="section">
            <div class="container">
                <div class="row">
                    <div class="col-lg-4 col-md-4 col-sm-4 col-xs-12">
                        <div class="dashboard-box col-1" id="hoverable_text">
                            <h2  data-en="Total Numbers of Visitor's" data-or="ମୋଟ ପର୍ଯ୍ୟାଟକ(ମାନଙ୍କ)ର ସଂଖ୍ୟା">Total Numbers of Visitor's</h2>
                            <span ><?php echo number_to_ks($visitor_counter); ?></span>
                        </div>
                    </div>
                    <div class="col-lg-4 col-md-4 col-sm-4 col-xs-12">
                        <div class="dashboard-box col-2" id="hoverable_text">
                            <h2 data-en="Total Numbers of Extraordinary Gazette(s)" data-or="ମୋଟ ଅବାଜ୍ଞାତ ଗେଜେଟ୍(ଗୁଡ଼ିକ)ର ସଂଖ୍ୟା">Total Numbers of Extraordinary Gazettes</h2>
                            <span id="hoverable_text"><?php echo number_to_ks($total_extraordinary); ?></span>
                        </div>
                    </div>
                    <div class="col-lg-4 col-md-4 col-sm-4 col-xs-12">
                        <div class="dashboard-box col-3" id="hoverable_text">
                            <h2 data-en="Total Numbers of Weekly Gazette(s)" data-or="ମୋଟ ସାପ୍ତାହିକ ଗେଜେଟ୍(ଗୁଡ଼ିକ)ର ସଂଖ୍ୟା">Total Numbers of Weekly Gazettes</h2>
                            <span id="hoverable_text"><?php echo number_to_ks($total_weekly); ?></span>
                        </div>
                    </div>
                </div>
            </div>
        </section>
        <section class="main-container">
            <div class="container">
                <div class="row">
                    <div class="col-lg-4 col-md-4 col-sm-12 col-xs-12 left-side-bar message-wrapper-responsive">
                        <div class="row">
                            <!-- <div class="col-lg-12 col-md-12 col-sm-12 col-xs-12 media-section fadeInScale">
                                <div class="infographic-section links">
                                    <div class="messagediv">
                                        <img src="<?php echo base_url(); ?>assets/frontend/images/cm-(StateName).jfif"/>
                                    </div>
                                    <div class="message-cont">
                                        <h4>Hon'ble CM's Message</h4>
                                        <p>
                                            I am happy that today Govt. Printing Press under Commerce and Transport Dept. is introducing e-Gazette system which would change the conventional method of publication of (StateName) Gazette to eco-friendly, paperless and through electronic mode, making the process more convenient and transparent to both citizens as well as indenting Departments.
                                        </p><br/>
                                        <a href="#" data-toggle="modal" data-target="#cm_message" class="pull-right view_more_message" width="350px">View More</a>
                                    </div>
                                </div>
                            </div> -->
                        </div>
                    </div>
                    <!--Middle Content Start-->
                    <div class="col-lg-8 col-md-8 col-sm-12 col-xs-12 pull-right">
                        <div class="row">
                            <div class="col-lg-12 col-md-12 col-sm-12 col-xs-12 media-section fadeInBottom">
                                <div class="infographic-section">
                                    <h3 class="head-section teal" id="hoverable_text" data-en="Recent Extraordinary Gazettes" data-or="ସମ୍ପ୍ରତି ଅବାଜ୍ଞାତ ଗେଜେଟ୍ଗୁଡ଼ିକ">Recent Extraordinary Gazettes</h3>
                                    <div class="hole-border">
                                      
                                        <table class="table table-bordered table-hover table-striped recent-gazette">
                                            <thead>
                                                <tr>
                                                    <th id="hoverable_text" class="dep-width" data-en="Department" data-or="ବିଭାଗ">Department</th>
                                                    <th id="hoverable_text" class="sub-width" data-en="Subject" data-or="ବିଷୟ">Subject</th>
                                                    <th id="hoverable_text" class="wid" data-en="Issue Date" data-or="ଜାରି ତାରିଖ">Issue Date</th>
                                                    <th id="hoverable_text" class="wid"  data-en="Download" data-or="ଡାଉନଲୋଡ୍">Download</th>
                                                </tr>
                                            </thead>
                                            <tbody>
                                                <?php if (!empty($extraordinary_gazettes['name_surname_data'])) { ?>
                                                    <?php foreach ($extraordinary_gazettes['name_surname_data'] as $gazette) { ?>
                                                        <tr>
                                                            <td id="hoverable_text"><?php echo 'Name - ' . $gazette->name . ", Gaurdian Name - " . $gazette->father_name; ?></td>
                                                            <td id="hoverable_text" data-en="Change of Name/Surname" data-or="ନାମ/ପରିଚୟ ପରିବର୍ତ୍ତନ">Change of Name/Surname</td>
                                                            <td id="hoverable_text"><?php echo date("Y-m-d", strtotime($gazette->modified_at)); ?></td>
                                                            <td><a id="hoverable_text" href="<?php echo $gazette->press_signed_pdf; ?>" target="_blank"><span class="download"><img src="<?php echo base_url(); ?>assets/frontend/images/download.png"/></span></a></td>
                                                        </tr>
                                                    <?php } ?>
                                                <?php } ?>

                                                <?php if (!empty($extraordinary_gazettes['cop_data'])) { ?>
                                                    <?php foreach ($extraordinary_gazettes['cop_data'] as $cop) { ?>
                                                        <tr>
                                                            <td id="hoverable_text"><?php echo 'Name - ' . $cop->name.', Guardian Name - '.$cop->father_name; ?></td>
                                                            <td id="hoverable_text" data-en="Change of Partnership" data-or="ସହଯୋଗ ପରିବର୍ତ୍ତନ">Change of Partnership</td>
                                                            <td id="hoverable_text"><?php echo date("Y-m-d", strtotime($cop->created_at)); ?></td>
                                                            <td><a id="hoverable_text" href="<?php echo $cop->press_signed_pdf; ?>" target="_blank"><span class="download"><img src="<?php echo base_url(); ?>assets/frontend/images/download.png"/></span></a></td>
                                                        </tr>
                                                    <?php } ?>
                                                <?php } ?>

                                                <?php if (empty($extraordinary_gazettes['name_surname_data']) && empty($extraordinary_gazettes['cop_data'])) { ?>
                                                    <tr>
                                                        <td colspan="4" class="no_document" id="hoverable_text" data-en="In this section no document available at this moment." data-or="ଏହି ବିଭାଗରେ ଏହି ମୁହୂର୍ତ୍ତରେ ଡକ୍ୟୁମେଣ୍ଟ ଉପଲବ୍ଧ ନାହିଁ |">In this section no document available at this moment.</td>
                                                    </tr>
                                                <?php } ?>
                                            </tbody>
                                        </table>
                                        <div class="footer-section">
                                            <a href="<?php echo base_url(); ?>extraordinary" id="hoverable_text"  data-en="View All" data-or="ସମସ୍ତ ଦେଖନ୍ତୁ">View All</a>
                                        </div>
                                    </div>
                                </div>
                            </div>
                            <div class="col-lg-12 col-md-12 col-sm-12 col-xs-12 media-section Weekly-down-part fadeInBottom">
                                <div class="infographic-section">
                                    <h3 class="head-section pink" id="hoverable_text" data-en="Recent Weekly Gazettes" data-or="ସମ୍ପ୍ରତି ସାପ୍ତାହିକ ଗେଜେଟ୍ଗୁଡ଼ିକ">Recent Weekly Gazettes</h3>
                                    <div class="hole-border">
                                        <table class="table table-bordered table-hover table-striped recent-gazette">
                                            <thead>
                                                <tr>
                                                    <th class="dep-width" id="hoverable_text" data-en="Department" data-or="ବିଭାଗ">Department</th>
                                                    <th class="sub-width" id="hoverable_text" data-en="Subject" data-or="ବିଷୟ">Subject</th>
                                                    <th class="wid" id="hoverable_text" data-en="Issue Date" data-or="ଜାରି ତାରିଖ">Issue Date</th>
                                                    <th class="wid" id="hoverable_text"  data-en="Download" data-or="ଡାଉନଲୋଡ୍">Download</th>
                                                </tr>
                                            </thead>
                                            <tbody>
                                                <?php if (!empty($weekly_gazettes)) { ?>
                                                    <?php foreach ($weekly_gazettes as $gazette) { ?>
                                                        <?php if ($gazette->signed_pdf_file_path != '') { ?>
                                                            <tr>
                                                                <td id="hoverable_text">Gazette contains multiple departments</td>
                                                                <td id="hoverable_text">Gazette contains multiple subjects</td>
                                                                <td><?php echo date("Y-m-d", strtotime($gazette->created_at)); ?></td>
                                                                <td><a id="hoverable_text" href="<?php echo $gazette->signed_pdf_file_path; ?>" target="_blank"><span class="download"><img src="<?php echo base_url(); ?>assets/frontend/images/download.png"/></span></a></td>
                                                            </tr>
                                                        <?php } ?>
                                                    <?php } ?>
                                                <?php } else { ?>
                                                    <tr>
                                                        <td colspan="4" class="no_document" id="hoverable_text" data-en="In this section no document available at this moment." data-or="ଏହି ବିଭାଗରେ ଏହି ମୁହୂର୍ତ୍ତରେ ଡକ୍ୟୁମେଣ୍ଟ ଉପଲବ୍ଧ ନାହିଁ |">In this section no document available at this moment.</td>
                                                    </tr>
                                                <?php } ?>
                                            </tbody>
                                        </table>
                                        <div class="footer-section">
                                            <a href="<?php echo base_url(); ?>weekly" id="hoverable_text" data-en="View All" data-or="ସମସ୍ତ ଦେଖନ୍ତୁ">View All</a>
                                        </div>
                                    </div>
                                </div>
                            </div>
                        </div>
                    </div>
                    <!--Middle Content End-->
                    <!--Left Sidebar-1 Start-->
                    <div class="col-lg-4 col-md-4 col-sm-12 col-xs-12 left-side-bar pull-left">
                        <div class="row">

                            <!-- <div class="col-lg-12 col-md-12 col-sm-12 col-xs-12 media-section fadeInScale message-wrapper">
                                <div class="infographic-section links">
                                    <div class="messagediv">
                                        <img src="<?php echo base_url(); ?>assets/frontend/images/cm-(StateName)-message-image.png"/>
                                    </div>
                                    <div class="message-cont">
                                        <h4>Hon'ble CM's Message</h4>
                                        <p>
                                            I am happy that today Govt. Printing Press under Commerce and Transport Dept. is introducing e-Gazette system which would change the conventional method of publication of (StateName) Gazette to eco-friendly, paperless and through electronic mode, making the process more convenient and transparent to both citizens as well as indenting Departments. 
                                        </p><br/>
                                        <a href="#" data-toggle="modal" data-target="#cm_message" class="pull-right view_more_message" width="350px">View More</a>
                                    </div>
                                </div>
                            </div> -->
                            <div class="col-lg-12 col-md-12 col-sm-12 col-xs-12 media-section important-link-section fadeInScale">
                                <div class="infographic-section links">
                                    <h3 class="head-section orange" id="hoverable_text" data-en="Gazettes on Demand" data-or="ଆବଶ୍ୟକତା ଅନୁସାରେ ଗେଜେଟ୍">Gazettes on Demand</h3>
                                    <ul id="announcements-vertical-news">
                                        <li><a href="<?php echo base_url(); ?>bills_acts" id="hoverable_text" data-en="Bills & Acts" data-or="ବିଲ୍ସ ଏବଂ ଆଇନରାଜ">Bills & Acts</a></li>
                                        <li><a href="<?php echo base_url(); ?>land_acquisition" id="hoverable_text" data-en="Land Acquisition" data-or="ଭୂମି ଅଧିଗ୍ରହଣ">Land Acquisition</a></li>
                                        <!-- <li><a href="<?php echo base_url(); ?>surname_change">Surname Change/Partnership Firm</a></li> -->
                                        <li><a href="<?php echo base_url(); ?>change_of_partnership_details" id="hoverable_text" data-en="Change of Partnership Details" data-or="ସହଯୋଗ ବିବରଣୀ ପରିବର୍ତ୍ତନ">Change of Partnership Details</a></li>
                                        <li><a href="<?php echo base_url(); ?>change_name_surname" id="hoverable_text" data-en="Change of Name/Surname" data-or="ନାମ/ପରିଚୟ ପରିବର୍ତ୍ତନ">Change of Name/surname</a></li>
                                        <li><a href="<?php echo base_url(); ?>other_gazette" id="hoverable_text" data-en="Others" data-or="ଅନ୍ୟ">Others</a></li>
                                    </ul>
                                </div>
                            </div>
                            <div class="col-lg-12 col-md-12 col-sm-12 col-xs-12  media-section important-link-section reveal-left">
                                <div class="infographic-section e-margin links">
                                    <h3 class="head-section green" id="hoverable_text" data-en="Important Links" data-or="ମହତ୍ତମ ଲିଙ୍କଗୁଡ଼ିକ">Important Links</h3>
                                    <ul id="announcements-vertical-news">
                                        <li class="external"><a href="https://govtpress.(StateName).gov.in/" target="_blank" id="hoverable_text" data-en="Directorate of Printing, Stationery & Publication" data-or="ମୁଦ୍ରଣ, ଲେଖନ ଏବଂ ପ୍ରକାଶନ ନେତୃତ୍ୱ">Directorate of Printing, Stationery & Publication</a></li>
                                        <li class="external"><a href="http://ct.(StateName).gov.in/" target="_blank" id="hoverable_text" data-en="Commerce & Transport Department" data-or="ବାଣିଜ୍ୟ ଓ ପରିବହନ ବିଭାଗ">Commerce & Transport Department</a></li>
                                        <li class="external"><a href="https://www.(StateName).gov.in/" target="_blank" id="hoverable_text" data-en="State Portal of (StateName)" data-or="ଓଡ଼ିଶା ରାଜ୍ୟ ପୋର୍ଟାଲ">State Portal of (StateName)</a></li>
                                    </ul>
                                </div>
                            </div>
                        </div>
                    </div>
                    <!--Left Sidebar-1 End-->
                </div>
            </div>
        </section>
        <!-- PDF Modal -->
        <!-- <div class="modal fade" id="pdfModal" tabindex="-1" role="dialog" aria-labelledby="pdfModalLabel">
            <div class="modal-dialog modal-xl modal-dialog-centered" role="document">
                <div class="modal-content">
                <div class="modal-header bg-primary text-white">
                    <h5 class="modal-title" id="pdfModalLabel">Important Gazette</h5>
                    <button type="button" class="close text-white" data-dismiss="modal" aria-label="Close" style="font-size: 2rem;">
                    &times;
                    </button>
                </div>
                <div class="modal-body p-0">
                    <img src="assets/pdf/eGazette_helpdesk_flex_banner.png" alt="Helpdesk Banner" style="width: 100%; height: auto; display: block;" />
                </div>
                </div>
            </div>
        </div> -->

        <!-- "C:\xampp\htdocs\egazette\assets\pdf\eGazette_helpdesk_flex_banner.pdf" -->
        <?php include_once 'website/include/footer.php'; ?>
        <?php include_once 'website/include/script.php'; ?>
    </body>
</html>

<script>
  $(document).ready(function() {
    $('#pdfModal').modal('show');
  });
</script>
