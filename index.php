<!DOCTYPE html>
<html lang="en">
<?php
if (session_status() === PHP_SESSION_NONE) {
  session_start();
}

include 'header1.php';
 ?>


<head>
    <title>Coding4Schools</title>
    <!-- Google tag (gtag.js) -->
<script async src="gtag/js"></script>
<script>
  window.dataLayer = window.dataLayer || [];
  function gtag(){dataLayer.push(arguments);}
  gtag('js', new Date());

  gtag('config', 'G-JH49K7C941');
</script>

<style>
    .CommonLoader{ position: fixed; top:0; left:0; right:0; bottom:0; background-color: rgba(255,255,255,0.95); z-index: 98; background-image: url("https://www.codingfirst.org/images/spinner.svg"); background-position: center; background-repeat: no-repeat; background-size: 150px; animation: FadeIn linear 0.3s; }
    .CommonLoader2{ background-color: rgba(255,255,255,1) !important; z-index: 99999999  !important;; animation: FadeIn linear 0s  !important;; }
    

.owl-nav {
    position: absolute;
    top: 50%;
    width: 100%;
    transform: translateY(-50%);
    pointer-events: none;
    z-index: 10;
}

.owl-nav button {
    pointer-events: all;
    position: absolute;
    width: 50px;
    height: 50px;
    border-radius: 50%;
    background: rgba(0,0,0,0.6) !important;
    border: none !important;
    display: flex;
    align-items: center;
    justify-content: center;
}

.owl-nav .owl-prev { left: 20px; }
.owl-nav .owl-next { right: 20px; }

.owl-arrow {
    color: #fff;
    font-size: 30px;
    line-height: 1;
}
/* === RESET & GLOBAL FIXES === */
* {
    box-sizing: border-box;
}

body {
    margin: 0;
    padding: 0;
    line-height: 1.5;
}

/* === SECTION SPACING FIXES === */

.gray-section,
.white-section,
.testimonials-section {
    padding: 20px 0 !important;
    margin: 0 !important;
}

/* === ROW & COLUMN FIXES === */
.row {
    padding: 20px 0 !important;
    margin: 0 !important;
    display: flex;
    flex-wrap: wrap;
    align-items: center;
    gap: 40px;
}

.row.p-80.m-50,
.row.p-80 {
    padding: 30px 20px !important;
    margin: 0 !important;
}

.col-50 {
    flex: 1;
    min-width: 300px;
    padding: 0 15px;
}

/* === REMOVE EXCESSIVE SPACING === */
.sep {
    display: none !important; /* Remove all separator spacing */
}

/* === BANNER CAROUSEL FIXES === */
.single-carousel .owl-carousel {
    margin: 0 !important;
}

.slideShow {
    position: relative;
    min-height: 500px;
    display: flex;
    align-items: center;
    justify-content: center;
}



/* === TEXT & IMAGES ALIGNMENT === */
.main-title-section,
.main-title,
.text-capitalize {
    font-size: 2rem;
    margin: 0 0 15px 0 !important;
    line-height: 1.2;
}

.section-text,
.text-center {
    font-size: 1.1rem;
    margin: 0 0 20px 0 !important;
    line-height: 1.6;
}

.pictures {
    text-align: center;
}

.pictures img {
    max-width: 100%;
    height: auto;
    display: block;
    margin: 0 auto;
}

/*MS GRID === */
.items {
    display: flex;
    flex-wrap: wrap;
    gap: 20px;
    margin-top: 20px;
}

.item {
    flex: 1 1 150px;
    text-align: center;
    padding: 20px;
}

.item img {
    width: 60px;
    height: 60px;
    margin: 0 auto 10px;
}

/* === BUTTONS === */
.buttons {
    display: flex;
    gap: 15px;
    flex-wrap: wrap;
    margin-top: 20px;
}

.main-btn {
    padding: 15px 30px;
    font-size: 1.1rem;
    text-decoration: none;
    border-radius: 8px;
    display: inline-block;
    transition: all 0.3s ease;
}

/* === RESPONSIVE === */
@media (max-width: 768px) {
    .row {
        flex-direction: column;
        gap: 30px;
    }
    
    .col-50 {
        min-width: 100%;
        padding: 0;
    }
    
    .slideContent h1 {
        font-size: 1.8rem;
    }
    
    .slideContent h3 {
        font-size: 1.1rem;
    }
    
    .testimonials {
        grid-template-columns: 1fr;
    }
    
    .items {
        justify-content: center;
    }
    
    .buttons {
        justify-content: center;
    }
}

/* === OVERRIDE EXTERNAL CSS === */
.p-80, .m-50 {
    padding: 0 !important;
    margin: 0 !important;
}

/* ===== FIX TEXT OVER BOY IMAGE ===== */
.slideShow {
    position: relative;
}

/* Position text safely on left */
.slideContent.slide-3 {
    position: absolute;
    top: 30%;
    left: 50%;               /* move text away from boy */
    transform: translateY(-50%);
    max-width: 45%;
    
    padding: 20px 25px;
    border-radius: 10px;
}

/* Improve readability */
.slideContent.slide-3 h1,
.slideContent.slide-3 h2 {
    margin: 0;
    text-align: left;
}

/* Mobile fix */
@media (max-width: 768px) {
    .slideContent.slide-3 {
        position: absolute;      /* text goes BELOW image */
        transform: none;
         top: 30%;
    left: 50%; 
        max-width: 100%;
        margin: 10px;
        text-align: center;
    }
}











</style>


<link href="css/main.css" rel="stylesheet" media="all">
<link href="css/course_module.css" rel="stylesheet" media="all">

<!--<script src="js/require.js/require.js" ></script>-->


