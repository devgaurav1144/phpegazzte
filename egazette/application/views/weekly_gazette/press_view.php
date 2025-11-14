<?php defined('BASEPATH') OR exit('No direct script access allowed'); ?>
<style rel="stylesheet" nonce="8f0882ce3be14f201cadd0eff5726cbd">
	.left_txt {
		float: left;
	}
	.time_display {
		padding: 4px 10px;
	}
</style>
<!-- CONTENT -->
<section id="content">
    <div class="page page-forms-validate">
        <!-- bradcome -->
        <ol class="breadcrumb">
            <li><a href="<?php echo base_url(); ?>dashboard">Dashboard</a></li>
            <li><a href="<?php echo base_url(); ?>weekly_gazette">Weekly Gazette</a></li>
            <li class="active">View Weekly Gazette Details</li>
        </ol>

        <!-- Modal -->
        <div class="modal fade" id="myModal3" tabindex="-1" role="dialog" aria-labelledby="myModalLabel" aria-hidden="true" data-backdrop="false">
            
            <?php echo form_open('weekly_gazette/reject_gazette', array('class' => "form1", 'name' => "form1", 'method' => "post")); ?>    
                <input type="hidden" name="gazette_id" id="gazette_id" value="<?php echo $details->gazette_id; ?>"/>
                <input type="hidden" name="dept_id" id="dept_id" value="<?php echo $details->dept_id; ?>"/>
                <input type="hidden" name="part_id" id="part_id" value="<?php echo $details->part_id; ?>"/>
                <input type="hidden" name="user_id" id="user_id" value="<?php echo $details->user_id; ?>"/>
                <div class="modal-dialog modal-md">
                    <div class="modal-content">
                        <div class="modal-header">
                            <button type="button" class="close" data-dismiss="modal">&times;</button>
                            <h4 class="modal-title">Resubmit Gazette to Department.</h4>
                        </div>
                        <div class="modal-body">
                            <div class="form-group">
                                <label>Remark<span class="asterisk">*</span></label>
                                <textarea name="reject_remarks" placeholder="Enter Remark" class="form-control remark_textarea" required rows="6"></textarea>
                                <?php if (form_error('reject_remarks')) { ?>
                                    <span class="error"><?php echo form_error('reject_remarks'); ?></span>
                                <?php } ?>
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
                <section class="boxs">
                    <?php if ($this->session->flashdata('success')) { ?>
                        <div class="alert alert-success alert-dismissable"><button aria-hidden="true" data-dismiss="alert" class="close" type="button">×</button><?php echo $this->session->flashdata('success'); ?></div>
                    <?php } ?>
                    <?php if ($this->session->flashdata('error')) { ?>
                        <div class="alert alert-danger alert-dismissable"><button aria-hidden="true" data-dismiss="alert" class="close" type="button">×</button><?php echo $this->session->flashdata('error'); ?></div>
                    <?php } ?>
                    <!-- <div class="boxs-header">
                        <h3 class="custom-font hb-blue"><strong>Weekly Gazette Details</strong></h3>
                        <?php if (($details->status_id < 5)) { ?>
                            <div class="action_btn">
                                <?php if ($details->status_id == 1) { ?>
                                    <button class="btn btn-raised btn-danger reject_btn" data-toggle="modal" data-target="#myModal3">Resubmit</button>
                                <?php } ?>
                                <?php if (($details->status_id == 1) || ($details->status_id == 4)) { ?>
                                    <?php echo form_open('weekly_gazette/save_press_gazette_part', array('class' => "form1 redd-btn", 'name' => "edit_form", 'method' => "post")); ?>    
                                        <input type="hidden" name="dept_part_id" id="dept_part_id" value="<?php echo $details->dept_part_id; ?>"/>
                                        <input type="hidden" name="gazette_id" id="gazette_id" value="<?php echo $details->gazette_id; ?>"/>
                                        <input type="hidden" name="dept_id" id="dept_id" value="<?php echo $details->dept_id; ?>"/>
                                        <input type="hidden" name="part_id" id="part_id" value="<?php echo $details->part_id; ?>"/>
                                        <input type="hidden" name="user_id" id="user_id" value="<?php echo $details->user_id; ?>"/>
                                        <button type="submit" class="btn btn-raised btn-success edit_btn">Approve</button>
                                    <?php echo form_close(); ?>
                                <?php } ?>
                            </div>
                        <?php } ?>
                    </div> -->
                    <div class="boxs-header">
                        <div class="col-md-8">
                            <h3 class="custom-font hb-blue"><strong>Weekly Gazette Details</strong></h3>
                        </div>
                        <div class="col-md-4">
                            <?php if (($details->status_id < 5)) { ?>
                            <div class="" style="float: right;">
                                <div class="row">
                                    <?php if ($details->status_id == 1) { ?>
                                        <div class="col-md-6">
                                                <button class="btn btn-raised btn-danger reject_btn" data-toggle="modal" data-target="#myModal3">Resubmit</button>
                                        </div>
                                    <?php } ?>
                                    <?php if (($details->status_id == 1) || ($details->status_id == 4)) { ?>
                                        <div class="col-md-6">
                                            <?php //echo "Testing View"; ?>
                                            <?php echo form_open('weekly_gazette/save_press_gazette_part',array('class' => "form1 redd-btn",'name' => "edit_form" ,'method' => "post")); ?>
                                                <input type="hidden" name="dept_part_id" id="dept_part_id" value="<?php echo $details->dept_part_id;?>"/>
                                                <input type="hidden" name="gazette_id" id="gazette_id" value="<?php echo $details->gazette_id;?>"/>
                                                <input type="hidden" name="dept_id" id="dept_id" value="<?php echo $details->dept_id;?>"/>
                                                <input type="hidden" name="part_id" id="part_id" value="<?php echo $details->part_id;?>"/>
                                                <input type="hidden" name="user_id" id="user_id" value="<?php echo $details->user_id;?>"/>
                                                <button type="submit" class="btn btn-raised btn-success edit_btn">Approve</button>
                                            <?php echo form_close(); ?>
                                        </div>
                                    <?php } ?>
                                </div>
                            </div>
                            <?php } ?>
                        </div>
                    </div>
                    <div class="clearfix"></div>
                    <div class="boxs-body">
                        <div class="border_data">
                            <div class="row">
                                <div class="form-group col-md-6">
                                    <label for="username">Year : </label>
                                    <?php echo $details->year; ?>
                                </div>
                                <div class="form-group col-md-6">
                                    <label for="email">Week : </label>
                                    <?php echo $details->week; ?>
                                </div>
                            </div>
                            <div class="row">
                                <div class="form-group col-md-6">
                                    <label for="username">Part No : </label>
                                    <?php echo $details->part_name; ?>
                                </div>
                                <div class="form-group col-md-6">
                                    <label for="email">Section : </label>
                                    <?php echo $details->section_name; ?>
                                </div>
                            </div>
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
                                <!-- <div class="form-group col-md-6">
                                    <label for="username">Week : </label>
                                    <?php echo $details->week; ?>
                                </div> -->
                            </div>
                            <div class="row">
                                <div class="form-group col-md-6">
                                    <label for="username">Dept. Gazette (Signed PDF) : </label><br/>
                                    <a href="<?php echo base_url() . $details->dept_pdf_file_path; ?>" target="_blank" class="file_icons"><i class="fa fa-file-pdf-o"></i></a>
                                </div>

                                <div class="form-group col-md-6">
                                    <label for="username">Status : </label>
                                    <?php echo $details->status_name; ?>
                                </div>
                            </div>

                            <div class="row">
                                
                                <?php if (!empty($details->reject_remarks)) { ?>
                                    <div class="form-group col-md-6">
                                        <label for="username">Reject Remarks : </label>
                                        <?php echo $details->reject_remarks; ?>
                                    </div>
                                <?php } ?>
                            </div>
                            
                            <div class="row">
                                <div class="pdf-container">
                                    <embed src="<?php echo base_url() . $details->dept_pdf_file_path; ?>" type="application/pdf" width="100%" height="650px" internalinstanceid="8">
                                </div>
                            </div>
                            
                            <?php if (!empty($details->press_pdf_file_path)) { ?>
                                <div class="row">
                                    <div class="form-group col-md-6">
                                        <label for="username">Press Gazette (Signed PDF) : </label><br/>
                                        <a href="<?php echo base_url() . $details->press_pdf_file_path; ?>" target="_blank" class="file_icons"><i class="fa fa-file-pdf-o"></i></a>
                                    </div>
                                </div>
                            <?php } ?>
                        </div>
                        <div class="row">
                            <div class="content-container">
                                <?php echo $details->content; ?>
                            </div>
                        </div>
                        <br/>
                        <div class="row">
                            <div class="col-lg-12 col-md-12">
                                <h4 class="custom-font hb-cyan">Status History</h4>
                                <ul class="list-group">
                                    <?php if (!empty($status_list)) { ?>
                                        <?php foreach ($status_list as $status) { ?>
                                            <li class="list-group-item"><span class="badge bg-default time_display"><?php echo get_formatted_datetime($status->created_at); ?></span><?php echo $status->status_name; ?></li>
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