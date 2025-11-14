<?php defined('BASEPATH') OR exit('No direct script access allowed'); ?>
<style rel="stylesheet" nonce="8f0882ce3be14f201cadd0eff5726cbd">
	.left_txt {
		float: left;
	}
</style>
<!-- CONTENT -->
<section id="content">
    <div class="page page-forms-validate">
        <!-- bradcome -->
        <div class="bg-light lter b-b wrapper-md mb-10">
            <div class="row">
                <div class="col-sm-6 col-xs-12">
                    <h1 class="h3 m-0">Applicant Preview Details</h1>
                </div>
            </div>
        </div>
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
                        <h3 class="custom-font hb-cyan left_txt">Applicant Preview Details</h3>
                    </div>
                    <div class="clearfix"></div>
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
                        </div>  <div class="border_data"> <div class="row">
                        <?php 
                        foreach ($tot_docus as $tot_docu) { ?>
                        
                                <div class="form-group col-md-6">
                                    <label for="email"><?php echo $tot_docu->document_name; ?>
                                    </label><br>
                                    
                                    <?Php  if($tot_docu->document_name =='PAN Card of Partners') { 
                                    $image_pan = $this->Applicants_login_model->select_cur_path_pan($id);
                                    //print_r($image_pan);
                                    foreach($image_pan as $image ) {  ?>
                                            <div class="col-md-3">
                                                                                              
                                                <a href="<?php if(!empty($image->pan_card_of_partnetship)) {
                                                    echo  $image->pan_card_of_partnetship;  } ?>" target="_blank"  class="file_icons"><i class="fa fa-image"></i></a>
                                                
                                                
                                            </div>
                                    <?php  } } else if($tot_docu->document_name =='Aadhaar Card of Partners')  {
                                        $images_aadhar = $this->Applicants_login_model->select_cur_path_aadhar($id);
                                         foreach($images_aadhar as $image ) {  ?>
                                            <div class="col-md-3">
                                               
                                                  <a href="<?php if(!empty($image->aadhar_card_of_partnetship)) {
                                            echo  $image->aadhar_card_of_partnetship;  } ?>" target="_blank"  class="file_icons"><i class="fa fa-image"></i></a>
                                                    
                                            </div>
                                         <?php  } } else {  
                                    $images = $this->Applicants_login_model->select_cur_path($id); 
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
                                        echo  $image->igr_certificate_file; } ?>" target="_blank"  class="file_icons"><i class="fa fa-image"></i></a>
                                                                
                                                                
                                    </div>
                                    <?php } else if($tot_docu->document_name == 'Original Newspaper Advertisement') { ?>
                                    <div class="col-md-3">
                           
                                        <a href="<?php if(!empty($image->orignal_newspaper_of_advertisement)) { 
                                        echo  $image->orignal_newspaper_of_advertisement; } ?>" target="_blank"  class="file_icons"><i class="fa fa-image"></i></a>
                                        
                                    </div>
                                    <?php } else if($tot_docu->document_name == 'Notice in Softcopy') { ?>
                                    <div class="col-md-6">

                                        <a href="<?php if(!empty($image->pdf_for_notice_of_softcopy)) {                                                                echo $image->pdf_for_notice_of_softcopy; } ?>" target="_blank"  class="file_icons"><i class="fa fa-file-pdf-o"></i></a>
                                         
                       
                                    </div>
                                    
                                    <?php } 
                                         } } ?>
                                </div>
                            
                        <?php  } ?></div></div>
                        <div class="row">
                            <div class="pdf-container">
                                <embed src="<?php echo $details->pdf_for_notice_of_softcopy; ?>" type="application/pdf" width="100%" height="650px" internalinstanceid="8">
                            </div>
                        </div>
                        <?php
//print_r($details);
                            $pdftext = file_get_contents($details->pdf_for_notice_of_softcopy);
                            $num_pag = preg_match_all("/\/Page\W/", $pdftext, $dummy);
                            $window_path = str_replace('/', '\\', $details->pdf_for_notice_of_softcopy);
                            $pdf_path = substr($details->pdf_for_notice_of_softcopy, 1);
                        ?>
                        <div class="boxs-footer text-right bg-tr-black lter dvd dvd-top">
                            
                            <?php if (!empty($details->pdf_for_notice_of_softcopy)) { ?>
                                <a href="<?php echo $details->pdf_for_notice_of_softcopy; ?>" class="btn btn-raised btn-success" target="_blank">View Signed PDF<div class="ripple-container"></div></a>
                            <?php } ?>
                            <?php if (empty($details->pdf_for_notice_of_softcopy)) { ?>
                                    <a href="http://localhost:81/StartPage.aspx?files=<?php echo $pdf_path; ?>&page=<?php echo $num_pag; ?>&gazette_id=<?php echo $details->gazette_id; ?>&type=dept&category=extra&signed_name=<?php echo $signed_name; ?>&designation=<?php echo $signed_designation; ?>" class="btn btn-raised btn-info dept_sign_btn" id="sign_pdf_btn">Sign PDF<div class="ripple-container"></div></a>
                            <?php } ?>
                            
                         
                        </div>
                   
                </section> </div>
            </div>
       
</section>



<script src="https://code.jquery.com/jquery-1.12.4.min.js" type="text/javascript" nonce="a6b9a780936c8e980939086f618dded2"></script>
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