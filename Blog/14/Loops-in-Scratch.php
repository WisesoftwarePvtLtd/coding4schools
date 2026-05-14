



<!DOCTYPE html>
<html lang="en">

<head>
    <title>Coding First | Loops in Scratch </title>
    <!-- Google tag (gtag.js) -->
<script async src="https://www.googletagmanager.com/gtag/js?id=G-JH49K7C941"></script>
<script>
  window.dataLayer = window.dataLayer || [];
  function gtag(){dataLayer.push(arguments);}
  gtag('js', new Date());

  gtag('config', 'G-JH49K7C941');
</script>

<style>
    .CommonLoader{ position: fixed; top:0; left:0; right:0; bottom:0; background-color: rgba(255,255,255,0.95); z-index: 98; background-image: url("https://www.codingfirst.org/images/spinner.svg"); background-position: center; background-repeat: no-repeat; background-size: 150px; animation: FadeIn linear 0.3s; }
    .CommonLoader2{ background-color: rgba(255,255,255,1) !important; z-index: 99999999  !important;; animation: FadeIn linear 0s  !important;; }
</style>


<link href="https://www.codingfirst.org/css/main.css?1764253462" rel="stylesheet" media="all">
<link href="https://www.codingfirst.org/css/course_module.css?1764253462" rel="stylesheet" media="all">

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


    <meta charset="utf-8">
    <meta name="viewport" content="width=device-width, initial-scale=1, shrink-to-fit=no">
    <meta name="keywords" content="loops,scratch,scratch.mit.edu,forever loop,while loop,for loop,scratch coding,coding for kids,programming for kids,coding first,brighter future,coding for beginners,coding tutorial,scratch tutorial">
    <meta name="description" content="  Scratch is made up of block coding and is the easiest way to learn to code, anyone can do it! In this example, we will be looking at the forever loop and how it can be used in a quick example. Loops are an important foundation of coding and are useful for any project.">

    <link href="https://www.codingfirst.org/Blog/14/Loops-in-Scratch" rel="canonical">

    <meta property="og:image" content="https://www.codingfirst.org/data/blog/14.png"/><meta property="og:type" content="article" /><meta property="og:title" content="Loops in Scratch"/><meta property="og:site_name" content="codingfirst.org"/><meta property="og:description" content="Using Loops in Scratch
Scratch uses block code to make learning to code easier. There are many cool projects that can be done using Scratch and by learning the different types of loops leaves more room for&amp;nbsp;creativity!

If we wanted to repeat a certain action in code multiple times,..."/><meta property="og:url" content="https://www.codingfirst.org/Blog/14/Loops-in-Scratch"/><meta name="twitter:card" content="summary" /><meta name="twitter:url" content="https://www.codingfirst.org/Blog/14/Loops-in-Scratch"><meta name="twitter:title" content="Loops in Scratch"><meta name="twitter:description" content="Using Loops in Scratch
Scratch uses block code to make learning to code easier. There are many cool projects that can be done using Scratch and by learning the different types of loops leaves more room for&amp;nbsp;creativity!

If we wanted to repeat a certain action in code multiple times,..."><meta name="twitter:image" content="https://www.codingfirst.org/data/blog/14.png">
</head>

<body>



