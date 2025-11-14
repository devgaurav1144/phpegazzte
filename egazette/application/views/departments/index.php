<?php defined('BASEPATH') OR exit('No direct script access allowed'); ?>
<!--  CONTENT  -->
<section id="content">
    <div class="page page-tables-footable">
        <ol class="breadcrumb">
            <li><a href="<?php echo base_url(); ?>dashboard">Dashboard</a></li>
            <li class="active">Departments</li>
        </ol>
        <!-- bradcome -->
        <!-- <div class="b-b mb-10">
            <div class="row">
                <div class="col-sm-6 col-xs-12">
                    <h1 class="h3 m-0">Departments</h1>
                    <small class="text-muted"></small>
                </div>
            </div>
        </div> -->
        <!-- row -->
        
        <div class="row">
            <div class="col-md-12">
                <section class="boxs">
                    <div class="boxs-header">
                        <h3 class="custom-font hb-blue"><strong>Departments</strong></h3>
                        <a href="<?php echo base_url(); ?>department/add" class="btn-bg btn btn-success btn-right-align">Add</a>
                    </div>
                    <div class="boxs-body">
                        <?php if ($this->session->flashdata('success')) { ?>
                            <div class="alert alert-success alert-dismissable"><button aria-hidden="true" data-dismiss="alert" class="close" type="button">×</button><?php echo $this->session->flashdata('success'); ?></div>
                        <?php } ?>
                        <?php if ($this->session->flashdata('error')) { ?>
                            <div class="alert alert-success alert-dismissable"><button aria-hidden="true" data-dismiss="alert" class="close" type="button">×</button><?php echo $this->session->flashdata('error'); ?></div>
                        <?php } ?>
                        
                        <table id="searchTextResults" data-filter="#filter" data-page-size="5" class="footable table table-custom">
                            <thead>
                                <tr>
                                    <th width="80px">Sl No</th>
                                    <th>Department</th>
                                    <th>Date</th>
                                    <th>Status</th>
                                    <th>Action</th>
                                </tr>
                            </thead>
                            <tbody>
                                <?php if (!empty($departments)) { ?>
                                    <?php 
                                        if ($this->my_pagination->total_rows > $this->my_pagination->per_page) {
                                            $cntr = 1 + ($this->my_pagination->cur_page - 1) * $this->my_pagination->per_page;
                                        } else {
                                            $cntr = 1;
                                        }
                                    ?>
                                    <?php foreach ($departments as $dept) { ?>
                                        <tr>
                                            <td><?php echo $cntr; ?></td>
                                            <td><?php echo $dept->department_name;?></td>
                                            <td><?php echo get_formatted_datetime($dept->datetime);?></td>
                                            <td><?php echo ($dept->status == 1) ? 'Active' : 'Inactive';?></td>
                                            <td>
                                                <div class="btn-group mr5 btn-grp-mr">
                                                    <button type="button" class="btn btn-default btn-pad">Action</button>
                                                    <button type="button" class="btn btn-default dropdown-toggle btn-pad" data-toggle="dropdown">
                                                        <span class="caret"></span>
                                                        <span class="sr-only">Toggle Dropdown</span>
                                                    </button>
                                                    <ul class="dropdown-menu" role="menu">
                                                        <li><a href="<?php echo base_url();?>department/edit/<?php echo $dept->id; ?>">Edit</a></li>
                                                        <li class="delete_id" id="<?php echo $dept->id; ?>"><a href="javascript:void(0);">Delete<div class="ripple-container"></div></a></li>
                                                    </ul>
                                                </div>
                                            </td>
                                        </tr>
                                    <?php $cntr++; } ?>
                                <?php } else { ?>
                                    <tr>
                                        <td colspan="5" class="center">No data to display</td>
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
<script src="https://code.jquery.com/jquery-3.4.1.min.js" type="text/javascript" nonce="a6b9a780936c8e980939086f618dded2"></script>
<script type="text/javascript" nonce="a6b9a780936c8e980939086f618dded2">
    $(document).ready(function(){
        $('.delete_id').on('click', function(){
            var id = $(this).attr('id');
            if (confirm('Are you sure to delete the department')) {
                // make ajax request
                $.ajax({
                    type: "post",
                    url: '<?php echo base_url(); ?>' + "department/delete",
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