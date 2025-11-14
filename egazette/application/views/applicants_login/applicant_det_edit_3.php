<?php defined('BASEPATH') OR exit('No direct script access allowed'); ?>
<!-- CONTENT -->
<!-- <script src="<?php echo base_url(); ?>assets/js/vendor/html5lightbox.js" nonce="a6b9a780936c8e980939086f618dded2" type="text/javascript"></script> -->
<script src="https://code.jquery.com/jquery-3.4.1.min.js" type="text/javascript" nonce="a6b9a780936c8e980939086f618dded2"></script>
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
    #applicant{
        width: 145px;
        height: 36px;
        margin-left: 150px;
    }
    #ver_btn{
        width: 80px;
        height: 36px;
        margin-left: 39px;
    } 
    #btn_div{
        margin-top: -20px;
    }
    #reject_button{
        width: 80px;
        height: 36px;
        margin-left: 99px;
    }
    
     #igr_ver_btn{
        width: 80px;
        height: 36px;
        margin-left: 39px;
    } 
    #igr_reject_button{
        width: 80px;
        height: 36px;
        margin-left: 99px;
    }
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
    
     #forward_to_publish{
        width: 80px;
        height: 36px;
        margin-left: 200px;
    } 
    #make_payment_btn{
        width: 150px;
        height: 36px;
        margin-left: 200px;
    } 
    #resubmit {
        margin-top: 94px;
    }
    
    .loader{
        position: fixed;
        left: 0px;
        top: 0px;
        width: 100%;
        height: 100%;
        z-index: 9999;
        background: url('<?php echo base_url(); ?>assets/images/loader.gif') 50% 50% no-repeat rgb(249,249,249);
    }
    
</style>
<!-- /content area -->
<div class="modal col-md-12" id="imageCropModal">
    <div class="modal-dialog">
        <div class="modal-content">
            <!-- Modal Header -->
            <div class="modal-header">
                <input type="hidden" name="div_id" id="div_id">
                <h4 class="modal-title">Crop Image</h4>
                <button type="button" class="close" data-dismiss="modal">&times;</button>
            </div>
            <!-- Modal body -->
            <div class="modal-body" >
                <span class='red-text'> <?php echo form_error('path'); ?> </span>     
                <img src="" class="crop" id="dp_preview" width = "100" height="100" />
            </div>
            <!-- Modal footer -->
            <div class="modal-footer">
                <button type="button" class="btn btn-success" data-dismiss="modal" onclick="uploadFile();">Submit</button>
            </div>
        </div>
    </div>
</div>