<div class="menu-marker"></div>
<nav class="menu menu-desktop">
    <div class="menu-sizer">
        <a href="https://www.codingfirst.org" class="logo"><img src="https://www.codingfirst.org/images/Logo_cd.png">
            <div class="name"> <span>codingFirst</span> </div>
        </a>
        <ul class="links">
            <li><a class="scroll_to" href="https://www.codingfirst.org">Home <span class="underline"></span></a></li>
            <li><a class="scroll_to" href="https://www.codingfirst.org/About">About Us  <span class="underline"></span></a></li>
            <li><a class="scroll_to" href="https://www.codingfirst.org/Gallery">Gallery  <span class="underline"></span></a></li>

            <li class="dropdown"><a id="course_dropdpwn" class="dropdown_menu " href="javascript:;">Courses
                    <img src="https://www.codingfirst.org/images/arrow-down.svg" />
                    <span class="underline"></span>
                </a>
                <div class="dropdown_list">
                                        <div>
                        <a data-attr="1"  class="course_level" href="javascript:;"><span>Primary</span>
                            <i class="fas fa-caret-right"></i>
                        </a>

                        <ul class="dropdown_sub" id="sub_course_1">
                                                            <li>
                                    <a href="https://www.codingfirst.org/Course/4/Basics-of-block-programming-Scratch-Junior-Level-I">
                                       Basics of block programming: Scratch Junior Level I</a>
                                </li>
                                                            <li>
                                    <a href="https://www.codingfirst.org/Course/6/Game-Development-Basics-Scratch-Junior-Level-II">
                                       Game Development Basics: Scratch Junior Level II</a>
                                </li>
                                                            <li>
                                    <a href="https://www.codingfirst.org/Course/17/Introduction-to-Coding-Logic">
                                       Introduction to Coding Logic</a>
                                </li>
                                                            <li>
                                    <a href="https://www.codingfirst.org/Course/8/Basics-Of-Scratch">
                                       Basics Of Scratch</a>
                                </li>
                                                            <li>
                                    <a href="https://www.codingfirst.org/Course/9/Game-development-with-Scratch-Level-I">
                                       Game development with Scratch: Level I</a>
                                </li>
                                                            <li>
                                    <a href="https://www.codingfirst.org/Course/33/Game-development-with-Scratch-Level-II">
                                       Game development with Scratch: Level II</a>
                                </li>
                                                            <li>
                                    <a href="https://www.codingfirst.org/Course/10/Advanced-Game-Programming-With-Scratch-Level-III">
                                       Advanced Game Programming With Scratch: Level III</a>
                                </li>
                                                            <li>
                                    <a href="https://www.codingfirst.org/Course/23/Artificial-Intelligence-for-Juniors">
                                       Artificial Intelligence for Juniors</a>
                                </li>
                                                            <li>
                                    <a href="https://www.codingfirst.org/Course/28/Introduction-to-Mouse-Coding">
                                       Introduction to Mouse Coding</a>
                                </li>
                                                            <li>
                                    <a href="https://www.codingfirst.org/Course/29/Coding-with-Dash-and-Dot">
                                       Coding with Dash and Dot</a>
                                </li>
                                                            <li>
                                    <a href="https://www.codingfirst.org/Course/27/Introduction-to-Makey-Makey">
                                       Introduction to Makey-Makey</a>
                                </li>
                            
                        </ul>
                    </div>

                                        <div>
                        <a data-attr="2"  class="course_level" href="javascript:;"><span>Middle School</span>
                            <i class="fas fa-caret-right"></i>
                        </a>

                        <ul class="dropdown_sub" id="sub_course_2">
                                                            <li>
                                    <a href="https://www.codingfirst.org/Course/11/Introduction-to-Creative-Computing-P5-js-Level-I">
                                       Introduction to Creative Computing: P5.js Level I</a>
                                </li>
                                                            <li>
                                    <a href="https://www.codingfirst.org/Course/12/Game-Programming-in-P5-js-Level-II">
                                       Game Programming in P5.js: Level II</a>
                                </li>
                                                            <li>
                                    <a href="https://www.codingfirst.org/Course/13/Introduction-to-Web-Development-with-HTML-and-CSS-Level-I">
                                       Introduction to Web Development with HTML and CSS: Level I</a>
                                </li>
                                                            <li>
                                    <a href="https://www.codingfirst.org/Course/14/Web-development-with-JavaScript">
                                       Web development with JavaScript</a>
                                </li>
                                                            <li>
                                    <a href="https://www.codingfirst.org/Course/20/3D-Design-Level-I">
                                       3D Design: Level I</a>
                                </li>
                                                            <li>
                                    <a href="https://www.codingfirst.org/Course/21/Advanced-3D-Design-Level-II">
                                       Advanced 3D Design: Level II</a>
                                </li>
                                                            <li>
                                    <a href="https://www.codingfirst.org/Course/24/Introduction-to-mBot-Robotics">
                                       Introduction to mBot Robotics</a>
                                </li>
                                                            <li>
                                    <a href="https://www.codingfirst.org/Course/30/Introduction-to-Micro-Bit">
                                       Introduction to Micro:Bit</a>
                                </li>
                                                            <li>
                                    <a href="https://www.codingfirst.org/Course/31/Micro-Bit-Programming-in-JS">
                                       Micro:Bit Programming in JS</a>
                                </li>
                            
                        </ul>
                    </div>

                                        <div>
                        <a data-attr="3"  class="course_level" href="javascript:;"><span>High School</span>
                            <i class="fas fa-caret-right"></i>
                        </a>

                        <ul class="dropdown_sub" id="sub_course_3">
                                                            <li>
                                    <a href="https://www.codingfirst.org/Course/15/Introduction-to-Python-Level-I">
                                       Introduction to Python: Level I</a>
                                </li>
                                                            <li>
                                    <a href="https://www.codingfirst.org/Course/16/Advanced-concepts-in-Python-Level-II">
                                       Advanced concepts in Python: Level II</a>
                                </li>
                                                            <li>
                                    <a href="https://www.codingfirst.org/Course/26/Introduction-to-Data-Analytics">
                                       Introduction to Data Analytics</a>
                                </li>
                                                            <li>
                                    <a href="https://www.codingfirst.org/Course/34/Introduction-to-Artificial-Intelligence">
                                       Introduction to Artificial Intelligence</a>
                                </li>
                                                            <li>
                                    <a href="https://www.codingfirst.org/Course/18/Game-Development-With-JavaScript">
                                       Game Development With JavaScript</a>
                                </li>
                                                            <li>
                                    <a href="https://www.codingfirst.org/Course/25/Introduction-to-Virtual-Reality">
                                       Introduction to Virtual Reality</a>
                                </li>
                                                            <li>
                                    <a href="https://www.codingfirst.org/Course/22/Arduino-Electronics-Programming-Level-I">
                                       Arduino Electronics Programming Level I</a>
                                </li>
                                                            <li>
                                    <a href="https://www.codingfirst.org/Course/32/Advanced-Arduino-Programming">
                                       Advanced Arduino Programming</a>
                                </li>
                            
                        </ul>
                    </div>

                    
                </div>
            </li>


            <li><a class="scroll_to" href="https://www.codingfirst.org/Teachers">Teachers  <span class="underline"></span></a></li>
            <li><a class="scroll_to" href="https://www.codingfirst.org/Schools">Schools  <span class="underline"></span></a></li>
            <li><a class="scroll_to" href="https://www.codingfirst.org/Blog">Blog  <span class="underline"></span></a></li>
            <li><a class="scroll_to" href="https://www.codingfirst.org/#.SectionFooter">Contact us  <span class="underline"></span></a></li>

                            <li class="users"><a href="https://www.codingfirst.org/Login"><span>Login</span></a></li>
            
        </ul>

    </div>
