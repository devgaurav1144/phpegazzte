<?php defined('BASEPATH') OR exit('No direct script access allowed'); ?>
<!--  CONTENT  -->
<section id="content">
    <div class="page page-tables-footable">
        <ol class="breadcrumb">
            <li><a href="<?php echo base_url(); ?>dashboard">Dashboard</a></li>
            <li class="active">Nodal Officers</li>
        </ol>
        <!-- bradcome -->

        <?php
            $dept = "";

            if(isset($inputs['dept'])){
                $dept = $inputs['dept'];
            }

            if(!empty($dept)){
                $class= "";
            }else{
                $class= "d-none";
            }
        ?>
        
        <!-- Modal -->
        <div class="modal fade" id="myModal3" tabindex="-1" role="dialog" aria-labelledby="myModalLabel" aria-hidden="true" data-backdrop="false">
            
            <?php echo form_open('user/account_reject', array('class' => "form1", 'name' => "form1", 'method' => "post")); ?>
                <input type="hidden" name="user_id" id="user_id" value=""/>
                <div class="modal-dialog modal-md">
                    <div class="modal-content">
                        <div class="modal-header">
                            <button type="button" class="close" data-dismiss="modal">&times;</button>
                            <h4 class="modal-title">Reject Nodal Officer Account</h4>
                        </div>
                        <div class="modal-body">
                            <div class="form-group">
                                <label>Remark<span class="asterisk">*</span></label>
                                <textarea name="remarks" placeholder="Enter Remark" class="form-control remark_textarea" rows="6" required></textarea>
                            </div>
                        </div>
                        <div class="modal-footer">
                            <button type="submit" class="btn btn-raised btn-success">Submit</button>
                        </div>
                    </div>
                </div>
            <?php echo form_close(); ?>
        </div>
        <!-- Modal -->
        
        <!-- row -->
        <div class="row">
            <div class="col-md-12">

                <section class="boxs box_report <?php echo $class?>" id="show_filter_form">
                    <div class="boxs-header">
                        <h3 class="custom-font hb-blue"><strong>Filter</strong></h3>
                    </div>
                    <div class="boxs-body">
                        <?php echo form_open('user/users', array('method' => 'post', 'id'=>'search_data', 'autocomplete' => 'off')); ?>
                        <div class="row">
                            
                            <?php if ($this->session->userdata('is_admin')) { ?>
                                <div class="col-md-6">
                                    <div class="form-group">    
                                        <label for="filter" class="label_txt">Department</label>
                                        <select class="form-control" name="dept" id="dept">
                                            <option value="">Select Department</option>
                                            <?php foreach ($department_type as $departmentValue) { ?>
                                                <option value="<?php echo $departmentValue->id; ?>" <?php
                                                        
                                                        if ($dept == $departmentValue->id) {
                                                            echo "selected";
                                                        }
                                                        ?>><?php echo $departmentValue->department_name; ?></option>
                                                    <?php } ?>
                                        </select>
                                    </div>
                                </div>
                            <?php }?>
                            <div class="clearfix"></div>
                            <div class="col-md-6">
                                <?php if ($this->session->userdata('is_admin')) { ?>
                                    <input type="submit" value="Search" name="" class="btn-bg btn btn-success" id="search_btn_admin">
                                <?php }?>
                                <?php if (!$this->session->userdata('is_admin')) { ?>
                                    <input type="submit" value="Search" name="" class="btn-bg btn btn-success" id="search_btn">
                                <?php }?>
                                <input type="button" id="reset_btn" value="Reset" name="" class="btn-bg btn btn-primary">
                                <span id="error"></span>
                            </div>
                            <?php echo form_close(); ?>
                        </div>                            
                    </div>
                </section> 

                <section class="boxs">
                    <div class="boxs-header filter-with-addd">
                        <h3 class="custom-font hb-blue"><strong>Nodal Officers</strong></h3>
                        <button class="btn filter-margin btn-success btn-raised btn-fab btn-fab-mini btn-round" id="show_filter" title="Filter"><i class="fa fa-filter"></i></button>

                            <a href="<?php echo base_url(); ?>user/add_nodal_officers" class="btn-bg btn btn-success btn-right-align">Add</a>
                    </div>
                    <div class="boxs-body">
                        <?php if ($this->session->flashdata('success')) { ?>
                            <div class="alert alert-success alert-dismissable"><button aria-hidden="true" data-dismiss="alert" class="close" type="button">×</button><?php echo $this->session->flashdata('success'); ?></div>
                        <?php } ?>
                        <?php if ($this->session->flashdata('error')) { ?>
                            <div class="alert alert-danger alert-dismissable"><button aria-hidden="true" data-dismiss="alert" class="close" type="button">×</button><?php echo $this->session->flashdata('error'); ?></div>
                        <?php } ?>
                        
                        <!-- <div class="form-group">
                            <label for="filter" style="padding-top: 5px">Search:</label>
                            <input id="filter" type="text" class="form-control rounded w-md mb-10 inline-block" />
                        </div>-->
                        <table id="searchTextResults" data-filter="#filter" data-page-size="5" class="footable table table-custom">
                            <thead>
                                <tr>
                                    <th>Sl No</th>
                                    <th>Name</th>
                                    <!-- <th>Designation</th> -->
                                    <th>Department</th>
                                    <th>Email</th>
                                    <th>Status</th>
                                    <th>Action</th>
                                </tr>
                            </thead>
                            <tbody>
                                <?php if (!empty($nodal_officers)) { ?>
                                    <?php 
                                        if ($this->my_pagination->total_rows > $this->my_pagination->per_page) {
                                            $cntr = 1 + ($this->my_pagination->cur_page - 1) * $this->my_pagination->per_page;
                                        } else {
                                            $cntr = 1;
                                        }
                                    ?>
                                        <?php foreach ($nodal_officers as $user) { ?>
                                        <tr>
                                            <td><?php echo $cntr; ?></td>
                                            <td><?php echo $user->name; ?></td>
                                            <!-- <td><?php //echo $user->designation; ?></td> -->
                                            <td><?php echo $user->department_name; ?></td>
                                            <td><?php echo $user->email; ?></td>
                                            <td>
                                                <div class="togglebutton" name ="<?php echo $user->dept_id; ?>" id="<?php echo $user->id; ?>" title="<?php echo $user->status;?>">
                                                    <label><input type="checkbox" class="update_account_status" <?php echo ($user->status == 1) ? "checked" : ""; ?>></label>
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
                                                        <li><a href="javascript:void(0);" data-toggle="modal" data-target="#myModal3" id="<?php echo $user->id; ?>" class="reject_account">Reject</a></li>
                                                        <li><a href="<?php echo base_url(); ?>user/edit/<?php echo $user->id; ?>">Edit</a></li>
                                                        <li><a href="<?php echo base_url(); ?>user/mail_password/<?php echo $user->id; ?>">SMS Password</a></li>
                                                        <li class="delete_id" id="<?php echo $user->id; ?>"><a href="javascript:void(0);">Delete<div class="ripple-container"></div></a></li>
                                                    </ul>
                                                </div>
                                            </td>
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

    <!-- Status Change Modal -->
    <div class="modal" id="change_status" tabindex="-1" role="dialog">
        <div class="modal-dialog modal-sm" role="document">
            <div class="modal-content">
                <!-- <div class="modal-header">
                    <h5 class="modal-title">Alert</h5>
                    <button type="button" class="close" data-dismiss="modal" aria-label="Close">
                    <span aria-hidden="true">&times;</span>
                    </button>
                </div> -->
                <?php echo form_open('user/account_approve', array('method' => 'post', 'id'=>'users_status')); ?>
                <div class="modal-body">
                    <p>Are you sure to update the status</p>
                    <input type="hidden" name="user_id" id="id">
                    <input type="hidden" name="status" id="status">
                    <input type="hidden" name="dept_id" id="dept_id">
                </div>
                <div class="modal-footer">
                    <button type="submit" class="btn btn-primary">Yes</button>
                    <button type="button" class="btn btn-secondary cancel" data-dismiss="modal">No</button>
                </div>
                <?php echo form_close(); ?>
            </div>
        </div>
    </div>
</section>
<!--/ CONTENT -->
<script src="<?php echo base_url(); ?>/assets/js/vendor/jquery/jquery-3.1.0.min.js" type="text/javascript" nonce="a6b9a780936c8e980939086f618dded2"></script>
<script type="text/javascript" nonce="a6b9a780936c8e980939086f618dded2">
    $(document).ready(function(){
        $('.delete_id').on('click', function(){
            var id = $(this).attr('id');
            
            if (confirm('Are you sure to delete the user')) {
                // make ajax request
                $.ajax({
                    type: "post",
                    url: '<?php echo base_url(); ?>' + "user/delete",
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
            }
        });

        $('.cancel').on('click',function(){
            $('#change_status').hide();
        });

        $('#show_filter').click(function () {                
            if($("#show_filter_form").hasClass("d-none")){
                $("#show_filter_form").removeClass("d-none");
            } else {
                $("#show_filter_form").addClass("d-none");
            }                
        });

        $('#reset_btn').click(function () { 
            $('#search_data').trigger('reset');      
            window.location.href = "<?php echo base_url(); ?>user/users"           
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