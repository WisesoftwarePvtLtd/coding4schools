var baseroot=document.currentScript.getAttribute('attr-baseroot');
var version=document.currentScript.getAttribute('attr-cache-version');

function loadCss(url) {
    var link = document.createElement("link");
    link.type = "text/css";
    link.rel = "stylesheet";
    link.href = url;
    document.getElementsByTagName("head")[0].appendChild(link);
}

// Load CSS with fallback checks
loadCss('https://use.fontawesome.com/releases/v5.7.1/css/all.css');
loadCss(baseroot+'/js/jquery.OwlCarousel/assets/owl.carousel.min.css');
loadCss(baseroot+'/js/jquery.OwlCarousel/assets/owl.theme.default.css');
loadCss(baseroot+'/js/jquery.fancybox/jquery.fancybox.min.css');
loadCss(baseroot+'/js/jquery.aos/dist/aos.css');
loadCss(baseroot+'/js/jquery.select2/select2.min.css');
loadCss(baseroot+'/js/chartjs/Chart.min.css');
loadCss(baseroot+'/js/jquery.mb.YTPlayer/dist/css/jquery.mb.YTPlayer.min.css');
loadCss('https://printjs-4de6.kxcdn.com/print.min.css');
loadCss('https://cdn.datatables.net/1.11.3/css/jquery.dataTables.min.css');
loadCss('https://cdnjs.cloudflare.com/ajax/libs/jquery-modal/0.9.1/jquery.modal.min.css');

setTimeout(function(){ loadCss(baseroot+'/css/transitions.css'); },1000);

// Safe RequireJS config with CDN fallbacks
requirejs.config({
    waitSeconds: 20,
    paths: {
        jquery: baseroot+"/js/jquery/jquery-3.4.1.min",
        owl: baseroot+"/js/jquery.OwlCarousel/owl.carousel.min",
        lazyload: baseroot+"/js/jquery.lazyload/jquery.lazyload.min",
        aos: baseroot+"/js/jquery.aos/dist/aos",
        sticky: baseroot+"/js/jquery.sticky/jquery.sticky",
        fancybox: baseroot+"/js/jquery.fancybox/jquery.fancybox.min",
        jquerymodal: "https://cdnjs.cloudflare.com/ajax/libs/jquery-modal/0.9.1/jquery.modal.min",
        scrollto: baseroot+"/js/jquery.scrollTo/jquery.scrollTo.min",
        select2: baseroot+"/js/jquery.select2/select2",
        anchor: baseroot+"/js/jquery.arbitrary-anchor/jquery.arbitrary-anchor",
        chartjs: baseroot+"/js/chartjs/Chart2.min",
        popper: "https://unpkg.com/@popperjs/core@2/dist/umd/popper",
        YTPlayer: baseroot+"/js/jquery.mb.YTPlayer/dist/jquery.mb.YTPlayer.min",
        html2canvas: "https://cdnjs.cloudflare.com/ajax/libs/html2canvas/1.1.5/html2canvas.min",
        jsPDF: "https://cdnjs.cloudflare.com/ajax/libs/jspdf/1.3.3/jspdf.min",
        PrintJS: "https://printjs-4de6.kxcdn.com/print.min",
        simplePagination: "https://cdnjs.cloudflare.com/ajax/libs/simplePagination.js/1.6/jquery.simplePagination",
        dataTable: "https://cdn.datatables.net/1.11.3/js/jquery.dataTables.min",
        pdfjs: "https://mozilla.github.io/pdf.js/build/pdf",
        nosleep: "https://cdn.jsdelivr.net/npm/nosleep.js@0.12.0/dist/NoSleep.min",
        mousetrap: "https://cdn.jsdelivr.net/npm/mousetrap@1.6.5/mousetrap.min",
    },
    shim: {
        owl: { deps: ['jquery'] },
        aos: { deps: ['jquery'] },
        lazyload: { deps: ['jquery'] },
        sticky: { deps: ['jquery'] },
        fancybox: { deps: ['jquery'] },
        jquerymodal: { deps: ['jquery'] },
        scrollto: { deps: ['jquery'] },
        anchor: { deps: ['jquery'] },
        popper: { deps: ['jquery'] },
        chartjs: { deps: ['jquery'] },
        select2: { deps: ['jquery'] },
        YTPlayer: { deps: ['jquery'] },
        html2canvas: { deps: ['jquery'] },
        jsPDF: { deps: ['jquery','html2canvas'] },
        PrintJS: { deps: ['jquery'] },
        dataTable: { deps: ['jquery'] },
        pdfjs: { deps: ['jquery'] },
        nosleep: { deps: ['jquery'] },
        mousetrap: { deps: ['jquery'] },
    }
});

