<?php defined('BASEPATH') OR exit('No direct script access allowed'); ?>
<!--  CONTENT  -->
<section id="content">
    <div class="page page-tables-footable">
        <!-- bradcome -->
        <ol class="breadcrumb">
            <li><a href="<?php echo base_url(); ?>dashboard">Dashboard</a></li>
            <li><a href="<?php echo base_url(); ?>weekly_gazette">Weekly Gazette</a></li>
            <li class="active">Approved Weekly Gazette</li>
        </ol>

        <!-- <div class="b-b mb-10">
            <div class="row">
                <div class="col-sm-12 col-xs-12">
                    <?php //if($this->session->userdata('is_admin')) { ?>
                        <h1 class="h3 m-0">Directorate of Printing, Stationery and Publication Weekly Gazette</h1>
                    <?php //} else { ?>
                        <h1 class="h3 m-0"><?php //echo $dept_name->department_name; ?> Weekly Gazette</h1>
                    <?php// } ?>
                    <small class="text-muted"></small>
                </div>
            </div>
        </div> -->

        <!---Filteration Data-->
        <?php 
            if(isset($inputs['weekTime'])){
                $weekTime = $inputs['weekTime'];
            }
            if(isset($inputs['statusType'])){
                $statusType = $inputs['statusType'];
            }
            if(isset($inputs['dept'])){
                $dept = $inputs['dept'];
            }
            if(isset($inputs['fdate'])){
                $fdate = $inputs['fdate'];
            }
            if(isset($inputs['tdate'])){
                $tdate = $inputs['tdate'];
            }
         
        ?>

        <!--Filteration-->

        <section class="boxs box_report d-none" id="show_filter_form">
            <div class="boxs-header">
                <h3 class="custom-font hb-blue"><strong>Filter</strong></h3>
            </div>
            <div class="boxs-body">
                <?php echo form_open('weekly_gazette/approved_wk_gz', array('method' => 'post', 'id'=>'search_data', 'autocomplete' => 'off')); ?>
                <div class="row">
                    <div class="col-md-3">
                        <div class="form-group">    
                            <label for="filter" class="label_txt">From Date:</label>
                            <input name="fdate" id="fdate" type="text" class="form-control rounded w-md mb-10 inline-block" placeholder="YYYY-MM-DD" autocomplete="off" value="<?php echo set_value('fdate'); ?>" />
                        </div>
                    </div>
                    <div class="col-md-3">
                        <div class="form-group">
                            <label for="filter" class="label_txt">To Date:</label>
                            <input name="tdate" id="tdate" type="text" class="form-control rounded w-md mb-10 inline-block" placeholder="YYYY-MM-DD" autocomplete="off" value="<?php echo set_value('tdate'); ?>"/>
                        </div> 
                    </div>
                    <?php if ($this->session->userdata('is_admin')) { ?>
                        <div class="col-md-3">
                            <div class="form-group">    
                                <label for="filter" class="label_txt">Department:</label>
                                <select class="form-control" name="dept" id="dept">
                                    <option value="">Select Department</option>
                                    <?php foreach ($department_type as $departmentValue) { ?>
                                        <option value="<?php echo $departmentValue->id; ?>" <?php
                                                $dept = set_value('dept');
                                                if ($dept == $departmentValue->id) {
                                                    echo "selected";
                                                }
                                                ?>><?php echo $departmentValue->department_name; ?></option>
                                            <?php } ?>
                                </select>
                            </div>
                        </div>
                    <?php }?>
                    <div class="col-md-3">
                        <div class="form-group">
                            <label for="filter" class="label_txt">Status:</label>
                            <select class="form-control" name="statusType" id="statusType">
                                <option value="">Select Status</option>
                                <?php foreach ($gz_status as $statusValue) { ?>
                                    <option value="<?php echo $statusValue->id; ?>"<?php
                                $statusType = set_value('statusType');
                                if ($statusType == $statusValue->id) {
                                    echo "selected";
                                }
                                ?>>
                                <?php echo $statusValue->status_name; ?>
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
                                <option value="1" <?php $weekTime = set_value('weekTime');
                                    if ($weekTime == '1') {
                                        echo "selected";
                                    } ?>>Week 1</option>
                                                    <option value="2"  <?php $weekTime = set_value('weekTime');
                                    if ($weekTime == '2') {
                                        echo "selected";
                                    } ?>>Week 2</option>
                                                        <option value="3"  <?php $weekTime = set_value('weekTime');
                                    if ($weekTime == '3') {
                                        echo "selected";
                                    } ?>>Week 3</option>
                                                                <option value="4"  <?php $weekTime = set_value('weekTime');
                                    if ($weekTime == '4') {
                                        echo "selected";
                                    } ?>>Week 4</option>

                            </select>
                        </div>    
                    </div>
                                    
                   <div class="clearfix"></div>
                    <div class="col-md-3">
                        <input type="submit" value="Search" name="" class="btn-bg btn btn-success" id="search_btn">
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
                        <h3 class="custom-font hb-blue"><strong>Govt. Press Approved Weekly Gazette</strong></h3>
                <?php } else { ?>
                    <h3 class="custom-font hb-blue"><strong>Approved Weekly Gazette</strong></h3>
                <?php } ?>
                <div class="">
                    <!-- If admin user, can view the weekly gazette filter for approval -->
                    <?php if ($this->session->userdata('is_admin')) { ?>
                        <a href="<?php echo base_url(); ?>weekly_gazette/view_weekly_gazette" class="btn-bg btn btn-warning">Merge</a>
                        <a href="<?php echo base_url(); ?>weekly_gazette/publish_gazette_form" class="btn-bg btn btn-success">Publish</a>
                    <?php } ?>
                    <button class="btn btn-success btn-raised btn-fab btn-fab-mini btn-round" id="show_filter" title="Filter"><i class="fa fa-filter"></i></button>
                    
                    <?php if (!$this->session->userdata('is_admin')) { ?>
                    <a href="<?php echo base_url(); ?>weekly_gazette/add" class="btn btn-success btn-raised btn-round">Add</a>
                <?php } ?>
                </div>
            </div>
            <div class="boxs-body">
                <?php if ($this->session->flashdata('success')) { ?>
                    <div class="alert alert-success alert-dismissable"><button aria-hidden="true" data-dismiss="alert" class="close" type="button">×</button><?php echo $this->session->flashdata('success'); ?></div>
                <?php } ?>
                <?php if ($this->session->flashdata('error')) { ?>
                    <div class="alert alert-danger alert-dismissable"><button aria-hidden="true" data-dismiss="alert" class="close" type="button">×</button><?php echo $this->session->flashdata('error'); ?></div>
                <?php } ?>
                <!-- If dept. user, can add the weekly gazette -->
                <!-- <?php //if (!$this->session->userdata('is_admin')) { ?>
                    <a href="<?php //echo base_url(); ?>weekly_gazette/add" class="btn-bg btn btn-success btn-right-align">Add</a>
                <?php //} ?> -->
                
                <div class="bs-example bs-example-tabs" data-example-id="togglable-tabs">
                    <div class="tab-content" id="myTabContent">
                        <div class="tab-pane fade active in" role="tabpanel" id="unpublished" aria-labelledby="unpublished-tab">
                            <table id="searchTextResults" data-filter="#filter" data-page-size="5" class="footable table table-custom">
                            <thead>
                                    <tr>
                                        <th>Sl No</th>
                                        <?php if ($this->session->userdata('is_admin')) { ?>
                                            <th>Department</th>
                                        <?php } ?>
                                        <th>Part</th>
                                        <th width="150">Subject</th>
                                        <th>Date</th>
                                        <th>Dept. Document</th>
                                        <th>Week</th>
                                        <th>Status</th>
                                        <th>Action</th>
                                    </tr>
                                </thead>
                                <tbody>
                                    <?php if (!empty($gazettes_approved)) { ?>
                                        <?php
                                        if ($this->my_pagination->total_rows > $this->my_pagination->per_page) {
                                            $cntr = 1 + ($this->my_pagination->cur_page - 1) * $this->my_pagination->per_page;
                                        } else {
                                            $cntr = 1;
                                        }
                                        ?>
                                        <?php foreach ($gazettes_approved as $gazette) { ?>
                                            <tr>
                                                <td><?php echo $cntr; ?></td>
                                                <?php if ($this->session->userdata('is_admin')) { ?>
                                                    <td><?php echo $gazette->department_name; ?></td>
                                                <?php } ?>
                                                <td><?php echo $gazette->part_name; ?></td>
                                                <td><?php echo $gazette->subject; ?></td>
                                                <td><?php echo get_formatted_datetime($gazette->created_at); ?></td>
                                                <td>
                                                    <a href="<?php echo base_url() . $gazette->dept_pdf_file_path; ?>" target="_blank">
                                                        <i class="fa fa-file-pdf-o"></i>
                                                    </a>
                                                </td>
                                                <td><?php echo $gazette->week; ?></td>
                                                <td><?php echo $gazette->status_name; ?></td>
                                                <td class="center">
                                                    <?php if ($this->session->userdata('is_admin')) { ?>
                                                        <a href="<?php echo base_url(); ?>weekly_gazette/press_view/<?php echo $gazette->id; ?>"><i class="fa fa-eye"></i></a>
                                                    <?php } else { ?>
                                                        <a href="<?php echo base_url(); ?>weekly_gazette/dept_view/<?php echo $gazette->id; ?>/<?php echo $gazette->user_id; ?>"><i class="fa fa-eye"></i></a>
                                                    <?php } ?>
                                                </td>
                                            </tr>
                                            <?php
                                            $cntr++;
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
                        <div class="tab-pane fade" role="tabpanel" id="approved" aria-labelledby="approved-tab">
                            <table id="searchTextResults" data-filter="#filter" data-page-size="5" class="footable table table-custom">
                                
                            </table>
                            <?php if (isset($links)) { ?>
                                <?php echo $links; ?>
                            <?php } ?>
                        </div>

                    </div>
                </div>
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
                var dept = document.getElementById("dept").value;
                var statusType = document.getElementById("statusType").value;
                var f_data = document.getElementById("fdate").value;
                var t_data = document.getElementById("tdate").value;
                const error = document.getElementById('error');
                if (weekTime === "" && statusType === "" && f_data === "" && t_data === "" && dept === "") { 
                    
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