<!--/ CONTENT -->
<div class="loader"></div>
<section id="content">
    <div class="page page-forms-validate">
        <!-- bradcome -->
        <div class="bg-light lter b-b wrapper-md mb-10">
            <div class="row">
                <div class="col-sm-6 col-xs-12">
                    <h1 class="h3 m-0">Change of Partnership </h1>
                </div>
            </div>
        </div>
        <!-- row -->
        <div class="row">
            <div class="col-md-12">
                
                <section class="boxs">
                    
                    <div class="boxs-header">
                        <div class="col-md-8">
                            <h3 class="custom-font hb-blush">View Change of Partnership Details</h3>
                        </div>
                        <div class="col-md-4" id="btn_div" >
                            
                    <?php
                    if ($this->session->userdata('is_c&t')) {
                               
                          if($this->session->userdata('is_verifier_approver') == 'Verifier') {  
                                
                            if($gz_dets->cur_status == 1 || $gz_dets->cur_status == 10) {  ?>
                            
                            <button class="btn btn-raised btn-danger" data-toggle="modal" data-target="#myModal3" id="reject_button" >Reject</button>
                            <button type="submit" class="btn btn-raised btn-success " id="ver_btn" data-toggle="modal" data-target="#ct_for_model" >Forward</button>
                            <?php } else if( $gz_dets->cur_status == 12 || $gz_dets->cur_status == 15 ){ ?>
                            
                                 <button type="submit" class="btn btn-raised btn-success" id="applicant"  data-target="#ret_applicant"  data-toggle="modal" >Return Applicant </button>
                            <?php } }
                            if($this->session->userdata('is_verifier_approver') == 'Approver') {
                            if($gz_dets->cur_status == 2 || $gz_dets->cur_status == 9 ) { ?>
                            
                            <button type="submit" class="btn btn-raised btn-success" id="app_btn" data-toggle="modal" data-target="#ct_app_ret_model" >Approve</button>
                            
                           <?php }
                           if($gz_dets->cur_status == 5 ) { ?>
                            
                            <button type="submit" class="btn btn-raised btn-success" id="forward_to_publish" data-toggle="modal" data-target="#forward_to_pub_model" >Forward</button>
                            
                           <?php }  } } ?>
                            
                        <?php  if ($this->session->userdata('is_igr')) {
                               
                          if($this->session->userdata('is_verifier_approver') == 'Verifier') {  
                                
                            if($gz_dets->cur_status == 3 ) {  ?>
                            
                            <button class="btn btn-raised btn-danger" data-toggle="modal" data-target="#myModal_igr" id="igr_reject_button" >Reject</button>
                            <button type="submit" class="btn btn-raised btn-success " id="igr_ver_btn"  data-toggle="modal" data-target="#forward_igr_app"   >forward</button>
                            <?php } else if( $gz_dets->cur_status == 14 ){ ?>
                            
                                 <button type="submit" class="btn btn-raised btn-success" id="igr_ct_ver_return"  data-toggle="modal" data-target="#return_to_ct" >Return C&T verifier </button>
                            <?php } }
                            if($this->session->userdata('is_verifier_approver') == 'Approver') {
                            if($gz_dets->cur_status == 11 || $gz_dets->cur_status == 4 ) { ?>
                            
                            <button type="submit" class="btn btn-raised btn-success" id="igr_app_btn"   data-target="#rej_app_model" data-toggle="modal"  >Approve</button>
                            
                           <?php } } } ?>
                            
                        </div>
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
                                    <label for="username">Status : </label>
                                    <?php echo $gz_dets->status_det; ?>
                                </div>
                                <?php 
                                 $select_det_remark =  $this->Applicants_login_model->get_cur_rem($gz_dets->cur_status);
                                if(!empty($select_det_remark->remark)) { ?>
                                <div class="form-group col-md-6">
                                    <label for="email">Remark : </label>
                                    <?php 
                                   
                                    echo $select_det_remark->remark; ?>
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
    <label> <?php echo trim(ucfirst($tot_docu->document_name)); ?></label><br>
    <?php if($tot_docu->document_name =='PAN Card of Partners') { ?>
                                      
        <div class="">     
        <?php if($i == 1 ){
            $image_pan = $this->Applicants_login_model->select_cur_path_pan($par_id);
            foreach($image_pan as $image ) {  ?>
           <div class="col-md-2">
               <?php if(!empty($image->pan_card_of_partnetship)) { ?>
                    <a href="<?php echo $image->pan_card_of_partnetship; ?>" class="html5lightbox file_icons" data-width="480" data-height="320" data-group="mygroup" data-thumbnail="<?php echo $image->pan_card_of_partnetship; ?>"><i class="fa fa-image"></i></a>
               <?php } ?>
            </div>
            <?php $i++; }
        } ?> 
        </div>
 
    <?php } else if($tot_docu->document_name =='Aadhaar Card of Partners')  { ?>
                
        <?php if($j == 1 ){ ?>    
 
        <div class="" > <?php
            $images_aadhar = $this->Applicants_login_model->select_cur_path_aadhar($par_id);
            foreach($images_aadhar as $image ) {  ?>
            <div class="col-md-2">
            <a href="<?php if(!empty($image->aadhar_card_of_partnetship)) {  echo  $image->aadhar_card_of_partnetship;  } ?>" target="_blank"  class="file_icons"><i class="fa fa-image"></i></a>
            </div>
              <?php  $j++; } ?> </div> <?php } ?> 
         <?php  } 
    else { 
        $images = $this->Applicants_login_model->select_cur_path($par_id); 
        //print_r($images);
        foreach($images as $image ) { 
            if($tot_docu->document_name == 'Original Partnership Deed') { ?>
                <div class="col-md-3">
                    <a href="<?php if(!empty($image->orignal_partnership_deed)) {
                    echo  $image->orignal_partnership_deed;  } ?>" target="_blank"  class="file_icons"><i class="fa fa-image"></i></a>
                </div>
            <?php } else if($tot_docu->document_name == 'Deed of Reconstitution of Partnership') { ?>

            <div class="col-md-3">
            <a href="<?php if(!empty($image->deed_of_reconstitution_of_partnership)) {   echo  $image->deed_of_reconstitution_of_partnership; } ?>" target="_blank"  class="file_icons"><i class="fa fa-image"></i></a> 
            </div>
 
            <?php } else if(trim($tot_docu->document_name) == 'IGR Certificate') { ?>

            <div class="col-md-3">
            <a href="<?php if(!empty($image->igr_certificate_file)) { 
            echo  $image->igr_certificate_file; } ?>" target="_blank"  class="file_icons"><i class="fa fa-image"></i></a> </div>
            
            <?php } else if($tot_docu->document_name == 'Original Newspaper Advertisement') { ?>

            <div class="col-md-3">
            <a href="<?php if(!empty($image->orignal_newspaper_of_advertisement)) { 
            echo  $image->orignal_newspaper_of_advertisement; } ?>" target="_blank"  class="file_icons"><i class="fa fa-image"></i></a>
            </div>
 
            <?php } else if($tot_docu->document_name == 'Notice in Softcopy') { ?>

            <div class="col-md-6">
            <a href="<?php if(!empty($image->pdf_for_notice_of_softcopy)) { echo $image->pdf_for_notice_of_softcopy; } ?>" target="_blank"  class="file_icons"><i class="fa fa-file-pdf-o"></i></a>
            </div>

            <?php } 
        }   } ?>
