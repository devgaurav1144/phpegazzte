<?php defined('BASEPATH') OR exit('No direct script access allowed'); ?>
<style rel="stylesheet" nonce="8f0882ce3be14f201cadd0eff5726cbd">
	.left_txt {
		float: left;
	}
    .align_left{
        text-align: left;
    }
    .align_left:color{
        color:  red;
    }
    .text-info {
        font-size: 12px;
    }
    .dept_sign_btn {
        display: none;
    }
</style>
<!-- CONTENT -->
<section id="content">
    <div class="page page-forms-validate">
        <!-- bradcome -->
        <!-- bradcome -->
        <ol class="breadcrumb">
            <li><a href="<?php echo base_url(); ?>dashboard">Dashboard</a></li>
            <li><a href="<?php echo base_url(); ?>weekly_gazette">Weekly Gazette</a></li>
            <li class="active">Preview Weekly Gazette</li>
        </ol>
        
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
                    <div class="boxs-header filter-with-add">
                        <h3 class="custom-font hb-blue"><strong>Preview Weekly Gazette</strong></h3>
                        <?php if (empty($details->dept_signed_pdf_path)) { ?>
                            <!-- Check that if the status is -->
                            <?php if ($details->status_id == 2 || $details->status_id == 22) { ?>
                                <div class="">
                                    <a href="<?php echo base_url(); ?>weekly_gazette/dept_re_upload/<?php echo $details->id; ?>" class="btn btn-raised btn-success edit_btn pull-right">Reupload</a>
                                </div>
                            <?php } ?>
                        <?php } ?>
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
                                <!-- <div class="form-group col-md-6">
                                    <label for="username">Department Name : </label>
                                    <?php //echo $details->department_name; ?>
                                </div> -->
                                <div class="form-group col-md-6">
                                    <label for="email">Gazette Type : </label>
                                    <?php echo $details->gazette_type; ?>
                                </div>
                                <div class="form-group col-md-6">
                                    <label for="email">Created Datetime : </label>
                                    <?php echo get_formatted_datetime($details->created_at); ?>
                                </div>
                            </div>
                            <!-- <div class="row">
                                <div class="form-group col-md-6">
                                    <label for="username">Created User : </label>
                                    <?php //echo $details->name; ?>
                                </div>
                            </div> -->
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
                                <?php if (!empty($details->sl_no)) { ?>
                                    <div class="form-group col-md-6">
                                        <label for="username">Issue Date : </label>
                                        <?php echo $details->issue_date; ?>
                                    </div>
                                <?php } ?>
                            </div>
                            <div class="row">
                                <div class="form-group col-md-6">
                                    <label for="username">Dept. Gazette (Official Copy) : </label><br/>
                                    <a href="<?php echo base_url() . $details->dept_pdf_file_path; ?>" target="_blank" class="file_icons"><i class="fa fa-file-pdf-o"></i></a>
                                    <!--                                    <button class="btn btn-raised btn-info dept_sign_btn" id="sign_pdf_btn">Sign PDF<div class="ripple-container"></div></button>-->
                                </div>
                                <div class="form-group col-md-6">
                                    <label for="username">Status : </label>
                                    <?php echo $details->status_name; ?>
                                </div>
                            </div>
                            <div class="row">
                                <div class="pdf-container">
                                    <embed src="<?php echo base_url() . $details->dept_pdf_file_path; ?>" type="application/pdf" width="100%" height="650px" internalinstanceid="8">
                                </div>
                            </div>
                        </div>
                        <div class="row">
                        </div>
                        <hr/>
                        <?php
                        $pdftext = file_get_contents($details->dept_pdf_file_path);
                        $num_pag = preg_match_all("/\/Page\W/", $pdftext, $dummy);
                        $window_path = str_replace('/', '\\', $details->dept_pdf_file_path);
                        $pdf_path = substr($details->dept_pdf_file_path, 1);
                        ?>
                        <div class="boxs-footer text-right bg-tr-black lter dvd dvd-top">
                            <?php if (!empty($details->dept_signed_pdf_path)) { ?>
                                <a href="<?php echo base_url() . $details->dept_signed_pdf_path; ?>" class="btn btn-raised btn-success" target="_blank">View Signed PDF<div class="ripple-container"></div></a>
                            <?php } ?>
                            <?php if (empty($details->dept_signed_pdf_path)) { ?>
                                <div class="checkbox align_left">
                                    <label class="checkbox checkbox-custom">
                                        <input type="checkbox" name="declaration" id="declaration" data-parsley-trigger="change" required="" data-parsley-multiple="agree" data-parsley-id="7279"><span class="checkbox-material"></span> <span class="text-info">Verified & Certified that the Contents Uploaded for Gazette Publication have been prepared in accordance to the Guidelines provided in the User Manual & correct in all respects including its format & page setup.</span></label><ul class="parsley-errors-list" id="parsley-id-multiple-agree"></ul>
                                </div>
                                <a href="http://localhost:81/StartPage.aspx?files=<?php echo $pdf_path; ?>&page=<?php echo $num_pag; ?>&gazette_id=<?php echo $details->gazette_id; ?>&type=dept&category=weekly&signed_name=<?php echo $signed_name; ?>&designation=<?php echo $signed_designation; ?>&dept=<?php echo $details->dept_id; ?>&part_id=<?php echo $details->part_id; ?>" class="btn btn-raised btn-info dept_sign_btn" id="sign_pdf_btn">Sign PDF<div class="ripple-container"></div></a>
                            <?php } ?>
                            
                            <?php if (!empty($details->dept_signed_pdf_path)) { ?>
                                <?php if ($details->status_id == 2 || $details->status_id == 22) { ?>
                                    <?php echo form_open('weekly_gazette/dept_submitted', array('name' => "sign_form", 'id' => "sign_form", 'method' => "post")); ?>    
                                        <input type="hidden" name="gazette_id" value="<?php echo $details->gazette_id; ?>"/>
                                        <input type="hidden" name="part_id" value="<?php echo $details->part_id; ?>"/>
                                        <input type="hidden" name="dept_id" value="<?php echo $details->dept_id; ?>"/>
                                        <button type="submit" class="btn btn-raised btn-success" id="form4Submit">Submit</button>
                                    <?php echo form_close(); ?>
                                <?php } else if ($details->status_id == 7) { ?>
                                    <?php echo form_open('weekly_gazette/resubmit_dept_gazette/' . $details->gazette_id, array('name' => "sign_form", 'id' => "sign_form", 'method' => "post")); ?>
                                        <input type="hidden" name="gazette_id" value="<?php echo $details->gazette_id; ?>"/>
                                        <input type="hidden" name="part_id" value="<?php echo $details->part_id; ?>"/>
                                        <input type="hidden" name="dept_id" value="<?php echo $details->dept_id; ?>"/>
                                        <button type="submit" class="btn btn-raised btn-success" id="form4Submit">Submit</button>
                                    <?php echo form_close(); ?>
                                <?php } ?>        
                            <?php } ?>
                        </div>
                    </div>
                </section>
            </div>
        </div>
    </div>
</section>
<script src="https://code.jquery.com/jquery-1.12.4.min.js" type="text/javascript" nonce="a6b9a780936c8e980939086f618dded2"></script>
<script type="text/javascript" nonce="a6b9a780936c8e980939086f618dded2">
    $(document).ready(function(){
        $('input[type="checkbox"]').click(function () {
            //debugger;
            if($(this).is(':checked')){
                $('#sign_pdf_btn').show();
            } else {
                $('#sign_pdf_btn').hide();
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