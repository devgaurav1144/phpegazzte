<?php defined('BASEPATH') OR exit('No direct script access allowed'); ?>
<style rel="stylesheet" nonce="8f0882ce3be14f201cadd0eff5726cbd">
	.header_image {
		background-image: url('<?php echo base_url(); ?>assets/images/login-bg.jpg'); background-size: cover; background-position: top center;
	}
</style>
<!doctype html>
<html class="no-js" lang="">
    <head>
        <meta charset="utf-8"/>
        <link rel="shortcut icon" href="favicon.ico" type="image/x-icon">
        <link rel="icon" href="favicon.ico" type="image/x-icon">
        <title>:: 404 Page Not Found ::</title>
        <meta content="width=device-width, initial-scale=1.0, maximum-scale=1.0, user-scalable=0" name="viewport" />
        <link rel="stylesheet" href="<?php echo base_url(); ?>assets/js/vendor/bootstrap/bootstrap.min.css" nonce="8f0882ce3be14f201cadd0eff5726cbd">
        <link href="<?php echo base_url(); ?>assets/css/main.css" rel="stylesheet" nonce="8f0882ce3be14f201cadd0eff5726cbd">
    </head>
    <body id="falcon" class="authentication">
        <!--  Application Content  -->
        <div class="wrapper">
            <div class="header header-filter header_image">
                <div class="container">
                    <div class="row">
                        <div class="col-md-8 col-md-offset-2 col-sm-12 col-sm-offset-6 text-center">
                            <div class="card card-signup">
                                <div class="header header-primary text-center">
                                    <h4>We are unable to find this page for you.</h4>
                                </div>
                                <div class="content">
                                    <div class="row">
                                        <div class="col-md-12 text-center">
                                            <h4 class="mb-0 mt-40">Page not found</h4>
                                            <p class="text-muted">The page you are looking for doesn't exist.</p>
                                        </div>
                                    </div>
                                    <!-- /input-group -->
                                    <div class="footer text-center mb-20"><a href="<?php echo base_url(); ?>" class="btn btn-info btn-raised"><i class="fa fa-home mr-10"></i>Return to home</a></div>
                                </div>
                            </div>
                        </div>
                    </div>
                </div>
                <footer class="footer">
                    <div class="container">
                        <div class="row"></div>
                    </div>
                </footer>
            </div>
        </div>
        <!--/ Application Content -->
        <!--  Vendor JavaScripts --> 
        <script src="<?php echo base_url(); ?>assets/bundles/libscripts.bundle.js" type="text/javascript" nonce="a6b9a780936c8e980939086f618dded2"></script>
        <script src="<?php echo base_url(); ?>assets/bundles/mainscripts.bundle.js" type="text/javascript" nonce="a6b9a780936c8e980939086f618dded2"></script>
        <!-- Custom Js -->
    </body>
</html>
