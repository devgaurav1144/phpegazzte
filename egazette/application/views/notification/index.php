<?php defined('BASEPATH') OR exit('No direct script access allowed'); ?>
<!--  CONTENT  -->
<style>
    .unread{
        background-color: #fff;
        font-weight: 600;
    }
    a:hover{
        text-decoration:none;
    }
</style>
<section id="content">
    <div class="page page-tables-footable">
        <ol class="breadcrumb">
            <li><a href="<?php echo base_url(); ?>applicants_login/dashboard">Dashboard</a></li>
            <li class="active">Notifications</li>
        </ol>
        <!-- row -->
        <div class="row">
            <div class="col-md-12">
                <section class="boxs">
                    <div class="boxs-header">
                        <h3 class="custom-font hb-blue"><strong>Notifications</strong></h3>
                    </div>
                    <div class="boxs-body">
                        <?php if ($this->session->flashdata('success')) { ?>
                            <div class="alert alert-success alert-dismissable"><button aria-hidden="true" data-dismiss="alert" class="close" type="button">×</button><?php echo $this->session->flashdata('success'); ?></div>
                        <?php } ?>
                        <?php if ($this->session->flashdata('error')) { ?>
                            <div class="alert alert-danger alert-dismissable"><button aria-hidden="true" data-dismiss="alert" class="close" type="button">×</button><?php echo $this->session->flashdata('error'); ?></div>
                        <?php } ?>
                        <table id="searchTextResults" data-filter="#filter" data-page-size="5" class="footable table table-custom">
                            <thead>
                                <tr>
                                    <th>Sl. No.</th>
                                    <th>Date</th>
                                    <th>Message</th>
                                </tr>
                            </thead>
                            <tbody>
                                
                                <?php if (!empty($notifications)) {?>  
                                    <?php 
                                        if ($this->my_pagination->total_rows > $this->my_pagination->per_page) {
                                            $cntr = 1 + ($this->my_pagination->cur_page - 1) * $this->my_pagination->per_page;
                                        } else {
                                            $cntr = 1;
                                        }
                                    ?>
                                        <?php foreach ($notifications as $notif) { 
                                            $CI =& get_instance();
                                        ?>

                                        <tr <?php echo ($notif->is_read == 0) ? 'class="unread"': '' ?>>
                                            <td><?php echo $cntr; ?></td>
                                            <td><?php echo get_formatted_datetime($notif->created_at); ?></td>
                                            <td>
                                                <?php if ($this->session->userdata('is_admin')) {           
                                                    
                                                    $get_gazette_status = $CI->notification_model->get_gazette_status($notif->gazette_id, $notif->gazette_type);  ?>

                                                    <?php if($notif->gazette_type == "1"){?>
                                                        <?php if($notif->is_paid == "0"){?>
                                                            <a href="<?php echo base_url(); ?>gazette/press_view/<?php echo $notif->gazette_id; ?>"  class="admin_read" data-view_id="<?php echo $notif->id?>"><?php echo $notif->text; ?></a>
                                                        <?php }else{?>
                                                            <a href="<?php echo base_url(); ?>gazette/press_view_paid/<?php echo $notif->gazette_id; ?>"  class="admin_read" data-view_id="<?php echo $notif->id?>"><?php echo $notif->text; ?></a>
                                                        <?php } ?>
                                                    <?php } else if($notif->gazette_type == "2"){?>
                                                        <a href="<?php echo base_url(); ?>weekly_gazette/press_view/<?php echo $notif->gazette_id; ?>"  class="admin_read_weekly" data-view_id="<?php echo $notif->id?>"><?php echo $notif->text; ?></a>

                                                    <?php } else if($notif->gazette_type == "3"){?>
                                                        <?php if($notif->module_id == "2"){?>
                                                        <a href="<?php echo base_url(); ?>applicants_login/view_details_change_name_govt/<?php echo $notif->gazette_id; ?>"  class="change_name_surname" id="<?php echo $notif->id?>"><?php echo $notif->text; ?></a>
                                                    <?php }else{?>
                                                        <a href="<?php echo base_url(); ?>applicants_login/view_details_par_gove/<?php echo $notif->gazette_id; ?>"  class="change_partnership_admin" id="<?php echo $notif->id?>"><?php echo $notif->text; ?></a>
                                                    <?php }}?> 

                                                <?php } else { ?>
                                                    <?php if($notif->gazette_type == "1"){?>
                                                        <?php if($notif->is_paid == "0"){?>
                                                            <a href="<?php echo base_url(); ?>gazette/dept_view/<?php echo $notif->gazette_id; ?>/<?php echo $notif->route_user_id; ?>"  class="admin_read" data-view_id="<?php echo $notif->id?>"><?php echo $notif->text; ?></a>
                                                        <?php }else{?>
                                                            <a href="<?php echo base_url(); ?>gazette/dept_view_paid/<?php echo $notif->gazette_id; ?>/<?php echo $notif->route_user_id; ?>"  class="admin_read" data-view_id="<?php echo $notif->id?>"><?php echo $notif->text; ?></a>
                                                        <?php } ?>
                                                    <?php } else if($notif->gazette_type == "2"){?>
                                                        <a href="<?php echo base_url(); ?>weekly_gazette/dept_view/<?php echo $notif->gazette_id; ?>/<?php echo $notif->user_id; ?>"  class="admin_read_weekly" data-view_id="<?php echo $notif->id?>"><?php echo $notif->text; ?></a>
                                                    <?php }?>
                                                <?php } ?>
                                            </td>
                                        </tr>
                                        
                                        <?php $cntr++;
                                    }
                                    ?>
                                <?php } else { ?>
                                    <tr>
                                        <td colspan="5" class="center">No data to display</td>
                                    </tr>
                                <?php } ?>
                            </tbody>
                            <!-- <tfoot class="hide-if-no-paging">
                                <tr>
                                    <td colspan="5" class="text-right">
                                        <ul class="pagination">
                                        </ul>
                                    </td>
                                </tr>
                            </tfoot> -->
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
    
    $(document).ready(function () {

        $('.admin_read').click(function () { 
            let id = $(this).data('view_id');
            $.ajax({
                type: "POST",
                url: '<?php echo base_url(); ?>' + "gazette/set_read_admin_dept",
                data:{'id':id,
                    '<?php echo $this->security->get_csrf_token_name(); ?>' : '<?php echo $this->security->get_csrf_hash(); ?>'
                },
                complete: function () {
                    
                }     
            });
        });

        $('.admin_read_weekly').click(function () { 
            let id = $(this).data('view_id');
            $.ajax({
                type: "POST",
                url: '<?php echo base_url(); ?>' + "gazette/set_read_admin_dept_weekly",
                data:{'id':id,
                    '<?php echo $this->security->get_csrf_token_name(); ?>' : '<?php echo $this->security->get_csrf_hash(); ?>'
                },
                complete: function () {
                    
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