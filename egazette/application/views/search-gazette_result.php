<?php include_once 'page_initialization.php'; ?>
<!DOCTYPE html>
<html class="no-js">
    <head>
        <meta charset="utf-8">
        <meta http-equiv="X-UA-Compatible" content="IE=edge">
        <meta name="viewport" content="width=device-width, initial-scale=1">
        <title>Search Gazette  | eGazette | Government of (StateName)</title>
        <meta charset="UTF-8">
        <meta name="viewport" content="initial-scale=1.0, maximum-scale=1.0, user-scalable=no" />
        <?php include_once 'website/include/header-scripts-style.php'; ?>
        <style rel="stylesheet" nonce="8f0882ce3be14f201cadd0eff5726cbd">
            .no_document {
                text-align: center;
            }
            .pag-section .pagination {
                display: block;
            }
        </style>
    </head>
    <body class="home-body">
        <?php include_once 'website/include/header-menu.php'; ?>

        <section class="inner-banner">
            <div class="container">
                <h1>Search Gazette</h1>
            </div>
        </section>
        <section class="breadcumb-wrapper" id="skip-to-main">
            <div class="container">
                <div class="row">
                    <div class="col-lg-12">
                        <div class="breadcumb">
                            <ul>
                                <li><a href="<?php echo base_url(); ?>" rel="noopener noreferrer">Home</a><span> &gt;</span></li>
                                <li><a href="<?php echo base_url()?>search_gazette" rel="noopener noreferrer">Search Gazette</a><span> &gt;</span></li>
                                <li>Gazette Result</li>
                            </ul>
                        </div>
                    </div>
                </div>
            </div>
        </section>

        <section class="main-container">
            <div class="container">
                <div class="row">
                    <!--Right Sidebar-1 Start-->
                    <div class="col-lg-9 col-md-9 col-sm-12 col-xs-12 e-margin fadeInBottom">

                        <div class="infographic-section media-section">
                            <!-- <h3 class="head-section pink">Gazette Result</h3> -->
                            <div class="content-part">
                                <div class="row">
                                    <div class="hole-border">
                                        <table class="table table-striped recent-gazette">
                                            <thead>
                                                <tr>
                                                    <th>SL.No</th>
                                                    <th>Department</th>
                                                    <th>Subject</th>
                                                    <th class="wid">Date</th>
                                                    <th>Download</th>
                                                </tr>
                                            </thead>
                                            <tbody>
                                                <?php
                                                $i = 1;
                                                if (count($search_result) > 0) {

                                                    if ($this->my_pagination->total_rows > $this->my_pagination->per_page) {
                                                        $cntr = 1 + ($this->my_pagination->cur_page - 1) * $this->my_pagination->per_page;
                                                    } else {
                                                        $cntr = 1;
                                                    }

                                                    foreach ($search_result as $searchValue) {
                                                        if ($gazetteTypeValue == "weekly") {
                                                            $dept = "Gazette contains multiple departments";
                                                            $subj = "Gazette contains multiple subjects";
                                                        } else {
                                                            $dept = $searchValue->department_name;
                                                            $subj = $searchValue->subject;
                                                        }
                                                        ?>
                                                        <tr>
                                                            <td><?php echo $cntr ?></td>
                                                            <td><?php echo $dept; ?></td>
                                                            <td><?php echo $subj; ?></td>
                                                            <td><?php echo date('d-m-Y', strtotime($searchValue->created_at)) ?></td>
                                                            <td>
                                                                <?php if ($gazetteTypeValue == "weekly") { ?>
                                                                    <a href="<?php echo $searchValue->signed_pdf_file_path ?>"><i class="fa fa-file-pdf-o"></i></a>
                                                                <?php } else { ?>
                                                                    <a href="<?php echo $searchValue->press_signed_pdf_path ?>"><i class="fa fa-file-pdf-o"></i></a>
                                                                <?php } ?>
                                                            </td>
                                                        </tr>
                                                        <?php
                                                        $cntr++;
                                                    }
                                                } else {
                                                    ?>
                                                    <tr>
                                                        <td colspan="4" align="center" class="no_document">In this section no document available at this moment.</td>
                                                    </tr>
                                                <?php } ?>
                                            </tbody>
                                        </table>

                                        

                                    </div>
                                </div>
                            </div>
                        </div>
                        <?php if (isset($links)) { ?>
                                            <?php echo $links; ?>
                                        <?php } ?>
                    </div>
                    <!--Left Sidebar-1 Start-->
                    <div class="col-lg-3 col-md-3 col-sm-12 col-xs-12 left-side-bar">
                        <div class="row">
                            <div class="col-lg-12 col-md-12 col-sm-12 col-xs-12  media-section important-link-section reveal-left">
                                <div class="infographic-section e-margin links">
                                    <h3 class="head-section green">Important Links</h3>
                                    <ul id="announcements-vertical-news">
                                        <li class="external"><a href="https://govtpress.(StateName).gov.in/" target="_blank">Directorate of Printing, Stationery & Publication</a></li>
                                        <li class="external"><a href="http://ct.(StateName).gov.in/" target="_blank">Commerce & Transport Department</a></li>
                                        <li class="external"><a href="https://www.(StateName).gov.in/" target="_blank">National Portal of (StateName)</a></li>
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