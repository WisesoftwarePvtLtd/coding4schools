
<?php 
?>


<!DOCTYPE html>
<html lang="en">

<head>
    <title>Coding First</title>
    
<style>
    .CommonLoader{ position: fixed; top:0; left:0; right:0; bottom:0; background-color: rgba(255,255,255,0.95); z-index: 98; background-image: url("https://www.codingfirst.org/images/spinner.svg"); background-position: center; background-repeat: no-repeat; background-size: 150px; animation: FadeIn linear 0.3s; }
    .CommonLoader2{ background-color: rgba(255,255,255,1) !important; z-index: 99999999  !important;; animation: FadeIn linear 0s  !important;; }
</style>


<link href="https://www.codingfirst.org/css/style.css?1764565878" rel="stylesheet" media="all">
<link href="https://www.codingfirst.org/css/course_module.css?1764565878" rel="stylesheet" media="all">

<script src="https://www.codingfirst.org/js/require.js/require.js" ></script>


<meta name="theme-color" content="#000">
<meta name="msapplication-navbutton-color" content="#000">
<meta name="apple-mobile-web-app-status-bar-style" content="#000">

<link rel="apple-touch-icon-precomposed" sizes="57x57" href="https://www.codingfirst.org/images/favicon/apple-touch-icon-57x57.png" />
<link rel="apple-touch-icon-precomposed" sizes="114x114" href="https://www.codingfirst.org/images/favicon/apple-touch-icon-114x114.png" />
<link rel="apple-touch-icon-precomposed" sizes="72x72" href="https://www.codingfirst.org/images/favicon/apple-touch-icon-72x72.png" />
<link rel="apple-touch-icon-precomposed" sizes="144x144" href="https://www.codingfirst.org/images/favicon/apple-touch-icon-144x144.png" />
<link rel="apple-touch-icon-precomposed" sizes="60x60" href="https://www.codingfirst.org/images/favicon/apple-touch-icon-60x60.png" />
<link rel="apple-touch-icon-precomposed" sizes="120x120" href="https://www.codingfirst.org/images/favicon/apple-touch-icon-120x120.png" />
<link rel="apple-touch-icon-precomposed" sizes="76x76" href="https://www.codingfirst.org/images/favicon/apple-touch-icon-76x76.png" />
<link rel="apple-touch-icon-precomposed" sizes="152x152" href="https://www.codingfirst.org/images/favicon/apple-touch-icon-152x152.png" />
<link rel="icon" type="image/png" href="https://www.codingfirst.org/images/favicon/favicon-196x196.png" sizes="196x196" />
<link rel="icon" type="image/png" href="https://www.codingfirst.org/images/favicon/favicon-96x96.png" sizes="96x96" />
<link rel="icon" type="image/png" href="https://www.codingfirst.org/images/favicon/favicon-32x32.png" sizes="32x32" />
<link rel="icon" type="image/png" href="https://www.codingfirst.org/images/favicon/favicon-16x16.png" sizes="16x16" />
<link rel="icon" type="image/png" href="https://www.codingfirst.org/images/favicon/favicon-128.png" sizes="128x128" />
<meta name="application-name" content="&nbsp;"/>
<meta name="msapplication-TileColor" content="#FFFFFF" />
<meta name="msapplication-TileImage" content="https://www.codingfirst.org/images/favicon/mstile-144x144.png" />
<meta name="msapplication-square70x70logo" content=https://www.codingfirst.org/images/favicon/"mstile-70x70.png" />
<meta name="msapplication-square150x150logo" content="https://www.codingfirst.org/images/favicon/mstile-150x150.png" />
<meta name="msapplication-wide310x150logo" content="https://www.codingfirst.org/images/favicon/mstile-310x150.png" />
<meta name="msapplication-square310x310logo" content="https://www.codingfirst.org/images/favicon/mstile-310x310.png" />

<!-- Google Tag Manager -->
<script>(function(w,d,s,l,i){w[l]=w[l]||[];w[l].push({'gtm.start':
new Date().getTime(),event:'gtm.js'});var f=d.getElementsByTagName(s)[0],
j=d.createElement(s),dl=l!='dataLayer'?'&l='+l:'';j.async=true;j.src=
'https://www.googletagmanager.com/gtm.js?id='+i+dl;f.parentNode.insertBefore(j,f);
})(window,document,'script','dataLayer','GTM-TD552ZH');</script>
<!-- End Google Tag Manager -->

