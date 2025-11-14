<?php defined('BASEPATH') OR exit('No direct script access allowed'); ?>
<!-- CONTENT -->
<section id="content">
    <div class="page page-forms-validate">
        <!-- bradcome -->
        <div class="bg-light lter b-b wrapper-md mb-10">
            <div class="row">
                <div class="col-sm-6 col-xs-12">
                    <h1 class="h3 m-0">HOD Officer</h1>
                </div>
            </div>
        </div>
        <!-- row -->
        <div class="row">
            <div class="col-md-12">
                <section class="boxs">
                    <div class="boxs-header">
                        <h3 class="custom-font hb-blush">Add HOD Officer</h3>
                    </div>
                    
                    <?php echo form_open('', array('name' => 'form1',  'id' => 'form1', 'method' => 'post')); ?>
                     <input type="hidden" name="hod_id" id="hod_id" value="<?php echo $hod_id; ?>">
                        <div class="boxs-body">
                            <div class="row">
                                <div class="form-group col-md-6">
                                    <label for="username">Name : </label>
                                    <input type="text" name="user_name" id="user_name" class="form-control" autocomplete="off" value="<?php echo $hod->name; ?>">
                                    <?php if (form_error('user_name')) { ?>
                                        <span class="error"><?php echo form_error('user_name'); ?></span>
                                    <?php } ?>
                                </div>
                                
                                <div class="form-group col-md-6">
                                    <label for="email">Email : </label>
                                    <input type="text" name="email" id="email" class="form-control"  autocomplete="off" value="<?php echo  $hod->email; ?>">
                                    <?php if (form_error('email')) { ?>
                                        <span class="error"><?php echo form_error('email'); ?></span>
                                    <?php } ?>
                                </div>
                            </div>
                            <div class="row">
                                <div class="form-group col-md-6">
                                    <label for="username">Department Name : </label>
                                    <select name="dept_id" id="dept_id" class="form-control" >
                                        <option value="">Select Department</option>
                                        <?php if (!empty($departments)) { ?>
                                            <?php foreach ($departments as $dept) { ?>
                                                <option value="<?php echo $dept->id; ?>" <?php if($hod->dept_id == $dept->id) { echo "selected"; } ?>><?php echo $dept->department_name; ?></option>
                                            <?php } ?>
                                        <?php } ?>
                                    </select>
                                    <?php if (form_error('dept_id')) { ?>
                                        <span class="error"><?php echo form_error('dept_id'); ?></span>
                                    <?php } ?>
                                </div>
                                <div class="form-group col-md-6">
                                    <label for="email">Mobile : </label>
                                    <input type="text" name="mobile" id="mobile" class="form-control"  autocomplete="off" value="<?php echo $hod->mobile; ?>" minlength="10" maxlength="10"> 
                                    <?php if (form_error('mobile')) { ?>
                                        <span class="error"><?php echo form_error('mobile'); ?></span>
                                    <?php } ?>
                                </div>
                            </div>
                            <div class="row">
                                <div class="form-group col-md-6">
                                    <label for="email">DOB: </label>
                                    <input type="text" name="dob" id="dob" class="form-control"  autocomplete="off" value="<?php  ?>">
                                    <?php if (form_error('dob')) { ?>
                                        <span class="error"><?php echo form_error('dob'); ?></span>
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
    
         $('input.alpha_only').keypress(function (e) {
           var regex = new RegExp("^[a-zA-Z\ ]$");
           var str = String.fromCharCode(!e.charCode ? e.which : e.charCode);
           if (regex.test(str)) {
               return true;
           }
           e.preventDefault();
           return false;
       });
       
       
    //alert('ok');
    $('#form1').validate({
        // initialize the plugin

        rules: {
            
            user_name: {
                required: true
            },
            email: {
                required: true
            },
            dept_id: {
                required: true
            },
            mobile: {
                required: true,
                digits: true,
                minlength: 10,
                maxlength: 10
            },
            dob: {
                required: true
            }
               
        },
        messages: {
            user_name: {
                required: "Please enter user name"
            },
            email: {
                required: "Please enter email"
            },
            dept_id: {
                required: "Please enter department",
            },
            mobile: {
                required: "Please enter mobile number",
                digits: "Mobile number should contain only digits",
                minlength: "Mobile number must be 10 digits",
                maxlength: "Mobile number maximum upto 10 digits"
            },
            dob: {
                required: "Please enter DOB",
            }
        },
        submitHandler: function (form) {
           //alert('ok');
           $.ajax({
               type: "POST",
               url: "<?php echo base_url(); ?>gazette_hod/update_hod_officers",
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