</div>                                    
<?php  } ?> </div>  </div> 
                             <div class="row">
                    <div class="pdf-container">
                        <embed src="<?php echo $details->pdf_for_notice_of_softcopy; ?>" type="application/pdf" width="100%" height="650px" internalinstanceid="8">
                    </div>
                </div>
                        <div class="panel-group" id="accordion">
                           <div class="panel panel-default">
                               <div class="panel-heading">
                                   <h4 class="panel-title">
                                       <a data-toggle="collapse" data-parent="#accordion" href="#collapse_1">Statut History</a>
                                   </h4>
                               </div>
                               <div id="collapse_1" class="panel-collapse collapse">
                                   <div class="panel-body panel-body-no-padding">
                                       <div class="row">
                                           <div class="col-lg-12 col-md-12">
                                               <ul class="list-group">
                                                    <?php if (!empty($status_list)) { ?>
                                                        <?php foreach ($status_list as $status) { //echo $status->id;
     $select_det_remark =  $this->Applicants_login_model->select_det_remark($status->id); 
     
     //print_r($select_det_remark); ?>
     <li class="list-group-item"><span class="badge bg-default hstry_class"><?php echo get_formatted_datetime($status->created_at); ?></span><?php if(empty($select_det_remark->remark)) {
                 echo $status->status_det;
            } else {
               echo $status->status_det .'('.$select_det_remark->remark.')';
            } ?> </li>
                                                        <?php } ?>
                                                    <?php } ?>
                                                </ul>
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
                                                        <label><?php echo $list->docu; ?></label><br>
    <?php $get_all_docu =  $this->Applicants_login_model->get_all_docu($list->did,$par_id); 
    //print_r($get_all_docu);
    foreach($get_all_docu as $get_all) { ?>      
                                                        
                     <div class="row">

            <?php if($get_all->gz_docu_id == '7') { ?>
            <div class="col-md-4">
                 <a href="<?php if(!empty($get_all->docu)) { echo $get_all->docu; } ?>" target="_blank"  class="file_icons"><i class="fa fa-file-pdf-o"></i></a>
            </div>
            <div class="col-md-4"><?php echo $get_all->created_at; ?></div><br>
            <?php } else { ?>
            <div class="col-md-4">
                <a href="<?php if(!empty($get_all->docu)) {  echo  $get_all->docu;  } ?>" target="_blank"  class="file_icons"><i class="fa fa-image"></i></a> 
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
                            
           <?php echo form_open("https://uat.(StateName)treasury.gov.in/echallan/Tax.do", array("method" => "POST", "id" => "form2", "name" => "form2")); ?>

		<input name="transactionDetail" type="hidden" value="(0058-00-200-0127-02082-000, Change of Partnetship, <?php echo $price; ?>)"/>
		<input name="depositedBy" type="hidden" value="<?php echo $gz_dets->name; ?>"/>
		<input name="contactNo" type="hidden" value="<?php echo $gz_dets->mobile; ?>"/>
		<input name="emailId" type="hidden" value="<?php echo $gz_dets->email; ?>"/>
		<input name="district" type="hidden" value="Khurda"/>
		<input name="depositorAddress" type="hidden" value="<?php echo $gz_dets->address; ?>"/>
		<input name="deptName" type="hidden" value="EGZ"/>
                
		<input name="deptRefId" type="hidden" value="<?php echo uniqid(time()); ?>"/>
                <input name="partnership_id" type="hidden" value="<?php echo $par_id; ?>"/>
                
               	<input name="otherParameters" type="hidden" value="ref_no=<?php echo uniqid(time()).'_'.$par_id; ?>!~!ref_id=<?php  echo uniqid(time()).'_'.$gz_dets->file_no; ?>!~!price= <?php echo $price; ?>!~!redirect_url=<?php echo base_url(); ?>applicants_login/change_partnership_details"/>
                
			<input type="submit" value="Make Payment" class="btn btn-raised btn-danger" id="make_payment_btn"/>
            <?php echo form_close(); ?>
            
                    </div>
                             <?php }  } ?>
