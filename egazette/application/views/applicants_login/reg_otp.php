<?php defined('BASEPATH') OR exit('No direct script access allowed'); ?>
<!doctype html>
<html class="no-js" lang="">
    <head>
        <meta charset="utf-8" />
        <link rel="shortcut icon" href="<?php echo base_url(); ?>assets/images/favicon.ico" type="image/x-icon">
        <link rel="icon" href="<?php echo base_url(); ?>assets/images/favicon.ico" type="image/x-icon">
        <title>:: E-Gazette - Verify OTP ::</title>
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
            
            .middle{
                text-align: center;
            }
            .success {
                color: green;
            }
            .error {
                color: red;
            }
            .mandatory {
                color: red;
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
                                <h3 class="mt-0">Verify OTP</h3>
                                <?php if ($this->session->flashdata('error')) { ?>
                                    <span class="error"><?php echo $this->session->flashdata('error'); ?></span>
                                <?php } ?>
                                <?php if ($this->session->flashdata('success')) { ?>
                                    <span class="success"><?php echo $this->session->flashdata('success'); ?></span>
                                <?php } ?>
                                <?php echo form_open('applicants_login/verify_reg_otp', array('method' => 'post', 'class' => 'form', 'id' => 'otp_form')); ?>    
                                    <input type="hidden" name="login_id" id="login_id" value="<?php echo $login_id; ?>"/>
                                    <div class="content">
                                        <div class="form-group mobile_number">
                                            <label class="labels">OTP <span class="mandatory">*</span></label>
                                            <input type="text" name="otp" id="otp" class="form-control underline-input" placeholder="Enter OTP" required="" autocomplete="off" maxlength="4" value="<?php echo set_value('otp'); ?>">
                                            <?php if (form_error('otp')) { ?>
                                                <span class="error"><?php echo form_error('otp'); ?></span>
                                            <?php } ?>
                                        </div>
                                        <div class="form-group mobile_number">
                                            <label class="labels">Password <span class="mandatory">*</span></label>
                                            <input type="password" name="password" id="password" class="form-control underline-input pass_vali" placeholder="Enter Password" required="" autocomplete="off" maxlength="16">
                                            <?php if (form_error('password')) { ?>
                                                <span class="error"><?php echo form_error('password'); ?></span>
                                            <?php } ?>
                                        </div>
                                        <div class="form-group mobile_number">
                                            <label class="labels">Confirm Password <span class="mandatory">*</span></label>
                                            <input type="password" name="confirm_password" id="confirm_password" class="form-control underline-input pass_vali" placeholder="Retype Password" required="" autocomplete="off" maxlength="16">
                                            <?php if (form_error('confirm_password')) { ?>
                                                <span class="error"><?php echo form_error('confirm_password'); ?></span>
                                            <?php } ?>
                                        </div>
                                        <input type="hidden" name="encoded_otp" id="encoded_otp">
                                        <input type="hidden" name="encoded_password" id="encoded_password">
                                        <input type="hidden" name="encoded_confirm_password" id="encoded_confirm_password">
                                    </div>
                                    <div class="footer text-center">
                                        <input type="submit" class="btn btn-info btn-raised" name="submit" value="Submit"/>
                                    </div>
                                <?php echo form_close(); ?>
                                <?php echo form_open('applicants_login/resend_reg_otp', array('method' => 'post', 'class' => 'form', 'id' => 'otp_form')); ?>    
                                <input type="hidden" name="login_id" id="login_id" value="<?php echo $login_id; ?>"/>
                                    <input type="submit" class="btn btn-wd" name="submit" value="Resend OTP"/>
<!--                                <a href="javascript:void(0);" class="btn btn-wd" id="resend_otp">Resend OTP</a>-->
                                <?php echo form_close(); ?>
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
        <script src="<?php echo base_url(); ?>assets/js/crypto-js.min.js" type="text/javascript" nonce="a6b9a780936c8e980939086f618dded2"></script>
        <script src="<?php echo base_url(); ?>assets/js/Encryption.js?>" type="text/javascript" nonce="a6b9a780936c8e980939086f618dded2"></script>

        <!-- Custom Js -->
        <script type="text/javascript" nonce="a6b9a780936c8e980939086f618dded2">
            
            /*
            * Restrict alphabetic input only using class number_only in the input field
            */
           $('input.pass_vali').keypress(function (e) {
               var regex = new RegExp("^[A-Za-z0-9\-@._]$");
               var str = String.fromCharCode(!e.charCode ? e.which : e.charCode);
               if (regex.test(str)) {
                   return true;
               }
               e.preventDefault();
               return false;
           });
            
            $.validator.addMethod("pwcheck", function(value) {
                return /^[A-Za-z0-9\d=!\-@._*]*$/.test(value) // consists of only these
                    && /[A-Z]/.test(value) // has a lowercase letter
                    && /[a-z]/.test(value) // has a lowercase letter
                    && /\d/.test(value); // has a digit
            }, 'Password must contain at least digit,capital and lower case character(A-Za-z0-9@-_.)');

            $("#otp_form").validate({
                rules: {
                    otp: {
                        required: true,
                        number: true,
                        minlength: 4,
                        maxlength: 4
                    },
                    password: {
                        required: true,
                        minlength: 4,
                        maxlength: 16,
                        pwcheck: true
                    },
                    confirm_password: {
                        required: true,
                        minlength: 4,
                        maxlength: 16,
                        equalTo: '#password'
                    }
                },
                messages: {
                    otp: {
                        required: 'Please enter OTP',
                        number: 'OTP should be in digits',
                        minlength: 'OTP should be 4 digits',
                        maxlength: 'OTP should be 4 digits'
                    },
                    password: {
                        required: 'Please enter Password',
                        minlength: 'Password should be minimum 6 characters',
                        maxlength: 'Password should be maximum 16 characters',
                        pwcheck: 'Password must contain a small letter, a digit and a capital letter'
                    },
                    confirm_password: {
                        required: 'Please enter Confirm Password',
                        minlength: 'Confirm Password should be minimum 6 characters',
                        maxlength: 'Confirm Password should be maximum 16 characters',
                        equalTo: 'Confirm password should be matched with password'
                    }
                }
                // ,
                // submitHandler: function(form) {
                //     var hash = CryptoJS.SHA256($('#password').val());
                //     $('#password').val(hash);
                //     $('#confirm_password').val(hash);
                //     form.submit();
                // }
            });
            
            $("#resend_otp").on('click', function () {
                $(".loader_wrapper").show();
                $.ajax({
                    type : "POST",
                    url : "<?php echo base_url(); ?>applicants_login/resend_reg_otp",
                    data : {
                        login_id : $("#login_id").val(),
                        "<?php echo $this->security->get_csrf_token_name();?>": "<?php echo $this->security->get_csrf_hash();?>"
                    },
                    success : function (response) {
                        $("#table_id").append(response);
                        $(".loader_wrapper").hide();
                    }
                });
            });
        </script>

        <script>
            $(document).ready( function () {
                $("#password, #confirm_password").on("input", function() {
                    var readableString = $(this).val();
                    var nonceValue = 'lol';

                    var encryption = new Encryption();
                    var encrypted = encryption.encrypt(readableString, nonceValue);

                    if ($(this).attr('id') === 'password' ) {
                        $('#encoded_password').val(encrypted);
                    } else if ($(this).attr('id') === 'confirm_password' ) {
                        $('#encoded_confirm_password').val(encrypted);
                    } else if ($(this).attr('id') === 'otp' ) {
                        $('#encoded_otp').val(encrypted);
                    }
                });
            });
        </script>
    </body>
    
</html>