</nav>


<nav class="menu-mobile">
    <div class="menu-mobile-container">
        <div class="logo-wrap-mobile">
            <a href="https://www.codingfirst.org">
                <img src="https://www.codingfirst.org/images/Logo_cd.png" class="img-fluid">
            </a>
        </div>
        <div class="disclaimer">
            For the best experience, we recommend accessing this website on a laptop or PC using google chrome.
        </div>
        <a href="javascript:;" class="main-nav-mobile-link"><i class="fas fa-bars"></i></a>

        <ul class="menu-mobile-ul">
            <li><a href="https://www.codingfirst.org" class="MobileMenuItem scroll_to">Homepage</a></li>


            <li><a href="https://www.codingfirst.org/About" class="MobileMenuItem scroll_to">About Us</a></li>
            <li><a href="https://www.codingfirst.org/Gallery" class="MobileMenuItem scroll_to">Gallery</a></li>
            <li class="mobile-dropdown"><a href="javascript:;" class="MobileMenuItem scroll_to">Courses  <i class="fas fa-chevron-down"></i></a>
                <div class="dropdown_list_menu">
                                            <div>
                            <a data-attr="1"  class="course_level_mobile" href="javascript:;"><span>Primary</span>
                                <i class="fas fa-chevron-down"></i>
                            </a>

                            <ul class="dropdown_sub" id="sub_mobile_course_1">
                                                                    <li>
                                        <a href="https://www.codingfirst.org/Course/4/Basics-of-block-programming-Scratch-Junior-Level-I">
                                            Basics of block programming: Scratch Junior Level I</a>
                                    </li>
                                                                    <li>
                                        <a href="https://www.codingfirst.org/Course/6/Game-Development-Basics-Scratch-Junior-Level-II">
                                            Game Development Basics: Scratch Junior Level II</a>
                                    </li>
                                                                    <li>
                                        <a href="https://www.codingfirst.org/Course/8/Basics-Of-Scratch">
                                            Basics Of Scratch</a>
                                    </li>
                                                                    <li>
                                        <a href="https://www.codingfirst.org/Course/9/Game-development-with-Scratch-Level-I">
                                            Game development with Scratch: Level I</a>
                                    </li>
                                                                    <li>
                                        <a href="https://www.codingfirst.org/Course/10/Advanced-Game-Programming-With-Scratch-Level-III">
                                            Advanced Game Programming With Scratch: Level III</a>
                                    </li>
                                                                    <li>
                                        <a href="https://www.codingfirst.org/Course/17/Introduction-to-Coding-Logic">
                                            Introduction to Coding Logic</a>
                                    </li>
                                                                    <li>
                                        <a href="https://www.codingfirst.org/Course/23/Artificial-Intelligence-for-Juniors">
                                            Artificial Intelligence for Juniors</a>
                                    </li>
                                                                    <li>
                                        <a href="https://www.codingfirst.org/Course/27/Introduction-to-Makey-Makey">
                                            Introduction to Makey-Makey</a>
                                    </li>
                                                                    <li>
                                        <a href="https://www.codingfirst.org/Course/28/Introduction-to-Mouse-Coding">
                                            Introduction to Mouse Coding</a>
                                    </li>
                                                                    <li>
                                        <a href="https://www.codingfirst.org/Course/29/Coding-with-Dash-and-Dot">
                                            Coding with Dash and Dot</a>
                                    </li>
                                                                    <li>
                                        <a href="https://www.codingfirst.org/Course/33/Game-development-with-Scratch-Level-II">
                                            Game development with Scratch: Level II</a>
                                    </li>
                                
                            </ul>
                        </div>

                                            <div>
                            <a data-attr="2"  class="course_level_mobile" href="javascript:;"><span>Middle School</span>
                                <i class="fas fa-chevron-down"></i>
                            </a>

                            <ul class="dropdown_sub" id="sub_mobile_course_2">
                                                                    <li>
                                        <a href="https://www.codingfirst.org/Course/11/Introduction-to-Creative-Computing-P5-js-Level-I">
                                            Introduction to Creative Computing: P5.js Level I</a>
                                    </li>
                                                                    <li>
                                        <a href="https://www.codingfirst.org/Course/12/Game-Programming-in-P5-js-Level-II">
                                            Game Programming in P5.js: Level II</a>
                                    </li>
                                                                    <li>
                                        <a href="https://www.codingfirst.org/Course/13/Introduction-to-Web-Development-with-HTML-and-CSS-Level-I">
                                            Introduction to Web Development with HTML and CSS: Level I</a>
                                    </li>
                                                                    <li>
                                        <a href="https://www.codingfirst.org/Course/14/Web-development-with-JavaScript">
                                            Web development with JavaScript</a>
                                    </li>
                                                                    <li>
                                        <a href="https://www.codingfirst.org/Course/20/3D-Design-Level-I">
                                            3D Design: Level I</a>
                                    </li>
                                                                    <li>
                                        <a href="https://www.codingfirst.org/Course/21/Advanced-3D-Design-Level-II">
                                            Advanced 3D Design: Level II</a>
                                    </li>
                                                                    <li>
                                        <a href="https://www.codingfirst.org/Course/24/Introduction-to-mBot-Robotics">
                                            Introduction to mBot Robotics</a>
                                    </li>
                                                                    <li>
                                        <a href="https://www.codingfirst.org/Course/30/Introduction-to-Micro-Bit">
                                            Introduction to Micro:Bit</a>
                                    </li>
                                                                    <li>
                                        <a href="https://www.codingfirst.org/Course/31/Micro-Bit-Programming-in-JS">
                                            Micro:Bit Programming in JS</a>
                                    </li>
                                
                            </ul>
                        </div>

                                            <div>
                            <a data-attr="3"  class="course_level_mobile" href="javascript:;"><span>High School</span>
                                <i class="fas fa-chevron-down"></i>
                            </a>

                            <ul class="dropdown_sub" id="sub_mobile_course_3">
                                                                    <li>
                                        <a href="https://www.codingfirst.org/Course/15/Introduction-to-Python-Level-I">
                                            Introduction to Python: Level I</a>
                                    </li>
                                                                    <li>
                                        <a href="https://www.codingfirst.org/Course/16/Advanced-concepts-in-Python-Level-II">
                                            Advanced concepts in Python: Level II</a>
                                    </li>
                                                                    <li>
                                        <a href="https://www.codingfirst.org/Course/18/Game-Development-With-JavaScript">
                                            Game Development With JavaScript</a>
                                    </li>
                                                                    <li>
                                        <a href="https://www.codingfirst.org/Course/22/Arduino-Electronics-Programming-Level-I">
                                            Arduino Electronics Programming Level I</a>
                                    </li>
                                                                    <li>
                                        <a href="https://www.codingfirst.org/Course/25/Introduction-to-Virtual-Reality">
                                            Introduction to Virtual Reality</a>
                                    </li>
                                                                    <li>
                                        <a href="https://www.codingfirst.org/Course/26/Introduction-to-Data-Analytics">
                                            Introduction to Data Analytics</a>
                                    </li>
                                                                    <li>
                                        <a href="https://www.codingfirst.org/Course/32/Advanced-Arduino-Programming">
                                            Advanced Arduino Programming</a>
                                    </li>
                                                                    <li>
                                        <a href="https://www.codingfirst.org/Course/34/Introduction-to-Artificial-Intelligence">
                                            Introduction to Artificial Intelligence</a>
                                    </li>
                                
                            </ul>
                        </div>

                    
                </div>
            </li>
            <li><a href="https://www.codingfirst.org/Teachers" class="MobileMenuItem ">Teachers</a></li>
            <li><a href="https://www.codingfirst.org/Schools" class="MobileMenuItem ">Schools</a></li>
            <li><a href="https://www.codingfirst.org/Blog" class="MobileMenuItem ">Blog</a></li>

            <li><a href="https://www.codingfirst.org#.SectionFooter" class="MobileMenuItem scroll_to">Contact us</a></li>

                            <li><a class="MobileMenuItem" href="https://www.codingfirst.org/login.php"><span><i class="fas fa-user"></i></span>Login</a></li>

            

        </ul>

    </div>
