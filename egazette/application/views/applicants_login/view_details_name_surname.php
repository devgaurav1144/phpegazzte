<?php defined('BASEPATH') OR exit('No direct script access allowed'); ?>
<script src="https://code.jquery.com/jquery-1.12.4.min.js" type="text/javascript" nonce="a6b9a780936c8e980939086f618dded2"></script>
<script src="<?php echo base_url(); ?>assets/js/vendor/filestyle/bootstrap-filestyle.min.js" type="text/javascript" nonce="a6b9a780936c8e980939086f618dded2"></script> 
<link href="<?php echo base_url(); ?>assets/js/vendor/lightbox2-dev/dist/css/lightbox.css" rel="stylesheet" nonce="8f0882ce3be14f201cadd0eff5726cbd"/>
<script src="<?php echo base_url(); ?>assets/js/vendor/lightbox2-dev/src/js/lightbox.js" type="text/javascript" nonce="a6b9a780936c8e980939086f618dded2"></script>
<style rel="stylesheet" nonce="8f0882ce3be14f201cadd0eff5726cbd">
    #place-error{
        position: absolute;
        width: 100%;
        /* margin-left: -125px; */
    }
    /* #notice_date-error{
        position: absolute;
        width: 100%;
        margin-left: -125px;
    } */
    #salutation-error{
        position: absolute;
        width: 100%;
    }
    #name_for_notice-error{
        position: absolute;
        width: 100%;
        /* margin-left: -125px; */
    }
    #age-error{
        position: absolute;
        width: 100%;
        /* margin-left: -125px; */
    }
    #old_name-error{
        position: absolute;
        width: 100%;
        /* margin-left: -125px; */
    }
    #new_name-error{
        position: absolute;
        width: 100%;
        /* margin-left: -125px; */
    }
    #new_name_one-error{
        position: absolute;
        width: 100%;
        /* margin-left: -125px; */
    }
    #new_name_two-error{
        width: 100%;
        left:0;
        text-align: left;
    }
    #address-error{
        /* position: absolute; */
        width: 100%;
        left:0;
        text-align: left;
        /* margin-left: -227px; */
    }
    #new_name{
        padding: 5px 20px !important;
    }
    #new_name_one{
        padding: 5px 20px !important;
    }
    #old_name{
        padding: 5px 20px !important;
    }
    #name_for_notice{
        padding: 5px 20px !important;
    }
    .left_txt {
        float: left;
    }
     .gazette-nav-bar {
        padding: 0px 0px; 
        border-top: 0px solid #000;
        text-align:center;         
    }
    .div_sign {
        text-align:right;         
    }    
	.left-align-text {
		text-align: left !important;
	}
	.age_text {
		width: 100px;
	}
    .file_icons_add{
        padding: 0;
        font-size: 14px;
     }
     .file_icons_add .i{
        font-size: 14px;
     }
     .custom-file-find .btn{
       padding:0;
     }
     /* .bootstrap-filestyle .btn-default{
         margin:0;
     } */
     .lower-btn{
        margin:0 !important;  
     }
     .action_btn {
    width: auto !important;
    }
    .bootstrap-filestyle .btn-default{
        margin-bottom: 8px !important;
    }
    .form-group label{
        font-size:13px;
    }
    #address {
    height: 37px;
    position: relative;
    top: 21px;
    width: 330px;
    }
    #address_minor {
    height: 37px;
    position: relative;
    top: 21px;
    width: 330px;
    }
    .btn.btn-default:hover {
    background: #4caf50 !important;
    }
    .hb-blush{
        font-weight:500 !important;
    }
    table {
    width: 100%;
    }
    /* .cstm-header thead{
        background: #4caf50;
        color: #fff;
    } */
    th{
    text-align: center;
    }
    /* td{
    text-align: center;
    } */
    /* .gazette-nav-bar ul {
    border-top: none;
    border-bottom: none;
    } */
    .div_sign{
        border-top: none !important;
        border-bottom: none !important;
    }
    .gazette-nav-bar.left-align-text{
        border-bottom: none !important;
    }

    #pay_online_button {
      display: none; 
    }
</style>

<!-- Modal -->
<div class="modal fade" id="c_and_t_pro_for" tabindex="-1" role="dialog" aria-labelledby="myModalLabel" aria-hidden="true" data-backdrop="false">

    <?php echo form_open('applicants_login/forward_change_name_surname_c_and_t_processor', array('class' => "form1 redd-btn", 'name' => "forward_form_pro", 'method' => "post", 'id' => 'forward_form_pro')); ?>
    
        <input type="hidden" name="change_name_id" id="change_name_id" value="<?php echo $id; ?>"/>
        <input type="hidden" name="status" id="status" value="<?php echo $gz_dets->current_status; ?>"/>
        <input type="hidden" name="button_type" id="button_type" value="abc"/>
        <div class="modal-dialog modal-md">
            <div class="modal-content">
                <div class="modal-header">
                    <button type="button" class="close" data-dismiss="modal">&times;</button>
                    <!-- <?php //if($button_type == "Return") { ?> -->
                        <!-- <h4 class="modal-title">Return to Applicant</h4> -->
                        <h4 class="modal-title" id="return_forward_processor"></h4>
                    <!-- <?php //} else { ?>
                        <h4 class="modal-title">Forward to Verifier</h4>
                    <?php //} ?> -->
                    
                </div>
                <div class="modal-body">
                    <div class="form-group">
                        <label>Remark<span class="asterisk">*</span></label>
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

<div class="modal fade" id="c_and_t_pro_reject" tabindex="-1" role="dialog" aria-labelledby="myModalLabel" aria-hidden="true" data-backdrop="false">

    <?php echo form_open('applicants_login/reject_change_name_surname_c_and_t_processor', array('class' => "form1 redd-btn", 'name' => "reject_form_pro", 'method' => "post", 'id' => 'reject_form_pro')); ?>
    
        <input type="hidden" name="change_name_id" id="change_name_id" value="<?php echo $id; ?>"/>
        
        <div class="modal-dialog modal-md">
            <div class="modal-content">
                <div class="modal-header">
                    <button type="button" class="close" data-dismiss="modal">&times;</button>
                    <h4 class="modal-title">Reject Request</h4>
                </div>
                <div class="modal-body">
                    <div class="form-group">
                        <label>Remark<span class="asterisk">*</span></label>
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

<div class="modal fade" id="c_and_t_veri_for" tabindex="-1" role="dialog" aria-labelledby="myModalLabel" aria-hidden="true" data-backdrop="false">

    <?php echo form_open('applicants_login/forward_change_name_surname_c_and_t_verifier', array('class' => "form1 redd-btn", 'name' => "forward_form_veri", 'method' => "post", 'id' => 'forward_form_veri')); ?>
    
        <input type="hidden" name="change_name_id" id="change_name_id" value="<?php echo $id; ?>"/>
        <input type="hidden" name="status" id="status" value="<?php echo $gz_dets->current_status; ?>"/>
        <input type="hidden" name="button_type_veri" id="button_type_veri" value="Forward"/>

        <div class="modal-dialog modal-md">
            <div class="modal-content">
                <div class="modal-header">
                    <button type="button" class="close" data-dismiss="modal">&times;</button>
                    <h4 class="modal-title" id ="return_forward_verifier"></h4>
                </div>
                <div class="modal-body">
                    <div class="form-group">
                        <label>Remark<span class="asterisk">*</span></label>
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

<div class="modal fade" id="c_and_t_veri_reject" tabindex="-1" role="dialog" aria-labelledby="myModalLabel" aria-hidden="true" data-backdrop="false">

    <?php echo form_open('applicants_login/reject_change_name_surname_c_and_t_verifier', array('class' => "form1 redd-btn", 'name' => "forward_form_veri", 'method' => "post", 'id' => 'forward_form_veri')); ?>
    
        <input type="hidden" name="change_name_id" id="change_name_id" value="<?php echo $id; ?>"/>
        
        <div class="modal-dialog modal-md">
            <div class="modal-content">
                <div class="modal-header">
                    <button type="button" class="close" data-dismiss="modal">&times;</button>
                    <h4 class="modal-title">Reject Request</h4>
                </div>
                <div class="modal-body">
                    <div class="form-group">
                        <label>Remark<span class="asterisk">*</span></label>
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

<div class="modal fade" id="c_and_t_appr_approv" tabindex="-1" role="dialog" aria-labelledby="myModalLabel" aria-hidden="true" data-backdrop="false">

    <?php echo form_open('applicants_login/approve_change_name_surname_c_and_t_approver', array('class' => "form1 redd-btn", 'name' => "approve_form_approver", 'method' => "post", 'id' => 'approve_form_approver')); ?>
    
        <input type="hidden" name="change_name_id" id="change_name_id" value="<?php echo $id; ?>"/>
        <input type="hidden" name="status" id="status" value="<?php echo $gz_dets->current_status; ?>"/>
        <input type="hidden" name="button_type_approver" id="button_type_approver" value="Approve"/>
        
        <div class="modal-dialog modal-md">
            <div class="modal-content">
                <div class="modal-header">
                    <button type="button" class="close" data-dismiss="modal">&times;</button>
                    <h4 class="modal-title" id ="return_forward_approver"></h4>
                </div>
                <div class="modal-body">
                    <div class="form-group">
                        <label>Remark<span class="asterisk">*</span></label>
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

