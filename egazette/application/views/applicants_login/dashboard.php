<?php defined('BASEPATH') OR exit('No direct script access allowed'); ?>
<style rel="stylesheet" nonce="8f0882ce3be14f201cadd0eff5726cbd">
    .success_login {
        color: #28a745;
    }
    .error {
        color: #D9534F;
    }
    .min-height {
        min-height: 140px;
    }
</style>
<section id="content">
    <div class="page dashboard-page">
        <!-- bradcome -->
        <div class="b-b mb-20">
            <div class="row">
                <div class="col-sm-12 col-xs-12">
                    
                    <h1 class="h3 m-0">
                        <?php if ($this->session->userdata('is_applicant')) { ?>
                            Applicant's Dashboard
                        <?php } else if ($this->session->userdata('is_igr')) { ?>
                            IGR Dashboard
                        <?php } else if ($this->session->userdata('is_c&t')) { ?>
                            C&T Dashboard
						<?php } else if ($this->session->userdata('is_offline_approver')) { ?>
                            Offline Approver Dashboard
                        <?php } ?>
                    </h1>
                </div>
            </div>
        </div>
        <?php if ($this->session->flashdata('success')) { ?>
            <span class="error"><?php echo $this->session->flashdata('success'); ?></span>
        <?php } ?>
        <?php if ($this->session->flashdata('error')) { ?>
            <span class="error"><?php echo $this->session->flashdata('error'); ?></span>
        <?php } ?>
        <div class="row clearfix">
            <!--<pre><?php // print_r($this->session->userdata()); ?></pre>-->
            <!-- <div class="col-lg-12">
                <span id="logoutTimer"></span>
            </div> -->
            <div class="col-lg-5-cols col-sm-6 col-xs-12">
                <div class="boxs top_report_chart l-seagreen">
                    <div class="boxs-body min-height">
                        <h3 class="mt-0"><?php echo $total_submitted; ?></h3>
                        <p>Total Submitted </p>
                    </div>
                </div>
            </div>
            <?php if ($this->session->userdata('is_c&t') == 1) { ?>
                <?php if ($this->session->userdata('is_c&t_module') == 2) { ?>
                    
                    <div class="col-lg-5-cols col-sm-6 col-xs-12">
                        <div class="boxs top_report_chart l-salmon">
                            <div class="boxs-body min-height">
                                <h3 class="mt-0"><?php echo $cos_unpublished_gazettes; ?></h3>
                                <p>Change of Name/Surname Pending </p>
                            </div>
                        </div>
                    </div>
                    <div class="col-lg-5-cols col-sm-6 col-xs-12">
                        <div class="boxs top_report_chart l-blush">
                            <div class="boxs-body min-height">
                                <h3 class="mt-0"><?php echo $cos_published_gazettes; ?></h3>
                                <p>Change of Name/Surname Published</p>
                            </div>
                        </div>
                    </div>
                <?php } if ($this->session->userdata('is_c&t_module') == 1) { ?>
                    
                    <div class="col-lg-5-cols col-sm-6 col-xs-12">
                        <div class="boxs top_report_chart l-salmon">
                            <div class="boxs-body min-height">
                                <h3 class="mt-0"><?php echo $cop_unpublished_gazettes; ?></h3>
                                <p>Change of Partnership Pending </p>
                            </div>
                        </div>
                    </div>
                    <div class="col-lg-5-cols col-sm-6 col-xs-12">
                        <div class="boxs top_report_chart l-parpl">
                            <div class="boxs-body min-height">
                                <h3 class="mt-0"><?php echo $cop_published_gazettes; ?></h3>
                                <p>Change of Partnership Forwarded </p>
                            </div>
                        </div>
                    </div>
                <?php } if ($this->session->userdata('is_c&t_module') == 6) { ?>
                    <div class="col-lg-5-cols col-sm-6 col-xs-12">
                        <div class="boxs top_report_chart l-slategray">
                            <div class="boxs-body min-height">
                                <h3 class="mt-0"><?php echo $cog_unpublished_gazettes; ?></h3>
                                <p>Change of Gender Pending</p>
                            </div>
                        </div>
                    </div>
                    <div class="col-lg-5-cols col-sm-6 col-xs-12">
                        <div class="boxs top_report_chart l-slategray">
                            <div class="boxs-body min-height">
                                <h3 class="mt-0"><?php echo $cog_published_gazettes; ?></h3>
                                <p>Change of Gender Published</p>
                            </div>
                        </div>
                    </div>
                <?php } ?>   
                
                <div class="col-lg-5-cols col-sm-6 col-xs-12">
                    <div class="boxs top_report_chart l-amber">
                        <div class="boxs-body min-height">
                            <h3 class="mt-0"><?php echo $poc_unpublished_gazettes; ?></h3>
                            <p>Payment of Cost <br> Pending </p>
                        </div>
                    </div>
                </div>
                <div class="col-lg-5-cols col-sm-6 col-xs-12">
                    <div class="boxs top_report_chart l-blush">
                        <div class="boxs-body min-height">
                            <h3 class="mt-0"><?php echo $poc_published_gazettes; ?></h3>
                            <p>Payment of Cost Forwarded </p>
                        </div>
                    </div>
                </div>
            
            <?php } else if ($this->session->userdata('is_igr') == 1) { ?>
                
                
                <div class="col-lg-5-cols col-sm-6 col-xs-12">
                    <div class="boxs top_report_chart l-parpl">
                        <div class="boxs-body min-height">
                            <h3 class="mt-0"><?php echo $cop_unpublished_gazettes; ?></h3>
                            <p>Change of Partnership Pending </p>
                        </div>
                    </div>
                </div>
                <div class="col-lg-5-cols col-sm-6 col-xs-12">
                    <div class="boxs top_report_chart l-amber">
                        <div class="boxs-body min-height">
                            <h3 class="mt-0"><?php echo $cop_published_gazettes; ?></h3>
                            <p>Change of Partnership Forwarded </p>
                        </div>
                    </div>
                </div>
            
            <?php } else { ?>
                <div class="col-lg-5-cols col-sm-6 col-xs-12">
                    <div class="boxs top_report_chart l-salmon">
                        <div class="boxs-body min-height">
                            <h3 class="mt-0"><?php echo $cos_unpublished_gazettes; ?></h3>
                            <p>Change of Name/Surname <br/> Pending </p>
                        </div>
                    </div>
                </div>
                <div class="col-lg-5-cols col-sm-6 col-xs-12">
                    <div class="boxs top_report_chart l-parpl">
                        <div class="boxs-body min-height">
                            <h3 class="mt-0"><?php echo $cos_published_gazettes; ?></h3>
                            <p>Change of Name/Surname Published</p>
                        </div>
                    </div>
                </div>
                
                <div class="col-lg-5-cols col-sm-6 col-xs-12">
                    <div class="boxs top_report_chart l-blush">
                        <div class="boxs-body min-height">
                            <h3 class="mt-0"><?php echo $cop_unpublished_gazettes; ?></h3>
                            <p>Change of Partnership Pending </p>
                        </div>
                    </div>
                </div>
                <div class="col-lg-5-cols col-sm-6 col-xs-12">
                    <div class="boxs top_report_chart l-amber">
                        <div class="boxs-body min-height">
                            <h3 class="mt-0"><?php echo $cop_published_gazettes; ?></h3>
                            <p>Change of Partnership Published </p>
                        </div>
                    </div>
                </div>
        </div>    
        <div class="row">    
                <div class="col-lg-5-cols col-sm-6 col-xs-12">
                    <div class="boxs top_report_chart l-slategray">
                        <div class="boxs-body min-height">
                            <h3 class="mt-0"><?php echo $cog_unpublished_gazettes; ?></h3>
                            <p>Change of Gender Pending</p>
                        </div>
                    </div>
                </div>
                <div class="col-lg-5-cols col-sm-6 col-xs-12">
                    <div class="boxs top_report_chart l-slategray">
                        <div class="boxs-body min-height">
                            <h3 class="mt-0"><?php echo $cog_published_gazettes; ?></h3>
                            <p>Change of Gender Published</p>
                        </div>
                    </div>
                </div>
            <?php } ?>
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

