

<style>
    .CommonLoader{ position: fixed; top:0; left:0; right:0; bottom:0; background-color: rgba(255,255,255,0.95); z-index: 98; background-image: url("https://www.codingfirst.org/images/spinner.svg"); background-position: center; background-repeat: no-repeat; background-size: 150px; animation: FadeIn linear 0.3s; }
    .CommonLoader2{ background-color: rgba(255,255,255,1) !important; z-index: 99999999  !important;; animation: FadeIn linear 0s  !important;; }
</style>


<link href="https://www.codingfirst.org/css/style.css?1764217415" rel="stylesheet" media="all">
<link href="https://www.codingfirst.org/css/course_module.css?1764217415" rel="stylesheet" media="all">

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



    <style>
        iframe{
            width: 800px;height: 100%;
        }
        .form-control{width: 100%;}
        .title{font-size: 22px; font-weight: 300;margin-bottom: 20px;color:#1C75BC; text-transform: uppercase;}
        .sample-btn{ width: 100%;padding: 15px;}
        .sample-btn:hover{
            background-color: #f66f1e;
        }
    </style>



    <body style="width: 100%; height: auto;padding: 30px;display: flex;justify-content: center;align-items: center;">


    <form id="EnrollForm" class="form-control"  method="post" onsubmit="ShowLoader();">
        <div class="title">Register to request a 1 month free trial</div>
        <div>
            <input  type="text" name="school" placeholder="School Name" required>
        </div>
        <div>
            <input  type="text" name="city" placeholder="School City" required>
        </div>
        <div>
            <input  type="text" name="grade" placeholder="Student Grades" required>
        </div>
        <div>
            <input  type="number" name="student_nbr" placeholder="Number of students" required>
        </div>
        <div>
            <input  type="text" name="contact_name" placeholder="Contact Name" required>
        </div>
        <div>
            <input  type="text" name="email" placeholder="Email"  required>
        </div>
        <div>
            <input  type="text" name="contact_nbr" placeholder="Phone number with country code"  required>
        </div>
        <div>
            <input  type="text" placeholder=Whatsapp number with country code" name="wechat_nbr" >
        </div>

        <div>
            <textarea name="message" placeholder="Message"></textarea>
        </div>

        <input class="sample-btn" type="submit" name="submit" value="Enroll">
        <input type="hidden" name="act" value="enroll">
    </form>


    </body>





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



