<?php defined('BASEPATH') OR exit('No direct script access allowed'); ?>
<!-- CONTENT -->
<section id="content">
    <div class="page page-forms-validate">
        <ol class="breadcrumb">
            <li><a href="<?php echo base_url(); ?>dashboard">Dashboard</a></li>
            <li><a href="<?php echo base_url(); ?>commerce_transport_department">C&T User</a></li>
            <li class="active">Add C&T User</li>
        </ol>
        <!-- bradcome -->

        <!-- row -->
        <div class="row">
            <div class="col-md-12">
                <section class="boxs">
                    <div class="boxs-header">
                        <h3 class="custom-font hb-blue"><strong>Add C&T User</strong></h3>
                    </div>
                    <?php echo form_open('', array('name' => 'form1',  'id' => 'form1', 'method' => 'post')); ?>
                        <div class="boxs-body">
                             <div class="row">
                                <div class="form-group col-md-6">
                                    <label for="module_name">Verifier/Approver/Processor : <span class="asterisk">*</span></label>
                                    <select name="ver_app" id="ver_app" class="form-control" required="">
                                        <option value="">Select Verifier/Approver/Processor</option>
                                        <option value="Verifier">Verifier</option>
                                        <option value="Approver">Approver</option>
                                        <option value="Processor">Processor</option>
                                    </select>
                                    <?php if (form_error('module_name')) { ?>
                                        <span class="error"><?php echo form_error('module_name'); ?></span>
                                    <?php } ?>
                                </div>
                            </div>
                            <div class="row">
                                <div class="form-group col-md-6">
                                    <label for="username">Name :  <span class="asterisk">*</span></label>
                                    <input type="text" name="name" id="name" class="form-control alpha_only" autocomplete="off" value="<?php echo set_value('user_name'); ?>" minlength="4" maxlength="50">
                                    <?php if (form_error('user_name')) { ?>
                                        <span class="error"><?php echo form_error('user_name'); ?></span>
                                    <?php } ?>
                                </div>
                                <div class="form-group col-md-6">
                                    <label for="username">User Name : <span class="asterisk">*</span></label></label>
                                    <input type="text" name="username" id="username" class="form-control alpha_only"  autocomplete="off" value="<?php echo set_value('username'); ?>"  minlength="4" maxlength="50">
                                    <?php if (form_error('username')) { ?>
                                        <span class="error"><?php echo form_error('username'); ?></span>
                                    <?php } ?>
                                </div>
                                <div class="form-group col-md-6">
                                    <label for="email">Email :  <span class="asterisk">*</span></label>
                                    <input type="text" name="email" id="email" class="form-control"  autocomplete="off" value="<?php echo set_value('email'); ?>"  minlength="6" maxlength="96">
                                    <?php if (form_error('email')) { ?>
                                        <span class="error"><?php echo form_error('email'); ?></span>
                                    <?php } ?>
                                </div>
                           
                                <div class="form-group col-md-6">
                                    <label for="email">Mobile :  <span class="asterisk">*</span></label>
                                    <input type="text" name="mobile" id="mobile" class="form-control number_only"  autocomplete="off" value="<?php echo set_value('mobile'); ?>" minlength="10" maxlength="10"> 
                                    <?php if (form_error('mobile')) { ?>
                                        <span class="error"><?php echo form_error('mobile'); ?></span>
                                    <?php } ?>
                                </div>
                                <!--<div class="form-group col-md-6">
                                        <label for="email">Employee ID (HRMS): <span class="asterisk">*</span></label></label>
                                        <input type="text" name="emp_id" id="emp_id" class="form-control" autocomplete="off" value="<?php //echo set_value('emp_id'); ?>"  minlength="4" maxlength="50">
                                        <?php //if (form_error('emp_id')) { ?>
                                            <span class="error"><?php //echo form_error('emp_id'); ?></span>
                                        <?php //} ?>
                                    </div>-->
                                <!--<div class="form-group col-md-6">
                                    <label for="email">DOB:  <span class="asterisk">*</span></label>
                                    <input type="text" name="dob" id="dob" class="form-control report_date "  autocomplete="off" value="<?php //echo set_value('dob'); ?>">
                                    <?php //if (form_error('dob')) { ?>
                                        <span class="error"><?php //echo form_error('dob'); ?></span>
                                    <?php //} ?>
                                </div>-->
                                
                                <div class="form-group col-md-6">
                                    <label for="email">Module:  <span class="asterisk">*</span></label>
                                    <select name="module_id" id="module_id" class="form-control" required="">
                                     <option value="">Select Module</option>
                                     <?php if(!empty($modules)){ ?>
                                         <?php foreach ($modules as $module){ ?>
                                             <option value="<?php echo $module->id; ?>"><?php echo $module->module_name; ?></option>
                                         <?php } ?>
                                     <?php } ?>
                                    </select>
                                    <?php if (form_error('module_id')) { ?>
                                        <span class="error"><?php echo form_error('module_id'); ?></span>
                                    <?php } ?>
                                </div>
                                
                            </div>
                        </div>
                        <div class="boxs-footer text-right bg-tr-black lter dvd dvd-top">
                            <button type="submit" class="btn btn-raised btn-success" id="form4Submit">Submit</button>
                        </div>
                    <?php echo form_close(); ?>
                </section>
            </div>
        </div>
    </div>
</section>
<!--/ CONTENT -->

