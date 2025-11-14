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
        <!-- bradcome -->
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
                                    <th>Sl No</th>
                                    <th>Date</th>
                                    <th>Message</th>
                                </tr>
                            </thead>
                            <tbody>
                                <?php if (!empty($notifications)) { ?>
                                    <?php 
                                        if ($this->my_pagination->total_rows > $this->my_pagination->per_page) {
                                            $cntr = 1 + ($this->my_pagination->cur_page - 1) * $this->my_pagination->per_page;
                                        } else {
                                            $cntr = 1;
                                        }
                                    ?>
                                        <?php foreach ($notifications as $notif) { ?>
                                        <tr <?php echo ($notif->is_viewed == 0) ? 'class="unread"': '' ?>>
                                            <td><?php echo $cntr; ?></td>
                                            <td><?php echo get_formatted_datetime($notif->created_at); ?></td>
                                            <td class="get-data">
                                                <?php if ($this->session->userdata('is_applicant')) { ?>
                                                    <?php if($notif->module_id == 1) {?>
                                                        <a href="<?php echo base_url(); ?>check_status/change_partnership_status_details/<?php echo $notif->master_id; ?>" class="applicant_read" data-view_id="<?php echo $notif->id?>"><?php echo $notif->text; ?></a>
                                                    <?php } else {?>
                                                        <a href="<?php echo base_url(); ?>check_status/change_name_status_details/<?php echo $notif->master_id; ?>" class="applicant_read" data-view_id="<?php echo $notif->id?>"><?php echo $notif->text; ?></a>
                                                    <?php }?>
                                                <?php } elseif($this->session->userdata('is_c&t')) { ?>
                                                    <?php if($notif->module_id == 1) {?>
                                                        <a href="<?php echo base_url(); ?>applicants_login/edit_partnership_details/<?php echo $notif->master_id; ?>" class="cnt_read" data-view_id="<?php echo $notif->id?>"><?php echo $notif->text; ?></a>
                                                    <?php } else if($notif->module_id == 3){?>
                                                        <a href="<?php echo base_url(); ?>extraordinary_poc/view/<?php echo $notif->master_id; ?>" class="cnt_read" data-view_id="<?php echo $notif->id?>"><?php echo $notif->text; ?></a>
                                                    <?php } else {?>
                                                        <a href="<?php echo base_url(); ?>applicants_login/view_details_name_surname/<?php echo $notif->master_id; ?>" class="cnt_read" data-view_id="<?php echo $notif->id?>"><?php echo $notif->text; ?></a>
                                                    <?php }?>
                                                <?php } else if($this->session->userdata('is_igr')) { ?>
                                                    <a href="<?php echo base_url(); ?>applicants_login/edit_partnership_details/<?php echo $notif->master_id; ?>" class="igr_read" data-view_id="<?php echo $notif->id?>"><?php echo $notif->text; ?></a>
                                                <?php }?>
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
<!-- <script type="text/javascript" nonce="a6b9a780936c8e980939086f618dded2">
    
    $(document).ready(function () {

        $('.applicant_read').click(function () { 
            let id = $(this).data('view_id');
            $.ajax({
                type: "POST",
                url: '<?php echo base_url(); ?>' + "applicants_login/set_read_applicant",
                data:{'id':id,
                    '<?php echo $this->security->get_csrf_token_name(); ?>' : '<?php echo $this->security->get_csrf_hash(); ?>'
                },
                complete: function () {
                    
                }     
            });
        });

        $('.cnt_read').click(function () { 
           debugger;
            let id = $(this).data('view_id');
            $.ajax({
                type: "POST",
                url: '<?php echo base_url(); ?>' + "applicants_login/set_read_cnt",
                data:{'id':id,
                    '<?php echo $this->security->get_csrf_token_name(); ?>' : '<?php echo $this->security->get_csrf_hash(); ?>'
                },
                complete: function () {
                    
                }     
            });
        });

        $('.igr_read').click(function () { 
           debugger;
            let id = $(this).data('view_id');
            $.ajax({
                type: "POST",
                url: '<?php echo base_url(); ?>' + "applicants_login/set_read_igr",
                data:{'id':id,
                    '<?php echo $this->security->get_csrf_token_name(); ?>' : '<?php echo $this->security->get_csrf_hash(); ?>'
                },
                complete: function () {
                    
                }     
            });
        });
    });
    
</script> -->

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