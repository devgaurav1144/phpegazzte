<?php defined('BASEPATH') OR exit('No direct script access allowed'); ?>
<!-- CONTENT -->
<script src="https://code.jquery.com/jquery-3.4.1.min.js" type="text/javascript" nonce="a6b9a780936c8e980939086f618dded2"></script>
<script type="text/javascript" nonce="a6b9a780936c8e980939086f618dded2" src="<?php echo base_url(); ?>assets/js/croppie.js"></script>
<script type="text/javascript" nonce="a6b9a780936c8e980939086f618dded2" src="<?php echo base_url(); ?>assets/js/croppie.min.js"></script>
<link href="<?php echo base_url(); ?>assets/js/vendor/lightbox2-dev/dist/css/lightbox.css" rel="stylesheet" nonce="8f0882ce3be14f201cadd0eff5726cbd"/>
<script src="<?php echo base_url(); ?>assets/js/vendor/lightbox2-dev/src/js/lightbox.js" type="text/javascript" nonce="a6b9a780936c8e980939086f618dded2"></script>
<link rel="stylesheet" type="text/css" href="<?php echo base_url(); ?>assets/css/croppie.css"/>
<script src="<?php echo base_url(); ?>assets/js/vendor/filestyle/bootstrap-filestyle.min.js" type="text/javascript" nonce="a6b9a780936c8e980939086f618dded2"></script> 
<style  rel="stylesheet" nonce="8f0882ce3be14f201cadd0eff5726cbd">
    #html5-watermark {
        display:none !important;
    }
    #labelOverlay {
        width: 90px;
        height: 45px;
        position: absolute;
        top: 150px;
        left: 205px;
        text-align: center;
        cursor: default;
    }
    #labelOverlay p {
        line-height: 0.3;
        padding:0;
        margin: 8px;
    }
    #labelOverlay p.used-size {
        line-height: 1;
        font-size: 13pt;
        color: #8e8e8e;
    }
    #labelOverlay p.total-size {
        line-height: 0.5;
        font-size: 13pt;
        color: #04c;
    }
    .thumb-image {
        width: 100px;
        height: 100px;
        float: left;
        object-fit: cover;
        border: 1px solid #ddd;
    }
    #rej_btn{
        width: 119px;
        height: 36px;
        margin-left: 178px;
    }
    /* #applicant{
        width: 145px;
        height: 36px;
        margin-left: 150px;
        z-index: 9999;
    } */
    /* #ver_btn{
        width: 80px;
        height: 36px;
        margin-left: 39px;
    }  */
    .btn-raised{
        text-transform: capitalize;
        font-size: 13px;
        padding: 5px 10px !important;
        margin: 13px 5px 0 0 !important;
        float: right;
    }
    .custom-btn{
        text-transform: none;
        font-size: 13px;
        padding: 10px !important;
        margin: 0 !important;
        float: none;
    }
    #btn_div{
        margin-top: -20px;
    }
    #reject_button{
        width: 80px;
        height: 30px;
        margin-left: 99px;
    }
    
     /* #igr_ver_btn{
        width: 80px;
        height: 36px;
        margin-left: 39px;
    }  */
    /* #igr_reject_button{
        width: 80px;
        height: 36px;
        margin-left: 99px;
        z-index: 9999;
    } */
    #igr_app_btn{
        width: 80px;
        height: 36px;
        margin-left: 200px;
    } 
    #igr_ct_ver_return{
        width: 145px;
        height: 36px;
        margin-left: 150px;
    }
    
     /* #forward_to_publish{
        width: 80px;
        height: 36px;
        margin-left: 200px;
    }  */
    #make_payment_btn{
        
        margin-left: 200px;
    } 
    /*    #resubmit {
            margin-top: 94px;
        }*/
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
    .file_icons_add{
        padding: 0;
        font-size: 14px;
     }
     .file_icons_add .i{
        font-size: 14px;
     }
     table {
    width: 100%;
    }
    th{
    text-align: center;
    }
    .hb-blush{
        font-weight:500 !important;
    }
    .bootstrap-filestyle .btn-default {
        margin-bottom: 7px !important;
    }
    .btn.btn-default:hover {
    background-color: #4caf50 !important;
    }
    .input-icons i {
            position: absolute;
    }
        
    .input-icons {
        width: 100%;
        margin-bottom: 10px;
    }
        
    .icon {
        padding: 10px;
        min-width: 40px;
    }
        
    .input-field {
        width: 100%;
        padding: 10px;
        text-align: center;
    }
    .offline_btn_color{
        background-color: #dfa90f !important;
        color: white !important;
    }

    .pay_btn_style{
        margin-right: 13px !important;
        font-size: 11px !important;
        padding: 7px 20px !important;
    }
</style>
<!-- /content area -->


