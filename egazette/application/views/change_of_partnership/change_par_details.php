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
        <ol class="breadcrumb">
            <li><a href="<?php echo base_url(); ?>applicants_login/dashboard">Dashboard</a></li>
            <li><a href="<?php echo base_url(); ?>applicants_login/partnership_change_list_govt">Change of Partnership</a></li>
            <li class="active">Change of Partnership Details</li>
        </ol>
        <?php echo form_open('applicants_login/forward_to_pay', array('name' => 'form_pa',  'id' => 'form_pa', 'method' => 'post', 'enctype' => 'multipart/form-data')); ?>
        <input type="hidden" name="par_id" id="par_id" value="<?php echo $par_id; ?>">
        <input type="hidden" name="file_no_par" id="file_no_par" value="<?php echo $gz_dets->file_no; ?>">
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
                        <h3 class="custom-font hb-blue"><strong>Change of Partnership Details</strong></h3>
                    </div>
                    <div class="clearfix"></div>
                    <div class="boxs-body">
                        <div class="border_data">
                            <div class="row">
                                <div class="form-group col-md-6">
                                    <label for="username">Applicant name : </label>
                                    <?php echo $gz_dets->name; ?>
                                </div>
                                <div class="form-group col-md-6">
                                    <label for="username">File No : </label>
                                    <?php echo $gz_dets->file_no; ?>
                                </div>
                            </div>
                            <div class="row">
                                <div class="form-group col-md-6">
                                    <label for="username">Status : </label>
                                    <?php echo $gz_dets->status_det; ?>
                                </div>
                                <div class="form-group col-md-6">
                                    <label for="email">Date : </label>
                                    <?php echo strftime('%d %b %Y, %I:%M %p', strtotime($gz_dets->created_at)); ?>
                                </div>
                            </div>
                        </div> 
                        <div class="row">
                            <div class="pdf-container">
                                <?php if ($gz_dets->press_pdf != "") { ?>
                                <embed src="<?php echo base_url() . $gz_dets->press_pdf; ?>" type="application/pdf" width="100%" height="650px" internalinstanceid="8">
                                <?php } else { ?>
                                    <embed src="<?php echo $details->pdf_for_notice_of_softcopy; ?>" type="application/pdf" width="100%" height="650px" internalinstanceid="8">
                                <?php } ?>
                                
                            </div>
                        
                        <?php $num_pag ="";
                        if($gz_dets->cur_status == 16) { ?>
                        <div class="boxs-footer text-right bg-tr-black lter dvd dvd-top">
                            
                            <div class="form-group col-md-12">
                                    <label for="username">No of Page : </label>
                                                                  
                                   <?php
                                   
                                   $pdftext = file_get_contents($details->pdf_for_notice_of_softcopy);
                                   $num_pag = preg_match_all("/\/Page\W/", $pdftext, $dummy);
                                   
                                   echo $num_pag;
                                   
                                   
//                                    $pdftext = file_get_contents($det->pdf_for_notice_of_softcopy);
//                            $num_pag = preg_match_all("/\/Page\W/", $pdftext, $dummy);
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
                            
                            <button class="btn btn-raised btn-success dept_sign_btn" id="for_to_pay" type="submit">Enable Payment Option<div class="ripple-container"></div></button>
                    </div>
                        <?php } ?>
            <?php   echo form_close(); ?>
            </div>
            </section> </div>
            </div>   
            <div class="boxs-footer text-right bg-tr-black lter dvd dvd-top">

<?php if ($gz_dets->cur_status == 7 && !empty($gz_dets->press_pdf)) { 
        $pdftext = file_get_contents($details->press_pdf);
        $num_pag = preg_match_all("/\/Page\W/", $pdftext, $dummy);
        
        ?>
   
    
    <a href="http://localhost:81/StartPage.aspx?files=<?php echo $gz_dets->press_pdf; ?>&page=<?php echo $num_pag; ?>&gazette_id=<?php echo $par_id; ?>&type=press&category=cop&signed_name=<?php echo $signed_name; ?>&designation=<?php echo $designation; ?>" class="btn btn-raised btn-info govt_press_sign" id="govt_press_sign" >Sign PDF<div class="ripple-container"></div></a>

    <?php } else if ($gz_dets->cur_status == 8 && !empty($gz_dets->press_signed_pdf) && $gz_dets->press_publish == 0) { ?>

        <a href="<?php echo base_url() . $gz_dets->press_signed_pdf; ?>" class="btn btn-raised btn-success" target="_blank">View Signed PDF</a>
		<button class="btn btn-raised btn-success govt_publish" id="govt_publish">Publish<div class="ripple-container"  ></div></button>

    <?php } ?>
   
                
    <?php if($gz_dets->cur_status == 17 && $gz_dets->press_publish == 1 ) { ?>

        <a href="<?php echo base_url() . $gz_dets->press_signed_pdf; ?>" class="btn btn-raised btn-success" target="_blank">View Signed PDF</a>

    <?php } ?> 
        
         <?php if($gz_dets->cur_status == 7 && empty($gz_dets->press_pdf)) { ?>

        
        <a href="<?php echo base_url('applicants_login/press_add/'.$par_id); ?>" class="btn btn-raised btn-success">Press Add<div class="ripple-container"></div></a>
 
        

    <?php } ?> 
               
           
             </div>
             
</section>
<script src="https://code.jquery.com/jquery-1.12.4.min.js" type="text/javascript" nonce="a6b9a780936c8e980939086f618dded2"></script>
<script src="https://code.jquery.com/jquery-3.4.1.min.js" type="text/javascript" nonce="a6b9a780936c8e980939086f618dded2"></script>
<script type="text/javascript" nonce="a6b9a780936c8e980939086f618dded2">
$(document).ready(function () {  
    //alert();
    /*
    *  for send pdf for signed
    */
//    $('#govt_press_sign').click(function(){
//         
//        var par_id = $('#par_id').val();
//        
//        $.ajax({
//            type: "POST",
//            url: "<?php echo base_url(); ?>applicants_login/govt_press_sign",
//            data: {
//                par_id :par_id,
//                '<?php echo $this->security->get_csrf_token_name(); ?>' : '<?php echo $this->security->get_csrf_hash(); ?>'
//            }, 
//
//            //if success, returns success message
//            success: function (data) {
//                location.reload();
//            },
//            error: function (data) {
//                location.reload();
//            }
//            }) 
//         
//     }) ;
     /*
     * govt publish pdf
      */
     $('#govt_publish').click(function(){
         
        var par_id = $('#par_id').val();
        
        $.ajax({
            type: "POST",
            url: "<?php echo base_url(); ?>applicants_login/govt_publish",
            data: {
                par_id :par_id,
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
         
     }) ;
     
           }) 
        
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