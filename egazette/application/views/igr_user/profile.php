<?php defined('BASEPATH') OR exit('No direct script access allowed'); ?>
<!-- CONTENT -->
<section id="content">
    <div class="page page-forms-validate">
        <!-- bradcome -->
        <div class="bg-light lter b-b wrapper-md mb-10">
            <div class="row">
                <div class="col-sm-6 col-xs-12">
                    <h1 class="h3 m-0">Profile Details</h1>
                </div>
            </div>
        </div>
        <!-- row -->
        <div class="row">
            <div class="col-md-12">
                <section class="boxs">
                    <!-- boxs header -->
                    <div class="boxs-header">
                        <h3 class="custom-font hb-blush">Applicant Profile</h3>
                    </div>
                    
                    <?php echo form_open('igr_user/profile', array('method' => 'post', 'id' => 'profile_form', 'role' => 'form', 'name' => 'profile_form')); ?>    
                        <div class="boxs-body">
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
                            <div class="row">
                                <div class="col-md-6">
                                    <div class="form-group mobile_number">
                                        <label for="website">Verifier/Approver : </label>
                                        <select name="module_id" id="module_id" class="form-control" disabled>
                                            <option value="<?php echo $this->session->userdata('is_verifier_approver'); ?>"><?php echo $this->session->userdata('is_verifier_approver'); ?></option>
                                        </select>
                                        <?php if (form_error('module_id')) { ?>
                                            <span class="error"><?php echo form_error('module_id'); ?></span>
                                        <?php } ?>
                                    </div>
                                </div>
                                <div class="form-group col-md-6">
                                    <label for="name">Name: </label>
                                    <input type="text" name="name" id="name" class="form-control alpha_only" required value="<?php echo $user_data->user_name; ?>" maxlength="30" autocomplete="off"/>
                                    <?php if (form_error('name')) { ?>
                                        <span class="error"><?php echo form_error('name'); ?></span>
                                    <?php } ?>
                                </div>
                            </div>
                            <div class="row">
                                
                                <div class="form-group col-md-6">
                                    <label for="website">Email: </label>
                                    <input type="email" name="email" id="email" class="form-control" value="<?php echo $user_data->email; ?>" required="" maxlength="96" autocomplete="off"/>
                                    <?php if (form_error('email')) { ?>
                                        <span class="error"><?php echo form_error('email'); ?></span>
                                    <?php } ?>
                                </div>
                                
                                <div class="form-group col-md-6">
                                    <label for="message">Mobile: </label>
                                    <input type="text" class="form-control number_only" name="mobile" id="mobile" value="<?php echo $user_data->mobile; ?>" required="" maxlength="10" autocomplete="off"/>
                                    <?php if (form_error('mobile')) { ?>
                                        <span class="error"><?php echo form_error('mobile'); ?></span>
                                    <?php } ?>
                                </div>
                            </div>
                        </div>
                        <div class="boxs-footer text-right bg-tr-black lter dvd dvd-top">
                            <button type="submit" class="btn btn-raised btn-success" id="form2Submit">Submit</button>
                        </div>
                    <?php echo form_close(); ?>
                </section>
            </div>
        </div>
    </div>
</section>
<script src="https://code.jquery.com/jquery-3.4.1.min.js" type="text/javascript" nonce="a6b9a780936c8e980939086f618dded2"></script>
<script type="text/javascript" nonce="a6b9a780936c8e980939086f618dded2">
    $(document).ready(function () {
        
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
        
        $("#profile_form").validate ({
            rules: {
                name: {
                    required: true,
                    minlength: 5
                },
                email: {
                    required: true,
                    email: true
                },
                mobile: {
                    required: true,
                    minlength: 10
                }
            },
            messages: {
                name: {
                    required: "Please enter name",
                    minlength: "Name should be atleast 5 characters"
                },
                email: {
                    required: "Please enter email",
                    email: "Please enter valid email"
                },
                mobile: {
                    required: "Please enter mobile number",
                    minlength: "Mobile number should be 10 characters"
                }
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