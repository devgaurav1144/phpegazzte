<?php defined('BASEPATH') OR exit('No direct script access allowed'); ?>
<!-- CONTENT -->

<!--<script src="<?php echo base_url(); ?>assets/js/image_crop.js" type="text/javascript" nonce="a6b9a780936c8e980939086f618dded2"></script>-->
<!--<script src="<?php echo base_url(); ?>assets/js/jcrop.js" type="text/javascript" nonce="a6b9a780936c8e980939086f618dded2"></script>-->
<!--<link type="text/css" rel="stylesheet" href="https://cdnjs.cloudflare.com/ajax/libs/jquery-jcrop/0.9.12/css/jquery.Jcrop.min.css"  nonce="a6b9a780936c8e980939086f618dded2"/>-->
<script src="https://code.jquery.com/jquery-3.4.1.min.js" type="text/javascript" nonce="a6b9a780936c8e980939086f618dded2"></script>
<!-- /content area -->
<div class="modal col-md-12" id="imageCropModal">
    <div class="modal-dialog">
        <div class="modal-content">
            <!-- Modal Header -->
            <div class="modal-header">
                <input type="hidden" name="div_id" id="div_id">
                <h4 class="modal-title">Crop Image</h4>
                <button type="button" class="close" data-dismiss="modal">&times;</button>
            </div>
            <!-- Modal body -->
            <div class="modal-body" >
                <span class='red-text'> <?php echo form_error('path'); ?> </span>     
                <img src="" class="crop" id="dp_preview" width = "100" height="100" />
            </div>
            <!-- Modal footer -->
            <div class="modal-footer">
                <button type="button" class="btn btn-success" data-dismiss="modal" onclick="uploadFile();">Submit</button>
            </div>
        </div>
    </div>
</div>

<!--/ CONTENT -->

