
<?php defined('BASEPATH') OR exit('No direct script access allowed'); ?>
<!--  CONTENT  -->
<section id="content">
    <div class="page page-tables-footable">
        <!-- bradcome -->
        <div class="b-b mb-10">
            <div class="row">
                <div class="col-sm-6 col-xs-12">
                    <h1 class="h3 m-0">HOD Officers</h1>
                    <small class="text-muted"></small>
                </div>
            </div>
        </div>
        
        <!-- Modal -->
        <div class="modal fade" id="myModal3" tabindex="-1" role="dialog" aria-labelledby="myModalLabel" aria-hidden="true" data-backdrop="false">
            
            <?php echo form_open('user/account_reject', array('class' => "form1", 'name' => "form1", 'method' => "post")); ?>
                <input type="hidden" name="user_id" id="user_id" value=""/>
                <div class="modal-dialog modal-md">
                    <div class="modal-content">
                        <div class="modal-header">
                            <button type="button" class="close" data-dismiss="modal">&times;</button>
                            <h4 class="modal-title">Reject Nodal Officer Account</h4>
                        </div>
                        <div class="modal-body">
                            <div class="form-group">
                                <label>Remark<span class="asterisk">*</span></label>
                                <textarea name="remarks" placeholder="Enter Remark" class="form-control remark_textarea" rows="6" required></textarea>
                            </div>
                        </div>
                        <div class="modal-footer">
                            <button type="submit" class="btn btn-raised btn-success">Submit</button>
                        </div>
                    </div>
                </div>
            <?php echo form_close(); ?>
        </div>
        <!-- Modal -->
        
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
                        <a href="<?php echo base_url(); ?>gazette_hod/hod_registration" class="btn-bg btn btn-success btn-right-align">Add</a>
<!--                        <div class="form-group">
                            <label for="filter" style="padding-top: 5px">Search:</label>
                            <input id="filter" type="text" class="form-control rounded w-md mb-10 inline-block" />
                        </div>-->
                        <table id="searchTextResults" data-filter="#filter" data-page-size="5" class="footable table table-custom">
                            <thead>
                                <tr>
                                    <th>Sl No</th>
                                    <th>Name</th>                                    
                                    <th>Dept.</th>
                                    <th>Mobile</th>
                                    <th>Email</th>
                                    <th>Status</th>
                                    <th>Action</th>
                                </tr>
                            </thead>
                            <tbody>
                                <?php if (!empty($hods)) { ?>
                                    <?php 
                                        if ($this->my_pagination->total_rows > $this->my_pagination->per_page) {
                                            $cntr = 1 + ($this->my_pagination->cur_page - 1) * $this->my_pagination->per_page;
                                        } else {
                                            $cntr = 1;
                                        }
                                    ?>
                                        <?php foreach ($hods as $hod) { ?>
                                        <tr>
                                            <td><?php echo $cntr; ?></td>
                                            <td><?php echo $hod->name; ?></td>                        
                                            <td><?php echo $hod->department_name; ?></td>
                                            <td><?php echo $hod->mobile; ?></td>
                                            <td><?php echo $hod->email; ?></td>
                                            <td>
                                                <div class="stat_change_hod togglebutton" id="<?php echo $hod->id; ?>" title="<?php echo $hod->status; ?>">
                                                    <label><input type="checkbox" <?php echo ($hod->status == 1) ? "checked" : ""; ?>></label>
                                                </div>
                                            </td>
                                            <td>
                                                <div class="btn-group mr5 btn-grp-mr">
                                                    <button type="button" class="btn btn-default btn-pad">Action</button>
                                                    <button type="button" class="btn btn-default dropdown-toggle btn-pad" data-toggle="dropdown">
                                                        <span class="caret"></span>
                                                        <span class="sr-only">Toggle Dropdown</span>
                                                    </button>
                                                    <ul class="dropdown-menu" role="menu">
                                                  
                                                        <li><a href="<?php echo base_url(); ?>Gazette_hod/hod_edit/<?php echo $hod->id; ?>">Edit</a></li>
                                                        
                                                        <li class="delete_id" id="<?php echo $hod->id; ?>"><a href="javascript:void(0);">Delete<div class="ripple-container"></div></a></li>
                                                    </ul>
                                                </div>
                                            </td>
                                        </tr>
                                        <?php $cntr++;
                                    } ?>
                                <?php } else { ?>
                                    <tr>
                                        <td colspan="7" class="center">No data to display</td>
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
        $('.delete_id').on('click', function(){
            var id = $(this).attr('id');
            
            if (confirm('Are you sure to delete the user')) {
                //alert();
                
                // make ajax request
                $.ajax({
                    type: "post",
                    url: '<?php echo base_url(); ?>' + "Gazette_hod/delete",
                    data: {
                        'id' : id,'<?php echo $this->security->get_csrf_token_name(); ?>' : '<?php echo $this->security->get_csrf_hash(); ?>'
                        
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
        
        /*
         * User approve/reject 
         */
        $('.stat_change_hod').one('click', function (e) {
              e.preventDefault();
            var user_id = $(this).attr('id');
            var status = $(this).attr('title');
            //alert('ok');
            // make ajax request
            $.ajax({
              
                type: "post",
                url: '<?php echo base_url(); ?>' + "gazette_hod/hod_status",
                data: {
                    'user_id' : user_id,
                    'status' : status,
                    '<?php echo $this->security->get_csrf_token_name(); ?>' : '<?php echo $this->security->get_csrf_hash(); ?>'
                },
                dataType: 'json',
                 //if success, returns success message
                success: function (data) {
                    location.reload();
                },
                error: function (data) {
                    location.reload();
                }
            });
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