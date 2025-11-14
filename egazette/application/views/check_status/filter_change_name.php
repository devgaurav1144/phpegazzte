
<?php defined('BASEPATH') OR exit('No direct script access allowed'); ?>
<!--  CONTENT  -->
<section id="content">
    <div class="page page-tables-footable">
        <!-- bradcome -->
        <div class="b-b mb-10">
            <div class="row">
                <div class="col-sm-6 col-xs-12">
                    <h1 class="h3 m-0">Change of Name/Surname - Check Status</h1>
                    <small class="text-muted"></small>
                </div>
            </div>
        </div>
        
              
        <!-- row -->
        <div class="row">
            <div class="col-md-12">
                <section class="boxs">
                    <div class="boxs-body">
                        <?php if ($this->session->flashdata('success')) { ?>
                            <div class="alert alert-success alert-dismissable"><button aria-hidden="true" data-dismiss="alert" class="close" type="button">×</button><?php echo $this->session->flashdata('success'); ?></div>
                        <?php } ?>
                        <?php if ($this->session->flashdata('error')) { ?>
                            <div class="alert alert-danger alert-dismissable"><button aria-hidden="true" data-dismiss="alert" class="close" type="button">×</button><?php echo $this->session->flashdata('error'); ?></div>
                        <?php } ?>
                        
                        <?php echo form_open('check_status/filter_change_name', array('id' => 'filter_form', 'name' => 'filter_form', 'method' => 'POST')) ?>
                            
                            <div class="row">
                                <div class="form-group col-md-6">
                                    <label for="file_no">File Number : </label>
                                    <input type="text" id="file_no" name="file_no" placeholder="XN-0000-<?php echo date('Y'); ?>" class="form-control" maxlength="12" autocomplete="off" value="<?php echo $this->session->userdata('file_no') ?>"/>
                                    
                                </div>
                                <!-- <div class="form-group col-md-3">
                                    <label for="f_date">From Date : </label>
                                    <input type="text" id="f_date" name="f_date" placeholder="YYYY-MM-DD" class="form-control" autocomplete="off" value="<?php echo $this->session->userdata('f_date') ?>"/>
                                    
                                </div>
                                <div class="form-group col-md-3">
                                    <label for="t_date">To Date : </label>
                                    <input type="text" id="t_date" name="t_date" placeholder="YYYY-MM-DD" class="form-control" autocomplete="off" value="<?php echo $this->session->userdata('t_date') ?>"/>
                                    
                                </div> -->
                                <div class="form-group col-md-6">
                                    <label for="status_id">Gazette Status : </label>
                                    <select name="status_id" id="status_id" class="form-control">
                                        <option value="">Select Gazette Status</option>
                                        <?php if (!empty($status)) { ?>
                                            <?php foreach ($status as $state) { ?>
                                        <option value="<?php echo $state->id; ?>" <?php if ($this->session->userdata('status_id') == $state->id) { echo 'selected'; } ?>><?php echo $state->status_name; ?></option>
                                            <?php } ?>
                                        <?php } ?>
                                    </select>
                                    
                                </div>
                            </div>
                            
                            <div class="row">
                                <div class="col-md-3">
                                    <button type="submit" class="btn-bg btn btn-success">Search</button>
                                </div>
                            </div>
                            
                        <?php echo form_close(); ?>
                         
                    </div>
                </section>
                <section class="boxs">
                    <div class="boxs-body">
                        <a href="<?php echo base_url(); ?>check_status/change_name_status" class="btn-bg btn btn-success btn-right-align">All Gazettes</a>
                        <table id="searchTextResults" data-filter="#filter" data-page-size="5" class="footable table table-custom">
                            <thead>
                                <tr>
                                    <th>Sl No</th>
                                    <th>Gazette Type</th>
                                    <th>File Number</th>
                                    <th>Date</th>
                                    <th>Status</th>
                                    <th width="120">View Details</th>
                                </tr>
                            </thead>
                            <tbody>
                                <?php if (!empty($change_of_names)) { ?>
                                    <?php 
                                        if ($this->my_pagination->total_rows > $this->my_pagination->per_page) {
                                            $cntr = 1 + ($this->my_pagination->cur_page - 1) * $this->my_pagination->per_page;
                                        } else {
                                            $cntr = 1;
                                        }
                                    ?>
                                        <?php //print_r($partners);
                                        foreach ($change_of_names as $data) { ?>
                                        <tr>
                                            <td><?php echo $cntr; ?></td>
                                            <td>Extraordinary</td>
                                            <td><?php echo $data->file_no; ?></td>
                                            <td><?php echo  strftime('%d %b %Y, %I:%M %p', strtotime($data->created_at)); ?></td>
                                            <td><?php echo $data->status_name; ?></td>
                                            <td class="center">
                                                <a href="<?php echo base_url(); ?>check_status/change_name_status_details/<?php echo $data->id; ?>"><i class="fa fa-eye"></i></a>
                                            </td>
                                        </tr>
                                        <?php $cntr++;
                                    } ?>
                                <?php } else { ?>
                                    <tr>
                                        <td colspan="6" class="center">No data to display</td>
                                    </tr>
                                <?php } ?>
                            </tbody>
                        </table>
                        <?php if (isset($links)) { ?>
                            <?php echo $links; ?>
                        <?php } ?>
                    </div>
                </section>
            </div>
        </div>
    </div>
</section>
<!--/ CONTENT -->
<script src="<?php echo base_url(); ?>/assets/js/vendor/jquery/jquery-3.1.0.min.js" type="text/javascript" nonce="a6b9a780936c8e980939086f618dded2"></script>
<script type="text/javascript" nonce="a6b9a780936c8e980939086f618dded2">
    $(document).ready(function(){
        
        $("#f_date").datepicker({
            dateFormat: 'yy-mm-dd',
            autoclose: true,
            todayHighlight: true,
            maxDate: new Date(),
            onSelect: function(selected) {
                $("#t_date").datepicker("option","minDate", selected)
            }
        });	

        $("#t_date").datepicker({
            dateFormat: 'yy-mm-dd',
            autoclose: true,
            todayHighlight: true,
            maxDate: new Date(),
            onSelect: function(selected) {
                $("#f_date").datepicker("option","maxDate", selected)
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