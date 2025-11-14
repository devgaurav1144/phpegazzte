<?php include_once 'page_initialization.php'; ?>
<!DOCTYPE html>
<html  class="no-js">
    <head>
        <meta charset="utf-8">
        <meta http-equiv="X-UA-Compatible" content="IE=edge">
        <meta name="viewport" content="width=device-width, initial-scale=1">
        <title>Feedback | eGazette | Government of (StateName)</title>
        <meta charset="UTF-8">
        <meta name="viewport" content="initial-scale=1.0, maximum-scale=1.0, user-scalable=no" />
        <?php include_once 'website/include/header-scripts-style.php'; ?>
        <link nonce="8f0882ce3be14f201cadd0eff5726cbd" type="text/css" rel="Stylesheet" href="<?php echo CaptchaUrls::LayoutStylesheetUrl(); ?>" />
        <style nonce="8f0882ce3be14f201cadd0eff5726cbd">
            .BDC_CaptchaDiv {
                overflow: visible !important;
                margin: 13px auto 5px !important;
            }
            .BDC_CaptchaIconsDiv {
                position: absolute;
                top: 22px;
                margin-left: 14px !important;
                right: -80px;
            }
			textarea {
                resize: none;
            }
            .mandatory {
                color: red;
            }

        </style>
    </head>
    <body class="home-body">
        <?php include_once 'website/include/header-menu.php'; ?>
        <section class="inner-banner">
            <div class="container">
                <h1>Feedback</h1>
            </div>
        </section>
        <section class="breadcumb-wrapper" id="skip-to-main">
            <div class="container">
                <div class="row">
                    <div class="col-lg-12">
                        <div class="breadcumb">
                            <ul>
                                <li><a href="<?php echo base_url(); ?>" rel="noopener noreferrer">Home</a><span> &gt;</span></li>
                                <li>Feedback</li>
                            </ul>
                        </div>
                    </div>
                </div>
            </div>
        </section>
        <section class="main-container">
            <div class="container">
                <div class="row">
                    <!--Right Sidebar-1 Start-->
                    <div class="col-lg-9 col-md-9 col-sm-12 col-xs-12 e-margin fadeInBottom">
                        <?php if ($this->session->flashdata('success')) { ?>
                            <div class="alert alert-success alert-dismissable"><button aria-hidden="true" data-dismiss="alert" class="close" type="button">×</button><?php echo $this->session->flashdata('success'); ?></div>
                        <?php } ?>
                        <?php if ($this->session->flashdata('error')) { ?>
                            <div class="alert alert-danger alert-dismissable"><button aria-hidden="true" data-dismiss="alert" class="close" type="button">×</button><?php echo $this->session->flashdata('error'); ?></div>
                        <?php } ?>
                        <div class="infographic-section media-section">
                        <!-- <h3 class="head-section pink">Feedback</h3> -->
                            
                            <div class="content-part">
                                <div class="row">
                                    <?php echo form_open('website/submit_feedback', array('class' => 'row', 'method' => 'post', 'id' => 'feedback_frm')); ?>
                                        <div class="col-lg-6 col-md-6 col-sm-6 col-xs-12 marg">
                                            <div class="search-gazette">
                                                <div class="form-group">
                                                    <label class="margin-bottom">Name <span class="mandatory">*</span></label>
                                                    <input type="text" placeholder="Name" class="form-control" name="name" id="name" autocomplete="off" value="<?php echo set_value('name'); ?>" maxlength="30">
                                                    <?php if (form_error('name')) { ?>
                                                        <span class="error"><?php echo form_error('name'); ?></span>
                                                    <?php } ?>
                                                </div>
                                            </div>
                                        </div>
                                        <div class="col-lg-6 col-md-6 col-sm-6 col-xs-12 marg">
                                            <div class="search-gazette">
                                                <div class="form-group">
                                                    <label class="margin-bottom">Email <span class="mandatory">*</span></label>
                                                    <input type="text" placeholder="Email" class="form-control" name="email" id="email" autocomplete="off" value="<?php echo set_value('email'); ?>" maxlength="96">
                                                    <?php if (form_error('email')) { ?>
                                                        <span class="error"><?php echo form_error('email'); ?></span>
                                                    <?php } ?>
                                                </div>
                                            </div>
                                        </div>
                                        <div class="col-lg-6 col-md-6 col-sm-6 col-xs-12 marg" id="departmentID">
                                            <div class="search-gazette">
                                                <div class="form-group">
                                                    <label class="margin-bottom">Mobile No <span class="mandatory">*</span></label>
                                                    <input type="text" placeholder="Mobile No" class="form-control" name="mobile" id="mobile" autocomplete="off" value="<?php echo set_value('mobile'); ?>" maxlength="10">
                                                    <?php if (form_error('mobile')) { ?>
                                                        <span class="error"><?php echo form_error('mobile'); ?></span>
                                                    <?php } ?>
                                                </div>
                                            </div>
                                        </div>
                                        <div class="col-lg-6 col-md-6 col-sm-6 col-xs-12 marg" id="notificationID">
                                            <div class="search-gazette">
                                                <!--  <form> -->
                                                <div class="form-group">
                                                    <label class="margin-bottom" for="usr">Occupation <span class="mandatory">*</span></label>
                                                    <select class="form-control" name="occupation" id="occupation" autocomplete="off">
                                                        <option value="">Select Occupation</option>
                                                        <option value="Students" <?php echo set_select('occupation', 'Students'); ?>>Students</option>
                                                        <option value="Government Services" <?php echo set_select('occupation', 'Government Services'); ?>>Government Services</option>
                                                        <option value="Business" <?php echo set_select('occupation', 'Business'); ?>>Business</option>
                                                        <option value="Professionals" <?php echo set_select('occupation', 'Professionals'); ?>>Professionals</option>
                                                        <option value="Researchers" <?php echo set_select('occupation', 'Researchers'); ?>>Researchers</option>
                                                    </select>
                                                    <?php if (form_error('occupation')) { ?>
                                                        <span class="error"><?php echo form_error('occupation'); ?></span>
                                                    <?php } ?>
                                                </div>
                                                <!-- </form> -->
                                            </div>
                                        </div>
                                        <div class="col-lg-6 col-md-6 col-sm-6 col-xs-12 marg address-field">
                                            <div class="search-gazette">
                                                <div class="form-group">
                                                    <label class="margin-bottom">Address <span class="mandatory">*</span></label>
                                                    <textarea class="form-control" autocomplete="off" placeholder="Address" name="address" id="address"  maxlength="50"><?php echo set_value('address'); ?></textarea>
                                                    <?php if (form_error('address')) { ?>
                                                        <span class="error"><?php echo form_error('address'); ?></span>
                                                    <?php } ?>
                                                </div>
                                            </div>
                                        </div>
                                        <div class="col-lg-6 col-md-6 col-sm-6 col-xs-12 marg" id="subjectID">
                                            <div class="search-gazette">
                                                <div class="form-group">
                                                    <label class="margin-bottom">Subject <span class="mandatory">*</span></label>
                                                    <input type="text" placeholder="Subject" class="form-control" name="subject" id="subject" autocomplete="off" value="<?php echo set_value('subject'); ?>" maxlength="100"/>
                                                    <?php if (form_error('subject')) { ?>
                                                        <span class="error"><?php echo form_error('subject'); ?></span>
                                                    <?php } ?>
                                                </div>
                                            </div>
                                        </div>
                                        <div class="col-lg-12 col-md-12 col-sm-12 col-xs-12">
                                            <div class="row">
                                                <div class="col-lg-6 col-md-6 col-sm-12 col-xs-12 marg" id="keywordID">
                                                    <div class="search-gazette">
                                                        <div class="form-group">
                                                            <label class="margin-bottom">Feedback <span class="mandatory">*</span></label>
                                                            <textarea placeholder="Feedback" class="form-control" name="feedback" id="feedback" rows="5" maxlength="800" autocomplete="off"><?php echo set_value('feedback'); ?></textarea>

                                                            <?php if (form_error('feedback')) { ?>
                                                                <span class="error"><?php echo form_error('feedback'); ?></span>
                                                            <?php } ?>
                                                        </div>
                                                    </div>
                                                </div>
                                                <div class="col-lg-2 col-md-4 col-sm-8 col-xs-4 marg">
                                                    <?php echo $captcha; ?>
                                                </div>
                                                <div class="col-lg-6 col-md-6 col-sm-6 col-xs-12 marg">
                                                    <label class="margin-bottom">Captcha <span class="mandatory">*</span></label>
                                                    <input type="text" name="captcha" class="form-control" id="captcha" autocomplete="off" maxlength="6" placeholder="Enter Captcha"/>
                                                    <span id="captcha_error" class="error" name="captcha_error"><?php echo $captchaValidationMessage; ?></span>
                                                </div>
                                                <div class="submit-button">
                                                    <input type="submit" class="hvr-shutter-out-horizontal middle-btm">
                                                </div>
                                            </div>
                                        </div>
                                    <?php echo form_close(); ?>
                                </div>
                            </div>
                        </div>
                    </div>
                    <!--Right Sidebar-1 End-->
                    <!--Left Sidebar-1 Start-->
                    <div class="col-lg-3 col-md-3 col-sm-12 col-xs-12 left-side-bar">
                        <div class="row">
                            <div class="col-lg-12 col-md-12 col-sm-12 col-xs-12  media-section important-link-section reveal-left">
                                <div class="infographic-section e-margin links">
                                    <h3 class="head-section green">Important Links</h3>
                                    <ul id="announcements-vertical-news">
                                        <li class="external"><a href="https://govtpress.(StateName).gov.in/" target="_blank">Directorate of Printing, Stationery & Publication</a></li>
                                        <li class="external"><a href="http://ct.(StateName).gov.in/" target="_blank">Department of Commerce & Transport</a></li>
                                        <li class="external"><a href="https://www.(StateName).gov.in/" target="_blank">State Portal of (StateName)</a></li>
                                    </ul>
                                </div>
                            </div>
                        </div>
                    </div>
                    <!--Left Sidebar-1 End-->
                </div>
            </div>
        </section>
        <?php include_once 'website/include/footer.php'; ?>
        <?php include_once 'website/include/script.php'; ?>
    </body>