<section id="content">
    <div class="page page-forms-validate">
        <!-- bradcome -->
        <div class="bg-light lter b-b wrapper-md mb-10">
            <div class="row">
                <div class="col-sm-6 col-xs-12">
                    <h1 class="h3 m-0">C&T User</h1>
                </div>
            </div>
        </div>
        <!-- row -->
        <div class="row">
            <div class="col-md-12">
                <section class="boxs">
                    <div class="boxs-header">
                        <h3 class="custom-font hb-blush">Add Partnership Details</h3>
                    </div>
                    
                    <?php echo form_open('', array('name' => 'form_pa',  'id' => 'form_pa', 'method' => 'post')); ?>
                        <div class="boxs-body">                       
                            <div class="row">                                           
                                <div class="form-group col-md-6">
                                    <label for="email">Gazette type: </label>
                                    <select name="gazette_id" id="gazette_id" class="form-control" required="">
                                     <option value="">Select Gazette Type</option>
                                     <?php if(!empty($gz_types)){ ?>
                                         <?php foreach ($gz_types as $gz_type){ ?>
                                         <option value="<?php echo $gz_type->id; ?>" <?php if($get_part_dets->gazette_type_id == $gz_type->id) { echo "selected"; } ?> ><?php echo $gz_type->gazette_type; ?></option>
                                         <?php } ?>
                                     <?php } ?>
                                    </select>
                                    <?php if (form_error('gazette_id')) { ?>
                                        <span class="error"><?php echo form_error('gazette_id'); ?></span>
                                    <?php } ?>
                                </div>
                            </div>
                            <div class="row" > 
                            <?php //print_r($tot_docus);
                            foreach ($tot_docus as $tot_docu) { ?>
                               
                                <table >
                                    <tr >
                                        <td width = "500"><li><?php echo $tot_docu->document_name; ?></li></td>
                                        <td><input type="file" name = "upload_<?php echo $tot_docu->id; ?>" id = "upload_<?php echo $tot_docu->id; ?>" >
                                    <input type="hidden" id="img_id_<?php echo $tot_docu->id; ?>" name ="img_id_<?php echo $tot_docu->id; ?>" />
                                    <input type="hidden" id="count" name ="count" value="<?php echo $count; ?>" /></td>
                                        <td>
                                            <img scr ="<?php echo $get_part_dets->document_name ?>" id ="imag_dw" name ="imag_dw" height="42" width="42" />                                            
                                        </td>
                                    
<!--</tr><br>                   <input type="hidden" id="x_<?php// echo $tot_docu->id; ?>" name="x" />
                           
                            <input type="hidden" id="y_<?php //e/cho $tot_docu->id; ?>" name="y" />
                            <input type="hidden" name="x2_<?php //echo $tot_docu->id; ?>" id="x2" />
                            <input type="hidden" name="y2_<?php //echo $tot_docu->id; ?>" id="y2" />
                            <input type="hidden" id="w_<?php //echo $tot_docu->id; ?>" name="w" />
                            <input type="hidden" id="h_<?php// echo $tot_docu->id; ?>" name="h" />-->
                                </table>
                                <script type="text/javascript" nonce="a6b9a780936c8e980939086f618dded2">
                                    $('#upload_<?php  echo $tot_docu->id; ?>').change(function () {

                                        var input_file = $('#upload_<?php  echo $tot_docu->id; ?>').prop('files')[0];

                                        var id = <?php  echo $tot_docu->id; ?>;
                                        var reader = new FileReader();
                                        reader.onload = function (e) {

                                        };
                                        reader.readAsDataURL(input_file);
                                        upload_notification_file(input_file,id);
                                    });
//                              
//                                $('#upload_<?php // echo $tot_docu->id; ?>').change(function () {
//                                   
//                                     $("#div_id").val(<?php //echo $tot_docu->id; ?>);
//                                    $("#imageCropModal").modal('show');
//                                    readURL(this);
//                                });
//
//                                function readURL(input) {
//                                    //alert(input.files[0]);
//                                    if (input.files[0]) {
//                                        var reader = new FileReader();
//
//                                        reader.onload = function (e) {
//                                            $('.jcrop-holder').replaceWith('');
//                                            $('#dp_preview').replaceWith('<img class="crop" id="dp_preview" src="' + e.target.result + '"/>');
//
//                                            $('.crop').Jcrop({
//                                                onSelect: updateCoords,
//                                                onChange: updateCoords,
//                                                bgOpacity: '.5',
//                                                bgColor: 'black',
//                                                addClass: 'jcrop-dark',
//                                                maxSize: [900, 900]
//                                            });
//                                        }
//
//                                        reader.readAsDataURL(input.files[0]);
//                                    }
//                                }
//                                
//                                function updateCoords(c) {
//                                    $('#x_<?php //echo $tot_docu->id; ?>').val(c.x);
//                                    $('#y_<?php //echo $tot_docu->id; ?>').val(c.y);
//                                    $('#x2_<?php //echo $tot_docu->id; ?>').val(c.x2);
//                                    $('#y2_<?php //echo $tot_docu->id; ?>').val(c.y2);
//                                    $('#w_<?php //echo $tot_docu->id; ?>').val(c.w);
//                                    $('#h_<?php //echo $tot_docu->id; ?>').val(c.h);
//                                    // console.log(c);
//                                };
//
//                                 function uploadFile()
//                                 {               
//                                        var i = <?php //echo $tot_docu->id; ?>;
//                             //                var incre_id = $("#incre_id").val(); 
//                             //                
//                             //                //alert(incre_id);
//                             //                for (var i = 1; i < incre_id; i++)
//                             //                {
//                                         //var id = i;
//                                         var file_data = $('#upload_<?php //echo $tot_docu->id; ?>').prop('files')[0];
//                                         //alert(file_data);
//                                         var x = $('#x_' +  i).val();
//                                         var y = $('#y_' +  i).val();
//                                         var x2 = $('#x2_' +  i).val();
//                                         var y2 = $('#y2_' +  i).val();
//                                         var w = $('#w_' +  i).val();
//                                         var h = $('#h_' +  i).val();
//
//                                         var form_data = new FormData();
//
//                                         form_data.append('file',file_data);
//                                         form_data.append('x',x);
//                                         form_data.append('y',y);
//                                         form_data.append('x2',x2);
//                                         form_data.append('y2',y2);
//                                         form_data.append('w',w);
//                                         form_data.append('h',h);
//
//                                         $.ajax({
//                                             url: '<?php//echo base_url(); ?>' + "applicant_login/upload_document",
//                                             dataType: 'text', // what to expect back from the PHP script, if anything
//                                             cache: false,
//                                             contentType: false,
//                                             processData: false,
//                                             data: form_data,    
//                                             type: 'post',
//                                             success: function (php_script_response) {
//                                                //alert(php_script_response);
//                                                 if(php_script_response){
//                                                   
//                                                     $('#img_id_'+i).val(php_script_response.trim()); 
//                                                 }
//                                             }
//                                         });
//                             //                }                
//
//                                 }

       // for append div of CBO dropdown and No. Of Participants
   
    

            /*
 * Upload notification file
 */
function upload_notification_file(file,id) {
    var fd = new FormData();
    var token = $("input[name=<?php echo $this->security->get_csrf_token_name(); ?>]").val();    
    fd.append('file', file);
    fd.append('<?php echo $this->security->get_csrf_token_name(); ?>', token);
    //$('.loader').show();
    // make ajax request
    $.ajax({
        type: "POST",
        url: "<?php echo base_url(); ?>applicant_login/upload_document",
        data: fd,
        contentType: false,
        processData: false,
        success: function (msg) {
            //alert(<?php //echo $tot_docu->id; ?>);
            var json = JSON.parse(msg);
            //console.log(json);
            if (json['success']) {
                //alert('ok');
                    $(".notif_file").hide();
                    $(".notif_file").html();
                    $('#img_id_'+id).val(json['success']);
                   
            }
            if (json['error']) {
                    $('.loader').hide();
                    $(".notif_file").html(json['error']);
                    $('#img_id_'+id).val('');
                    $(".notif_file").show();
            }
        },
        error: function (msg) {
                $('.loader').hide();
        }
    });
}
    
                                </script>
                            <?php } ?>
                            
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