<div class="modal fade" id="c_and_t_appr_return" tabindex="-1" role="dialog" aria-labelledby="myModalLabel" aria-hidden="true" data-backdrop="false">

    <?php echo form_open('applicants_login/approve_change_name_surname_c_and_t_approver', array('class' => "form1 redd-btn", 'name' => "return_form_approver", 'method' => "post")); ?>
    
        <input type="hidden" name="change_name_id" id="change_name_id" value="<?php echo $id; ?>"/>
        <input type="hidden" name="status" id="status" value="<?php echo $gz_dets->current_status; ?>"/>
        
        <div class="modal-dialog modal-md">
            <div class="modal-content">
                <div class="modal-header">
                    <button type="button" class="close" data-dismiss="modal">&times;</button>
                    <h4 class="modal-title">Return To Applicant</h4>
                </div>
                <div class="modal-body">
                    <div class="form-group">
                        <label>Remark<span class="asterisk">*</span></label>
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

<div class="modal fade" id="c_and_t_appr_reject" tabindex="-1" role="dialog" aria-labelledby="myModalLabel" aria-hidden="true" data-backdrop="false">

    <?php echo form_open('applicants_login/reject_change_name_surname_c_and_t_approver', array('class' => "form1 redd-btn", 'name' => "reject_form_approver", 'method' => "post", 'id' => 'reject_form_approver')); ?>
    
        <input type="hidden" name="change_name_id" id="change_name_id" value="<?php echo $id; ?>"/>
        
        <div class="modal-dialog modal-md">
            <div class="modal-content">
                <div class="modal-header">
                    <button type="button" class="close" data-dismiss="modal">&times;</button>
                    <h4 class="modal-title">Reject Change of Name/Surname Request</h4>
                </div>
                <div class="modal-body">
                    <div class="form-group">
                        <label>Remark<span class="asterisk">*</span></label>
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

