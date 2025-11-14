<?php defined('BASEPATH') OR exit('No direct script access allowed'); ?>
<!doctype html>
<html class="no-js" lang="">
    <head>
        <meta charset="utf-8" />
        <link rel="shortcut icon" href="<?php echo base_url(); ?>assets/images/favicon.ico" type="image/x-icon">
        <link rel="icon" href="<?php echo base_url(); ?>assets/images/favicon.ico" type="image/x-icon">
        <title>:: E-Gazette - Applicant Login ::</title>
        <meta name="description" content="">
        <meta name="viewport" content="width=device-width, initial-scale=1">
        <link rel="stylesheet" href="<?php echo base_url(); ?>assets/js/vendor/bootstrap/bootstrap.min.css" nonce="8f0882ce3be14f201cadd0eff5726cbd">
        <!-- CSS Files -->
        <link href="<?php echo base_url(); ?>assets/css/main.css" rel="stylesheet" nonce="8f0882ce3be14f201cadd0eff5726cbd">
        <link nonce="8f0882ce3be14f201cadd0eff5726cbd" type="text/css" rel="Stylesheet" href="<?php echo CaptchaUrls::LayoutStylesheetUrl(); ?>" />
        <style>
            .BDC_CaptchaDiv {
                overflow: visible !important;
                margin: 13px auto 5px !important;
            }
            .BDC_CaptchaIconsDiv {
                position: absolute;
                top: 22px;
                margin-left: 14px !important;
                right: -52px;
            }
			.card-signup .header {
                box-shadow: 0 1px 1px -12px rgba(0,0,0,0.56), 0 4px 10px 0px rgba(0,0,0,0.12), 0 0px 10px -5px rgba(0,0,0,0.2); 
            }
            .copyright a{
                color: #fff
            }
            
            .mandatory{
                color: red;
            }
            .form-group{
                text-align: left;
                margin: 0
            }
            .page{
                text-align: left;
            }
            .forgot-p {
                padding: 0;
                margin: 0;
                text-transform: none;
                color: #337ab7 !important;
                display: block;
                text-align: left;
                margin-bottom: 10px;
            }
            .card-signup .content {
                padding: 0 20px 20px;
            }
            .login_bg {
                background-image: url('<?php echo base_url(); ?>assets/images/login-bg.svg'); 
                background-size: cover;
                background-position: top center;
            }
            .btn-raised{
                width: 100%;
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
            .error{
                color: red;
            }
            .success {
                color: green;
            }
            .middle{
                text-align: center;
            }
            @media screen and (max-width: 1199px){
                .BDC_CaptchaIconsDiv {
                    right: 17px;
                }
            }
            @media screen and (max-width: 991px){ 
                .BDC_CaptchaIconsDiv{
                    right: 108px;
                }
                .BDC_CaptchaDiv{
                    margin: 13px 130px 5px 0 !important;
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
        </style>
    </head>
    <body id="falcon" class="authentication">
        <div class="wrapper">
            <div class="header header-filter login_page_header login_bg">
                <div class="container">
                    <div class="row">
                        <div class="col-md-4 col-md-offset-4 col-sm-6 col-sm-offset-3 text-center">
                            <div class="card card-signup">
                                <?php echo form_open('applicants_login/index', array('id' => 'login_form', 'method' => 'post', 'class' => 'form')); ?>
                                    <div class="header header-primary text-center">
                                        <a href="<?php echo base_url(); ?>"><img width="260px;height:auto" src="<?php echo base_url(); ?>assets/frontend/images/uplogo.png"/></a>
                                    </div>
                                    <h3 class="mt-0">Applicant Login</h3>
                                    
                                    <div class="middle">
                                        <?php if ($this->session->flashdata('success')) { ?>
                                            <span class="success"><?php echo $this->session->flashdata('success'); ?></span>
                                        <?php } ?>
                                    </div>
                                    <div class="middle">
                                        <?php if ($this->session->flashdata('error')) { ?>
                                            <span class="error"><?php echo $this->session->flashdata('error'); ?></span>
                                        <?php } ?>
                                    </div>
                                    <div class="content">
                                        <div class="form-group mobile_number">
                                            <label class="field-head">Mobile No. <span class="mandatory">*</span></label>
                                            <input type="text" name="mobile" id="mobile" class="form-control underline-input" placeholder="Enter Mobile Number" required autocomplete="off" value="" maxlength="10">
                                            <?php if (form_error('mobile')) { ?>
                                                <span class="error"><?php echo form_error('mobile'); ?></span>
                                            <?php } ?>
                                        </div>
                                        <div class="form-group mobile_number">
                                            <label class="field-head">Password <span class="mandatory">*</span></label>
                                            <input type="password" name="password" id="password" class="form-control underline-input" placeholder="Enter password" required autocomplete="off" maxlength="16" value="" onpaste="return false;" oncopy="return false;" oncut="return false;"/>
                                            <input type="hidden" name="enc_pwd" id="enc_pwd" value=""/>
                                            <input type="hidden" id="nonce" name="nonce">
                                            <a href="<?php echo base_url(); ?>applicants_login/forgot_password" class="btn btn-wd forgot-p">Forgot Password?</a>
                                            <?php if (form_error('password')) { ?>
                                                <span class="error"><?php echo form_error('password'); ?></span>
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
                                            <input type="submit" class="btn btn-info btn-raised w-100" name="submit_btn" value="Login">                                        </div>
                                            <div class="text-center text-muted content-divider mb-3">
										        <span class="px-2">Don't have an account?</span>
									    </div>
                                            <a href="<?php echo base_url(); ?>applicants_login/registeration" class="btn btn btn-default btn-raised w-100">Applicant Registration</a><br>
                                            <a href="<?php echo base_url(); ?>igr_user/login" class="btn btn-wd btn btn-default btn-raised w-100">IGR User Dashboard</a>
                                            <a href="<?php echo base_url(); ?>commerce_transport_department/login_ct" class="btn btn-wd btn btn-default btn-raised w-100">C&T User Dashboard</a>
                                            <a href="<?php echo base_url(); ?>offline_payment_users/login_offline" class="btn btn-wd btn btn-default btn-raised w-100">Offline Approver Dashboard</a>

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
        <script src="<?php echo base_url(); ?>assets/bundles/libscripts.bundle.js" type="text/javascript" nonce="a6b9a780936c8e980939086f618dded2"></script>
        <!-- <script src="<?php echo base_url(); ?>assets/bundles/mainscripts.bundle.js" type="text/javascript" nonce="a6b9a780936c8e980939086f618dded2"></script> -->
        <script src="<?php echo base_url(); ?>assets/js/jquery.validate.min.js" type="text/javascript" nonce="a6b9a780936c8e980939086f618dded2"></script>
        <script src="<?php echo base_url(); ?>assets/js/vendor/jRespond/jRespond.js" type="text/javascript" nonce="a6b9a780936c8e980939086f618dded2"></script>
        <script src="<?php echo base_url(); ?>assets/js/crypto-js.min.js" type="text/javascript" nonce="a6b9a780936c8e980939086f618dded2"></script>
        <script src="<?php echo base_url(); ?>assets/js/Encryption.js?>" type="text/javascript" nonce="a6b9a780936c8e980939086f618dded2"></script>
        <script src="<?php echo base_url(); ?>assets/js/sha256.js" type="text/javascript" nonce="a6b9a780936c8e980939086f618dded2"></script>
        <!-- Custom Js -->
        <script type="text/javascript" nonce="a6b9a780936c8e980939086f618dded2">
		
            $(document).ready(function(){

                var nonceValue = generateNonce();

                $("#nonce").val(nonceValue);

                $("#password").on("input", function() {
                    // Encrypt password object
                    var readableString = $(this).val();
                    var encryption = new Encryption();
                    var encrypted = encryption.encrypt(readableString, nonceValue);
                    $("#enc_pwd").val(encrypted);
                });
				
            });

            function generateNonce() {
                return CryptoJS.lib.WordArray.random(16).toString();
            }
            
            $("#login_form").validate({
                rules: {
                    mobile: {
                        required: true,
                        minlength: 10,
                        maxlength: 10,
						number: true
                    },
                    password: {
                        required: true,
                        minlength: 4,
                        maxlength: 16
                    },
                    captcha: {
                        required: true
                    }
                },
                messages: {
                    mobile: {
                       required: 'Please enter mobile number',
                       minlength: 'Mobile number should be minimum 10 digits',
                       maxlength: 'Mobile number should be maximum 10 digits',
					   number: 'Mobile number should be in digits'
                   },
                   password: {
                       required: 'Please enter password',
                       minlength: 'Password should be minimum 4 characters',
                       maxlength: 'Password should be maximum 16 characters'
                   },
                   captcha: {
                        required: 'Please enter captcha'
                    }
                },
                submitHandler: function(form) {
                    var hash = CryptoJS.SHA256($('#password').val());
                    $('#password').val(hash);
                    form.submit();
                }
            });
        </script>
    </body>
</html>