<script type="text/javascript" nonce="a6b9a780936c8e980939086f618dded2">
    
$(document).ready(function () {
    /*
    * script for image cropping
    */
  

   
       
    //alert('ok');
    $('#form_pa').validate({
        // initialize the plugin

        rules: {
            
            gazette_id: {
                required: true
            }
               
        },
        messages: {
            gazette_id: {
                required: "Please enter fazette type"
            }
        },
        submitHandler: function (form) {
           //alert('ok');
           $.ajax({
               type: "POST",
               url: "<?php echo base_url(); ?>applicant_login/insert_partnership_details",
               //data: $('#shg_details_frm input[type=\'text\'], #shg_details_frm select, #shg_details_frm textarea, #shg_details_frm input[type="hidden"]'),
               data: $(form).serialize(),
               dataType: 'json',
               success: function (json) {
                   //alert()
                   if (json['redirect']) {
                       window.location = json['redirect'];
                   } else if (json['error']) {
                       if (json['error']['message']) {
                           $('#form_pa').prepend('<div class="alert bg-danger"><button type="button" class="close" data-dismiss="alert"><span>Ã—</span><span class="sr-only">Close</span></button><span class="text-semibold"></span>' + json['error']['warning'] + '</div>');
                       }

                        for (i in json['error']) {
                           var element = $('#' + i);
                           $(element).parent().find('.error').remove();
                           $(element).after('<span class="error">' + json['error'][i] + '</span>');
                        }
                        
                   } else {
                       $(element).parent().find('.error').remove();
                   }
               }
           });
       }
        
    }); 
    
}); 

</script>
