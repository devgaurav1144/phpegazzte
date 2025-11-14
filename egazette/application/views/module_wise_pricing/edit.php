<?php defined('BASEPATH') OR exit('No direct script access allowed'); ?>
<!--  CONTENT  -->
<section id="content">
    <div class="page page-tables-footable">
        <div class="b-b mb-10">
            <div class="row">
                <div class="col-sm-6 col-xs-12">
                    <h1 class="h3 m-0">Module Wise Pricing</h1>
                    <small class="text-muted"></small>
                </div>
            </div>
        </div>
        <div class="row">
            <div class="col-md-12">
                <section class="boxs">
                    <div class="boxs-header">
                        <h3 class="custom-font hb-blush">Add Module Wise Pricing</h3>
                    </div>
                    <?php echo form_open('', array('name' => "pricing", 'role' => "form", 'id' => "pricing", 'method' => "post", 'enctype' => "multipart/form-data")); ?>
                    <div class="boxs-body">
                        <?php if ($this->session->flashdata('success')) { ?>
                            <div class="alert alert-success alert-dismissable"><button aria-hidden="true" data-dismiss="alert" class="close" type="button">×</button><?php echo $this->session->flashdata('success'); ?></div>
                        <?php } ?>
                        <?php if ($this->session->flashdata('error')) { ?>
                            <div class="alert alert-success alert-dismissable"><button aria-hidden="true" data-dismiss="alert" class="close" type="button">×</button><?php echo $this->session->flashdata('error'); ?></div>
                        <?php } ?>
                        <div class="row">
                            <div class="form-group col-md-6">
                                <label for="module_name">Module Name : <span class="asterisk">*</span></label>
                                <select name="module_id" id="module_id" class="form-control" required="">
                                    <option value="">Select Module</option>
                                    <?php if(!empty($modules)){ ?>
                                        <?php foreach ($modules as $module){ ?>
                                            <option value="<?php echo $module->id; ?>" <?php if($sel_data->module_id == $module->id){ echo 'selected'; }else{ echo ''; } ?>><?php echo $module->module_name; ?></option>
                                        <?php } ?>
                                    <?php } ?>
                                </select>
                                <?php if (form_error('module_name')) { ?>
                                    <span class="error"><?php echo form_error('module_name'); ?></span>
                                <?php } ?>
                            </div>
                            <div class="form-group col-md-6">
                                <label for="pricing">Pricing Per Page : <span class="asterisk">*</span></label>
                                <input type="text" name="price_pp" id="price_pp" class="form-control number_only" required="required" autocomplete="off" maxlength="5" value="<?php echo $sel_data->pricing; ?>">
                                <?php if (form_error('price_pp')) { ?>
                                    <span class="error"><?php echo form_error('price_pp'); ?></span>
                                <?php } ?>
                            </div>
                        </div>
                    </div>
                    <input type="hidden" id="mod_id" name="mod_id" value="<?php echo $this->uri->segment(3); ?>">
                    <div class="boxs-footer text-right bg-tr-black lter dvd dvd-top">
                        <button type="submit" class="btn btn-raised btn-success" id="form4Submit">Submit</button>
                    </div>
                    <?php echo form_close(); ?>
                </section>
            </div>
        </div>
        
    </div>
</section>
<!--  /CONTENT  -->
<script src="https://code.jquery.com/jquery-3.4.1.min.js" type="text/javascript" nonce="a6b9a780936c8e980939086f618dded2"></script>
<script type="text/javascript" nonce="a6b9a780936c8e980939086f618dded2">
    $(document).ready(function () {
        $("#pricing").validate({
            rules: {
                module_id: {
                    required: true
                },
                price_pp: {
                    required: true
                }
            },
            messages: {
                module_id: {
                    required: "Please select module name"
                },
                price_pp: {
                    required: "Please enter pricing per page"
                }
            },
            submitHandler: function (form) {
                $.ajax({
                    type: "POST",
                    url: "<?php echo base_url(); ?>module_wise_pricing/edit_pricing",
                    //data: $('#shg_details_frm input[type=\'text\'], #shg_details_frm select, #shg_details_frm textarea, #shg_details_frm input[type="hidden"]'),
                    data: $(form).serialize(),
                    dataType: 'json',
                    success: function (json) {
                        if (json['redirect']) {
                            window.location = json['redirect'];
                        } else if (json['error']) {
                            if (json['error']['message']) {
                                $('#pricing').prepend('<div class="alert bg-danger"><button type="button" class="close" data-dismiss="alert"><span>Ã—</span><span class="sr-only">Close</span></button><span class="text-semibold"></span>' + json['error']['warning'] + '</div>');
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
         * Delete pricing
         */
        $('.delete_id').on('click', function(){
            var id = $(this).attr('id');
            if (confirm('Are you sure to delete the user')) {
                // make ajax request
                $.ajax({
                    type: "post",
                    url: '<?php echo base_url(); ?>' + "module_wise_pricing/delete",
                    data: {
                        'id' : id,
                        '<?php echo $this->security->get_csrf_token_name(); ?>' : '<?php echo $this->security->get_csrf_hash(); ?>'
                    },
                    // if success, returns success message
                    success: function (data) {
                        location.reload();
                    },
                    error: function (data) {
                        location.reload();
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