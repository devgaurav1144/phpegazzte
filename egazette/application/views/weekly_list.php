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
            .no_document {
                text-align: center;
            }
            .footer-section .pagination {
                display: block;
            }
        </style>
    </head>
    <body class="home-body">
        <?php include_once 'website/include/header-menu.php'; ?>
        <section class="inner-banner">
            <div class="container">
                <h1>Weekly Gazettes</h1>
            </div>
        </section>
        <section class="breadcumb-wrapper" id="skip-to-main">
            <div class="container">
                <div class="row">
                    <div class="col-lg-12">
                        <div class="breadcumb">
                            <ul>
                                <li><a href="<?php echo base_url(); ?>" rel="noopener noreferrer">Home</a><span> &gt;</span></li>
                                <li>Weekly Gazettes</li>
                            </ul>
                        </div>
                    </div>
                </div>
            </div>
        </section>
        <section class="main-container">
            <div class="container">
                <div class="row">

                    <!--Middle Content Start-->
                    <div class="col-lg-9 col-md-9 col-sm-12 col-xs-12">
                        <div class="row">
                            <div class="col-lg-12 col-md-12 col-sm-12 col-xs-12 media-section fadeInBottom">
                                <div class="infographic-section">
                                    <h3 class="head-section teal">Weekly Gazettes
                                    <a href="<?php echo base_url(); ?>search_gazette" class="searchlink">
                                        Search
                                    </a>
                                    </h3>
                                    <div class="hole-border">
                                        <table class="table table-bordered table-hover table-striped recent-gazette">
                                            <thead>
                                                <tr>
                                                    <th class="dep-width">Department</th>
                                                    <th class="sub-width">Subject</th>
                                                    <th class="wid">Issue Date</th>
                                                    <th class="wid">Download</th>
                                                </tr>
                                            </thead>
                                            <tbody>
                                                <?php if (!empty($gazette_list)) { ?>
                                                    <?php foreach ($gazette_list as $gazette) { ?>
                                                        <tr>
                                                            <td>Gazette contains multiple departments</td>
                                                            <td>Gazette contains multiple subjects</td>
                                                            <td><?php echo date("Y-m-d", strtotime($gazette->created_at)); ?></td>
                                                            <td><a href="<?php echo $gazette->signed_pdf_file_path; ?>" target="_blank"><span class="download"><img src="<?php echo base_url(); ?>assets/frontend/images/download.png"/></span></a></td>
                                                        </tr>
                                                    <?php } ?>
                                                <?php } else { ?>
                                                    <tr>
                                                        <td colspan="4" class="no_document">In this section no document available at this moment.</td>
                                                    </tr>
                                                <?php } ?>
                                            </tbody>
                                        </table>
                                    </div>
                                </div>
                            </div>
                        </div>
                        <div class="footer-section">
                            <?php echo $links; ?>
                        </div>
                    </div>
                    <!--Middle Content End-->
                    <!--Left Sidebar-1 Start-->
                    <div class="col-lg-3 col-md-3 col-sm-12 col-xs-12 left-side-bar">
                        <div class="row">
                            <div class="col-lg-12 col-md-12 col-sm-12 col-xs-12 media-section fadeInScale">
                                <div class="infographic-section links">
                                    <h3 class="head-section orange">Gazettes on Demand</h3>
                                    <ul id="announcements-vertical-news">
                                        <li><a href="<?php echo base_url(); ?>bills_acts">Bills & Acts</a></li>
                                        <li><a href="<?php echo base_url(); ?>land_acquisition">Land Acquisition</a></li>
                                        <li><a href="<?php echo base_url(); ?>surname_change">Surname Change/Partnership Firm</a></li>
                                        <li><a href="<?php echo base_url(); ?>change_of_partnership_details">Change of Partnership Details</a></li>
                                        <li><a href="<?php echo base_url(); ?>change_name_surname">Change of Name/surname</a></li>
                                        <li><a href="<?php echo base_url(); ?>other_gazette">Others</a></li>
                                    </ul>
                                </div>
                            </div>
                            <div class="col-lg-12 col-md-12 col-sm-12 col-xs-12  media-section important-link-section reveal-left">
                                <div class="infographic-section e-margin links">
                                    <h3 class="head-section green">Important Links</h3>
                                    <ul id="announcements-vertical-news">
                                        <li class="external"><a href="https://govtpress.(StateName).gov.in/" target="_blank">Directorate of Printing, Stationery & Publication</a></li>
                                        <li class="external"><a href="http://ct.(StateName).gov.in/" target="_blank">Commerce & Transport Department</a></li>
                                        <li class="external"><a href="https://www.(StateName).gov.in/" target="_blank">State Portal of (StateName)</a></li>
                                    </ul>
                                </div>
                            </div>
                        </div>
                    </div>
                    <!--Left Sidebar-1 End-->
                </div>
            </div>
        </section>
        <?php include_once 'website/include/footer.php'; ?>
        <?php include_once 'website/include/script.php'; ?>
    </body>
</html>