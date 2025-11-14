<?php include_once 'page_initialization.php'; ?>
<!DOCTYPE html>
<html   class="no-js">
    <head>
        <meta charset="utf-8">
        <meta http-equiv="X-UA-Compatible" content="IE=edge">
        <meta name="viewport" content="width=device-width, initial-scale=1">
        <title>Search Archival Gazette  | eGazette | Government of (StateName)</title>
        <meta charset="UTF-8">
        <meta name="viewport" content="initial-scale=1.0, maximum-scale=1.0, user-scalable=no" />
        <?php include_once 'website/include/header-scripts-style.php'; ?>
        <style rel="stylesheet" nonce="8f0882ce3be14f201cadd0eff5726cbd">
            .week_hide {
                display: none;
            }
            .searchlink {
                background: #32297e;
            }
            .no_document {
                text-align: center;
            }
            .pag-section .pagination {
                display: block;
            }
            .footer-section .pagination {
                display: block;
            }
            .mt-3{
                margin-top:30px;
            }
             
       
      
            .dept-card2
            {
                display: flex;
                margin: auto auto 5px auto;
                width: 250px;
                height: 65px;
                background: linear-gradient(to right, rgb(20, 40, 119), rgb(5, 117, 230));
                border-radius: 15px;
                transition: all 2s;
                align-items: start;
                justify-content: center;
                font-family: 'Belanosima', sans-serif;
            }
            
            .circle2
            {
                position: absolute;
                margin: auto;
                margin-top: 25px;
                width: 1px;
                height: 1px;
                background-color: rgb(42, 42, 42);
                border-radius: 50%;
                transition: all 0.5s;
                
            }
            .dept-card2:hover 
            {
                box-shadow: 1px 1px 8px #1A4870  
            }
            .dept-card2:hover .circle2
            {
                margin-top: 0px;
                background-color: rgb(42, 42, 42);
                width: 250px;
                height: 65px;
                border-radius: 15px;
                transition: all 0.5s;
            }
            .dept-card2:hover .deptContent
            {
                color: white;
                padding: 5px;
            }
            .deptContent
            {
                margin: auto;
                z-index: 100;
                color: white;
                font-weight: 600;
                letter-spacing: 2px;
                text-align: center;
                font-size: 13px;
            }
        </style>
    </head>
    <body class="home-body">
        <?php include_once 'website/include/header-menu.php'; ?>
        <section class="inner-banner">
            <div class="container">
                <h1>Extraordinary Archival Gazette</h1>
            </div>
        </section>
        <section class="breadcumb-wrapper" id="skip-to-main">
            <div class="container">
                <div class="row">
                    <div class="col-lg-12">
                        <div class="breadcumb">
                            <ul>
                                <li><a href="<?php echo base_url(); ?>" rel="noopener noreferrer">Home</a><span> &gt;</span></li>
                                <li><a href="javascript:void(0)" rel="noopener noreferrer">Archival</a><span> &gt;</span></li>
                                <li>Extraordinary Archival Gazette</li>
                            </ul>
                        </div>
                    </div>
                </div>
            </div>
        </section>
        <section class="main-container">
            <div class="container">
                <!--Clickable Cards for Departments Start-->
                <div class="row">
                    <?php if (!empty($dept)) { ?>
                        <?php foreach ($dept as $department) { ?>
                            <div class="col-lg-3 col-md-4 col-sm-6 col-xs-12 mb-4">
                                <a href="<?php echo site_url('extraordinary_archival_dept/' . $department->id . '/1'); ?>" class="text-decoration-none">
                                    <div class="dept-card2">
                                        <div class="circle2"></div>
                                        <p class="deptContent"><?php echo $department->department_name; ?></p> <!-- Display the department name -->
                                    </div>
                                </a>
                            </div>
                        <?php } ?>
                    <?php } else { ?>
                        <p>No departments available at the moment.</p>
                    <?php } ?>
                </div>
                <!--Clickable Card for Departments End-->
                <div class="row search">
                    <!--Right Sidebar-1 Start-->
                    <div class="col-lg-9 col-md-9 col-sm-12 col-xs-12 e-margin fadeInBottom">
                        <div class="infographic-section media-section">
                            <h3 class="head-section pink">Search Extraordinary Archival Gazette</h3>
                            <div class="content-part">
                                <div class="row">
                                    <?php echo form_open('extraordinary_archival_search', array('method' => 'post', 'class' => 'row', 'id' => 'filter', 'name' => 'filter')); ?>
                                        
                                        <input type="hidden" id="check" name="check" value="1"/>
                                        <div class="col-lg-6 col-md-6 col-sm-6 col-xs-12 marg ext">
                                            <div class="search-gazette">
                                                <div class="form-group">
                                                    <label class="margin-bottom">Department </label>
                                                    <select name="dept_id" id="dept_id" class="form-control" >
                                                        <option value="">Select Department</option>
                                                        <?php if (!empty($dept)) { ?>
                                                            <?php foreach ($dept as $type) { ?>
                                                            <option value="<?php echo $type->id; ?>" <?php echo set_select('dept_id', $type->id); ?>><?php echo $type->department_name; ?></option>
                                                            <?php } ?>
                                                        <?php } ?>
                                                    </select>
                                                </div>
                                            </div>
                                        </div>
                                        <div class="col-lg-6 col-md-6 col-sm-6 col-xs-12 marg week">
                                            <div class="search-gazette">
                                                <div class="form-group">
                                                    <label class="margin-bottom">Week </label>
                                                    <select name="week_id" id="week_id" class="form-control" >
                                                        <option value="">Select Week</option>
                                                        <option value="1" <?php echo set_select('week', 1); ?>>Week 1</option>
                                                        <option value="2" <?php echo set_select('week', 2); ?>>Week 2</option>
                                                        <option value="3" <?php echo set_select('week', 3); ?>>Week 3</option>
                                                        <option value="4" <?php echo set_select('week', 4); ?>>Week 4</option>
                                                    </select>
                                                </div>
                                            </div>
                                        </div>
                                        <div class="col-lg-6 col-md-6 col-sm-6 col-xs-12 marg ext">
                                            <div class="search-gazette">
                                                <div class="form-group">
                                                    <label class="margin-bottom">Notification Type </label>
                                                    <select name="notification_type_id" id="notification_type_id" class="form-control" >
                                                        <option value="">Select Notification Type</option>
                                                        <?php if (!empty($notification_type)) { ?>
                                                            <?php foreach ($notification_type as $type) { ?>
                                                            <option value="<?php echo $type->id; ?>" <?php echo set_select('notification_type_id', $type->id); ?>><?php echo $type->notification_type; ?></option>
                                                            <?php } ?>
                                                        <?php } ?>
                                                    </select>
                                                </div>
                                            </div>
                                        </div>
                                        <div class="col-lg-6 col-md-6 col-sm-6 col-xs-12 marg ext">
                                            <div class="search-gazette">
                                                <div class="form-group">
                                                    <label class="margin-bottom">Subject </label>
                                                    <input name="subject" id="subject" class="form-control" placeholder="Enter Subject" value="<?php echo set_value('subject'); ?>"  autocomplete="off">
                                                </div>
                                            </div>
                                        </div>
                                        <div class="col-lg-6 col-md-6 col-sm-6 col-xs-12 marg">
                                            <div class="search-gazette">
                                                <div class="form-group">
                                                    <label class="margin-bottom">Order/Notification Number </label>
                                                    <input type="text" name="notification_number" id="notification_number" class="form-control alpha_num_dash" placeholder="Enter Order Number/Notification"  value="<?php echo set_value('notification_number'); ?>" autocomplete="off">
                                                </div>
                                            </div>
                                        </div>
                                        <div class="col-lg-6 col-md-6 col-sm-6 col-xs-12 marg">
                                            <div class="search-gazette">
                                                <div class="form-group">
                                                    <label class="margin-bottom">Gazette Number </label>
                                                    <input name="gazette_no" id="gazette_no" class="form-control number_only" placeholder="Enter Gazette Number" value="<?php echo set_value('gazette_no'); ?>"  autocomplete="off">
                                                </div>
                                            </div>
                                        </div>
                                        <div class="col-lg-6 col-md-6 col-sm-6 col-xs-12 marg">
                                            <div class="search-gazette">
                                                <div class="form-group">
                                                    <label class="margin-bottom">Keywords </label>
                                                    <input name="keywords" id="keywords" class="form-control" placeholder="Use comma for multiple keywords" value="<?php echo set_value('keywords'); ?>"  autocomplete="off">
                                                </div>
                                            </div>
                                        </div>
                                        <div class="col-lg-6 col-md-6 col-sm-6 col-xs-12 marg">
                                            <div class="search-gazette">
                                                <div class="form-group">
                                                    <label class="margin-bottom">From Date </label>
                                                    <input name="f_date" id="f_date" class="form-control " placeholder="YYYY-MM-DD" value="<?php echo set_value('f_date'); ?>"  autocomplete="off">
                                                </div>
                                            </div>
                                        </div>
                                        <div class="col-lg-6 col-md-6 col-sm-6 col-xs-12 marg">
                                            <div class="search-gazette">
                                                <div class="form-group">
                                                    <label class="margin-bottom">To Date </label>
                                                    <input name="t_date" id="t_date" class="form-control " placeholder="YYYY-MM-DD" value="<?php echo set_value('t_date'); ?>"  autocomplete="off">
                                                </div>
                                            </div>
                                        </div>
                                        <div class="col-lg-6 col-md-6 col-sm-6 col-xs-12 marg">
                                            <div class="form-group">
                                                <label for="year">Year:</label>
                                                <select name="year" id="year" class="form-control">
                                                    <option value="">Select Year</option>
                                                        <?php
                                                            $currentYear = date('Y');
                                                            for ($year = $currentYear; $year >= 1990; $year--) {
                                                                echo '<option value="' . $year . '" ' . set_select('year', $year) . '>' . $year . '</option>';
                                                            }
                                                        ?>
                                                </select>
                                                <?php if (form_error('year')) { ?>
                                                    <span class="error"><?php echo form_error('year'); ?></span>
                                                <?php } ?>
                                            </div>
                                        </div>

                                        <div class="submit-button">
                                            <input type="submit" class="hvr-shutter-out-horizontal middle-btm">

                                        </div>

                                        
                                    <?php echo form_close(); ?>
                                </div>
                            </div>
                        </div>
                    </div>
                    <!--Left Sidebar-1 End-->
                </div>
                
                <div class="row mt-3">
                    <!--Right Sidebar-1 Start-->
                    <!-- <div class="col-lg-9 col-md-9 col-sm-12 col-xs-12">
                        <div class="row">
                            <div class="col-lg-12 col-md-12 col-sm-12 col-xs-12 media-section fadeInBottom">
                                <div class="infographic-section media-section">
                                    <h3 class="head-section pink">Extraordinary Archival Gazette
                                        <a href="javascript:void(0)" id="search" class="searchlink">
                                            Search
                                        </a>
                                    </h3><br>
                                    <div class="hole-border">
                                        <table class="table table-striped recent-gazette">
                                            <thead>
                                                <tr>
                                                    <th>Department</th>
                                                    <th>Subject</th>
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
                                                            <td><?php echo $gazette->department_name; ?></td>
                                                            <td><?php echo $gazette->subject; ?></td>
                                                            
                                                            <td><?php echo date('d-m-Y', strtotime($gazette->published_date)); ?></td>
                                                            <td>
                                                                <a href="<?php echo site_url('Archival_ControllerEW/view_gazette/' . $gazette->id); ?>" target="blank"><i class="fa fa-file-pdf-o"></i></a>
                                                            </td>
                                                            
                                                        </tr>
                                                        <?php
                                                        $cntr++;
                                                    }
                                                    ?>
                                                    <?php } else { ?>
                                                    <tr>
                                                       <td colspan="5" align="center" class="no_document">In this section no document available at this moment.</td>
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
                            
                    </div> -->
                    
                </div>
            </div>
        </section>
        <?php include_once 'website/include/footer.php'; ?>
        <?php include_once 'website/include/script.php'; ?>
    </body>
</html>

<script type="text/javascript" nonce="a6b9a780936c8e980939086f618dded2">
    var search = 0;
    $(document).ready(function () {
        
        $(".search").hide();
        $(".week").hide();
        
        $("#search").on('click', function () {
            if (search == 0) {
                $(".search").show();
                search = 1;
            } else if (search == 1) {
                $(".search").hide();
                search = 0;
            }
        });
        
        $("#f_date").datepicker({
            dateFormat: 'dd-mm-yy',
            autoclose: true,
            todayHighlight: true,
            maxDate: new Date(),
            onSelect: function(selected) {
                $("#t_date").datepicker("option","minDate", selected)
            }
        });	

        $("#t_date").datepicker({
            dateFormat: 'dd-mm-yy',
            autoclose: true,
            todayHighlight: true,
            maxDate: new Date(),
            onSelect: function(selected) {
                $("#f_date").datepicker("option","maxDate", selected)
            }
        });
    });
    
</script>

