<?php defined('BASEPATH') OR exit('No direct script access allowed'); ?>
<!--  CONTENT  -->
<section id="content">
    <div class="page page-tables-footable">
        <!-- bradcome -->
        <ol class="breadcrumb">
            <li><a href="<?php echo base_url(); ?>dashboard">Dashboard</a></li>
            <li><a href="<?php echo base_url(); ?>weekly_gazette">Weekly Gazette</a></li>
            <li class="active">Published Weekly Gazette</li>
        </ol>

        <!--Filteration-->

        <?php 
            $weekTime = "";
            $statusType = "";
            $dept = "";
            $year = "";

            if(isset($inputs['weekTime'])){
                $weekTime = $inputs['weekTime'];
            }
            if(isset($inputs['statusType'])){
                $statusType = $inputs['statusType'];
            }
            if(isset($inputs['dept'])){
                $dept = $inputs['dept'];
            }
            if(isset($inputs['year'])){
                $year = $inputs['year'];
            }

            if(!empty($weekTime) || !empty($statusType) || !empty($dept) || !empty($year)){
                $class= "";
            }else{
                $class= "d-none";
            }
        ?>

        <!-- / Filteration -->

        <section class="boxs box_report d-none" id="show_filter_form">
            <div class="boxs-header">
                <h3 class="custom-font hb-blue"><strong>Filter</strong></h3>
            </div>
            <div class="boxs-body">
                <?php echo form_open('weekly_gazette/published_wk_gz', array('method' => 'post', 'id'=>'search_data', 'autocomplete' => 'off')); ?>
                <div class="row">
                    <div class="col-md-3">
                        <div class="form-group">
                            <label for="filter" class="label_txt">Year:</label>
                            <select class="form-control" name="year" id="year">
                                <option value="">Select Year</option>
                                    <?php foreach ($all_year as $years) { ?>
                                        <option value="<?php echo $years->year; ?>"
                                            <?php
                                                if ($year == $years->year) {
                                                    echo "selected";
                                                }
                                            ?>
                                        >
                                            <?php echo $years->year; ?>
                                        </option>
                                    <?php } ?>
                            </select>
                        </div>    
                    </div>

                    <div class="col-md-3">
                        <div class="form-group">
                            <label for="filter" class="label_txt">Week:</label>
                            <select class="form-control" name="weekTime" id="weekTime">
                                <option value="">Select Week</option>
                                    <?php foreach ($all_week as $weeks) { ?>
                                        <option value="<?php echo $weeks->week; ?>"
                                            <?php
                                                if ($weekTime == $weeks->week) {
                                                    echo "selected";
                                                }
                                            ?>
                                        >
                                            <?php echo $weeks->week; ?>
                                        </option>
                                    <?php } ?>
                            </select>
                        </div>    
                    </div>
                    
                    <div class="clearfix"></div>
                    <div class="col-md-3">
                        <?php if (!$this->session->userdata('is_admin')) { ?>
                            <input type="submit" value="Search" name="" class="btn-bg btn btn-success" id="search_btn">
                        <?php } ?>

                        <?php if ($this->session->userdata('is_admin')) { ?>
                            <input type="submit" value="Search" name="" class="btn-bg btn btn-success" id="search_btn_admin">
                        <?php } ?>
                        <input type="button" id="reset_btn" value="Reset" name="" class="btn-bg btn btn-primary">
                        <span id="error"></span>
                    </div>
                    <?php echo form_close(); ?>
                </div>                            
            </div>
        </section>

        <section class="boxs">
            <div class="boxs-header filter-with-add">
                <?php if ($this->session->userdata('is_admin')) { ?>
                        <h3 class="custom-font hb-blue"><strong>Govt. Press Published Weekly Gazette</strong></h3>
                <?php } else { ?>
                    <h3 class="custom-font hb-blue"><strong>Published Weekly Gazette</strong></h3>
                <?php } ?>
                <div class="">
                    <button class="btn btn-success btn-raised btn-fab btn-fab-mini btn-round" id="show_filter" title="Filter"><i class="fa fa-filter"></i></button>
                </div>
            </div>
            <div class="boxs-body">
                <?php if ($this->session->flashdata('success')) { ?>
                    <div class="alert alert-success alert-dismissable"><button aria-hidden="true" data-dismiss="alert" class="close" type="button">×</button><?php echo $this->session->flashdata('success'); ?></div>
                <?php } ?>
                <?php if ($this->session->flashdata('error')) { ?>
                    <div class="alert alert-danger alert-dismissable"><button aria-hidden="true" data-dismiss="alert" class="close" type="button">×</button><?php echo $this->session->flashdata('error'); ?></div>
                <?php } ?>
                
                <table id="searchTextResults" data-filter="#filter" data-page-size="5" class="footable table table-custom">
                    <thead>
                        <tr>
                            <th>Sl No</th>
                            <th>Date</th>
                            <th>Year</th>
                            <th>Week</th>
                            <th>Press PDF</th>
                        </tr>
                    </thead>
                    <tbody>
                        <?php if (!empty($gazettes_published)) { ?>
                            <?php
                            if ($this->my_pagination->total_rows > $this->my_pagination->per_page) {
                                $cntr = 1 + ($this->my_pagination->cur_page - 1) * $this->my_pagination->per_page;
                            } else {
                                $cntr = 1;
                            }
                            ?>
                            <?php foreach ($gazettes_published as $gazette) { ?>
                                <tr>
                                    <td><?php echo $cntr; ?></td>
                                    <td><?php echo get_formatted_datetime($gazette->created_at); ?></td>
                                    <td><?php echo $gazette->year; ?></td>
                                    <td><?php echo $gazette->week; ?></td>
                                    <td>
                                        <a href="<?php echo base_url() . $gazette->signed_pdf_file_path; ?>" target="_blank">
                                            <i class="fa fa-file-pdf-o"></i> View
                                        </a>
                                    </td>
                                </tr>
                                <?php $cntr++;
                            }
                            ?>
                            <?php } else { ?>
                                <tr>
                                    <td colspan="8" class="center">No data to display</td>
                                </tr>
                            <?php } ?>
                    </tbody>
                </table>
                <?php if (isset($links)) { ?>
                    <?php echo $links; ?>
                <?php } ?>
            </div>
        </section>
    </div>
