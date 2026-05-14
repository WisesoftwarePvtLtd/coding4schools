[33m0521787[m[33m ([m[1;36mHEAD[m[33m -> [m[1;32mmain[m[33m, [m[1;31morigin/main[m[33m, [m[1;31morigin/HEAD[m[33m, [m[1;32mtodayjan_fix[m[33m, [m[1;32mrecover-todayjan-fix[m[33m)[m Merged todayjan_fix branch

[1mdiff --cc Register.php[m
[1mindex 7b31cd7,4ebed3b..c3f39c4[m
[1m--- a/Register.php[m
[1m+++ b/Register.php[m
[36m@@@ -1,244 -1,107 +1,354 @@@[m
[32m++<<<<<<< HEAD[m
[32m +[m
[32m +[m
[32m +[m
[32m +[m
[32m +<!DOCTYPE html>[m
[32m +<html lang="en">[m
[32m +[m
[32m +<head>[m
[32m +    <title>Coding First</title>[m
[32m +    [m
[32m +<style>[m
[32m +    .CommonLoader{ position: fixed; top:0; left:0; right:0; bottom:0; background-color: rgba(255,255,255,0.95); z-index: 98; background-image: url("https://www.codingfirst.org/images/spinner.svg"); background-position: center; background-repeat: no-repeat; background-size: 150px; animation: FadeIn linear 0.3s; }[m
[32m +    .CommonLoader2{ background-color: rgba(255,255,255,1) !important; z-index: 99999999  !important;; animation: FadeIn linear 0s  !important;; }[m
[32m +</style>[m
[32m +[m
[32m +[m
[32m +<link href="https://www.codingfirst.org/css/style.css?1764565878" rel="stylesheet" media="all">[m
[32m +<link href="https://www.codingfirst.org/css/course_module.css?1764565878" rel="stylesheet" media="all">[m
[32m +[m
[32m +<script src="https://www.codingfirst.org/js/require.js/require.js" ></script>[m
[32m +[m
[32m +[m
[32m +<meta name="theme-color" content="#000">[m
[32m +<meta name="msapplication-navbutton-color" content="#000">[m
[32m +<meta name="apple-mobile-web-app-status-bar-style" content="#000">[m
[32m +[m
[32m +<link rel="apple-touch-icon-precomposed" sizes="57x57" href="https://www.codingfirst.org/images/favicon/apple-touch-icon-57x57.png" />[m
[32m +<link rel="apple-touch-icon-precomposed" sizes="114x114" href="https://www.codingfirst.org/images/favicon/apple-touch-icon-114x114.png" />[m
[32m +<link rel="apple-touch-icon-precomposed" sizes="72x72" href="https://www.codingfirst.org/images/favicon/apple-touch-icon-72x72.png" />[m
[32m +<link rel="apple-touch-icon-precomposed" sizes="144x144" href="https://www.codingfirst.org/images/favicon/apple-touch-icon-144x144.png" />[m
[32m +<link rel="apple-touch-icon-precomposed" sizes="60x60" href="https://www.codingfirst.org/images/favicon/apple-touch-icon-60x60.png" />[m
[32m +<link rel="apple-touch-icon-precomposed" sizes="120x120" href="https://www.codingfirst.org/images/favicon/apple-touch-icon-120x120.png" />[m
[32m +<link rel="apple-touch-icon-precomposed" sizes="76x76" href="https://www.codingfirst.org/images/favicon/apple-touch-icon-76x76.png" />[m
[32m +<link rel="apple-touch-icon-precomposed" sizes="152x152" href="https://www.codingfirst.org/images/favicon/apple-touch-icon-152x152.png" />[m
[32m +<link rel="icon" type="image/png" href="https://www.codingfirst.org/images/favicon/favicon-196x196.png" sizes="196x196" />[m
[32m +<link rel="icon" type="image/png" href="https://www.codingfirst.org/images/favicon/favicon-96x96.png" sizes="96x96" />[m
[32m +<link rel="icon" type="image/png" href="https://www.codingfirst.org/images/favicon/favicon-32x32.png" sizes="32x32" />[m
[32m +<link rel="icon" type="image/png" href="https://www.codingfirst.org/images/favicon/favicon-16x16.png" sizes="16x16" />[m
[32m +<link rel="icon" type="image/png" href="https://www.codingfirst.org/images/favicon/favicon-128.png" sizes="128x128" />[m
[32m +<meta name="application-name" content="&nbsp;"/>[m
[32m +<meta name="msapplication-TileColor" content="#FFFFFF" />[m
[32m +<meta name="msapplication-TileImage" content="https://www.codingfirst.org/images/favicon/mstile-144x144.png" />[m
[32m +<meta name="msapplication-square70x70logo" content=https://www.codingfirst.org/images/favicon/"mstile-70x70.png" />[m
[32m +<meta name="msapplication-square150x150logo" content="https://www.codingfirst.org/images/favicon/mstile-150x150.png" />[m
[32m +<meta name="msapplication-wide310x150logo" content="https://www.codingfirst.org/images/favicon/mstile-310x150.png" />[m
[32m +<meta name="msapplication-square310x310logo" content="https://www.codingfirst.org/images/favicon/mstile-310x310.png" />[m
[32m +[m
[32m +<!-- Google Tag Manager -->[m
[32m +<script>(function(w,d,s,l,i){w[l]=w[l]||[];w[l].push({'gtm.start':[m
[32m +new Date().getTime(),event:'gtm.js'});var f=d.getElementsByTagName(s)[0],[m
[32m +j=d.createElement(s),dl=l!='dataLayer'?'&l='+l:'';j.async=true;j.src=[m
[32m +'https://www.googletagmanager.com/gtm.js?id='+i+dl;f.parentNode.insertBefore(j,f);[m
[32m +})(window,document,'script','dataLayer','GTM-TD552ZH');</script>[m
[32m +<!-- End Google Tag Manager -->[m
[32m +[m
[32m +<script src="https://www.google.com/recaptcha/api.js" async defer></script>[m
[32m +[m
[32m +[m
[32m +    <meta name="robots" content="all, index, follow" >[m
[32m +    <meta name="viewport" content="width=device-width, initial-scale=1, maximum-scale=1">[m
[32m +    <link href="https://www.codingfirst.org" rel="canonical">[m
[32m +</head>[m
[32m +[m
[32m +<body>[m
[32m +[m
[32m +<?php[m
[32m +include '../../header.php';[m
[32m +?>[m
[32m +[m
[32m +<div class="login-body">[m
[32m +[m
[32m +    <div class="login-content">[m
[32m +[m
[32m +        <div class="flex-center loginSection" style="width: 100%;padding: 30px;">[m
[32m +[m
[32m +            <div class="loginBox">[m
[32m +                <form name="login-form" method="post" id="registration_submit">[m
[32m +                    <div class="form-label-black">[m
[32m +                        <img src="https://www.codingfirst.org/images/icons/teacher-svgrepo-com.svg">[m
[32m +[m
[32m +                        Teacher Registration </div>[m
[32m +                    <input class="formfield" type="text" name="first_name" placeholder="First Name"   required />[m
[32m +                    <input class="formfield" type="text" name="last_name" placeholder="Last Name"   required />[m
[32m +                    <input class="formfield" type="text" name="t_email" placeholder="Email"   required />[m
[32m +                    <input class="formfield" type="password" name="t_password" placeholder="Password" required />[m
[32m +                    <input class="formfield" type="password" name="conf_t_password" placeholder="Confirm Password" required />[m
[32m +                    <input type="hidden" name="act" value="teacher_registration">[m
[32m +[m
[32m +                    <div style="position: relative; height: 90px" class="captcha_wrapper">[m
[32m +                        <div id="recaptcha-registration" class="g-recaptcha" data-sitekey="6LeRjJYaAAAAAITip3rCKqMOOT_I79H20IEM_zp5"></div>[m
[32m +                    </div>[m
[32m +[m
[32m +                    <input type="submit" class="signinSubmit" value="Register">[m
[32m +[m
[32m +                </form>[m
[32m +                <div class="form-footer">Already have an account?  <a href="https://www.codingfirst.org/Login">Log in</a></div>[m
[32m +[m
[32m +[m
[32m +                <br><br><br>[m
[32m +            </div>[m
[32m +        </div>[m
[32m +[m
[32m +    </div>[m
[32m +[m
[32m +[m
[32m +[m
[32m +</div>[m
[32m +[m
[32m +[m
[32m +[m
[32m +[m
[32m +[m
[32m +[m
[32m +[m
[32m +<?php[m
[32m +include '../../footer.php';[m
[32m +?>[m
[32m +<script language="javascript">[m
[32m +    function FooterFunctionsCommon(){[m
[32m +        [m
[32m +             function disableSelection(target){[m
[32m +            if (typeof target.onselectstart!="undefined") //For IE[m
[32m +                target.onselectstart=function(){return false}[m
[32m +            else if (typeof target.style.MozUserSelect!="undefined") //For Firefox[m
[32m +                target.style.MozUserSelect="none"[m
[32m +            else //All other route (For Opera)[m
[32m +                target.onmousedown=function(){return false}[m
[32m +            target.style.cursor = "default"[m
[32m +        }[m
[32m +[m
[32m +        disableSel