<!-- CONTENT -->
<section id="content">
    <div class="page page-forms-validate">
        <!-- bradcome -->
        <ol class="breadcrumb">
            <li><a href="<?php echo base_url(); ?>applicants_login/dashboard">Dashboard</a></li>
            <li><a href="<?php echo base_url(); ?>check_status/change_name_status">Change of Name/Surname Status details</a></li>
            <li class="active">Details</li>
        </ol>
        

        <!-- <div class="bg-light lter b-b wrapper-md mb-10">
            <div class="row">
                <div class="col-sm-6 col-xs-12">
                    <h1 class="h3 m-0">Applicant View Details</h1>
                    
                </div>
            </div>
        </div> -->
        <!-- row -->
        <div class="row">
            <div class="col-md-12">
                <section class="boxs">
                    <?php if ($this->session->flashdata('success')) { ?>
                        <div class="alert alert-success alert-dismissable"><button aria-hidden="true" data-dismiss="alert" class="close" type="button">×</button><?php echo $this->session->flashdata('success'); ?></div>
                    <?php } ?>
                    
                    <div class="boxs-header">
                        <h3 class="custom-font hb-blue"><strong>Applicant View Details</strong></h3>
                        
                        <?php if ($this->session->userdata('is_c&t') && $this->session->userdata('is_verifier_approver') == 'Verifier'  && $this->session->userdata('is_c&t_module') == 2) { ?>
                        
                            <div class="action_btn">
                                <?php if(in_array($gz_dets->current_status, array(2,19))) { ?>
                                    <button class="btn btn-raised btn-warning edit_btn button_virifier" data-toggle="modal" data-target="#c_and_t_veri_for" name="Return">Return to Applicant</button>
                                <?php } ?>
                                <?php if (in_array($gz_dets->current_status, array(2,19))) { ?>
                                    <button class="btn btn-raised btn-success edit_btn button_virifier" data-toggle="modal" data-target="#c_and_t_veri_for" name="forward">Forward</button>
                                <?php } ?>
                            </div>
                        
                        <?php } else if ($this->session->userdata('is_c&t') && $this->session->userdata('is_verifier_approver') == 'Approver'  && $this->session->userdata('is_c&t_module') == 2) { ?>
                            
                            <div class="action_btn btn-group">

                                <?php if (in_array($gz_dets->current_status, array(4))) { ?>
                                    <button class="btn btn-raised btn-warning edit_btn button_approver" data-toggle="modal" data-target="#c_and_t_appr_approv" name="Return">Return To Applicant</button>
                                <?php } ?>
                               
                                <?php if (in_array($gz_dets->current_status, array(4,20))) { ?>
                                    <button class="btn btn-raised btn-success edit_btn button_approver" data-toggle="modal" data-target="#c_and_t_appr_approv"  name="Approve">Approve</button>
                                <?php } ?>
                                <?php if (in_array($gz_dets->current_status, array(4,20))) { ?>
                                    <button class="btn btn-raised btn-danger reject_btn" data-toggle="modal" data-target="#c_and_t_appr_reject">Reject</button>
                                <?php } ?>
                                    
                            </div>
                            
                        <?php } else if ($this->session->userdata('is_c&t') && $this->session->userdata('is_verifier_approver') == 'Processor' && $this->session->userdata('is_c&t_module') == 2) { ?>
                    
                            <div class="action_btn">
                                <?php if(in_array($gz_dets->current_status, array(1,8,18, 12, 13))) { ?>
                                    <button class="btn btn-raised btn-warning edit_btn button_type_application" data-toggle="modal" data-target="#c_and_t_pro_for" name="Return">Return to Applicant</button>
                                <?php } ?>
                                <?php if (in_array($gz_dets->current_status, array(1, 6, 18))) { ?>
                                    <button class="btn btn-raised btn-success edit_btn button_type_application" data-toggle="modal" data-target="#c_and_t_pro_for" name="Forward">Forward</button>
                                <?php } ?>
                                
                                <!-- <?php //if (in_array($gz_dets->current_status, array(1, 6))) { ?>
                                    <button class="btn btn-raised btn-danger reject_btn" data-toggle="modal" data-target="#c_and_t_pro_reject">Reject</button>
                                <?php //} ?> -->
                                
                            </div>
                        
                        <?php } ?>
                    </div>
                    <div class="clearfix"></div>
                    <div class="boxs-body">
                        <div class="border_data">
                            <?php if (!empty($gz_dets)) { ?>
                                <div class="row">
                                    <div class="form-group col-md-6">
                                        <label for="username">Applicant Name : </label>
                                        <?php echo $gz_dets->name ?>
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
                                    <label for="username">State : </label>
                                    <?php echo $gz_dets->state_name; ?>
                                </div>
                                <div class="form-group col-md-6">
                                    <label for="email">District : </label>
                                    <?php echo $gz_dets->district_name; ?>
                                </div>
                            </div>
                            <div class="row">
                                <div class="form-group col-md-6">
                                    <label for="username">Block/ULB : </label>
                                    <?php echo $gz_dets->block_name; ?>
                                </div>
                                <div class="form-group col-md-6">
                                    <label for="email">Address  : </label>
                                    <?php echo $gz_dets->address_1; ?>
                                </div>
                                
                            </div>
                            
                            <div class="row">
                                <div class="form-group col-md-6">
                                    <label for="username">Father Name : </label>
                                    <?php echo $gz_dets->applicant_father; ?>
                                </div>
                                <div class="form-group col-md-6">
                                    <label for="email">Date of Birth  : </label>
                                    <?php echo $gz_dets->date_of_birth; ?>
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
                                <?php 
                                $remarks = $this->Applicants_login_model->get_remarks_on_change_name_surname($gz_dets->id, $gz_dets->current_status); ?>
                                <?php if (!empty($remarks)){?>
                                <div class="form-group col-md-6">
                                    <label for="username">Remarks : </label>
                                    <?php echo $remarks; ?>
                                </div>
                                <?php }?>
                            </div>
                        </div> 

                        <div class="border_data"> 
                            <div class="row">
                            <?php 
                                    if (!empty($tot_documents)) { ?>
                                    <?php foreach ($tot_documents as $data) { ?>
                                        <div class="form-group col-md-6" id="doc_<?php echo $data->id; ?>">
                                            <label for="email"><?php echo $data->document_name; ?></label><br>

                                            <?php $image = $this->Applicants_login_model->get_image_link($id); ?>

                                            <?php 
                                                $link = "";
                                                if ($data->id == 1) {
                                                    if (!empty($image->affidavit)) {
                                                        
                                                        $ext = pathinfo($image->affidavit, PATHINFO_EXTENSION);
                                                        if($ext == "pdf"){
                                                            $link = $image->affidavit;
                                                            $class = "fa fa-file-pdf-o";
                                                        } else {
                                                            $link = $image->affidavit;
                                                            $class = "fa fa-image";
                                                        }
                                                    }
                                                    
                                                } else if ($data->id == 2){
                                                    if (!empty($image->original_newspaper)) {
                                                        $ext = pathinfo($image->original_newspaper, PATHINFO_EXTENSION);
                                                        if($ext == "pdf"){
                                                            $link = $image->original_newspaper;
                                                            $class = "fa fa-file-pdf-o";
                                                        } else {
                                                            $link = $image->original_newspaper;
                                                            $class = "fa fa-image";
                                                        }
                                                    }
                                                    
                                                }else if ($data->id == 3){
                                                    if (!empty($image->notice_softcopy_pdf)) {
                                                        $link = $image->notice_softcopy_pdf; 
                                                        $class = "fa fa-file-pdf-o";
                                                    }
                                                    
                                                }else if ($data->id == 4){
                                                    if (!empty($image->kyc_document)) {
                                                        $ext = pathinfo($image->kyc_document, PATHINFO_EXTENSION);
                                                        if($ext == "pdf"){
                                                            $link = $image->kyc_document;
                                                            $class = "fa fa-file-pdf-o";
                                                        } else {
                                                            $link = $image->kyc_document;
                                                            $class = "fa fa-image";
                                                        }
                                                    }
                                                    
                                                }else if ($data->id == 5){
                                                    if (!empty($image->approval_authority)) {
                                                        $ext = pathinfo($image->approval_authority, PATHINFO_EXTENSION);
                                                        if($ext == "pdf"){
                                                            $link = $image->approval_authority;
                                                            $class = "fa fa-file-pdf-o";
                                                        } else {
                                                            $link = $image->approval_authority;
                                                            $class = "fa fa-image";
                                                        }
                                                    }
                                                    
                                                }else if ($data->id == 6){
                                                    if (!empty($image->deed_changing_form)) {
                                                        $ext = pathinfo($image->deed_changing_form, PATHINFO_EXTENSION);
                                                        if($ext == "pdf"){
                                                            $link = $image->deed_changing_form;
                                                            $class = "fa fa-file-pdf-o";
                                                        } else {
                                                            $link = $image->deed_changing_form;
                                                            $class = "fa fa-image";
                                                        }
                                                    }
                                                    
                                                }else if ($data->id == 7){
                                                    if (!empty($image->birth_certificate)) {
                                                        $ext = pathinfo($image->birth_certificate, PATHINFO_EXTENSION);
                                                        if($ext == "pdf"){
                                                            $link = $image->birth_certificate;
                                                            $class = "fa fa-file-pdf-o";
                                                        } else {
                                                            $link = $image->birth_certificate;
                                                            $class = "fa fa-image";
                                                        }
                                                    }
                                                }

                                                if($class == "fa fa-image"){
                                                    
                                            ?>
                                            
                                                <a href="<?php echo $link; ?>" data-lightbox="<?php echo $link; ?>"  class="file_icons"><i class="<?php echo $class; ?>"></i></a>

                                            <?php } else {?>

                                                <a href="<?php echo $link; ?>" target="_blank"  class="file_icons"><i class="<?php echo $class; ?>"></i></a>
                                            <?php }?>
                                        </div>
                                    <?php } ?>
                                <?php } ?>

                            </div>
                        </div>

                        <div class="row">
                            <div class="pdf-container">
                                <embed src="<?php echo $image->notice_softcopy_pdf; ?>" type="application/pdf" width="100%" height="650px" internalinstanceid="8">
                            </div>
                        </div>
                        
                        <div class="panel-group" id="accordion">
                            <div class="panel panel-default">
                                <div class="panel-heading">
                                    <h4 class="panel-title hb-cyan">
                                        <a data-toggle="collapse" data-parent="#accordion" href="#collapse_1">Status History</a>
                                    </h4>
                                </div>
                                <div id="collapse_1" class="panel-collapse collapse">
                                    <div class="panel-body panel-body-no-padding">
                                        <div class="row">
                                            <div class="col-lg-12 col-md-12">
                                                <table class="table table-bordered cstm-header">
                                                    <thead>
                                                        <tr>
                                                            <th width="320">Status</th>
                                                            <th>Remarks</th>
                                                            <th width="180">Date</th>
                                                        </tr>
                                                    </thead>
                                                    <tbody>
                                                    <!-- <tr class="list-group"> -->
                                                        <?php if (!empty($status_list)) { ?>
                                                            <?php foreach ($status_list as $status) { 
                                                                 $remarks = "";
                                                                 if (!empty($status->remarks)) {
                                                                     $remarks = "(" . $status->remarks . ")";
                                                                 }
                                                                ?>
                                                                <tr>
                                                                <td ><?php echo $status->status_name; ?></td>
                                                                <td><?php echo ($remarks); ?></td>
                                                                <td><span class="badge bg-default hstry_class"><?php echo get_nice_datetime($status->created_at); ?></span></td>
                                                                </tr>
                                                            <?php } ?>
                                                        <?php } else { ?>
                                                            <p class="center">No data available</p>
                                                        <?php } ?>
                                                    </tbody>
                                                </table>
                                            </div>
                                        </div> 
                                    </div>
                                </div>
                            </div>
                        </div>
                        
                        <div class="panel-group" id="accordion">
                            <div class="panel panel-default">
                                <div class="panel-heading">
                                    <h4 class="panel-title hb-cyan">
                                        <a data-toggle="collapse" data-parent="#accordion" href="#collapse_2">Document History</a>
                                    </h4>
                                </div>
                                <div id="collapse_2" class="panel-collapse collapse">
                                    <div class="panel-body panel-body-no-padding">
                                        <div class="border_data">
                                        <?php if (!empty($docu_list)) { ?>
                                            
                                                <?php foreach ($docu_list as $list) { ?>
                                                    <?php if(!empty($list)){?>
                                                        <div class="row">
                                                            <div class="form-group  col-md-12" id="">
                                                                <label><?php echo $list->docu; ?>(<?php echo get_nice_datetime($list->created_at); ?>)</label><br>
                                                                <div class="col-md-2">
                                                                    <?php if($list->gz_docu_id == 8) { ?>

                                                                        <?php $link = $this->Applicants_login_model->get_pdf($id, $list->id); ?>

                                                                        <a href="<?php echo $link; ?>" data-lightbox="<?php echo $link; ?>"  class="file_icons"><i class="fa fa-file-pdf-o"></i></a>

                                                                    <?php } else if ($list->gz_docu_id != 8 && $list->gz_docu_id != 3) { ?>
                                                                        <?php 
                                                                        $ext = pathinfo($list->document_name, PATHINFO_EXTENSION);
                                                                        if($ext == "pdf"){?>

                                                                        <a href="<?php if(!empty($list->document_name)) {  echo  $list->document_name;  } ?>" target="_blank"  class="file_icons"><i class="fa fa-file-pdf-o"></i></a>
                                                                        
                                                                        <?php } else {?>

                                                                            <a href="<?php if(!empty($list->document_name)) {  echo  $list->document_name;  } ?>" data-lightbox="<?php echo $list->document_name; ?>"  class="file_icons"><i class="fa fa-image"></i></a>
                                                                            
                                                                        <?php }?>

                                                                    <?php } ?>
                                                                </div>
                                                            </div>
                                                        </div>
                                                    <?php }?>
                                                <?php } ?>
                                            <?php } else { ?>
                                                <p class="center">No data available</p>
                                            <?php } ?>
                                        </div>
                                    </div>
                                </div>
                            </div>
                        </div>
                        
 <?php if ( $gz_dets->current_status == 15) { ?>
                        <div class="panel panel-default">
    <div class="panel-heading">Declaration</div>
    <div class="panel-body">
<?php foreach ($declartion as $list) { ?>
  <div class="checkbox" id="paymentForm">
         
  <label><input type="checkbox" value="" style="" class="check-item"><?php if(!empty($list->declaration)) {  echo  $list->declaration;  } ?></label>
</div>
<?php } ?>

             <?php } ?>
  

<!-- current_status == 9 -->
                        <?php if (!empty ($per_page_value) && $gz_dets->current_status == 15) { ?>
                            <div class="boxs-footer text-right  lter dvd dvd-top">
                                <?php if ($this->session->userdata('is_applicant') && $gz_dets->current_status == 15) { ?>
                                    <?php
                                        $details = $this->Applicants_login_model->get_image_link($id);
                                        $pdftext = file_get_contents($details->notice_softcopy_pdf);
                                        $num_pag = (int)preg_match_all("/\/Page\W/", $pdftext, $dummy);
                                        $price = $num_pag * $per_page_value;
                                    ?>

                                    
                                    <div class="form-group col-md-12">
                                        <label for="username">No of Page : <?php  echo $num_pag; ?></label>
                                    </div>
                                    <div class="form-group col-md-12">
                                        <label for="username">Per Page value : <?php  echo $per_page_value; ?></label>
                                    </div>
                                    <div class="form-group col-md-12">
                                        <label for="username">Total : <?php  echo $num_pag . ' * ' . $per_page_value . ' = ' . $price; ?></label>
                                    </div>
                                      <?php echo form_open("applicants_login/updatepaymentstatus", array("method" => "POST", "id" => "form1", "name" => "form1")); ?>
                                    <?php //echo //form_open("https://uat.(StateName)treasury.gov.in/echallan/dept-intg", array("method" => "POST", "id" => "form1", "name" => "form1")); ?>
                                        <?php 
                                            // Dept. Code
                                            $dept_code = "EGZ";
                                            // HoA Details
                                            $HoA = "0058-00-200-0127-02082-000";
                                            // Description
                                            $descp = "Change of Surname Gazette Publication";
                                            // Amount
                                            $amnt = $price;
                                            // Total Amount
                                            $tot_amnt = $price;
                                            // Depositor name
                                            $depositor_name = $gz_dets->name;
                                            // Depositor Address
                                            $depositor_addr = 'BBSR';
                                            // State
                                            $state = "(StateName)";
                                            // District
                                            $district = "Khudha";
                                            // Pincode
                                            $pincode = "751024";
                                            // Mobile
                                            $mobile = $gz_dets->mobile;
                                            // Email
                                            $email = $gz_dets->email;
                                            // Addl. Info
                                            $addl_info = $id . "!~!" . $gz_dets->file_no;
                                            // Return URL
                                            $return_url =  base_url() . "applicants_login/change_name_surname_payment_response";

                                            $dep_ref_id = uniqid(time());
                                            // Message Format
                                            $msg_format = $dept_code . "|" . $dep_ref_id . "|" . $HoA . "|" . $descp . "|". $amnt . "||||||||||||||||" . $tot_amnt . "|" . $depositor_name . "|" . $depositor_addr . "||" . $state . "|" . $district . "|" . $pincode ."|" . $mobile . "|" . $email . "|" . $addl_info . "||||||" . $return_url;
											//echo $msg_format;
                                            // Function to be used for Encrypt the Message string
                                            function encrypt($data = '', $key = NULL) {
                                                if($key != NULL && $data != ""){
                                                    $method = "AES-256-ECB";
                                                    $encrypted = openssl_encrypt($data, $method, $key, OPENSSL_RAW_DATA);
                                                    $result = base64_encode($encrypted);
                                                    return $result;
                                                } else {
                                                    return "String to encrypt, Key is required.";
                                                }
                                            }

                                            // Binary file path
                                            //echo $binary_key;
                                            $binary_file_path = $binary_key;
                                            // get size of the binary file
                                            $filesize = filesize($binary_file_path);
                                            $handle = fopen($binary_file_path, "rb");
                                            $secret_key = fread($handle, filesize($binary_file_path));
                                            // Checksum
                                            $checksum = hash_hmac('sha256', $msg_format, $secret_key, true);
                                            // Encrypted Message
                                            $chksum_msg = $msg_format . "|" . base64_encode($checksum);
                                            //OPENSSL_PKCS5_PADDING
                                            $encrypted_msg = encrypt($chksum_msg, $secret_key);
                                        ?>
                                        <input type="hidden" name="deptCode" value="<?php echo $dept_code; ?>"/>
                                        <input type="hidden" name="filenum" value="<?php echo $gz_dets->file_no; ?>"/>
                                        
                                        <input type="hidden" name="msg" value="<?php echo $encrypted_msg; ?>"/>
                                        <!-- <input type="hidden" name="dep_ref_id" value="<?php //echo $dep_ref_id; ?>">
                                        <input type="hidden" name="amount" value="<?php //echo $amnt; ?>"/> -->
                                        <input type="hidden" name="description" value="<?php echo $descp; ?>"/>

                                       <button type="submit" id="pay_online_button" class="btn btn-raised btn-success edit_btn">Proceed To Pay Online</button>
                                        <!-- <button type="submit" id="pay_online_button" class="btn btn-raised btn-success edit_btn">Proceed To Pay Online</button> -->
                                    <?php echo form_close(); ?>
                                    <!--Edited by subha-->
                                    <?php echo form_open("make_payment/make_payment_name_surname_offline", array("method" => "POST", "id" => "form_pay_offline", "name" => "form_pay_offline")); ?>
                                        <input type="hidden" name="record_id" value="<?php echo $id; ?>"/>
                                        <input type="hidden" name="tot_amnt" value="<?php echo $price; ?>"/>
                                        <input type="hidden" name="depositor_name" value="<?php echo $gz_dets->name; ?>"/>
                                        <input type="hidden" name="mobile" value="<?php echo $gz_dets->mobile; ?>"/>
                                        <input type="hidden" name="email" value="<?php echo $gz_dets->email; ?>"/>
                                        <input type="hidden" name="file_no" value="<?php echo $gz_dets->file_no; ?>"/>
                                        <input type="hidden" name="deptCode" value="<?php echo $dept_code; ?>"/>
                                        <input type="hidden" name="msg" value="<?php echo $encrypted_msg; ?>"/>
                                        <!-- <button type="submit" name="btn_pay_offline" id="btn_pay_offline" class="btn btn-raised btn-success edit_btn offline_btn_color">Proceed To Pay Offline</button> -->
                                        <?php echo form_close(); ?>


                                <?php } ?>
                            </div>
                        <?php } ?>

    </div>
  </div>
                        
                       
                    </div>
                </section>
                
            </div>
        </div>

        <!-- Code reading is pending from here -->
        
        <?php if (($gz_dets->current_status == 22 || $gz_dets->current_status == 23 || $gz_dets->current_status == 24) && ($this->session->userdata('is_applicant'))) { ?>
            <div class="row">
                <div class="col-md-12">
                    <section class="boxs">
                        <div class="boxs-header">
                            <h3 class="custom-font hb-blush">Resubmission </h3>
                        </div>
                        <div class="boxs-body">
                            <?php echo form_open('applicants_login/edit_change_of_name_surname', array('name' => 'form_name_surname_edit',  'id' => 'form_name_surname_edit', 'method' => 'POST', 'enctype' => 'multipart/form-data')); ?>
                                <div class="row">
                                    <div class="row">
                                        <div class="form-group col-md-6">
                                            <input type="text" id="file_no" name="file_no" value="<?php echo $gz_dets->file_no; ?>" style="display: none;" readonly/>
                                        </div>
                                    </div>
                                    <div class="form-group col-md-4">
                                        <label for="state_id">State  <span class="asterisk">*</span></label>
                                        <select name="state_id" id="state_id" class="form-control" required="">
                                            <option value="">Select State</option>
                                            <?php if (!empty($states)) { ?>
                                                <?php foreach ($states as $state) { ?>
                                                    <option value="<?php echo $state->id; ?>" <?php if ($state->id == $gz_dets->state_id) { echo 'selected'; } ?>><?php echo $state->state_name; ?></option>
                                                <?php } ?>
                                            <?php } ?>
                                        </select>
                                        <?php if (form_error('state_id')) { ?>
                                            <span class="error"><?php echo form_error('state_id'); ?></span>
                                        <?php } ?>
                                    </div>
                                    <div class="form-group col-md-4">
                                        <label for="district_id">District  <span class="asterisk">*</span></label>
                                        <select name="district_id" id="district_id" class="form-control" required="">
                                            <?php foreach ($districts as $d_name) { ?>
                                                <option value="<?php echo $d_name->id; ?>" <?php echo ($d_name->id == $gz_dets->district_id) ? "selected" : ""; ?>><?php echo $d_name->district_name; ?></option>
                                            <?php } ?>
                                        </select>
                                        <?php if (form_error('district_id')) { ?>
                                            <span class="error"><?php echo form_error('district_id'); ?></span>
                                        <?php } ?>
                                    </div>
                                    <div class="form-group col-md-4">
                                        <label for="block_ulb_id">Block/ULB  <span class="asterisk">*</span></label>
                                        <select name="block_ulb_id" id="block_ulb_id" class="form-control"   required="">
                                        <optgroup label="Block">
                                            <!-- <?php //var_dump($gz_dets->type)?> -->
                                            <?php foreach ($block_ulb as $bu_name) { ?>
                                                <option value="block_<?php echo $bu_name->id; ?>" <?php echo ($bu_name->id == $gz_dets->block_ulb_id) ? "selected" : ""; ?>><?php echo $bu_name->block_name; ?></option>
                                            <?php } ?>
                                        </optgroup>
                                        <optgroup label="ULB">
                                            <?php foreach ($ulb as $ulb) { ?>
                                                <option value="ulb_<?php echo $ulb->id; ?>" <?php echo ($ulb->id == $gz_dets->block_ulb_id) ? "selected" : ""; ?>><?php echo $ulb->ulb_name; ?></option>
                                            <?php } ?>
                                        </select>
                                        </optgroup>
                                        <?php if (form_error('block_ulb_id')) { ?>
                                            <span class="error"><?php echo form_error('block_ulb_id'); ?></span>
                                        <?php } ?>
                                    </div>
                                    <div class="form-group col-md-4">
                                        <label for="address_1">Address   <span class="asterisk">*</span></label>
                                        <textarea type="text" id="address_1" name="address_1" placeholder="Enter Address " class="form-control" maxlength="200" minlength="5" autocomplete="off"><?php echo $gz_dets->address_1; ?></textarea>
                                        <?php if (form_error('address_1')) { ?>
                                            <span class="error"><?php echo form_error('address_1'); ?></span>
                                        <?php } ?>
                                    </div>     
                                    
                                     


                                    <div class="form-group col-md-4">
                                        <label for="address_1">Pin code  <span class="asterisk">*</span></label>
                                        <input type="text" id="pin_code" name="pin_code" placeholder="Enter Pin code" class="form-control number_only" maxlength="6" minlength="6" autocomplete="off" value="<?php echo $gz_dets->pin_code; ?>">
                                        <?php if (form_error('pin_code')) { ?>
                                            <span class="error"><?php echo form_error('pin_code'); ?></span>
                                        <?php } ?>
                                    </div>
                                    <div class = "clearfix"></div>
                                    <input type="hidden" id="change_name_id" name="change_name_id" value="<?php echo $id; ?>">
                                    <?php  foreach ($tot_documents as $tot_document) { ?>

                                        <?php $image = $this->Applicants_login_model->get_image_link($id); ?>

                                            <?php 
                                                $link = "";
                                                $redirectionLink = "";
                                                if ($tot_document->id == 1) {
                                                    if (!empty($image->affidavit)) {
                                                        $link = $image->affidavit;
                                                    }
                                                    
                                                } else if ($tot_document->id == 2){
                                                    if (!empty($image->original_newspaper)) {
                                                        $link = $image->original_newspaper;
                                                    }
                                                    
                                                }else if ($tot_document->id == 3){
                                                    if (!empty($image->notice_in_softcopy)) {
                                                        $link = $image->notice_in_softcopy;
                                                    }
                                                    
                                                }else if ($tot_document->id == 4){
                                                    if (!empty($image->kyc_document)) {
                                                        $link = $image->kyc_document;
                                                    }
                                                    
                                                }else if ($tot_document->id == 5){
                                                    if (!empty($image->approval_authority)) {
                                                        $link = $image->approval_authority;
                                                    }
                                                    
                                                }else if ($tot_document->id == 6){
                                                    if (!empty($image->deed_changing_form)) {
                                                        $link = $image->deed_changing_form;
                                                    }
                                                    
                                                }else if ($tot_document->id == 7){
                                                    if (!empty($image->birth_certificate)) {
                                                        $link = $image->birth_certificate;
                                                    }
                                                }
                                            ?>
                                        <?php 
                                            if ($tot_document->id == 3) { ?>

                                                <input type="hidden" id="word_file" name="word_file" value="<?php echo $link; ?>">
                                                <input type="hidden" id="pdf_file" name="pdf_file" value="<?php echo $image->notice_softcopy_pdf; ?>">
                                               <?php continue;
                                            } elseif($tot_document->id == 6) {
                                                $class = "fa fa-file-pdf-o";
                                                $accept = "pdf/*";
                                            }else{
                                                $class = "fa fa-file-image-o";
                                                $accept = "image/*";
                                            }

                                            if ($tot_document->id == 5 || $tot_document->id == 6 || $tot_document->id == 7) {
                                                $style = 'display: none';
                                            } else {
                                                $style = '';
                                            }
                                        ?>
                                        <div class="form-group col-md-4" id="doc_edit_<?php echo $tot_document->id; ?>">
                                            <?php if ($tot_document->id == 3) { ?>
                                                
                                            <?php } ?>
                                            
                                            <label><?php echo $tot_document->document_name; ?><span class="asterisk">*</span>
                                                <span class="file_icons_add">
                                                    <i class="<?php echo $class; ?>"></i>
                                                </span>
                                            </label>
                                           
                                            <div class="custom-file-find">
                                                <input type="file" id="docu_<?php echo $tot_document->id; ?>" name="docu_<?php echo $tot_document->id; ?>" accept="<?php echo $accept; ?>" class="filestyle chk_file lower-btn file_upload_button" data-buttonText="File" data-iconName="fa fa-upload" style="clip: inherit !important;">
                                            </div>
                                            <input type="hidden" id="document_<?php echo $tot_document->id; ?>" name="document_<?php echo $tot_document->id; ?>" value="<?php echo $link; ?>">

                                            <span class="old-file">
                                                    <?php if ($tot_document->id == 1 && !empty($image->affidavit)) {
                                                        $ext = pathinfo($image->affidavit, PATHINFO_EXTENSION);?>

                                                        <?php if($ext == "pdf"){ ?>
                                                            <a href="<?php if($image->affidavit) { echo $image->affidavit; }?>" target="_blank"  class=""><i class="fa fa-file-pdf-o"></i></a>
                                                        <?php } else {?>
                                                            <a href="<?php echo $image->affidavit; ?>" data-lightbox="<?php echo $image->affidavit; ?>"><i class="fa fa-file-image-o"></i></a>
                                                        <?php }
                                                    } ?>
                                                    
                                                    <?php if ($tot_document->id == 2 && !empty($image->original_newspaper)) {
                                                        $ext = pathinfo($image->original_newspaper, PATHINFO_EXTENSION);?>

                                                        <?php if($ext == "pdf"){ ?>
                                                            <a href="<?php echo $image->original_newspaper ?>" target="_blank"  class=""><i class="fa fa-file-pdf-o"></i></a>
                                                        <?php } else {?>
                                                            <a href="<?php echo $image->original_newspaper; ?>" data-lightbox="<?php echo $image->original_newspaper; ?>"><i class="fa fa-file-image-o"></i></a>
                                                        <?php }
                                                    } ?>

                                                    <?php if ($tot_document->id == 4 && !empty($image->kyc_document)) {
                                                        $ext = pathinfo($image->kyc_document, PATHINFO_EXTENSION);?>

                                                        <?php if($ext == "pdf"){ ?>
                                                            <a href="<?php echo $image->kyc_document ?>" target="_blank"  class=""><i class="fa fa-file-pdf-o"></i></a>
                                                        <?php } else {?>
                                                            <a href="<?php echo $image->kyc_document; ?>" data-lightbox="<?php echo $image->kyc_document; ?>"><i class="fa fa-file-image-o"></i></a>
                                                        <?php }
                                                    } ?>

                                                    <?php if ($tot_document->id == 5 && !empty($image->approval_authority)) {
                                                        $ext = pathinfo($image->approval_authority, PATHINFO_EXTENSION);?>

                                                        <?php if($ext == "pdf"){ ?>
                                                            <a href="<?php echo $image->approval_authority ?>" target="_blank"  class=""><i class="fa fa-file-pdf-o"></i></a>
                                                        <?php } else {?>
                                                            <a href="<?php echo $image->approval_authority; ?>" data-lightbox="<?php echo $image->approval_authority; ?>"><i class="fa fa-file-image-o"></i></a>
                                                        <?php }
                                                    } ?>

                                                    <?php if ($tot_document->id == 6 && !empty($image->deed_changing_form)) {
                                                        $ext = pathinfo($image->deed_changing_form, PATHINFO_EXTENSION);?>

                                                        <?php if($ext == "pdf"){ ?>
                                                            <a href="<?php echo $image->deed_changing_form ?>" target="_blank"  class=""><i class="fa fa-file-pdf-o"></i></a>
                                                        <?php } else {?>
                                                            <a href="<?php echo $image->deed_changing_form; ?>" data-lightbox="<?php echo $image->deed_changing_form; ?>"><i class="fa fa-file-image-o"></i></a>
                                                        <?php }
                                                    } ?>

                                                    <?php if ($tot_document->id == 7 && !empty($image->birth_certificate)) {
                                                        $ext = pathinfo($image->birth_certificate, PATHINFO_EXTENSION);?>

                                                        <?php if($ext == "pdf"){ ?>
                                                            <a href="<?php echo $image->birth_certificate ?>" target="_blank"  class=""><i class="fa fa-file-pdf-o"></i></a>
                                                        <?php } else {?>
                                                            <a href="<?php echo $image->birth_certificate; ?>" data-lightbox="<?php echo $image->birth_certificate; ?>"><i class="fa fa-file-image-o"></i></a>
                                                        <?php }
                                                    } ?>

                                            </span>

                                            <span class="notif_file_<?php echo $tot_document->id; ?> error"></span>
                                            <?php if (form_error("doc_<?php echo $tot_document->id; ?>")) { ?>
                                                <span class="error"><?php echo form_error("doc_<?php echo $tot_document->id; ?>"); ?></span>
                                            <?php } ?>
                                        </div>
                                        <script type="text/javascript" nonce="a6b9a780936c8e980939086f618dded2">
                                            
                                            $("#docu_<?php echo $tot_document->id; ?>").on('change', function () {
                                                
                                                var input_file = $(this).prop('files')[0];
                                                var ext = input_file['name'].substring(input_file['name'].lastIndexOf('.') + 1).toLowerCase();
                                                <?php if ($tot_document->id != 3) { ?>
                                                    switch (ext) {
                                                        case 'jpg':
                                                        case 'jpeg':
                                                        case 'png':
                                                        case 'pdf':
                                                        $(".notif_file_<?php echo $tot_document->id; ?>").html('');
                                                        $(".notif_file_<?php echo $tot_document->id; ?>").hide();
                                                        // Display filename in the span help block
                                                        $('.files_<?php echo $tot_document->id; ?>').html($(this).val().split('\\').pop());
                                                        break;
                                                        default:
                                                            $(".notif_file_<?php echo $tot_document->id; ?>").html('Please upload only jpg/jpeg/png/pdf file');
                                                            $(".notif_file_<?php echo $tot_document->id; ?>").show();
                                                            this.value = '';
                                                    }
                                                <?php } else { ?>
                                                    switch (ext) {
                                                        case 'doc':
                                                        case 'docx':
                                                        $(".notif_file_<?php echo $tot_document->id; ?>").html('');
                                                        $(".notif_file_<?php echo $tot_document->id; ?>").hide();
                                                        // Display filename in the span help block
                                                        $('.files_<?php echo $tot_document->id; ?>').html($(this).val().split('\\').pop());

                                                        break;
                                                        default:
                                                            $(".notif_file_<?php echo $tot_document->id; ?>").html('Please upload only doc file');
                                                            $(".notif_file_<?php echo $tot_document->id; ?>").show();
                                                            this.value = '';
                                                    }
                                                <?php } ?>

                                                var val = $(this).val();
                                                if(val != "") {
                                                    var input_file = $('#docu_<?php  echo $tot_document->id; ?>').prop('files')[0];
                                                    var id = <?php  echo $tot_document->id; ?>;
                                                    var reader = new FileReader();
                                                    reader.onload = function (e) {
                                                        $('#doc_pre_' + id).attr('src', e.target.result);
                                                        $('#doc_pre_' + id).show();
                                                    };
                                                    reader.readAsDataURL(input_file);
                                                    upload_notification_file(input_file, id);
                                                }
                                            });

                                            function upload_notification_file(file, id) {
                                                var fd = new FormData();
                                                var token = $("input[name=<?php echo $this->security->get_csrf_token_name(); ?>]").val();
                                                var file_no = $("#file_no").val();
                                                fd.append('file', file);
                                                fd.append('<?php echo $this->security->get_csrf_token_name(); ?>', token);
                                                fd.append('file_no', file_no);
                                                fd.append('id', id);
                                                //$('.loader').show();
                                                if(id != 3){
                                                    $.ajax({
                                                        type: "POST",
                                                        url: "<?php echo base_url(); ?>applicants_login/upload_document_for_change_name_surname",
                                                        data: fd,
                                                        contentType: false,
                                                        processData: false,
                                                        success: function (msg) {
                                                            var json = JSON.parse(msg);

                                                            if (json['success']) {
                                                                $(".notif_file").hide();
                                                                $(".notif_file").html();
                                                                $('#document_' + id).val(json['success']);
                                                            }
                                                            if (json['error']) {
                                                                //$('.loader').hide();
                                                                $(".notif_file").html(json['error']);
                                                                $('#document_' + id).val('');
                                                                $(".notif_file").show();
                                                            }
                                                        },
                                                        error: function (msg) {
                                                            //$('.loader').hide();
                                                        }
                                                    });
                                                }
                                            }
                                        </script>
                                    <?php } ?>
                                        
                                </div>

                                <?php $select_notice_det = $this->Applicants_login_model->select_notice_det($id); 
                                //print_r($gz_dets);exit();
                                if(!empty($select_notice_det)) { 
                                   if($gz_dets->is_minor == 1){ ?>
                                   <!-- <?php //echo 'abc';?> -->
                                    <div class="header-gazette">  
                                        <input type="hidden" id='val_chk_updated' name='val_chk_updated'>
                                        <p class="sub-head"><h3>Notice</h3></p>                   
                                        <div class="gazette-nav-bar left-align-text">
                                            <ul> 
                                                <li class="">By virtue of an affidavit sworn before the Executive &nbsp;&nbsp;</li>
                                                <li>
                                                    <select   class="form-control paragrap-space" id="approver_minor" name="approver_minor">
                                                        <option selected="selected" value="Executive Magistrate" <?php if($select_notice_det->approver == 'Executive Magistrate') { echo "selected"; }?>>Executive Magistrate</option>
                                                        <option value="Notary Public" <?php if($select_notice_det->approver == 'Notary Public') { echo "selected"; }?>>Notary Public</option>
                                                    </select>
                                                    <?php if (form_error('approver_minor')) { ?>
                                                        <span class="error"><?php echo form_error('approver_minor'); ?></span>
                                                    <?php } ?>
                                                </li>
                                                <li class="">
                                                    <input type="text" id="place_minor" name="place_monir" placeholder="Place" class="form-control alpha_only chk_updated" autocomplete="off" minlength="4" value="<?php echo $select_notice_det->place; ?>">
                                                    <?php if (form_error('place_minor')) { ?>
                                                        <span class="error"><?php echo form_error('place_minor'); ?></span>
                                                    <?php } ?>
                                                </li>
                                                <li class="">&nbsp;&nbsp;on Dated &nbsp;&nbsp;</li>
                                                <li class="">
                                                        <input type="text" id="notice_date_minor" name="notice_date_minor" placeholder="DD-MM-YYYY" class="form-control chk_updated"  autocomplete="off" value="<?php echo $select_notice_det->date; ?>">
                                                    <?php if (form_error('notice_date_minor')) { ?>
                                                        <span class="error"><?php echo form_error('notice_date_minor'); ?></span>
                                                    <?php } ?>
                                                </li>
                                                <li class="">&nbsp;&nbsp;,&nbsp;&nbsp;I ,&nbsp;&nbsp;</li>                               
                                                <li>
                                                        <select class="form-control chk_updated1" id="salutation_minor" name="salutation_minor">
                                                            <option>Select salutation</option>
                                                        <option selected="selected" value="Mr."  <?php if($select_notice_det->salutation == 'Mr.') { echo "selected"; }?>  >Mr.</option>
                                                        <option value="Mrs." <?php if($select_notice_det->salutation == 'Mrs.') { echo "selected"; }?> >Mrs.</option>
                                                        <!-- <option value="Smt." <?php //if($select_notice_det->salutation == 'Smt.') { echo "selected"; }?> >Smt.</option> -->
                                                        <option value="Miss." <?php if($select_notice_det->salutation == 'Miss.') { echo "selected"; }?> >Miss.</option>
                                                        </select>

                                                    <?php if (form_error('salutation_minor')) { ?>
                                                        <span class="error"><?php echo form_error('salutation_minor'); ?></span>
                                                    <?php } ?>
                                                </li>
                                                <li class="">&nbsp;&nbsp;&nbsp;</li> 
                                                <li class="">
                                                    <input type="text" id="name_for_notice_minor" name="name_for_notice_minor" placeholder="Old name" class="form-control alpha_only chk_updated"  autocomplete="off" minlength="4" maxlength="50"  value="<?php echo $select_notice_det->old_name; ?>">
                                                    <?php if (form_error('name_for_notice_minor')) { ?>
                                                        <span class="error"><?php echo form_error('name_for_notice_minor'); ?></span>
                                                    <?php } ?>
                                                </li>
                                                
                                                <li class="">&nbsp;,</li> 
                                                    <li class="">
                                                        <textarea id="address_minor" name="address_minor" placeholder="Address" class="form-control alpha_only chk_updated"  autocomplete="off" minlength="4" maxlength="200"><?php echo $select_notice_det->address; ?></textarea>
                                                        <?php if (form_error('address_minor')) { ?>
                                                            <span class="error"><?php echo form_error('address_minor'); ?></span>
                                                        <?php } ?>
                                                    </li>
                                                <li class=""> &nbsp;&nbsp;have changed my &nbsp;&nbsp;</li> 
                                                <li>
                                                        <select class="form-control chk_updated1" id="son_daughter" name="son_daughter">
                                                        <option selected="selected" value="Son's"  <?php if($select_notice_det->son_daughter == "Son's") { echo "selected"; }?>  >Son's</option>
                                                        <option value="Daughter's" <?php if($select_notice_det->son_daughter == "Daughter's") { echo "selected"; }?> >Daughter's</option>
                                                        </select>

                                                    <?php if (form_error('son_daughter')) { ?>
                                                        <span class="error"><?php echo form_error('son_daughter'); ?></span>
                                                    <?php } ?>
                                                </li>
                                                <li class="">&nbsp;&nbsp;name from&nbsp;&nbsp;</li>
                                                <li class="">
                                                        <input type="text" id="old_name_minor" name="old_name_minor" placeholder="Old Name" class="form-control alpha_only chk_updated"  autocomplete="off" minlength="4" maxlength="50"  value="<?php echo $select_notice_det->old_name_1; ?>" >
                                                    <?php if (form_error('old_name_minor')) { ?>
                                                        <span class="error"><?php echo form_error('old_name_minor'); ?></span>
                                                    <?php } ?>
                                                </li>
                                                <li class="">&nbsp;&nbsp; to &nbsp;&nbsp;</li> 
                                                <li class="">
                                                    <input type="text" id="new_name_minor" name="new_name_minor" placeholder="New Name" class="form-control alpha_only chk_updated"  autocomplete="off" minlength="4" maxlength="50"  value="<?php echo $select_notice_det->new_name; ?>" >
                                                    <?php if (form_error('new_name_minor')) { ?>
                                                        <span class="error"><?php echo form_error('new_name_minor'); ?></span>
                                                    <?php } ?>
                                                </li>
                                                <li class=""> .&nbsp;&nbsp;Henceforth,&nbsp;&nbsp; </li>
                                                <li>
                                                        <select class="form-control chk_updated1" id="gender" name="gender">
                                                        <option selected="selected" value="He"  <?php if($select_notice_det->gender == "He") { echo "selected"; }?>  >He</option>
                                                        <option value="She" <?php if($select_notice_det->gender == "She") { echo "selected"; }?> >She</option>
                                                        </select>

                                                    <?php if (form_error('gender')) { ?>
                                                        <span class="error"><?php echo form_error('gender'); ?></span>
                                                    <?php } ?>
                                                </li>
                                                <li>shall be known as</li>
                                                <li class="">
                                                    <input type="text" id="new_name_one_minor" name="new_name_one_minor" placeholder="New Name" class="form-control alpha_only chk_updated"  autocomplete="off" minlength="4" maxlength="50" value="<?php echo $select_notice_det->new_name_1; ?>">
                                                    <?php if (form_error('new_name_one_minor')) { ?>
                                                        <span class="error"><?php echo form_error('new_name_one_minor'); ?></span>
                                                    <?php } ?>
                                                </li><br><br>
                                                <li class=""> for all purposes.&nbsp;&nbsp;</li>
                                                <br><br>
                                            </ul>
                                            <ul class="div_sign">
                                                <li>
                                                    <input type="text" id="new_name_two_minor" name="new_name_two_minor" placeholder="Signature" class="form-control alpha_only chk_updated paragrap-space"  autocomplete="off" minlength="4" maxlength="50"  value="<?php echo $select_notice_det->sign_name; ?>">
                                                    <?php if (form_error('new_name_two_minor')) { ?>
                                                        <span class="error"><?php echo form_error('new_name_two_minor'); ?></span>
                                                    <?php } ?>
                                                </li>
                                            </ul>
                                        </div>
                                    </div>
                                 
                                <?php } else { ?>
                                    <!-- <?php //echo 'xyz';?> -->
                                    <div class="header-gazette">  
                                    <input type="hidden" id='val_chk_updated' name='val_chk_updated'>
                                    <p class="sub-head"><h3>Notice</h3></p>  
                                    <div class="gazette-nav-bar left-align-text">
                                        <ul> <li class="">By virtue of an affidavit sworn before the Executive &nbsp;&nbsp;</li>
                                        <li>
                                                <select   class="form-control paragrap-space" id="approver" name="approver">
                                                    <option selected="selected" value="Executive Magistrate" <?php if($select_notice_det->approver == 'Executive Magistrate') { echo "selected"; }?>>Executive Magistrate</option>
                                                    <option value="Notary Public" <?php if($select_notice_det->approver == 'Notary Public') { echo "selected"; }?>>Notary Public</option>
                                                </select>
                                                <?php if (form_error('approver')) { ?>
                                                    <span class="error"><?php echo form_error('approver'); ?></span>
                                                <?php } ?>
                                            </li>
                                            <li class="">
                                                <input type="text" id="place" name="place" placeholder="Place" class="form-control alpha_only chk_updated" autocomplete="off" minlength="4" value="<?php echo $select_notice_det->place; ?>">
                                                <?php if (form_error('place')) { ?>
                                                    <span class="error"><?php echo form_error('place'); ?></span>
                                                <?php } ?>
                                            </li>
                                            <li class="">&nbsp;&nbsp;on Dated &nbsp;&nbsp;</li>
                                            <li class="">
                                                    <input type="text" id="notice_date" name="notice_date" placeholder="DD-MM-YYYY" class="form-control chk_updated"  autocomplete="off" value="<?php echo $select_notice_det->date; ?>">
                                                <?php if (form_error('notice_date')) { ?>
                                                    <span class="error"><?php echo form_error('notice_date'); ?></span>
                                                <?php } ?>
                                            </li>
                                            <li class="">&nbsp;&nbsp;,&nbsp;&nbsp;I ,&nbsp;&nbsp;</li>                               
                                            <li>
                                            <select class="form-control chk_updated1" id="salutation" name="salutation">
                                                <option>Select salutation</option>
                                            <option selected="selected" value="Mr."  <?php if($select_notice_det->salutation == 'Mr.') { echo "selected"; }?>  >Mr.</option>
                                            <option value="Mrs." <?php if($select_notice_det->salutation == 'Mrs.') { echo "selected"; }?> >Mrs.</option>
                                            <!-- <option value="Smt." <?php //if($select_notice_det->salutation == 'Smt.') { echo "selected"; }?> >Smt.</option> -->
                                            <option value="Miss." <?php if($select_notice_det->salutation == 'Miss.') { echo "selected"; }?> >Miss.</option>
                                            </select>

                                                <?php if (form_error('salutation')) { ?>
                                                    <span class="error"><?php echo form_error('salutation'); ?></span>
                                                <?php } ?>
                                            </li>
                                            <li class="">&nbsp;&nbsp;&nbsp;</li> 
                                            <li class="">
                                                <input type="text" id="name_for_notice" name="name_for_notice" placeholder="Old name" class="form-control alpha_only chk_updated"  autocomplete="off" minlength="4" maxlength="50"  value="<?php echo $select_notice_det->old_name; ?>">
                                                <?php if (form_error('name_for_notice')) { ?>
                                                    <span class="error"><?php echo form_error('name_for_notice'); ?></span>
                                                <?php } ?>
                                            </li>
                                            <li class="">&nbsp;,</li> 
                                                <li class="">
                                                    <textarea id="address" name="address" placeholder="Address" class="form-control alpha_only chk_updated"  autocomplete="off" minlength="4" maxlength="200"><?php echo $select_notice_det->address; ?></textarea>
                                                    <?php if (form_error('address')) { ?>
                                                        <span class="error"><?php echo form_error('address'); ?></span>
                                                    <?php } ?>
                                                </li>
                                            <li class=""> &nbsp;&nbsp;have changed my name from&nbsp;&nbsp;</li> 
                                            <li class="">
                                                    <input type="text" id="old_name" name="old_name" placeholder="Old Name" class="form-control alpha_only chk_updated"  autocomplete="off" minlength="4" maxlength="50"  value="<?php echo $select_notice_det->old_name_1; ?>" >
                                                <!-- <?php /*/if (form_error('old_name')) { ?>
                                                    <span class="error"><?php echo form_error('old_name'); ?></span>
                                                <?php } */?> -->
                                            </li>
                                            <li class="">&nbsp;&nbsp; to &nbsp;&nbsp;</li> 
                                            <li class="">
                                                <input type="text" id="new_name" name="new_name" placeholder="New Name" class="form-control alpha_only chk_updated"  autocomplete="off" minlength="4" maxlength="50"  value="<?php echo $select_notice_det->new_name; ?>" >
                                                <?php if (form_error('new_name')) { ?>
                                                    <span class="error"><?php echo form_error('new_name'); ?></span>
                                                <?php } ?>
                                            </li><li class =""> &nbsp;.</li>
                                            <li class="">&nbsp;&nbsp;Henceforth,&nbsp;&nbsp;I shall be known as&nbsp;&nbsp; </li> 
                                            <li class="">
                                                <input type="text" id="new_name_one" name="new_name_one" placeholder="New Name" class="form-control alpha_only chk_updated"  autocomplete="off" minlength="4" maxlength="50" value="<?php echo $select_notice_det->new_name_1; ?>">
                                                <?php if (form_error('new_name_one')) { ?>
                                                    <span class="error"><?php echo form_error('new_name_one'); ?></span>
                                                <?php } ?>
                                            </li>&nbsp;
                                            <li class=""> for all purposes.&nbsp;&nbsp;</li>
                                            <br><br>
                                        </ul>
                                        <ul class="div_sign">
                                            <li>
                                                <input tyvpe="text" id="new_name_two" name="new_name_two" placeholder="Signature" class="form-control alpha_only chk_updated paragrap-space"  autocomplete="off" minlength="4" maxlength="50"  value="<?php echo $select_notice_det->sign_name; ?>">
                                                <?php if (form_error('new_name_two')) { ?>
                                                    <span class="error"><?php echo form_error('new_name_two'); ?></span>
                                                <?php } ?>
                                            </li>
                                        </ul>
                                    </div>
                                    </div>
                                <?php } } ?>
                                
                                <div class="boxs-footer text-right bg-tr-black lter dvd dvd-top">
                                    <button type="submit" class="btn btn-raised btn-success" id="form4Submit">Re-submit</button>
                                </div>
                            <?php echo form_close(); ?>
                        </div>
                    </section>
                </div>
            </div>
        <?php } ?>
    </div>
</section>
<!-- CONTENT END -->


<script>
    $(document).ready(function() {


    $('.check-item').on('change', function() {
       
        // check if all checkboxes are checked
        if ($('.check-item:checked').length === $('.check-item').length) {
            
          $('#pay_online_button').fadeIn(); // show button
        } else {
          $('#pay_online_button').fadeOut(); // hide button
        }
      });


        // Attach a click event handler to the "Proceed To Pay Online" button
        // $("#pay_online_button").click(function(e) {
      
        //     // Retrieve the values of the hidden input fields
        //     // var deptCode = $("input[name='deptCode']").val();
        //     // var depRefId = $("input[name='dep_ref_id']").val();
        //     // var amount = $("input[name='amount']").val();


        //     var description = $("input[name='description']").val();
        //     var encryptedMsg = $("input[name='msg']").val();

        //     console.log(depRefId);
        //     return false;
        //     e.preventDefault();

        //     Send AJAX request to store the data
        //     $.ajax({
        //         url: "<?php echo base_url('DepRefController/storeDeptRefData'); ?>",
        //         type: "POST",
        //         data: {
        //             // deptCode: deptCode,
        //             // dep_ref_id: depRefId,
        //             // amount: amount,
        //             description: description,
        //             encryptedMsg: encryptedMsg
        //         },
        //         dataType: "json",
        //         success: function(response) {
        //             console.log(response);
        //             if (response.success) {
        //                 console.log("Data stored successfully.");

        //                 // console.log(response.data_array);
        //                 // Submit the form if data is stored successfully
        //                 $("#form1").submit();
        //             } else {
        //                 console.error("Failed to store data.");
        //                 // Handle error scenario
        //             }
        //         },
        //         error: function(xhr, status, error) {
        //             console.error("AJAX request failed:::", error);
        //             return false;
        //             // Handle error scenario
        //         }
        //     });
        // });
    });
</script>




<script type="text/javascript" nonce="a6b9a780936c8e980939086f618dded2">
    $(document).ready(function () {

        $("#new_name_minor").keyup(function(){
            let get_new_name = $("#new_name_minor").val();
            $("#new_name_one_minor").val(get_new_name);
        });

        $("#name_for_notice_minor").keyup(function(){
            let get_new_name_1 = $("#name_for_notice_minor").val();
            $("#new_name_two_minor").val(get_new_name_1);
        });

        $("#name_for_notice").keyup(function(){
            let get_new_name_2 = $("#name_for_notice").val();
            $("#new_name").val(get_new_name_2);
            $("#new_name_one").val(get_new_name_2);
            $("#new_name_two").val(get_new_name_2);
        });

        /*
        * View Image on Popbox of Lightbox
        */
        
        lightbox.option({
            'resizeDuration': 200,
            'wrapAround': true
        })
        //loader

        $('#form_name_surname_edit').submit(function() {
            $('.loader').show(); 
        return true;
        });

        $('.button_type_application').on('click',function(){
            if($(this).attr('name') == "Return"){
                $('#button_type').val('Return');
                $('#return_forward_processor').html('Return to Applicant');
            }else{
                $('#button_type').val('Forward');
                $('#return_forward_processor').html('Forward to Verifier');
            }
        });

        $('.button_virifier').on('click',function(){
            if($(this).attr('name') == "Return"){
                $('#button_type_veri').val('Return');
                $('#return_forward_verifier').html('Return to Applicant');
            }else{
                $('#button_type_veri').val('Forward');
                $('#return_forward_verifier').html('Forward to Approver');
            }
        });

        $('.button_approver').on('click',function(){
            if($(this).attr('name') == "Return"){
                $('#button_type_approver').val('Return');
                $('#return_forward_approver').html('Return to Applicant');
            }else{
                $('#button_type_approver').val('Approve');
                $('#return_forward_approver').html('Approve');
            }
        });

        $("#notice_date").datepicker({
            dateFormat: 'dd-mm-yy',
            autoclose: true,
            todayHighlight: true,
            maxDate: new Date(),
            onSelect: function(selected) {
                $("#t_date").datepicker("option","minDate", selected)
            }
        });
        
          /*
        * Restrict alphabetic input only using class number_only in the input field
        */
       $('input.alpha_only').keypress(function (e) {
           var regex = new RegExp("^[a-zA-Z\ ]$");
           var str = String.fromCharCode(!e.charCode ? e.which : e.charCode);
           if (regex.test(str)) {
               return true;
           }
           e.preventDefault();
           return false;
       });
       
         /*
        * Restrict number input only using class number_only in the input field
        */
       $('input.number_only').keyup(function (e) {
           if (/\D/g.test(this.value)) {
               // Filter non-digits from input value.
               this.value = this.value.replace(/\D/g, '');
           }
       });
       
        $('.chk_file').on('change',function(){
           var val = this.files[0].size;
           var filesize = 5242880;
           var id = $(this).attr("id");
           var str = id.split('_');
           //alert(id);
           if(val > filesize){
               $('.notif_file_'+str[1]).css('display','block');
               $('.notif_file_'+str[1]).html('File size must be less than 5 MB'); 
           } else {
               $('.notif_file_'+str[1]).css('display','none');
               $('.notif_file_'+str[1]).html('');
           }
        });
        
        $("#form_name_surname_edit").validate({
            rules: {
                state_id: {
                    required: true
                },
                district_id: {
                    required: true
                },
                block_ulb_id: {
                    required: true
                },
                address_1: {
                    required: true,
                    minlength: 5,
                    maxlength: 200
                },
                pin_code: {
                    required: true,
                    minlength: 6,
                    maxlength: 6
                },
                 place: {
                    required: true,
                    minlength: 4
                },
                notice_date: {
                    required: true
                },
                salutation: {
                    required: true
                },
                name_for_notice: {
                    required: true,
                    minlength: 4,
                    maxlength: 50
                },
                age: {
                    required: true
                },
                old_name: {
                    required: true,
                    minlength: 4,
                    maxlength: 50,
                    // equalTo: "#name_for_notice"
                },
                new_name: {
                    required: true,
                    minlength: 4,
                    maxlength: 50
                },
                new_name_one: {
                    required: true,
                    minlength: 4,
                    maxlength: 50,
					equalTo: "#new_name"
                },
                new_name_two: {
                    required: true,
                    minlength: 4,
                    maxlength: 50,
					equalTo: "#new_name"
                }
            },
            messages: {
                state_id: {
                    required: "Please select state"
                },
                district_id: {
                    required: "Please select district"
                },
               block_ulb_id: {
                    required: "Please select block/ulb"
                },
                address_1: {
                    required: "Please enter address ",
                    minlength: "Please enter minimum 5 characters",
                    maxlength: "Please enter maximum 200 characters"
                },               
                pin_code: {
                    required: "Please select pin code",
                    minlength: "Pincode must be 6 digit",
                    maxlength: "Pincode must be 6 digit"
                },
                place: {
                    required: "Please enter place",
                    minlength: "Please enter minimum 4 characters",
                },
                notice_date: {
                    required: "Please select date"
                },
                salutation: {
                    required: "Please select salutation"
                },
                name_for_notice: {
                    required: "Please enter name",
                    minlength: "Please enter minimum 4 characters",
                    maxlength:  "Please enter minimum 50 characters",
                },
                age: {
                    required: "Please enter age"
                },
                old_name: {
                    required: "Please enter name",
                     minlength: "Please enter minimum 4 characters",
                    maxlength:  "Please enter minimum 50 characters",
                    equalTo: "Name must be same as above name"
                },
                new_name: {
                    required: "Please enter name",
                    minlength: "Please enter minimum 4 characters",
                    maxlength:  "Please enter minimum 50 characters",
                },
                new_name_one: {
                    required: "Please enter name",
                    minlength: "Please enter minimum 4 characters",
                    maxlength:  "Please enter minimum 50 characters",
					equalTo: "Name must be same as New Name"
                },
                new_name_two: {
                    required: "Please enter signature",
                    minlength: "Please enter minimum 4 characters",
                    maxlength:  "Please enter minimum 50 characters",
					equalTo: "Signature must be same as New Name"
                }
            }
        });
       
        
       
        
        
        <?php if ($gz_dets->govt_employee == 1) { ?>
            $("#doc_5").css('display', 'block');
            $("#doc_6").css('display', 'block');
            $("#doc_edit_5").css('display', 'block');
            $("#doc_edit_6").css('display', 'block');
        <?php } else { ?>
            $("#doc_5").css('display', 'none');
            $("#doc_6").css('display', 'none');
            $("#doc_edit_5").css('display', 'none');
            $("#doc_edit_6").css('display', 'none');
        <?php } ?>
            
        <?php if ($gz_dets->is_minor == 1) { ?>
            $("#doc_7").css('display', 'block');
            $("#doc_edit_7").css('display', 'block');
        <?php } else { ?>
            $("#doc_7").css('display', 'none');
            $("#doc_edit_7").css('display', 'none');
        <?php } ?>
        

        <?php if ($gz_dets->govt_employee == 1) { ?>
            $("#docu_5").css('display', 'block');
            $("#docu_6").css('display', 'block');
            $("#docu_edit_5").css('display', 'block');
            $("#docu_edit_6").css('display', 'block');
        <?php } else { ?>
            $("#docu_5").css('display', 'none');
            $("#docu_6").css('display', 'none');
            $("#docu_edit_5").css('display', 'none');
            $("#docu_edit_6").css('display', 'none');
        <?php } ?>
            
        <?php if ($gz_dets->is_minor == 1) { ?>
            $("#docu_7").css('display', 'block');
            $("#docu_edit_7").css('display', 'block');
        <?php } else { ?>
            $("#docu_7").css('display', 'none');
            $("#docu_edit_7").css('display', 'none');
        <?php } ?>
            
        $("#forward_form_pro").validate ({
            rules : {
                remarks: {
                    required: true,
                    minlength: 5
                }
            },
            messages: {
                remarks: {
                    required: "Please enter remarks",
                    minlength: "Remarks should be atleast 5 characters"
                }
            }
        });
        
        $("#forward_form_veri").validate ({
            rules : {
                remarks: {
                    required: true,
                    minlength: 5
                }
            },
            messages: {
                remarks: {
                    required: "Please enter remarks",
                    minlength: "Remarks should be atleast 5 characters"
                }
            }
        });
        
        $("#approve_form_approver").validate({
            rules : {
                remarks: {
                    required: true,
                    minlength: 5
                }
            },
            messages: {
                remarks: {
                    required: "Please enter remarks",
                    minlength: "Remarks should be atleast 5 characters"
                }
            }
        });
        
        $("#reject_form_approver").validate ({
            rules : {
                remarks: {
                    required: true,
                    minlength: 5
                }
            },
            messages: {
                remarks: {
                    required: "Please enter remarks",
                    minlength: "Remarks should be atleast 5 characters"
                }
            }
        });
        
        $("#reject_form_pro").validate ({
            rules : {
                remarks: {
                    required: true,
                    minlength: 5
                }
            },
            messages: {
                remarks: {
                    required: "Please enter remarks",
                    minlength: "Remarks should be atleast 5 characters"
                }
            }
        });

        $("#state_id").on('change', function () {
            $(".loader").show();
            var id = $(this).val();
            $.ajax({
                type: "POST",
                url: "<?php echo base_url(); ?>applicants_login/get_districts_list",
                data: {
                    id: id,
                    "<?php echo $this->security->get_csrf_token_name(); ?>": "<?php echo $this->security->get_csrf_hash(); ?>"
                },
                success: function (data) {
                    $('#district_id').html(data);
                    $(".loader").hide();
                }
            });
        });

        $("#district_id").on('change', function () {
            $(".loader").show();
            var id = $(this).val();
                $.ajax({
                    type: "POST",
                    url: "<?php echo base_url(); ?>applicants_login/get_block_ulb",
                    data: {
                        id: id,
                        "<?php echo $this->security->get_csrf_token_name(); ?>": "<?php echo $this->security->get_csrf_hash(); ?>"
                    },
                    success: function (data) {
                        $('#block_ulb_id').html(data);
                        $(".loader").hide();
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