<script src="https://www.google.com/recaptcha/api.js" async defer></script>


    <meta name="robots" content="all, index, follow" >
    <meta name="viewport" content="width=device-width, initial-scale=1, maximum-scale=1">
    <link href="https://www.codingfirst.org" rel="canonical">
</head>

<body>

<?php
include '../../header.php';
?>

<div class="login-body">

    <div class="login-content">

        <div class="flex-center loginSection" style="width: 100%;padding: 30px;">

            <div class="loginBox">
                <form name="login-form" method="post" id="registration_submit">
                    <div class="form-label-black">
                        <img src="https://www.codingfirst.org/images/icons/teacher-svgrepo-com.svg">

                        Teacher Registration </div>
                    <input class="formfield" type="text" name="first_name" placeholder="First Name"   required />
                    <input class="formfield" type="text" name="last_name" placeholder="Last Name"   required />
                    <input class="formfield" type="text" name="t_email" placeholder="Email"   required />
                    <input class="formfield" type="password" name="t_password" placeholder="Password" required />
                    <input class="formfield" type="password" name="conf_t_password" placeholder="Confirm Password" required />
                    <input type="hidden" name="act" value="teacher_registration">

                    <div style="position: relative; height: 90px" class="captcha_wrapper">
                        <div id="recaptcha-registration" class="g-recaptcha" data-sitekey="6LeRjJYaAAAAAITip3rCKqMOOT_I79H20IEM_zp5"></div>
                    </div>

                    <input type="submit" class="signinSubmit" value="Register">

                </form>
                <div class="form-footer">Already have an account?  <a href="https://www.codingfirst.org/Login">Log in</a></div>


                <br><br><br>
            </div>
        </div>

    </div>



</div>







<?php
include '../../footer.php';
?>
<script language="javascript">
    function FooterFunctionsCommon(){
        
             function disableSelection(target){
            if (typeof target.onselectstart!="undefined") //For IE
                target.onselectstart=function(){return false}
            else if (typeof target.style.MozUserSelect!="undefined") //For Firefox
                target.style.MozUserSelect="none"
            else //All other route (For Opera)
                target.onmousedown=function(){return false}
            target.style.cursor = "default"
        }

        disableSelection(document.body)
        
          // Screenshot detection
        function logScreenshot(type) {
            $.ajax({
                url: 'https://www.codingfirst.org/log_screenshot.php',
                type: 'POST',
                data: {
                    pagelink: window.location.href,
                    type: type,
                    device: navigator.userAgent
                },
                success: function(response) {
                    console.log('Screenshot logged');
                }
            });
        }

      // Track Cmd+Shift detection with debounce
        var lastCmdShiftTime = 0;
        var cmdShiftDebounce = 2000; // 2 seconds cooldown
        
        // Detect keyboard shortcuts for screenshots
        document.addEventListener('keydown', function(e) {
            console.log('Key:', e.key, 'Meta:', e.metaKey, 'Shift:', e.shiftKey, 'Ctrl:', e.ctrlKey);
            
            // Mac: Detect Cmd+Shift (workaround since full shortcut is blocked by OS)
            if (e.metaKey && e.shiftKey && e.key === 'Shift') {
                var now = Date.now();
                if (now - lastCmdShiftTime > cmdShiftDebounce) {
                    console.log('Cmd+Shift detected - possible screenshot attempt');
                    logScreenshot('Mac - Cmd+Shift Detected (Possible Screenshot)');
                    lastCmdShiftTime = now;
                }
            }
            
            // Mac: If somehow the number gets through (unlikely but worth trying)
            if (e.metaKey && e.shiftKey && (e.key === '3' || e.key === '4' || e.key === '5')) {
                console.log('Full Mac screenshot shortcut detected!');
                logScreenshot('Mac Screenshot (Cmd+Shift+' + e.key + ')');
            }
            
            // Windows: PrtScn, Alt+PrtScn, Win+Shift+S
            if (e.key === 'PrintScreen') {
                if (e.altKey) {
                    logScreenshot('Windows Screenshot (Alt+PrtScn)');
                } else if (e.metaKey && e.shiftKey) {
                    logScreenshot('Windows Screenshot (Win+Shift+S)');
                } else {
                    logScreenshot('Windows Screenshot (PrtScn)');
                }
            }
        });

      



    }