<!--/ CONTENT -->
<div class="loader"></div>
<section id="content">
    <div class="page page-forms-validate">
        <!-- bradcome -->
        <!-- <ol class="breadcrumb">
            <li><a href="<?php //echo base_url(); ?>applicants_login/dashboard">Dashboard</a></li>
            <li><a href="<?php //echo base_url(); ?>applicants_login/partnership_details_list">Change of Partnership Status details</a></li>
            <li class="active">Details</li>
        </ol> -->

        <!-- <div class="bg-light lter b-b wrapper-md mb-10">
            <div class="row">
                <div class="col-sm-6 col-xs-12">
                    <h1 class="h3 m-0">Change of Partnership </h1>
                </div>
            </div>
        </div> -->
        <!-- row -->
        <div class="row">
            <div class="col-md-12">
                
                <section class="boxs">
                    
                    <div class="boxs-header">
                      <div class="row">
                        <div class="col-md-8">
                            <h3 class="custom-font hb-blue"><strong>View Change of Partnership Details</strong></h3>
                        </div>
                        <div class="col-md-4" id="btn_div" >
                            
                            <?php
                            if ($this->session->userdata('is_c&t')) {
                                    
                                if($this->session->userdata('is_verifier_approver') == 'Verifier' && $this->session->userdata('is_c&t_module') == 1) {  
                                        
                                    if($gz_dets->cur_status == 10 || $gz_dets->cur_status == 27 || $gz_dets->cur_status == 28) {  ?>
                                    <!-- <?php //} else if( $gz_dets->cur_status == 12 || $gz_dets->cur_status == 15 ){ ?> -->
                                    
                                        <button type="submit" class="btn btn-raised btn-warning" id="applicant"  data-target="#ret_applicant"  data-toggle="modal" >Return to Applicant </button>
                                        <button type="submit" class="btn btn-raised btn-success " id="ver_btn" data-toggle="modal" data-target="#ct_for_model" >Forward</button>

                                    <?php } }
                                    
                                    if($this->session->userdata('is_verifier_approver') == 'Approver' && $this->session->userdata('is_c&t_module') == 1) {
                                        if($gz_dets->cur_status == 2 || $gz_dets->cur_status == 9 || $gz_dets->cur_status == 29 || $gz_dets->cur_status == 31 ) { ?>
                                        
                                            <button type="submit" class="btn btn-raised btn-warning" id="applicant"  data-target="#ret_applicant"  data-toggle="modal" >Return to Applicant </button>
                                            <button type="submit" class="btn btn-raised btn-success" id="forward_to_publish" data-toggle="modal" data-target="#forward_to_pub_model" >Forward</button>
                                            
                                            <!-- <button class="btn btn-raised btn-danger" data-toggle="modal" data-target="#myModal3" id="reject_button" >Reject</button> -->
                                            <button type="submit" class="btn btn-raised btn-danger" data-toggle="modal" data-target="#ct_approver_reject" id="ct_app_reject" >Reject</button>
                                            <!-- <button type="submit" class="btn btn-raised btn-success" id="app_btn" data-toggle="modal" data-target="#ct_app_ret_model" >Approve</button> -->
                                        
                                        <?php }
                                    //   if($gz_dets->cur_status == 5 ) { ?>
                                    
                                    
                                    
                                       <?php }  
                                    if($this->session->userdata('is_verifier_approver') == 'Processor' && $this->session->userdata('is_c&t_module') == 1) {  
                                            
                                        if($gz_dets->cur_status == 1 || $gz_dets->cur_status == 20) {  ?>
                                        <!-- <?php //} else if( $gz_dets->cur_status == 12 || $gz_dets->cur_status == 15 ){ ?> -->
                                        
                                        <button type="submit" class="btn btn-raised btn-warning" data-toggle="modal" data-target="#ret_applicant" >Return to Applicant </button>
                                        <button type="submit" class="btn btn-raised btn-success " id="ver_btn" data-toggle="modal" data-target="#ct_for_model" >Forward</button>
                                    <?php } } }?>
                                    
                                <?php  if ($this->session->userdata('is_igr')) {
                                    
                                    if($this->session->userdata('is_verifier_approver') == 'Verifier') {  
                                        if($gz_dets->cur_status == 3 || $gz_dets->cur_status == 21 || $gz_dets->cur_status == 23 ) {  ?>
                                        
                                            <!-- <button class="btn btn-raised btn-danger" data-toggle="modal" data-target="#myModal_igr" id="igr_reject_button" >Reject</button> -->
                                            
                                            <button type="submit" class="btn btn-raised btn-warning" data-toggle="modal" data-target="#ret_applicant" >Return to Applicant </button>
                                            <button type="submit" class="btn btn-raised btn-success " id="igr_ver_btn"  data-toggle="modal" data-target="#forward_igr_app">Forward</button>
                                        
                                        <?php }

                                    }
                                    
                                    if($this->session->userdata('is_verifier_approver') == 'Approver') {

                                    if($gz_dets->cur_status == 11 || $gz_dets->cur_status == 4 || $gz_dets->cur_status == 26) { ?>
                                    
                                    <!-- <button type="submit" class="btn btn-raised btn-success" id="igr_app_btn"   data-target="#rej_app_model" data-toggle="modal"  >Approve</button> -->
                                    <button type="submit" class="btn btn-raised btn-warning" data-toggle="modal" data-target="#ret_applicant" >Return to Applicant </button>
                                    
                                    <button type="submit" class="btn btn-raised btn-success " id="igr_approver_btn"  data-toggle="modal" data-target="#forward_ct_vrifier">Forward</button>
                                    
                                    <button type="submit" class="btn btn-raised btn-danger" data-toggle="modal" data-target="#myModal_igr" id="igr_reject_button">Reject</button>
                                
                                <?php } } } ?>
                        </div>
                        </div>
                    </div>
                    
                
                        <div class="boxs-body">                       
                           <div class="border_data">
                            <?php if (!empty($gz_dets)) { ?>
                                <div class="row" style="margin-top: 10px;">
                                    <div class="form-group col-md-6">
                                        <label for="username">Applicant Name : </label>
                                        <?php echo $gz_dets->name; ?>
                                    </div>
                                    <div class="form-group col-md-6">
                                        <label for="username">Partnership Firm Name : </label>
                                        <?php echo $gz_dets->partnership_firm_name; ?>
                                    </div>
                                    <div class="form-group col-md-6">
                                        <label for="email">Gazette Type : </label>
                                        <?php echo $gz_dets->gazette_type; ?>
                                    </div>
                                </div>
                            <?php } ?>
                            <div class="row">
                                
                                  <!-- <div class="form-group col-md-6">
                                      <label for="email">District : </label>
                                       <?php //echo $gz_dets->district_name; ?>
                                    </div>  -->
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
                                    <label for="username">Status : </label>
                                    <?php echo $gz_dets->status_det; ?>
                                </div>
                                <?php 
                                //  $select_det_remark =  $this->Applicants_login_model->get_cur_rem($gz_dets->cur_status);
                                $remarks = $this->Applicants_login_model->get_remarks_on_change_partnership($gz_dets->id, $gz_dets->cur_status);
                                if(!empty($remarks)) { ?>
                                <div class="form-group col-md-6">
                                    <label for="email">Remark : </label>
                                    <?php 
                                   
                                    echo $remarks; ?>
                                </div>
                                <?php } ?>
                            </div>
                                 <div class="row" > 
                            <?php //print_r($tot_docus);
                            $file_type = "";
                            $i = 1;
                            $j = 1;
                            foreach ($tot_docus as $tot_docu) { ?>

                            <div class="form-group col-md-6">  
                                <?php if ($tot_docu->document_name == 'NOC_Notice of Outgoing_Retiring Partners') {?>
                                    <label for="email">NOC/Notice of Outgoing/Retiring Partners</label><br>
                                <?php } elseif($tot_docu->document_name == 'PAN Card of Incoming_Outgoing Partners') {?>
                                    <label for="email">PAN Card of Incoming/ Outgoing Partners</label>
                                <?php } elseif($tot_docu->document_name == 'Aadhaar Card of Incoming_Outgoing Partners'){?>
                                    <label for="email">Aadhaar Card of Incoming/ Outgoing Partners</label>
                                <?php } else {?>                                      
                                <label> <?php echo trim(ucfirst($tot_docu->document_name)); ?></label><br>
                                <?php }?>
                                
                                <?php if($tot_docu->document_name =='PAN Card of Incoming_Outgoing Partners') { ?>
                                                                
                                    <div class="row">     
                                    <?php if($i == 1 ){
                                        $image_pan = $this->Applicants_login_model->select_cur_path_pan($par_id);
                                        foreach($image_pan as $image ) {  ?>
                                    <div class="col-md-2">
                                            <?php $ext = pathinfo($image->pan_card_of_partnetship, PATHINFO_EXTENSION);
                                                if($ext == "pdf"){ ?>
                                                    <a href="<?php if(!empty($image->pan_card_of_partnetship)) { echo $image->pan_card_of_partnetship; } ?>" target="_blank"  class="file_icons"><i class="fa fa-file-pdf-o"></i></a>
                                                <?php } else {?>
                                                    <?php if(!empty($image->pan_card_of_partnetship)) { ?>
                                                            <a href="<?php echo $image->pan_card_of_partnetship; ?>" class="html5lightbox file_icons" data-width="480" data-height="320" data-group="mygroup" data-thumbnail="<?php echo $image->pan_card_of_partnetship; ?>" data-lightbox="$image->pan_card_of_partnetship"><i class="fa fa-image"></i></a>
                                                    <?php } ?>
                                                <?php }?>
                                        </div>
                                        <?php $i++; }
                                    } ?> 
                                    </div>
                            
                                <?php } else if($tot_docu->document_name =='Aadhaar Card of Incoming_Outgoing Partners')  { ?>
                                            
                                    <?php if($j == 1 ){ ?>    
                            
                                    <div class="row" > <?php
                                        $images_aadhar = $this->Applicants_login_model->select_cur_path_aadhar($par_id);
                                        foreach($images_aadhar as $image ) {  ?>
                                        <div class="col-md-2">
                                        <?php $ext = pathinfo($image->aadhar_card_of_partnetship, PATHINFO_EXTENSION);
                                            if($ext == "pdf"){ ?>
                                                <a href="<?php if(!empty($image->aadhar_card_of_partnetship)) { echo $image->aadhar_card_of_partnetship; } ?>" target="_blank"  class="file_icons"><i class="fa fa-file-pdf-o"></i></a>
                                            <?php } else {?>
                                                <a href="<?php if(!empty($image->aadhar_card_of_partnetship)) {  echo  $image->aadhar_card_of_partnetship;  } ?>" data-lightbox="$image->aadhar_card_of_partnetship"  class="file_icons"><i class="fa fa-image"></i></a>
                                            <?php }?>
                                        </div>
                                        <?php  $j++; }   } ?> 
                                    </div> <?php  } 
                                else { 
                                    $images = $this->Applicants_login_model->select_cur_path($par_id); 
                                    // $imagesPan = $this->Applicants_login_model->select_cur_path_pan($par_id);
                                    // $imagesAadhaar = $this->Applicants_login_model->select_cur_path_aadhar($par_id);
                                    //print_r($images);
                                    foreach($images as $image ) { 
                                        if($tot_docu->document_name == 'Previous Partnership Deed') { ?>
                                        <div class="row">
                                            <div class="col-md-3">
                                                <?php $ext = pathinfo($image->orignal_partnership_deed, PATHINFO_EXTENSION);
                                                if($ext == "pdf"){ ?>
                                                    <a href="<?php if(!empty($image->orignal_partnership_deed)) { echo $image->orignal_partnership_deed; } ?>" target="_blank"  class="file_icons"><i class="fa fa-file-pdf-o"></i></a>
                                                <?php } else {?>
                                                    <a href="<?php if(!empty($image->orignal_partnership_deed)) {
                                                    echo  $image->orignal_partnership_deed;  } ?>" data-lightbox="$image->orignal_partnership_deed"  class="file_icons"><i class="fa fa-image"></i></a>
                                                <?php }?>
                                            </div>
                                        </div>
                                        <?php } else if($tot_docu->document_name == 'Deed of Reconstitution of Partnership') { ?>
                                        <div class="row">
                                            <div class="col-md-3">
                                                <?php $ext = pathinfo($image->deed_of_reconstitution_of_partnership, PATHINFO_EXTENSION);
                                                if($ext == "pdf"){ ?>
                                                    <a href="<?php if(!empty($image->deed_of_reconstitution_of_partnership)) { echo $image->deed_of_reconstitution_of_partnership; } ?>" target="_blank"  class="file_icons"><i class="fa fa-file-pdf-o"></i></a>
                                                <?php } else {?>
                                                    <a href="<?php if(!empty($image->deed_of_reconstitution_of_partnership)) {   echo  $image->deed_of_reconstitution_of_partnership; } ?>" data-lightbox="$image->deed_of_reconstitution_of_partnership"  class="file_icons"><i class="fa fa-image"></i></a> 
                                                <?php } ?>
                                            </div>
                                        </div>
                                        <?php } else if(trim($tot_docu->document_name) == 'IGR Certificate') { ?>
                                        <div class="row">
                                            <div class="col-md-3">
                                                <?php $ext = pathinfo($image->igr_certificate_file, PATHINFO_EXTENSION);
                                                if($ext == "pdf"){ ?>
                                                    <a href="<?php if(!empty($image->igr_certificate_file)) { echo $image->igr_certificate_file; } ?>" target="_blank"  class="file_icons"><i class="fa fa-file-pdf-o"></i></a>
                                                <?php } else {?>
                                                    <a href="<?php if(!empty($image->igr_certificate_file)) { 
                                                    echo  $image->igr_certificate_file; } ?>" data-lightbox="$image->igr_certificate_file"  class="file_icons"><i class="fa fa-image"></i></a> 
                                                <?php }?>
                                            </div>
                                        </div>
                                        <?php } else if($tot_docu->document_name == 'Original Newspaper Advertisement') { ?>
                                        <div class="row">
                                            <div class="col-md-3">
                                                <?php $ext = pathinfo($image->orignal_newspaper_of_advertisement, PATHINFO_EXTENSION);
                                                    if($ext == "pdf"){ ?>
                                                        <a href="<?php if(!empty($image->orignal_newspaper_of_advertisement)) { echo $image->orignal_newspaper_of_advertisement; } ?>" target="_blank"  class="file_icons"><i class="fa fa-file-pdf-o"></i></a>
                                                    <?php } else {?>
                                                        <a href="<?php if(!empty($image->orignal_newspaper_of_advertisement)) { 
                                                        echo  $image->orignal_newspaper_of_advertisement; } ?>" data-lightbox="$image->orignal_newspaper_of_advertisement"  class="file_icons"><i class="fa fa-image"></i></a>
                                                    <?php }?>
                                            </div>
                                                
                                        </div>
                                        <?php } else if(trim($tot_docu->document_name) == 'NOC_Notice of Outgoing_Retiring Partners') { ?>
                                        <div class = "row">
                                            <div class="col-md-3">
                                                <?php $ext = pathinfo($image->noc_notice_of_outgoing, PATHINFO_EXTENSION);
                                                    if($ext == "pdf"){ ?>
                                                        <a href="<?php if(!empty($image->noc_notice_of_outgoing)) { echo $image->noc_notice_of_outgoing; } ?>" target="_blank"  class="file_icons"><i class="fa fa-file-pdf-o"></i></a>
                                                    <?php } else {?>
                                                        <a href="<?php if(!empty($image->noc_notice_of_outgoing)) { 
                                                        echo  $image->noc_notice_of_outgoing; } ?>" data-lightbox="$image->noc_notice_of_outgoing"  class="file_icons"><i class="fa fa-image"></i></a>
                                                    <?php } ?> 
                                            </div>
                                        </div>
                                        <?php } else if(trim($tot_docu->document_name) == 'Challan / Form V') { ?>
                                        <div class="row">
                                            <div class="col-md-3">
                                            <?php $ext = pathinfo($image->challan, PATHINFO_EXTENSION);
                                                if($ext == "pdf"){ ?>
                                                    <a href="<?php if(!empty($image->challan)) { echo $image->challan; } ?>" target="_blank"  class="file_icons"><i class="fa fa-file-pdf-o"></i></a>
                                                <?php } else {?>
                                                    <a href="<?php if(!empty($image->challan)) { 
                                                    echo  $image->challan; } ?>" data-lightbox="$image->challan"  class="file_icons"><i class="fa fa-image"></i></a> 
                                                <?php }?>
                                            </div>
                                        </div>
                                        <?php } else if($tot_docu->document_name == 'Notice in Softcopy') { ?>
                                        <div class="row">
                                            <div class="col-md-6">
                                            <a href="<?php if(!empty($image->pdf_for_notice_of_softcopy)) { echo $image->pdf_for_notice_of_softcopy; } ?>" target="_blank"  class="file_icons"><i class="fa fa-file-pdf-o"></i></a>
                                            </div>
                                        </div>
                                        <?php } 
                                    }   } ?>
                                </div>                                    
                                <?php  } ?> 
                                </div>  
                            </div> 
                             <!-- <div class="row">
                                <div class="pdf-container">
                                    <embed src="<?php //echo $details->pdf_for_notice_of_softcopy; ?>" type="application/pdf" width="100%" height="650px" internalinstanceid="8">
                                </div>
                            </div> -->
                        <div class="panel-group" id="accordion">
                           <div class="panel panel-default">
                               <div class="panel-heading">
                                   <h4 class="panel-title">
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
                                                            <th width="350">Status</th>
                                                            <th>Remarks</th>
                                                            <th width="180">Date</th>
                                                        </tr>
                                                    </thead>
                                                    <tbody>
                                                        <ul class="list-group">
                                                            <?php if (!empty($status_list)) { ?>
                                                                <?php foreach ($status_list as $status) { //echo $status->id;
                                                                    $select_det_remark =  $this->Applicants_login_model->select_det_remark($status->id); 
                                                                    //print_r($select_det_remark); ?>
                                                                    <tr>
                                                                    <td ><?php echo $status->status_det; ?></td>
                                                                    <td><?php if(empty($select_det_remark->remark)) {
                                                                    echo '';
                                                                    } else {
                                                                    echo $select_det_remark->remark;
                                                                    } ?></td>
                                                                    <td><span class="badge bg-default hstry_class"><?php echo get_nice_datetime($status->created_at); ?></span></td>
                                                                    </tr>
                                                                    <!-- <li class="list-group-item"><span class="badge bg-default hstry_class"><?php //echo get_formatted_datetime($status->created_at); ?></span><?php //if(empty($select_det_remark->remark)) {
                                                                    //echo //$status->status_det;
                                                                    //} //else {
                                                                    //echo //$status->status_det .'('.$select_det_remark->remark.')';
                                                                    //} ?> </li> -->
                                                                <?php } ?>
                                                            <?php } ?>
                                                        </ul>
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
                                   <h4 class="panel-title">
                                       <a data-toggle="collapse" data-parent="#accordion" href="#collapse_2">Document History</a>
                                   </h4>
                               </div>
                               <div id="collapse_2" class="panel-collapse collapse">
                                   <div class="panel-body panel-body-no-padding">
                                       <div class="row">
                                           <div class="col-lg-12 col-md-12">
                                                <?php if (!empty($docu_list)) { ?>
                                                    <?php foreach ($docu_list as $list) { ?>
                                                    <div class="form-group  col-md-6">
                                                        <?php if ($list->docu == 'NOC_Notice of Outgoing_Retiring Partners') {?>
                                                            <label for="email">NOC/Notice of Outgoing/Retiring Partners
                                                            </label>
                                                        <?php } elseif($list->docu == 'PAN Card of Incoming_Outgoing Partners') {?>
                                                            <label for="email">PAN Card of Incoming/ Outgoing Partners
                                                            </label>
                                                        <?php } elseif($list->docu == 'Aadhaar Card of Incoming_Outgoing Partners'){?>
                                                            <label for="email">Aadhaar Card of Incoming/ Outgoing Partners
                                                            </label>
                                                        <?php } else {?>
                                                        <label><?php echo $list->docu; ?></label><br>
                                                        <?php }?>
                                                        <?php $get_all_docu =  $this->Applicants_login_model->get_all_docu($list->did,$par_id); 
                                                        //print_r($get_all_docu);
                                                        foreach($get_all_docu as $get_all) { ?>
                                                        <div class="row">
                                                            <?php if($get_all->gz_docu_id == '7') { ?>
                                                                <div class="col-md-4">
                                                                    <a href="<?php if(!empty($get_all->docu)) { echo $get_all->docu; } ?>" target="_blank"  class="file_icons"><i class="fa fa-file-pdf-o"></i></a>
                                                                </div>
                                                                <div class="col-md-4"><?php echo $get_all->created_at; ?></div>
                                                                <?php } else { ?>
                                                                <div class="col-md-4">
                                                                <?php $ext = pathinfo($get_all->docu, PATHINFO_EXTENSION);
                                                                    if($ext == "pdf"){ ?>
                                                                        <a href="<?php if(!empty($get_all->docu)) { echo $get_all->docu; } ?>" target="_blank"  class="file_icons"><i class="fa fa-file-pdf-o"></i></a>
                                                                    <?php } else {?>
                                                                        <a href="<?php if(!empty($get_all->docu)) {  echo  $get_all->docu;  } ?>" data-lightbox="$get_all->docu"  class="file_icons"><i class="fa fa-image"></i></a> 
                                                                    <?php }?>
                                                                </div>
                                                                <div class="col-md-4"><?php echo $get_all->created_at; ?></div><br>
                                                                <?php } ?>
                                                            </div>
                                                            <?php } ?>     
                                                        </div>
                                                        <?php } ?>
                                                        <?php } ?>
                                                        </div>
                                                    </div>
                                                </div>
                                            </div>
                                        </div>
                                    </div>
                            <?php if ($this->session->userdata('is_applicant')) {  
               
                if($gz_dets->cur_status == 6) { ?>
                            
            <div class="boxs-footer text-right bg-tr-black lter dvd dvd-top">  
                <div class="form-group col-md-12">
                    <label for="username">No of Page : </label>
                    <?php
                                   
                    $pdftext = file_get_contents($details->pdf_for_notice_of_softcopy);
                    $num_pag = preg_match_all("/\/Page\W/", $pdftext, $dummy);

                    echo $num_pag;
                    ?>
                    </div>
                    <div class="form-group col-md-12">
                            <label for="username">Per Page Amount: </label>
                            <?php echo $amt_per_page->pricing; ?>
                        </div>
                    <div class="form-group col-md-12">
                        <label for="username">Total :  </label>
                        <?php echo $num_pag*$amt_per_page->pricing; ?>
                    </div>
                
            <?php
            $price =  $num_pag*$amt_per_page->pricing;
           ?> 
                            
           <?php echo form_open("https://uat.(StateName)treasury.gov.in/echallan/dept-intg", array("method" => "POST", "id" => "form2", "name" => "form2")); ?>
				<?php 
					// Dept. Code
					$dept_code = "EGZ";
					// HoA Details
					$HoA = "0058-00-200-0127-02082-000";
					// Description
					$descp = "Change of Partnership Gazette Publication";
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
					$addl_info = $par_id . "!~!" . $gz_dets->file_no;
					// Return URL
					$return_url =  base_url() . "applicants_login/change_partnership_details";
					// Message 
                    $dep_ref_id = uniqid(time());
                    
					$msg_format = $dept_code . "|" . $dep_ref_id . "|" . $HoA . "|" . $descp . "|". $amnt . "||||||||||||||||" . $tot_amnt . "|" . $depositor_name . "|" . $depositor_addr . "||" . $state . "|" . $district . "|" . $pincode ."|" . $mobile . "|" . $email . "|" . $addl_info . "||||||" . $return_url;
					
					// Function to be used for Encrypt the Message string
					function encrypt($data = '', $key = NULL) {
						if($key != NULL && $data != ""){
							$method = "AES-256-ECB";
							$encrypted = openssl_encrypt($data, $method, $key, OPENSSL_RAW_DATA);
							$result = base64_encode($encrypted);
							return $result;
						}else{
							return "String to encrypt, Key is required.";
						}
					}
					
					// Binary file path
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
				<input type="hidden" name="msg" value="<?php echo $encrypted_msg; ?>"/>
                <!-- <input type="hidden" name="dep_ref_id" value="<?php //echo $dep_ref_id; ?>">
                <input type="hidden" name="amount" value="<?php //echo $amnt; ?>"/> -->
                <input type="hidden" name="description" value="<?php echo $descp; ?>"/>
				<input type="submit" value="Proceed to Pay Online" class="btn btn-raised btn-success edit_btn pay_btn_style" id="make_payment_btn"/>
            <?php echo form_close(); ?>
            <div class="clearfix"></div>
            <?php echo form_open("make_payment/make_payment_patnership_offline", array("method" => "POST", "id" => "form_pay_offline", "name" => "form_pay_offline")); ?>
                <input type="hidden" name="record_id" value="<?php echo $par_id; ?>"/>
                <input type="hidden" name="tot_amnt" value="<?php echo $price; ?>"/>
                <input type="hidden" name="depositor_name" value="<?php echo $gz_dets->name; ?>"/>
                <input type="hidden" name="mobile" value="<?php echo $gz_dets->mobile; ?>"/>
                <input type="hidden" name="email" value="<?php echo $gz_dets->email; ?>"/>
                <input type="hidden" name="file_no" value="<?php echo $gz_dets->file_no; ?>"/>
                <input type="hidden" name="deptCode" value="<?php echo $dept_code; ?>"/>
                <input type="hidden" name="msg" value="<?php echo $encrypted_msg; ?>"/>
                <button type="submit" name="btn_pay_offline" id="btn_pay_offline" class="btn btn-raised btn-success edit_btn offline_btn_color pay_btn_style">Proceed To Pay Offline</button>
            <?php echo form_close(); ?>
            
                    </div>
                             <?php }  } ?>
                </div>         
               
                <div class="row">    </div> 
            
                </section>
            </div>
        </div>
        
        <input type="hidden" name="par_id" id="par_id" value="<?php echo $par_id; ?>">
        <?php if($this->session->userdata('is_applicant') && ($gz_dets->cur_status == 13 || $gz_dets->cur_status == 19 || $gz_dets->cur_status == 22 || $gz_dets->cur_status == 25 || $gz_dets->cur_status == 30)) { ?>
            <div class="row">
                <div class="col-md-12">
                    <section class="boxs">
                        <div class="boxs-header">
                            <div class="col-md-8">
                                <h3 class="custom-font hb-blush">Resubmission</h3>
                            </div>
                        </div>
                        <?php echo form_open('', array('name' => 'form_pa',  'id' => 'form_pa', 'method' => 'post', 'enctype' => 'multipart/form-data')); ?>
                            <div class="boxs-body">                       
                                
                                <input type="hidden" name="file_no_par" id="file_no_par" value="<?php echo $gz_dets->file_no; ?>">
                                <input type="hidden" name="par_id" id="par_id" value="<?php echo $par_id; ?>">
                                <div class="row">
                                    <div class="form-group col-md-4">
                                        <label for="state_id">State <span class="asterisk">*</span></label>
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
                                        <label for="district_id">District <span class="asterisk">*</span></label>
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
                                        <label for="police_station_id">Police Station <span class="asterisk">*</span></label>
                                        <select name="police_station_id" id="police_station_id" class="form-control" required="">
                                        <?php foreach ($police_stations as $p_name) { ?>
                                                <option value="<?php echo $p_name->id; ?>" <?php echo ($p_name->id == $gz_dets->police_station_id) ? "selected" : ""; ?>><?php echo $p_name->police_station_name; ?></option>
                                            <?php } ?>

                                        </select>
                                        <?php if (form_error('police_station_id')) { ?>
                                            <span class="error"><?php echo form_error('police_station_id'); ?></span>
                                        <?php } ?>
                                    </div>
                                </div>
                                <div class="row">
                                    <div class="form-group col-md-4">
                                        <label for="address_1">Address 1 <span class="asterisk">*</span></label>
                                        <input type="text" id="address_1" name="address_1" placeholder="Enter Address 1" class="form-control" maxlength="50" autocomplete="off" value="<?php echo $gz_dets->address_1 ?>"/>
                                        <?php if (form_error('address_1')) { ?>
                                            <span class="error"><?php echo form_error('address_1'); ?></span>
                                        <?php } ?>
                                    </div>
                                    <div class="form-group col-md-4">
                                        <label for="address_2">Address 2 <span class="asterisk">*</span></label>
                                        <input type="text" id="address_2" name="address_2" placeholder="Enter Address 2" class="form-control" maxlength="50" autocomplete="off" value="<?php echo $gz_dets->address_2 ?>"/>
                                        <?php if (form_error('address_2')) { ?>
                                            <span class="error"><?php echo form_error('address_2'); ?></span>
                                        <?php } ?>
                                    </div>
                                    <input type="hidden" id="count" name ="count" value="<?php echo $count; ?>" />
                                    <div class="form-group col-md-4">
                                        <label for="email">Original Partnership Deed <span class="asterisk">*</span>
                                            <span class="file_icons_add">
                                                <i class="fa fa-file-image-o"></i>
                                            </span>
                                        </label>
                                        <input type="file" class="filestyle" data-buttonText="File" data-iconName="fa fa-upload" name="upload_1" id="upload_1" accept="application/pdf" class="upload" data-key="1">
                                        <input type="hidden" id="img_id_1" name="img_id_1[]" value="<?php echo $images[0]->orignal_partnership_deed; ?>"/>
                                        <span class="old-file">
                                            
                                            
                                            <?php $ext = pathinfo($images[0]->orignal_partnership_deed, PATHINFO_EXTENSION);
                                            
                                            if($ext == "pdf"){ ?>
                                                <a href="<?php if(!empty($images[0]->orignal_partnership_deed)) { echo $images[0]->orignal_partnership_deed; } ?>" target="_blank"  class=""><i class="fa fa-file-pdf-o"></i></a>
                                            <?php } else {?>
                                                <a href="<?php echo $images[0]->orignal_partnership_deed; ?>" data-lightbox="<?php echo $images[0]->orignal_partnership_deed; ?>"><i class="fa fa-file-image-o"></i></a>
                                            <?php }?>
                                        <span class="error_message_image_1 error"></span>
                                    </div>
                                    <div class="form-group col-md-4">
                                        <label for="email">Deed of Reconstitution of Partnership <span class="asterisk">*</span>
                                            <span class="file_icons_add">
                                                <i class="fa fa-file-image-o"></i>
                                            </span>
                                        </label>
                                        <input type="file" class="filestyle" data-buttonText="File" data-iconName="fa fa-upload" name="upload_2" id="upload_2" accept="application/pdf" class="upload" data-key="2">
                                        <input type="hidden" id="img_id_2" name="img_id_2[]" value="<?php echo $images[0]->deed_of_reconstitution_of_partnership; ?>"/>
                                                <!-- <span class="files_2"></span><br> -->
                                        <span class="old-file">
                                            <?php $ext = pathinfo($images[0]->deed_of_reconstitution_of_partnership, PATHINFO_EXTENSION);
                                            if($ext == "pdf"){ ?>
                                                <a href="<?php if(!empty($images[0]->deed_of_reconstitution_of_partnership)) { echo $images[0]->deed_of_reconstitution_of_partnership; } ?>" target="_blank"  class=""><i class="fa fa-file-pdf-o"></i></a>
                                            <?php } else {?>
                                                <a href="<?php echo $images[0]->deed_of_reconstitution_of_partnership; ?>" data-lightbox="$images[0]->deed_of_reconstitution_of_partnership"><i class="fa fa-file-image-o"></i></a>
                                            <?php }?>
                                        </span>
                                        <span class="error_message_image_2 error"></span>
                                    </div>
                                    <div class="form-group col-md-4">
                                        <label for="email">IGR Certificate <span class="asterisk">*</span>
                                            <span class="file_icons_add">
                                                <i class="fa fa-file-image-o"></i>
                                            </span>
                                        </label>
                                        <input type="file" class="filestyle" data-buttonText="File" data-iconName="fa fa-upload" name="upload_3" id="upload_3" accept="image/*" class="upload" data-key="3">      
                                        <input type="hidden" id="img_id_3" name="img_id_3[]" value="<?php echo $images[0]->igr_certificate_file; ?>"/>
                                        <!-- <span class="files_3"></span><br> -->
                                        <span class="old-file">
                                        <?php $ext = pathinfo($images[0]->igr_certificate_file, PATHINFO_EXTENSION);
                                            if($ext == "pdf"){ ?>
                                                <a href="<?php if(!empty($images[0]->igr_certificate_file)) { echo $images[0]->igr_certificate_file; } ?>" target="_blank"  class=""><i class="fa fa-file-pdf-o"></i></a>
                                            <?php } else {?>
                                                <a href="<?php echo $images[0]->igr_certificate_file; ?>" data-lightbox="$images[0]->igr_certificate_file"><i class="fa fa-file-image-o"></i></a>
                                            <?php }?>
                                        </span>
                                        <span class="error_message_image_3 error"></span>
                                    </div>
                                    <div class="form-group col-md-4">
                                        <label for="email">Original Newspaper Advertisement <span class="asterisk">*</span>
                                            <span class="file_icons_add">
                                                <i class="fa fa-file-image-o"></i>
                                            </span>
                                        </label>
                                        <input type="file" class="filestyle" data-buttonText="File" data-iconName="fa fa-upload" name="upload_6" id="upload_6" accept="image/*" class="upload" data-key="6">             
                                        <input type="hidden" id="img_id_6" name="img_id_6[]" value="<?php echo $images[0]->orignal_newspaper_of_advertisement; ?>"/>
                                        <!-- <span class="files_6"></span><br> -->
                                        <span class="old-file">
                                        <?php $ext = pathinfo($images[0]->orignal_newspaper_of_advertisement, PATHINFO_EXTENSION);
                                            if($ext == "pdf"){ ?>
                                                <a href="<?php if(!empty($images[0]->orignal_newspaper_of_advertisement)) { echo $images[0]->orignal_newspaper_of_advertisement; } ?>" target="_blank"  class=""><i class="fa fa-file-pdf-o"></i></a>
                                            <?php } else {?>
                                                <a href="<?php echo $images[0]->orignal_newspaper_of_advertisement; ?>" data-lightbox="$images[0]->orignal_newspaper_of_advertisement"><i class="fa fa-file-image-o"></i></a>
                                            <?php }?>
                                            </span>
                                        <span class="error_message_image_6 error"></span>
                                    </div>
                                    <div class="form-group col-md-4">
                                        <label for="email">Notice in Softcopy <span class="asterisk">*</span>
                                            <span class="file_icons_add">
                                                <i class="fa fa-file-word-o"></i>
                                            </span>
                                        </label>
                                        <input type="file" class="filestyle" data-buttonText="File" data-iconName="fa fa-upload" name="upload_7" id="upload_7" accept="docx/*" class="upload" data-key="7">
                                        <input type="hidden" id="img_id_7" name="img_id_7[]" value="<?php echo $images[0]->pdf_for_notice_of_softcopy; ?>"/>
                                        <input type="hidden" id="img_id_doc" name="img_id_7_doc" value="<?php echo $images[0]->notice_of_softcopy; ?>"/>
                                        <!-- <span class="files_7"></span><br> -->
                                        <span class="old-file">
                                        <a href="<?php echo $images[0]->pdf_for_notice_of_softcopy; ?>" target="blank"><i class="fa fa-file-pdf-o"></i></a>
                                        </span>
                                        <span class="error_message_image_7 error"></span>
                                    </div>
                                <!-- </div> -->
                                <!-- <div class="row" id="app_pan"> -->
                                    <?php $pan = $this->Applicants_login_model->select_cur_path_pan($par_id); ?>
                                    <input type="hidden" id="pan_text" name ="pan_text" value="<?php echo count($pan) ?>" />
                                    <?php foreach ($pan as $key => $value) { ?>
                                        <div class="form-group col-md-4">
                                            <label for="email">PAN Card of Partners <span class="asterisk">*</span>
                                                <span class="file_icons_add">
                                                    <i class="fa fa-file-image-o"></i>
                                                </span>
                                            </label>
                                            <input type="file" class="filestyle" data-buttonText="File" data-iconName="fa fa-upload" name="upload_4" id="upload_4_<?php echo $key; ?>" accept="image/*" class="upload" data-key="4_<?php echo $key; ?>">

                                            <input type="hidden" id="img_id_4_<?php echo $key; ?>" name="img_id_4[]" value="<?php echo $value->pan_card_of_partnetship; ?>"/>
                                             <!-- <span class="files_4_<?php //echo $key; ?>"></span><br> -->
                                            <span class="old-file">
                                                <?php $ext = pathinfo($value->pan_card_of_partnetship, PATHINFO_EXTENSION);
                                                if($ext == "pdf"){ ?>
                                                    <a href="<?php if(!empty($value->pan_card_of_partnetship)) { echo $value->pan_card_of_partnetship; } ?>" target="_blank"  class=""><i class="fa fa-file-pdf-o"></i></a>
                                                <?php } else {?>
                                                    <a href="<?php echo $value->pan_card_of_partnetship; ?>" data-lightbox="$value->pan_card_of_partnetship"><i class="fa fa-file-image-o"></i></a>
                                                <?php } ?>
                                            </span>
                                                <!-- </div> -->
                                                <!-- <?php// if ($key == 0) { ?>
                                                    <a id="4" class="btn_add_pan "><i class="fa fa-plus" ></i></a>
                                                <?php //} ?> -->
                                            <!-- </div> -->
                                            <!-- <span class="help-block mb-0">Maximum 1 MB allowed.</span> -->
                                            <span class="error_message_image_4_<?php echo $key; ?> error"></span>
                                        </div>
                                    <?php } ?>
                                        
                                <!-- </div> -->
                                <!-- <div class="row" id="app_aadhar"> -->
                                    <?php $aadhaar = $this->Applicants_login_model->select_cur_path_aadhar($par_id); ?>
                                    <input type="hidden" id="aadhar_text" name ="aadhar_text" value="<?php echo count($aadhaar) ?>" />
                                    <?php foreach ($aadhaar as $key => $value) { ?>
                                        <div class="form-group col-md-4">
                                            <label for="email">Aadhaar Card of Partners <span class="asterisk">*</span>
                                                <span class="file_icons_add">
                                                    <i class="fa fa-file-image-o"></i>
                                                </span>
                                            </label>
                                            <input type="file" class="filestyle" data-buttonText="File" data-iconName="fa fa-upload" name="upload_5" id="upload_5_<?php echo $key; ?>" accept="image/*" class="upload" data-key="5_<?php echo $key; ?>">

                                            <input type="hidden" id="img_id_5_<?php echo $key; ?>" name="img_id_5[]" value="<?php echo $value->aadhar_card_of_partnetship; ?>"/>
                                                    </span>
                                                    <!-- <span class="files_5_<?php// echo $key; ?>"></span><br> -->
                                                    <span class="old-file">
                                                        <?php $ext = pathinfo($value->aadhar_card_of_partnetship, PATHINFO_EXTENSION);
                                                            if($ext == "pdf"){ ?>
                                                                <a href="<?php if(!empty($value->aadhar_card_of_partnetship)) { echo $value->aadhar_card_of_partnetship; } ?>" target="_blank"  class=""><i class="fa fa-file-pdf-o"></i></a>
                                                            <?php } else {?>
                                                                <a href="<?php echo $value->aadhar_card_of_partnetship; ?>" data-lightbox="$value->aadhar_card_of_partnetship"><i class="fa fa-file-image-o"></i></a>
                                                        <?php }?>
                                                    </span>
                                                <!-- </div> -->
                                                <!-- <?php //if ($key == 0) { ?>
                                                    <a id="5" class="btn_add_aadhar "><i class="fa fa-plus" ></i></a>
                                                <?php //} ?> -->
                                            <!-- </div> -->
                                            <!-- <span class="help-block mb-0">Maximum 1 MB allowed.</span> -->
                                            <span class="error_message_image_5_<?php echo $key; ?> error"></span>
                                        </div>
                                    <?php } ?>
                                    <div class="form-group col-md-4">
                                        <label for="email">NOC/Notice of Outgoing/Retiring Partners<span class="asterisk">*</span>
                                            <span class="file_icons_add">
                                                <i class="fa fa-file-image-o"></i>
                                            </span>
                                        </label>
                                        <input type="file" class="filestyle" data-buttonText="File" data-iconName="fa fa-upload" name="upload_8" id="upload_8" accept="image/*" class="upload" data-key="8">
                                        <input type="hidden" id="img_id_8" name="img_id_8[]" value="<?php echo $images[0]->noc_notice_of_outgoing; ?>"/>
                                        <!-- <span class="files_8"></span><br> -->
                                        <span class="old-file">
                                        <?php $ext = pathinfo($images[0]->noc_notice_of_outgoing, PATHINFO_EXTENSION);
                                        if($ext == "pdf"){ ?>
                                            <a href="<?php if(!empty($images[0]->noc_notice_of_outgoing)) { echo $images[0]->noc_notice_of_outgoing; } ?>" target="_blank"  class=""><i class="fa fa-file-pdf-o"></i></a>
                                        <?php } else {?>
                                            <a href="<?php echo $images[0]->noc_notice_of_outgoing; ?>" data-lightbox="$images[0]->noc_notice_of_outgoing"><i class="fa fa-file-image-o"></i></a>
                                        <?php }?>
                                        <span class="error_message_image_8 error"></span>
                                    </div>
                                    <div class="form-group col-md-4">
                                        <label for="email">Challan<span class="asterisk">*</span>
                                            <span class="file_icons_add">
                                                <i class="fa fa-file-image-o"></i>
                                            </span>
                                        </label>
                                        <input type="file" class="filestyle" data-buttonText="File" data-iconName="fa fa-upload" name="upload_9" id="upload_9" accept="image/*" class="upload" data-key="9">
                                        <input type="hidden" id="img_id_9" name="img_id_9[]" value="<?php echo $images[0]->challan; ?>"/>
                                        <!-- <span class="files_9"></span><br> -->
                                        <span class="old-file">
                                        <?php $ext = pathinfo($images[0]->challan, PATHINFO_EXTENSION);
                                        if($ext == "pdf"){ ?>
                                            <a href="<?php if(!empty($images[0]->challan)) { echo $images[0]->challan; } ?>" target="_blank"  class=""><i class="fa fa-file-pdf-o"></i></a>
                                        <?php } else {?>
                                            <a href="<?php echo $images[0]->challan; ?>" data-lightbox="$images[0]->challan"><i class="fa fa-file-image-o"></i></a>
                                        <?php }?>
                                        <span class="error_message_image_9 error"></span>
                                    </div>
                            </div>
                            <div class="boxs-footer text-right bg-tr-black lter dvd dvd-top">
                                <button type="submit" class="btn btn-raised custom-btn btn-success" id="resubmit">RESUBMIT</button>
                            </div>
                        <?php  echo form_close(); ?>
                    </section>
                </div>
            </div>
        <?php } ?>
    </div>

    <!-- C&T reject Modal -->
    <div class="modal fade" id="myModal3" tabindex="-1" role="dialog" aria-labelledby="myModalLabel" aria-hidden="true" data-backdrop="false">

        <?php echo form_open('', array('class' => "form_status_change", 'id' => "form_status_change", 'name' => "form_status_change", 'method' => "post")); ?>    
            <input type="hidden" name="par_sta_id" id="par_sta_id" value=""/>
            <div class="modal-dialog modal-md">
                <div class="modal-content">
                    <div class="modal-header">
                        <button type="button" class="close" data-dismiss="modal">&times;</button>
                        <h4 class="modal-title">Reject Partnership Change</h4>
                    </div>
                    <div class="modal-body">
                        <div class="form-group">
                            <label>Remark<span class="asterisk">*</span></label>
                            <textarea name="reject_remarks" placeholder="Enter Remark" class="form-control remark_textarea" rows="6"  id="reject_remarks" ></textarea>
                        </div>
                    </div>
                    <div class="modal-footer">
                        <button type="submit" class="btn btn-raised btn-success">Submit</button>
                    </div>
                </div>
            </div>
        <?php echo form_close(); ?>
    </div>
    <!-- c&t approver reject modal -->
    <div class="modal fade" id="ct_approver_reject" tabindex="-1" role="dialog" aria-labelledby="myModalLabel" aria-hidden="true" data-backdrop="false">

        <?php echo form_open('', array('class' => "ct_reject", 'id' => "ct_reject", 'name' => "ct_reject", 'method' => "post")); ?>    
        <div class="modal-dialog modal-md">
            <div class="modal-content">
                <div class="modal-header">
                        <input type="hidden" name="par_sta_id" id="par_sta_id" value="<?php echo $par_id; ?>"/>
                        <button type="button" class="close" data-dismiss="modal">&times;</button>
                        <h4 class="modal-title">Reject Partnership Change</h4>
                    </div>
                    <div class="modal-body">
                        <div class="form-group">
                            <label>Remark<span class="asterisk">*</span></label>
                            <textarea name="ct_reject_remarks" placeholder="Enter Remark" class="form-control remark_textarea" rows="6"  id="reject_remarks" ></textarea>
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
    
                  <!-- C&T verifier Modal -->
    <div class="modal fade" id="ct_for_model" tabindex="-1" role="dialog" aria-labelledby="myModalLabel" aria-hidden="true" data-backdrop="false">
        <?php echo form_open('', array('class' => "ct_for_form", 'id' => "ct_for_form", 'name' => "ct_for_form", 'method' => "post")); ?>    
            <input type="hidden" name="ct_app_id" id="ct_app_id" value=""/>
            <div class="modal-dialog modal-md">
                <div class="modal-content">
                    <div class="modal-header">
                        <button type="button" class="close" data-dismiss="modal">&times;</button>
                        <h4 class="modal-title">Forward Partnership Change</h4>
                    </div>
                    <div class="modal-body">
                        <div class="form-group">
                            <label>Remark<span class="asterisk">*</span></label>
                            <textarea name="forward_remarks" placeholder="Enter Remark" class="form-control remark_textarea" rows="6"  id="forward_remarks" ></textarea>
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
    
    
    <!-- C&T approve return request approve Modal -->
    <div class="modal fade" id="ct_app_ret_model" tabindex="-1" role="dialog" aria-labelledby="myModalLabel" aria-hidden="true" data-backdrop="false">

        <?php echo form_open('', array('class' => "ct_app_ret_form", 'id' => "ct_app_ret_form", 'name' => "ct_app_ret_form", 'method' => "post")); ?>    
            <input type="hidden" name="ct_app_ret_id" id="ct_app_ret_id" value=""/>
            <div class="modal-dialog modal-md">
                <div class="modal-content">
                    <div class="modal-header">
                        <button type="button" class="close" data-dismiss="modal">&times;</button>
                        <h4 class="modal-title">Approve Partnership Change</h4>
                    </div>
                    <div class="modal-body">
                        <div class="form-group">
                            <label>Remark<span class="asterisk">*</span></label>
                            <textarea name="rej_app_remark" placeholder="Enter Remark" class="form-control remark_textarea" rows="6"  id="rej_app_remark" ></textarea>
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
    
    
    <!--  IGR reject Modal -->
    <div class="modal fade" id="myModal_igr" tabindex="-1" role="dialog" aria-labelledby="myModalLabel" aria-hidden="true" data-backdrop="false">

        <?php echo form_open('', array('class' => "igr_reject", 'id' => "igr_reject", 'name' => "igr_reject", 'method' => "post")); ?>    
            <input type="hidden" name="igr_par_id" id="igr_par_id" value=""/>
            <div class="modal-dialog modal-md">
                <div class="modal-content">
                    <div class="modal-header">
                        <button type="button" class="close" data-dismiss="modal">&times;</button>
                        <h4 class="modal-title">Reject Partnership Change </h4>
                    </div>
                    <div class="modal-body">
                        <div class="form-group">
                            <label>Remark<span class="asterisk">*</span></label>
                            <textarea name="reject_remarks" placeholder="Enter Remark" class="form-control remark_textarea" rows="6"  id="reject_remarks" ></textarea>
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

    <!--Return To Applicant -->

    <div class="modal fade" id="ret_applicant" tabindex="-1" role="dialog" aria-labelledby="myModalLabel" aria-hidden="true" data-backdrop="false">

        <?php echo form_open('', array('class' => "ret_applicant_form", 'id' => "ret_applicant_form", 'name' => "ret_applicant_form", 'method' => "post")); ?>    
            <input type="hidden" name="ret_appli" id="ret_appli" value=""/>
            <div class="modal-dialog modal-md">
                <div class="modal-content">
                    <div class="modal-header">
                        <button type="button" class="close" data-dismiss="modal">&times;</button>
                        <h4 class="modal-title">Return To Appliacnt </h4>
                    </div>
                    <div class="modal-body">
                        <div class="form-group">
                            <label>Remark<span class="asterisk">*</span></label>
                            <textarea name="ret_applicant_rem" placeholder="Enter Remark" class="form-control remark_textarea" rows="6"  id="ret_applicant_rem" ></textarea>
                        </div>
                    </div>
                    <div class="modal-footer">
                        <button type="submit" class="btn btn-raised btn-success">Submit</button>
                    </div>
                </div>
            </div>
        <?php echo form_close(); ?>
    </div>
    
    <!--  igr  approver  Modal -->
    <div class="modal fade" id="rej_app_model" tabindex="-1" role="dialog" aria-labelledby="myModalLabel" aria-hidden="true" data-backdrop="false">

        <?php echo form_open('', array('class' => "rej_app_form", 'id' => "rej_app_form", 'name' => "rej_app_form", 'method' => "post")); ?>    
            <input type="hidden" name="rej_app_id" id="rej_app_id" value=""/>
            <div class="modal-dialog modal-md">
                <div class="modal-content">
                    <div class="modal-header">
                        <button type="button" class="close" data-dismiss="modal">&times;</button>
                        <h4 class="modal-title">Approve Partnership Change </h4>
                    </div>
                    <div class="modal-body">
                        <div class="form-group">
                            <label>Remark<span class="asterisk">*</span></label>
                            <textarea name="ret_app_rem" placeholder="Enter Remark" class="form-control remark_textarea" rows="6"  id="ret_app_rem" ></textarea>
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
    
    
    <!--  return  to c&t user for forward to applicant Modal -->
    <div class="modal fade" id="return_to_ct" tabindex="-1" role="dialog" aria-labelledby="myModalLabel" aria-hidden="true" data-backdrop="false">

        <?php echo form_open('', array('class' => "return_to_ct_form", 'id' => "return_to_ct_form", 'name' => "return_to_ct_form", 'method' => "post")); ?>    
            <input type="hidden" name="return_to_ct_id" id="return_to_ct_id" value=""/>
            <div class="modal-dialog modal-md">
                <div class="modal-content">
                    <div class="modal-header">
                        <button type="button" class="close" data-dismiss="modal">&times;</button>
                        <h4 class="modal-title">Reject Partnership Change </h4>
                    </div>
                    <div class="modal-body">
                        <div class="form-group">
                            <label>Remark<span class="asterisk">*</span></label>
                            <textarea name="return_ct" placeholder="Enter Remark" class="form-control remark_textarea" rows="6"  id="return_ct" ></textarea>
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
    
    
    <!--  forward to igr approver Modal -->
    <div class="modal fade" id="forward_igr_app" tabindex="-1" role="dialog" aria-labelledby="myModalLabel" aria-hidden="true" data-backdrop="false">

        <?php echo form_open('', array('class' => "forward_igr_app_form", 'id' => "forward_igr_app_form", 'name' => "forward_igr_app_form", 'method' => "post")); ?>    
            <input type="hidden" name="forward_igr_app_id" id="forward_igr_app_id" value=""/>
            <div class="modal-dialog modal-md">
                <div class="modal-content">
                    <div class="modal-header">
                        <button type="button" class="close" data-dismiss="modal">&times;</button>
                        <h4 class="modal-title">Forward Partnership Change </h4>
                    </div>
                    <div class="modal-body">
                        <div class="form-group">
                            <label>Remark<span class="asterisk">*</span></label>
                            <textarea name="forward_igr_app_rem" placeholder="Enter Remark" class="form-control remark_textarea" rows="6"  id="forward_igr_app_rem" ></textarea>
                        </div>
                    </div>
                    <div class="modal-footer">
                        <button type="submit" class="btn btn-raised btn-success">Submit</button>
                    </div>
                </div>
            </div>
        <?php echo form_close(); ?>
    </div>
    <!--  Forward to CT Verifier From IGR Approver-->
    <div class="modal fade" id="forward_ct_vrifier" tabindex="-1" role="dialog" aria-labelledby="myModalLabel"      aria-hidden="true" data-backdrop="false">

        <?php echo form_open('', array('class' => "forward_ct_vrifier_form", 'id' => "forward_ct_vrifier_form", 'name' => "forward_ct_vrifier_form", 'method' => "post")); ?>    
            <input type="hidden" name="forward_ct_vrifier_id" id="forward_ct_vrifier_id" value=""/>
            <div class="modal-dialog modal-md">
                <div class="modal-content">
                    <div class="modal-header">
                        <button type="button" class="close" data-dismiss="modal">&times;</button>
                        <h4 class="modal-title">Forward Partnership Change </h4>
                    </div>
                    <div class="modal-body">
                        <div class="form-group">
                            <label>Remark<span class="asterisk">*</span></label>
                            <textarea name="forward_ct_vrifier_rem" placeholder="Enter Remark" class="form-control remark_textarea" rows="6"  id="forward_ct_vrifier_rem" ></textarea>
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
        <!--  forward to publish from c&t user Modal -->
    <div class="modal fade" id="forward_to_pub_model" tabindex="-1" role="dialog" aria-labelledby="myModalLabel" aria-hidden="true" data-backdrop="false">

        <?php echo form_open('', array('class' => "forward_to_pub_form", 'id' => "forward_to_pub_form", 'name' => "forward_to_pub_form", 'method' => "post")); ?>    
            <input type="hidden" name="forward_to_pub_id" id="forward_to_pub_id" value=""/>
            <div class="modal-dialog modal-md">
                <div class="modal-content">
                    <div class="modal-header">
                        <button type="button" class="close" data-dismiss="modal">&times;</button>
                        <h4 class="modal-title">Forward Partnership Change </h4>
                    </div>
                    <div class="modal-body">
                        <div class="form-group">
                            <label>Remark<span class="asterisk">*</span></label>
                            <textarea name="forward_to_pub_rem" placeholder="Enter Remark" class="form-control remark_textarea" rows="6"  id="forward_to_pub_rem" ></textarea>
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
    <div class="modal fade" id="imageModal" tabindex="-1" role="dialog" aria-labelledby="myModalLabel" aria-hidden="true" data-backdrop="false">
    
        <div class="modal-dialog croppie-container cr-resizer-horisontal">
            <div class="modal-content modal-size">
                <div class="modal-header">
                    <h4 class="modal-title">Crop</h4>
                    <button type="button" class="close" data-dismiss="modal">&times;</button>
                </div>

                <div class="modal-body" >
                    <div class="row marginT8">
                        <div class="col-md-8 text-center">
                            <div class="image_crop" id="dp_preview"></div>
                        </div>
                    </div>
                </div>
                <input type="hidden" id="for_documents_img" value="0">
                <div class="modal-footer">
                    <button type="button" class="btn btn-success" id="crop_identity_img" data-dismiss="modal">Crop</button>
                </div>
            </div>
        </div>
    </div>
