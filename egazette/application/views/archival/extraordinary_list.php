<?php defined('BASEPATH') OR exit('No direct script access allowed'); ?>
<style rel="stylesheet" nonce="8f0882ce3be14f201cadd0eff5726cbd">
	.facebook_btn {
		border:none; overflow:hidden;
	}
    .btn {
        margin-right: 11px !important;
    }
</style>
<!--  CONTENT  -->
<section id="content">
    <div class="page page-tables-footable">
        <!-- bradcome -->
        <ol class="breadcrumb">
            <li><a href="<?php echo base_url(); ?>applicants_login/dashboard">Dashboard</a></li>
            <li>Archival</li>
            <li class="active">Extraordinary Gazette (Archival)</li>
        </ol>
        <!-- row -->
        <?php if ($this->session->flashdata('success')) { ?>
            <div class="alert alert-success alert-dismissable"><button aria-hidden="true" data-dismiss="alert" class="close" type="button">×</button><?php echo $this->session->flashdata('success'); ?></div>
        <?php } ?>
        <?php if ($this->session->flashdata('error')) { ?>
            <div class="alert alert-danger alert-dismissable"><button aria-hidden="true" data-dismiss="alert" class="close" type="button">×</button><?php echo $this->session->flashdata('error'); ?></div>
        <?php } ?>

        <?php 
            $dept_id = "";
            $notification_type_id = "";
            $subject = "";
            $notification_number = "";
            $gazette_no = "";
            $keywords = "";
            $notice_date_form = "";
            $notice_date_to = "";

            if(isset($inputs['dept_id'])){
                $dept_id = $inputs['dept_id'];
            }
            if(isset($inputs['notification_type_id'])){
                $notification_type_id = $inputs['notification_type_id'];
            }
            if(isset($inputs['subject'])){
                $subject = $inputs['subject'];
            }
            if(isset($inputs['notification_number'])){
                $notification_number = $inputs['notification_number'];
            }
            if(isset($inputs['gazette_no'])){
                $gazette_no = $inputs['gazette_no'];
            }
            if(isset($inputs['keywords'])){
                $keywords = $inputs['keywords'];
            }
            if(isset($inputs['notice_date_form'])){
                $notice_date_form = $inputs['notice_date_form'];
            }
            if(isset($inputs['notice_date_to'])){
                $notice_date_to = $inputs['notice_date_to'];
            }
            if(!empty($dept_id) || 
            !empty($notification_type_id) || 
            !empty($subject) || 
            !empty($notification_number) || 
            !empty($gazette_no) || 
            !empty($keywords) || 
            !empty($notice_date_form) || 
            !empty($notice_date_to)){
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
                    <?php echo form_open('archival/filter', array('name' => "archival_add", 'role' => "form", 'id' => "archival_add", 'enctype' => "multipart/form-data", 'method' => "post")); ?>    
                        <div class="boxs-body">
                            <div class="row">
                                <input type="hidden" name="check" value="1">
                                <div class="form-group col-md-4 ext">
                                    <label for="username">Department : </label>
                                    <select name="dept_id" id="dept_id" class="form-control">
                                        <option value="">Select Department</option>
                                        <?php if (!empty($dept)) { ?>
                                            <?php foreach ($dept as $type) { ?>
                                            <option value="<?php echo $type->id; ?>" <?php echo set_select('dept_id', $type->id); ?>><?php echo $type->department_name; ?></option>
                                            <?php } ?>
                                        <?php } ?>
                                    </select>
                                    <?php if (form_error('dept_id')) { ?>
                                        <span class="error"><?php echo form_error('dept_id'); ?></span>
                                    <?php } ?>
                                </div>
                                
                                <div class="form-group col-md-4">
                                    <label for="username">Notification Type : </label>
                                    <select name="notification_type_id" id="notification_type_id" class="form-control">
                                        <option value="">Select Notification Type</option>
                                        <?php if (!empty($notification_types)) { ?>
                                            <?php foreach ($notification_types as $type) { ?>
                                            <option value="<?php echo $type->id; ?>" <?php echo set_select('notification_type_id', $type->id); ?>><?php echo $type->notification_type; ?></option>
                                            <?php } ?>
                                        <?php } ?>
                                    </select>
                                    <?php if (form_error('notification_type_id')) { ?>
                                        <span class="error"><?php echo form_error('notification_type_id'); ?></span>
                                    <?php } ?>
                                </div>
                                <div class="form-group col-md-4 ext">
                                    <label for="email">Subject : </label>
                                    <input name="subject" id="subject" class="form-control" placeholder="Enter Subject" value="<?php echo set_value('subject'); ?>"  autocomplete="off">
                                    <?php if (form_error('subject')) { ?>
                                        <span class="error"><?php echo form_error('subject'); ?></span>
                                    <?php } ?>
                                </div>
                            </div>
                            <div class="row">
                                <div class="form-group col-md-4">
                                    <label for="email">Order/Notification Number : </label>
                                    <input type="text" name="notification_number" id="notification_number" class="form-control alpha_num_dash" placeholder="Enter Order Number/Notification"  value="<?php echo set_value('notification_number'); ?>" autocomplete="off">
                                    
                                    <?php if (form_error('notification_number')) { ?>
                                        <span class="error"><?php echo form_error('notification_number'); ?></span>
                                    <?php } ?>
                                </div>
                                <div class="form-group col-md-4">
                                    <label for="email">Gazette No : </label>
                                    <input name="gazette_no" id="gazette_no" class="form-control number_only" placeholder="Enter Gazette No" value="<?php echo set_value('gazette_no'); ?>"  autocomplete="off">
                                    <?php if (form_error('gazette_no')) { ?>
                                        <span class="error"><?php echo form_error('gazette_no'); ?></span>
                                    <?php } ?>
                                </div>
                                <div class="form-group col-md-4">
                                    <label for="email">Keywords : </label>
                                    <input name="keywords" id="keywords" class="form-control" placeholder="Use comma for multiple keywords" value="<?php echo set_value('keywords'); ?>"  autocomplete="off">
                                    <?php if (form_error('keywords')) { ?>
                                        <span class="error"><?php echo form_error('keywords'); ?></span>
                                    <?php } ?>
                                </div>
                            </div>
                            <div class="row">
                                <div class="form-group col-md-4">
                                    <label for="email">From Date : </label>
                                    <input name="f_date" id="f_date" class="form-control" placeholder="DD-MM-YYYY" value="<?php echo set_value('f_date'); ?>"  autocomplete="off">
                                    <?php if (form_error('f_date')) { ?>
                                        <span class="error"><?php echo form_error('f_date'); ?></span>
                                    <?php } ?>
                                </div>
                                
                                <div class="form-group col-md-4">
                                    <label for="email">To Date : </label>
                                    <input name="t_date" id="t_date" class="form-control" placeholder="DD-MM-YYYY" value="<?php echo set_value('t_date'); ?>"  autocomplete="off">
                                    <?php if (form_error('t_date')) { ?>
                                        <span class="error"><?php echo form_error('t_date'); ?></span>
                                    <?php } ?>
                                </div>

                                <div class="form-group col-md-4">
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
                            <div class="row">
                                <div class="form-group col-md-6">
                                    <input type="submit" value="Search" id= "search_name" name="search_name" class="btn-bg btn btn-success">
                                    <input type="button" id="reset_btn" value="Reset" name="" class="btn-bg btn btn-primary">
                                    <div class="col-md-15">
                                        <span id="error"></span>
                                    </div>
                                </div>
                            </div>
                        </div>
                            
                    <?php echo form_close(); ?>
                </section>
            </div>
            <div class="col-md-12">
                <section class="boxs">
                    <div class="boxs-header filter-with-addd">
                        <h3 class="custom-font hb-blue"><strong>Extraordinary Gazettes</strong></h3>
                        <!-- <div style="position: relative; left: 150px; width: 55%;">
                            <?php echo form_open('archival/filter', array('name' => "archival_add", 'role' => "form", 'id' => "archival_add", 'enctype' => "multipart/form-data", 'method' => "post"))?>
                                <div class="form-group col-md-4 ext">
                                    <label for="username">Department : </label>
                                    <select name="dept_id" id="dept_id" class="form-control">
                                        <option value="">Select Department</option>
                                        <?php if (!empty($dept)) { ?>
                                            <?php foreach ($dept as $type) { ?>
                                            <option value="<?php echo $type->id; ?>" <?php echo set_select('dept_id', $type->id); ?>><?php echo $type->department_name; ?></option>
                                            <?php } ?>
                                        <?php } ?>
                                    </select>
                                    <?php if (form_error('dept_id')) { ?>
                                        <span class="error"><?php echo form_error('dept_id'); ?></span>
                                    <?php } ?>
                                </div>
                                <div class="form-group col-md-3">
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
                                <div class="form-group col-md-3">
                                    <input type="submit" value="Search" id= "search_name" name="search_name" class="btn-bg btn btn-success" style="margin-top: 25px;">
                                    <div class="col-md-15">
                                        <span id="error"></span>
                                    </div>
                                </div>
                            <?php echo form_close()?>
                        </div> -->
                        <button class="btn btn-success btn-raised btn-fab btn-fab-mini btn-round" id="show_filter" title="Filter"><i class="fa fa-filter"></i></button>
                        <a href="<?php echo base_url(); ?>archival/add" class="btn-bg btn btn-success btn-right-align">Add</a>
                    </div>
                    <div class="boxs-body">
                        
                        <table id="searchTextResults" data-filter="#filter" data-page-size="5" class="footable table table-custom">
                            <thead>
                                <tr>
                                    <th>Sl No</th>
                                    <th>Department</th>
                                    <th>Notification Number</th>
                                    <th>Gazette Number</th>
                                    <th>Published Date</th>
                                    <th>Published Gazette</th>
                                    <th>Action</th>
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
                                            <td><?php echo $cntr; ?></td>
                                            <td><?php echo $gazette->department_name; ?></td>
                                            <td><?php echo $gazette->notification_number; ?></td>
                                            <td><?php echo $gazette->gazette_number; ?></td>
                                            <td><?php echo date('d-m-Y', strtotime($gazette->published_date)); ?></td>
                                            <td>
                                                <a href="<?php echo base_url() . $gazette->gazette_file; ?>" target="blank"><i class="fa fa-file-pdf-o"></i></a>
                                            </td>
                                            <td>
                                                <div class="btn-group mr5 btn-grp-mr">
                                                    <button type="button" class="btn btn-default btn-pad">Action</button>
                                                    <button type="button" class="btn btn-default dropdown-toggle btn-pad" data-toggle="dropdown">
                                                        <span class="caret"></span>
                                                        <span class="sr-only">Toggle Dropdown</span>
                                                    </button>
                                                    <ul class="dropdown-menu" role="menu">
                                                        
                                                        <li>
                                                            <a href="<?php echo base_url(); ?>archival/extraordinary_view/<?php echo $gazette->id ?>">View</a>
                                                        </li>
                                                        <li>
                                                            <a href="<?php echo base_url(); ?>archival/edit_extraordinary_view/<?php echo $gazette->id ?>">Edit</a>
                                                        </li>
                                                        <li>
                                                            <a href="#" class="delete" data-toggle="modal" data-target="#delete_modal" data-key="<?php echo $gazette->id ?>">Delete</a>
                                                        </li>
                                                    </ul>
                                                </div>
                                            </td>
                                        </tr>
                                        <?php
                                        $cntr++;
                                    }
                                    ?>
                                    <?php } else { ?>
                                    <tr>
                                       <td colspan="7" class="center">No data to display</td>
                                    </tr>
                            <?php } ?>
                            </tbody>
                        </table>
                        <?php if (isset($links)) { ?>
                            <?php echo $links; ?>
                        <?php } ?>
                    </div>
                </section>
                
                <div class="modal fade" id="delete_modal" tabindex="-1" role="dialog" aria-labelledby="myModalLabel" aria-hidden="true" data-backdrop="false">

                    <?php echo form_open('archival/delete', array('class' => "form1 redd-btn", 'name' => "reject_form_approver", 'method' => "post", 'id' => 'reject_form_approver')); ?>

                        <input type="hidden" name="gz_id" id="gz_id" value=""/>
                        <input type="hidden" name="check" id="check" value="1"/>

                        <div class="modal-dialog modal-md">
                            <div class="modal-content">
                                <div class="modal-header">
                                    <button type="button" class="close" data-dismiss="modal">&times;</button>
                                    <h4 class="modal-title">Delete Gazette</h4>
                                </div>
                                <div class="modal-body">
                                    <p>Do you really want to delete this gazette ?</p>
                                </div>
                                <div class="modal-footer">
                                    <button type="submit" class="btn btn-raised btn-success">Submit</button>
                                </div>
                            </div>
                        </div>
                    <?php echo form_close(); ?>
                </div>
            </div>
        </div>
    </div>