</nav>

<script language="javascript">

    function HeaderFunctions(){
        $(document).ready(function(){

            $('#course_dropdpwn').click(function(){
                $('.dropdown_list').toggleClass('show_dd_menu');
            });

            $('.course_level').click(function(){
                $(".course_level").removeClass('selected');
                $(this).toggleClass('selected');
                event.stopPropagation();

                var attr = $(this).attr('data-attr');
                $(".course_level").not(this).parent().find('.dropdown_sub').removeClass('show_dd_sub_menu');
                // $(".dropdown_sub").not(this).removeClass('show_dd_sub_menu');

                $('#sub_course_'+attr).toggleClass('show_dd_sub_menu');

            });



            $('.course_level_mobile').click(function(){
                $(".course_level_mobile").removeClass('selected');
                $(this).toggleClass('selected');
                event.stopPropagation();

                var attr = $(this).attr('data-attr');
                $(".course_level_mobile").not(this).parent().find('.dropdown_sub').removeClass('show_dd_sub_menu');

                $('#sub_mobile_course_'+attr).toggleClass('show_dd_sub_menu');

            });




            $('#notification').click(function(event){
                event.stopPropagation();
                $(".dropdown_notifications").slideToggle();
            });

            $('.notification_item').on('click', function () {


                var notification_id = $(this).attr('data-attr');
                var redirect = $(this).attr('data-link');


                console.log(notification_id);

                $.ajax({
                    method: "POST",
                    url: 'read_notification.php',
                    data: { id: notification_id },
                    success: function(data){
                        console.log(data);
                    }
                });

                $(this).removeClass('not-read');
                window.location=redirect;


            });


        });







    }



