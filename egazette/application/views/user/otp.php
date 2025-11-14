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
            
            .mandatory{
                color: red;
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
            
        </style>
    </head>
    <body id="falcon" class="authentication">
        <div class="wrapper">
            <div class="header header-filter login_bg">
                <div class="container">
                    <div class="row">
                        <div class="col-md-4 col-md-offset-4 col-sm-6 col-sm-offset-3 text-center">
                            <div class="card card-signup">
                                
                                    <div class="header header-primary text-center heading">
                                        <a href="<?php echo base_url(); ?>"><img width="270px;" src="<?php echo base_url(); ?>assets/frontend/images/logo.png"/></a>
                                    </div>
                                    <h3 class="mt-0">Forgot Password</h3>
                                    <?php if ($this->session->flashdata('error')) { ?>
                                        <?php echo $this->session->flashdata('error'); ?>
                                    <?php } ?>
                                    <span class="message"></span>
                                    <?php echo form_open('user/forgot_password', array('method' => 'post', 'class' => 'form', 'id' => 'otp_form')); ?>    
                                        <div class="content">
                                            <div class="form-group">
                                                <label class="field-head">Email <span class="mandatory">*</span></label>
                                                <input type="text" name="email" id="email" class="form-control underline-input" placeholder="Enter your email" required="" autocomplete="off" maxlength="96">
                                                <?php if (form_error('email')) { ?>
                                                    <span class="error"><?php echo form_error('email'); ?></span>
                                                <?php } ?>
                                            </div>
                                            <div class="footer text-center">
                                            <input type="submit" class="btn btn-info btn-raised btn-wd" name="submit" value="Submit"/>
                                        </div>
                                        <div class="text-center text-muted content-divider mb-3">
                                            <span class="px-2">Back to login?</span>
                                        </div>
                                        <a href="<?php echo base_url(); ?>user/login" class="btn btn-default btn-raised register_btn btn-wd">Login</a>
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
            $("#otp_form").validate({
                rules: {
                    email: {
                        required: true,
                        email: true,
                        minlength: 6,
                        maxlength: 96
                    }
                },
                messages: {
                   email: {
                       required: 'Please enter your email',
                       minlength: 'OTP should be minimum 6 characters',
                       maxlength: 'OTP should be maximum 96 characters'
                   }      
                }
            });
        </script>
    </body>
</html>