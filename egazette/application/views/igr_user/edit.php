<?php defined('BASEPATH') OR exit('No direct script access allowed'); ?>
<!-- CONTENT -->
<section id="content">
    <div class="page page-forms-validate">
    <ol class="breadcrumb">
            <li><a href="<?php echo base_url(); ?>dashboard">Dashboard</a></li>
            <li><a href="<?php echo base_url(); ?>igr_user">IGR Users</a></li>
            <li class="active">Edit IGR User</li>
        </ol>
        <!-- bradcome -->

        <!-- row -->
        <div class="row">
            <div class="col-md-12">
                <section class="boxs">
                    <div class="boxs-header">
                        <h3 class="custom-font hb-blue"><strong>Edit IGR Users</strong></h3>
                    </div>
                    
                    <?php echo form_open('', array('name' => 'add_user',  'id' => 'add_user', 'method' => 'post')); ?>
                        <div class="boxs-body">
                            <div class="row">
                                <div class="form-group col-md-6">
                                    <label for="module_name">Verifier/Approver : <span class="asterisk">*</span></label>
                                    <select name="module_id" id="module_id" class="form-control" required="">
                                        <option value="">Select</option>
                                        <option value="Verifier">Verifier</option>
                                        <option value="Approver">Approver</option>
                                        
                                    </select>
                                    <?php if (form_error('module_name')) { ?>
                                        <span class="error"><?php echo form_error('module_name'); ?></span>
                                    <?php } ?>
                                </div>
                            </div>
                            <div class="row">
                                <div class="form-group col-md-6">
                                    <label for="username">Name : <span class="asterisk">*</span></label>
                                    <input type="text" name="user_name" id="user_name" class="form-control alpha_only" autocomplete="off" value="<?php echo $users->user_name; ?>">
                                    <?php if (form_error('user_name')) { ?>
                                        <span class="error"><?php echo form_error('user_name'); ?></span>
                                    <?php } ?>
                                </div>
                                
                                <div class="form-group col-md-6">
                                    <label for="email">Email : <span class="asterisk">*</span></label>
                                    <input type="text" name="email" id="email" class="form-control"  autocomplete="off" value="<?php echo $users->email; ?>">
                                    <?php if (form_error('email')) { ?>
                                        <span class="error"><?php echo form_error('email'); ?></span>
                                    <?php } ?>
                                </div>
                            </div>
                            <div class="row">
                                <div class="form-group col-md-6">
                                    <label for="email">Mobile : <span class="asterisk">*</span></label>
                                    <input type="text" name="mobile" id="mobile" class="form-control number_only"  autocomplete="off" value="<?php echo $users->mobile; ?>" minlength="10" maxlength="10"> 
                                    <?php if (form_error('mobile')) { ?>
                                        <span class="error"><?php echo form_error('mobile'); ?></span>
                                    <?php } ?>
                                </div>
                                <div class="form-group col-md-6">
                                    <label for="email">DOB: <span class="asterisk">*</span></label>
                                    <input type="text" name="dob" id="dob" class="form-control report_date"  autocomplete="off" value="<?php echo $users->date_of_birth; ?>">
                                    <?php if (form_error('dob')) { ?>
                                        <span class="error"><?php echo form_error('dob'); ?></span>
                                    <?php } ?>
                                </div>
                            </div>
                        </div>
                    <input type="hidden" id="user_id" name="user_id" value="<?php echo $this->uri->segment(3); ?>">
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
    
    $("#module_id").val('<?php echo $users->verify_approve; ?>');
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
     * Allowd number input only using class number_only in the input field
     */
    $('input.number_only').keyup(function (e) {
        if (/\D/g.test(this.value)) {
            // Filter non-digits from input value.
            this.value = this.value.replace(/\D/g, '');
        }
    });
    
    /*
     * Email validation
     */
    jQuery.validator.addMethod("email", function (value, element) {
        return this.optional(element) || /^([A-Za-z0-9_\-\.])+\@([A-Za-z0-9_\-\.])+\.([A-Za-z]{2,4})$/i.test(value);
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
    
    
    $('#add_user').validate({
        rules: {
            module_id: {
                required: true  
            },
            user_name: {
                required: true,
                minlength: 4
            },
            email: {
                required: true,
                email: true
            },
            mobile: {
                required: true,
                digits: true,
                minlength: 10,
                maxlength: 10
            },
            dob: {
                required: true,
                check_date_of_birth: true
            }
        },
        messages: {
            module_id: {
                required: "Please select verifier/approver"  
            },
            user_name: {
                required: "Please enter user name",
                minlength: "Name must be atleast 4 characters"
            },
            email: {
                required: "Please enter email",
                email: "Please enter valid email"
            },
            mobile: {
                required: "Please enter mobile number",
                digits: "Mobile number should contain only digits",
                minlength: "Mobile number must be 10 digits",
                maxlength: "Mobile number maximum upto 10 digits"
            },
            dob: {
                required: "Please enter DOB",
                check_date_of_birth: "User must be 18 years old"
            }
        },
        submitHandler: function (form) {
           //alert('ok');
           $.ajax({
               type: "POST",
               url: "<?php echo base_url(); ?>igr_user/edit_igr_user",
               //data: $('#shg_details_frm input[type=\'text\'], #shg_details_frm select, #shg_details_frm textarea, #shg_details_frm input[type="hidden"]'),
               data: $(form).serialize(),
               dataType: 'json',
               success: function (json) {
                   if (json['redirect']) {
                       window.location = json['redirect'];
                   } else if (json['error']) {
                       if (json['error']['message']) {
                           $('#add_user').prepend('<div class="alert bg-danger"><button type="button" class="close" data-dismiss="alert"><span>Ã—</span><span class="sr-only">Close</span></button><span class="text-semibold"></span>' + json['error']['warning'] + '</div>');
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