<script src="https://code.jquery.com/jquery-3.4.1.min.js" type="text/javascript" nonce="a6b9a780936c8e980939086f618dded2"></script>
<script type="text/javascript" nonce="a6b9a780936c8e980939086f618dded2">
    
$(document).ready(function () {
    
    
      /*
        * Restrict alphabetic input only using class number_only in the input field
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
        * Restrict number input only using class number_only in the input field
        */
       $('input.number_only').keyup(function (e) {
           if (/\D/g.test(this.value)) {
               // Filter non-digits from input value.
               this.value = this.value.replace(/\D/g, '');
           }
       });
       
    // $("#ver_app").on('change', function () {
    //     var id = $(this).val();
    //     if (id == 'Processor') {
    //         $("#module_id").val('2');
    //         $('#module_id option:not(:selected)').attr('disabled', true);
    //     } else {
    //         $("#module_id").val('');
    //         $('#module_id option').attr('disabled', false);
    //     }
    // });
    
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
        * Restrict alphabetic input only using class number_only in the input field
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
     * Date of birth validation
     */
    $.validator.addMethod("check_date_of_birth", function(value, element) {
        var age = 18;
        var date = value.split("-");
        var year = date[0];
        var month = date[1];
        var day = date[2];
        var mydate = new Date();
        mydate.setFullYear(year, month-1, day);

        var currdate = new Date();
        currdate.setFullYear(currdate.getFullYear() - age);
        return currdate > mydate;
    });
       
       
    //alert('ok');
    $('#form1').validate({
        // initialize the plugin

        rules: {
            ver_app: {
				required: true,
			},
            name: {
                required: true,
                minlength: 4,
                maxlength: 50
                
            },
            email: {
                required: true,
                 minlength: 6,
                maxlength: 96
            },
            username: {
                required: true,
                minlength: 4,
                maxlength: 50
            },
            emp_id: {
                required: true,
                minlength: 4,
                maxlength: 50
            },
            mobile: {
                required: true,
                digits: true,
                minlength: 10,
                maxlength: 10
            },
			module_id: {
				required: true
			}
            //dob: {
            //    required: true,
            //    check_date_of_birth : true
            //}
               
        },
        messages: {
			ver_app: {
				required: 'Please select Type',
			},
            name: {
                required: "Please enter name",
                minlength: "Please enter minimum 4 characters",
                maxlength: "Please enter maximum 50 characters"
            },
            email: {
                required: "Please enter email",
                minlength: "Please enter minimum 4 characters",
                maxlength: "Please enter maximum 96 characters"
            },
            username: {
                required: "Please enter user name",
                minlength: "Please enter minimum 4 characters",
                maxlength: "Please enter maximum 50 characters"
            },
            emp_id: {
                required: "Please enter employee id",
                minlength: "Please enter minimum 4 characters",
                maxlength: "Please enter maximum 50 characters"
            },
            mobile: {
                required: "Please enter mobile number",
                digits: "Mobile number should contain only digits",
                minlength: "Mobile number must be 10 digits",
                maxlength: "Mobile number maximum upto 10 digits"
            },
			module_id: {
				required: 'Please select module'
			}
            //dob: {
            //    required: "Please enter DOB",
            //    check_date_of_birth: "User must be 18 years old"
            //}
        },
        submitHandler: function (form) {
           //alert('ok');
           $.ajax({
               type: "POST",
               url: "<?php echo base_url(); ?>Commerce_transport_department/add_candt_users",
               //data: $('#shg_details_frm input[type=\'text\'], #shg_details_frm select, #shg_details_frm textarea, #shg_details_frm input[type="hidden"]'),
               data: $(form).serialize(),
               dataType: 'json',
               success: function (json) {
                   if (json['redirect']) {
                       window.location = json['redirect'];
                   } else if (json['error']) {
                       if (json['error']['message']) {
                           $('#shg_details_frm').prepend('<div class="alert bg-danger"><button type="button" class="close" data-dismiss="alert"><span>Ã—</span><span class="sr-only">Close</span></button><span class="text-semibold"></span>' + json['error']['warning'] + '</div>');
                       }

                       for (i in json['error']) {
                           var element = $('#' + i);
                           $(element).parent().find('.error').remove();
                           $(element).after('<span class="error">' + json['error'][i] + '</span>');
                       }
                   } else {
                       $(element).parent().find('.error').remove();
                   }
               }
           });
       }
        
    }); 
    
}); 

</script>

<?php
    $userLocation = '';
    if ($this->session->userdata('is_applicant')) {
        $userLocation = 'applicants_login/logout';
    } else if ($this->session->userdata('is_igr')) {
        $userLocation = 'Igr_user/logout';
    } else if ($this->session->userdata('is_c&t')) {
        $userLocation = 'Commerce_transport_department/logout';
    } else {
        $userLocation = 'user/logout';
    }
?>
<script>
    var inactivityTimeout;

    function resetInactivityTimeout() {
        clearTimeout(inactivityTimeout);
        inactivityTimeout = setTimeout(function() {
            window.location.href = "<?php echo base_url($userLocation); ?>";
        }, 15 * 60 * 1000); 
    }

    document.addEventListener("mousemove", resetInactivityTimeout);
    document.addEventListener("keypress", resetInactivityTimeout);

    // Initialize timeout on page load
    resetInactivityTimeout();
</script>