</script>




<div class="gray-section">
    <div class="row p-80">
        <div class="blogPage p-80 m-50">
          <div class="page-grid-50">
              <div class="left">

                  <h1>Loops in Scratch</h1>

                  <!-- Go to www.addthis.com/dashboard to customize your tools -->
                  <div class="addthis_inline_share_toolbox_fz01"></div>

                  <div class="date"> June 07, 2021</div>


                  <div class="text">
                      <p><strong>Using Loops in Scratch</strong><br /><br />
Scratch uses block code to make learning to code easier. There are many cool projects that can be done using Scratch and by learning the different types of loops leaves more room for&nbsp;creativity!</p><br />
<br />
<p>If we wanted to repeat a certain action in code multiple times, it would be best and more efficient to use a loop, that way the code is easier to read and there is less to write. Loops are great tools to use within code and projects to repeat an action multiple times. In the &#39;Control&#39; section of block code, there are three types of loops: repeat x number of times, repeat until, and forever. Each type of loop has a different purpose and knowing what they do is important in writing code!</p><br />
<br />
<p><strong>Repeat x Number of Times Loop</strong><br /><br />
This loop is mainly used for repeating something a specific number of times. If we knew we wanted to ask &#39;Why?&#39; three times, then this loop would be used to repeat that action three times.&nbsp;</p><br />
<br />
<p>Outside of Scratch, this would be called a For Loop, which repeats code a set number of times just like Scratch!</p><br />
<br />
<p><strong>Repeat Until</strong>&nbsp;<strong>Loop</strong><br /><br />
This loop is useful for when something needs to happen before the loop should stop. If we wanted to keep asking &#39;Why?&#39; until an answer was given, then this block would be used for that.&nbsp;</p><br />
<br />
<p>Outside of Scratch, this would be called a While Loop, which repeats code until something is no longer true or false. This uses booleans, a data type in coding of &#39;True&#39; or &#39;False&#39;, until it changes, then the code would be repeated.&nbsp;</p><br />
<br />
<p><strong>Forever Loop</strong><br /><br />
This loop has no end, and is useful for repeating an action or a set of actions forever. This is the type of loop that is used in the video!</p><br />
<br />
<p><strong>Conclusion</strong></p><br />
<br />
<p>Understanding the different uses and abilities of each type of loop is useful for thinking of projects to do. Loops are an important part of coding and should be practiced often to better understand how to use it!</p><br />
                  </div>


              </div>

              <div class="right">
                   

                      <video class="blog-video" controls width="250">

                          <source src="https://www.codingfirst.org/data/uploaded_files/Scratch Loops for blog_id_1227.mp4		
