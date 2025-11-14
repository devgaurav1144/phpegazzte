<?php defined('BASEPATH') OR exit('No direct script access allowed'); ?>
<!-- CONTENT -->
<section id="content">
    <div class="page page-forms-validate">
        <!-- bradcome -->
        <ol class="breadcrumb">
            <li><a href="<?php echo base_url(); ?>dashboard">Dashboard</a></li>
            <li><a href="<?php echo base_url(); ?>gazette">Extraordinary Gazette</a></li>
            <li class="active">View Details</li>
        </ol>
        <!-- <div class="bg-light lter b-b wrapper-md mb-10">
            <div class="row">
                <div class="col-sm-6 col-xs-12">
                    <h1 class="h3 m-0">View Gazette</h1>
                </div>
            </div>
        </div> -->
        <!-- row -->
        <div class="row">
            <div class="col-md-12">
                <section class="boxs">
                    <div class="boxs-header">
                        <h3 class="custom-font hb-blue"><strong>Department Extraordinary Gazette Details</strong></h3>

                        <?php if (($details->status_id == 2 || $details->status_id == 7 || $details->status_id == 22)) { ?>
                            <div class="action_btn">
                                <a href="<?php echo base_url(); ?>gazette/dept_preview/<?php echo $details->id; ?>" class="btn btn-raised btn-success edit_btn pull-right">Submit</a>
                            </div>
                        <?php } ?>
                    </div>
                    <div class="boxs-body">
                        <div class="border_data">
                            <?php if (!empty($details->sl_no) && !empty($details->letter_no)) { ?>
                                <div class="row">
                                    <div class="form-group col-md-6">
                                        <label for="username">Sl No : </label>
                                        <?php echo $details->sl_no; ?>
                                    </div>
                                    <div class="form-group col-md-6">
                                        <label for="email">Letter/Memo Number : </label>
                                        <?php echo $details->letter_no; ?>
                                    </div>
                                </div>
                            <?php } ?>
                            <div class="row">
                                <div class="form-group col-md-6">
                                    <label for="username">Department Name : </label>
                                    <?php echo $details->department_name; ?>
                                </div>
                                <div class="form-group col-md-6">
                                    <label for="email">Gazette Type : </label>
                                    <?php echo $details->gazette_type; ?>
                                </div>
                            </div>
                            <div class="row">
                                <div class="form-group col-md-6">
                                    <label for="email">Payment Type : </label>
                                    <?php
                                    if ($details->is_paid == 0) {
                                        echo 'Free';
                                    } else {
                                        echo 'Payment of Cost';
                                    }
                                    ?>
                                </div>
                                <div class="form-group col-md-6">
                                    <label for="email">Created Datetime : </label>
                                    <?php echo get_formatted_datetime($details->created_at); ?>
                                </div>
                            </div>
                            <div class="row">
                                <div class="form-group col-md-6">
                                    <label for="email">Subject : </label>
                                    <?php echo $details->subject; ?>
                                </div>
                                <div class="form-group col-md-6">
                                    <label for="username">Notification Type : </label>
                                    <?php echo strtoupper($details->notification_type); ?>
                                </div>
                            </div>
                            <div class="row">
                                <div class="form-group col-md-6">
                                    <label for="email">Notification Number : </label>
                                    <?php echo $details->notification_number; ?>
                                </div>
                                <?php if (!empty($details->issue_date)) { ?>
                                    <div class="form-group col-md-6">
                                        <label for="username">Issue Date : </label>
                                        <?php echo $details->issue_date; ?>
                                    </div>
                                <?php } ?>
                            </div>
                            <div class="row">
                                <?php if ($details->sro_available == 1) { ?>
                                    <div class="form-group col-md-6">
                                        <label for="username">SRO Available : </label>
                                        Yes
                                    </div>
                                <?php } ?>
                                <?php if (!empty($details->letter_no)) { ?>
                                    <div class="form-group col-md-6">
                                        <label for="username">SRO No : </label>
                                        <?php echo $details->letter_no; ?>
                                    </div>
                                <?php } ?>
                            </div>
                            <div class="row">
                                <div class="form-group col-md-6">
                                    <label for="username">Status : </label>
                                    <?php echo $details->status_name; ?>
                                </div>
                                <?php if (!empty($details->reject_remarks)) { ?>
                                    <div class="form-group col-md-6">
                                        <label for="username">Reject Remarks : </label>
                                        <?php echo $details->reject_remarks; ?>
                                    </div>
                                <?php } ?>
                            </div>
                            <div class="row">
                                <div class="form-group col-md-6">
                                    <label for="username">Dept. Gazette (Official Copy) : </label><br/>
                                    <?php if (!empty($details->dept_signed_pdf_path)) { ?> 
                                        <a href="<?php echo base_url() . $details->dept_signed_pdf_path; ?>" target="_blank" class="file_icons"><i class="fa fa-file-pdf-o"></i></a>
                                    <?php } else { ?>
                                        <a href="<?php echo base_url() . $details->dept_pdf_file_path; ?>" target="_blank" class="file_icons"><i class="fa fa-file-pdf-o"></i></a>
                                    <?php } ?>
                                </div>
                                <?php if (!empty($details->press_signed_pdf_path)) { ?>
                                    <div class="form-group col-md-6">
                                        <label for="username">Press Gazette (Signed PDF) : </label><br/>
                                        <a href="<?php echo base_url() . $details->press_signed_pdf_path; ?>" target="_blank" class="file_icons"><i class="fa fa-file-pdf-o"></i></a>
                                    </div>
                                <?php } ?>
                            </div>
                        </div>
                        
                        <?php if ($details->status_id == 17 || $details->status_id == 20 || $details->status_id == 21) { ?>
                            <div class="row">
                                <div class="pdf-container">
                                    <embed src="<?php echo base_url() . $details->dept_signed_pdf_path; ?>" type="application/pdf" width="100%" height="650px" internalinstanceid="8">
                                </div>
                                <div class="boxs-footer text-right bg-tr-black lter dvd dvd-top">
                                    <?php
                                        $pdftext = file_get_contents($details->dept_signed_pdf_path);
                                        //  $num_pag = preg_match_all("/\/Page\W/", $pdftext, $dummy);
                                        $num_pag = $total_pages;
                                        $price = 529;
                                        
                                    ?>
                                    <div class="form-group col-md-12"> 
                                            <label for="username">No. of Pages : <?php echo $num_pag; ?></label>
                                    </div>
                                    <div class="form-group col-md-12"> 
                                            <label for="username">Per Page Price : <?php echo $price; ?></label>
                                    </div>
                                    <div class="form-group col-md-12"> 
                                            <label for="username">Total : <?php echo $price * $num_pag; ?></label>
                                    </div>
                                    
                                    <?php echo form_open("https://uat.(StateName)treasury.gov.in/echallan/dept-intg", array("method" => "POST", "id" => "form1", "name" => "form1")); ?>
                                        
                                        <?php
                                            $total = $price * $num_pag;
                                            // Dept. Code
                                            $dept_code = "EGZ";
                                            // HoA Details
                                            $HoA = "0058-00-200-0127-02082-000";
                                            // Description
                                            $descp = "Extraordinary Gazette Publication";
                                            // Amount
                                            $amnt = $total;
                                            // Total Amount
                                            $tot_amnt = $total;
                                            // Depositor name
                                            $depositor_name = $dept_details->name;
                                            // Depositor Address
                                            $depositor_addr = $dept_details->department_name;
                                            // State
                                            $state = "(StateName)";
                                            // District
                                            $district = "Khudha";
                                            // Pincode
                                            $pincode = "751024";
                                            // Mobile
                                            $mobile = $dept_details->mobile;
                                            // Email
                                            $email = $dept_details->email;
                                            // Addl. Info
                                            $addl_info = $id;
                                            // Return URL
                                            $return_url =  base_url() . "gazette/payment_response";
                                            // Message Format
                                            $msg_format = $dept_code . "|" . uniqid(time()) . "|" . $HoA . "|" . $descp . "|". $amnt . "||||||||||||||||" . $tot_amnt . "|" . $depositor_name . "|" . $depositor_addr . "||" . $state . "|" . $district . "|" . $pincode ."|" . $mobile . "|" . $email . "|" . $addl_info . "||||||" . $return_url;
                                            // Function to be used for Encrypt the Message string
                                            function encrypt($data = '', $key = NULL) {
                                                if($key != NULL && $data != ""){
                                                    $method = "AES-256-ECB";
                                                    $encrypted = openssl_encrypt($data, $method, $key, OPENSSL_RAW_DATA);
                                                    $result = base64_encode($encrypted);
                                                    return $result;
                                                }else{
                                                    return "String to encrypt, Key is required.";
                                                }
                                            }
                                            
                                            // Binary file path
                                            $binary_file_path = $binary_key;
                                            // get size of the binary file
                                            $filesize = filesize($binary_file_path);
                                            $handle = fopen($binary_file_path, "rb");
                                            $secret_key = fread($handle, filesize($binary_file_path));
                                            // Checksum
                                            $checksum = hash_hmac('sha256', $msg_format, $secret_key, true);
                                            // Encrypted Message
                                            $chksum_msg = $msg_format . "|" . base64_encode($checksum);
                                            //OPENSSL_PKCS5_PADDING
                                            $encrypted_msg = encrypt($chksum_msg, $secret_key);
                                        ?>
                                        <input type="hidden" name="deptCode" value="<?php echo $dept_code; ?>"/>
                                        <input type="hidden" name="msg" value="<?php echo $encrypted_msg; ?>"/>
                                        <button type="submit" id="pay_online_button" class="btn btn-raised btn-success edit_btn">Proceed To Pay Online</button>
                                    <?php echo form_close(); ?>

                                    <?php echo form_open("make_payment/make_payment_department_offline", array("method" => "POST", "id" => "form_pay_offline", "name" => "form_pay_offline")); ?>
                                        <input type="hidden" name="record_id" value="<?php echo $id; ?>"/>
                                        <input type="hidden" name="tot_amnt" value="<?php echo $price; ?>"/>
                                        <input type="hidden" name="depositor_name" value="<?php echo $dept_details->name; ?>"/>
                                        <input type="hidden" name="mobile" value="<?php echo $dept_details->mobile; ?>"/>
                                        <input type="hidden" name="email" value="<?php echo $dept_details->email; ?>"/>
                                        <input type="hidden" name="dept_name" value="<?php echo $dept_details->department_name; ?>"/>
                                        <input type="hidden" name="deptCode" value="<?php echo $dept_code; ?>"/>
                                        <input type="hidden" name="msg" value="<?php echo $encrypted_msg; ?>"/>
                                        <button type="submit" name="btn_pay_offline" id="btn_pay_offline" class="btn btn-raised btn-success edit_btn offline_btn_color">Proceed To Pay Offline</button>
                                        <?php echo form_close(); ?>

                                </div>
                            </div>
                        <?php } ?>
                        <br/>
                        <div class="row">
                            <div class="col-lg-12 col-md-12">
                                <h4 class="custom-font hb-cyan">Status History</h4>
                                <ul class="list-group">
                                    <?php if (!empty($status_list)) { ?>
                                        <?php foreach ($status_list as $status) { ?>
                                            <?php
                                                if ($status->remarks != '') {
                                                    $remarks = " (Remarks: " . $status->remarks . ")";
                                                } else {
                                                    $remarks = '';
                                                }
                                            ?>
                                            <li class="list-group-item">
                                                <span class="badge bg-default"><?php echo get_formatted_datetime($status->created_at); ?></span><?php echo $status->status_name; ?> <span><?php echo $remarks; ?></span>
                                            </li>
                                        <?php } ?>
                                    <?php } ?>
                                </ul>
                            </div>
                        </div>

                        <!-- Documents History  -->

                        <div class="row">
                            <div class="col-lg-12 col-md-12">
                                <h4 class="custom-font hb-cyan">Document History</h4>
                                <ul class="list-group">
                                    <?php if (!empty($document_list)) { ?>
                                        <?php foreach ($document_list as $document) { ?>
                                            
                                            <li class="list-group-item">
                                                <span class="badge bg-default"><?php echo get_formatted_datetime($document->created_at); ?></span>

                                                <a href="<?php echo base_url() . $document->pdf_file_path; ?>" target="_blank" class="file_icons"><i class="fa fa-file-pdf-o"></i></a>
                                            </li>
                                        <?php } ?>
                                    <?php } ?>
                                </ul>
                            </div>
                        </div>

                        <?php if ($details->status_id == 11 || $details->status_id == 12 || $details->status_id == 13) { ?>
                        <div class="row">
                            <div class="boxs-header">
                                <h3 class="custom-font hb-cyan">Re-Submit Gazette</h3>
                            </div>
                            <?php echo validation_errors(); ?>
                            <?php echo form_open('gazette/resubmit_save_gazette', array('class' => "form1", 'name' => "resubmit", 'id' => 'resubmit', 'method' => "post", 'enctype' => "multipart/form-data")); ?>    
                                <input type="hidden" name="gazette_id" value="<?php echo $details->gazette_id; ?>"/>
                                <div class="boxs-body">
                                    <div class="row">
                                        <div class="form-group col-md-6">
                                            <label for="email">Gazette (Official Copy): <span class="asterisk">*</span>
                                                <span class="file_icons_add">
                                                <a href="<?php echo base_url() . "assets/pdf/extra-ordinary_dept_word_fomat.pdf";?>" target="_blank"><i class="fa fa-file-word-o"></i></a>
                                                </span>
                                            </label>
                                            <div class="row fileupload-buttonbar">
                                                <div class="col-lg-10">
                                                    <span class="btn btn-raised btn-success fileinput-button">
                                                        <i class="glyphicon glyphicon-plus"></i>
                                                        <span>Choose File</span>
                                                        <input type="file" name="doc_files" required="">
                                                    </span>
                                                    <span class="files"></span>
                                                </div>
                                            </div>
                                            <label class="error" id="doc_files-error" for="doc_files"></label>
                                            <span class="help-block mb-0">Maximum 5 MB allowed.</span>
                                        </div>
                                    </div>
                                </div>
                                <div class="boxs-footer text-right bg-tr-black lter dvd dvd-top">
                                    <button type="submit" class="btn btn-raised btn-success" id="form4Submit">Resubmit</button>
                                </div>
                            <?php echo form_close(); ?>
                        </div>
                        <?php } ?>
                    </div>
                </section>
            </div>
        </div>
    </div>
