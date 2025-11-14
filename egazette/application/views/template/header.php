<?php defined('BASEPATH') OR exit('No direct script access allowed'); ?>
<!doctype html>
<html class="no-js" lang="en">
    <head>
        <meta charset="utf-8">
		<meta name="twitter:widgets:csp" content="on">
		<!--<meta http-equiv="Content-Security-Policy" content="default-src 'none'; script-src 'self'; connect-src 'self' blob:; img-src 'self' data: blob:; style-src 'self' 'unsafe-inline'; font-src 'self';" />-->
        <title>:: E-Gazette - <?php echo (isset($title)) ? $title: 'Dashboard' ;?> ::</title>
        <link rel="icon" type="image/ico" href="<?php echo base_url(); ?>assets/images/favicon.ico" />
        <meta name="description" content="">
        <meta name="viewport" content="width=device-width, initial-scale=1">
        <!-- vendor css files -->
        <link rel="stylesheet" href="<?php echo base_url(); ?>assets/js/vendor/bootstrap/bootstrap.min.css">    
        <link rel="stylesheet" href="<?php echo base_url(); ?>assets/css/vendor/animsition.min.css">
        <link rel="stylesheet" href="<?php echo base_url(); ?>assets/js/vendor/morris/morris.css">    
        <link rel="stylesheet" href="<?php echo base_url(); ?>assets/js/vendor/datetimepicker/css/bootstrap-datetimepicker.min.css">
<!--        <link rel="stylesheet" href="http://blueimp.github.io/Gallery/css/blueimp-gallery.min.css">-->
        <link rel="stylesheet" href="<?php echo base_url(); ?>assets/js/vendor/file-upload/css/jquery.fileupload.css">
        <link rel="stylesheet" href="<?php echo base_url(); ?>assets/js/vendor/file-upload/css/jquery.fileupload-ui.css">
