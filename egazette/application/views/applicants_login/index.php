
<?php defined('BASEPATH') OR exit('No direct script access allowed'); ?>
<!--  CONTENT  -->
<style  rel="stylesheet" nonce="8f0882ce3be14f201cadd0eff5726cbd">
    .breadcrumb{
        padding:8px 1px;
    }
    #error{
        color:red;
    }
    .btn:not(.btn-raised):not(.btn-link):focus{
        background-color:#4caf50 !important;
    }
    #app_name-error{
        display: inline-block;
        position: absolute;
        font-size: 13px;

    }
</style>
<section id="content">
    <div class="page page-tables-footable">
        <!-- bradcome -->

        <ol class="breadcrumb">
            <li><a href="<?php echo base_url(); ?>applicants_login/dashboard">Dashboard</a></li>
            <li class="active">Change of Partnership</li>
        </ol>
        
        <!--Filter Section-->
        <?php 
            $app_name = "";
            $file_no = "";
            $status = "";
            $notice_date_form = "";
            $notice_date_to = "";
           
            if(isset($inputs['app_name'])){
                $app_name = $inputs['app_name'];
            }
            if(isset($inputs['file_no'])){
                $file_no = $inputs['file_no'];
            }
            if(isset($inputs['status'])){
                $status = $inputs['status'];
            }
            if(isset($inputs['notice_date_form'])){
                $notice_date_form = $inputs['notice_date_form'];
            }
            if(isset($inputs['notice_date_to'])){
                $notice_date_to = $inputs['notice_date_to'];
            }
            if(!empty($app_name) || !empty($file_no) || !empty($status) || !empty($notice_date_form) || !empty($notice_date_to)){
                $class= "";
            }else{
                $class= "d-none";
            }
         ?>
        
        <div class="col-md-15">
                <section class="boxs box_report <?php echo $class ?>" id="show_filter_form">
                    <div class="boxs-header">
                        <h3 class="custom-font hb-blue"><strong>Filter</strong></h3>
                    </div>
                    <?php echo form_open('applicants_login/search_for_partnership', array('method' => 'post','id'=>'search_data','autocomplete' => 'off')); ?>
                        <div class="boxs-body">
                            <div class="row">
                                <div class="form-group col-md-4">
                                    <label for="name">Applicant Name </label>
                                    <input type="text" name="app_name" id="app_name" class="form-control" autocomplete="off" value="<?php echo $app_name; ?>">
                                    <?php if (form_error('app_name')) { ?>
                                        <span class="error"><?php echo form_error('app_name'); ?></span>
                                    <?php } ?>
                                </div>
                                <div class="form-group col-md-4">
                                    <label for="File Number">File Number</label>
                                    <input type="text" name="file_no" id="file_no" class="form-control" autocomplete="off" value="<?php echo $file_no; ?>">
                                    <?php if (form_error('file_no')) { ?>
                                        <span class="error"><?php echo form_error('file_no'); ?></span>
                                    <?php } ?>
                                </div>
                                <div class="form-group col-md-4">
                                <label for="status">Status : </label>
                                <select name="status" id="status" class="form-control">
                                            <option value="">Select status</option>
                                            <?php if (!empty($total_status)) { ?>
                                                <?php foreach ($total_status as $sname) { ?>
                                                    <option value="<?php echo $sname->id; ?>" <?php if($status == $sname->id){ echo 'selected'; } ?>><?php echo $sname->status_det; ?></option>
                                                <?php } ?>
                                            <?php } ?>
                                        </select>
                                </div>
                                <div class="form-group col-md-4">
                                    <label for="f_date">From Date </label>
                                        <input type="text" class="form-control" name="notice_date_form" placeholder = "YYYY-DD-MM" id="notice_date_form" value="<?php echo $notice_date_form; ?>">
                                </div>
                                <div class="form-group col-md-4">
                                    <label for="f_date">To date </label>
                                        <input type="text" class="form-control" name="notice_date_to" placeholder = "YYYY-DD-MM" id="notice_date_to" value="<?php echo $notice_date_to; ?>">
                                </div>
                                
                                <div class="clearfix"></div>
                                    <div class="col-md-5">
                                        <div class="form-group" >
                                            <input type="submit" value="Search" name="search_name" id="search_name"class="btn-bg btn btn-success">
                                            <span id="error"></span>
                                            <input type="button" id="reset_btn" value="Reset" name="" class="btn-bg btn btn-primary">
                                            <span id="error"></span>
                                        </div>   
                                    </div>
                                </div>
                        </div>
                    <?php echo form_close(); ?>
                </div>
            </div>
              
        <!-- row -->
        <div class="">
            <div class="col-md-12">
                <section class="boxs">
                    <div class="boxs-header filter-with-add">
                        <?php if ($this->session->userdata('is_admin')) { ?>
                                <h3 class="custom-font hb-blue"><strong>Govt. Press Partnership Details</strong></h3>
                        <?php } else { ?>
                            <h3 class="custom-font hb-blue"><strong>Partnership Details</strong></h3>
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
                        <?php if($this->session->userdata('is_applicant')) { ?>
                        <a href="<?php echo base_url(); ?>applicants_login/add_partnership_details" class="btn-bg btn btn-success btn-right-align">Add</a>
                        <?php } ?>
                        <table id="searchTextResults" data-filter="#filter" data-page-size="5" class="footable table table-custom">
                            <thead>
                                <tr>
                                    <th>Sl No</th>
                                    <th>Applicant Name</th>
                                    <th>Partnership Firm Name</th>
                                    <th>File No</th>
                                    <th>Date</th>  
                                    <th>Status</th>
                                    <th>View Details</th>
<!--                                    <th>Press PDF</th>-->
<!--                                    <th>Action</th>-->
                                </tr>
                            </thead>
                            <tbody>
                                <?php if (!empty($partners)) { ?>
                                    <?php 
                                        if ($this->my_pagination->total_rows > $this->my_pagination->per_page) {
                                            $cntr = 1 + ($this->my_pagination->cur_page - 1) * $this->my_pagination->per_page;
                                        } else {
                                            $cntr = 1;
                                        }
                                    ?>
                                        <?php //print_r($partners);
                                        foreach ($partners as $partner) { ?>
                                        <tr>
                                            <td><?php echo $cntr; ?></td>
                                            <td><?php echo $partner->name; ?></td>  
                                            <td><?php echo $partner->partnership_firm_name; ?></td>
                                            <td><?php echo $partner->file_no; ?></td>  
                                            <td><?php echo strftime('%d %b %Y, %I:%M %p', strtotime($partner->created_at)); ?></td> 
                                            <td><?php echo $partner->status_det; ?></td>
<!--                                            <td><a href="<?php echo base_url(); ?>applicants_login/view_details_par/<?php echo $partner->id; ?>"><i class="fa fa-eye"></i></a></td> -->
                                             <td><a href="<?php echo base_url(); ?>applicants_login/edit_partnership_details/<?php echo $partner->id; ?>"><i class="fa fa-eye"></i></a></td>
<!--                                            <td>
                                                <div class="btn-group mr5 btn-grp-mr">
                                                    <button type="button" class="btn btn-default btn-pad">Action</button>
                                                    <button type="button" class="btn btn-default dropdown-toggle btn-pad" data-toggle="dropdown">
                                                        <span class="caret"></span>
                                                        <span class="sr-only">Toggle Dropdown</span>
                                                    </button>
                                                    <ul class="dropdown-menu" role="menu">
                                                  
                                                        <li><a href="<?php echo base_url(); ?>applicants_login/edit_partnership_details/<?php //echo $partner->id; ?>">Edit</a></li>
                                                        
                                                        <li class="delete_id" id="<?php echo $partner->id; ?>"><a href="javascript:void(0);">Delete<div class="ripple-container"></div></a></li>
                                                    </ul>
                                                </div>
                                            </td>-->
                                        </tr>
                                        <?php $cntr++;
                                    } ?>
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
            window.location.href = "<?php echo base_url(); ?>applicants_login/partnership_details_list"           
        });
        
        $('#search_name').click(function(){
            var data = document.getElementById("file_no").value;
            var data_name = document.getElementById("app_name").value;
            var s_data = document.getElementById("status").value;
            var f_data = document.getElementById("notice_date_form").value;
            var t_data = document.getElementById("notice_date_to").value;
            if (data === "" && data_name === ""&& s_data === "" && f_data === "" && t_data === "" ) { 
                //$("#searcherror").html = "Error"
                error.textContent = 'Please enter any data to search';
                //alert("Input your data to search");
                return false;
            } else {
                return true;
            }
        });

        jQuery.validator.addMethod("lettersonly", function(value, element) {
            return this.optional(element) || /^[a-z\s]+$/i.test(value);
            }, "Please enter only alphabetical characters");

        $("#search_data").validate({
            rules: {
                app_name: {
                    lettersonly:true
                },
            },
            message:{
                app_name:{
                    letteresonly:"Please enter only alphabatic caracter"
                }
            }
        })

        $("#notice_date_form").datepicker({
                dateFormat: 'yy-mm-dd',
                autoclose: true,
                todayHighlight: true,
                maxDate: new Date(),
                onSelect: function(selected) {
                    $("#notice_date_to").datepicker("option","minDate", selected)
                }
            });	

            $("#notice_date_to").datepicker({
                dateFormat: 'yy-mm-dd',
                autoclose: true,
                todayHighlight: true,
                maxDate: new Date(),
                onSelect: function(selected) {
                    $("#notice_date_form").datepicker("option","maxDate", selected)
                }
            });

        $('.delete_id').on('click', function(){
            var id = $(this).attr('id');
            
            if (confirm('Are you sure to delete the user')) {
                //alert();
                
                // make ajax request
                $.ajax({
                    type: "post",
                    url: '<?php echo base_url(); ?>' + "applicants_login/delete",
                    data: {
                        'id' : id,'<?php echo $this->security->get_csrf_token_name(); ?>' : '<?php echo $this->security->get_csrf_hash(); ?>'
                        
                    },
                    //if success, returns success message
                    success: function (data) {
                        location.reload();
                    },
                    error: function (data) {
                        location.reload();
                    }
                });
            }
        });
        
        /*
         * User approve/reject 
         */
        $('.stat_change_ct').one('click', function (e) {
              e.preventDefault();
            var user_id = $(this).attr('id');
            var status = $(this).attr('title');
            //alert('ok');
            // make ajax request
            $.ajax({
              
                type: "post",
                url: '<?php echo base_url(); ?>' + "applicants_login/candt_status",
                data: {
                    'user_id' : user_id,
                    'status' : status,
                    '<?php echo $this->security->get_csrf_token_name(); ?>' : '<?php echo $this->security->get_csrf_hash(); ?>'
                },
                dataType: 'json',
                 //if success, returns success message
                success: function (data) {
                  location.reload();
                },
                error: function (data) {
                    location.reload();
                }
            });
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