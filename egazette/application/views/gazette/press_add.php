<?php defined('BASEPATH') OR exit('No direct script access allowed'); ?>
<style nonce="8f0882ce3be14f201cadd0eff5726cbd">
    
    .month2 {
        width: 174px !important;
    }

    .form-group label.error {
        position: absolute;
        width: 100%;
        left: -4px;
        padding: 0;
        bottom: -11px;
    }

</style>
<!-- CONTENT -->
<section id="content">
    <div class="page page-forms-validate">
        <!-- bradcome -->
        <ol class="breadcrumb">
            <li><a href="<?php echo base_url(); ?>dashboard">Dashboard</a></li>
            <li><a href="<?php echo base_url(); ?>gazette">Extraordinary Gazette</a></li>
            <li class="active">Approve Extraordinary Gazette</li>
        </ol>
        <!-- row -->
        <div class="row">
            <div class="col-md-12">
                <section class="boxs">
                    <div class="boxs-header">
                        <h3 class="custom-font hb-blue"><strong>Approve Gazette</strong></h3>
                    </div>
                    
                    <?php echo form_open('gazette/press_preview', array('name' => "form1", 'role' => "form", 'id' => "exo_docket_add_form", 'enctype' => "multipart/form-data", 'method' => "post")); ?>    
                        <div class="gazette-main">
                            <div class="header-gazette">
                                <img src="<?php echo base_url(); ?>assets/images/header.jpg">
                                <p class="sub-head"> EXTRAORDINARY <span>PUBLISHED BY AUTHORITY</span></p>
                                <?php //if (!empty($sro_no)) { ?>
                                    <!-- <span class="sro_no_show">S.R.O No: <?php //echo $sro_no; ?></span> -->
                                <?php //} ?>
                                <?php if ($sro_avl->sro_available == 1 && !empty($sro_no)) { ?>
                                    <span class="sro_no_show">
                                        <input type="text" placeholder="SRO No" name="sro_no" id="sro_no" required value="<?php echo $sro_no; ?>" class="form-control" autocomplete="off" readonly="">
                                    </span>
                                <?php } else { ?>
                                    <span class="sro_no_show">
                                        <input type="text" placeholder="SRO No" name="sro_no" id="sro_no" value="" class="form-control" autocomplete="off" readonly="">
                                    </span>
                                <?php } ?>    
                                <div class="gazette-nav-bar">
                                    <ul class="docker-ul-head">
                                        <li class="number">
                                            <input type="text" name="sl_no" id="sl_no" class="form-control" required value="<?php echo $sl_no; ?>" placeholder="Number"  autocomplete="off">
                                            <?php if (form_error('sl_no')) { ?>
                                                <span class="error"><?php echo form_error('sl_no'); ?></span>
                                            <?php } ?>
                                        </li>
                                        <li class="city">CUTTACK,</li>
                                        <li class="day">
                                            <input type="text" name="issue_date" id="issue_date" class="form-control picker" value="" placeholder="MONDAY, JANUARY 2, 2017" required value="<?php echo set_value('issue_date'); ?>" autocomplete="off">
                                            <?php if (form_error('issue_date')) { ?>
                                                <span class="error"><?php echo form_error('issue_date'); ?></span>
                                            <?php } ?>
                                        </li>
                                        <li class="month month2">
                                            <!--<input type="text" placeholder="/ KARTIKA 19,  1926" name="sakabda_date" id="shakabda_date" required value="<?php //echo set_value('sakabda_date'); ?>" class="form-control" autocomplete="off">-->
                                            <?php //if (form_error('sakabda_date')) { ?>
                                                <!--<span class="error"><?php //echo form_error('sakabda_date'); ?></span>-->
                                            <?php //} ?>
											<select name="saka_month" id="saka_month" required class="form-control" autocomplete="off">
												<option value="BAISAKHA" <?php echo set_select('saka_month', 'BAISAKHA'); ?>>BAISAKHA</option>
												<option value="JAISTHA" <?php echo set_select('saka_month', 'JAISTHA'); ?>>JAISTHA</option>
												<option value="ASADHA" <?php echo set_select('saka_month', 'ASADHA'); ?>>ASADHA</option>
												<option value="SRAVANA" <?php echo set_select('saka_month', 'SRAVANA'); ?>>SRAVANA</option>
												<option value="BHADRA" <?php echo set_select('saka_month', 'BHADRA'); ?>>BHADRA</option>
												<option value="ASWINA" <?php echo set_select('saka_month', 'ASWINA'); ?>>ASWINA</option>
												<option value="KARTIKA" <?php echo set_select('saka_month', 'KARTIKA'); ?>>KARTIKA</option>
												<option value="MARGASIRA" <?php echo set_select('saka_month', 'MARGASIRA'); ?>>MARGASIRA</option>
												<option value="PAUSA" <?php echo set_select('saka_month', 'PAUSA'); ?>>PAUSA</option>
												<option value="MAGHA" <?php echo set_select('saka_month', 'MAGHA'); ?>>MAGHA</option>
												<option value="FALGUNA" <?php echo set_select('saka_month', 'FALGUNA'); ?>>FALGUNA</option>
												<option value="CHAITRA" <?php echo set_select('saka_month', 'CHAITRA'); ?>>CHAITRA</option>
											</select>
											<?php if (form_error('saka_month')) { ?>
												<span class="error"><?php echo form_error('saka_month'); ?></span>
											<?php } ?>
                                        </li>
                                        <li class="month month2">
											<select name="saka_date" id="saka_date" required class="form-control" autocomplete="off">
												<option value="1" <?php echo set_select('saka_date', '1'); ?>>1</option>
												<option value="2" <?php echo set_select('saka_date', '2'); ?>>2</option>
												<option value="3" <?php echo set_select('saka_date', '3'); ?>>3</option>
												<option value="4" <?php echo set_select('saka_date', '4'); ?>>4</option>
												<option value="5" <?php echo set_select('saka_date', '5'); ?>>5</option>
												<option value="6" <?php echo set_select('saka_date', '6'); ?>>6</option>
												<option value="7" <?php echo set_select('saka_date', '7'); ?>>7</option>
												<option value="8" <?php echo set_select('saka_date', '8'); ?>>8</option>
												<option value="9" <?php echo set_select('saka_date', '9'); ?>>9</option>
												<option value="10" <?php echo set_select('saka_date', '10'); ?>>10</option>
												<option value="11" <?php echo set_select('saka_date', '11'); ?>>11</option>
												<option value="12" <?php echo set_select('saka_date', '12'); ?>>12</option>
												<option value="13" <?php echo set_select('saka_date', '13'); ?>>13</option>
												<option value="14" <?php echo set_select('saka_date', '14'); ?>>14</option>
												<option value="15" <?php echo set_select('saka_date', '15'); ?>>15</option>
												<option value="16" <?php echo set_select('saka_date', '16'); ?>>16</option>
												<option value="17" <?php echo set_select('saka_date', '17'); ?>>17</option>
												<option value="18" <?php echo set_select('saka_date', '18'); ?>>18</option>
												<option value="19" <?php echo set_select('saka_date', '19'); ?>>19</option>
												<option value="20" <?php echo set_select('saka_date', '20'); ?>>20</option>
												<option value="21" <?php echo set_select('saka_date', '21'); ?>>21</option>
												<option value="22" <?php echo set_select('saka_date', '22'); ?>>22</option>
												<option value="23" <?php echo set_select('saka_date', '23'); ?>>23</option>
												<option value="24" <?php echo set_select('saka_date', '24'); ?>>24</option>
												<option value="25" <?php echo set_select('saka_date', '25'); ?>>25</option>
												<option value="26" <?php echo set_select('saka_date', '26'); ?>>26</option>
												<option value="27" <?php echo set_select('saka_date', '27'); ?>>27</option>
												<option value="28" <?php echo set_select('saka_date', '28'); ?>>28</option>
												<option value="29" <?php echo set_select('saka_date', '29'); ?>>29</option>
												<option value="30" <?php echo set_select('saka_date', '30'); ?>>30</option>
												<option value="31" <?php echo set_select('saka_date', '31'); ?>>31</option>
											</select>
											<?php if (form_error('saka_date')) { ?>
												<span class="error"><?php echo form_error('saka_date'); ?></span>
											<?php } ?>
                                        </li>
                                        <li class="month month2">
											<input type="text" name="saka_year" id="saka_year" class="form-control" value="" maxlength="4" placeholder="2017" required value="<?php echo set_value('saka_year'); ?>" autocomplete="off">
                                            <?php if (form_error('saka_year')) { ?>
                                                <span class="error"><?php echo form_error('saka_year'); ?></span>
                                            <?php } ?>
                                        </li>
                                    </ul>
                                </div>
                            </div>
                            <input type="hidden" name="gazette_id" value="<?php echo $gazette_id; ?>"/>
                            <div class="boxs-body">

                            </div>
                            <div class="pdf-container">
                                <embed src="<?php echo base_url() . $details->dept_pdf_file_path; ?>" type="application/pdf" width="100%" height="650px" internalinstanceid="8">
                            </div>
                            <footer>
                                <div class="line"></div>
                                <p>Processed and e-Published by the Director, Directorate of Printing, Stationery and Publication, (StateName), Cuttack-10</p><p></p>
                            </footer>
                        </div>
                        <div class="boxs-footer text-right bg-tr-black lter dvd dvd-top">
                            <button type="submit" class="btn btn-raised btn-success" id="form4Submit">Save Preview</button>
                        </div>
                    <?php echo form_close(); ?>
                </section>
            </div>
        </div>
    </div>
</section>
<script src="https://code.jquery.com/jquery-1.12.4.min.js" type="text/javascript" nonce="a6b9a780936c8e980939086f618dded2"></script>
<script>
    $(document).ready(function(){
        // press add docket form submit
        $("#exo_docket_add_form").validate({
            rules: {
                issue_date: {
                    required: true
                },
                saka_month: {
                    required: true
                },
                saka_date: {
                    required: true
                },
                saka_year: {
                    required: true,
                    digits: true,
                    minlength: 4,
                    maxlength: 4
                }
            },
            messages: {
                issue_date: {
                    required: 'Please enter issue date'
                },
                saka_month: {
                    required: 'Please select Saka Month'
                },
                saka_date: {
                    required: 'Please select Saka Date'
                },
                saka_year: {
                    required: 'Please enter Saka Year',
                    digits: 'Year should be 4 digits',
                    minlength: 'Year should be 4 digits',
                    maxlength: 'Year should be 4 digits'
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