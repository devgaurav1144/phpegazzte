<?php defined('BASEPATH') OR exit('No direct script access allowed'); ?>
<!doctype html>
<html class="no-js" lang="">
    <head>
        <meta charset="utf-8" />
        <link rel="shortcut icon" href="<?php echo base_url(); ?>assets/images/favicon.ico" type="image/x-icon">
        <link rel="icon" href="<?php echo base_url(); ?>assets/images/favicon.ico" type="image/x-icon">
        <title>:: E-Gazette - Nodal Officer Registration ::</title>
        <meta name="description" content="">
        <meta name="viewport" content="width=device-width, initial-scale=1">
        <link rel="stylesheet" href="<?php echo base_url(); ?>assets/js/vendor/bootstrap/bootstrap.min.css" nonce="8f0882ce3be14f201cadd0eff5726cbd">
        <!-- CSS Files -->
        <link href="<?php echo base_url(); ?>assets/css/main.css" rel="stylesheet" nonce="8f0882ce3be14f201cadd0eff5726cbd">
        <style rel="stylesheet" nonce="8f0882ce3be14f201cadd0eff5726cbd">
            .card-signup .header {
                box-shadow: 0 1px 1px -12px rgba(0,0,0,0.56), 0 4px 10px 0px rgba(0,0,0,0.12), 0 0px 10px -5px rgba(0,0,0,0.2); 
            }
            .copyright {
                margin-bottom: 20px !important;
            }
            .card-signup .content {
                padding: 0 20px 20px;

            }
            .copyright a{
                color: #fff
            }
            .register_btn {
                width: 50%;
            }
            .error{
                color: #D9534F;
            }
            .success_login {
                color: #28a745;
            }
            
            .mobile_number{
                text-align: left;
            }
            .login_bg {
                background-image: url('<?php echo base_url(); ?>assets/images/login-bg.svg'); background-size: cover;
                background-position: top center;
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
            .mandatory{
                color: red;
            }
            .nodal-register{
                margin: 0 auto;
                width: 62%;
                margin-left: 20%;
            }
            .BDC_CaptchaDiv {
                overflow: visible !important;
                margin: 23px auto 10px !important;
            }
            .BDC_CaptchaIconsDiv {
                position: absolute;
                top: 30px;
                margin-left: 0px !important;
                right: 50px;
            }
            @media screen and (max-width: 1199px){
                .BDC_CaptchaIconsDiv{
                    right: 15px;
                }
            }
            @media screen and (max-width: 991px){ 
                .BDC_CaptchaIconsDiv{
                    right: 86px;
                    top: 8px;
                }
                
            } 
            @media screen and (max-width: 600px){
                .BDC_CaptchaIconsDiv{
                    right: 41px;
                }
            }
            @media screen and (max-width: 500px){
                .BDC_CaptchaIconsDiv {
                    right: 17px;
                }
            }    
        </style>
    </head>
    <body id="falcon" class="authentication">
        <div class="wrapper">
            <div class="header header-filter login_bg">
                <div class="container">
                    <div class="row">
                        <div class="col-md-6 col-md-offset-3 col-sm-12 text-center nodal-register">
                            <div class="card card-signup">
                                <?php echo form_open('user/register', array('class' => 'form', 'method' => 'post', 'id' => 'register_form'))?>
                                    <div class="header header-primary text-center">
                                        <a href="<?php echo base_url(); ?>"><img width="270px;" src="<?php echo base_url(); ?>assets/frontend/images/logo.png"/></a>
                                    </div>
                                    <h3 class="mt-0">Nodal Officer Registration</h3>
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
                                                    <label class="field-head">Name <span class="mandatory">*</span></label>
                                                    <input type="text" name="name" id="name" class="form-control underline-input" placeholder="Enter your name" required autocomplete="off" value="<?php echo set_value('name'); ?>" maxlength="30">
                                                    <?php if (form_error('name')) { ?>
                                                        <span class="error"><?php echo form_error('name'); ?></span>
                                                    <?php } ?>
                                                </div>
                                            </div>
                                            <div class="col-md-6">
                                                <div class="form-group mobile_number">
                                                    <label class="field-head">Designation <span class="mandatory">*</span></label>
                                                    <select name="designation" id="designation" class="form-control" required>
                                                        <option value="">Select Designation</option>
                                                        <?php if (!empty($designations)) { ?>
                                                            <?php foreach ($designations as $design) { ?>
                                                                <option value="<?php echo $design->name; ?>" <?php echo set_select('designation', $design->name); ?>><?php echo $design->name; ?></option>
                                                            <?php } ?>
                                                        <?php } ?>
                                                    </select>
                                                    <?php if (form_error('designation')) { ?>
                                                        <span class="error"><?php echo form_error('designation'); ?></span>
                                                    <?php } ?>
                                                </div>
                                            </div>
                                        </div>
                                        <div class="row">
                                            <div class="col-md-6">
                                                <div class="form-group mobile_number">
                                                    <label class="field-head">Username <span class="mandatory">*</span></label>
                                                    <input type="text" name="username" id="username" value="<?php echo set_value('username'); ?>" class="form-control" required placeholder="Enter username" autocomplete="off"/>
                                                    <?php if (form_error('username')) { ?>
                                                        <span class="error"><?php echo form_error('username'); ?></span>
                                                    <?php } ?>
                                                </div>
                                            </div>
                                            <div class="col-md-6">
                                                <div class="form-group mobile_number">
                                                    <label class="field-head">Email <span class="mandatory">*</span></label>
                                                    <input type="text" name="email" id="email" class="form-control underline-input" placeholder="Enter email" required autocomplete="off" value="<?php echo set_value('email'); ?>" maxlength="96">
                                                    <?php if (form_error('email')) { ?>
                                                        <span class="error"><?php echo form_error('email'); ?></span>
                                                    <?php } ?>
                                                </div>
                                            </div>
                                        </div>
                                        <div class="row">
                                            <div class="col-md-6">
                                                <div class="form-group mobile_number">
                                                    <label class="field-head">Mobile No. <span class="mandatory">*</span></label>
                                                    <input type="text" name="mobile" id="mobile" class="form-control underline-input" placeholder="Enter mobile number" required autocomplete="off" value="<?php echo set_value('mobile'); ?>" maxlength="10">
                                                    <?php if (form_error('mobile')) { ?>
                                                        <span class="error"><?php echo form_error('mobile'); ?></span>
                                                    <?php } ?>
                                                </div>
                                            </div>
                                            <div class="col-md-6">
                                                <div class="form-group mobile_number">
                                                    <label class="field-head">Employee ID (HRMS) <span class="mandatory">*</span></label>
                                                    <input type="text" name="gpf_no" id="gpf_no" class="form-control underline-input" placeholder="Enter employee ID (HRMS)" required autocomplete="off" value="<?php echo set_value('gpf_no'); ?>" maxlength="6">
                                                    <?php if (form_error('gpf_no')) { ?>
                                                        <span class="error"><?php echo form_error('gpf_no'); ?></span>
                                                    <?php } ?>
                                                </div>
                                            </div>
                                        </div>
                                        <div class="row">
                                            <div class="col-md-12">
                                                <div class="form-group mobile_number">
                                                    <label class="field-head">Department <span class="mandatory">*</span></label>
                                                    <select name="dept_id" id="dept_id" class="form-control" required>
                                                        <option value="">Select Department</option>
                                                        <?php if (!empty($departments)) { ?>
                                                            <?php foreach ($departments as $dept) { ?>
                                                                <option value="<?php echo $dept->id; ?>" <?php echo set_select('dept_id', $dept->id); ?>><?php echo $dept->department_name; ?></option>
                                                            <?php } ?>
                                                        <?php } ?>
                                                    </select>
                                                    <?php if (form_error('dept_id')) { ?>
                                                        <span class="error"><?php echo form_error('dept_id'); ?></span>
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
                                                    <label class="field-head">Captcha <span class="mandatory">*</span></label>
                                                    <input type="text" name="captcha" class="form-control" id="captcha" autocomplete="off" maxlength="6" placeholder="Enter Captcha"/>
                                                    <span id="captcha_error" class="error" name="captcha_error"><?php echo $captchaValidationMessage; ?></span>
                                                </div>
                                            </div>
                                        </div>

                                        <div class="footer text-center">
                                        <input type="submit" class="btn btn-info btn-raised register_btn" name="submit" value="Register"/>
                                            </div>
                                            <div class="text-center text-muted content-divider mb-3">
                                                <span class="px-2">Already have an account?</span>
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
        <script src="<?php echo base_url(); ?>assets/js/additional-methods.min"></script>
        <!-- Custom Js -->
        <script type="text/javascript" nonce="a6b9a780936c8e980939086f618dded2">

            $.validator.addMethod("lettersonly", function(value, element) {
                return this.optional(element) || /^[A-Z\s]+$/i.test(value);
            }, "Only alphabetical characters");

            $("#register_form").validate({
                rules: {
                    name: {
                        required: true,
                        minlength: 4,
                        maxlength: 40
                    },
                    username: {
                        required: true,
                        minlength: 4,
                        maxlength: 20
                    },
                    designation: {
                        required: true
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
                    dept_id: {
                        required: true,
                    },
                    gpf_no: {
                        required: true,
                        minlength: 6,
                        maxlength: 6,
                        lettersonly: true
                    },
                    captcha:{
                        required: true,
                    }
                },
                messages: {
                    name: {
                        required: 'Please enter name',
                        minlength: 'Name should be minimum 4 characters',
                        maxlength: 'Name should be maximum 40 characters'
                    },
                    username: {
                        required: 'Please enter username',
                        minlength: 'Password should be minimum 4 characters',
                        maxlength: 'Password should be maximum 20 characters'
                    },
                    designation: {
                        required: 'Please select designation'
                    },
                    email: {
                        required: 'Please enter email',
                        email: 'Please enter a valid email',
                        minlength: 'Email should be minimum 6 characters',
                        maxlength: 'Email should be maximum 96 characters'
                    },
                    mobile: {
                        required: 'Please enter mobile number',
                        number: 'Please enter 10 digit mobile number',
                        minlength: 'Mobile number should be minimum 10 digit',
                        maxlength: 'Mobile number should be maximum 10 digit'
                    },
                    dept_id: {
                        required: 'Please select department'
                    },
                    gpf_no: {
                        required: 'Please enter Employee ID',
                        minlength: 'HRMS ID should be minimum 6 characters',
                        maxlength: 'HRMS ID should be maximum 6 characters',
                        lettersonly: 'Only block alphabet letters allowed'
                    },
                    captcha:{
                        required: 'Please enter captcha'
                    }
                }
            });
        </script>
    </body>
</html>