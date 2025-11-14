<?php defined('BASEPATH') OR exit('No direct script access allowed'); ?>
<!-- CONTENT -->
<section id="content">
    <div class="page page-forms-validate">
        <!-- bradcome -->
        <ol class="breadcrumb">
            <li><a href="<?php echo base_url(); ?>applicants_login/dashboard">Dashboard</a></li>
            <li class="active">CMS</li>
            <li class="active">Disclaimer</li>
        </ol>
        <!-- row -->
        <div class="row">
            <div class="col-md-12">
                <section class="boxs">
                    <div class="boxs-header">
                        <h3 class="custom-font hb-blue"><strong>Disclaimer</strong></h3>
                    </div>
                    <?php echo form_open('Cms_System/disclaimer', array('name' => "form1", 'role' => "form", 'id' => "smtp_form", 'enctype' => "multipart/form-data", 'method' => "post")); ?>    
                        <div class="boxs-body">
                            <div class="row">
                                <div class="form-group col-md-12">
                                    <label for="email">Description : <span class="asterisk">*</span></label>
                                    <textarea class="form-control" name="disclaimer_desc" id="disclaimer_desc"><?php if($disclaimer[0]['cms_desc']){echo $disclaimer[0]['cms_desc'];}?></textarea>
                                    <?php if (form_error('disclaimer_desc')) { ?>
                                        <span class="error"><?php echo form_error('disclaimer_desc'); ?></span>
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
<!--/ CONTENT -->
<script src="<?php echo base_url(); ?>assets/js/vendor/tinymce/js/tinymce/tinymce.min.js" type="text/javascript" nonce="a6b9a780936c8e980939086f618dded2"></script>
<script type="text/javascript" nonce="a6b9a780936c8e980939086f618dded2">
    tinymce.init({
        selector: '#disclaimer_desc',
        plugins: 'print preview fullpage powerpaste searchreplace autolink directionality advcode visualblocks visualchars fullscreen image link media mediaembed template codesample table charmap hr pagebreak nonbreaking anchor toc insertdatetime advlist lists wordcount tinymcespellchecker a11ychecker imagetools textpattern help formatpainter code  permanentpen pageembed tinycomments mentions linkchecker contextmenu',
        height: 400,
        branding: false,
        toolbar: 'bold italic strikethrough forecolor backcolor | link image media pageembed | alignleft aligncenter alignright alignjustify  | numlist bullist outdent indent | removeformat',
        icons: 'material',
        menu: {
             file: {title: 'File', items: 'newdocument'},
             edit: {title: 'Edit', items: 'undo redo | cut copy paste pastetext | selectall'},
             view: {title: 'View', items: 'visualaid'},
             format: {title: 'Format', items: 'bold italic underline strikethrough superscript subscript | formats | removeformat'},
             table: {title: 'Table', items: 'inserttable tableprops deletetable | cell row column'},
             tools: {title: 'Tools', items: 'spellchecker code'}
        },
        menubar: 'file edit insert view format table tools',
        image_advtab: true,
        content_css: [
          '//fonts.googleapis.com/css?family=Lato:300,300i,400,400i',
          '//www.tiny.cloud/css/codepen.min.css'
        ],
        link_list: [
          { title: 'My page 1', value: 'http://www.tinymce.com' },
          { title: 'My page 2', value: 'http://www.moxiecode.com' }
        ],
        image_list: [
          { title: 'My page 1', value: 'http://www.tinymce.com' },
          { title: 'My page 2', value: 'http://www.moxiecode.com' }
        ],
        image_class_list: [
          { title: 'None', value: '' },
          { title: 'Some class', value: 'class-name' }
        ],
        importcss_append: true,
        file_picker_callback: function (callback, value, meta) {
          /* Provide file and text for the link dialog */
          if (meta.filetype === 'file') {
            callback('https://www.google.com/logos/google.jpg', { text: 'My text' });
          }
          /* Provide image and alt text for the image dialog */
          if (meta.filetype === 'image') {
            callback('https://www.google.com/logos/google.jpg', { alt: 'My alt text' });
          }
          /* Provide alternative source and posted for the media dialog */
          if (meta.filetype === 'media') {
            callback('movie.mp4', { source2: 'alt.ogg', poster: 'https://www.google.com/logos/google.jpg' });
          }
        },
        templates: [
          { title: 'Some title 1', description: 'Some desc 1', content: 'My content' },
          { title: 'Some title 2', description: 'Some desc 2', content: '<div class="mceTmpl"><span class="cdate">cdate</span><span class="mdate">mdate</span>My content2</div>' }
        ],
        template_cdate_format: '[CDATE: %m/%d/%Y : %H:%M:%S]',
        template_mdate_format: '[MDATE: %m/%d/%Y : %H:%M:%S]',
        image_caption: true,
        spellchecker_dialog: true,
        spellchecker_whitelist: ['Ephox', 'Moxiecode'],
        tinycomments_mode: 'embedded',
        formatpainter_removeformat: [ 
            { 
                selector: 'b,strong,em,i,font,u,strike,sub,sup,dfn,code,samp,kbd,var,cite,mark,q,del,ins', 
                remove: 'all', 
                split: true, 
                expand: false, 
                block_expand: true, 
                deep: true 
            },
            { 
                selector: 'span', 
                attributes: ['style', 'class'], 
                remove: 'empty', 
                split: true, 
                expand: false, 
                deep: true 
            },
            { 
            selector: '*:not(tr,td,th,table)', 
            attributes: ['style', 'class'], 
            split: false, 
            expand: false, 
            deep: true 
            }
        ],
        content_style: '.mce-annotation { background: #fff0b7; } .tc-active-annotation {background: #ffe168; color: black; }'
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