</section>

<script>
// Attach a click event handler to the "Proceed To Pay Online" button
$(document).ready(function() {
    $('#make_payment_btn').click(function (e) {
        // var deptCode = $("input[name='deptCode']").val();
        // var depRefId = $("input[name='dep_ref_id']").val();
        // var amount = $("input[name='amount']").val();
        var description = $("input[name='description']").val();
        var encryptedMsg = $("input[name='msg']").val();

        //e.preventDefault();
        // console.log(depRefId);
        $.ajax({
            url: "<?php echo base_url('DepRefController/storeDeptRefData'); ?>",
            type: "POST",
            data: {
                // deptCode: deptCode,
                // dep_ref_id: depRefId,
                // amount: amount,
                description: description,
                encryptedMsg: encryptedMsg
            },
            dataType: "json",
            success: function(response) {
                if (response.success) {
                    console.log("Data stored successfully.");
                    // Submit the form if data is stored successfully
                    $("#form2").submit();
                } else {
                    console.error("Failed to store data.");
                    // Handle error scenario
                }
            },
            error: function(xhr, status, error) {
                console.error("AJAX request failed:::", error);
                return false;
                // Handle error scenario
            }
        });
    });
});

</script>

<script type="text/javascript" nonce="a6b9a780936c8e980939086f618dded2">
    /*
    * Upload notification file
    */
    function upload_notification_file(file,id,sub_id) {
        var fd = new FormData();
        var token = $("input[name=<?php echo $this->security->get_csrf_token_name(); ?>]").val();
        var file_no_par = $("#file_no_par").val();

        fd.append('file', file);
        fd.append('id', id);
         fd.append('file_no_par', file_no_par);

        fd.append('<?php echo $this->security->get_csrf_token_name(); ?>', token);
        //$('.loader').show();
        // make ajax request
        $.ajax({
            type: "POST",
            url: "<?php echo base_url(); ?>applicants_login/upload_document",
            data: fd,
            contentType: false,
            processData: false,
            success: function (msg) {
                //alert(<?php //echo $tot_docu->id; ?>);
                var json = JSON.parse(msg);
                //console.log(json);
                if (json['success']) {
                    //alert(json['success']);
                        $(".notif_file").hide();
                        $(".notif_file").html();

                        if(id == 4 || id == 5) {
                            $('#img_id_'+id + "_" +sub_id).val(json['success']);
                        } else {
                          $('#img_id_'+id).val(json['success']);
                        }

                }
                if (json['error']) {
                        $('.loader').hide();
                        $(".notif_file").html(json['error']);
                        $('#img_id_'+id).val('');
                        $(".notif_file").show();
                }
            },
            error: function (msg) {
                    $('.loader').hide();
            }
        });
    }
