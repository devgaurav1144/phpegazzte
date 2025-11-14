<?php defined('BASEPATH') OR exit('No direct script access allowed'); ?>
<!doctype html>
<html class="no-js" lang="">
    <head>
        <meta charset="utf-8" />
        <link rel="shortcut icon" href="<?php echo base_url(); ?>assets/images/favicon.ico" type="image/x-icon">
        <link rel="icon" href="<?php echo base_url(); ?>assets/images/favicon.ico" type="image/x-icon">
        <title>:: E-Gazette - Forgot Password ::</title>
        <meta name="description" content="">
        <meta name="viewport" content="width=device-width, initial-scale=1">
        <link rel="stylesheet" href="<?php echo base_url(); ?>assets/js/vendor/bootstrap/bootstrap.min.css">
        <!-- CSS Files -->
        <link href="<?php echo base_url(); ?>assets/css/main.css" rel="stylesheet" nonce="8f0882ce3be14f201cadd0eff5726cbd">
        <style nonce="8f0882ce3be14f201cadd0eff5726cbd">
            .card-signup .header {
                box-shadow: 0 1px 1px -12px rgba(0,0,0,0.56), 0 4px 10px 0px rgba(0,0,0,0.12), 0 0px 10px -5px rgba(0,0,0,0.2); 
            }
            .copyright a{
                color: #fff
            }
            .login_bg {
                background-image: url('<?php echo base_url(); ?>assets/images/login-bg.svg'); background-size: cover;
                background-position: top center;
            }
            .form-group{
                text-align: left;
            }
            .content-divider {
                text-align: center;
                position: relative;
                z-index: 1;
                margin: 10px 0;
            }
            .content-divider > span:before {
                content: "";
                position: absolute;         
                top: 50%;
                left: 0;
                height: 1px;
                background-color: #f1f4f9;
                width: 100%;
                z-index: -1;
            }
            .content-divider > span {
                background-color: #f1f4f9;
                display: inline-block;
                padding: 2px 10px;
                font-size: 13px;
            }
            .card-signup .content {
                padding: 0 20px 20px;
            }
            .btn{
                width: 100%;
            }
            .heading{
                margin: 0 0 15px;
            }
            .mandatory{
                color: red;
            }
            .error{
                color: red;
            }
            .error-message {
                color: red;
            }
        </style>
    </head>
    <body id="falcon" class="authentication">
        <div class="wrapper">
            <div class="header header-filter login_bg">
                <div class="container">
                    <div class="row">
                        <div class="col-md-4 col-md-offset-4 col-sm-6 col-sm-offset-3 text-center">
                            <div class="card card-signup">
                                <div class="header header-primary text-center">
                                    <a href="<?php echo base_url(); ?>"><img width="270px;" src="<?php echo base_url(); ?>assets/frontend/images/logo.png"/></a>
                                </div>
                                <h3 class="mt-0">Forgot Password</h3>
                                <?php if ($this->session->flashdata('error')): ?>
                                    <div class="error-message">
                                        <?php echo $this->session->flashdata('error'); ?>
                                    </div>
                                <?php endif; ?>
                                <span class="message"></span>
                                <?php echo form_open('applicants_login/forgot_password', array('method' => 'post', 'class' => 'form', 'id' => 'otp_form')); ?>    
                                    <div class="content">
                                        <div class="form-group">
                                            <label class="field-head">Mobile <span class="mandatory">*</span></label>
                                            <input type="text" name="mobile" id="mobile" class="form-control underline-input" placeholder="Enter your mobile" required="" autocomplete="off" maxlength="10">
                                            <?php if (form_error('mobile')) { ?>
                                                <span class="error"><?php echo form_error('mobile'); ?></span>
                                            <?php } ?>
                                        </div>
                                        <div class="row">
                                            <div class="col-lg-6 col-md-16 col-sm-12 col-xs-12 marg">
                                                <?php echo $captchaImg; ?>
                                            </div>
                                        </div>
                                        <div class="row">
                                            <div class="col-lg-12 col-md-12 col-xs-12 page">
                                                <label class="field-head">Captcha <span class="mandatory">*</span></label>
                                                <input type="text" name="captcha" class="form-control" id="captcha" autocomplete="off" maxlength="6" placeholder="Enter Captcha"/>
                                                <span id="captcha_error" class="error" name="captcha_error"><?php echo $captchaValidationMessage; ?></span>
                                            </div>
                                        </div>
                                        <div class="footer text-center">
                                            <input type="submit" class="btn btn-info btn-raised" name="submit" value="Submit"/>
                                        </div>
                                        <div class="text-center text-muted content-divider mb-3">
                                            <span class="px-2">Back to login?</span>
                                        </div>
                                        <a href="<?php echo base_url(); ?>applicants_login/index" class="btn btn-default btn-raised register_btn btn-wd">Login</a>
                                        <?php echo form_close(); ?>
                                    </div>
                            </div>
                        </div>
                    </div>
                </div>
                <footer class="footer mt-20">
                    <div class="container">
                        <div class="col-lg-12 text-center">
                            <div class="copyright text-white mt-20"> &copy; Directorate of Printing, Stationery & Publication. Developed by <a href="https://www.nic.in/" target="_blank">NIC</a>
                            </div>
                        </div>
                    </div>
                </footer>
            </div>
        </div>
        <!--  Vendor JavaScripts -->
        <script src="<?php echo base_url(); ?>assets/bundles/libscripts.bundle.js"></script>
        <script src="<?php echo base_url(); ?>assets/bundles/mainscripts.bundle.js"></script>
        <script src="<?php echo base_url(); ?>assets/js/jquery.validate.min.js"></script>
        <!-- Custom Js -->
        <script type="text/javascript" nonce="a6b9a780936c8e980939086f618dded2">
            /*
             * Email validation
             */
            //jQuery.validator.addMethod("email", function (value, element) {
            //   return this.optional(element) || /^([A-Za-z0-9_\-\.])+\@([A-Za-z0-9_\-\.])+\.([A-Za-z]{2,4})$/i.test(value);
            //});
            $("#otp_form").validate({
                rules: {
                    mobile: {
                        required: true,
						number: true,
                        minlength: 10,
                        maxlength: 10
                    },
                    captcha: {
                        required: true
                    }
                },
                messages: {
                   mobile: {
                       required: 'Please enter your mobile',
					   number: 'Mobile number should be in digits',
                       minlength: 'Mobile should be minimum 10 digits',
                       maxlength: 'Mobile should be maximum 10 digits'
                   },
                   captcha: {
                        required: 'Please enter captcha'
                    }      
                }
            });
        </script>
    </body>
</html>