<!--                </div>         -->
               
                <!--<div class="row">    </div>--> 
            
                </section>
                
            </div>
        </div>
        <?php if($this->session->userdata('is_applicant')) { ?>
            <?php if($gz_dets->cur_status == 13) { ?>
                <div class="row">
            <div class="col-md-12">
                <section class="boxs">
                    <div class="boxs-header">
                        <h3 class="custom-font hb-blush">Change of Partnership Resubmission</h3>
                    </div>
                    <?php echo form_open('', array('name' => 'form_pa', 'id' => 'form_pa', 'method' => 'post', 'enctype' => 'multipart/form-data')); ?>
                    <div class="boxs-body">                       
                        
                        <div class="row" > 
                            <?php
                            //print_r($tot_docus);
                            $file_type = "";
                            foreach ($tot_docus as $tot_docu) {
                                $file_type = "image/*"
                                ?>
                                <?php if ($tot_docu->document_name == 'PAN Card of Partners') { ?>
                                    <hr>
                                    <div class="col-md-12" id="app_pan">
                                        <div  class="col-md-6">
                                            <?php } else if ($tot_docu->document_name == 'Aadhaar Card of Partners') { ?>
                                            <div class="col-md-12" id="app_aadhar">
                                                <div  class="col-md-6">
                                                    <?php } else { ?>
                                                    <div class="col-md-6">
                                                    <?php } ?>
                                                    <label for="email"><?php echo trim(ucfirst($tot_docu->document_name)); ?><span class="asterisk">*</span>
                                                        <span class="file_icons_add">
                                                            <?php if ($tot_docu->document_name == 'Notice in Softcopy') {
                                                                $file_type = ".docx";
                                                                ?>
                                                                <i class="fa fa-file-word-o"></i>
                                                            <?php } ?>
                                                        </span>
                                                    </label>
                                                    <div class="row fileupload-buttonbar clearfix">
                                                        <div class="col-lg-6">
                                                            <span class="btn btn-raised btn-success fileinput-button">
                                                                <i class="glyphicon glyphicon-plus"></i>
                                                                <?php if ($tot_docu->document_name == 'Notice in Softcopy') { ?>
                                                                    <span>Choose File</span>
                                                                <?php } else { ?>
                                                                    <span>Select Image</span>
                                                                <?php } ?>
                                                                <?php if ($tot_docu->id == '4' || $tot_docu->id == '5') { ?>
                                                                    <input type="file" name="upload_<?php echo $tot_docu->id; ?>" id="upload_<?php echo $tot_docu->id . '_1'; ?>" accept="<?php echo $file_type; ?>">
                                                                    
                                                                    <input type="hidden" id="img_id_<?php echo $tot_docu->id . '_1'; ?>" name="img_id_<?php echo $tot_docu->id; ?>[]" />
    <?php } else { ?>
                                                                    <input type="file" name="upload_<?php echo $tot_docu->id; ?>" id="upload_<?php echo $tot_docu->id; ?>" >
                                                                    
                                                                    <input type="hidden" id="img_id_<?php echo $tot_docu->id; ?>" name="img_id_<?php echo $tot_docu->id; ?>[]" />

                                                                <?php } ?>
                                                                <input type="hidden" id="count" name ="count" value="<?php echo $count; ?>" />
                                                            </span>
                                                            <span class="files_<?php echo $tot_docu->id; ?>"></span>
                                                        </div>
                                                        <?php if ($tot_docu->document_name == 'PAN Card of Partners') { ?>
                                                            <a id="<?php echo $tot_docu->id; ?>" class="btn_add_pan "><i class="fa fa-plus" ></i></a>   
                                                    <?php } else if ($tot_docu->document_name == 'Aadhaar Card of Partners') { ?>
                                                            <a id="<?php echo $tot_docu->id; ?>" class="btn_add_aadhar "><i class="fa fa-plus" ></i></a>  
                                                    <?php } ?>
                                                    </div>
                                                    <?php if ($tot_docu->document_name == 'Notice in Softcopy') { ?>
                                                        <span class="help-block mb-0">Maximum 5 MB allowed.</span>
                                                        <span class="error_message_word_<?php echo $tot_docu->id; ?>">Please upload only MS Word (.docx) file.</span>
                                                    <?php } else { ?>
                                                        <span class="help-block mb-0">Maximum 1 MB allowed.</span>
                                                        <span class="error_message_image_<?php echo $tot_docu->id; ?>">Please upload only JPEG/PNG image.</span>
                                                        
                                                    <?php } ?>
                                                    <div class="clearfix"></div>
                                                    <?php if (form_error('doc_files')) { ?>
                                                        <span class="error"><?php echo form_error('doc_files'); ?></span>
                                                    <?php } ?>
                                                </div>
                                                <script type="text/javascript" nonce="a6b9a780936c8e980939086f618dded2">
                                                    $(document).ready(function () {
                                                        $('.loader').hide();
                                                        $('.error_message_image_<?php echo $tot_docu->id; ?>').hide();
                                                        $('.error_message_image_size_<?php echo $tot_docu->id; ?>').hide();
                                                        $('.error_message_word_<?php echo $tot_docu->id; ?>').hide();

                                                        $('#upload_<?php echo $tot_docu->id; ?>').change(function () {

                                                            var input_file = $('#upload_<?php echo $tot_docu->id; ?>').prop('files')[0];

                                                            var ext = input_file['name'].substring(input_file['name'].lastIndexOf('.') + 1).toLowerCase();

                                                            if (<?php echo $tot_docu->id; ?> == 7) {
                                                                if(input_file && (ext == "docx")) {
                                                                    // Display filename in the span help block
                                                                    $('.files_<?php echo $tot_docu->id; ?>').html($(this).val().split('\\').pop());
                                                                    $('.error_message_word_<?php echo $tot_docu->id; ?>').hide();
                                                                } else {
                                                                    $('.error_message_word_<?php echo $tot_docu->id; ?>').css('color', 'red');
                                                                    $('.error_message_word_<?php echo $tot_docu->id; ?>').show();
                                                                }
                                                            } else {
                                                                if (input_file && (ext == "png" || ext == "jpeg" || ext == "jpg")) {
                                                                    // Display filename in the span help block
                                                                    $('.files_<?php echo $tot_docu->id; ?>').html($(this).val().split('\\').pop());
                                                                    $('.error_message_image_<?php echo $tot_docu->id; ?>').hide();

                                                                    var id = <?php echo $tot_docu->id; ?>;

                                                                    var reader = new FileReader();
                                                                    reader.onload = function (e) {
                                                                        //$('.image_preview_<?php echo $tot_docu->id; ?>').attr('src', e.target.result)
                                                                    };
                                                                    reader.readAsDataURL(input_file);
                                                                    if (id != 7) {
                                                                        upload_notification_file(input_file, id, '');
                                                                    }
                                                                
                                                                } else {
                                                                    $('.error_message_image_<?php echo $tot_docu->id; ?>').css('color', 'red');
                                                                    $('.error_message_image_<?php echo $tot_docu->id; ?>').show();
                                                                }
                                                            }
                                                        });

                                                        $('#upload_<?php echo $tot_docu->id; ?>' + '_1').change(function () {
                                                            
                                                            var input_file = $('#upload_<?php echo $tot_docu->id; ?>' + '_1').prop('files')[0];
                                                            var ext = input_file['name'].substring(input_file['name'].lastIndexOf('.') + 1).toLowerCase();

                                                            if (input_file && (ext == "png" || ext == "jpeg" || ext == "jpg")) {
                                                                // Display filename in the span help block
                                                                $('.files_<?php echo $tot_docu->id; ?>').html($(this).val().split('\\').pop());
                                                                $('.error_message_image_<?php echo $tot_docu->id; ?>').hide();

                                                                var id = <?php echo $tot_docu->id; ?>;

                                                                var reader = new FileReader();
                                                                reader.onload = function (e) {

                                                                };
                                                                reader.readAsDataURL(input_file);
                                                                upload_notification_file(input_file, id, '1');
                                                            } else {
                                                                $('.error_message_image_<?php echo $tot_docu->id; ?>').css('color', 'red');
                                                                $('.error_message_image_<?php echo $tot_docu->id; ?>').show();
                                                            }
                                                        });

                                                        $(document).on('change', '#upload_<?php echo $tot_docu->id; ?>' + '_2', function () {
                //                                    $('#upload_<?php echo $tot_docu->id; ?>'+'_2').on('Change',function () {

                                                            var input_file = $('#upload_<?php echo $tot_docu->id; ?>' + '_2').prop('files')[0];
                                                            var ext = input_file['name'].substring(input_file['name'].lastIndexOf('.') + 1).toLowerCase();

                                                            if (input_file && (ext == "png" || ext == "jpeg" || ext == "jpg")) {

                                                                $('.error_message_image_<?php echo $tot_docu->id; ?>').hide();

                                                                var id = <?php echo $tot_docu->id; ?>;

                                                                var reader = new FileReader();
                                                                reader.onload = function (e) {

                                                                };
                                                                reader.readAsDataURL(input_file);
                                                                upload_notification_file(input_file, id, '2');
                                                            } else {
                                                                $('.error_message_image_<?php echo $tot_docu->id; ?>').css('color', 'red');
                                                                $('.error_message_image_<?php echo $tot_docu->id; ?>').show();
                                                            }
                                                        });

                                                        /*
                                                         * Upload notification file
                                                         */
                                                        function upload_notification_file(file, id, sub_id) {
                                                            // diaplay loader
                                                            $('.loader').show();
                                                            var fd = new FormData();
                                                            var token = $("input[name=<?php echo $this->security->get_csrf_token_name(); ?>]").val();
                                                            fd.append('file', file);
                                                            fd.append('id', id);
                                                            fd.append('file_no_par', '<?php echo $gz_dets->file_no; ?>');
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
                                                                    //alert(<?php //echo $tot_docu->id;  ?>);
                                                                    var json = JSON.parse(msg);
                                                                    //console.log(json);
                                                                    if (json['success']) {
                                                                        $('.loader').hide();
                                                                        $(".notif_file").hide();
                                                                        $(".notif_file").html();

                                                                        if (id == 4 || id == 5) {
                                                                            //alert(id);
                                                                            if (sub_id == 1) {
                                                                                $('#img_id_' + id + '_1').val(json['success']);
                                                                            } else {
                                                                                $('#img_id_' + id + '_2').val(json['success']);
                                                                            }
                                                                        } else {
                                                                            $('#img_id_' + id).val(json['success']);
                                                                        }

                                                                    }
                                                                    if (json['error']) {
                                                                        $('.loader').hide();
                                                                        $(".notif_file").html(json['error']);
                                                                        $('#img_id_' + id).val('');
                                                                        $(".notif_file").show();
                                                                    }
                                                                },
                                                                error: function (msg) {
                                                                    $('.loader').hide();
                                                                }
                                                            });
                                                        }
                                                    });

                                                </script>
                                        <?php if ($tot_docu->document_name == 'PAN Card of Partners') { ?>
                                                </div>
                                        <?php } else if ($tot_docu->document_name == 'Aadhaar Card of Partners') { ?>
                                            </div>
                                        <?php } else { ?>

                                    <?php } ?>
                                <?php } ?>
                                </div>
                                <input type="hidden" id="pan_text" name ="pan_text" value="2" />
                                <input type="hidden" id="par_id" name="par_id" value="<?php echo $par_id; ?>">
                                <input type="hidden" id="aadhar_text" name ="aadhar_text" value="2" />
                                <input type="hidden" id="file_no_par" name="file_no_par" value="<?php echo $gz_dets->file_no; ?>"
                                       
                                <div id="error_msg" class="error"></div>
                                <div class="boxs-footer text-right bg-tr-black lter dvd dvd-top">
                                    <button type="submit" class="btn btn-raised btn-success" id="form4Submit">Resubmit</button>
                                </div>
                                <?php echo form_close(); ?>
                                </section>
                                
                            </div>
                        </div>
            <?php } ?>
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
    
    <!--  return  applicant Modal -->
    <div class="modal fade" id="ret_applicant" tabindex="-1" role="dialog" aria-labelledby="myModalLabel" aria-hidden="true" data-backdrop="false">

        <?php echo form_open('', array('class' => "ret_applicant_form", 'id' => "ret_applicant_form", 'name' => "ret_applicant_form", 'method' => "post")); ?>    
            <input type="hidden" name="ret_appli" id="ret_appli" value=""/>
            <div class="modal-dialog modal-md">
                <div class="modal-content">
                    <div class="modal-header">
                        <button type="button" class="close" data-dismiss="modal">&times;</button>
                        <h4 class="modal-title">Reject Partnership Change </h4>
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
    <!-- Modal -->
    
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
    
    
    
    
    <!--  return  applicant Modal -->
    <div class="modal fade" id="ret_applicant" tabindex="-1" role="dialog" aria-labelledby="myModalLabel" aria-hidden="true" data-backdrop="false">

        <?php echo form_open('', array('class' => "ret_applicant_form", 'id' => "ret_applicant_form", 'name' => "return_to_ct_form", 'method' => "post")); ?>    
            <input type="hidden" name="ret_appli" id="ret_appli" value=""/>
            <div class="modal-dialog modal-md">
                <div class="modal-content">
                    <div class="modal-header">
                        <button type="button" class="close" data-dismiss="modal">&times;</button>
                        <h4 class="modal-title">Reject Partnership Change </h4>
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
    