<!--        <link rel="stylesheet" href="<?php echo base_url(); ?>assets/js/vendor/file-upload/css/jquery.fileupload-ui.css">-->
        <link rel="stylesheet" href="<?php echo base_url(); ?>assets/js/vendor/jquery-ui/jquery-ui.min.css">
        <noscript>
        <link rel="stylesheet" href="<?php echo base_url(); ?>assets/js/vendor/file-upload/css/jquery.fileupload-ui-noscript.css">
        </noscript>
        
        <!-- project main css files -->
        <link rel="stylesheet" href="<?php echo base_url(); ?>assets/css/main.css" nonce="8f0882ce3be14f201cadd0eff5726cbd">
        <link rel="stylesheet" href="<?php echo base_url(); ?>assets/css/style.css" nonce="8f0882ce3be14f201cadd0eff5726cbd">
        
        <style>
            .date_size{
                text-align: right;
                font-size: 12px;
            }
            .media-body{
                padding-right: 0px !important;;
            }
        </style>
    </head>
    <body id="falcon" class="main_Wrapper">
        <div id="wrap" class="animsition">
            <!-- HEADER Content -->
            <div id="header">
                <header class="clearfix">
                    <!-- Branding -->
                    <div class="branding">
                        <a class="brand" href="<?php echo base_url(); ?>dashboard">
                            <span><img class="inner_logo" src="<?php echo base_url(); ?>assets/images/logo1.png" width="150" height="100"/></span>
                        </a>
                        <a role="button" tabindex="0" class="offcanvas-toggle visible-xs-inline">
                            <i class="fa fa-bars"></i>
                        </a>
                    </div>
                    <!-- Branding end -->

                    <!-- Left-side navigation -->
                    <ul class="nav-left pull-left list-unstyled list-inline">
                        <li class="leftmenu-collapse">
                            <a role="button" tabindex="0" class="collapse-leftmenu">
                                <i class="fa fa-outdent"></i>
                            </a>
                        </li>
                    </ul>
                    <?php 
                        $CI =& get_instance();
                        $CI->load->model(array('notification_model', 'user_model'));
                        $CI->load->helper(array('custom', 'text'));
                    ?>
                    <!-- Left-side navigation end -->
                    <div class="department_header_text"><?php $result_user = $CI->user_model->get_dept_name($this->session->userdata('user_id'));echo ($this->session->userdata('is_admin')) ? "Directorate of Printing, Stationery & Publication" : $result_user->department_name; ?></div>
                    <!-- Right-side navigation -->
                    <ul class="nav-right pull-right list-inline">
                        <?php 
                            if ($this->session->userdata('is_admin')) {
                                $notifications = $CI->notification_model->get_admin_notifications_latest($this->session->userdata('user_id'));
                            } else {
                                $notifications = $CI->notification_model->get_user_notifications_latest($this->session->userdata('user_id'));
                            }
                        ?>
						<li class="manual">
							<?php if ($this->session->userdata('user_id') && !$this->session->userdata('is_admin')) { ?>
								<a href="<?php echo base_url(); ?>manual/public/dept_manual.pdf" target="_blank">User Manual</a>
							<?php } ?>
                            <?php if ($this->session->userdata('user_id') && $this->session->userdata('is_admin')) { ?>
                                <a href="<?php echo base_url(); ?>manual/public/govt_press_manual.pdf" target="_blank">User Manual</a>
                            <?php } ?>
						</li>
                        <li class="dropdown notifications">
                            <a href="javascript:void(0);" class="dropdown-toggle" data-toggle="dropdown">
                                <i class="fa fa-bell"></i>
                                <?php if (!empty($notifications)) { ?>
                                    <div class="notify">
                                        <span class="heartbit"></span>
                                        <span class="point"></span>
                                    </div>
                                <?php } ?>
                            </a>
                            <div class="dropdown-menu pull-right panel panel-default">
                                <?php if (!empty($notifications)) { ?>
                                    <ul class="list-group">
                                    <?php //var_dump($notifications); exit; ?>
                                        <?php foreach ($notifications as $notif) { ?>
                                            
                                            <li class="list-group-item">
                                                <?php if (!$this->session->userdata('is_admin')) { ?>
                                                    <?php if($notif->gazette_type == "1"){?>
                                                        
                                                        <?php if($notif->is_paid == "0"){?>
                                                            <a href="<?php echo base_url() . 'gazette/dept_view/' . $notif->gazette_id.'/'. $this->session->userdata('user_id')?>" role="button" tabindex="0" class="media notif_click" id="<?php echo $notif->id; ?>">
                                                        <?php } else {?>
                                                            <a href="<?php echo base_url() . 'gazette/dept_view_paid/' . $notif->gazette_id.'/'. $this->session->userdata('user_id')?>" role="button" tabindex="0" class="media notif_click" id="<?php echo $notif->id; ?>">
                                                        <?php } ?>
                                                    <?php } else if($notif->gazette_type == "2"){?>
                                                        <a href="<?php echo base_url() . 'weekly_gazette/dept_view/' . $notif->gazette_id.'/'. $this->session->userdata('user_id')?>" role="button" tabindex="0" class="media notif_click_weekly" id="<?php echo $notif->id; ?>">
                                                    <?php }?>
                                                        <input type="hidden" name="gazette_id" class="gazetee_id_val" id="<?php echo $this->session->userdata('user_id')?>" value="<?php echo $notif->gazette_id; ?>"/>
                                                        <div class="media-body">
                                                            <!-- <span class="block"><?php //echo character_limiter($notif->text, 20); ?></span> -->
                                                            <div class="col-lg-12 col-md-12 col-sm-12 col-xs-12 date_size">
                                                                <small class="text-muted"><?php echo get_formatted_datetime($notif->created_at); ?></small>
                                                            </div>
                                                            <div class="col-lg-12 col-md-12 col-sm-12 col-xs-12">
                                                                <span class="block"><?php echo $notif->text; ?></span>
                                                            </div>
                                                        </div>
                                                    </a>
                                                <?php } else { ?>
                                                    <?php if($notif->gazette_type == "1"){?>
                                                        <?php if($notif->is_paid == "0"){?>
                                                            <a href="<?php echo base_url() . 'gazette/press_view/' . $notif->gazette_id; ?>" role="button" tabindex="0" class="media notif_click" id="<?php echo $notif->id; ?>">
                                                        <?php }else{?>
                                                            <a href="<?php echo base_url() . 'gazette/press_view_paid/' . $notif->gazette_id; ?>" role="button" tabindex="0" class="media notif_click" id="<?php echo $notif->id; ?>">
                                                        <?php } ?>
                                                    <?php } else if($notif->gazette_type == "2"){?>  
                                                        <a href="<?php echo base_url() . 'weekly_gazette/press_view/' . $notif->gazette_id; ?>" role="button" tabindex="0" class="media notif_click_weekly" id="<?php echo $notif->id; ?>">
                                                    <?php } else if($notif->gazette_type == "3"){?>
                                                        <?php if($notif->module_id == "2"){?>
                                                        <a href="<?php echo base_url(); ?>applicants_login/view_details_change_name_govt/<?php echo $notif->gazette_id; ?>"  class="change_name_surname_admin" id="<?php echo $notif->id?>">
                                                    <?php } else if($notif->module_id == "1" ){  ?>
                                                        <?php if($notif->is_paid == "0") {?>
                                                            <a href="<?php echo base_url(); ?>applicants_login/view_details_par_gove/<?php echo $notif->gazette_id; ?>"  class="change_partnership_admin" id="<?php echo $notif->id?>">
                                                        <?php }else{?>
                                                            <a href="<?php echo base_url(); ?>applicants_login/view_details_par_gove/<?php echo $notif->gazette_id; ?>"  class="change_partnership_admin" id="<?php echo $notif->id?>">
                                                    <?php } } }?>
                                                        <input type="hidden" name="gazette_id" class="gazetee_id_val" id="<?php echo $this->session->userdata('user_id')?>" value="<?php echo $notif->gazette_id; ?>"/>
                                                        <div class="media-body">
                                                            <div class="col-lg-12 col-md-12 col-sm-12 col-xs-12 date_size">
                                                                <small class="text-muted"><?php echo get_formatted_datetime($notif->created_at); ?></small>
                                                            </div>
                                                            <div class="col-lg-12 col-md-12 col-sm-12 col-xs-12">
                                                                <span class="block"><?php echo $notif->text; ?></span>
                                                            </div>
                                                        </div>
                                                    </a>
                                                <?php } ?>
                                            </li>
                                        <?php } ?>
                                    </ul>
                                    <div class="panel-footer">
                                        <a href="<?php echo base_url(); ?>notification/index" role="button" tabindex="0">Show all notifications
                                            <i class="fa fa-angle-right pull-right"></i>
                                        </a>
                                    </div>
                                <?php } else { ?>
                                    <div class="panel-footer">
                                        <a role="button" tabindex="0">No Notifications
                                        </a>
                                    </div>
                                <?php } ?>
                            </div>
                        </li>
                        <li class="dropdown nav-profile">
                            <a href="javascript:void(0);" class="dropdown-toggle" data-toggle="dropdown">
                                <img src="<?php echo base_url(); ?>assets/images/user-profile.png" alt="" class="0 size-30x30"> </a>
                            <ul class="dropdown-menu pull-right" role="menu">
                                <li>
                                    <div class="user-info">
                                        <div class="user-name"><?php echo ($this->session->userdata('logged_in')) ? $this->session->userdata('user_name') : "Administrator"; ?></div>
                                    </div>
                                </li>
                                <li>
                                    <a href="<?php echo base_url(); ?>" role="button" tabindex="0" target="_blank">
                                        <i class="fa fa-globe"></i>Visit Website</a>
                                </li>
                                <li>
                                    <a href="<?php echo base_url(); ?>user/profile" role="button" tabindex="0">
                                        <i class="fa fa-user"></i>Profile</a>
                                </li>
                                <li>
                                    <a href="<?php echo base_url(); ?>user/change_password" role="button" tabindex="0">
                                        <i class="fa fa-lock"></i>Change Password</a>
                                </li>
                                <li class="divider"></li>
                                <li>
                                    <a href="<?php echo base_url(); ?>user/logout" role="button" tabindex="0">
                                        <i class="fa fa-sign-out"></i>Logout</a>
                                </li>
                            </ul>
                        </li>
                    </ul>
                    <!-- Right-side navigation end -->
                </header>
            </div>
            <!--/ HEADER Content  -->