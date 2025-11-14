<?php defined('BASEPATH') OR exit('No direct script access allowed'); ?>
<!--  CONTENT  -->
<!-- <section id="content">
    <div class="page page-tables-footable">
         bradcome -->
        <div class="b-b mb-10">
            <div class="row">
                <div class="col-sm-6 col-xs-12">
                    <h1 class="h3 m-0">Districts</h1>
                    <small class="text-muted"></small>
                </div>
            </div>
        </div>
        <style rel="stylesheet" nonce="8f0882ce3be14f201cadd0eff5726cbd">
    .label_txt {
        padding-top: 5px;
    }
    .well_margin {
        background-color: #fff;
    }
    .well{
        background-color: #fff;
    }
    .m-0{
        padding-left: 9px;
    }
    .modal-title{
        font-weight: 900;
        color:red;
    }
    #error {
        display: block;
        color: red;
        margin: 5px 0 0 0;
    }
    td{
    text-align: center;
    }
    th{
    text-align: center;
    }
    .btn:not(.btn-raised):not(.btn-link):focus{
        background-color:#4caf50;
    }
</style>
<!--  CONTENT  -->
<section id="content">
    <div class="page page-tables-footable">
        <ol class="breadcrumb">
            <li><a href="<?php echo base_url(); ?>dashboard">Dashboard</a></li>
            <li class="active">Districts</li>
        </ol>
        <!-- bradcome -->

        <?php
            $state_name = "";
            $district_name = "";
            
            if(isset($inputs['state_name'])){
                $state_name = $inputs['state_name'];
            }
            if(isset($inputs['district_name'])){
                $district_name = $inputs['district_name'];
            }

            if(!empty($state_name) || !empty($district_name)){
                $class= "";
            }else{
                $class= "d-none";
            }

        ?>
        <!-- row -->
        <div class="">
            <div class="col-md-15">
                <section class="boxs box_report <?php echo $class ?>" id="show_filter_form">
                    <div class="boxs-header">
                        <h3 class="custom-font hb-blue"><strong>Filter</strong></h3>
                    </div>
                    <?php echo form_open('district/search_district', array('method' => 'post','name' => "formsearch",'id' => "formsearch")); ?>
                        <div class="boxs-body">
                            <div class="row">
                                <div class="form-group col-md-6">
                                    <label for="username">State Name : </label>
                                    <select name="state_name" id="state_name" class="form-control">
                                    <option value="">Select State</option>
                                                <?php if (!empty($state_names)) { ?>
                                                    <option value="26" 
                                                    <?php if(26 == $state_name){
                                                            echo "selected";
                                                        }?>>(StateName)</option>
                                                    <?php foreach ($state_names as $state) { ?>
                                                    <?php if($state->id != '26') { ?> 
                                                    <option value="<?php echo $state->id; ?>"  
                                                     <?php if($state->id == $state_name){
                                                            echo "selected";
                                                        } ?>><?php echo $state->state_name; ?></option>
                                                    <?php } ?>
                                                <?php } ?>
                                            <?php } ?>
                                    </select>
                                    <?php if (form_error('state_name')) { ?>
                                        <span class="error"><?php echo form_error('state_name'); ?></span>
                                    <?php } ?>
                                </div>
                                <div class="form-group col-md-6">
                                    <label for="username">District Name : </label>
                                    <!-- <select name="district_name" id="district_name" class="form-control">
                                        <option value="">Select District</option>
                                    </select> -->
                                    <input type="text" name="district_name" id="district_name" class="form-control" autocomplete="off" value="<?php echo set_value('district_name'); ?>">
                                    <?php if (form_error('district_name')) { ?>
                                        <span class="error"><?php echo form_error('district_name'); ?></span>
                                    <?php } ?>
                                </div>
                                <div class="clearfix"></div>
                                    <div class="col-md-5">
                                        <div class="form-group" >
                                            <div class="row">
                                                <div class="col-md-4">
                                                    <input type="submit" value="Search" name="search" class="btn-bg btn btn-success" onclick="return checkField()">
                                                </div>
                                                <div class="col-md-6" style="float: left;">
                                                    <input type="button" id="reset_btn" value="Reset" name="" class="btn-bg btn btn-primary">
                                                </div>
                                                <div class="col-md-12">
                                                    <span id="error"></span>
                                                </div>
                                            </div>
                                        </div>   
                                    </div>
                                </div>
                        </div>
                    <?php echo form_close(); ?>
                </div>
            </div>
        </div>
        
        <div class="">
            <div class="col-md-12">
                <section class="boxs">
                    <div class="boxs-header filter-with-addd">
                        <h3 class="custom-font hb-blue"><strong>Districts</strong></h3>
                        <button class="btn filter-margin btn-success btn-raised btn-fab btn-fab-mini btn-round" id="show_filter" title="Filter"><i class="fa fa-filter"></i></button>
                        <a href="<?php echo base_url(); ?>district/add" class="btn-bg btn btn-success btn-right-align">Add</a>
                    </div>
                    <div class="boxs-body">
                        <?php if ($this->session->flashdata('success')) { ?>
                            <div class="alert alert-success alert-dismissable"><button aria-hidden="true" data-dismiss="alert" class="close" type="button">×</button><?php echo $this->session->flashdata('success'); ?></div>
                        <?php } ?>
                        <?php if ($this->session->flashdata('error')) { ?>
                            <div class="alert alert-success alert-dismissable"><button aria-hidden="true" data-dismiss="alert" class="close" type="button">×</button><?php echo $this->session->flashdata('error'); ?></div>
                        <?php } ?>
                        
                        
                        <table id="searchTextResults" data-filter="#filter" data-page-size="5" class="footable table table-custom">
                            <thead>
                                <tr>
                                    <th width="150px">Sl No</th>
                                    <!-- <th>Block</th> -->
                                    <th>District</th>
                                    <th>State</th>
                                    <th>Status</th>
                                    <th>Action</th>
                                </tr>
                            </thead>
                            <tbody>
                                <?php if (!empty($districts)) { ?>
                                    <?php 
                                        if ($this->my_pagination->total_rows > $this->my_pagination->per_page) {
                                            $cntr = 1 + ($this->my_pagination->cur_page - 1) * $this->my_pagination->per_page;
                                        } else {
                                            $cntr = 1;
                                        }
                                    ?>
                                    <?php foreach ($districts as $dist) { ?>
                                        <tr>
                                            <td><?php echo $cntr; ?></td>
                                            <td><?php echo $dist->district_name;?></td>
                                            <td><?php echo $dist->state_name;?></td>
                                            <td>
                                                <div class="status_change togglebutton" id="<?php echo $dist->id; ?>" title="<?php echo $dist->status; ?>">
                                                    <label><input onclick="statusChange(<?php echo $dist->id; ?>)"  type="checkbox"  <?php echo ($dist->status == 1) ? "checked" : ""; ?>></label>
                                                </div>
                                            </td>
                                            <td>
                                                <div class="btn-group mr5 btn-grp-mr">
                                                    <button type="button" class="btn btn-default btn-pad">Action</button>
                                                    <button type="button" class="btn btn-default dropdown-toggle btn-pad" data-toggle="dropdown">
                                                        <span class="caret"></span>
                                                        <span class="sr-only">Toggle Dropdown</span>
                                                    </button>
                                                    <ul class="dropdown-menu" role="menu">
                                                        <li><a href="<?php echo base_url();?>district/edit/<?php echo $dist->id; ?>">Edit</a></li>
                                                        <li class="delete_id" id="<?php echo $dist->id; ?>"><a href="javascript:void(0);"onclick="deleteFunction(<?php echo $dist->id; ?>)">Delete<div class="ripple-container"></div></a></li>
                                                    </ul>
                                                </div>
                                            </td>
                                        </tr>
                                    <?php $cntr++; } ?>
                                <?php } else { ?>
                                    <tr>
                                        <td colspan="5" class="center">No data to display</td>
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
<div class="modal" tabindex="-1" role="dialog" id="deleteFunction">
  <div class="modal-dialog" role="document">
    <div class="modal-content">
      <div class="modal-header">
        <h5 class="modal-title">Delete</h5>
      </div>
	  <form action="#" class="form">
		<input type="hidden" name="dis_id" id="dis_id">
      <div class="modal-body">
        <p>Are you sure,you want to delete the details.</p>
      </div>
      <div class="modal-footer">
        <button type="button" class="btn btn-primary dis_id">Yes</button>
        <button type="button" class="btn btn-secondary" data-dismiss="modal" >No</button>
      </div>
	</form>
    </div>
  </div>
</div>

<!-- <div class="modal" tabindex="-1" role="dialog" id="statusChange">
  <div class="modal-dialog" role="document">
    <div class="modal-content">
      <div class="modal-header">
        <h5 class="modal-title">Status</h5>
      </div>
	  <form action="#" class="form">
		<input type="text" name="dis_status_id" id="dis_status_id">
      <div class="modal-body">
        <p>Are you sure,you want change the status.</p>
      </div>
      <div class="modal-footer">
        <button type="button" class="btn btn-primary dis_status_id">Yes</button>
        <button type="button" class="btn btn-secondary" data-dismiss="modal" >No</button>
      </div>
	</form>
    </div>
  </div>
</div> -->
<!--/ CONTENT -->
<script src="https://code.jquery.com/jquery-3.4.1.min.js" type="text/javascript" nonce="a6b9a780936c8e980939086f618dded2"></script>
<script type="text/javascript" nonce="a6b9a780936c8e980939086f618dded2">

        function deleteFunction(dis_id){
        $('#dis_id').val(dis_id);
        $('#deleteFunction').modal('show');
        }

        // function statusChange(dis_status_id){
        // $('#dis_status_id').val(dis_status_id);
        // $('#statusChange').modal('show');
        // }
    $(document).ready(function(){
        $('.dis_id').on('click', function(){
            var id = $('#dis_id').val();
                // make ajax request
                $.ajax({
                    type: "post",
                    url: '<?php echo base_url(); ?>' + "district/delete",
                    data: {
                        'id' : id,
                        '<?php echo $this->security->get_csrf_token_name(); ?>' : '<?php echo $this->security->get_csrf_hash(); ?>'
                    },
                    // if success, returns success message
                    success: function (data) {
                        location.reload();
                    },
                    error: function (data) {
                        location.reload();
                    }
                });
        });

        /*
         * Status change 
         */
        $('.status_change').one('click', function (e) {
            e.preventDefault();
            var id = $(this).attr('id');
            var status = $(this).attr('title');
            // make ajax request
            $.ajax({
                type: "post",
                url: "<?php echo base_url(); ?>district/status_change",
                data: {
                    'id' : id,
                    'status' : status,
                    '<?php echo $this->security->get_csrf_token_name(); ?>' : '<?php echo $this->security->get_csrf_hash(); ?>'
                },
                dataType: 'json',
                // if success, returns success message
                success: function (data) {
                    location.reload();
                },
                error: function (data) {
                    location.reload();
                }
            });
        })

        jQuery.validator.addMethod("lettersonly", function(value, element) {
            return this.optional(element) || /^[a-z\s]+$/i.test(value);
            }, "Please enter only alphabetical characters");


        $("#formsearch").validate({
          rules: {
            district_name: {
                lettersonly:true
            },
          },
        })

        window.checkField = function() {
            var x = document.getElementById("state_name").value;
            
            if(x === ""){
                var x = document.getElementById("district_name").value;
            }
            
            const error = document.getElementById('error');
            if (x === "") { 
                // $("#displayError").html = "Error"
                error.textContent = 'Please enter any data to search';
                // alert("Input your data to search");
                return false;
            } else {
                return true;
            }
        }
        $('#show_filter').click(function () {                
            if($("#show_filter_form").hasClass("d-none")){
                $("#show_filter_form").removeClass("d-none");
            } else {
                $("#show_filter_form").addClass("d-none");
            }                
        });

        $('#reset_btn').click(function () { 
            $('#search_data').trigger('reset');      
            window.location.href = "<?php echo base_url(); ?>district"           
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