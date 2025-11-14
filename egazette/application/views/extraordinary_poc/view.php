<?php defined('BASEPATH') OR exit('No direct script access allowed'); ?>
<style rel="stylesheet" nonce="8f0882ce3be14f201cadd0eff5726cbd">
	.left_class {
		float: left;
	}
	.hstry_class {
		padding: 4px 10px;
	}
</style>
<!-- CONTENT -->
<section id="content">
    <div class="page page-forms-validate">
        <!-- bradcome -->
        <ol class="breadcrumb">
            <li><a href="<?php echo base_url(); ?>applicants_login/dashboard">Dashboard</a></li>
            <li><a href="<?php echo base_url(); ?>extraordinary_poc/verifier_index">Extraordinary Gazette</a></li>
            <li class="active">Department Gazette Details</li>
        </ol>
        <!-- Modal -->
        <div class="modal fade" id="forward_reject" tabindex="-1" role="dialog" aria-labelledby="myModalLabel" aria-hidden="true" data-backdrop="false">

    <?php echo form_open('extraordinary_poc/forward_reject', array('class' => "form1 redd-btn", 'name' => "forward_form_pro", 'method' => "post", 'id' => 'forward_form_pro')); ?>
    
        <input type="hidden" name="gazette_id" id="gazette_id" value="<?php echo $details->id; ?>"/>
        <input type="hidden" name="curr_status" id="curr_status" value="<?php echo $details->status_id; ?>"/>
        <input type="hidden" name="next_status" id="next_status"/>
        
        <div class="modal-dialog modal-md">
            <div class="modal-content">
                <div class="modal-header">
                    <button type="button" class="close" data-dismiss="modal">&times;</button>
                    <h4 class="modal-title">Forward to Verifier</h4>
                    
                </div>
                <div class="modal-body">
                    <div class="form-group">
                        <label>Remark <span class="asterisk">*</span></label>
                        <textarea name="remarks" placeholder="Enter Remark" class="form-control remark_textarea" rows="6" required maxlength="500"></textarea>
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
            <!--<pre><?php // print_r($this->session->userdata()); print_r($details); ?></pre>-->
            <div class="col-md-12">
                <section class="boxs">
                    <?php if ($this->session->flashdata('success')) { ?>
                        <div class="alert alert-success alert-dismissable"><button aria-hidden="true" data-dismiss="alert" class="close" type="button">×</button><?php echo $this->session->flashdata('success'); ?></div>
                    <?php } ?>
                    <?php if ($this->session->flashdata('error')) { ?>
                        <div class="alert alert-danger alert-dismissable"><button aria-hidden="true" data-dismiss="alert" class="close" type="button">×</button><?php echo $this->session->flashdata('error'); ?></div>
                    <?php } ?>
                    <div class="boxs-header">
                        <h3 class="custom-font hb-blue"><strong>Department Gazette Details</strong></h3>
                        <div class="action_btn">
                            
                            <?php if ($this->session->userdata('is_verifier_approver') == 'Processor') { ?>

                                <?php if (in_array($details->status_id, array(1, 4))) { ?>

                                    <button class="btn btn-raised btn-success forward_reject edit_btn" data-toggle="modal" data-target="#forward_reject" id="<?php echo $details->status_id ?>" data-next="8">Forward</button>

                                    <!-- <button class="btn btn-raised btn-danger forward_reject reject_btn" data-toggle="modal" data-target="#forward_reject" id="<?php echo $details->status_id ?>" data-next="11">Reject</button> -->
                                    
                                    <button class="btn btn-raised btn-success forward_reject edit_btn" data-toggle="modal" data-target="#forward_reject" id="<?php echo $details->status_id ?>" data-next="11">Return To Dept.</button>

                                <?php } else if (in_array($details->status_id, array(14, 15))) { ?>
                                    
                                    <!-- <button class="btn btn-raised btn-success forward_reject edit_btn" data-toggle="modal" data-target="#forward_reject" id="<?php echo $details->status_id ?>" data-next="16">Return To Dept.</button> -->

                                <?php } ?>

                            <?php } else if ($this->session->userdata('is_verifier_approver') == 'Verifier') { ?>
                                
                                <?php if (in_array($details->status_id, array(8))) { ?>
                                    
                                    <button class="btn btn-raised btn-success forward_reject edit_btn" data-toggle="modal" data-target="#forward_reject" id="<?php echo $details->status_id ?>" data-next="9">Forward</button>

                                    <button class="btn btn-raised btn-success forward_reject edit_btn" data-toggle="modal" data-target="#forward_reject" id="<?php echo $details->status_id ?>" data-next="12">Return To Dept.</button>

                                    <!-- <button class="btn btn-raised btn-danger forward_reject reject_btn" data-toggle="modal" data-target="#forward_reject" id="<?php echo $details->status_id ?>" data-next="12">Reject</button> -->
                                    
                                <?php } ?>
                                    
                            <?php } else if ($this->session->userdata('is_verifier_approver') == 'Approver') { ?>
                                
                                <?php if (in_array($details->status_id, array(9))) { ?>
                                    
                                    <button class="btn btn-raised btn-success forward_reject edit_btn" data-toggle="modal" data-target="#forward_reject" id="<?php echo $details->status_id ?>" data-next="10">Forward</button>

                                    <button class="btn btn-raised btn-success forward_reject edit_btn" data-toggle="modal" data-target="#forward_reject" id="<?php echo $details->status_id ?>" data-next="13">Return To Dept.</button>

                                    <button class="btn btn-raised btn-danger forward_reject reject_btn" data-toggle="modal" data-target="#forward_reject" id="<?php echo $details->status_id ?>" data-next="14">Reject</button>
                                    
                                <?php } else if (in_array($details->status_id, array(11, 12))) { ?>
                                    
                                    <!-- <button class="btn btn-raised btn-success forward_reject edit_btn" data-toggle="modal" data-target="#forward_reject" id="<?php echo $details->status_id ?>" data-next="<?php if ($details->status_id == 11) { echo '14'; } else if ($details->status_id == 12) { echo '15'; } ?>">Approve</button> -->
                                    
                                <?php } ?>
                                    
                            <?php } ?>
                            
                        </div>                        
                    </div>
                    <div class="clearfix"></div>
                    <div class="boxs-body">
                        <div class="border_data">
                            <?php if (!empty($details->sl_no) && !empty($details->letter_no)) { ?>
                                <div class="row">
                                    <div class="form-group col-md-6">
                                        <label for="username">Sl No : </label>
                                        <?php echo $details->sl_no; ?>
                                    </div>
                                    <div class="form-group col-md-6">
                                        <label for="email">Letter/Memo Number : </label>
                                        <?php echo $details->letter_no; ?>
                                    </div>
                                </div>
                            <?php } ?>
                            <div class="row">
                                <div class="form-group col-md-6">
                                    <label for="username">Department Name : </label>
                                    <?php echo $details->department_name; ?>
                                </div>
                                <div class="form-group col-md-6">
                                    <label for="email">Gazette Type : </label>
                                    <?php echo $details->gazette_type; ?>
                                </div>
                            </div>
                            <div class="row">
                                <div class="form-group col-md-6">
                                    <label for="username">Created User : </label>
                                    <?php echo $details->name; ?>
                                </div>
                                <div class="form-group col-md-6">
                                    <label for="email">Created Datetime : </label>
                                    <?php echo get_formatted_datetime($details->created_at); ?>
                                </div>
                            </div>
                            <div class="row">
                                <div class="form-group col-md-6">
                                    <label for="email">Subject : </label>
                                    <?php echo $details->subject; ?>
                                </div>
                                <div class="form-group col-md-6">
                                    <label for="username">Notification Type : </label>
                                    <?php echo strtoupper($details->notification_type); ?>
                                </div>
                            </div>
                            <div class="row">
                                <div class="form-group col-md-6">
                                    <label for="email">Notification Number : </label>
                                    <?php echo $details->notification_number; ?>
                                </div>
                                <?php if (!empty($details->issue_date)) { ?>
                                <div class="form-group col-md-6">
                                    <label for="username">Issue Date : </label>
                                    <?php echo $details->issue_date; ?>
                                </div>
                                <?php } ?>
                            </div>
                            <div class="row">
                                <div class="form-group col-md-6">
                                    <label for="username">Dept. Gazette (Signed PDF) : </label><br/>
                                    <a href="<?php echo base_url() . $details->dept_signed_pdf_path; ?>" target="_blank" class="file_icons"><i class="fa fa-file-pdf-o"></i></a>
                                </div>
                                <?php if (!empty($details->letter_no)) { ?>
                                    <div class="form-group col-md-6">
                                        <label for="username">SRO No : </label><br/>
                                        <?php echo $details->letter_no; ?>
                                    </div>
                                <?php } ?>
                            </div>
                            <div class="row">
                                <div class="form-group col-md-6">
                                    <label for="username">Status : </label>
                                    <?php echo $details->status_name; ?>
                                </div>
                                <?php if (!empty($details->reject_remarks)) { ?>
                                    <div class="form-group col-md-6">
                                        <label for="username">Reject Remarks : </label>
                                        <?php echo $details->reject_remarks; ?>
                                    </div>
                                <?php } ?>
                            </div>
                            <?php if (!empty($details->press_signed_pdf_path)) { ?>
                                <div class="row">
                                    <div class="form-group col-md-6">
                                        <label for="username">Press Gazette (Signed PDF) : </label><br/>
                                        <a href="<?php echo base_url() . $details->press_signed_pdf_path; ?>" target="_blank" class="file_icons"><i class="fa fa-file-pdf-o"></i></a>
                                    </div>
                                </div>
                            <?php } ?>
                        </div>
                        <div class="row">
                            
                        </div>
                        <br/>
                        <div class="row">
                            <div class="col-lg-12 col-md-12">
                                <h4 class="custom-font hb-cyan">Status History</h4>
                                <ul class="list-group">
                                    <?php if (!empty($status_list)) { ?>
                                        <?php foreach ($status_list as $status) { ?>
                                            <li class="list-group-item"><span class="badge bg-default hstry_class"><?php echo get_formatted_datetime($status->created_at); ?></span><?php 
                                            if ($status->remarks != '') {
                                                $remarks = " ( Remarks : " . $status->remarks . " )";
                                            } else {
                                                $remarks = '';
                                            }
                                            echo $status->status_name . $remarks; ?></li>
                                        <?php } ?>
                                    <?php } ?>
                                </ul>
                            </div>
                        </div>
                    </div>
                </section>
            </div>
        </div>
    </div>
