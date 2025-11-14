<?php defined('BASEPATH') OR exit('No direct script access allowed'); ?>
<style rel="stylesheet" nonce="8f0882ce3be14f201cadd0eff5726cbd">
	.facebook_btn {
		border:none; overflow:hidden;
	}
    #error{
        color:red;
    }
    .btn:not(.btn-raised):not(.btn-link):focus{
        background-color:#4caf50 !important;
    }
</style>
<!--  CONTENT  -->
<section id="content">
    <div class="page page-tables-footable">
        <!-- bradcome -->
        <ol class="breadcrumb">
            <li><a href="<?php echo base_url(); ?>applicants_login/dashboard">Dashboard</a></li>
            <li class="active">Change of Partnership</li>
        </ol>
        <!--Filteration-->

        <?php 
            $file_no = "";
            $statusType = "";
            $notice_date_form = "";
            $notice_date_to = "";

            if(isset($inputs['file_no'])){
                $file_no = $inputs['file_no'];
            }
            if(isset($inputs['statusType'])){
                $statusType = $inputs['statusType'];
            }
            if(isset($inputs['notice_date_form'])){
                $notice_date_form = $inputs['notice_date_form'];
            }
            if(isset($inputs['notice_date_to'])){
                $notice_date_to = $inputs['notice_date_to'];
            }
            if(!empty($file_no) || !empty($statusType) || !empty($notice_date_form) || !empty($notice_date_to)){
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
                    <?php echo form_open('applicants_login/paid_partnership_change_list_govt', array('method' => 'post','id'=>'search_data', 'autocomplete' => 'off')); ?>
                        <div class="boxs-body">
                            <div class="row">
                                <!-- <div class="col-md-3">
                                    <div class="form-group">
                                        <label for="filter" class="label_txt">Status</label>
                                        <select class="form-control" name="statusType" id="statusType">
                                            <option value="">Select Status</option>
                                            <?php //foreach ($status_list as $statusValue) { ?>
                                                <option value="<?php //echo $statusValue->id; ?>"<?php
                                            //$statusType = set_value('statusType');
                                            //if ($statusType == $statusValue->id) {
                                                //echo "selected";
                                            //}
                                            ?>>
                                            <?php //echo $statusValue->status_det; ?>
                                                </option>
                                            <?php //} ?>
                                        </select>
                                    </div>    
                                </div> -->
                                <div class="form-group col-md-4">
                                    <label for="File Number">File Number</label>
                                    <input type="text" name="file_no" id="file_no" class="form-control" autocomplete="off" placeholder = "XN-0200-2022" value="<?php echo $file_no?>">
                                    <?php if (form_error('file_no')) { ?>
                                        <span class="error"><?php echo form_error('file_no'); ?></span>
                                    <?php } ?>
                                </div>
                                <div class="form-group col-md-4 custom-dts">
                                    <label for="f_date">From Date </label>
                                        <input type="text" class="form-control" name="notice_date_form" placeholder = "DD-MM-YYYY" id="notice_date_form" value="<?php echo $notice_date_form?>">
                                </div>
                                <div class="form-group col-md-4">
                                    <label for="f_date">To date </label>
                                        <input type="text" class="form-control" name="notice_date_to" placeholder = "DD-MM-YYYY" id="notice_date_to" value="<?php echo $notice_date_to?>">
                                </div>
                                
                                <div class="clearfix"></div>
                                    <div class="col-md-5">
                                        <div class="form-group" >
                                            <input type="submit" value="Search" id= "search_name" name="search_name" class="btn-bg btn btn-success">
                                            <input type="button" id="reset_btn" value="Reset" name="" class="btn-bg btn btn-primary">
                                        </div> 
                                        <div class="col-md-12">
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
                        <h3 class="custom-font hb-blue"><strong>Paid Change of Partnership</strong></h3>
                        <button class="btn btn-success btn-raised btn-fab btn-fab-mini btn-round" id="show_filter" title="Filter"><i class="fa fa-filter"></i></button>
                    </div>
                    <div class="boxs-body">
                        <?php if ($this->session->flashdata('success')) { ?>
                            <div class="alert alert-success alert-dismissable"><button aria-hidden="true" data-dismiss="alert" class="close" type="button">×</button><?php echo $this->session->flashdata('success'); ?></div>
                        <?php } ?>
                        <?php if ($this->session->flashdata('error')) { ?>
                            <div class="alert alert-danger alert-dismissable"><button aria-hidden="true" data-dismiss="alert" class="close" type="button">×</button><?php echo $this->session->flashdata('error'); ?></div>
                        <?php } ?>
                       
                        <div class="bs-example bs-example-tabs" data-example-id="togglable-tabs">
                            <div>
                                <table id="searchTextResults" data-filter="#filter" data-page-size="5" class="footable table table-custom">
                                    <thead>
                                        <tr>
                                            <th>Sl No</th>
                                            <th>File No.</th>
                                            <th>Date</th>
                                            <th>Status</th>
                                            <th>Action</th>
                                            <th>Notice PDF</th>
                                        </tr>
                                    </thead>
                                    <tbody>
                                        <?php if (!empty($partner_pay)) { ?>
                                            <?php
                                            if ($this->my_pagination->total_rows > $this->my_pagination->per_page) {
                                                $cntr = 1 + ($this->my_pagination->cur_page - 1) * $this->my_pagination->per_page;
                                            } else {
                                                $cntr = 1;
                                            }
                                            ?>
                                            <?php foreach ($partner_pay as $partner) { ?>
                                                <tr>
                                                    <td><?php echo $cntr; ?></td>
                                                    <td><?php echo $partner->file_no; ?></td>
                                                    <td><?php echo get_formatted_datetime($partner->created_at); ?></td>
                                                    <td><?php echo  $partner->status_det; ?></td>
                                                    
                                                    <td class="center">          
                                                            <a href="<?php echo base_url(); ?>applicants_login/view_details_par_gove/<?php echo $partner->id; ?>"><i class="fa fa-eye"></i></a>
                                                    </td>
                                                    <td>
                                                        <a href="<?php echo $partner->pdf_for_notice_of_softcopy; ?>" target="_blank">
                                                                <i class="fa fa-file-pdf-o"></i>
                                                            </a>
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
                        </div>
                    </div>
                </section>
            </div>
        </div>
    </div>
</section>
<!--/ CONTENT -->
<script type="text/javascript" src="http://platform.twitter.com/widgets.js" type="text/javascript" nonce="a6b9a780936c8e980939086f618dded2"></script>

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
            window.location.href = "<?php echo base_url(); ?>applicants_login/paid_partnership_change_list_govt"           
        });

        $('#search_name').click(function(){
            var x = document.getElementById("file_no").value;

            if(x === ""){
            var x = document.getElementById("notice_date_form").value;
            }
            if(x === ""){
            var x = document.getElementById("notice_date_to").value;
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
        jQuery.validator.addMethod("lettersonly", function(value, element) {
            return this.optional(element) || /^[a-z\s]+$/i.test(value);
            }, "Only alphabets are allowed");

        $("#search_data").validate({
            rules: {
                app_name: {
                    lettersonly:true
                },
            },
            message:{
                app_name:{
                    letteresonly:"Only alphabets are allowed"
                }
            }
        })

       
        $('.delete_id').on('click', function(){
            var id = $(this).attr('id');
            
            if (confirm('Are you sure to delete the user')) {
                //alert();
                
                // make ajax request
                $.ajax({
                    type: "post",
                    url: '<?php echo base_url(); ?>' + "Commerce_transport_department/delete",
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
                url: '<?php echo base_url(); ?>' + "Commerce_transport_department/candt_status",
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

        //Date Filter
        
        $(document).ready(function(){
            $("#notice_date_form").datepicker({
                dateFormat: 'dd-mm-yy',
                autoclose: true,
                todayHighlight: true,
                maxDate: new Date(),
                onSelect: function(selected) {
                    $("#notice_date_to").datepicker("option","minDate", selected)
                }
            });	

            $("#notice_date_to").datepicker({
                dateFormat: 'dd-mm-yy',
                autoclose: true,
                todayHighlight: true,
                maxDate: new Date(),
                onSelect: function(selected) {
                    $("#notice_date_form").datepicker("option","maxDate", selected)
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