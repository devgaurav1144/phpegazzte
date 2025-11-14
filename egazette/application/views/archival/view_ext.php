<?php defined('BASEPATH') OR exit('No direct script access allowed'); ?>
<style rel="stylesheet" nonce="8f0882ce3be14f201cadd0eff5726cbd">
    .left_txt {
        float: left;
    }
</style>
<script src="https://code.jquery.com/jquery-1.12.4.min.js" type="text/javascript" nonce="a6b9a780936c8e980939086f618dded2"></script>

<!-- CONTENT -->
<section id="content">
    <div class="page page-forms-validate">
        <!-- bradcome -->
        <ol class="breadcrumb">
            <li><a href="<?php echo base_url(); ?>applicants_login/dashboard">Dashboard</a></li>
            <li><a href="<?php echo base_url(); ?>archival/extraordinary">Extraordinary Gazette</a></li>
            <li class="active">Extraordinary Archival Gazette</li>
        </ol>
        <!-- row -->
        <div class="row">
            <div class="col-md-12">
                <section class="boxs">
                    <div class="boxs-header">
                        <h3 class="custom-font hb-blue"><strong>View Details</strong></h3>
                    </div>
                    <div class="clearfix"></div>
                    <div class="boxs-body">
                        <div class="border_data">
                            <div class="row">
                                <div class="form-group col-md-6">
                                    <label for="username">Department : </label>
                                    <?php echo $gz_dets->department_name; ?>
                                </div>
                                <div class="form-group col-md-6">
                                    <label for="email">Notification Type : </label>
                                    <?php echo $gz_dets->notification_type; ?>
                                </div>
                            </div>
                            <div class="row">
                                <div class="form-group col-md-6">
                                    <label for="username">Subject : </label>
                                    <?php echo $gz_dets->subject; ?>
                                </div>
                                <div class="form-group col-md-6">
                                    <label for="email">Notification Number : </label>
                                    <?php echo $gz_dets->notification_number; ?>
                                </div>
                            </div>
                            <div class="row">
                                <div class="form-group col-md-6">
                                    <label for="username">Gazette Number : </label>
                                    <?php echo $gz_dets->gazette_number; ?>
                                </div>
                                <div class="form-group col-md-6">
                                    <label for="email">Published Date : </label>
                                    <?php echo date("d/m/Y", strtotime($gz_dets->published_date)); ?>
                                </div>
                            </div>
                        </div>  
                        
                        <div class="row">
                            <div class="pdf-container">
                                <embed src="<?php echo base_url() . $gz_dets->gazette_file; ?>" type="application/pdf" width="100%" height="650px" internalinstanceid="8">
                            </div>
                        </div>
                    </div>
                </section> 
            </div>
        </div>
    </div>
</section>
<script type="text/javascript" nonce="a6b9a780936c8e980939086f618dded2">
    $(document).ready(function () {

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