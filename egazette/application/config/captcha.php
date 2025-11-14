<?php
defined('BASEPATH') OR exit('No direct script access allowed');

// BotDetect PHP Captcha configuration options
// more details here: https://captcha.com/doc/php/captcha-options.html
// ----------------------------------------------------------------------------

$config = array(
    /*
    |--------------------------------------------------------------------------
    | Captcha configuration for example page
    |--------------------------------------------------------------------------
    */
//    'ExampleCaptcha' => array(
//        'UserInputID' => 'CaptchaCode',
//        'ImageWidth' => 250,
//        'ImageHeight' => 50,
//    ),

    /*
    |--------------------------------------------------------------------------
    | Captcha configuration for contact page
    |--------------------------------------------------------------------------
    */
    'LoginCaptcha' => array(
        'UserInputID' => 'captcha',
        'CodeLength' => 5,
        'SoundEnabled' => FALSE,
        'ImageWidth' => 172,
        'ImageHeight' => 50,
    ),


// Captcha for Forget Password Page

    'ForgotCaptcha' =>  array(
        'UserInputID' => 'captcha',
        'CodeLength' => 5,
        'SoundEnabled' => FALSE,
        'ImageWidth' => 172,
        'ImageHeight' => 50,
    ),



    /*
    |--------------------------------------------------------------------------
    | Captcha configuration for login page
    |--------------------------------------------------------------------------
    */
//    'LoginCaptcha' => array(
//        'UserInputID' => 'CaptchaCode',
//        'CodeLength' => CaptchaRandomization::GetRandomCodeLength(5, 5),
//        'ImageStyle' => array(
//            ImageStyle::Radar,
//            ImageStyle::Collage,
//            ImageStyle::Fingerprints,
//        ),
//    )
    // Add more your Captcha configuration here...
);