</section>
<script src="https://code.jquery.com/jquery-3.4.1.min.js" type="text/javascript" nonce="a6b9a780936c8e980939086f618dded2"></script>
<script type="text/javascript" nonce="8f0882ce3be14f201cadd0eff5726cbd">
$(document).ready(function() {
    // Attach a click event handler to the "Proceed To Pay Online" button
    $("#pay_online_button").click(function(e) {
        // Retrieve the values of the hidden input fields
        var deptCode = $("input[name='deptCode']").val();
        var depRefId = $("input[name='dep_ref_id']").val();
        var amount = $("input[name='amount']").val();
        var description = $("input[name='description']").val();
        e.preventDefault();
        // console.log(depRefId);
        // return false;

        // Send AJAX request to store the data
        $.ajax({
            url: "<?php echo base_url('DepRefController/storeDeptRefData'); ?>",
            type: "POST",
            data: {
                deptCode: deptCode,
                dep_ref_id: depRefId,
                amount: amount,
                description: description
                // "<?php// echo $this->security->get_csrf_token_name(); ?>": "<?php// echo $this->security->get_csrf_hash(); ?>"
            },
            dataType: "json",
            success: function(response) {
                if (response.success) {
                    // console.log("Data stored successfully.");
                    // Submit the form if data is stored successfully
                    $("#form1").submit();
                    // console.log(response);
                } else {
                    // console.error("Failed to store data.");
                    // Handle error scenario
                }
            },
            error: function(xhr, status, error) {
                console.error("AJAX request failed:::", error);
                return false;
                // Handle error scenario
            }
        });
    });
});
</script>
<script type="text/javascript" nonce="8f0882ce3be14f201cadd0eff5726cbd">
    $(document).ready(function () {
        $(".error").hide();
        $.validator.addMethod('filesize', function (value, element, param) {
            return this.optional(element) || (element.files[0].size <= param)
        }, 'File size must be less than 5 MB');

        $.validator.addMethod('filetype', function (value, element, param) {
            var ext = element.value.match(/\.(.+)$/)[1];
            return this.optional(element) || (ext == param)
        }, 'Please select only DOC (.docx) file');
        
        $("#resubmit").validate({
            rules : {
                doc_files : {
                    required : true,
                    filetype : 'docx',
                    filesize : 5000000
                }
            },
            messages : {
                doc_files : {
                    required : "Please select gazette (official copy)"
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