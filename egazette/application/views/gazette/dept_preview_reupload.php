<?php defined('BASEPATH') OR exit('No direct script access allowed'); ?>
<style nonce="8f0882ce3be14f201cadd0eff5726cbd">
    .upload_help {
        margin-top: -5px;
    }
    .loader{
        position: fixed;
        left: 0px;
        top: 0px;
        width: 100%;
        height: 100%;
        z-index: 9999;
        display: none;
        background: url('<?php echo base_url(); ?>assets/images/loader.gif') 50% 50% no-repeat rgb(249,249,249);
    }
</style>
<div class="loader"></div>
<!-- CONTENT -->
<section id="content">
    <div class="page page-forms-validate">
        <!-- bradcome -->
        <ol class="breadcrumb">
            <li><a href="<?php echo base_url(); ?>dashboard">Dashboard</a></li>
            <li><a href="<?php echo base_url(); ?>gazette">Extraordinary Gazette</a></li>
            <li class="active">Reupload Extraordinary Gazette</li>
        </ol>
        <!-- <div class="bg-light lter b-b wrapper-md mb-10">
            <div class="row">
                <div class="col-sm-6 col-xs-12">
                    <h1 class="h3 m-0">Reupload Gazette</h1>
                </div>
            </div>
        </div> -->
        <!-- row -->
        <div class="row">
            <div class="col-md-12">
                <section class="boxs">
                    <div class="boxs-header">
                        <h3 class="custom-font hb-blue"><strong>Reupload Extraordinary Gazette</strong></h3>
                        <?php if (($details->status_id == 2 || $details->status_id == 7 || $details->status_id == 22)) { ?>
                            <div class="action_btn">
                                <a href="<?php echo base_url(); ?>gazette/dept_preview/<?php echo $details->id; ?>" class="btn btn-raised btn-success edit_btn pull-right">Submit</a>
                            </div>
                        <?php } ?>
                        <?php if ($this->session->flashdata('success')) { ?>
                            <div class="alert alert-success alert-dismissable"><button aria-hidden="true" data-dismiss="alert" class="close" type="button">×</button><?php echo $this->session->flashdata('success'); ?></div>
                        <?php } ?>
                        <?php if ($this->session->flashdata('error')) { ?>
                            <div class="alert alert-danger alert-dismissable"><button aria-hidden="true" data-dismiss="alert" class="close" type="button">×</button><?php echo $this->session->flashdata('error'); ?></div>
                        <?php } ?>
                    </div>
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

                        <?php if ($details->sro_available == 1) { ?>
                            <div class="row">
                                <?php if ($details->sro_available == 1) { ?>
                                    <div class="form-group col-md-6">    
                                            <label for="username">SRO Available : </label>
                                            Yes
                                    </div>
                                <?php } ?>
                                <?php if (!empty($details->sl_no) && !empty($details->letter_no)) { ?>
                                    <div class="form-group col-md-6">
                                        <label for="username">SRO No : </label>
                                        <?php echo $details->letter_no; ?>
                                    </div>
                                <?php } ?>
                            </div>
                        <?php } ?>

                        <div class="row">
                            <div class="form-group col-md-6">
                                <label for="username">Dept. Gazette (Official Copy) : </label><br/>
                                <a href="<?php echo base_url() . $details->dept_pdf_file_path; ?>" target="_blank" class="file_icons"><i class="fa fa-file-pdf-o"></i></a>
                            </div>
                        </div>
                        <?php if ($details->status_id == 2 || $details->status_id == 22) { ?>
                            <div class="row">
                                <div class="boxs-header">
                                    <h3 class="custom-font hb-cyan">Reupload Gazette</h3>
                                </div>
                                <?php echo validation_errors(); ?>
                                <?php echo form_open('gazette/reupload_save_gazette', array('class' => "form1", 'id' => "re_upload_ex_save_gazette", 'name' => "form1", 'method' => "post", 'enctype' => "multipart/form-data")); ?>
                                    <input type="hidden" name="gazette_id" value="<?php echo $details->gazette_id; ?>"/>
                                    <div class="boxs-body">
                                        <div class="row">
                                            <div class="form-group col-md-6">
                                                <label for="email">Gazette (Official Copy): <span class="asterisk">*</span>
                                                    <span class="file_icons_add">
                                                        <a href="<?php echo base_url() . "assets/pdf/extra-ordinary_dept_word_fomat.pdf";?>" target="_blank"><i class="fa fa-file-word-o"></i></a>
                                                    </span>
                                                </label>
                                                <div class="row fileupload-buttonbar clearfix">
                                                    <div class="col-lg-10">
                                                        <span class="btn btn-raised btn-success fileinput-button">
                                                            <i class="glyphicon glyphicon-plus"></i>
                                                            <span>Choose File</span>
                                                            <input type="file" name="doc_files" required=""/>
                                                        </span>
                                                        <span class="files"></span>
                                                    </div>
                                                </div>
                                                <label id="doc_files-error" class="error" for="doc_files"></label>
                                                <span class="help-block mb-0">Maximum 30 MB allowed.</span>
                                                <div class="clearfix"></div>
                                                <?php if (form_error('doc_files')) { ?>
                                                    <span class="error"><?php echo form_error('doc_files'); ?></span>
                                                <?php } ?>
                                            </div>
                                        </div>
                                    </div>
                                    <div class="boxs-footer text-right bg-tr-black lter dvd dvd-top">
                                        <button type="submit" class="btn btn-raised btn-success" id="form4Submit">Reupload</button>
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
<script src="https://code.jquery.com/jquery-1.12.4.min.js" type="text/javascript" nonce="a6b9a780936c8e980939086f618dded2"></script>
<script src="https://cdnjs.cloudflare.com/ajax/libs/pdfobject/2.1.1/pdfobject.min.js" type="text/javascript" nonce="a6b9a780936c8e980939086f618dded2"></script>
<script type="text/javascript" nonce="a6b9a780936c8e980939086f618dded2">
    $(document).ready(function(){
        // Hide loader
        $(".loader").hide();
        // validator file size to be in MB
        $.validator.addMethod('filesize', function (value, element, param) {
            return this.optional(element) || (element.files[0].size <= param * 1000000)
        }, 'File size must be less than {0} MB');
        
        // Extraordinary gazette form
        $("#re_upload_ex_save_gazette").validate({
            rules: {
                doc_files: {
                    required: true,
                    extension: "docx",
                    filesize: 30
                }
            },
            messages: {
                doc_files: {
                    required: "Please upload document",
                    extension: "Only .docx allowed",
                    filesize: "Maximum 30 MB allowed"
                }
            },
            submitHandler: function (form, event) {
                $("#form4Submit").attr("disabled", true);
                $(".loader").show();
                form.submit();
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