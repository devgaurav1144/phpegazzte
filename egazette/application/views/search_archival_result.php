<?php include_once 'page_initialization.php'; ?>
<!DOCTYPE html>
<html class="no-js">
    <head>
        <meta charset="utf-8">
        <meta http-equiv="X-UA-Compatible" content="IE=edge">
        <meta name="viewport" content="width=device-width, initial-scale=1">
        <title>Search Archival Gazette  | eGazette | Government of (StateName)</title>
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
            .footer-section .pagination {
                display: block;
            }
        </style>
    </head>
    <body class="home-body">
        <?php include_once 'website/include/header-menu.php'; ?>
        <section class="main-container">
            <div class="container">
                <div class="row">
                    <!--Right Sidebar-1 Start-->
                    <div class="col-lg-9 col-md-9 col-sm-12 col-xs-12">
                        <div class="row">
                            <div class="col-lg-12 col-md-12 col-sm-12 col-xs-12 media-section fadeInBottom">
                                <div class="infographic-section media-section">
                                    <h3 class="head-section teal">Archival Gazette Result
                                        <?php if ($this->session->userdata('check') == 1) { ?>
                                            <a href="<?php echo base_url(); ?>extraordinary_archival" class="searchlink">
                                                Search
                                            </a>
                                        <?php } else { ?>
                                            <a href="<?php echo base_url(); ?>weekly_archival" class="searchlink">
                                                Search
                                            </a>
                                        <?php } ?>
                                            
                                    </h3><br>
                                    <div class="hole-border">
                                        <table class="table table-striped recent-gazette">
                                            <thead>
                                                <tr>
                                                    <?php if ($this->session->userdata('check') == 1) { ?>
                                                        <th>Department</th>
                                                        <th>Subject</th>
                                                    <?php } else if ($this->session->userdata('check') == 2) { ?>
                                                        <th>Week</th>
                                                        <th>Notification Number</th>
                                                        <th>Gazette Number</th>
                                                    <?php } ?>
                                                    <th class="wid">Date</th>
                                                    <th>Download</th>
                                                </tr>
                                            </thead>
                                            <tbody>
                                                <?php if (!empty($gazettes)) { ?>
                                                    <?php
                                                    if ($this->my_pagination->total_rows > $this->my_pagination->per_page) {
                                                        $cntr = 1 + ($this->my_pagination->cur_page - 1) * $this->my_pagination->per_page;
                                                    } else {
                                                        $cntr = 1;
                                                    }
                                                    ?>
                                                    <?php foreach ($gazettes as $gazette) { ?>
                                                        <tr>
                                                            <?php if ($this->session->userdata('check') == 1) { ?>
                                                                <td><?php echo $gazette->department_name; ?></td>
                                                                <td><?php echo $gazette->subject; ?></td>
                                                            <?php } else if ($this->session->userdata('check') == 2) { ?>
                                                                <td><?php echo "Week " . $gazette->week_id ?></td>
                                                                <td><?php echo $gazette->notification_number; ?></td>
                                                                <td><?php echo $gazette->gazette_number; ?></td>
                                                            <?php } ?>
                                                            
                                                            <td><?php echo date('d-m-Y', strtotime($gazette->published_date)); ?></td>
                                                            <td>
                                                                <a href="<?php echo base_url() . $gazette->gazette_file; ?>" target="blank"><i class="fa fa-file-pdf-o"></i></a>
                                                            </td>
                                                            
                                                        </tr>
                                                        <?php
                                                        $cntr++;
                                                    }
                                                    ?>
                                                    <?php } else { ?>
                                                    <tr>
                                                       <td colspan="5" class="center">In this section no document available at this moment.</td>
                                                    </tr>
                                                <?php } ?>
                                            </tbody>
                                        </table>
                                    </div>
                                </div>
                            </div>
                        </div>
                        <?php if (isset($links)) { ?>
                            <div class="footer-section">
                                <?php echo $links; ?>
                            </div>
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