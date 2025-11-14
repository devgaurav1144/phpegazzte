


<?php defined('BASEPATH') OR exit('No direct script access allowed'); ?>
<style  rel="stylesheet" nonce="8f0882ce3be14f201cadd0eff5726cbd">
    .breadcrumb{
        padding:8px 1px;
    }
    #error{
        color:red;
    }
    .btn:not(.btn-raised):not(.btn-link):focus{
        background-color:#4caf50 !important;
    }
</style>
<!--  CONTENT  -->
<section id="content">
    <div class="page page-tables-footable">
        <!-- bradcome -->

        <ol class="breadcrumb">
            <li><a href="<?php echo base_url(); ?>applicants_login/dashboard">Dashboard</a></li>
            <li class="active">Check Transaction Status</li></li>
            <!-- <li class="active">Add</li> -->
        </ol>

        <div class="b-b mb-10">
            <div class="row">
                <div class="col-sm-6 col-xs-12">
                    <h1 class="h3 m-0">Check Transaction Status</li></h1>
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
                        

                        <?php echo form_open('applicants_login/fetch_transaction_details', array('id' => 'search_dept_ref_id_form', 'name' => 'search_dept_ref_id_form', 'method' => 'POST')) ?>
                            <!-- form fields -->
                            <div class="row">
                                <div class="form-group col-md-6">
                                    <label>Select Type of Transaction : </label>
                                    <select name="transaction_type" id="transaction_type" class="form-control">
                                        <option value="">Select Type</option>
                                        <option value="COS">Change of Name/Surname</option>
                                        <option value="COG">Change of Gender</option>
                                        <option value="COP">Change of Partnership</option>
                                    </select>
                                </div>
                                <div class="form-group col-md-6">
                                    <label for="dept_ref_id">Enter Department Reference ID : </label>
                                    <input type="text" id="dept_ref_id" name="dept_ref_id" placeholder="170746824265c5e5d2b8b5e" class="form-control" autocomplete='off'/>
                                </div>
                            </div>
                            <button type="submit" class="btn-bg btn btn-success" id="search_dept_ref_id">Search</button>
                            <span id="error"></span>
                        <?php echo form_close(); ?>
                    </div>
                </section>
                <section class="boxs">
                    <div class="boxs-body">
                    <?php if (isset($error_message)): ?>
                        <div class="alert alert-danger"><?php echo $error_message; ?></div>
                    <?php endif; ?>
                    <?php if (isset($success_message)): ?>
                        <div class="alert alert-success"><?php echo $success_message; ?></div>
                    <?php endif; ?>
                        <table id="searchTextResults" class="footable table table-custom"  >
                            <thead>
                                <tr>
                                    <th>Sl No</th>
                                    <th>Department Reference ID</th>
                                    <th>Amount</th>
                                    <th>Challan Reference ID</th>
                                    <th>Bank Transaction ID</th>
                                    <th>Bank Transaction Status</th>
                                    <th>Bank Transaction Time</th>
                                </tr>
                            </thead>
                            <tbody>
                                <?php if (empty($data_array)): ?>
                                    <tr>
                                        <td colspan="7" align='center'>No data found</td> <!-- Adjust the colspan as per the number of columns -->
                                    </tr>
                                <?php else: ?>
                                    <tr>
                                        <td>1</td>
                                        <td><?php echo $data_array[1]; ?></td> 
                                        <td><?php echo $data_array[2]; ?></td>
                                        <td><?php echo $data_array[3]; ?></td>
                                        <td><?php echo $data_array[4]; ?></td>
                                        <td><?php echo $data_array[6]; ?></td>
                                        <td><?php echo $data_array[7]; ?></td> 
                                    </tr>
                                <?php endif; ?>
                            </tbody>
                        </table>
                    </div>
                </section>
            </div>
        </div>
    </div>
</section>
<!--/ CONTENT -->
<script src="<?php echo base_url(); ?>/assets/js/vendor/jquery/jquery-3.1.0.min.js" type="text/javascript" nonce="a6b9a780936c8e980939086f618dded2"></script>

<script>
    $(document).ready(function() {
        $('#search_dept_ref_id_form').submit(function(event) {
            event.preventDefault(); // Prevent the default form submission
            const transaction_type = $('#transaction_type').val();
            const dept_ref_id = $('#dept_ref_id').val();
            const error = $('#error');
            
            // Clear any previous error messages
            error.text('');

            // Validate transaction type
            if (transaction_type === "") {
                error.text('Please select a transaction type');
                return false;
            }

            // Validate department reference ID
            if (dept_ref_id.trim() === "") {
                error.text('Please enter Department Reference ID to search');
                return false;
            }

            // If validation passes, submit the form
            this.submit();
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