<meta name="theme-color" content="#000">
<meta name="msapplication-navbutton-color" content="#000">
<meta name="apple-mobile-web-app-status-bar-style" content="#000">


<!-- Google Tag Manager -->
<script>(function(w,d,s,l,i){w[l]=w[l]||[];w[l].push({'gtm.start':
new Date().getTime(),event:'gtm.js'});var f=d.getElementsByTagName(s)[0],
j=d.createElement(s),dl=l!='dataLayer'?'&l='+l:'';j.async=true;j.src=
'https://www.googletagmanager.com/gtm.js?id='+i+dl;f.parentNode.insertBefore(j,f);
})(window,document,'script','dataLayer','GTM-TD552ZH');</script>
<!-- End Google Tag Manager -->

<script src="recaptcha/api.js" async defer></script>


    <meta name="robots" content="all, index, follow" >
    <meta name="viewport" content="width=device-width, initial-scale=1, maximum-scale=1">

    <meta name="keywords" content="STEM education,online coding for kids,coding websites for kids,best coding websites,best coding games for kids">
    <meta name="description" content="Discover a whole new way of learning and problem solving with cutting edge technology and a leading STEM certified curriculum">

    <link href="index.php" rel="canonical">
    <!-- Owl Carousel -->
<link rel="stylesheet" href="https://cdnjs.cloudflare.com/ajax/libs/OwlCarousel2/2.3.4/assets/owl.carousel.min.css">
<link rel="stylesheet" href="https://cdnjs.cloudflare.com/ajax/libs/OwlCarousel2/2.3.4/assets/owl.theme.default.min.css">

</head>

<body>






<div class="banner-section" style="background-color: lightblue;">
    <div class="single-carousel owl-carousel">

        <picture class="slideShow" >
            <source media="(max-width: 800px)" data-srcset="images/7_mobile.png" />
            <img src="images/13.png">

            <div class="slideContent slide-3">
                <h1 class="color-darkblue">Transform the way your students learn.</h1>

                <div class="sep"></div>

                <h2 class="color-orange" style="width: unset;">
                     Interactive coding projects for all ages.
                </h2>

                <div class="sep"></div>


              
            </div>

        </picture>

        <picture class="slideShow" style="background-color:black;">
            <source media="(max-width: 500px)" data-srcset="images/8_mobile.png" />
            <img src="images/55.png">

            <div class="slideContent slide-1">
                
                <h1 class="color-white"></h1>


                <h3 class="color-white" style="width: unset;">
                    
                </h3>
                <div class="sep"></div>
                <div class="sep"></div>

             

            </div>
        </picture>

        <picture class="slideShow"  style="background-color:lightgray;" >
            <source media="(max-width: 800px)" data-srcset="images/3_mobile.png" />
            <img src="images/9.png">

            <div class="slideContent slide-2">
                <h1 class="color-gray">
                    
                </h1>

                <h1 class="color-orange">
                    Teach AI with confidence.
                </h1>

                <h3 class="color-darkblue" style="width: unset">
                    Easy-to-implement modules designed for every grade <br>level.
                </h3>

                <div class="sep"></div>
                <div class="sep"></div>
                <div class="sep"></div>

               
            </div>
        </picture>
         <picture class="slideShow" style="background-color:lightyellow;">
            <source media="(max-width: 800px)" data-srcset="images/3_mobile.png" />
            <img src="images/17.png">

            <div class="slideContent slide-2">
                <h1 class="color-gray">
                    
                </h1>

                <h1 class="color-orange">
                    Step into the world of VR coding.
                </h1>

                <h3 class="color-orange" style="width: unset">
                   Immersive lessons that let students build in virtual reality.

                </h3>

                <div class="sep"></div>
                <div class="sep"></div>
                <div class="sep"></div>

               
            </div>
        </picture>


    </div>
</div>

<div class="gray-section" style="background-color: lightblue;">

    <div class="row p-80  m-50">
        <div class="col-50">
            <div class="pictures">
                <img src="images/1.png">
            </div>

        </div>
        <div class="col-50">
            <div class="main-title-section">
                 Innovation, creativity and fun
            </div>
            <p class="section-text">
               Coding4Schools combines creativity, structured learning, and interactive fun to help every student thrive in coding and technology.

            </p>
        </div>
    </div>

</div>

<div class="white-section">

    <div class="row p-80 m-50">

        

        <div class="sep"></div>
        <div class="sep"></div>
        <div class="sep"></div>
        <div class="sep"></div>


        <div class="col-50">
            <div class="main-title-section">
               Your school’s gateway to coding, robotics & AI excellence
            </div>
            <p class="section-text">
                Bring cutting-edge STEM education to your classrooms with an easy-to-teach, engaging, and fully structured program loved by students and teachers.

            </p>
        </div>
        <div class="col-50">
            <div class="pictures">
                <img src="images/10.png">
            </div>
        </div>
    </div>
   
</div>











<?php
include 'footer.php';
?>

<script>
$(document).ready(function () {
    $('.single-carousel').owlCarousel({
        items: 1,
        loop: true,
        autoplay: false,        // ❌ no automatic sliding
        nav: true,              // ✅ arrows for clickingre
        dots: true,             // ✅ dots for clicking
        mouseDrag: true,        // allow drag on desktop
        touchDrag: true,        // allow swipe on mobile
        smartSpeed: 600
    });
});
</script>


<script src="https://cdnjs.cloudflare.com/ajax/libs/jquery/3.7.1/jquery.min.js"></script>
<script src="https://cdnjs.cloudflare.com/ajax/libs/OwlCarousel2/2.3.4/owl.carousel.min.js"></script>

</body>