"
                                  type="video/webm">

                          <source src="https://www.codingfirst.org/data/uploaded_files/Scratch Loops for blog_id_1227.mp4		
"
                                  type="video/mp4">

                          Sorry, your browser doesn't support embedded videos.
                      </video>


                      <div class="blog-gallery">
                          <a data-fancybox="gallery" href="https://www.codingfirst.org/data/blog/14.png" class="gallery_item" style="background-image:url('https://www.codingfirst.org/data/blog/14.png')"></a>

                                                                                              <a data-fancybox="gallery" href="https://www.codingfirst.org/data/blog_gallery/1623044695.png" class="gallery_item" style="background-image:url('https://www.codingfirst.org/data/blog_gallery/1623044695.png')"></a>
                                                                        <a data-fancybox="gallery" href="https://www.codingfirst.org/data/blog_gallery/1623044708.png" class="gallery_item" style="background-image:url('https://www.codingfirst.org/data/blog_gallery/1623044708.png')"></a>
                                                                        <a data-fancybox="gallery" href="https://www.codingfirst.org/data/blog_gallery/1623044719.png" class="gallery_item" style="background-image:url('https://www.codingfirst.org/data/blog_gallery/1623044719.png')"></a>
                                                                                                                </div>



                                </div>
          </div>

        </div>



    </div>

