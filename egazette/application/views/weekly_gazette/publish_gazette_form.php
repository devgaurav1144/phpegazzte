<?php defined('BASEPATH') OR exit('No direct script access allowed'); ?>
<!-- CONTENT -->
<section id="content">
    <div class="page page-forms-validate">
        <!-- bradcome -->
        <ol class="breadcrumb">
            <li><a href="<?php echo base_url(); ?>dashboard">Dashboard</a></li>
            <li><a href="<?php echo base_url(); ?>weekly_gazette">Weekly Gazette</a></li>
            <li class="active">Publish Weekly Gazette</li>
        </ol>

        <!-- row -->
        <div class="row">
            <div class="col-md-12">
                <section class="boxs">
                    <div class="boxs-header filter-with-add">
                        <h3 class="custom-font hb-blue"><strong>Publish Weekly Gazette</strong></h3>
                        <div class="">
                            <!-- If admin user, can view the weekly gazette filter for approval -->
                            <?php if ($this->session->userdata('is_admin')) { ?>
                                <a href="<?php echo base_url(); ?>weekly_gazette/merged_wk_gz" class="btn-bg btn btn-warning">View Merge</a>
                            <?php } ?>
                        </div>
                    </div>
                    <?php echo form_open('weekly_gazette/publish_gazette_form', array('class' => "form1", 'name' => "form1", 'id' => "publish_form", 'method' => "post", 'enctype' => "multipart/form-data")); ?>    
                        <div class="boxs-body">
                            <div class="row">
                                <div class="form-group col-md-4">
                                    <label for="email">Year : <span class="asterisk">*</span></label>
                                    <select name="year" id="year" required="" class="form-control">
                                        <option value="">Select Year</option>
                                        <?php if (!empty($year_list)) { ?>
                                            <?php foreach ($year_list as $year_det) { ?>
                                                <option value="<?php echo $year_det->year; ?>"><?php echo $year_det->year;?></option>
                                            <?php } ?>
                                        <?php } ?>
                                    </select>
                                    <?php if (form_error('year')) { ?>
                                        <?php echo form_error('year'); ?>
                                    <?php } ?>
                                </div>
                                <div class="form-group col-md-4">
                                    <label for="email">Week : <span class="asterisk">*</span></label>
                                    <select name="week" id="week" required="" class="form-control">
                                        <option value="">Select Week</option>
                                    </select>
                                    <?php if (form_error('week')) { ?>
                                        <?php echo form_error('week'); ?>
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
<script src="https://code.jquery.com/jquery-1.12.4.min.js" type="text/javascript" nonce="a6b9a780936c8e980939086f618dded2"></script>
<script src="<?php echo base_url(); ?>assets/bundles/jquery.validate.min.js" type="text/javascript" nonce="a6b9a780936c8e980939086f618dded2"></script>
<script type="text/javascript" nonce="a6b9a780936c8e980939086f618dded2">
    $(document).ready(function() {
        // weekly gazette form Year selection & Week loading
        $('#year').on('change', function () {
            if ($(this).val() !== "") {        
                $.ajax({
                    type: "post",
                    url: '<?php echo base_url(); ?>' + "weekly_gazette/get_merged_year_wise_week",
                    data: {
                        'year' : $(this).val(),
                        '<?php echo $this->security->get_csrf_token_name(); ?>' : '<?php echo $this->security->get_csrf_hash(); ?>'
                    },
                    dataType: 'html',
                    // if success, returns success message
                    success: function (data) {
                        $('#week').html(data);
                    },
                    error: function (data) {
                        $('#week').html(data);
                    }
                });
            } else {
                $('#week').val('');
            }
        });

        // validate function
        $("#publish_form").validate({
            rules: {
                year: {
                    required: true
                },
                week: {
                    required: true
                }
            },
            messages: {
                year: {
                    required: "Please select Year"
                },
                week: {
                    required: "Please select Week"
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