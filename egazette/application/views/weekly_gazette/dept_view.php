<?php defined('BASEPATH') OR exit('No direct script access allowed'); ?>
<!-- CONTENT -->
<section id="content">
    <div class="page page-forms-validate">
        <!-- bradcome -->
        <ol class="breadcrumb">
            <li><a href="<?php echo base_url(); ?>dashboard">Dashboard</a></li>
            <li><a href="<?php echo base_url(); ?>weekly_gazette">Weekly Gazette</a></li>
            <li class="active">View Details</li>
        </ol>
        <!-- row -->
        <div class="row">
            <div class="col-md-12">
                <section class="boxs">
                    <div class="boxs-header">
                        <h3 class="custom-font hb-blue"><Strong>Department Weekly Gazette Details</strong></h3>
                        <?php if (($details->status_id == 2) || ($details->status_id == 7) || ($details->status_id == 22)) { ?>
                            <div class="action_btn">
                                <a href="<?php echo base_url(); ?>weekly_gazette/dept_preview/<?php echo $details->gazette_id; ?>" class="btn btn-raised btn-success edit_btn pull-right">Submit</a>
                            </div>
                        <?php } ?>
                    </div>
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
                            </div>
                            <div class="row">
                                <div class="form-group col-md-6">
                                    <label for="username">Dept. Gazette (Official Copy) : </label><br/>
                                    <a href="<?php echo base_url() . $details->dept_pdf_file_path; ?>" target="_blank" class="file_icons"><i class="fa fa-file-pdf-o"></i></a>
                                </div>
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
                        </div>
                        <br/>
                        <div class="row">
                            <div class="col-lg-12 col-md-12">
                                <h4 class="custom-font hb-cyan">Status History</h4>
                                <ul class="list-group">
                                    <?php if (!empty($status_list)) { ?>
                                        <?php foreach ($status_list as $status) { ?>
                                    <li class="list-group-item"><span class="badge bg-default"><?php echo get_formatted_datetime($status->created_at); ?></span><?php echo $status->status_name; ?></li>
                                        <?php } ?>
                                    <?php } ?>
                                </ul>
                            </div>
                        </div>
                        <!-- Document History -->
                        <?php if (!empty($document_list)) { ?>
                            <div class="row">
                                <div class="col-lg-12 col-md-12">
                                    <h4 class="custom-font hb-cyan">Document History</h4>
                                    <ul class="list-group">
                                        <?php foreach ($document_list as $document) { ?>
                                            <li class="list-group-item">
                                                <span class="badge bg-default"><?php echo get_formatted_datetime($document->created_at); ?></span>
                                                <a href="<?php echo base_url() . $document->pdf_file_path; ?>" target="_blank" class="file_icons"><i class="fa fa-file-pdf-o"></i></a>
                                            </li>
                                        <?php } ?>
                                    </ul>
                                </div>
                            </div>
                        <?php } ?>
                        <?php if ($details->status_id == 3) { ?>
                        <div class="row">
                            <div class="boxs-header">
                                <h3 class="custom-font hb-cyan">Re-Submit Gazette</h3>
                            </div>
                            <?php echo validation_errors(); ?>
                            
                            <?php echo form_open('weekly_gazette/resubmit_save_gazette', array('class' => "form1", 'name' => "form1", 'method' => "post", 'enctype' => "multipart/form-data")); ?>
                            
                                <input type="hidden" name="gazette_id" value="<?php echo $details->gazette_id; ?>"/>
                                <input type="hidden" name="part_id" value="<?php echo $details->part_id; ?>"/>
                                <input type="hidden" name="dept_id" value="<?php echo $details->dept_id; ?>"/>
                                <input type="hidden" name="user_id" value="<?php echo $details->user_id; ?>"/>
                                <div class="boxs-body">
                                    <div class="row">
                                        <div class="form-group col-md-6">
                                            <label for="email">Gazette (Official Copy): <span class="asterisk">*</span>
                                                <span class="file_icons_add">
                                                    <a href="<?php echo base_url() . "assets/pdf/extra-ordinary_dept_word_fomat.pdf";?>" target="_blank"><i class="fa fa-file-word-o"></i></a>
                                                </span>
                                            </label>
                                            <div class="row fileupload-buttonbar">
                                                <div class="col-lg-10">
                                                    <span class="btn btn-raised btn-success fileinput-button">
                                                        <i class="glyphicon glyphicon-plus"></i>
                                                        <span>Choose File</span>
                                                        <input type="file" name="doc_files" accept=".docx" required="">
                                                    </span>
                                                    <span class="fileupload-process"></span>
                                                    <span class="files"></span>
                                                </div>
                                            </div>
                                            <span class="help-block mb-0">Maximum 5 MB allowed.</span>
                                        </div>
                                    </div>
                                </div>
                                <div class="boxs-footer text-right bg-tr-black lter dvd dvd-top">
                                    <button type="submit" class="btn btn-raised btn-success" id="form4Submit">Resubmit</button>
                                </div>
                            <?php echo form_close(); ?>
                        </div>
                        <?php } ?>
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