</div>










<div class="footer SectionFooter">
    <div class="footerContent">

        <div class="row">


            <div class="footer-col-first">
                <h3 class="secondary-title white">Features </h3>
                <div class="FooterLinks">
                    <a href="https://www.codingfirst.org"><i class="fas fa-chevron-right"></i> Home page</a>
                    <a href="https://www.codingfirst.org/About"><i class="fas fa-chevron-right"></i> About Us</a>
                    <a href="https://www.codingfirst.org/Schools"><i class="fas fa-chevron-right"></i> Schools</a>
                    <a href="https://www.codingfirst.org/Login"><i class="fas fa-chevron-right"></i> Log In</a>
                    <a href="https://www.codingfirst.org/Privacy"><i class="fas fa-chevron-right"></i> Privacy</a>
                    <a href="https://www.codingfirst.org/Terms"><i class="fas fa-chevron-right"></i>  Terms</a>
                </div>
            </div>

            <div class="footer-col">

                <h3 class="secondary-title white">Our Courses </h3>
                <div class="FooterLinks">
                    <a href="https://www.codingfirst.org/Introduction-To-Programming"><i class="fas fa-chevron-right"></i> Introduction to Programming</a>
                    <a href="https://www.codingfirst.org/Programming-Games-with-scratch"><i class="fas fa-chevron-right"></i> Programming Games with Scratch</a>
                    <a href="https://www.codingfirst.org/Introduction-to-programming-with-syntax"><i class="fas fa-chevron-right"></i> Introduction to programming with Syntax</a>
                    <a href="https://www.codingfirst.org/Programming-real-life-projects"><i class="fas fa-chevron-right"></i> Programming real life projects</a>
                </div>

            </div>




            <div class="footer-col">
                <h3 class="secondary-title white">Contact Us </h3>
                <div class="FooterLinks">

                    <a style="text-transform: lowercase;font-size: 16px;font-weight: 500" href="mailto:info@codingfirst.org">info@codingfirst.org</a>


                </div>
            </div>

         </div>





        </div>





    <div class="trademark"><i class="far fa-registered"></i> Coding First Education Technology FZE <br>
    </div>>

    </div>
</div>
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


<script language="javascript">

    function AlertsFunctions() {

        $('[data-fancybox="gallery"]').fancybox({
            animationEffect : false,
            clickContent    : false,
            buttons : [
                'download',
                'thumbs',
                'close'
            ]
        });

    }
</script>



<!-- Go to www.addthis.com/dashboard to customize your tools -->
<script type="text/javascript" src="//s7.addthis.com/js/300/addthis_widget.js#pubid=ra-5d80a331849e60dd"></script>