</section>
<!--/ CONTENT -->
<!--<script type="text/javascript" src="http://platform.twitter.com/widgets.js" type="text/javascript" nonce="a6b9a780936c8e980939086f618dded2"></script>-->
<script src="<?php echo base_url(); ?>/assets/js/vendor/jquery/jquery-3.1.0.min.js" type="text/javascript" nonce="a6b9a780936c8e980939086f618dded2"></script>
<script type="text/javascript" nonce="a6b9a780936c8e980939086f618dded2">
    $(document).ready(function () {
        $('#show_filter').click(function () {                
            if($("#show_filter_form").hasClass("d-none")){
                $("#show_filter_form").removeClass("d-none");
            } else {
                $("#show_filter_form").addClass("d-none");
            }                
        });

        $('#reset_btn').click(function () { 
            $('#search_data').trigger('reset');      
            window.location.href = "<?php echo base_url(); ?>archival/extraordinary"           
        });

        $(".delete").on('click', function () {
            var id = $(this).data('key');
            $("#gz_id").val(id);
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

        $('#search_name').click(function(){

            var x = document.getElementById("dept_id").value;

            if(x === ""){
            var x = document.getElementById("notification_type_id").value;
            }
            if(x === ""){
            var x = document.getElementById("subject").value;
            }
            if(x === ""){
            var x = document.getElementById("notification_number").value;
            }
            if(x === ""){
            var x = document.getElementById("gazette_no").value;
            }
            if(x === ""){
            var x = document.getElementById("keywords").value;
            }
            if(x === ""){
            var x = document.getElementById("f_date").value;
            }
            if(x === ""){
            var x = document.getElementById("t_date").value;
            }
            const error = document.getElementById('error');
            if (x === "") { 
                error.textContent = 'Please enter any data to search';
                //alert("Input your data to search");
                return false;
            } else {
                return true;
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