</script>


<script>
    document.addEventListener('contextmenu', function(e) {
        e.preventDefault();
    });
    
    
        window.addEventListener("keydown", checkKeyPress, false);
    function checkKeyPress(key) {

       if((event.ctrlKey || event.metaKey) && key.keyCode == "73") {
            event.preventDefault();
            return false;
        }

        else if((event.ctrlKey || event.metaKey) && (key.keyCode == "73" || key.keyCode == "74" )) {
            event.preventDefault();
            return false;
        }

        else if(event.ctrlKey && event.shiftKey && (key.keyCode == "73" || key.keyCode == "74" ))
        {
            event.preventDefault();
            return false;
        }



    }
    
</script>



<!-- Google Tag Manager (noscript) -->
<noscript><iframe src="https://www.googletagmanager.com/ns.html?id=GTM-TD552ZH"
height="0" width="0" style="display:none;visibility:hidden"></iframe></noscript>
<!-- End Google Tag Manager (noscript) -->


<script>
    function FooterFunctions() {
        $('#registration_submit').submit(function () {
            ShowLoader();
            return true;
        });
    }


</script>
<html class="no-js">
<?php
include 'header.php';
?>
<body>
    <div class="site-section bg-light" id="contact-section">
        <div class="page-container">
            <div class="row">
                <div class="col-12 text-center mb-5">
                    <h2 class="text-black section-title text-uppercase">Register now</h2>
                    <p class="text-center">Please fill in the form</p>
                </div>
            </div>
            <div class="row justify-content-center">
                <div class="col-lg-6 mb-5">
                    <form action="#" method="post">
                        <div class="form-group row">
                            <div class="col-md-12">
                                <input type="text" class="form-control" placeholder="first name*" id="name">
                            </div>
                        </div>

                        <div class="form-group row">
                            <div class="col-md-12">
                                <input type="text" class="form-control" placeholder="family name*" id="familyname">
                            </div>
                        </div>

                        <div class="form-group row">
                            <div class="col-md-12">
                                <input type="text" class="form-control" placeholder="email*" id="email">
                            </div>
                        </div>

                        <div class="form-group row">
                            <div class="col-md-12">
                                <input type="password" class="form-control" placeholder="password*" id="password">
                            </div>
                        </div>

                        <div class="form-group row">
                            <div class="col-md-12">
                                <input type="password" class="form-control" placeholder="confirm password*"
                                    id="password2">
                            </div>
                        </div>

                        <div class="form-group row">
                            <div class="col-md-12">
                                <input type="text" style="font-weight: normal;" class="date form-control"
                                    placeholder="birthday*" id="birthday">
                            </div>
                        </div>

                        <div class="form-group row">
                            <div class="col-md-12">
                                <input type="text" class="form-control" placeholder="activation code*"
                                    id="activationcode">
                            </div>
                        </div>

                        <div class="form-group row">
                            <div class="col-md-6 offset-3">
                                <div class="btn btn-block btn-primary text-white py-3 px-5" onclick="register();">
                                    Register</div>
                            </div>
                        </div>
                    </form>
                </div>

            </div>
        </div>
    </div>
    <script type="text/javascript">
        $('.date').datepicker({
            'format': 'm/d/yyyy',
            'autoclose': true
        });

        function register() {
            var data = { name: $("#name").val(), password: $("#password").val(), password2: $("#password2").val(), familyname: $("#familyname").val(), email: $("#email").val(), birthday: $("#birthday").val(), activationcode: $('#activationcode').val() };
            var url = "ajax/ok_register_treatment.php";
            $.ajax({
                type: "POST",
                url: url,
                data: data,
                success: function (data) {
                    console.log(data);
                    var rsu = JSON.parse(data);
                    if (rsu[0]) {
                        if (rsu[1] == 3) {
                            window.location.href = "users/books.php";
                        }
                        else {
                            window.location.href = "index.php";
                        }
                    } else {
                        layer.msg(rsu[1]);
                    }
                }
            });//
        }
    </script>
    <?php include 'footer.php'; ?>
</body>

</html>
