<?php include_once 'page_initialization.php'; ?>
<!DOCTYPE html>
<html class="no-js">
    <head>
        <meta charset="utf-8">
        <meta http-equiv="X-UA-Compatible" content="IE=edge">
        <meta name="viewport" content="width=device-width, initial-scale=1">
        <title>Contact Us | eGazette | Government of (StateName)</title>
        <meta charset="UTF-8">
        <meta name="viewport" content="initial-scale=1.0, maximum-scale=1.0, user-scalable=no" />
        <?php include_once 'website/include/header-scripts-style.php'; ?>
        <style rel="stylesheet" nonce="8f0882ce3be14f201cadd0eff5726cbd">
            .contact-head {
                margin-top: 0px;
            }
            .dbl-subtitle{
                color: rgb(20, 40, 119);
                font-family: 'Open Sans', sans-serif;
                font-weight: bold;
                font-size: 24px;
                position: absolute;
                margin-top: 13px;
            }
            .dbl-subtitle span:before {
                content: '';
                left: -10px;
            }
            .dbl-subtitle span:before {
                display: inline-block;
                position: relative;
                z-index: 1;
                width: 2px;
                height: 1.1em;
                top: 4px;
                background-color: currentColor;
                left: -10px;
            }
            .contact-head{
                border-bottom: 1px dotted #ddd;
            }
            .heading-title{
                color: #0075c3;
                font-family: 'Open Sans', sans-serif;
                font-size: 18px;
                margin-bottom: 10px;
                font-weight: 400;
                margin-top: 0px;
            }  
            .btn{
               background: #1067e9;
               color: #ffffff;
               background-color: #1067e9;
            }
            .headoffice{
                border-bottom: 1px dashed #ddd;
                padding-bottom: 20px;
                padding-right: 40px;
                margin-top: 58px;
            }
            .contact{
                position: relative;
                padding-left: 40px !important;
                margin-bottom: 22px;
            }
            .mail-box{
                position: relative;
                padding-left: 40px !important;
                margin-bottom: 0;
            }
            .placeholder{
                position: absolute;
                left: 0;
                top: 6px;
            }
            .call{
                position: absolute;
                left: 0;
                top: -1px;
            }
            .mail{
                position: absolute;
                left: 0;
                top: 3px;
            }
            .BDC_CaptchaIconsDiv {
                position: absolute;
                top: 11px;
                margin-left: 14px !important;
                right: 80px;
            }
            .submit-button {
                margin-top: 27px;
            }
            .sub-heading2{
                font-size: 18px;
            }
            .head-office{
                padding-right: 40px; 
            }
            .feedback-form {
                border: 1px solid #f3f2f2;
                box-shadow: 0px 0px 6px 6px rgb(224 224 225 / 19%);
                padding: 30px;
                background: #ffffff;
                width: 100%;
                float: left;
            }
            .address .contact:last-child {
                margin-bottom: 0;
            }
            .map{
                width: 100%;
                margin-top: 40px;
            }
            .head-office ul li {
                text-align: left;
            }
            .BDC_CaptchaDiv{
                display: flex;
                flex-direction: row;
            }
            .mandatory{
                color: red;
            }
            .BDC_CaptchaDiv {
                overflow: visible !important;
                margin: 5px auto 10px !important;
            }
            .error{
                color: #D9534F;
            }
            .success {
                color: #28a745;
            }
            .alert {
                font-family: 'Open Sans', sans-serif;
                font-size: 15px;
            }
            @media screen and (max-width: 1199px){
                .feedback-form {
                    margin-top: 30px;
                }
            }
           
        </style>
    </head>
    <body class="home-body">
        <?php include_once 'website/include/header-menu.php'; ?>
        <section class="inner-banner">
            <div class="container">
                <h1>Contact Us</h1>
            </div>
        </section>
        <section class="breadcumb-wrapper" id="skip-to-main">
            <div class="container">
                <div class="row">
                    <div class="col-lg-12">
                        <div class="breadcumb">
                            <ul>
                                <li><a href="<?php echo base_url(); ?>" rel="noopener noreferrer">Home</a><span> &gt;</span></li>
                                <li>Contact Us</li>
                            </ul>
                        </div>
                    </div>
                </div>
            </div>
        </section>
        <section class="main-container">
            <div class="container">
                <div class="row">
                    <div class="feedback container">
                        <div class="col-lg-5 col-md-12 col-sm-12 col-xs-12 fadeInBottom locater">
                            <div class="dbl-subtitle">
                                <span>Get in Touch</span>
                            </div>
                            <div class="head-office headoffice" class="contact-head d-flex align-items-center">
                                <h3 class="sub-heading2">Head Office</h3>
                                <ul class="address">
                                    <li class="contact"><img class="placeholder" src="<?php echo base_url(); ?>assets/frontend/images/pin (1).png" alt=""> Director, Printing, Stationery and Publication, Madhupatna, Cuttack-753010, (StateName)</li>
                                    <li class="contact"><img class="call" src="<?php echo base_url(); ?>assets/frontend/images/phone.png" alt=""> 0671-2344732</li>
                                    <li class="mail-box"><img class="mail" src="<?php echo base_url(); ?>assets/frontend/images/mail (1).png" alt=""> directoratepsp[at]gmail[dot]com | deputydirectorpp[at]rediffmail[dot]com</li>
                                </ul>
                            </div>
                            <div class="head-office right">
                                <h3 class="sub-heading2">Secretariat Branch Office</h3>
                                <ul class="address">
                                    <li class="contact"><img class="placeholder" src="<?php echo base_url(); ?>assets/frontend/images/pin (1).png" alt=""> Secretariat Branch Press, UNIT-3, At.: Kharvelnagar, P.O.: Bhubaneswar, Dist: Khurda</li>
                                    <li class="contact"><img class="call" src="<?php echo base_url(); ?>assets/frontend/images/phone.png" alt=""> 0674-2392924</li>
                                    <li class="contact"><img class="mail" src="<?php echo base_url(); ?>assets/frontend/images/mail (1).png" alt=""> secretariatpressbbsr[at]gmail[dot]com</li>
                                </ul>
                            </div>
                        </div>      
                        <div class="col-lg-7">
                            <div class="feedback-form">
                            <?php if ($this->session->flashdata('success')) { ?>
                                <div class="alert alert-success alert-dismissible fade in">
                                    <a href="#" class="close" data-dismiss="alert" aria-label="close">&times;</a>
                                    <?php echo $this->session->flashdata('success'); ?>
                                </div>
                            <?php } ?>
                            <?php if ($this->session->flashdata('error')) { ?>
                                <div class="alert alert-danger alert-dismissible fade in">
                                    <a href="#" class="close" data-dismiss="alert" aria-label="close">&times;</a>
                                    <?php echo $this->session->flashdata('error'); ?>
                                </div>
                            <?php } ?>
                            <h3 class="heading-title">Send Message</h3>
                                <div class="row">
                                    <div class="content-part">
                                    <?php echo validation_errors(); ?>
                                        <?php echo form_open('contact_email', array('class' => 'form', 'method' => 'post', 'id' => 'contact_form'))?>
                                            <div class="row">
                                                <div class="col-lg-6 col-md-6 col-sm-6 col-xs-12 marg">
                                                    <div class="search-gazette">
                                                        <div class="form-group">
                                                            <label class="margin-bottom">Name <span class="mandatory">*</span></label>
                                                            <input type="text" placeholder="Name" class="form-control" name="name" id="name" autocomplete="off" value="<?php echo set_value('name'); ?>" maxlength="30" >
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
                                                            <input type="text" placeholder="Email" class="form-control" name="email" id="email" autocomplete="off" value="<?php echo set_value('email'); ?>" maxlength="96" >
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
                                                            <input type="text" placeholder="Mobile No" class="form-control" name="mobile" id="mobile" autocomplete="off" value="<?php echo set_value('mobile'); ?>" maxlength="10" >
                                                            <?php if (form_error('mobile')) { ?>
                                                                <span class="error"><?php echo form_error('mobile'); ?></span>
                                                            <?php } ?>
                                                        </div>
                                                    </div>
                                                </div>
                                                <div class="col-lg-6 col-md-6 col-sm-6 col-xs-12 marg" id="subjectID">
                                                    <div class="search-gazette">
                                                        <div class="form-group">
                                                            <label class="margin-bottom">Subject <span class="mandatory">*</span></label>
                                                            <input type="text" placeholder="Subject" class="form-control" name="subject" id="subject" autocomplete="off" value="<?php echo set_value('subject'); ?>" maxlength="100" >
                                                            <?php if (form_error('subject')) { ?>
                                                                <span class="error"><?php echo form_error('subject'); ?></span>
                                                            <?php } ?> 
                                                        </div>
                                                    </div>
                                                </div>
                                                <div class="col-lg-12 col-md-12 col-sm-12 col-xs-12">
                                                    <div class="row">
                                                        <div class="col-lg-12 col-md-12 col-sm-12 col-xs-12 marg" id="keywordID">
                                                            <div class="search-gazette">
                                                                <div class="form-group">
                                                                    <label class="margin-bottom">Message <span class="mandatory">*</span></label>
                                                                    <textarea placeholder="Message" class="form-control" name="message" id="message" rows="5" maxlength="800" autocomplete="off" ></textarea>        
                                                                    <?php if (form_error('message')) { ?>
                                                                        <span class="error"><?php echo form_error('message'); ?></span>
                                                                    <?php } ?>
                                                                </div>
                                                            </div>
                                                        </div>
                                                        <div class="col-lg-6 col-md-6 col-sm-6 col-xs-12 marg">
                                                            <?php echo $captcha; ?>
                                                            <label class="margin-bottom">Captcha <span class="mandatory">*</span></label>
                                                            <input type="text" name="captcha" class="form-control" id="captcha" autocomplete="off" maxlength="6" placeholder="Enter Captcha"  >
                                                        </div>
                                                        <div class="col-lg-12 col-ms-12 col-sm-12 col-xs-12">
                                                            <div class="submit-button">
                                                                <!-- <input type="submit" class="hvr-shutter-out-horizontal middle-btm"> -->
                                                                <input type="submit" class="btn btn-info btn-raised" name="submit" value="Submit"/>
                                                            </div>
                                                        </div>
                                                    </div>
                                                </div>
                                            </div>
                                        </div>
                                    <?php echo form_close(); ?>
                                </div>
                            </div>
                        </div>
                    </div>
                </div>
                <iframe class="map" src="https://www.google.com/maps/embed?pb=!1m14!1m8!1m3!1d14953.575777184677!2d85.9020305!3d20.4490068!3m2!1i1024!2i768!4f13.1!3m3!1m2!1s0x0%3A0x98693ebb7deaa91a!2sDirectorate%20of%20Printing%2C%20Stationery%20%26%20Publication!5e0!3m2!1sen!2sin!4v1676963315753!5m2!1sen!2sin" width="600" height="450" style="border:0;" allowfullscreen="" loading="lazy" referrerpolicy="no-referrer-when-downgrade"></iframe>
            </div>
        </div>
    </section>
        <?php include_once 'website/include/footer.php'; ?>
        <?php include_once 'website/include/script.php'; ?>

        <!--  Vendor JavaScripts -->
        <!-- <script src="<?php echo base_url(); ?>assets/bundles/libscripts.bundle.js"></script>
        <script src="<?php echo base_url(); ?>assets/bundles/mainscripts.bundle.js"></script> -->
        <script src="<?php echo base_url(); ?>assets/js/jquery.validate.min.js"></script>
        <!-- <script src="<?php echo base_url(); ?>assets/js/additional-methods.min"></script> -->
        <!-- Custom Js -->
        <script type="text/javascript" nonce="a6b9a780936c8e980939086f618dded2">
            $.validator.addMethod("lettersonly", function(value, element) {
                return this.optional(element) || /^[A-Z\s]+$/i.test(value);
            }, "Only alphabetical characters");

            $("#contact_form").validate({
                rules: {
                    name: {
                        required: true,
                        minlength: 4,
                        maxlength: 40
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
                    subject: {
                        required: true,
                        minlength: 5,
                        maxlength: 100
                    },
                    message: {
                        required: true,
                        minlength: 5,
                        maxlength: 200
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
                    subject: {
                        required: 'Please enter subject',
                        minlength: 'Subject should be minimum 5 characters',
                        maxlength: 'Subject should be maximum 100 characters'
                    },
                    message: {
                        required: 'Please enter Message',
                        minlength: 'Message should be minimum 5 characters',
                        maxlength: 'Message should be maximum 200 characters'
                    },
                    captcha:{
                        required: 'Please enter captcha'
                    }
                }
            });
        </script>
    </body>
</html>

