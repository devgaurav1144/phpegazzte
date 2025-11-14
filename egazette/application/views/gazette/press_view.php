<?php defined('BASEPATH') OR exit('No direct script access allowed'); ?>
<style rel="stylesheet" nonce="8f0882ce3be14f201cadd0eff5726cbd">
	.left_class {
		float: left;
	}
	.hstry_class {
		padding: 4px 10px;
	}
    .mr-15 {
        margin-right: 15px;
    }
</style>
<!-- CONTENT -->
<section id="content">
    <div class="page page-forms-validate">
        <!-- bradcome -->
        <ol class="breadcrumb">
            <li><a href="<?php echo base_url(); ?>dashboard">Dashboard</a></li>
            <li><a href="<?php echo base_url(); ?>gazette">Extraordinary Gazette</a></li>
            <li class="active">View Extraordinary Gazette</li>
        </ol>
        <!-- bradcome -->
        <!-- Modal -->
        <div class="modal fade" id="myModal3" tabindex="-1" role="dialog" aria-labelledby="myModalLabel" aria-hidden="true" data-backdrop="false">
            
            <?php echo form_open('gazette/reject_gazette', array('class' => "form1", 'name' => "form1", 'method' => "post")); ?>    
                <input type="hidden" name="gazette_id" id="gazette_id" value="<?php echo $details->gazette_id; ?>"/>
                <div class="modal-dialog modal-md">
                    <div class="modal-content">
                        <div class="modal-header">
                            <button type="button" class="close" data-dismiss="modal">&times;</button>
                            <h4 class="modal-title">Resubmit Gazette to Department</h4>
                        </div>
                        <div class="modal-body">
                            <div class="form-group">
                                <label>Remark<span class="asterisk">*</span></label>
                                <textarea name="reject_remarks" placeholder="Enter Remark" class="form-control remark_textarea" rows="6" required></textarea>
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

                    <div class="boxs-header">
                        <div class="col-md-8">
                            <h3 class="custom-font hb-blue" class="left_class"><strong>Extraordinary Gazette Details</strong></h3>
                        </div>
                        <div class="col-md-4">
                            <?php if (($details->status_id < 5)) { ?>
                            <div class="" style="float: right;">
                                <div class="row">
                                    <?php if ($details->status_id == 1) { ?>
                                        <div class="col-md-6">
                                            <button class="btn btn-raised btn-danger reject_btn mr-15" data-toggle="modal" data-target="#myModal3">Resubmit</button> 
                                        </div>
                                    <?php } ?>
                                    <?php if (($details->status_id == 1) || ($details->status_id == 4)) { ?>
                                        <div class="col-md-6">
                                            <?php echo form_open('gazette/press_add', array('class' => "form1 redd-btn", 'name' => "edit_form", 'method' => "post")); ?>
                                                <input type="hidden" name="gazette_id" id="gazette_id" value="<?php echo $details->id; ?>"/>
                                                <button type="submit" class="btn btn-raised btn-success edit_btn">Approve</button>
                                            <?php echo form_close(); ?>
                                        </div>
                                    <?php } ?>
                                </div>
                            </div>
                            <?php } ?>
                            <?php if (($details->status_id == 6)) { ?>
                                <div class="action_btn">
                                    <a href="<?php echo base_url(); ?>gazette/press_publish_view/<?php echo $details->id; ?>" class="btn btn-raised btn-success edit_btn pull-right">Publish</a>
                                </div>
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
                                    <label for="email">Payment Type : </label>
                                    <?php
                                    if ($details->is_paid == 0) {
                                        echo 'Free';
                                    } else {
                                        echo 'Payment of Cost';
                                    }
                                    ?>
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
                                <?php if ($details->sro_available == 1) { ?>
                                    <div class="form-group col-md-6">
                                        <label for="username">SRO Available : </label>
                                        Yes
                                    </div>
                                <?php } ?>
                                <?php if (!empty($details->letter_no)) { ?>
                                    <div class="form-group col-md-6">
                                        <label for="username">SRO No : </label>
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
                            <div class="row">
                                <div class="form-group col-md-6">
                                    <label for="username">Dept. Gazette (Signed PDF) : </label><br/>
                                    <a href="<?php echo base_url() . $details->dept_signed_pdf_path; ?>" target="_blank" class="file_icons"><i class="fa fa-file-pdf-o"></i></a>
                                </div>
                                <?php if (!empty($details->press_signed_pdf_path)) { ?>
                                    <div class="form-group col-md-6">
                                        <label for="username">Press Gazette (Signed PDF) : </label><br/>
                                        <a href="<?php echo base_url() . $details->press_signed_pdf_path; ?>" target="_blank" class="file_icons"><i class="fa fa-file-pdf-o"></i></a>
                                    </div>
                                <?php } ?>
                            </div>
                        </div>
                        <br/>
                        <div class="row">
                            <div class="col-lg-12 col-md-12">
                                <h4 class="custom-font hb-cyan">Status History</h4>
                                <ul class="list-group">
                                    <?php if (!empty($status_list)) { ?>
                                        <?php foreach ($status_list as $status) { ?>
                                            <li class="list-group-item"><span class="badge bg-default hstry_class"><?php echo get_formatted_datetime($status->created_at); ?></span><?php echo $status->status_name; ?></li>
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