</section>




<script src="https://code.jquery.com/jquery-3.4.1.min.js" type="text/javascript" nonce="a6b9a780936c8e980939086f618dded2"></script>
<script type="text/javascript" nonce="a6b9a780936c8e980939086f618dded2">
    
$(document).ready(function () {
    $(".loader").hide();
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
    $('#applicant').on('click', function(){
        ////alert('ok');
        var id = $('#par_id').val();
        //alert(id);
        $('#ret_appli').val(id);       
    });
    
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
    aadhar_card_load('5');
    
    pan_card_load('4'); 
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
                upload_1: {
                    required: true
                },
                upload_2: {
                    required: true
                },
                upload_3: {
                    required: true
                },
                upload_6: {
                    required: true
                },
                upload_7: {
                    required: true
                }
            },
            messages: {
                upload_1: {
                    required: "Please upload original partnership deed"
                },
                upload_2: {
                    required: "Please upload deed of reconstition of partnership"
                },
                upload_3: {
                    required: "Please upload IGR certificate"
                },
                upload_6: {
                    required: "Please upload original newspaper advertisement"
                },
                upload_7: {
                    required: "Please upload notice in softcopy"
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
                         $(".loader").hide();
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
                     },

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
                   location.reload();
                },
                error: function (data) {
                   location.reload();
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
                   location.reload();
                },
                error: function (data) {
                   location.reload();
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
                   location.reload();
                },
                error: function (data) {
                   location.reload();
                }
            }) 
            }
        }) 
        
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
                   location.reload();
                },
                error: function (data) {
                   location.reload();
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
                   location.reload();
                },
                error: function (data) {
                   location.reload();
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
                   location.reload();
                },
                error: function (data) {
                   location.reload();
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
                   location.reload();
                },
                error: function (data) {
                   location.reload();
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
            url: "<?php echo base_url(); ?>applicants_login/add_pan",
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
            url: "<?php echo base_url(); ?>applicants_login/add_aadhar",
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