$(document).ready(function () {
/*
 * View Image on Popbox of Lightbox
 */
 
        lightbox.option({
            'resizeDuration': 200,
            'wrapAround': true
        })

$('input[type=file]').change(function () {
        var input_file = $(this).prop('files')[0];
        var key = $(this).data('key');
        var ext = input_file['name'].substring(input_file['name'].lastIndexOf('.') + 1).toLowerCase();
        
        if (key == 7) {
            if (input_file && (ext == "docx")) {
                $('.files_' + key).html($(this).val().split('\\').pop());
                $('.error_message_image_' + key).hide();
            } else {
                $('.error_message_image_' + key).show();
                $('.error_message_image_' + key).html("Please upload only (.docx) file");
                this.value = '';
                $('.files_' + key).html('');
            }
        } else {
            if (input_file && (ext == "png" || ext == "jpeg" || ext == "jpg" || ext == "pdf")) {
                $('.files_' + key).html($(this).val().split('\\').pop());
                $('.error_message_image_' + key).hide();
                
                if (key.length > 1) {
                    var data = key.split("_");
                    var id = data[0];
                    var dyn_id = data[1];
                    upload_notification_file(input_file,id,dyn_id);
                } else {
                    var id = key;
                    upload_notification_file(input_file,id,'');
                }
                
            } else {
                $('.error_message_image_' + key).show();
                $('.error_message_image_' + key).html("Please upload only (.png, .jpeg, .jpg, .pdf) file");
                this.value = '';
                $('.files_' + key).html('');
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
                url: "<?php echo base_url(); ?>applicants_login/get_police_station_list",
                data: {
                    id: id,
                    "<?php echo $this->security->get_csrf_token_name(); ?>": "<?php echo $this->security->get_csrf_hash(); ?>"
                },
                success: function (data) {
                    $('#police_station_id').html(data);
                    $(".loader").hide();
                }
            });
        });
        
    /*
     * reject by C&T verifier user
     */
    $('#reject_button').on('click', function(){
        ////alert('ok');
        var id = $('#par_id').val();
        $('#par_sta_id').val(id);       
    });


    
       /*
     *forward by c&t user
     */
    $('#ver_btn').on('click', function(){
        //alert('ok');
        var id = $('#par_id').val();
        //alert(id);
        $('#ct_app_id').val(id);       
    });
    
    /*
    * c&t approver return request approve
    */
    $('#app_btn').on('click', function(){
        //alert('ok');
        var id = $('#par_id').val();
        //alert(id);
        $('#ct_app_ret_id').val(id);       
    });
    
//    /*
//    * reject by C&T verifier user
//    */
//    $('#app_btn').on('click', function(){
//        //alert('ok');
//        var id = $('#par_id').val();
//        //alert(id);
//        $('#ct_app_ret_id').val(id);       
//    });
            
         
    /*
    * return applicant
    */
    // $('#applicant').on('click', function(){
    //     ////alert('ok');
    //     var id = $('#par_id').val();
    //     //alert(id);
    //     $('#ret_appli').val(id);       
    // });
    
    /*
     * reject application approve by igr
     */
    $('#igr_app_btn').on('click', function(){
        ////alert('ok');
        var id = $('#par_id').val();
        //alert(id);
        $('#rej_app_id').val(id);       
    });
    
     /*
     *forward to publish
     */
    $('#forward_to_publish').on('click', function(){
        ////alert('ok');
        var id = $('#par_id').val();
        //alert(id);
        $('#forward_to_pub_id').val(id);       
    });
            
            
    /*
    * script for image cropping
    */
//    aadhar_card_load('5');
//    
//    pan_card_load('4'); 
    /*
     * for pan card add
     */
    $('.btn_add_pan').click(function(){
         var id = $(this).attr('id');
        pan_card_load(id);
     });
     
         
   /*
    * load aadhar card second div
    */
   $('.btn_add_aadhar').click(function(){
        var id = $(this).attr('id');
       aadhar_card_load(id);
   });
   
    /*
    * load aadhar card second div
    */
   $('#igr_reject_button').click(function(){
       var id = $('#par_id').val();
       //alert(id);
        $('#igr_par_id').val(id);
   });
   
     /*
    * igr return to c&t verifier
    */
   $('#igr_ct_ver_return').click(function(){
       var id = $('#par_id').val();
       //alert(id);
        $('#return_to_ct_id').val(id);
   });
   
      /*
    * forwarxd to igr approver from igr verifier
    */
   $('#igr_ver_btn').click(function(){
       var id = $('#par_id').val();
       //alert(id);
        $('#forward_igr_app_id').val(id);
   });
   
   /**
   Forward to IGR Approver from CT Verifier
    */
    $('#igr_approver_btn').click(function(){
       var id = $('#par_id').val();
        $('#forward_ct_vrifier_id').val(id);
   });
   
   
    /*
    *  for rejection  of applicant from c&t verifier
    */
    $('#form_status_change').validate({
        rules: {
            reject_remarks: {
                required: true
            }
        },
        messages: {
            reject_remarks: {
                required: "Please enter remark"
            }
        },
        submitHandler: function (form) {
               $.ajax({
                    type: "POST",
                    url: "<?php echo base_url(); ?>applicants_login/update_reject_sta_ct_ver",
                    data: $(form).serialize(),

                    //if success, returns success message
                    success: function (data) {
                        location.reload();
                    },
                    error: function (data) {
                        location.reload();
                    }
               }) 
            }
        }) 
        
        
        
    /*
    *  for rejection  of applicant by C&T Approver
    */
    $('#ct_reject').validate({
        rules: {
            reject_remarks: {
                required: true
            }
        },
        messages: {
            reject_remarks: {
                required: "Please enter remark"
            }
        },
        submitHandler: function (form) {
            $.ajax({
                type: "POST",
                url: "<?php echo base_url(); ?>applicants_login/reject_ct_app",
                data: $(form).serialize(),

                //if success, returns success message
                success: function (data) {
                    location.reload();
                },
                error: function (data) {
                    location.reload();
                }
            }) 
        }
    })


    /*
    *  for rejection  of applicant from igr verifier
    */
    $('#igr_reject').validate({
        rules: {
            reject_remarks: {
                required: true
            }
        },
        messages: {
            reject_remarks: {
                required: "Please enter remark"
            }
        },
        submitHandler: function (form) {
            $.ajax({
                type: "POST",
                url: "<?php echo base_url(); ?>applicants_login/update_reject_sta_igr_ver",
                data: $(form).serialize(),

                //if success, returns success message
                success: function (data) {
                    location.reload();
                },
                error: function (data) {
                    location.reload();
                }
            }) 
        }
    })
        
        /*
        * resubmit document
        */
     
        $('#form_pa').validate({
            // initialize the plugin
            rules: {            
                gazette_id: {
                    required: true
                },
                state_id: {
                    required: true
                },
                district_id: {
                    required: true
                },
                police_station_id: {
                    required: true
                },
                address_1: {
                    required: true,
                    minlength: 5,
                    maxlength: 50
                },
                address_2: {
                    required: true,
                    minlength: 5,
                    maxlength: 50
                },
                address_3: {
                    required: true,
                    minlength: 5,
                    maxlength: 50
                }
            },
            messages: {
                gazette_id: {
                    required: "Please enter gazette type"
                },
                state_id: {
                    required: "Please select state"
                },
                district_id: {
                    required: "Please select district"
                },
                police_station_id: {
                    required: "Please select police station"
                },
                address_1: {
                    required: "Please enter address 1",
                    minlength: "Please enter minimum 5 characters",
                    maxlength: "Please enter maximum 50 characters"
                },
                address_2: {
                    required: "Please enter address 2",
                    minlength: "Please enter minimum 5 characters",
                    maxlength: "Please enter maximum 50 characters"
                },
                address_3: {
                    required: "Please enter address 3",
                    minlength: "Please enter minimum 5 characters",
                    maxlength: "Please enter maximum 50 characters"
                }
            },
            
            submitHandler: function (form) {
                $(".loader").show();
                $('#error_msg').hide();
                 var fd = new FormData(form);
                 var file = $('#upload_7').prop('files')[0];
                 var token = $("input[name=<?php echo $this->security->get_csrf_token_name(); ?>]").val();    
                 fd.append('file', file);
                 //console.log(fd);
                 fd.append('<?php echo $this->security->get_csrf_token_name(); ?>', token);
                 //alert('ok');
                 $.ajax({
                     type: "POST",
                     url: "<?php echo base_url(); ?>applicants_login/update_partnership_details",
                     data: fd,
                     contentType: false,
                     processData: false,
                     success: function (msg) {
                     var json = JSON.parse(msg);
                     var count = $('#count').val();  
                         //console.log(json);
                         if (json['redirect']) {
                           window.location = json['redirect'];
                         } else  if (json['error']) {
                             if (json['error']['message']) {
                                 $('#form_pa').prepend('<div class="alert bg-danger"><button type="button" class="close" data-dismiss="alert"><span>Ã—</span><span class="sr-only">Close</span></button><span class="text-semibold"></span>' + json['error']['warning'] + '</div>');
                             }

                             for (i in json['error']) {
                                 var element = $('#' + i);
                                 $(element).parent().find('.error').remove();
                                 $(element).after('<span class="error">' + json['error'][i] + '</span>');
                             }
                         }  else {
                            $(element).parent().find('.error').remove();
                        }
                        $(".loader").hide();
                     }

                });
            }
        });
        
        /*
        * reject document form C&T verifier user
         */
        $('#ct_app_ret_form').validate({
        rules: {
            rej_app_remark: {
                required: true
            }
        },
        messages: {
            rej_app_remark: {
                required: "Please enter remark"
            }
        },
        submitHandler: function (form) {
            //alert('ok');
            var par_id = $('#par_id').val();
            var rej_app_remark = $('#rej_app_remark').val();
            
            $.ajax({
                type: "POST",
                url: "<?php echo base_url(); ?>applicants_login/update_reject_sta_ct_app",
                data: {
                    par_id :par_id,rej_app_remark:rej_app_remark,
                    '<?php echo $this->security->get_csrf_token_name(); ?>' : '<?php echo $this->security->get_csrf_hash(); ?>'
                },                  
                //if success, returns success message
                success: function (data) {
                    window.location = "<?php echo base_url();?>applicants_login/partnership_details_list";
                   //location.reload();
                },
                error: function (data) {
                    window.location = "<?php echo base_url();?>applicants_login/partnership_details_list";
                   //location.reload();
                }
            }) 
            }
        }) 
        /*
        * return document to applicant
        */       
        $('#ret_applicant_form').validate({
        rules: {
            ret_applicant_rem: {
                required: true
            }
        },
        messages: {
            ret_applicant_rem: {
                required: "Please enter remark"
            }
        },
        submitHandler: function (form) {
          $('.loader').show();
            //alert('ok');
            var par_id = $('#par_id').val();
            var ret_applicant_rem = $('#ret_applicant_rem').val();
            $.ajax({
                type: "POST",
                url: "<?php echo base_url(); ?>applicants_login/update_var_applicant_return",
                data: {
                    par_id :par_id,ret_applicant_rem:ret_applicant_rem,
                    '<?php echo $this->security->get_csrf_token_name(); ?>' : '<?php echo $this->security->get_csrf_hash(); ?>'
                },                  
                //if success, returns success message
                success: function (data) {
                  // location.reload();
                  window.location = "<?php echo base_url();?>applicants_login/partnership_details_list";
                },
                error: function (data) {
                    window.location = "<?php echo base_url();?>applicants_login/partnership_details_list";
                    $('.loader').hide();
                }
            })  
            }
        }) 
        
        /*
        * return document to c7t approver
         */
       $('#ct_for_form').validate({
        rules: {
            forward_remarks: {
                required: true
            }
        },
        messages: {
            forward_remarks: {
                required: "Please enter remark"
            }
        },
        submitHandler: function (form) {
            $('.loader').show();
            //alert('ok');
            var par_id = $('#par_id').val();
            var forward_remarks = $('#forward_remarks').val();
            $.ajax({
                type: "POST",
                url: "<?php echo base_url(); ?>applicants_login/ver_docu_c_t_user",
                data: {
                    par_id :par_id,forward_remarks:forward_remarks,
                    '<?php echo $this->security->get_csrf_token_name(); ?>' : '<?php echo $this->security->get_csrf_hash(); ?>'
                },                  
                //if success, returns success message
                success: function (data) {
                   //location.reload();
                   window.location = "<?php echo base_url();?>applicants_login/partnership_details_list";
                },
                error: function (data) {
                   //location.reload();
                   window.location = "<?php echo base_url();?>applicants_login/partnership_details_list";
                   $('.loader').hide();
                }
            }) 
            
        } 
       });
        
        /*
        * reject document form C&T verifier user
         */
       
        $('#rej_app_form').validate({
        rules: {
            ret_app_rem: {
                required: true
            }
        },
        messages: {
            ret_app_rem: {
                required: "Please enter remark"
            }
        },
        submitHandler: function (form) {
 
            //alert('ok');
            var par_id = $('#par_id').val();
            var ret_app_rem = $('#ret_app_rem').val();
            $.ajax({
                type: "POST",
                url: "<?php echo base_url(); ?>applicants_login/update_app_sta_igr_app",
                data: {
                    par_id :par_id,ret_app_rem:ret_app_rem,
                    '<?php echo $this->security->get_csrf_token_name(); ?>' : '<?php echo $this->security->get_csrf_hash(); ?>'
                },                  
                //if success, returns success message
                success: function (data) {
                   //location.reload();
                   window.location = "<?php echo base_url();?>applicants_login/partnership_details_list";
                },
                error: function (data) {
                   //location.reload();
                   window.location = "<?php echo base_url();?>applicants_login/partnership_details_list";
                }
            })  }
        }) 
        
        /*
        * return document to C&T verifier from igr verifier
        */
        
        
        $('#return_to_ct_form').validate({
        rules: {
            return_ct: {
                required: true
            }
        },
        messages: {
            return_ct: {
                required: "Please enter remark"
            }
        },
        submitHandler: function (form) {
 
        
            var par_id = $('#par_id').val();
            var return_ct = $('#return_ct').val();
            $.ajax({
                type: "POST",
                url: "<?php echo base_url(); ?>applicants_login/return_igr_to_ct_ver",
                data: {
                    par_id :par_id,return_ct:return_ct,
                    '<?php echo $this->security->get_csrf_token_name(); ?>' : '<?php echo $this->security->get_csrf_hash(); ?>'
                },                  
                //if success, returns success message
                success: function (data) {
                   //location.reload();
                   window.location = "<?php echo base_url();?>applicants_login/partnership_details_list";
                },
                error: function (data) {
                   //location.reload();
                   window.location = "<?php echo base_url();?>applicants_login/partnership_details_list";
                }
            }) 
            }
        }) 
        
         /*
        * return document to applicant
         */
        $('#forward_igr_app_form').validate({
        rules: {
            forward_igr_app_rem: {
                required: true
            }
        },
        messages: {
            forward_igr_app_rem: {
                required: "Please enter remark"
            }
        },
        submitHandler: function (form) {
 
          $('.loader').show();
            //alert('ok');
            var par_id = $('#par_id').val();
            var forward_igr_app_rem = $('#forward_igr_app_rem').val();
            $.ajax({
                type: "POST",
                url: "<?php echo base_url(); ?>applicants_login/return_igr_ver",
                data: {
                    par_id :par_id,forward_igr_app_rem:forward_igr_app_rem,
                    '<?php echo $this->security->get_csrf_token_name(); ?>' : '<?php echo $this->security->get_csrf_hash(); ?>'
                },                  
                //if success, returns success message
                success: function (data) {
                   //location.reload();
                   //debugger;
                   //alert('ok');
                   window.location = "<?php echo base_url();?>applicants_login/partnership_details_list";
                },
                error: function (data) {
                    //debugger;
                   // alert('not ok');
                   //location.reload();
                   window.location = "<?php echo base_url();?>applicants_login/partnership_details_list";
                   $('.loader').hide();
                }
            }) 
            }
        });

        /*
        *Forward From Igr Approver to CT Verifier
        */
        $('#forward_ct_vrifier_form').validate({
        rules: {
            forward_ct_vrifier_rem: {
                required: true
            }
        },
        messages: {
            forward_ct_vrifier_rem: {
                required: "Please enter remark"
            }
        },
        submitHandler: function (form) {
            $('.loader').show();
        
            // alert('ok');
            var par_id = $('#par_id').val();
            var forward_ct_vrifier_rem = $('#forward_ct_vrifier_rem').val();
            $.ajax({
                type: "POST",
                url: "<?php echo base_url(); ?>applicants_login/forward_igr_approver",
                data: {
                    par_id :par_id,forward_ct_vrifier_rem:forward_ct_vrifier_rem,
                    '<?php echo $this->security->get_csrf_token_name(); ?>' : '<?php echo $this->security->get_csrf_hash(); ?>'
                },                  
                //if success, returns success message
                success: function (data) {
                   //location.reload();
                   window.location = "<?php echo base_url();?>applicants_login/partnership_details_list";
                },
                error: function (data) {
                   //location.reload();
                   window.location = "<?php echo base_url();?>applicants_login/partnership_details_list";
                   $('.loader').hide();
                }
            }) 
            }
        })
         /*
        * forward to publish
         */
        $('#forward_to_pub_form').validate({
        rules: {
            forward_to_pub_rem: {
                required: true
            }
        },
        messages: {
            forward_to_pub_rem: {
                required: "Please enter remark"
            }
        },
        submitHandler: function (form) {
            $('.loader').show();
        
            //alert('ok');
            var par_id = $('#par_id').val();
             var forward_to_pub_rem = $('#forward_to_pub_rem').val();
            
    
            $.ajax({
                type: "POST",
                url: "<?php echo base_url(); ?>applicants_login/forward_to_pub_ins",
                data: {
                    par_id :par_id,forward_to_pub_rem:forward_to_pub_rem,
                    '<?php echo $this->security->get_csrf_token_name(); ?>' : '<?php echo $this->security->get_csrf_hash(); ?>'
                },                  
                //if success, returns success message
                success: function (data) {
                   //location.reload();
                   window.location = "<?php echo base_url();?>applicants_login/partnership_details_list";
                },
                error: function (data) {
                   //location.reload();
                   window.location = "<?php echo base_url();?>applicants_login/partnership_details_list";

                   $('.loader').hide();
                }
            }) 
            }
        }) 
        
        
    }); 
    /*
    * to load pan card second div
     */
    function pan_card_load(id){

        var pan_text = $('#pan_text').val();
        //alert(pan_text);
        $.ajax({
            type: "POST",
            url: "<?php echo base_url(); ?>applicants_login/add_pan_edit",
            data: {
                    'id':id,'pan_text':pan_text,
                    '<?php echo $this->security->get_csrf_token_name(); ?>' : '<?php echo $this->security->get_csrf_hash(); ?>'
            },          
            success: function (html) {
               //console.log(html);
               $('#app_pan').append(html);
               var pan_text = $('#pan_text').val();
               var final_incre =  pan_text + 1;
               $('#pan_text').val(final_incre);

            },
            error: function (data) {
               location.reload();
            }

        });
    }

    /*
    * to load aadhar card second div
     */
    function aadhar_card_load(id){

        var aadhar_text = $('#aadhar_text').val();
        //alert(id);
        $.ajax({
            type: "POST",
            url: "<?php echo base_url(); ?>applicants_login/add_aadhar_edit",
            data: {
                    'id':id,'aadhar_text':aadhar_text,
                    '<?php echo $this->security->get_csrf_token_name(); ?>' : '<?php echo $this->security->get_csrf_hash(); ?>'
            },          
            success: function (html) {
               //alert(html);
               $('#app_aadhar').append(html);
               var aadhar_text = $('#aadhar_text').val();
               var final_incre =  aadhar_text + 1;
               $('#aadhar_text').val(final_incre);
            },
            error: function (data) {
               //location.reload();
            }

        });
    }
    
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