<script language="javascript">
    function FooterFunctions(){

        var title = 'Loops in Scratch';
        var link = 'https://www.codingfirst.org/Blog/14/Loops-in-Scratch';
        var imgUrl = 'https://www.codingfirst.org/data/blog/14.png';
        var description = '<p><strong>Using Loops in Scratch</strong><br />
Scratch uses block code to make learning to code easier. There are many cool projects that can be done using Scratch and by learning the different types of loops leaves more room for&nbsp;creativity!</p>

<p>If we wanted to repeat a certain action in code multiple times, it would be best and more efficient to use a loop, that way the code is easier to read and there is less to write. Loops are great tools to use within code and projects to repeat an action multiple times. In the &#39;Control&#39; section of block code, there are three types of loops: repeat x number of times, repeat until, and forever. Each type of loop has a different purpose and knowing what they do is important in writing code!</p>

<p><strong>Repeat x Number of Times Loop</strong><br />
This loop is mainly used for repeating something a specific number of times. If we knew we wanted to ask &#39;Why?&#39; three times, then this loop would be used to repeat that action three times.&nbsp;</p>

<p>Outside of Scratch, this would be called a For Loop, which repeats code a set number of times just like Scratch!</p>

<p><strong>Repeat Until</strong>&nbsp;<strong>Loop</strong><br />
This loop is useful for when something needs to happen before the loop should stop. If we wanted to keep asking &#39;Why?&#39; until an answer was given, then this block would be used for that.&nbsp;</p>

<p>Outside of Scratch, this would be called a While Loop, which repeats code until something is no longer true or false. This uses booleans, a data type in coding of &#39;True&#39; or &#39;False&#39;, until it changes, then the code would be repeated.&nbsp;</p>

<p><strong>Forever Loop</strong><br />
This loop has no end, and is useful for repeating an action or a set of actions forever. This is the type of loop that is used in the video!</p>

<p><strong>Conclusion</strong></p>

<p>Understanding the different uses and abilities of each type of loop is useful for thinking of projects to do. Loops are an important part of coding and should be practiced often to better understand how to use it!</p>
';

        wx.config({
            // Configurations such as obtaining signatures from the background
            debug: false,
            appId: '',
            nonceStr: '',
            timestamp: '',
            signature: '',
            // Setting up the api to be invoked
            jsApiList: [
                'onMenuShareTimeline',
                'onMenuShareAppMessage',
                'onMenuShareQQ',
                'onMenuShareWeibo',
                'onMenuShareQZone'
            ]
        });

        wx.ready(function () {
            wx.onMenuShareTimeline({
                // Share titles
                title: title,
                // Sharing links
                link: link,
                // Sharing icons
                imgUrl: imgUrl,
                success: function () {},
                cancel: function () {}
            });
            wx.onMenuShareAppMessage({
                // Share titles
                title: title,
                // Sharing Description
                desc: description,
                // Sharing links
                link: link,
                // Sharing icons
                imgUrl: imgUrl,
                success: function () {},
                cancel: function () {}
            });
            wx.onMenuShareQQ({
                // Share titles
                title: title,
                // Sharing Description
                desc: description,
                // Sharing links
                link: link,
                imgUrl: imgUrl,
                success: function () {},
                cancel: function () {}
            });
            wx.onMenuShareQZone({
                // Share titles
                title: title,
                // Sharing Description
                desc: description,
                // Sharing links
                link: link,
                imgUrl: imgUrl,
                success: function () {},
                cancel: function () {}
            });
            wx.onMenuShareWeibo({
                // Share titles
                title: title,
                // Sharing Description
                desc: description,
                // Sharing links
                link: link,
                imgUrl: imgUrl,
                success: function () {},
                cancel: function () {}
            });
        });

        // Sharing to friends changes to
        wx.ready(function () {   //Call before the user may click the Share button
            wx.updateAppMessageShareData({
                title: title,
                desc: description,
                link: link, // Share links. The link domain name or path must be the same as the public number JS secure domain name corresponding to the current page.
                imgUrl: imgUrl, // Sharing icons
                success: function () {
                    // Successful setup
                }
            })
        });

        // Sharing in Friendship Circle and Sharing in qq Spatial Change to ___________
        wx.ready(function () {      //Call before the user may click the Share button
            wx.updateTimelineShareData({
                title: title, // Share titles
                link: link, // Share links. The link domain name or path must be the same as the public number JS secure domain name corresponding to the current page.
                imgUrl: imgUrl, // Sharing icons
                success: function () {
                    // Successful setup
                }
            })
        });


    }
</script>



</body>

