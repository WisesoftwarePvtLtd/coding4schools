<!DOCTYPE html>
<?php include 'header1.php'; ?>
<html lang="en">

<head>
    <title>International STEAM Curriculum Supplier | Coding4Schools</title>
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
    /* ================= WHITE SECTION ONLY ================= */
.white-section {
    background: linear-gradient(135deg, #ffffff 0%, #f0f4f8 100%);
    padding: 80px 0;
    position: relative;
    overflow: hidden;
}

.white-section::before {
    content: "";
    position: absolute;
    top: -50px;
    left: -50px;
    width: 200px;
    height: 200px;
    background: rgba(249, 115, 22, 0.1);
    border-radius: 50%;
    z-index: 0;
}

.white-section::after {
    content: "";
    position: absolute;
    bottom: -60px;
    right: -60px;
    width: 250px;
    height: 250px;
    background: rgba(251, 146, 60, 0.1);
    border-radius: 50%;
    z-index: 0;
}

/* HERO ROW - SIDE BY SIDE */
.white-section .hero-row {
    display: flex;
    flex-direction: row;       /* horizontal layout */
    align-items: center;       /* vertically center */
    justify-content: space-between;
    gap: 100px;
    position: relative;
    z-index: 1; /* above decorative circles */
}

/* COLUMN 50% */
.white-section .col-50 {
    width: 50%;
}

/* IMAGE LEFT */
.white-section .pictures {
    transition: transform 0.5s ease;
}
.white-section .pictures img {
    width: 100%;
    max-width: 520px;
    border-radius: 30px;
    box-shadow: 0 25px 60px rgba(2,6,23,0.18);
    transition: transform 0.5s ease, box-shadow 0.5s ease;
}
.white-section .pictures img:hover {
    transform: scale(1.05) rotate(1deg);
    box-shadow: 0 35px 70px rgba(2,6,23,0.25);
}

/* TEXT RIGHT */
.white-section .hero-text {
    background: rgba(255,255,255,0.95);
    padding: 50px 60px;
    border-radius: 30px;
    box-shadow: 0 25px 50px rgba(2,6,23,0.08);
    transition: transform 0.5s ease, box-shadow 0.5s ease;
}
.white-section .hero-text:hover {
    transform: translateY(-5px);
    box-shadow: 0 35px 60px rgba(2,6,23,0.15);
}

/* SECTION TEXT */
.white-section .section-text {
    font-size: 17px;
    line-height: 1.8;
    color: #334155;
    margin-bottom: 18px;
    position: relative;
}
.white-section .section-text::before {
    content: "•";
    color: #f97316;
    font-weight: bold;
    display: inline-block;
    width: 1em;
    margin-left: -1em;
}

/* LAST PARAGRAPH BOLD */
/* .white-section .section-text:last-child {
    font-weight: 600;
    color: #020617;
} */

/* ================= MOBILE ================= */
@media (max-width: 900px) {
    .white-section .hero-row {
        flex-direction: column; /* keep vertical for smaller screens */
        gap: 40px;
    }

    .white-section .col-50 {
        width: 100%;
    }

    .white-section .hero-text {
        padding: 30px;
    }
}

    /* ================== SECTION ================== */
.gray-section {
    background: linear-gradient(180deg, #f8fafc, #f1f5f9);
}

.row {
    max-width: 1200px;
    margin: auto;
    padding: 0 20px;
}

/* ================== TITLE ================== */
.main-title {
    font-size: 34px;
    font-weight: 800;
    text-align: center;
    color: #020617;
    letter-spacing: -0.5px;
    position: relative;
    margin-bottom: 60px;
}

.main-title::after {
    content: "";
    width: 70px;
    height: 4px;
    background: linear-gradient(90deg, #f97316, #fb923c);
    display: block;
    margin: 16px auto 0;
    border-radius: 10px;
}

/* ================== ITEMS GRID ================== */
.items {
    display: flex;
    gap: 24px;
    justify-content: space-between;
    align-items: stretch;
}

/* ================== CARD ================== */
.item {
    flex: 1;
    background: linear-gradient(180deg, #ffffff, #fafafa);
    padding: 34px 28px;
    border-radius: 18px;
    text-align: center;
    position: relative;
    overflow: hidden;
    transition: all 0.35s ease;
    box-shadow: 0 8px 30px rgba(2, 6, 23, 0.08);
    min-width: 0;
}

/* Top accent line */
.item::before {
    content: "";
    position: absolute;
    top: 0;
    left: 0;
    width: 100%;
    height: 4px;
    background: linear-gradient(90deg, #f97316, #fb923c);
}

.item:hover {
    transform: translateY(-8px);
    box-shadow: 0 20px 50px rgba(2, 6, 23, 0.18);
}

/* ================== ICON ================== */
.item img {
    width: 72px;
    margin-bottom: 18px;
    padding: 14px;
    background: #fff7ed;
    border-radius: 50%;
    box-shadow: 0 6px 20px rgba(249, 115, 22, 0.25);
}

/* ================== CARD TITLE ================== */
.label {
    font-size: 17px;
    font-weight: 700;
    color: #020617;
    margin-bottom: 14px;
    line-height: 1.4;
    word-wrap: break-word;
    overflow-wrap: break-word;
}

.label::after {
    content: "";
    display: block;
    width: 30px;
    height: 3px;
    background: #f97316;
    margin: 10px auto 0;
    border-radius: 10px;
}

/* ================== CARD TEXT ================== */
.text {
    font-size: 14.5px;
    line-height: 1.75;
    color: #475569;
    margin-top: 12px;
    word-wrap: break-word;
    overflow-wrap: break-word;
}

/* ================== TABLET ================== */
@media (max-width: 1024px) {
    .items {
        flex-wrap: wrap;
    }

    .item {
        flex: 1 1 48%;
    }
}

/* ================== MOBILE ================== */
@media (max-width: 600px) {
    .main-title {
        font-size: 26px;
    }

    .items {
        flex-direction: column;
    }

    .item {
        padding: 26px 22px;
    }

    .item img {
        width: 60px;
        padding: 12px;
    }

    .label {
        font-size: 16px;
    }

    .text {
        font-size: 14px;
    }
}

    
</style>

<link href="css/main.css" rel="stylesheet" media="all">
<link href="css/course_module.css" rel="stylesheet" media="all">
<script src="js/require.js/require.js" ></script>

<!-- Favicon and meta tags remain the same -->
<meta name="theme-color" content="#000">
<meta name="msapplication-navbutton-color" content="#000">
<meta name="apple-mobile-web-app-status-bar-style" content="#000">


<meta name="robots" content="all, index, follow" >
<meta name="viewport" content="width=device-width, initial-scale=1, maximum-scale=1">
<meta name="keywords" content="STEM curriculum, STEM certified curriculum, STEM for schools, STEAM learning, science, technology, arts, mathematics, coding first">
<meta name="description" content="Coding First is an international STEAM curriculum supplier to schools worldwide. It operates in China, Saudi Arabia, Malaysia, Armenia, Dubai, and Lebanon and serves over 12,000 students worldwide.">
<link href="about.php" rel="canonical">

<!-- Google Tag Manager -->
<script>(function(w,d,s,l,i){w[l]=w[l]||[];w[l].push({'gtm.start':
new Date().getTime(),event:'gtm.js'});var f=d.getElementsByTagName(s)[0],
j=d.createElement(s),dl=l!='dataLayer'?'&l='+l:'';j.async=true;j.src=
'https://www.googletagmanager.com/gtm.js?id='+i+dl;f.parentNode.insertBefore(j,f);
})(window,document,'script','dataLayer','GTM-TD552ZH');</script>

<script src="https://www.google.com/recaptcha/api.js" async defer></script>
</head>

<body>




<!-- White section with reduced height -->
<div class="white-section m-50">
    <div class="row" style="padding-top:40px; padding-bottom:40px;">
        <!-- Reduced spacers -->
        <div class="row">
            <div class="col-50 m-0">
                <div class="pictures">
                    <img src="images/22.png" alt="Coding4Schools">
                </div>
            </div>
            <div class="col-50">
                <p class="section-text">
                Coding4School  partners with schools around the world to deliver comprehensive programs in Coding, Robotics, AI, and emerging technologies. Our extensive portfolio reflects years of experience working with diverse educational systems, helping institutions bring modern STEM learning into their classrooms with ease.
                </p>
                <p class="section-text">
                    We also support schools with global procurement services for educational robots and technology kits, ensuring seamless access to high-quality equipment. Our structured curriculum, teacher-friendly tools, and international reach make us a trusted partner in 21st-century education.
                </p>
                <p class="section-text">
                    Coding4Schools is committed to preparing students everywhere for the digital future.
                </p>
            </div>
        </div>
    </div>
</div>


<!-- FIXED GRAY SECTION - NO MORE OVERLAP! -->
<div class="gray-section">
    <div class="row p-80">
        <div class="main-title text-center">
            Why learn with Us?
        </div>
        <div class="sep"></div><div class="sep"></div>

        <div class="col-100">
            <div class="items">
                <div class="item">
                    <img src="images/1 (2).png">
                    <div class="label">1. Procurement of Robots for Schools</div>
                    <div class="text">
                        Coding4Schools supports schools with end-to-end procurement of high-quality educational robots and STEM kits from trusted global suppliers. We help institutions choose the right hardware for their curriculum, ensuring compatibility, durability, and affordability.
                    </div>
                </div>
                <div class="item">
                    <img src="images/2.png">
                    <div class="label">2. IB, AP & A-Level Compatible Courses</div>
                    <div class="text">
                        Our advanced curriculum prepares students for international standards, including IB Computer Science, AP Computer Science, and A-Level exams. We provide structured pathways that build strong foundations in programming, algorithms, data structures, and computational thinking.
                    </div>
                </div>
                <div class="item">
                    <img src="images/3(1).png">
                    <div class="label">3. Regular Updates & New Technology Courses</div>
                    <div class="text">
                        Technology evolves quickly, and so do we. Coding4Schools continuously adds new modules, updates existing courses, and introduces emerging technologies—ensuring schools always stay current with the latest trends and advancements.
                    </div>
                </div>
                <div class="item">
                    <img src="images/4.png">
                    <div class="label">4. Training & Year-Round Teacher Support</div>
                    <div class="text">
                        We provide comprehensive training for teachers and ongoing year-round support. From pedagogy to platform usage, our team ensures educators feel confident and fully equipped to deliver the curriculum successfully throughout the school year.
                    </div>
                </div>
            </div>
        </div>
    </div>
</div>

<?php include 'footer.php'; ?>
</body>
</html>


