</section>
<!--/ CONTENT -->
<script type="text/javascript" src="http://platform.twitter.com/widgets.js" type="text/javascript" nonce="a6b9a780936c8e980939086f618dded2"></script>
<script src="<?php echo base_url(); ?>/assets/js/vendor/jquery/jquery-3.1.0.min.js" type="text/javascript" nonce="a6b9a780936c8e980939086f618dded2"></script>
<script type="text/javascript" nonce="a6b9a780936c8e980939086f618dded2">
        //Date Filter
        
        $(document).ready(function(){
            $("#fdate").datepicker({
                dateFormat: 'yy-mm-dd',
                autoclose: true,
                todayHighlight: true,
                maxDate: new Date(),
                onSelect: function(selected) {
                    $("#tdate").datepicker("option","minDate", selected)
                }
            });	

            $("#tdate").datepicker({
                dateFormat: 'yy-mm-dd',
                autoclose: true,
                todayHighlight: true,
                maxDate: new Date(),
                onSelect: function(selected) {
                    $("#fdate").datepicker("option","maxDate", selected)
                }
            });

            $('#show_filter').click(function () {                
                if($("#show_filter_form").hasClass("d-none")){
                    $("#show_filter_form").removeClass("d-none");
                } else {
                    $("#show_filter_form").addClass("d-none");
                }                
            });

            $('#search_btn').click(function(){
                
                var weekTime = document.getElementById("weekTime").value;
                var statusType = document.getElementById("statusType").value;
                var year = document.getElementById("year").value;
                const error = document.getElementById('error');
                if (weekTime === "" && statusType === "" && year === "") { 
                    
                    error.textContent = 'Please enter any data to search';
                    //alert("Input your data to search");
                    return false;
                } else {
                   
                    return true;
                }
            });

            $('#search_btn_admin').click(function(){
                
                var weekTime = document.getElementById("weekTime").value;
                var dept = document.getElementById("dept").value;
                var statusType = document.getElementById("statusType").value;
                var year = document.getElementById("year").value;
                const error = document.getElementById('error');
                if (weekTime === "" && statusType === "" && year === "" && dept === "") { 
                    
                    error.textContent = 'Please enter any data to search';
                    //alert("Input your data to search");
                    return false;
                } else {
                   
                    return true;
                }
            });

            // $('#reset_btn').click(function () { 
            //     // $('#search_data').reset(); 
            //     $('#search_data').trigger('reset');          
            //     // window.location.reload();   
            //     window.location.href = "<?php echo base_url(); ?>gazette"           
            // });
        });

</script>

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