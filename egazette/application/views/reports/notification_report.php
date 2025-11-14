<?php defined('BASEPATH') OR exit('No direct script access allowed'); ?>
<style rel="stylesheet" nonce="8f0882ce3be14f201cadd0eff5726cbd">
    .box_report {
        /*height: 257px;*/
    }
    .label_txt {
        padding-top: 5px;
    }
    .well {
        background-color: #fff;
    }
</style>
<!--  CONTENT  -->
<section id="content">
    <div class="page page-tables-footable">
        <!-- bradcome -->
        <ol class="breadcrumb">
            <li><a href="<?php echo base_url(); ?>applicants_login/dashboard">Dashboard</a></li>
            <li>Reports</li>
            <li class="active">Extraordinary Gazette Reports</li>
        </ol>
        <!-- row -->

        <?php 
            $gType = "";
            $fdate = "";
            $tdate = "";
            $subline = "";
            $dept = "";
            $odrNo = "";
            $nType = "";
            $statusType = "";

            if(isset($inputs['gType'])){
                $gType = $inputs['gType'];
            }
            if(isset($inputs['fdate'])){
                $fdate = $inputs['fdate'];
            }
            if(isset($inputs['tdate'])){
                $tdate = $inputs['tdate'];
            }
            if(isset($inputs['subline'])){
                $subline = $inputs['subline'];
            }
            if(isset($inputs['dept'])){
                $dept = $inputs['dept'];
            }
            if(isset($inputs['odrNo'])){
                $odrNo = $inputs['odrNo'];
            }
            if(isset($inputs['nType'])){
                $nType = $inputs['nType'];
            }
            if(isset($inputs['statusType'])){
                $statusType = $inputs['statusType'];
            }
            if(!empty($gType) || 
            !empty($fdate) || 
            !empty($tdate) || 
            !empty($subline) || 
            !empty($dept) || 
            !empty($odrNo) || 
            !empty($nType) || 
            !empty($statusType)){
                $class= "";
            }else{
                $class= "d-none";
            }
         ?>

        <div class="row">
            <div class="col-md-12">
                <section class="boxs box_report <?php echo $class ?>" id="show_filter_form">
                    <div class="boxs-header">
                        <h3 class="custom-font hb-blue"><strong>Filter</strong></h3>
                    </div>
                    <?php echo form_open('Reports/extraordinary_report_result', array('method' => 'post')); ?>
                    <div class="boxs-body">
                        <div class="row">
                            <!-- <div class="col-md-3">
                                <div class="form-group">
                                    <label for="filter" class="label_txt">Gazette Type:</label>
                                    <select class="form-control" name="gType" id="gType" required>
                                        <option value="">Select Gazette Type</option>
                                        <option value="1" <?php
                                        //$gType = set_value('gType');
                                        //if ($gType == 1) {
                                            //echo "selected";
                                        //}
                                        ?>>Extraordinary</option>
                                    </select>
                                </div>    
                            </div> -->
                            
                            <div class="col-md-3">
                                <div class="form-group">
                                    <label for="filter" class="label_txt">Subject Line:</label>
                                    <input name="subline" id="subline" type="text" class="form-control rounded w-md mb-10 inline-block" placeholder="Subject Name" value="<?php echo set_value('subline'); ?>"/>
                                </div>   
                            </div>
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
                            <div class="col-md-3">
                                <div class="form-group">
                                    <label for="filter" class="label_txt">Order No:</label>
                                    <input name="odrNo" id="odrNo" type="text" class="form-control rounded w-md mb-10 inline-block" placeholder="Notification/Order/Resolution No" value="<?php echo set_value('odrNo'); ?>"/>
                                </div> 
                            </div>
                            <div class="col-md-3">
                                <div class="form-group">
                                    <label for="filter" class="label_txt">Notification Type:</label>
                                    <select class="form-control" name="nType" id="nType">
                                        <option value="">Select Notification Type</option>
                                                <?php foreach ($notification_type as $notificatioTypeValue) { ?>
                                            <option value="<?php echo $notificatioTypeValue->notification_type; ?>"<?php
                                                    $sValue = set_value('nType');
                                                    if ($sValue == $notificatioTypeValue->notification_type) {
                                                        echo "selected";
                                                    }
                                                    ?>>
                                                <?php echo $notificatioTypeValue->notification_type; ?>
                                            </option>
                                        <?php } ?>
                                    </select>
                                </div>    
                            </div>
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
                                    <label for="filter" class="label_txt">From Date:</label>
                                    <input name="fdate" id="fdate" type="text" class="form-control rounded w-md mb-10 inline-block  " placeholder="DD-MM-YYYY" autocomplete="off" value="<?php echo set_value('fdate'); ?>" />
                                </div>
                            </div>
                            <div class="col-md-3">
                                <div class="form-group">
                                    <label for="filter" class="label_txt">To Date:</label>
                                    <input name="tdate" id="tdate" type="text" class="form-control rounded w-md mb-10 inline-block  " placeholder="DD-MM-YYYY" autocomplete="off" value="<?php echo set_value('tdate'); ?>"/>
                                </div> 
                            </div>
                            <div class="clearfix"></div>
                            <div class="col-md-6">
                                <input type="submit" value="Search" id= "search_name" name="search_name" class="btn-bg btn btn-success">
                                <input type="button" id="reset_btn" value="Reset" name="" class="btn-bg btn btn-primary">
                                <div class="col-md-15">
                                    <span id="error"></span>
                                </div>   
                            </div>
                            <?php echo form_close(); ?>
                        </div>
                    </div>
                </section>
                <section class="boxs box_report">
                    <div class="boxs-header filter-with-add">
                        <h3 class="custom-font hb-blue"><strong>Extraordinary Gazette Reports</strong></h3>
                        <button class="btn btn-success btn-raised btn-fab btn-fab-mini btn-round" id="show_filter" title="Filter"><i class="fa fa-filter"></i></button>
                    </div>
                    <div class="boxs-body">
                        <table id="searchTextResults" data-filter="#filter" data-page-size="5" class="footable table table-custom">
                            <thead>
                                <tr>
                                    <th width="100">Sl No</th>
                                    <th>Department</th>
                                    <th width="150">Notification</th>
                                    <th>Date</th>
                                    <th>Published Gazette</th>
                                    <th>Status</th>
                                    <th>Action</th>
                                </tr>
                            </thead>
                            <tbody>
                                <?php
                                if ($this->my_pagination->total_rows > $this->my_pagination->per_page) {
                                    $cntr = 1 + ($this->my_pagination->cur_page - 1) * $this->my_pagination->per_page;
                                } else {
                                    $cntr = 1;
                                }
                            
                                if (count($notification_report_details) > 0) {
                                    foreach ($notification_report_details as $notificationReportValue) {
                                        ?> 
                                        <tr>
                                            <td><?php echo $cntr; ?></td>
                                            <td><?php echo $notificationReportValue->department_name; ?></td>
                                            <td><?php echo $notificationReportValue->notification_type; ?></td>
                                            <td><?php echo $notificationReportValue->created_at; ?></td>
                                            <td><a href="<?php echo $notificationReportValue->dept_pdf_file_path; ?>"><i class="fa fa-file-pdf-o"></i></a></td>
                                            <td><?php echo $notificationReportValue->status_name; ?></td>
                                            <td><a href="<?php echo base_url('gazette/press_view/') . $notificationReportValue->id; ?>"> <i class="fa fa-eye"></i></a></td>
                                        </tr>
                                    <?php $cntr++; }
                            } else {
                                ?>
                                    <tr>
                                        <td colspan="7" align="center">No Data Found</td>
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
        </div>
    </div>
</section>
<!--/ CONTENT -->
<script src="<?php echo base_url(); ?>/assets/js/vendor/jquery/jquery-3.1.0.min.js" type="text/javascript" nonce="a6b9a780936c8e980939086f618dded2"></script>
<script type="text/javascript" nonce="a6b9a780936c8e980939086f618dded2">
    $(document).ready(function(){
        $('#show_filter').click(function () {                
            if($("#show_filter_form").hasClass("d-none")){
                $("#show_filter_form").removeClass("d-none");
            } else {
                $("#show_filter_form").addClass("d-none");
            }                
        });

        $('#reset_btn').click(function () { 
            $('#search_data').trigger('reset');      
            window.location.href = "<?php echo base_url(); ?>Reports/extraordinary_report"           
        });

        $("#fdate").datepicker({
            dateFormat: 'dd-mm-yy',
            autoclose: true,
            todayHighlight: true,
            maxDate: new Date(),
            onSelect: function(selected) {
                $("#tdate").datepicker("option","minDate", selected)
            }
        });	

        $("#tdate").datepicker({
            dateFormat: 'dd-mm-yy',
            autoclose: true,
            todayHighlight: true,
            maxDate: new Date(),
            onSelect: function(selected) {
                $("#fdate").datepicker("option","maxDate", selected)
            }
        });
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