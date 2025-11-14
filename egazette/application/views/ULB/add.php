<?php defined('BASEPATH') OR exit('No direct script access allowed'); ?>
<!-- CONTENT -->
<section id="content">
    <div class="page page-forms-validate">
        <ol class="breadcrumb">
            <li><a href="<?php echo base_url(); ?>dashboard">Dashboard</a></li>
            <li><a href="<?php echo base_url(); ?>ulb">ULB</a></li>
            <li class="active">Add ULB</li>
        </ol>
        <!-- bradcome -->
        
        <!-- row -->
        <div class="row">
            <div class="col-md-12">
                <section class="boxs">
                    <div class="boxs-header">
                    <h3 class="custom-font hb-blue"><strong>Add ULB</strong></h3>
                    </div>
                    <?php echo form_open('ulb/add', array('name' => "form1", 'role' => "form", 'id' => "form1", 'method' => "post", 'enctype' => "multipart/form-data")); ?>    
                        <div class="boxs-body">
                                <div class="row">
                                    <div class="form-group col-md-4">
                                        <label for="username">State Name : </label>
                                        <select name="state_name" id="state_name" class="form-control" required>
                                            <option value="">Select State</option>
                                                <?php if (!empty($state_names)) { ?>
                                                    <option value="26">(StateName)</option>
                                                    <?php foreach ($state_names as $state) { ?>
                                                    <?php if($state->id != '26') { ?>
                                                    <option value="<?php echo $state->id; ?>"  ><?php echo $state->state_name; ?></option>
                                                    <?php } ?>
                                                <?php } ?>
                                            <?php } ?>
                                        </select>
                                        <?php if (form_error('state_name')) { ?>
                                            <span class="error"><?php echo form_error('state_name'); ?></span>
                                        <?php } ?>
                                    </div>
                                    <div class="form-group col-md-4">
                                        <label for="username">District Name : </label>
                                        <select name="district_name" id="district_name" class="form-control" required>
                                            <option value="">Select District</option>
                                        </select>
                                        <?php if (form_error('district_name')) { ?>
                                            <span class="error"><?php echo form_error('district_name'); ?></span>
                                        <?php } ?>
                                    </div>
                                    <div class="form-group col-md-4">
                                        <label for="ulbname">ULB Name : <span class="asterisk">*</span></label>
                                        <input type="text" name="ulb_name" id="ulb_name" class="form-control" required autocomplete="off">
                                        <?php if (form_error('ulb_name')) { ?>
                                            <span class="error"><?php echo form_error('ulb_name'); ?></span>
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
<!-- <script src="https://code.jquery.com/jquery-3.4.1.min.js" type="text/javascript" nonce="8f0882ce3be14f201cadd0eff5726cbd"></script> -->
<script src="https://ajax.googleapis.com/ajax/libs/jquery/3.5.1/jquery.min.js" nonce="8f0882ce3be14f201cadd0eff5726cbd"></script>
<script src="https://cdn.jsdelivr.net/npm/jquery-validation@1.19.3/dist/jquery.validate.js"></script>
<script src="https://cdn.jsdelivr.net/npm/jquery-validation@1.19.3/dist/additional-methods.min.js"></script>
<script src="https://cdn.jsdelivr.net/jquery.validation/1.16.0/jquery.validate.min.js"></script>
<script type="text/javascript" nonce="8f0882ce3be14f201cadd0eff5726cbd">
    $(document).ready(function () {
      

        $("#state_name").on('change', function () {
            if ($(this).val() !== "") {
                $.ajax({
                    type: "post",
                    url: '<?php echo base_url(); ?>' + "ulb/get_district",
                    data: {
                        'state_id' : $(this).val(),
                        '<?php echo $this->security->get_csrf_token_name(); ?>' : '<?php echo $this->security->get_csrf_hash(); ?>'
                    },
                    dataType: 'html',
                    // if success, returns success message
                    success: function (data) {
                        $('#district_name').html(data);
                    },
                    error: function (data) {

                    }
                });
            } else {
                $('#district_name').html('');
            }
        });


        //Take Albhabetic Character Only
        jQuery.validator.addMethod("lettersonly", function(value, element) {
        return this.optional(element) || /^[a-z\s]+$/i.test(value);
        }, "Please enter only alphabetical characters");


    $("#form1").validate({
        rules: {
            // state_name: {
            //     required: true
            // },
            district_name: {
                required: true
            },
            ulb_name: {
                required: true,
                minlength: 3,
                maxlength: 30,
                lettersonly:true
            },
        },
        messages: {
            state_name: {
                required: 'Please select state name'
            },
            district_name: {
                required: 'Please select district name'
            },
            ulb_name: {
                required: 'Please enter ULB name',
                minlength: 'ULB should be minimum 3 characters',
                maxlength: 'ULB should be maximum 30 characters',
            },
        },
    });
});
    

</script>

<script>
    var inactivityTimeout;

    function resetInactivityTimeout() {
        clearTimeout(inactivityTimeout);
        inactivityTimeout = setTimeout(function() {
            window.location.href = "<?php echo base_url('user/logout'); ?>";
        }, 15 * 60 * 1000); 
    }

    document.addEventListener("mousemove", resetInactivityTimeout);
    document.addEventListener("keypress", resetInactivityTimeout);

    // Initialize timeout on page load
    resetInactivityTimeout();
</script>