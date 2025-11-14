<?php include_once 'page_initialization.php'; ?>
<!DOCTYPE html>
<html   class="no-js">
    <head>
        <meta charset="utf-8">
        <meta http-equiv="X-UA-Compatible" content="IE=edge">
        <meta name="viewport" content="width=device-width, initial-scale=1">
        <title>Search Gazette  | eGazette | Government of (StateName)</title>
        <meta charset="UTF-8">
        <meta name="viewport" content="initial-scale=1.0, maximum-scale=1.0, user-scalable=no" />
        <?php include_once 'website/include/header-scripts-style.php'; ?>
        <style rel="stylesheet" nonce="8f0882ce3be14f201cadd0eff5726cbd">
            .week_hide {
                display: none;
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
                                <li>Search Gazette</li>
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
                            <!-- <h3 class="head-section pink">Search Gazette</h3> -->
                            <div class="content-part">
                                <div class="row">
                                    <?php echo $uniqueID; ?>
                                    <?php echo form_open('search_gazette_result', array('method' => 'post', 'class' => 'row', 'id' => 'search_form')); ?>
					                    <div class="col-lg-6 col-md-6 col-sm-6 col-xs-12 marg">
                                            <div class="search-gazette">
                                                <div class="form-group">
                                                    <label class="margin-bottom">Gazette Type</label>
                                                    <select class="form-control" id="gType" name="gType" required="" autocomplete="off">
                                                        <option value="">Select</option>
                                                        <?php foreach ($gazette_type as $gazette_type_value) { ?>
                                                            <!-- <option>All</option> -->
                                                            <option value="<?php echo $gazette_type_value->id ?>">
                                                                <?php echo $gazette_type_value->gazette_type ?>
                                                            </option>
                                                        <?php } ?>
                                                    </select>
                                                </div>
                                            </div>
                                        </div>
                                        <div class="col-lg-6 col-md-6 col-sm-6 col-xs-12 marg">
                                            <div class="search-gazette">
                                                <div class="form-group">
                                                    <label class="margin-bottom">Year</label>
                                                    <select class="form-control" id="cYear" name="cYear" autocomplete="off">
                                                        <option value="">Select Year</option>
                                                        <?php foreach ($y1 as $y2) { ?>
                                                            <option value="<?php echo $y2->created_date ?>">
                                                                <?php echo $y2->created_date ?>
                                                            </option>
                                                        <?php } ?>
                                                    </select>
                                                </div>
                                            </div>
                                        </div>

                                        <div class="col-lg-12 col-md-12 col-sm-12 col-xs-12">
                                            <div class="row">
                                                <div class="col-lg-6 col-md-6 col-sm-6 col-xs-12 marg">
                                                    <div class="search-gazette">
                                                        <div class="form-group">
                                                            <label class="margin-bottom">Gazette No</label>
                                                            <input type="text" placeholder="Search by Gazette No" class="form-control" name="gazette_no" id="gazette_no" maxlength="40" autocomplete="off" maxlength="5">
                                                        </div>
                                                    </div>
                                                </div>
                                                <div class="col-lg-6 col-md-6 col-sm-6 col-xs-12 marg" id="keywordID">
                                                    <div class="search-gazette">
                                                        <div class="form-group">
                                                            <label class="margin-bottom">Published Date</label>
                                                            <input class="date-width" autocomplete="off" placeholder="DD-MM-YYYY" type="text" name="published_date" id="published_date">
                                                        </div>
                                                    </div>
                                                </div>
                                            </div>
                                        </div>

                                        <div class="week_hide" id="weeklyBlock">
                                            <div class="col-lg-6 col-md-6 col-sm-6 col-xs-12 marg">
                                                <div class="search-gazette">
                                                    <div class="form-group">
                                                        <label class="margin-bottom">Month</label>
                                                        <select class="form-control" name="monthName" id="monthName" autocomplete="off">
                                                            <option value="">Select Month</option>
                                                            <option value="01"<?php
                                                            $monthName = set_value('monthName');
                                                            if ($monthName == '01') {
                                                                echo "selected";
                                                            }
                                                            ?>>January</option>
                                                            <option value="02"<?php
                                                            $monthName = set_value('monthName');
                                                            if ($monthName == '02') {
                                                                echo "selected";
                                                            }
                                                            ?>>February</option>
                                                            <option value="03"<?php
                                                            $monthName = set_value('monthName');
                                                            if ($monthName == '03') {
                                                                echo "selected";
                                                            }
                                                            ?>>March</option>
                                                            <option value="04"<?php
                                                            $monthName = set_value('monthName');
                                                            if ($monthName == '04') {
                                                                echo "selected";
                                                            }
                                                            ?>>April</option>
                                                            <option value="05"<?php
                                                            $monthName = set_value('monthName');
                                                            if ($monthName == '05') {
                                                                echo "selected";
                                                            }
                                                            ?>>May</option>
                                                            <option value="06"<?php
                                                            $monthName = set_value('monthName');
                                                            if ($monthName == '06') {
                                                                echo "selected";
                                                            }
                                                            ?>>June</option>
                                                            <option value="07"<?php
                                                            $monthName = set_value('monthName');
                                                            if ($monthName == '07') {
                                                                echo "selected";
                                                            }
                                                            ?>>July</option>
                                                            <option value="08"<?php
                                                            $monthName = set_value('monthName');
                                                            if ($monthName == '08') {
                                                                echo "selected";
                                                            }
                                                            ?>>August</option>
                                                            <option value="09"<?php
                                                            $monthName = set_value('monthName');
                                                            if ($monthName == '09') {
                                                                echo "selected";
                                                            }
                                                            ?>>September</option>
                                                            <option value="10"<?php
                                                            $monthName = set_value('monthName');
                                                            if ($monthName == '10') {
                                                                echo "selected";
                                                            }
                                                            ?>>October</option>
                                                            <option value="11"<?php
                                                            $monthName = set_value('monthName');
                                                            if ($monthName == '11') {
                                                                echo "selected";
                                                            }
                                                            ?>>November</option>
                                                            <option value="12"<?php
                                                            $monthName = set_value('monthName');
                                                            if ($monthName == '12') {
                                                                echo "selected";
                                                            }
                                                            ?>>December</option>
                                                        </select>
                                                    </div>
                                                </div>
                                            </div>
                                            <div class="col-lg-6 col-md-6 col-sm-6 col-xs-12 marg">
                                                <div class="search-gazette">
                                                    <div class="form-group">
                                                        <label class="margin-bottom">Week</label>
                                                        <select class="form-control" name="weekTime" id="weekTime" autocomplete="off">
                                                            <option value="">Select Week</option>
                                                            <option value="1" <?php
                                                            $weekTime = set_value('weekTime');
                                                            if ($weekTime == '1') {
                                                                echo "selected";
                                                            }
                                                            ?>>Week 1</option>
                                                            <option value="2"  <?php
                                                            $weekTime = set_value('weekTime');
                                                            if ($weekTime == '2') {
                                                                echo "selected";
                                                            }
                                                            ?>>Week 2</option>
                                                            <option value="3"  <?php
                                                            $weekTime = set_value('weekTime');
                                                            if ($weekTime == '3') {
                                                                echo "selected";
                                                            }
                                                            ?>>Week 3</option>
                                                            <option value="4"  <?php
                                                            $weekTime = set_value('weekTime');
                                                            if ($weekTime == '4') {
                                                                echo "selected";
                                                            }
                                                            ?>>Week 4</option>
                                                        </select>
                                                    </div>
                                                </div>
                                            </div>
                                        </div>
                                        <div class="col-lg-6 col-md-6 col-sm-6 col-xs-12 marg" id="departmentID">
                                            <div class="search-gazette">
                                                <div class="form-group">
                                                    <label class="margin-bottom">Department</label>
                                                    <select class="form-control" id="deptID" name="deptID" autocomplete="off">
                                                        <option value="">Select Dept.</option>
                                                        <?php foreach ($department_type as $department_type_value) { ?>
                                                            <option value="<?= $department_type_value->id ?>">
                                                                <?= $department_type_value->department_name ?>
                                                            </option>
                                                        <?php } ?>
                                                    </select>
                                                </div>
                                            </div>
                                        </div>
                                        <div class="col-lg-6 col-md-6 col-sm-6 col-xs-12 marg" id="notificationID">
                                            <div class="search-gazette">
                                                <div class="form-group">
                                                    <label class="margin-bottom" for="usr">Notification No</label>
                                                    <input type="text" placeholder="Notification/Order/Resolution No" class="form-control" name="notNum" id="notNum" maxlength="40" autocomplete="off">
                                                </div>
                                            </div>
                                        </div>
                                        <div class="col-lg-6 col-md-6 col-sm-6 col-xs-12 marg">
                                            <div class="search-gazette">
                                                <div class="form-group">
                                                    <label class="margin-bottom mgg">From Date</label>
                                                    <input class="date-width"  autocomplete="off" placeholder="DD-MM-YYYY" type="text" name="sByFdate" id="sByFdate">

                                                    <label id="sByFdate-error" class="error" for="sByFdate"></label>
                                                </div>
                                            </div>
                                        </div>
                                        <div class="col-lg-6 col-md-6 col-sm-6 col-xs-12 marg">
                                            <div class="search-gazette">
                                                <div class="form-group">
                                                    <label class="margin-bottom mgg">To Date</label>
                                                    <input class="date-width" autocomplete="off" placeholder="DD-MM-YYYY" type="text" name="sByTdate" id="sByTdate">
                                                </div>
                                            </div>
                                        </div>
                                        <div class="col-lg-12 col-md-12 col-sm-12 col-xs-12">
                                            <div class="row">
                                                <div class="col-lg-6 col-md-6 col-sm-6 col-xs-12 marg" id="subjectID">
                                                    <div class="search-gazette">
                                                        <div class="form-group">
                                                            <label class="margin-bottom">Subject</label>
                                                            <input type="text" placeholder="Search by Subject" class="form-control" name="sName" id="sName" maxlength="40" autocomplete="off">
                                                        </div>
                                                    </div>
                                                </div>
                                                <div class="col-lg-6 col-md-6 col-sm-6 col-xs-12 marg" id="keywordID">
                                                    <div class="search-gazette">
                                                        <div class="form-group">
                                                            <label class="margin-bottom">Keywords</label>
                                                            <input type="text" placeholder="Search by Keywords" class="form-control" name="keywords" id="keywords" maxlength="40" autocomplete="off">
                                                        </div>
                                                    </div>
                                                </div>
                                                <div class="submit-button">
                                                    <input type="submit" class="hvr-shutter-out-horizontal middle-btm">
                                                </div>
                                            </div>
                                        </div>
                                    <?php echo form_close(); ?>
                                </div>
                            </div>
                        </div>
                    </div>
                    <!--Right Sidebar-1 End-->
                    <!--Left Sidebar-1 Start-->
                    <div class="col-lg-3 col-md-3 col-sm-12 col-xs-12 left-side-bar">
                        <div class="row">
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

<script src="<?php echo base_url(); ?>assets/js/jquery.validate.min.js" type="text/javascript" nonce="a6b9a780936c8e980939086f618dded2"></script>
<script type="text/javascript" nonce="a6b9a780936c8e980939086f618dded2">

    document.getElementById("gType").addEventListener('change', function () {
        if (this.value == "2") {
            document.getElementById("weeklyBlock").style.display = "block";
            document.getElementById("departmentID").style.display = "none";
            document.getElementById("notificationID").style.display = "none";
            document.getElementById("subjectID").style.display = "none";
        } else {
            document.getElementById("weeklyBlock").style.display = "none";
            document.getElementById("departmentID").style.display = "block";
            document.getElementById("notificationID").style.display = "block";
            document.getElementById("subjectID").style.display = "block";
        }
    });

    $(document).ready(function(){

        $("#sByFdate").datepicker({
            dateFormat: 'dd-mm-yy',
            autoclose: true,
            todayHighlight: true,
            maxDate: new Date(),
            onSelect: function(selected) {
                $("#sByTdate").datepicker("option","minDate", selected)
            }
        });	

        $("#sByTdate").datepicker({
            dateFormat: 'dd-mm-yy',
            autoclose: true,
            todayHighlight: true,
            maxDate: new Date(),
            onSelect: function(selected) {
                $("#sByFdate").datepicker("option","maxDate", selected)
            }
        });

        $("#published_date").datepicker({
            dateFormat: 'dd-mm-yy',
            autoclose: true,
            todayHighlight: true,
            maxDate: new Date(),
        });

        $("#search_form").validate({
            rules: {
                    gType: {
                        required: true,
                    },
                    cYear: {
                        required: true,
                    },
                    sByFdate:{
                        required: {
                            depends: function(element){
                                if ($('#sByTdate').val() == '') {
                                    return false;
                                } else {
                                    return true;
                                }
                            }
                        }
                    },

                    sByTdate:{
                        required: {
                            depends: function(element){
                                if ($('#sByFdate').val() == '') {
                                    return false;
                                } else {
                                    return true;
                                }
                            }
                        }
                    }
                },
                messages: {
                    gType: {
                       required: 'Please select gazette type',
                    },
                    cYear: {
                       required: 'Please select year',
                    },
                    sByFdate:{
                        required: 'Please select form date',
                    },
                    sByTdate:{
                        required: 'Please select to date'
                    }
                },
                submitHandler: function(form) {
                    form.submit();
                }
        });
    });
</script>