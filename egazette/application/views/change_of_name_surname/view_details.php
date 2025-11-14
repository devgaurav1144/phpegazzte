<?php defined('BASEPATH') OR exit('No direct script access allowed'); ?>

<style rel="stylesheet" nonce="8f0882ce3be14f201cadd0eff5726cbd">
	.left_txt {
            float: left;
	}
</style>
<script src="https://code.jquery.com/jquery-1.12.4.min.js" type="text/javascript" nonce="a6b9a780936c8e980939086f618dded2"></script>


<?php if ($details->current_status == 7) { ?>
    <section id="content">
        <div class="page page-forms-validate">
            <!-- bradcome -->
            <ol class="breadcrumb">
                <li><a href="<?php echo base_url(); ?>applicants_login/dashboard">Dashboard</a></li>
                <li><a href="<?php echo base_url(); ?>applicants_login/change_of_name_surname_govt_list">Change of Name/Surname</a></li>
                <li class="active">View Details</li>
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
                        <div class="boxs-header">
                            <h3 class="custom-font hb-blue"><strong>View Details</strong></h3>
                        </div>
                        <div class="boxs-body"><br>
                            <div class="border_data">
                                <?php if (!empty($gz_dets)) { ?>
                                    <div class="row">
                                        <div class="form-group col-md-6">
                                            <label for="username">Applicant Name : </label>
                                            <?php echo $gz_dets->name; ?>
                                        </div>
                                        <div class="form-group col-md-6">
                                            <label for="email">Gazette Type : </label>
                                            <?php echo $gz_dets->gazette_type; ?>
                                        </div>
                                    </div>
                                <?php } ?>
                                <div class="row">
                                    <div class="form-group col-md-6">
                                        <label for="username">File No : </label>
                                        <?php echo $gz_dets->file_no; ?>
                                    </div>
                                    <div class="form-group col-md-6">
                                        <label for="email">Date : </label>
                                        <?php echo strftime('%d %b %Y, %I:%M %p', strtotime($gz_dets->created_at)); ?>
                                    </div>
                                </div>
                                <div class="row">
                                    <div class="form-group col-md-6">
                                        <label for="username">Government Employee : </label>
                                        <?php if ($gz_dets->govt_employee == 1) { ?>
                                            <?php echo "Yes"; ?>
                                        <?php } else { ?>
                                            <?php echo "No"; ?>
                                        <?php } ?>
                                    </div>
                                    <div class="form-group col-md-6">
                                        <label for="email">Minor : </label>
                                        <?php if ($gz_dets->is_minor == 1) { ?>
                                            <?php echo "Yes"; ?>
                                        <?php } else { ?>
                                            <?php echo "No"; ?>
                                        <?php } ?>
                                    </div>
                                </div>
                                <div class="row">
                                    <div class="form-group col-md-6">
                                        <label for="username">Current Status : </label>
                                        <?php echo $gz_dets->status_name; ?>
                                    </div>
                                    <?php $remarks = $this->Applicants_login_model->get_remarks_on_change_name_surname($gz_dets->id, $gz_dets->current_status); ?>
                                    <div class="form-group col-md-6">
                                        <label for="username">Remarks : </label>
                                        <?php echo $remarks; ?>
                                    </div>
                                </div>
                            </div>
                            <div class="row">
                                <div class="pdf-container">
                                    <embed src="<?php echo $pdf->notice_softcopy_pdf; ?>" type="application/pdf" width="100%" height="650px" internalinstanceid="8">
                                </div>
                                <?php if ($this->session->userdata('is_admin')) { ?>
                                    <div class="box-footer text-right bg-tr-black lter dvd dvd-top">
                                        <?php
                                            $pdftext = file_get_contents($pdf->notice_softcopy_pdf);
                                            $num_pag = (int)preg_match_all("/\/Page\W/", $pdftext, $dummy);
                                            //$price = $num_pag * $per_page_value;
                                        ?>
                                        <div class="form-group col-md-12">
                                            <label for="username">No. of Pages : <?php echo $num_pag; ?></label>
                                        </div>
                                        <div class="form-group col-md-12"> 
                                            <label for="username">Per Page Price : <?php echo $pp_price = $this->Applicants_login_model->get_per_page_value_change_of_name_surname(); ?></label>
                                        </div>
                                        <div class="form-group col-md-12"> 
                                            <label for="username">Total : <?php echo $pp_price * $num_pag; ?></label>
                                        </div>
                                        <a href="<?php echo base_url(); ?>applicants_login/forward_to_pay_change_name/<?php echo $this->uri->segment(3) ?>" class="btn btn-raised btn-success edit_btn">Forward To Pay</a>
                                    </div>
                                <?php } ?>
                            </div>
                        </div>
                    </section>
                </div>
            </div>
        </div>
    </section>
<?php } else if ($details->current_status == 9) {?>
    <section id="content">
        <div class="page page-forms-validate">
            <!-- bradcome -->
            <ol class="breadcrumb">
                <li><a href="<?php echo base_url(); ?>applicants_login/dashboard">Dashboard</a></li>
                <li><a href="<?php echo base_url(); ?>applicants_login/change_of_name_surname_govt_list">Change of Name/Surname</a></li>
                <li class="active">View Details</li>
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
                        <div class="boxs-header">
                            <h3 class="custom-font hb-blue"><strong>View Details</strong></h3>
                        </div>
                        <div class="boxs-body"><br>
                            <div class="border_data">
                                <?php if (!empty($gz_dets)) { ?>
                                    <div class="row">
                                        <div class="form-group col-md-6">
                                            <label for="username">Applicant Name : </label>
                                            <?php echo $gz_dets->name; ?>
                                        </div>
                                        <div class="form-group col-md-6">
                                            <label for="email">Gazette Type : </label>
                                            <?php echo $gz_dets->gazette_type; ?>
                                        </div>
                                    </div>
                                <?php } ?>
                                <div class="row">
                                    <div class="form-group col-md-6">
                                        <label for="username">File No : </label>
                                        <?php echo $gz_dets->file_no; ?>
                                    </div>
                                    <div class="form-group col-md-6">
                                        <label for="email">Date : </label>
                                        <?php echo strftime('%d %b %Y, %I:%M %p', strtotime($gz_dets->created_at)); ?>
                                    </div>
                                </div>
                                <div class="row">
                                    <div class="form-group col-md-6">
                                        <label for="username">Government Employee : </label>
                                        <?php if ($gz_dets->govt_employee == 1) { ?>
                                            <?php echo "Yes"; ?>
                                        <?php } else { ?>
                                            <?php echo "No"; ?>
                                        <?php } ?>
                                    </div>
                                    <div class="form-group col-md-6">
                                        <label for="email">Minor : </label>
                                        <?php if ($gz_dets->is_minor == 1) { ?>
                                            <?php echo "Yes"; ?>
                                        <?php } else { ?>
                                            <?php echo "No"; ?>
                                        <?php } ?>
                                    </div>
                                </div>
                                <div class="row">
                                    <div class="form-group col-md-6">
                                        <label for="username">Current Status : </label>
                                        <?php echo $gz_dets->status_name; ?>
                                    </div>
                                    <?php $remarks = $this->Applicants_login_model->get_remarks_on_change_name_surname($gz_dets->id, $gz_dets->current_status); ?>
                                    <div class="form-group col-md-6">
                                        <label for="username">Remarks : </label>
                                        <?php echo $remarks; ?>
                                    </div>
                                </div>
                            </div>
                            <div class="row">
                                <div class="pdf-container">
                                    <embed src="<?php echo $pdf->notice_softcopy_pdf; ?>" type="application/pdf" width="100%" height="650px" internalinstanceid="8">
                                </div>
                            </div>
                        </div>
                    </section>
                </div>
            </div>
        </div>
    </section>
<?php } else if ($details->current_status == 10 || $details->current_status == 17 || $details->current_status == 11) { ?>
    <section id="content">
        <div class="page page-forms-validate">
            <!-- bradcome -->
            <ol class="breadcrumb">
                <li><a href="<?php echo base_url(); ?>applicants_login/dashboard">Dashboard</a></li>
                <li><a href="<?php echo base_url(); ?>applicants_login/change_of_name_surname_govt_list">Change of Name/Surname</a></li>
                <li class="active">View Details</li>
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
                        <div class="boxs-header">
                            <h3 class="custom-font hb-blue"><strong>View Details</strong></h3>
                        </div>
                        <div class="boxs-body">
                            <div class="border_data">
                                <?php if (!empty($gz_dets)) { ?>
                                    <div class="row">
                                        <div class="form-group col-md-6">
                                            <label for="username">Created User : </label>
                                            <?php echo $gz_dets->name.' '.$gz_dets->father_name; ?>
                                        </div>
                                        <div class="form-group col-md-6">
                                            <label for="email">Gazette Type : </label>
                                            <?php echo $gz_dets->gazette_type; ?>
                                        </div>
                                    </div>
                                <?php } ?>
                                <div class="row">
                                    <div class="form-group col-md-6">
                                        <label for="username">File No : </label>
                                        <?php echo $gz_dets->file_no; ?>
                                    </div>
                                    <div class="form-group col-md-6">
                                        <label for="email">Date : </label>
                                        <?php echo strftime('%d %b %Y, %I:%M %p', strtotime($gz_dets->created_at)); ?>
                                    </div>
                                </div>
                                <div class="row">
                                    <div class="form-group col-md-6">
                                        <label for="username">Government Employee : </label>
                                        <?php if ($gz_dets->govt_employee == 1) { ?>
                                            <?php echo "Yes"; ?>
                                        <?php } else { ?>
                                            <?php echo "No"; ?>
                                        <?php } ?>
                                    </div>
                                    <div class="form-group col-md-6">
                                        <label for="email">Minor : </label>
                                        <?php if ($gz_dets->is_minor == 1) { ?>
                                            <?php echo "Yes"; ?>
                                        <?php } else { ?>
                                            <?php echo "No"; ?>
                                        <?php } ?>
                                    </div>
                                </div>
                                <div class="row">
                                    <div class="form-group col-md-6">
                                        <label for="username">Current Status : </label>
                                        <?php echo $gz_dets->status_name; ?>
                                    </div>
                                    <?php $remarks = $this->Applicants_login_model->get_remarks_on_change_name_surname($gz_dets->id, $gz_dets->current_status); ?>
                                    <div class="form-group col-md-6">
                                        <label for="username">Remarks : </label>
                                        <?php echo $remarks; ?>
                                    </div>
                                </div>
                            </div>
                            <div class="row">
                              
                                <?php if ($details->current_status == 17 || $details->current_status == 11) { ?>
                                    <div class="pdf-container">
                                    <embed src="<?php echo base_url() . $pdf->press_pdf; ?>" type="application/pdf" width="100%" height="650px" internalinstanceid="8">
                                </div>
                                <?php } else if ($details->current_status == 10) { ?>
                                    <div class="pdf-container">
                                        <embed src="<?php echo $pdf->notice_softcopy_pdf; ?>" type="application/pdf" width="100%" height="650px" internalinstanceid="8">
                                    </div>
                                <?php } ?>
                            </div>

                            <div class="boxs-footer text-right bg-tr-black lter dvd dvd-top">
                                <?php if ($details->current_status == 10) { ?>

                                    <a href="<?php echo base_url(); ?>applicants_login/press_add_change_name_surname/<?php echo $id; ?>" class="btn btn-raised btn-success edit_btn">Press Add</a>

                                <?php } else if ($details->current_status == 17 && empty($details->press_signed_pdf)) { ?>

                                    <?php
                                        $pdftext = file_get_contents($pdf->press_pdf);
                                        $num_pag = (int)preg_match_all("/\/Page\W/", $pdftext, $dummy);
                                    ?>

                                    <!-- <a href="https://localhost:44303/StartPage.aspx?files=<?php echo $pdf->press_pdf; ?>&page=<?php echo $num_pag; ?>&gazette_id=<?php echo $id; ?>&type=press&category=cos&signed_name=<?php echo $signed_name; ?>&designation=<?php echo $designation; ?>" class="btn btn-raised btn-info govt_press_sign" id="govt_press_sign" >Sign PDF<div class="ripple-container"></div></a> -->
                                 <a href="http://localhost:8089/StartPage?files=<?php echo $pdf->press_pdf; ?>&page=<?php echo $num_pag; ?>&gazette_id=<?php echo $id; ?>&type=press&category=cos&signed_name=<?php echo $signed_name; ?>&designation=<?php echo $designation; ?>" class="btn btn-raised btn-info govt_press_sign" id="govt_press_sign" >Sign PDF<div class="ripple-container"></div></a>
 <?php echo form_open("applicants_login/signpdf", array("method" => "POST", "id" => "form1", "name" => "form1")); ?>
  <input type="hidden" name="filenum" value="<?php echo $gz_dets->file_no; ?>"/>
                                    <!-- <button type="submit"class="btn btn-raised btn-info govt_press_sign" id="govt_press_sign" >Sign PDF<div class="ripple-container"></div></button> -->
 <?php echo form_close(); ?>
                                <?php } else if (!empty($details->press_signed_pdf)) { ?>

                                    <a href="<?php echo base_url() . $details->press_signed_pdf; ?>" target="_blank" class="btn btn-raised btn-success edit_btn">View Signed PDF</a>
                                <?php } ?>

                                <?php if (!empty($details->press_signed_pdf) && $details->is_published == 0) { ?>

                                    <a href="<?php echo base_url(); ?>applicants_login/change_name_surname_publish/<?php echo $details->id; ?>" class="btn btn-raised btn-success edit_btn">Publish</a>

                                <?php } ?>
                            </div>

                        </div>

                    </section>
                </div>
            </div>
        </div>
    </section>
<?php }?>

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