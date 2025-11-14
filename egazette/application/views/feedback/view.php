<?php defined('BASEPATH') OR exit('No direct script access allowed'); ?>
<!-- CONTENT -->
<section id="content">
    <div class="page page-forms-validate">
        <!-- bradcome -->
        <ol class="breadcrumb">
            <li><a href="<?php echo base_url(); ?>applicants_login/dashboard">Dashboard</a></li>
            <li>CMS</li>
            <li class="active">Feedback Details</li>
        </ol>
        <!-- row -->
        <div class="row">
            <div class="col-md-12">
                <section class="boxs">
                    <div class="boxs-header">
                        <h3 class="custom-font hb-blue"><strong>Feedback Details</strong></h3>
                    </div>
                    <div class="boxs-body">
                        <div class="border_data">
                            <div class="row">
                                <div class="form-group col-md-6">
                                    <label for="username">User Name : </label>
                                    <?php echo $details->name; ?>
                                </div>
                                <div class="form-group col-md-6">
                                    <label for="email">Email : </label>
                                    <?php echo $details->email; ?>
                                </div>
                            </div>
                            <div class="row">
                                <div class="form-group col-md-6">
                                    <label for="username">Mobile : </label>
                                    <?php echo $details->mobile; ?>
                                </div>
                                <div class="form-group col-md-6">
                                    <label for="email">Date : </label>
                                    <?php echo get_formatted_datetime($details->created_at); ?>
                                </div>
                            </div>
                            <div class="row">
                                <div class="form-group col-md-6">
                                    <label for="username">Occupation : </label>
                                    <?php echo $details->occupation; ?>
                                </div>
                                <div class="form-group col-md-6">
                                    <label for="email">Subject : </label>
                                    <?php echo $details->subject; ?>
                                </div>
                            </div>
                            <div class="row">
                                <div class="form-group col-md-6">
                                    <label for="email">Address : </label>
                                    <?php echo $details->address; ?>
                                </div>
                                <div class="form-group col-md-6">
                                    <label for="username">Feedback : </label>
                                    <?php echo $details->feedback; ?>
                                </div>
                            </div>
                        </div>
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