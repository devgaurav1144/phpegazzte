<?php defined('BASEPATH') OR exit('No direct script access allowed'); ?>
<!-- CONTENT -->
<section id="content">
    <div class="page page-forms-validate">
        <ol class="breadcrumb">
            <li><a href="<?php echo base_url(); ?>dashboard">Dashboard</a></li>
            <li><a href="<?php echo base_url(); ?>district">Districts</a></li>
            <li class="active">Edit District</li>
        </ol>
        <!-- bradcome -->
        <!-- <div class="bg-light lter b-b wrapper-md mb-10">
            <div class="row">
                <div class="col-sm-6 col-xs-12">
                    <h1 class="h3 m-0">Edit District</h1>
                </div>
            </div>
        </div> -->
        <!-- row -->
        <div class="row">
            <div class="col-md-12">
                <section class="boxs">
                    <div class="boxs-header">
                        <h3 class="custom-font hb-blue"><strong>Edit District</strong></h3>
                    </div>
                    <?php echo form_open('district/edit/' . $ds_dtls->id, array('name' => "form1", 'role' => "form", 'id' => "form1", 'method' => "post", 'enctype' => "multipart/form-data")); ?>    
                        <div class="boxs-body">
                                <div class="row">
                                    <input type="hidden" name="ds_dtls" value="<?php echo $ds_dtls->id; ?>"/>
                                    <div class="form-group col-md-6">
                                        <label for="username">State Name : </label>
                                        <select name="state_name" id="state_name" class="form-control">
                                            <option value=""><?php echo $ds_dtls->state_name; ?></option>
                                                <?php if (!empty($state_names)) { ?>
                                                    <?php foreach ($state_names as $sname) { ?>
                                                        <option value="<?php echo $sname->id; ?>" <?php echo set_select('state_name', $sname->state_name); ?>><?php echo $sname->state_name; ?></option>
                                                    <?php } ?>
                                                <?php } ?>
                                                <?php 
                                                $statename = "";
                                                if(isset($ds_dtls->state_id)) {
                                                    $statename = $ds_dtls->state_id;
                                                }
                                            ?>
                                                
                                                <?php if (!empty($state_names)) { ?>
                                                    <?php foreach ($state_names as $sname) { ?>
                                                        <option value="<?php echo $sname->id; ?>" <?php if($sname->id == $statename) echo("selected") ?>><?php echo $sname->state_name; ?></option>
                                                    <?php } ?>
                                                <?php } ?>
                                        </select>
                                    </div>
                                    <div class="form-group col-md-6">
                                        <label for="email">District Name :</label>
                                        <input type="text" name="district_name" id="district_name" class="form-control" required value="<?php echo $ds_dtls->district_name; ?>">
                                        <?php if (form_error('district_name')) { ?>
                                            <span class="error"><?php echo form_error('district_name'); ?></span>
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

<script src="https://code.jquery.com/jquery-3.4.1.min.js" type="text/javascript" nonce="8f0882ce3be14f201cadd0eff5726cbd"></script>
<script type="text/javascript" nonce="8f0882ce3be14f201cadd0eff5726cbd">
        $(document).ready(function () {

            //Take Albhabetic Character Only
            jQuery.validator.addMethod("lettersonly", function(value, element) {
            return this.optional(element) || /^[a-z\s]+$/i.test(value);
            }, "Please enter only alphabetical characters");
            
            //Form Validation Check

            $("#form1").validate({
            rules: {
                // state_name: {
                //     required: true
                // },
                district_name: {
                    required: true,
                    minlength: 3,
                    maxlength: 30,
                    lettersonly:true
                },
            },
            messages: {
                state_name: {
                    required: "Please select state name"
                },
                district_name: {
                    required: "Please enter block name",
                    minlength: "District should be minimum 3 characters",
                    maxlength: "District should be maximum 30 characters"
                },
            },
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