</html>
<script src="<?php echo base_url(); ?>assets/js/jquery.validate.min.js"></script>
<script type="text/javascript" nonce="a6b9a780936c8e980939086f618dded2">
    $(document).ready(function () {
        $("#feedback_frm").validate({
            rules: {
                name: {
                    required: true,
                    minlength: 4,
                    maxlength: 96
                },
                email: {
                    required: true,
                    email: true,
                    minlength: 6,
                    maxlength: 96
                },
                mobile: {
                    required: true,
                    number: true,
                    minlength: 10,
                    maxlength: 10
                },
                occupation: {
                    required: true
                },
                subject: {
                    required: true,
                    minlength: 4,
                    maxlength: 30
                },
                address: {
                    required: true,
                    minlength: 4,
                    maxlength: 100
                },
                feedback: {
                    required: true,
                    minlength: 10,
                    maxlength: 400
                },
                captcha: {
                    required: true
                }
            },
            messages: {
                name: {
                    required: 'Please enter your name',
                    minlength: 'Name should be minimum 6 characters',
                    maxlength: 'Name should be maximum 96 characters'
                },
                email: {
                    required: 'Please enter your email',
                    email: 'Please enter a valid email address',
                    minlength: 'Email should be minimum 6 characters',
                    maxlength: 'Email should be maximum 96 characters'
                },
                mobile: {
                    required: 'Please enter your mobile no',
                    number: 'Mobile no should be in digits',
                    minlength: 'Mobile no should be 10 digits',
                    maxlength: 'Mobile no should be 10 digits'
                },
                occupation: {
                    required: 'Please select occupation'
                },
                subject: {
                    required: 'Please enter subject',
                    minlength: 'Subject should be minimum 4 characters',
                    maxlength: 'Subject should be maximum 30 characters'
                },
                address: {
                    required: 'Please enter address',
                    minlength: 'Address should be minimum 4 characters',
                    maxlength: 'Address should be maximum 100 characters'
                },
                feedback: {
                    required: 'Please enter feedback',
                    minlength: 'Feedback should be minimum 10 chars',
                    maxlength: 'Feedback should be minimum 400 chars'
                },
                captcha: {
                    required: 'Please enter captcha'
                }
            }
        });
    });
</script>