</section>
<script src="https://code.jquery.com/jquery-1.12.4.min.js" type="text/javascript" nonce="a6b9a780936c8e980939086f618dded2"></script>
<script type="text/javascript" nonce="a6b9a780936c8e980939086f618dded2">
    $(document).ready(function () {
        $(".forward_reject").on('click', function () {
            var curr_status = $(this).attr('id');
            var next_status = $(this).data('next');
            
            $("#next_status").val(next_status);
            
            if (next_status == 8) {
                $(".modal-title").html('Forward to Verifier');
            } else if (next_status == 11 || next_status == 12 || next_status == 13) {
                // $(".modal-title").html('Reject');
                $(".modal-title").html('Returned To Department');
            } else if (next_status == 9) {
                $(".modal-title").html('Forward to Approver');
            } else if (next_status == 10) {
                $(".modal-title").html('Forward to Govt. Press');
            } else if (next_status == 14 || next_status == 15) {
                $(".modal-title").html('Approve Reject Request');
            } else if (next_status == 16) {
                $(".modal-title").html('Returned To Department');
            }
        });
        
        $("#forward_form_pro").validate ({
            rules : {
                remarks: {
                    required: true,
                    minlength: 5,
                    maxlength: 500
                }
            },
            messages: {
                remarks: {
                    required: "Please enter remarks",
                    minlength: "Remarks should be atleast 5 characters",
                    maxlength: "Remarks should be maximum 500 characters"
                }
            }
        });
    });
</script>
<style rel="stylesheet" nonce="8f0882ce3be14f201cadd0eff5726cbd">
    .left_txt {
        float: left;
    }
</style>

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