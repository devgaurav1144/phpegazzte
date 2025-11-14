<?php defined('BASEPATH') OR exit('No direct script access allowed'); ?>
<!doctype html>
<html class="no-js" lang="">
    <head>
        <meta charset="utf-8" />
        <link rel="shortcut icon" href="<?php echo base_url(); ?>assets/images/favicon.ico" type="image/x-icon">
        <link rel="icon" href="<?php echo base_url(); ?>assets/images/favicon.ico" type="image/x-icon">
        <title>:: E-Gazette - Applicant's Registration ::</title>
        <meta name="description" content="">
        <meta name="viewport" content="width=device-width, initial-scale=1">
        <link rel="stylesheet" href="<?php echo base_url(); ?>assets/js/vendor/bootstrap/bootstrap.min.css" nonce="8f0882ce3be14f201cadd0eff5726cbd">
         
        <link href="<?php echo base_url(); ?>assets/css/main.css" rel="stylesheet" nonce="8f0882ce3be14f201cadd0eff5726cbd">
        <style rel="stylesheet" nonce="8f0882ce3be14f201cadd0eff5726cbd">
            .card-signup .header {
                box-shadow: 0 1px 1px -12px rgba(0,0,0,0.56), 0 4px 10px 0px rgba(0,0,0,0.12), 0 0px 10px -5px rgba(0,0,0,0.2); 
            }
            .copyright {
                margin-bottom: 20px !important;
            }
            .copyright a{
                color: #fff
            }
            .register_btn {
                width: 50%;
            }
            .error{
                color: #D9534F !important;
            }
            .success_login {
                color: #28a745;
            }
            .login_bg {
                background-image: url('<?php echo base_url(); ?>assets/images/login-bg.svg'); background-size: cover;
                background-position: top center;
            }
            .labels {
                color: #444444 !important;
                font-weight: 500 !important;
            }
            .mobile_number{
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

            .form-group {
                margin-top: 10px !important;
            }
            .BDC_CaptchaDiv {
                overflow: visible !important;
                margin: 23px 58px 10px 0 !important;
            }
            .BDC_CaptchaIconsDiv {
                position: absolute;
                top: 31px;
                margin-left: 0px !important;
                right: 40px;
            }
            @media screen and (max-width: 991px){ 
                .BDC_CaptchaIconsDiv{
                    right: 255px;
                    top: 11px;
                }
                
            } 
            @media screen and (max-width: 600px){
                .BDC_CaptchaDiv {
                    margin: 13px 94px 5px 0 !important;
                }
                .BDC_CaptchaIconsDiv{
                    right: 193px;
                }
            }
            @media only screen and (max-width: 500px) {
                .BDC_CaptchaIconsDiv {
                    right: 138px;
                }
            }
            @media screen and (max-width: 1199px){
                
            }
        </style>
    </head>
    <body id="falcon" class="authentication">
        <div class="wrapper">
            <div class="header header-filter login_bg">
                <div class="container">
                    <div class="row">
                        <div class="col-md-6 col-md-offset-3 col-sm-12 text-center">
                            <div class="card card-signup">
                                <?php echo form_open('applicants_login/registeration', array('method' => 'post', 'id' => 'register_form_btn', 'name' => 'register_form_btn'))?>
                                    <div class="header header-primary text-center">
                                        <a href="<?php echo base_url(); ?>"><img width="270px;" src="<?php echo base_url(); ?>assets/frontend/images/logo.png"/></a>
                                    </div>
                                    <h3 class="mt-0">Applicant Registration</h3>
                                    <?php if ($this->session->flashdata('success')) { ?>
                                        <span class="success_login"><?php echo $this->session->flashdata('success'); ?></span>
                                    <?php } ?>
                                    <?php if ($this->session->flashdata('error')) { ?>
                                        <span class="error"><?php echo $this->session->flashdata('error'); ?></span>
                                    <?php } ?>
                                    <div class="content">
                                        <div class="row">
                                            <div class="col-md-6">
                                                <div class="form-group mobile_number">
                                                    <label class="labels">Name <span class="error">*</span></label>
                                                    <input type="text" name="name" id="name" class="form-control alpha_only" placeholder="Enter your name" required autocomplete="off" value="<?php echo set_value('name'); ?>" maxlength="30">
                                                    <?php if (form_error('name')) { ?>
                                                        <span class="error"><?php echo form_error('name'); ?></span>
                                                    <?php } ?>
                                                </div>
                                            </div>
                                            <div class="col-md-6">
                                                <div class="form-group mobile_number">
                                                    <label class="labels">Father/Husband Name <span class="error">*</span></label>
                                                    <input type="text" name="f_name" id="f_name" value="<?php echo set_value('f_name'); ?>" class="form-control alpha_only" required placeholder="Enter your Father's name" autocomplete="off"/>
                                                    <?php if (form_error('f_name')) { ?>
                                                        <span class="error"><?php echo form_error('f_name'); ?></span>
                                                    <?php } ?>
                                                </div>
                                            </div>
                                        </div>
                                        <div class="row">
											<div class="col-md-6">
												<div class="form-group mobile_number">
													<label class="labels">Mobile <span class="error">*</span></label>
													<input type="text" name="mobile" id="mobile" class="form-control number_only" placeholder="Enter your mobile number" required autocomplete="off" value="<?php echo set_value('mobile'); ?>" maxlength="10">
													<?php if (form_error('mobile')) { ?>
														<span class="error"><?php echo form_error('mobile'); ?></span>
													<?php } ?>
												</div>
											</div>
                                            <div class="col-md-6">
                                                <div class="form-group mobile_number">
                                                    <label class="labels">Email</label>
                                                    <input type="text" name="email" id="email" class="form-control underline-input" placeholder="Enter your email" autocomplete="off" value="<?php echo set_value('email'); ?>" maxlength="96">
                                                    <?php if (form_error('email')) { ?>
                                                        <span class="error"><?php echo form_error('email'); ?></span>
                                                    <?php } ?>
                                                </div>
                                            </div>
                                        </div>
                                        <div class="row">
                                            <div class="col-md-6">
                                                <?php echo $captchaImg; ?>
                                            </div>
                                            <div class="col-md-6 col-xs-12">
                                                <div class="form-group mobile_number">
                                                    <label class="field-head">Captcha <span class="error">*</span></label>
                                                    <input type="text" name="captcha" class="form-control" id="captcha" autocomplete="off" maxlength="6" placeholder="Enter Captcha"/>
                                                    <span id="captcha_error" class="error" name="captcha_error"><?php echo $captchaValidationMessage; ?></span>
                                                </div>
                                            </div>
                                        </div>
                                        <div class="footer text-center">
                                            <input type="submit" class="btn btn-info btn-raised register_btn" Value="Register"/>
                                        </div>
                                        <div class="text-center text-muted content-divider mb-3">
                                                <span class="px-2">Already have an account?</span>
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
             * Restrict number input only using class number_only in the input field
             */
            $('input.number_only').keyup(function (e) {
               if (/\D/g.test(this.value)) {
                  // Filter non-digits from input value.
                  this.value = this.value.replace(/\D/g, '');
               }
            });
            
            /*
             * Allowed alphabets input only using class number_only in the input field
             */
            $('input.alpha_only').keypress(function (e) {
               var regex = new RegExp("^[a-zA-Z\ ]$");
               var str = String.fromCharCode(!e.charCode ? e.which : e.charCode);
               if (regex.test(str)) {
                  return true;
               }
               e.preventDefault();
               return false;
            });
            
            /*
             * Email validation
             */
            jQuery.validator.addMethod("email", function (value, element) {
               return this.optional(element) || /^([A-Za-z0-9_\-\.])+\@([A-Za-z0-9_\-\.])+\.([A-Za-z]{2,4})$/i.test(value);
            });
            
            
            $("#register_form_btn").validate({
                rules: {
                    name: {
                        required: true,
                        minlength: 4,
                        maxlength: 40
                    },
                    f_name: {
                        required: true,
                        minlength: 4,
                        maxlength: 40
                    },
                    //relation_id: {
                    //    required: true
                    //},
                    email: {
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
                    //address: {
                    //    required: true,
                    //    minlength: 5,
                    //    maxlength: 200
                    //},
                    captcha: {
                        required: true
                    }
                },
                messages: {
                    name: {
                        required: 'Please enter name',
                        minlength: 'Name should be minimum 4 characters',
                        maxlength: 'Name should be maximum 40 characters'
                    },
                    f_name: {
                        required: "Please enter father's name",
                        minlength: 'Father/Husband name should be minimum 4 characters',
                        maxlength: 'Father/Husband name should be maximum 40 characters'
                    },
                    //relation_id: {
                    //    required: 'Please select relation'
                    //},
                    email: {
                        email: 'Please enter a valid email',
                        minlength: 'Email should be minimum 6 characters',
                        maxlength: 'Email should be maximum 96 characters',
                    },
                    mobile: {
                        required: 'Please enter mobile number',
                        number: 'Please enter 10 digit mobile number',
                        minlength: 'Mobile number should be minimum 10 digit',
                        maxlength: 'Mobile number should be maximum 10 digit',
                    },
                    //address: {
                    //    required: "Please enter address",
                    //    minlength: "Address should be minimum 5 characters",
                    //    maxlength: "Address should be maximum 200 characters"
                    //},
                    captcha: {
                        required: "Please enter captcha"
                    }
                }     
            });
        </script>
    </body>
</html>