// Core initialization with comprehensive error handling
require(["jquery"], function ($) {
    // Safe function calls with existence checks
    var safeCall = function(fnName) {
        try {
            if (typeof window[fnName] === 'function') window[fnName]();
        } catch(e) {}
    };

    // Initialize safe functions
    if (typeof doParallax === 'function') {
        window.addEventListener('scroll', doParallax);
        doParallax();
    }
    
    safeCall('InitializeMenu');
    safeCall('DropDownCourses');
    safeCall('dropdownNews');
    safeCall('DropOffer');
    safeCall('getCoursePic');
    
    if (typeof InitializeMenuScroll === 'function') {
        window.addEventListener('scroll', InitializeMenuScroll);
        InitializeMenuScroll();
    }
    
    if (typeof WindowResizeController === 'function') {
        window.addEventListener('resize', WindowResizeController);
        WindowResizeController();
        setTimeout(WindowResizeController,3000);
    }
    
    safeCall('HeaderFunctions');
    safeCall('FooterFunctions');
    safeCall('FooterFunctionsCommon');
    safeCall('HideLoader');
});

// AOS with safe loading
requirejs.undef('aos');
require(["aos"], function (AOS) {
    if (AOS && typeof AOS.init === 'function') {
        AOS.init({
            once: true,
            easing: 'ease-in-out-sine',
            duration: 500,
        });
    }
}, function(err) {
    console.log('AOS failed to load:', err);
});

// YTPlayer with safe fallback
require(["YTPlayer"], function (YTPlayer) {
    if (typeof $.fn.YTPlayer === 'function') {
        jQuery(".player,.player2").YTPlayer();
    }
}, function(err) {
    console.log('YTPlayer failed to load');
});

// jsPDF safe load
require(['jsPDF'], function(jsPDF) {
    if (jsPDF) window.jsPDF = jsPDF;
});

// LazyLoad with safe initialization
var LazyLoad;
require(["lazyload"], function (lazy) {
    if (lazy && typeof lazy === 'function') {
        LazyLoad = new lazy({
            elements_selector: ".lazy",
            effect: "fadeIn",
            threshold: 200
        });
    }
}, function(err) {
    console.log('LazyLoad failed:', err);
});

// Owl Carousel with safe plugin detection
require(['owl'], function() {
    var initOwl = function(selector, config) {
        var $el = $(selector);
        if ($el.length && typeof $el.owlCarousel === 'function') {
            $el.owlCarousel(config);
        }
    };

    // Single carousel
    if($(".single-carousel").length>0) {
        initOwl(".single-carousel", {
            items: 1, dots: false, autoplay: false, loop: true, nav: true,
            navText: ['<div class="nav-prev"><i class="fas fa-chevron-left"></i></div>','<div class="nav-next"><i class="fas fa-chevron-right"></i></div>']
        });
    }

    // Multi carousel
    if($(".multi-carousel").length>0) {
        initOwl(".multi-carousel", {
            loop: true, margin: 30, items: 3, nav: true,
            navText: ['<div class="nav-prev"><i class="fas fa-chevron-left"></i></div>','<div class="nav-next"><i class="fas fa-chevron-right"></i></div>'],
            responsive: {0: {items: 1}, 768: {items: 3}}
        });
    }

    // Course pic
    if($(".course_pic").length>0) {
        initOwl(".course_pic", {items: 1, nav: false, dots: false, loop: false});
    }

    // Partners
    if($(".partners").length>0) {
        initOwl(".partners", {items: 1, nav: false, dots: true, loop: true});
    }

    // Custom arrows
    if($(".multi-carousel-customarrows").length>0) {
        initOwl(".multi-carousel-customarrows", {
            loop: false, rewind: true, autoWidth: true, items: 4, nav: true,
            navText: ['<div class="nav-prev"><i class="fas fa-chevron-left"></i></div>','<div class="nav-next"><i class="fas fa-chevron-right"></i></div>']
        });
    }

    // Nav carousel
    if($(".nav-carousel").length>0) {
        initOwl(".nav-carousel", {
            loop: false, margin: 10, autoWidth: true, items: 4, nav: true,
            navText: ['<div class="nav-prev"><i class="fas fa-chevron-left"></i></div>','<div class="nav-next"><i class="fas fa-chevron-right"></i></div>']
        });
    }
}, function(err) {
    console.log('OwlCarousel failed:', err);
});

// Sticky elements
require(['sticky'], function(hcSticky) {
    if (typeof $.fn.hcSticky === 'function') {
        if($('.container-pictures').length>0) {
            $('.container-pictures').css({ 'transition':'unset' }).hcSticky({
                stickTo: '.product-info .left', top: 150
            });
        }
        if($('.side-ad').length>0) {
            $('.side-ad').css({'transition': 'unset'}).hcSticky({
                stickTo: '.side-news', top: 150
            });
        }
    }
});

