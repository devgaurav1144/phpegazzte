<style nonce="8f0882ce3be14f201cadd0eff5726cbd">
    #navigation li ul a i {
    position: initial;
    display: inline-block;
}
#navigation .dropdown>ul li:last-child>a {
    display: flex;
}
</style>
<div id="controls">
    <aside id="leftmenu">
        <div id="leftmenu-wrap">
            <div class="panel-group slim-scroll" role="tablist">
                <div class="panel panel-default">
                    <div id="leftmenuNav" class="panel-collapse collapse in" role="tabpanel">
                        <div class="panel-body">
                            <!--  NAVIGATION Content -->
                            <ul id="navigation">
                                <?php 
                                    if ($this->uri->segment(1) == "dashboard") { 
                                        $class = "active open";
                                    } else {
                                        $class = "";
                                    }
                                ?>
                                <li class="<?php echo $class; ?>">
                                    <a href="<?php echo base_url(); ?>dashboard">
                                        <i class="fa fa-dashboard"></i>
                                        <span>Dashboard</span>
                                    </a>
                                </li>
                                <?php if ($this->session->userdata('is_admin')) { ?>
                                <?php 
                                    if (($this->uri->segment(1) == "department") ||
                                        ($this->uri->segment(1) == "user") ||
                                        ($this->uri->segment(1) == "block") ||
                                        ($this->uri->segment(1) == "ulb") ||
                                        ($this->uri->segment(1) == "police_station") ||
                                        ($this->uri->segment(1) == "gazette_type") ||
                                        ($this->uri->segment(1) == "gazette_publish") ||
                                        ($this->uri->segment(1) == "designation") || 
                                        ($this->uri->segment(1) == "module_wise_pricing")|| 
                                        ($this->uri->segment(1) == "gazette_hod") || 
                                        ($this->uri->segment(1) == "igr_user")|| 
                                        ($this->uri->segment(1) == "commerce_transport_department") || 
                                        ($this->uri->segment(1) == "district"))  
                                    { 
                                        $class = "dropdown open active";
                                    } else {
                                        $class = "";
                                    }
                                ?>
                                <li class="<?php echo $class; ?>">
                                    <a role="button" tabindex="0">
                                        <i class="fa fa-tags"></i>
                                        <span>Master Menu</span>
                                    </a>
                                    <ul>
                                        <li <?php echo ($this->uri->segment(1) == "department") ? 'class="active"' : ''; ?>><a href="<?php echo base_url(); ?>department"><i class="fa fa-angle-right"></i> Departments</a>
                                        </li>
                                        <li <?php echo ($this->uri->segment(1) == "user") ? 'class="active"' : ''; ?>><a href="<?php echo base_url(); ?>user/users"><i class="fa fa-angle-right"></i> Nodal Officers</a>
                                        </li>
                                        <li <?php echo ($this->uri->segment(1) == "designation") ? 'class="active"' : ''; ?>><a href="<?php echo base_url(); ?>designation"><i class="fa fa-angle-right"></i> Designations</a>
                                        </li>
                                        <li <?php echo ($this->uri->segment(1) == "district") ? 'class="active"' : ''; ?>><a href="<?php echo base_url(); ?>district"><i class="fa fa-angle-right"></i> Districts</a>
                                        </li>
                                        <li <?php echo ($this->uri->segment(1) == "block") ? 'class="active"' : ''; ?>><a href="<?php echo base_url(); ?>block"><i class="fa fa-angle-right"></i> Blocks</a>
                                        </li>
                                        <li <?php echo ($this->uri->segment(1) == "ulb") ? 'class="active"' : ''; ?>><a href="<?php echo base_url(); ?>ulb"><i class="fa fa-angle-right"></i> Urban Local Body</a>
                                        </li>
                                        <li <?php echo ($this->uri->segment(1) == "police_station") ? 'class="active"' : ''; ?>><a href="<?php echo base_url(); ?>police_station"><i class="fa fa-angle-right"></i> Police Station</a>
                                        </li>
                                        <!--<li <?php echo ($this->uri->segment(1) == "gazette_type") ? 'class="active"' : ''; ?>><a href="<?php echo base_url(); ?>gazette_type"><i class="fa fa-angle-right"></i> Gazette Type</a>
                                        </li>-->
                                        <!--<li <?php echo ($this->uri->segment(1) == "gazette_publish") ? 'class="active"' : ''; ?>><a href="<?php echo base_url(); ?>gazette_publish"><i class="fa fa-angle-right"></i> Weekly Publish Date</a>
                                        </li>-->
                                        <!--<li <?php echo ($this->uri->segment(1) == "module_wise_pricing") ? 'class="active"' : ''; ?>><a href="<?php echo base_url(); ?>module_wise_pricing"><i class="fa fa-angle-right"></i> Module Wise Pricing</a>
                                        </li>-->
                                        <!--<li <?php echo ($this->uri->segment(1) == "gazette_hod") ? 'class="active"' : ''; ?>><a href="<?php echo base_url(); ?>gazette_hod"><i class="fa fa-angle-right"></i> HOD </a>
                                        </li>-->
                                        <li <?php echo ($this->uri->segment(1) == "igr_user") ? 'class="active"' : ''; ?>><a href="<?php echo base_url(); ?>igr_user"><i class="fa fa-angle-right"></i> IGR Users </a>
                                        </li>
										
										<li <?php echo ($this->uri->segment(1) == "offline_payment_users") ? 'class="active"' : ''; ?>><a href="<?php echo base_url(); ?>offline_payment_users"><i class="fa fa-angle-right"></i> Offline Approver </a>
                                        </li>
										
                                            <li <?php echo ($this->uri->segment(1) == "commerce_transport_department") ? 'class="active"' : ''; ?>><a href="<?php echo base_url(); ?>commerce_transport_department"><i class="fa fa-angle-right"></i> C&T Users</a>
                                        </li>
                                    </ul>
                                </li>
                                <?php } ?>
                                <?php 
                                    if (($this->uri->segment(1) == "gazette") || ($this->uri->segment(1) == "weekly_gazette")) { 
                                        $class = "dropdown open active";
                                    } else {
                                        $class = "";
                                    }
                                ?>
                                <li class="<?php echo $class; ?>">
                                    <a role="button" tabindex="0">
                                        <i class="fa fa-list"></i>
                                        <span>Gazette</span>
                                    </a>
                                    <ul>
                                        <li <?php echo ($this->uri->segment(1) == "gazette") ? 'class="active"' : ''; ?>>
                                            <a href="<?php echo base_url(); ?>gazette"><i class="fa fa-angle-right"></i>Extraordinary Gazette</a>
                                            <ul>

                                                <?php if (!$this->session->userdata('is_admin')) { ?>
                                                    <?php
                                                        if($this->uri->segment(1) == "gazette" && $this->uri->segment(2) == "add"){
                                                            $class = "active";
                                                        }else{
                                                            $class = "";
                                                        }
                                                     ?>
                                                    <li class="<?php echo $class; ?>" <?php //echo ($this->uri->segment(1) == "gazette/add") ? 'class="active"' : ''; ?>>
                                                        <a href="<?php echo base_url(); ?>gazette/add"><i class="fa fa-angle-right"></i> Add Gazette</a>
                                                    </li>
                                                <?php }?>
                                                <?php
                                                    if($this->uri->segment(1) == "gazette" && empty($this->uri->segment(2))){
                                                        $class = "active";
                                                    }else{
                                                        $class = "";
                                                    }
                                                ?>
                                                <li class="<?php echo $class; ?>" <?php //echo ($this->uri->segment(1) == "gazette") ? 'class="active"' : ''; ?>>
                                                    <a href="<?php echo base_url(); ?>gazette"><i class="fa fa-angle-right"></i> Pending</a>
                                                </li>
                                                <?php
                                                        if($this->uri->segment(1) == "gazette" && $this->uri->segment(2) == "ex_published_gazette"){
                                                            $class = "active";
                                                        }else{
                                                            $class = "";
                                                        }
                                                     ?>
                                                <li class="<?php echo $class; ?>" <?php //echo ($this->uri->segment(1) == "gazette/ex_published_gazette") ? 'class="active"' : ''; ?>>
                                                    <a href="<?php echo base_url(); ?>gazette/ex_published_gazette"><i class="fa fa-angle-right"></i> Published</a>
                                                </li>

                                                
                                            </ul>
                                        </li>
                                        <li <?php echo ($this->uri->segment(1) == "weekly_gazette") ? 'class="active"' : ''; ?>>
                                            <a href="<?php echo base_url(); ?>weekly_gazette/index"><i class="fa fa-angle-right"></i>Weekly Gazette</a>
                                            <ul>

                                                <?php if (!$this->session->userdata('is_admin')) { ?>

                                                    <li <?php echo ($this->uri->segment(1) == "weekly_gazette" && $this->uri->segment(2) == "add") ? 'class="active"' : ''; ?>>
                                                        <a href="<?php echo base_url(); ?>weekly_gazette/add"><i class="fa fa-angle-right"></i> Add Gazette</a>
                                                    </li>
                                                <?php }?>

                                                <li <?php echo ($this->uri->segment(1) == "weekly_gazette" && $this->uri->segment(2) == "index") ? 'class="active"' : ''; ?>>
                                                    <a href="<?php echo base_url(); ?>weekly_gazette/index"><i class="fa fa-angle-right"></i> Pending</a>
                                                </li>

                                                <?php if ($this->session->userdata('is_admin')) { ?>

                                                    <li <?php echo ($this->uri->segment(1) == "weekly_gazette" && $this->uri->segment(2) == "approved_wk_gz") ? 'class="active"' : ''; ?>>
                                                    <a href="<?php echo base_url(); ?>weekly_gazette/approved_wk_gz"><i class="fa fa-angle-right"></i> Approved</a>
                                                    </li>
                                                    <li <?php echo ($this->uri->segment(1) == "weekly_gazette" && $this->uri->segment(2) == "merged_wk_gz") ? 'class="active"' : ''; ?>>
                                                    <a href="<?php echo base_url(); ?>weekly_gazette/merged_wk_gz"><i class="fa fa-angle-right"></i> Merged</a>
                                                    </li>

                                                <?php }else{ ?>

                                                    <li <?php echo ($this->uri->segment(1) == "weekly_gazette" && $this->uri->segment(2) == "approved_wk_gz") ? 'class="active"' : ''; ?>>
                                                    <a href="<?php echo base_url(); ?>weekly_gazette/approved_wk_gz"><i class="fa fa-angle-right"></i> Approved</a>
                                                    </li>
                                                    <?php } ?>

                                                <li <?php echo ($this->uri->segment(1) == "weekly_gazette" && $this->uri->segment(2) == "published_wk_gz") ? 'class="active"' : ''; ?>>
                                                    <a href="<?php echo base_url(); ?>weekly_gazette/published_wk_gz"><i class="fa fa-angle-right"></i> Published</a>
                                                </li>

                                                
                                            </ul>
                                        </li>
                                    </ul>
                                </li>
                                
                                <?php if ($this->session->userdata('is_admin') || $this->session->userdata('is_applicant')) { ?>
                                    <?php
                                        if($this->uri->segment(1) == "applicants_login" && 
                                            $this->uri->segment(2) == "change_of_name_surname_govt_list" ||
                                            $this->uri->segment(2) == "paid_change_of_name_surname_govt_list" ||
                                            $this->uri->segment(2) == "published_change_of_name_surname_govt_list" ||
                                            $this->uri->segment(2) == "partnership_change_list_govt" ||
                                            $this->uri->segment(2) == "paid_partnership_change_list_govt" ||
                                            $this->uri->segment(2) == "published_partnership_change_list_govt" ||
                                            $this->uri->segment(2) == "view_details_par_gove" ||
                                            $this->uri->segment(2) == "view_details_change_name_govt" ){
                                            $class = "active";
                                        }else{
                                            $class = "";
                                        }
                                    ?>

                                    <li class="<?php echo $class; ?>">
                                        <a role="button" tabindex="0">
                                            <i class="fa fa-list"></i>
                                            <span>Online Service</span>
                                        </a>
                                        <ul>
                                            <!-- Change of Name Surname Start -->
                                                <li <?php echo ($this->uri->segment(2) == "change_of_name_surname_govt_list" || $this->uri->segment(2) == "paid_change_of_name_surname_govt_list" || $this->uri->segment(2) == "published_change_of_name_surname_govt_list" || $this->uri->segment(2) == "view_details_change_name_govt") ? 'class="active"' : ''; ?>>
                                                    <a role="button"><i class="fa fa-angle-right"></i>Change of Name/Surname</a>
                                                    <ul>
                                                        <li <?php echo ($this->uri->segment(2) == "change_of_name_surname_govt_list" || $this->uri->segment(2) == 'view_details_change_name_govt') ? 'class="active"' : ''; ?> >
                                                            <a role="button" tabindex="0" href="<?php echo base_url(); ?>applicants_login/change_of_name_surname_govt_list"><i class="fa fa-angle-right"></i>Pending</a>
                                                        </li>
                                                        <li <?php echo ($this->uri->segment(2) == "paid_change_of_name_surname_govt_list") ? 'class="active"' : ''; ?>>
                                                            <a role="button" tabindex="0" href="<?php echo base_url(); ?>applicants_login/paid_change_of_name_surname_govt_list"><i class="fa fa-angle-right"></i>Paid</a>
                                                        </li>
                                                        <li <?php echo ($this->uri->segment(2) == "published_change_of_name_surname_govt_list") ? 'class="active"' : ''; ?>>
                                                            <a role="button" tabindex="0" href="<?php echo base_url(); ?>applicants_login/published_change_of_name_surname_govt_list"><i class="fa fa-angle-right"></i>Published</a>
                                                        </li>
                                                    </ul>
                                                </li>
                                            <!-- Change of Name Surname End -->

                                            <!-- Change of Partnership Start -->
                                                <li <?php echo ($this->uri->segment(2) == "partnership_change_list_govt" || $this->uri->segment(2) == "paid_partnership_change_list_govt" || $this->uri->segment(2) == "published_partnership_change_list_govt" || $this->uri->segment(2) == "view_details_par_gove") ? 'class="active"' : ''; ?> >
                                                    <a role="button" tabindex="0"><i class="fa fa-angle-right"></i>Change of <br>Partnership</a>
                                                    <ul>
                                                        <li <?php echo ($this->uri->segment(2) == "partnership_change_list_govt" || $this->uri->segment(2) == 'view_details_par_gove') ? 'class="active"' : ''; ?> >
                                                            <a role="button" tabindex="0" href="<?php echo base_url(); ?>applicants_login/partnership_change_list_govt"><i class="fa fa-angle-right"></i>Pending</a>
                                                        </li>
                                                        <li <?php echo ($this->uri->segment(2) == "paid_partnership_change_list_govt") ? 'class="active"' : ''; ?>>
                                                            <a role="button" tabindex="0" href="<?php echo base_url(); ?>applicants_login/paid_partnership_change_list_govt"><i class="fa fa-angle-right"></i>Paid</a>
                                                        </li>
                                                        <li <?php echo ($this->uri->segment(2) == "published_partnership_change_list_govt") ? 'class="active"' : ''; ?>>
                                                            <a role="button" tabindex="0" href="<?php echo base_url(); ?>applicants_login/published_partnership_change_list_govt"><i class="fa fa-angle-right"></i>Published</a>
                                                        </li>
                                                    </ul>
                                                </li>
                                            <!-- Change of Partnership End -->

                                            <!-- Change of Gender Start -->
                                            <?php //if ($this->session->userdata('user_id') == 57): ?>
                                            <li <?php echo ($this->uri->segment(2) == "change_of_gender_govt_list" || $this->uri->segment(2) == "paid_change_of_gender_govt_list" || $this->uri->segment(2) == "published_change_of_gender_govt_list" || $this->uri->segment(2) == "view_details_change_gender_govt") ? 'class="active"' : ''; ?>>
                                                <a role="button"><i class="fa fa-angle-right"></i>Change of Gender</a>
                                                <ul>
                                                    <li <?php echo ($this->uri->segment(2) == "change_of_gender_govt_list" || $this->uri->segment(2) == 'view_details_change_gender_govt') ? 'class="active"' : ''; ?> >
                                                        <a role="button" tabindex="0" href="<?php echo base_url(); ?>applicants_login/change_of_gender_govt_list"><i class="fa fa-angle-right"></i>Pending</a>
                                                    </li>
                                                    <li <?php echo ($this->uri->segment(2) == "paid_change_of_gender_govt_list") ? 'class="active"' : ''; ?>>
                                                        <a role="button" tabindex="0" href="<?php echo base_url(); ?>applicants_login/paid_change_of_gender_govt_list"><i class="fa fa-angle-right"></i>Paid</a>
                                                    </li>
                                                    <li <?php echo ($this->uri->segment(2) == "published_change_of_gender_govt_list") ? 'class="active"' : ''; ?>>
                                                        <a role="button" tabindex="0" href="<?php echo base_url(); ?>applicants_login/published_change_of_gender_govt_list"><i class="fa fa-angle-right"></i>Published</a>
                                                    </li>
                                                </ul>
                                            </li>
                                            <?php // endif; ?>
                                            <!-- Change of Gender End -->
                                        </ul>
                                    </li>

                                <?php } ?>
                                


                                <?php if ($this->session->userdata('is_admin')) { ?>
                                <?php 
                                    if (($this->uri->segment(1) == "Cms_System" || $this->uri->segment(1) == "feedback")) { 
                                        $class = "dropdown open active";
                                    } else {
                                        $class = "";
                                    }
                                ?>
                                <li class="<?php echo $class; ?>">
                                    <a role="button" tabindex="0">
                                        <i class="fa fa-pie-chart"></i>
                                        <span>CMS</span>
                                    </a>
                                    <ul <?php echo ($this->uri->segment(1) == "Cms_System") ? 'style="display: block"' : ''; ?>>
                                        <li <?php echo ($this->uri->segment(2) == "about_us_content") ? 'class="active"' : ''; ?>>
                                            <a href="<?php echo base_url(); ?>Cms_System/about_us_content">
                                                <i class="fa fa-angle-right"></i> About Us</a>
                                        </li>
                                        <li <?php echo ($this->uri->segment(2) == "about_gazette") ? 'class="active"' : ''; ?>>
                                            <a href="<?php echo base_url(); ?>Cms_System/about_gazette">
                                                <i class="fa fa-angle-right"></i> About Gazette</a>
                                        </li>
                                        <li <?php echo ($this->uri->segment(2) == "disclaimer") ? 'class="active"' : ''; ?>>
                                            <a href="<?php echo base_url(); ?>Cms_System/disclaimer">
                                                <i class="fa fa-angle-right"></i> Disclaimer</a>
                                        </li>
                                        <li <?php echo ($this->uri->segment(2) == "acknowledgement") ? 'class="active"' : ''; ?>>
                                            <a href="<?php echo base_url('Cms_System/acknowledgement'); ?>">
                                                <i class="fa fa-angle-right"></i> Acknowledgement</a>
                                        </li>
                                        <li <?php echo ($this->uri->segment(1) == "feedback") ? 'class="active"' : ''; ?>>
                                            <a href="<?php echo base_url('feedback'); ?>">
                                                <i class="fa fa-angle-right"></i> Feedback</a>
                                        </li>
                                    </ul>
                                </li>
                                <?php } ?>
								
								<?php if ($this->session->userdata('is_admin')) { ?>
                                    <?php 
                                        if ($this->uri->segment(1) == "archival" || $this->uri->segment(2) == "extraordinary_view" || $this->uri->segment(2) == "edit_extraordinary_view" || $this->uri->segment(2) == "weekly_view" || $this->uri->segment(2) == "edit_weekly_view") { 
                                            $class = "dropdown open active";
                                        } else {
                                            $class = "";
                                        }
                                    ?>
                                    <li class="<?php echo $class; ?>">
                                        <a role="button" tabindex="0">
                                            <i class="fa fa fa-list"></i>
                                            <span>Archival</span>
                                        </a>
                                        
                                        <ul>
                                            <li <?php echo ($this->uri->segment(2) == "extraordinary" || $this->uri->segment(2) == "extraordinary_view" || $this->uri->segment(2) == "edit_extraordinary_view") ? 'class="active"' : ''; ?>>
                                                <a href="<?php echo base_url(); ?>archival/extraordinary"><i class="fa fa-angle-right"></i>Extraordinary Gazette</a>
                                            </li>
                                            <li <?php echo ($this->uri->segment(2) == "weekly" || $this->uri->segment(2) == "weekly_view" || $this->uri->segment(2) == "edit_weekly_view") ? 'class="active"' : ''; ?>>
                                                <a href="<?php echo base_url(); ?>archival/weekly"><i class="fa fa-angle-right"></i>Weekly Gazette</a>
                                            </li>
                                        </ul>
                                    </li>
                                <?php } ?>
								
                                <?php if ($this->session->userdata('is_admin')) { ?>
                                    <?php 
                                        if ($this->uri->segment(1) == "Reports" && $this->uri->segment(2) == "extraordinary_report" || $this->uri->segment(2) == "weekly_report_result" ) { 
                                            $class = "dropdown active";
                                        } else {
                                            $class = "";
                                        }
                                    ?>
                                    <li class="<?php echo $class; ?>">
                                        <a role="button" tabindex="0">
                                            <i class="fa fa-bar-chart-o"></i>
                                            <span>Reports</span>
                                        </a>
                                        <ul <?php echo ($this->uri->segment(1) == "Reports") ? 'style="display: block"' : ''; ?>>
                                            <li <?php echo ($this->uri->segment(2) == "extraordinary_report" || $this->uri->segment(2) == "extraordinary_report_result") ? 'class="active"' : ''; ?>>
                                                <a href="<?php echo base_url(); ?>Reports/extraordinary_report"><i class="fa fa-angle-right"></i>Extraordinary Report</a>
                                            </li>
                                            <li <?php echo ($this->uri->segment(2) == "weekly_report_result") ? 'class="active"' : ''; ?>>
                                                <a href="<?php echo base_url(); ?>Reports/weekly_report_result"><i class="fa fa-angle-right"></i>Weekly Report</a>
                                            </li>
                                        </ul>
                                    </li>
                                <?php } ?>
                                <?php if ($this->session->userdata('is_admin')) { ?>
                                <?php 
                                    if (($this->uri->segment(1) == "settings") || ($this->uri->segment(1) == "activity_log")) { 
                                        $class = "dropdown open active";
                                    } else {
                                        $class = "";
                                    }
                                ?>
                                <li class="<?php echo $class; ?>">
                                    <a role="button" tabindex="0">
                                        <i class="fa fa-cog fw"></i>
                                        <span>Activity Log</span>
                                    </a>
                                    <ul>
                                        <!-- <li <?php echo ($this->uri->segment(2) == "smtp") ? 'class="active"' : ''; ?>>
                                            <a href="<?php echo base_url(); ?>settings/smtp">
                                                <i class="fa fa-angle-right"></i> SMTP</a>
                                        </li>
                                        <li <?php echo ($this->uri->segment(2) == "sms_gateway") ? 'class="active"' : ''; ?>>
                                            <a href="<?php echo base_url(); ?>settings/sms_gateway">
                                                <i class="fa fa-angle-right"></i> SMS</a>
                                        </li>
                                        <li <?php echo ($this->uri->segment(2) == "payment_gateway") ? 'class="active"' : ''; ?>>
                                            <a href="<?php echo base_url(); ?>settings/payment_gateway">
                                                <i class="fa fa-angle-right"></i> Payment Gateway</a>
                                        </li> -->
                                        <li <?php echo ($this->uri->segment(1) == "activity_log") ? 'class="active"' : ''; ?>>
                                            <a href="<?php echo base_url(); ?>activity_log">
                                                <i class="fa fa-angle-right"></i> Activity Log</a>
                                        </li>
                                    </ul>
                                </li>
                                <?php } ?>
                            </ul>
                            <!--/ NAVIGATION Content -->
                        </div>
                    </div>
                </div>
            </div>
        </div>
    </aside>
</div>
