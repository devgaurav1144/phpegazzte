<?php defined('BASEPATH') OR exit('No direct script access allowed'); ?>
<!-- CONTENT -->
<section id="content">
    <div class="page page-forms-validate">
        <!-- bradcome -->
        <ol class="breadcrumb">
            <li><a href="<?php echo base_url(); ?>applicants_login/dashboard">Dashboard</a></li>
            <li class="active">Change Password</li>
            <!-- <li class="active">Add</li> -->
        </ol>

        <div class="bg-light lter b-b wrapper-md mb-10">
            <div class="row">
                <div class="col-sm-6 col-xs-12">
                    <h1 class="h3 m-0">Change Password</h1>
                </div>
            </div>
        </div>
        <!-- row -->
        <div class="row">
            <div class="col-md-12">
                <section class="boxs">
                    <div class="boxs-header">
                        <h3 class="custom-font hb-blush">Change Password</h3>
                    </div>
                    <?php if ($this->session->flashdata('success')) { ?>
                        <div class="alert alert-success alert-dismissable"><button aria-hidden="true" data-dismiss="alert" class="close" type="button">×</button>
                            <?php echo $this->session->flashdata('success'); ?>
                        </div> 
                    <?php } ?>
                    <?php if ($this->session->flashdata('error')) { ?>
                        <div class="alert alert-danger alert-dismissable"><button aria-hidden="true" data-dismiss="alert" class="close" type="button">×</button>
                            <?php echo $this->session->flashdata('error'); ?>
                        </div> 
                    <?php } ?>
                    
                    <?php echo form_open('applicants_login/change_password', array('method' => 'post', 'id' => 'change_password', 'role' => 'form', 'name' => 'change_password')); ?>
                        <input type="hidden" name="user_id" value="<?php echo $this->session->userdata('user_id'); ?>"/>
                        <div class="boxs-body">
                            <div class="row">
                                <div class="form-group col-md-4">
                                    <label for="username">Current Password : </label>
                                    <input type="password" name="old_password" id="old_password" class="form-control" required autocomplete="off" value="<?php echo set_value('old_password'); ?>" onpaste="return false;" oncopy="return false;" oncut="return false;">
                                    <?php if (form_error('old_password')) { ?>
                                        <span class="error"><?php echo form_error('old_password'); ?></span>
                                    <?php } ?>
                                </div>
                                <div class="form-group col-md-4">
                                    <label for="username">New Password : </label>
                                    <input type="password" name="password" id="password" class="form-control pass_vali" required autocomplete="off" value="<?php echo set_value('password'); ?>" onpaste="return false;" oncopy="return false;" oncut="return false;">
                                    <?php if (form_error('password')) { ?>
                                        <span class="error"><?php echo form_error('password'); ?></span>
                                    <?php } ?>
                                </div>
                                <div class="form-group col-md-4">
                                    <label for="email">Confirm Password : </label>
                                    <input type="password" name="match_password" id="match_password" class="form-control pass_vali" required autocomplete="off" value="<?php echo set_value('match_password'); ?>" onpaste="return false;" oncopy="return false;" oncut="return false;">
                                    <?php if (form_error('match_password')) { ?>
                                        <span class="error"><?php echo form_error('match_password'); ?></span>
                                    <?php } ?>
                                </div>
                            </div>
                                    <input type="hidden" name="encoded_old_password" id="encoded_old_password">
                                    <input type="hidden" name="encoded_password" id="encoded_password">
                                    <input type="hidden" name="encoded_match_password" id="encoded_match_password">
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
<script src="<?php echo base_url(); ?>assets/js/crypto-js.min.js" type="text/javascript" nonce="a6b9a780936c8e980939086f618dded2"></script>
<script src="<?php echo base_url(); ?>assets/js/Encryption.js?>" type="text/javascript" nonce="a6b9a780936c8e980939086f618dded2"></script>

<script type="text/javascript" nonce="a6b9a780936c8e980939086f618dded2">
    $(document).ready(function () {
        $.validator.addMethod("pwcheck", function(value) {
            return /^[A-Za-z0-9\d=!\!-@._*#$&]*$/.test(value) // consists of only these
                && /[A-Z]/.test(value) // has a lowercase letter
                && /[a-z]/.test(value) // has a lowercase letter
                && /\d/.test(value) // has a digit
                && /[!-@._*#$&]/.test(value); // has a digit
        });
        
        $("#change_password").validate({
            rules: {
                old_password: {
                    required: true,
                    minlength: 4,
                    maxlength: 16
                },
                password: {
                    required: true,
                    pwcheck: true,
                    minlength: 6,
                    maxlength: 16
                },
                match_password: {
                    required: true,
                    equalTo: "#password",
                    minlength: 6,
                    maxlength: 16
                }
            },
            messages: {
                old_password: {
                    required: 'Please enter current password',
                    minlength: 'Password minimum of 4characters',
                    maxlength: 'Password maximum of 16 characters'
                },
                password: {
                    required: 'Please enter new password',
                    pwcheck: 'Password must contain a upper case, lower case character & a digit & special characters (A-Za-z0-9!-@._*#$&)',
                    minlength: 'Password minimum of 6 characters',
                    maxlength: 'Password maximum of 16 characters'
                },
                match_password: {
                    required: 'Please enter confirm password',
                    equalTo: 'Password & comfirm password are not same',
                    minlength: 'Password minimum of 6 characters',
                    maxlength: 'Password maximum of 16 characters'
                }
            }
            // ,
            // submitHandler: function(form) {
            //     // Hash only the new password and confirm password
            //     var newPasswordHash = CryptoJS.SHA256($('#password').val()).toString();
            //     var confirmPasswordHash = CryptoJS.SHA256($('#match_password').val()).toString();
                
            //     // Set the hashed values to their respective fields
            //     $('#password').val(newPasswordHash);
            //     $('#match_password').val(confirmPasswordHash);
                
            //     // Submit the form
            //     form.submit();
            // }
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

<script type="text/javascript" nonce="a6b9a780936c8e980939086f618dded2">
    $(document).ready(function(){
        $("#password, #old_password, #match_password").on("input", function(){
            // Encrypt password objects
            var readableString = $(this).val();
            var nonceValue = 'lol'; // You can replace 'lol' with a nonce value from your application
            
            var encryption = new Encryption();
            var encrypted = encryption.encrypt(readableString, nonceValue);
            
            // Set the encrypted value to corresponding hidden fields
            if ($(this).attr('id') === 'password') {
                $("#encoded_password").val(encrypted);
            } else if ($(this).attr('id') === 'old_password') {
                $("#encoded_old_password").val(encrypted);
            } else if ($(this).attr('id') === 'match_password') {
                $("#encoded_match_password").val(encrypted);
            }
        });
    });

    // $("#change_password").submit(function(event) {
    //     // Prevent the default form submission
    //     event.preventDefault();

    //     // Submit the form with encrypted values
    //     $.ajax({
    //         type: $(this).attr('method'),
    //         url: $(this).attr('action'),
    //         data: $(this).serialize(), // Serialize the form data including encrypted values
    //         success: function(response) {
    //             // Handle success response
    //             console.log(response);
    //         },
    //         error: function(xhr, status, error) {
    //             // Handle error
    //             console.error(xhr.responseText);
    //         }
    //     });
    // });
</script>