// Modal and fancybox safe loading
require(['jquerymodal', 'fancybox'], function() {
    var safeAlerts = function() {
        try {
            if (typeof AlertsFunctions === 'function') AlertsFunctions();
        } catch(e) {}
    };
    safeAlerts();
    
    // Fancy confirm dialog
    if (typeof $.fancybox !== 'undefined' && $.fancybox.open) {
        $.fancyConfirm = function(opts) {
            opts = $.extend(true, {
                title: 'Are you sure?', message: '', okButton: 'OK', 
                noButton: 'Cancel', callback: $.noop
            }, opts || {});

            $.fancybox.open({
                type: 'html',
                src: '<div class="fc-content"><h3>' + opts.title + '</h3><p>' + 
                     opts.message + '</p><div class="text-right confirmation">' +
                     '<a class="gradient-btn btn-red" data-value="0" data-fancybox-close>' + 
                     opts.noButton + '</a><button data-value="1" data-fancybox-close class="gradient-btn btn-green">' + 
                     opts.okButton + '</button></div></div>',
                opts: {
                    animationDuration: 350, animationEffect: 'material', modal: true,
                    afterClose: function(instance, current, e) {
                        var value = e ? $(e.target || e.currentTarget).data('value') : 0;
                        opts.callback(value);
                    }
                }
            });
        };
    }
});

// Anchor safe load
require(['anchor'], function() {});

// Select2 safe initialization
require(['select2'], function() {
    if (typeof $.fn.select2 === 'function') {
        $('.multiple-select').select2();
        $('.multiple-select-2').select2({
            dropdownParent: $('body'), minimumResultsForSearch: -1,
            placeholder: "Choose The Grade Course", allowClear: true
        }).on('select2:open', function() {
            $('.select2-search__field').attr('focus', false);
        });
    }
});

// DataTable safe init
require(['dataTable'], function() {
    if ($('#StudentTable').length && typeof $.fn.DataTable === 'function') {
        $('#StudentTable').DataTable({ pageLength: 10 });
    }
});

// Charts safe init
require(['chartjs'], function(Chart) {
    try {
        if (typeof ChartFunctions === 'function') ChartFunctions();
    } catch(e) {}
});

// PrintJS safe handler
require(['PrintJS'], function() {
    if ($('#printPage').length) {
        $('#printPage').click(function() {
            let answersAreVisible = $('.green_light_border').not('.hidden-answer').length > 0;
            let printStyles = `
                @media print {
                    ${answersAreVisible ? 
                        `.green_light_border { border: 3px solid rgba(102, 255, 102, 1) !important; background-color: rgba(102, 255, 102, 0.35) !important; -webkit-print-color-adjust: exact !important; print-color-adjust: exact !important; }` : 
                        `.green_light_border { border: none !important; background-color: transparent !important; }`
                    }
                    .green_border { border: 3px solid rgba(146, 227, 146, 0.5) !important; background-color: rgba(146, 227, 146, 0.5) !important; -webkit-print-color-adjust: exact !important; print-color-adjust: exact !important; }
                    .red_border { border: 3px solid rgba(255, 128, 128, 0.5) !important; background-color: rgba(255, 128, 128, 0.5) !important; -webkit-print-color-adjust: exact !important; print-color-adjust: exact !important; }
                    .sep { display: block !important; clear: both !important; height: 20px !important; width: 100% !important; border-bottom: 1px solid #e0e0e0 !important; margin: 15px 0 !important; -webkit-print-color-adjust: exact !important; print-color-adjust: exact !important; }
                    .question_item, .quiz-item { margin-bottom: 25px !important; page-break-inside: avoid !important; }
                    .question { margin-bottom: 15px !important; padding-bottom: 10px !important; border-bottom: 2px solid #2e3e4f !important; }
                    .answers { margin-bottom: 20px !important; padding-bottom: 15px !important; }
                    .square_images, .rectangle_images, .text-answer { margin-bottom: 10px !important; }
                    .square_images { float: left !important; width: calc(50% - 15px) !important; margin-right: 15px !important; }
                    .rectangle_images, .text-answer { width: 100% !important; clear: both !important; }
                }
            `;
            if (typeof printJS === 'function') {
                printJS({
                    printable: 'to_download', type: 'html',
                    css: [baseroot + '/css/generatequiz.css'], scanStyles: false, style: printStyles
                });
            }
        });
    }
});

// Safe popper load
require(['popper']);

// Safe viewport height calculation (no redeclaration)
(function() {
    if (!window.vhSet) {
        var vh = window.innerHeight * 0.01;
        document.documentElement.style.setProperty('--vh', `${vh}px`);
        window.vhSet = true;
    }
})();

// Google Analytics safe load
window.dataLayer = window.dataLayer || [];
window.gtag = window.gtag || function(){dataLayer.push(arguments);};
gtag('js', new Date());
gtag('config', 'UA-51241056-6');



















