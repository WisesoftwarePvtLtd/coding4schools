function FAQSInit(){
    $('.FAQsQAContainer .FAQsQAAnswer').slideUp(0);
    $('.FAQsQAContainer .FAQsQAQuestion .fa-chevron-down').hide();
    $('.FAQsQAContainer').click(function(){
        $(this).parent().find('.FAQsQAAnswer').slideUp();
        $(this).parent().find('.fa-chevron-down').hide();
        $(this).parent().find('.fa-chevron-right').show();
        $(this).parent().find('.fa-chevron-left').show();
        $(this).find('.FAQsQAAnswer').slideToggle();
        $(this).find('i').toggle(0);
    });
}


function guide(id){
    $.fancybox.open({
        'type': 'iframe',
        'width' : 630,
        'height' : 425,
        'autoDimensions': true,
        'autoScale': true,
        'src': 'guide.php?id='+id
    });
}


function ShowLoader(){
    $('body').append('<div class="CommonLoader"></div>');
}
function HideLoader(){
    $('.CommonLoader').remove();
}

function HideLoader2(callback) {
    setTimeout(function() {
        $('.CommonLoader').remove();
        if (callback) {
            callback(); // Call the callback function if provided
        }
    }, 2000);
}



function ShowMessage(Title,Text){

    $.fancybox.open('<div class="message"><h2>'+Title+'</h2><p>'+Text+'</p></div>');

}

function ShowVideoPopup(id){
    $.fancybox.open({
        'type': 'iframe',
        'autoDimensions': false,
        'autoSize' : false,
        'width': 'auto',
        'height': 'auto',
        'autoScale': false,
        'src': 'popup_video.php?id='+id
    });

}

function ShowPrivacyPopup(){
    $.fancybox.open({
        'type': 'iframe',
        'clickSlide' : 'false',
        'clickOutside' : 'false',
        'enableEscapeButton': false,
        'touch': false,
        'smallBtn': false,
        'toolbar': false,
        'modal': true,
        'src': 'privacy_popup.php'
    });

}

function ShowNewOffer(){
    $.fancybox.open({
        'type': 'iframe',
        'autoDimensions': false,
        'autoSize' : false,
        'width': 'auto',
        'height': 'auto',
        'autoScale': false,
        'fullScreen':false,
        'maxWidth'    : '500',
        'maxHeight'   : '500',
        'openEffect'  : 'none',
        'closeEffect' : 'none',
        'src': 'offer_popup.php'
    });

}


function ShowNewsLetter(){
    $.fancybox.open({
        'type': 'iframe',
        'autoDimensions': false,
        'autoSize' : false,
        'width': 'auto',
        'height': 'auto',
        'autoScale': false,
        'src': 'popup_newsletter.php'
    });
}



function ShowConfirmation(Title,Text,Link,ButtonValue = "Proceed"){

    $.fancybox.open({
        src: '<div class="message messageConfirmation"><h2>'+Title+'</h2><p>'+Text+'</p><a href="javascript:parent.$.fancybox.close();">Cancel</a> <a href="'+Link+'">'+ButtonValue+'</a> </div>',
        modal : true,
        type: "html",
        // afterClose: function() {
        //     if(Link !=""){
        //         window.location=Link;
        //     }
        // }
    });
    return false;
}

function loadCss(url) {
    var link = document.createElement("link");
    link.type = "text/css";
    link.rel = "stylesheet";
    link.href = url;
    document.getElementsByTagName("head")[0].appendChild(link);
}


function WindowResizeController(){
    $('.content iframe').each(function(){
        if($(this).attr('src').indexOf('facebook.com/')> 0){
            $(this).height($(this).width()*3/4);
        }
    });
}


function InitializeMenuScroll(){
    //initialize the menu

    // menu-desktop
    var scroll_start = 0;
    var startchange = $('.menu-marker');
    var offset = startchange.offset();


    if (startchange.length>0 && ($(document).scrollTop() - offset.top) > 0){
        $('.menu.menu-desktop').addClass('compact');
        $('.menu.desktop').addClass('compact');
        $('.menu.desktop-inner').addClass('compact');
        $('.users_menu').addClass('compact');
    }else{
        $('.menu.menu-desktop').removeClass('compact');
        $('.menu.desktop').removeClass('compact');
        $('.menu.desktop-inner').removeClass('compact');
        $('.users_menu').removeClass('compact');
    }
}

function InitializeMenu(){
    $('.menu-mobile-ul').slideUp(0);
    $('.main-nav-mobile-link').click(function(){ $('.menu-mobile-ul').slideToggle(200); })
}

function DropDownCourses(){
    $('.dropdown_list_menu').slideUp(0);
    $('.mobile-dropdown').click(function(){ $('.dropdown_list_menu').slideToggle(200); })
}

function DropOffer() {
    $('.upper_menu').css('display','block');
    $('.menu-content').css('margin-top','80px');
    $('.upper_menu').slideDown(0);
    $('#close').click(function(){
        $('.upper_menu').slideUp(0);
        $('.upper_menu').css('display','none');
        $('.menu-content').css('margin-top','0');
    })


}




var isMobile = false; //initiate as false
// device detection
if(/(android|bb\d+|meego).+mobile|avantgo|bada\/|blackberry|blazer|compal|elaine|fennec|hiptop|iemobile|ip(hone|od)|ipad|iris|kindle|Android|Silk|lge |maemo|midp|mmp|netfront|opera m(ob|in)i|palm( os)?|phone|p(ixi|re)\/|plucker|pocket|psp|series(4|6)0|symbian|treo|up\.(browser|link)|vodafone|wap|windows (ce|phone)|xda|xiino/i.test(navigator.userAgent)
    || /1207|6310|6590|3gso|4thp|50[1-6]i|770s|802s|a wa|abac|ac(er|oo|s\-)|ai(ko|rn)|al(av|ca|co)|amoi|an(ex|ny|yw)|aptu|ar(ch|go)|as(te|us)|attw|au(di|\-m|r |s )|avan|be(ck|ll|nq)|bi(lb|rd)|bl(ac|az)|br(e|v)w|bumb|bw\-(n|u)|c55\/|capi|ccwa|cdm\-|cell|chtm|cldc|cmd\-|co(mp|nd)|craw|da(it|ll|ng)|dbte|dc\-s|devi|dica|dmob|do(c|p)o|ds(12|\-d)|el(49|ai)|em(l2|ul)|er(ic|k0)|esl8|ez([4-7]0|os|wa|ze)|fetc|fly(\-|_)|g1 u|g560|gene|gf\-5|g\-mo|go(\.w|od)|gr(ad|un)|haie|hcit|hd\-(m|p|t)|hei\-|hi(pt|ta)|hp( i|ip)|hs\-c|ht(c(\-| |_|a|g|p|s|t)|tp)|hu(aw|tc)|i\-(20|go|ma)|i230|iac( |\-|\/)|ibro|idea|ig01|ikom|im1k|inno|ipaq|iris|ja(t|v)a|jbro|jemu|jigs|kddi|keji|kgt( |\/)|klon|kpt |kwc\-|kyo(c|k)|le(no|xi)|lg( g|\/(k|l|u)|50|54|\-[a-w])|libw|lynx|m1\-w|m3ga|m50\/|ma(te|ui|xo)|mc(01|21|ca)|m\-cr|me(rc|ri)|mi(o8|oa|ts)|mmef|mo(01|02|bi|de|do|t(\-| |o|v)|zz)|mt(50|p1|v )|mwbp|mywa|n10[0-2]|n20[2-3]|n30(0|2)|n50(0|2|5)|n7(0(0|1)|10)|ne((c|m)\-|on|tf|wf|wg|wt)|nok(6|i)|nzph|o2im|op(ti|wv)|oran|owg1|p800|pan(a|d|t)|pdxg|pg(13|\-([1-8]|c))|phil|pire|pl(ay|uc)|pn\-2|po(ck|rt|se)|prox|psio|pt\-g|qa\-a|qc(07|12|21|32|60|\-[2-7]|i\-)|qtek|r380|r600|raks|rim9|ro(ve|zo)|s55\/|sa(ge|ma|mm|ms|ny|va)|sc(01|h\-|oo|p\-)|sdk\/|se(c(\-|0|1)|47|mc|nd|ri)|sgh\-|shar|sie(\-|m)|sk\-0|sl(45|id)|sm(al|ar|b3|it|t5)|so(ft|ny)|sp(01|h\-|v\-|v )|sy(01|mb)|t2(18|50)|t6(00|10|18)|ta(gt|lk)|tcl\-|tdg\-|tel(i|m)|tim\-|t\-mo|to(pl|sh)|ts(70|m\-|m3|m5)|tx\-9|up(\.b|g1|si)|utst|v400|v750|veri|vi(rg|te)|vk(40|5[0-3]|\-v)|vm40|voda|vulc|vx(52|53|60|61|70|80|81|83|85|98)|w3c(\-| )|webc|whit|wi(g |nc|nw)|wmlb|wonu|x700|yas\-|your|zeto|zte\-/i.test(navigator.userAgent.substr(0,4))) {
    isMobile = true;
}

function doParallax(){

    $(".parallax, .parallaxText").css({ 'transition': '0s !important','transition-delay':'0s !important', 'transition-timing-function': 'linear' });
    if(isMobile!=true) {
        $(".parallax").each(function(){
            var offset = $(this).offset();
            var positionY = (window.pageYOffset - offset.top)/2;
            if(positionY < 0){ positionY = 0; }
            if (positionY < 100) {
                $(this).find(".parallaxText").css("opacity", "" + (100 - (positionY)) / 100);
            }
            $(this).css("background-position", "center +" + (positionY) + "px");

        });


    }
}


function visiblecheck(){
    $('.visiblecheck .text').each(function () {
        // Is this element visible onscreen?
        var visible = $(this).visible('vertical');
        if(visible === true){
            $(this).attr('attr-id');
            $('.anchor-all').removeClass('selected');
            $('#anchor-'+$(this).attr('attr-id')).addClass('selected');
            return false;
        }
    });
}


function isFunction(possibleFunction) {
    return eval(" typeof  " + possibleFunction);
}






function createCookie(name, value, days) {
    var expires;

    if (days) {
        var date = new Date();
        date.setTime(date.getTime() + (days * 24 * 60 * 60 * 1000));
        expires = "; expires=" + date.toGMTString();
    } else {
        expires = "";
    }
    document.cookie = encodeURIComponent(name) + "=" + encodeURIComponent(value) + expires + "; path=/";
}

function readCookie(name) {
    var nameEQ = encodeURIComponent(name) + "=";
    var ca = document.cookie.split(';');
    for (var i = 0; i < ca.length; i++) {
        var c = ca[i];
        while (c.charAt(0) === ' ')
            c = c.substring(1, c.length);
        if (c.indexOf(nameEQ) === 0)
            return decodeURIComponent(c.substring(nameEQ.length, c.length));
    }
    return null;
}

function eraseCookie(name) {
    createCookie(name, "", -1);
}


function isNumber(evt) {
    evt = (evt) ? evt : window.event;
    var charCode = (evt.which) ? evt.which : evt.keyCode;
    if (charCode > 31 && (charCode < 48 || charCode > 57)) {
        return false;
    }
    return true;
}




function LoadMoreNews(skip,numbertoload,baseroot,category = 0){
    $('#loadMore').hide();
    ShowLoader();
    $.post(baseroot+'/news-ajax.php',{ skip: skip, numbertoload:numbertoload,category:category  },function(data){ $('.AllNews').append(data); HideLoader(); });
}


function dropdownNews() {

    $(document).ready(function(){
        $('#national').click(function(){
            $(".news-dropdown").slideToggle();

        });
    });


}

function DeleteItem(page,item,label){
    $.fancyConfirm({
        title     : "Are you sure?",
        message   : "Are you sure you want to delete "+label+" with all of its sections?",
        okButton  : 'Agree',
        noButton  : 'Disagree',
        callback  : function (value) {
            if (value) {
                $.post('item_delete.php',{ id : item, page: page },function(){  })
                $('.teacher_item').slideUp(300, function(element){
                    $(this).remove();
                    if ($('.table').find('.teacher_item').length == 0) {
                        window.location.reload();
                    }
                });
                console.log(item);
                console.log(page);
                console.log(value);
            } else {
            }
        }
    });
}


function DeleteQuizQuestion(id){
    $.fancyConfirm({
        title     : "Are you sure?",
        message   : "Are you sure you want to delete this question?",
        okButton  : 'Agree',
        noButton  : 'Disagree',
        callback  : function (value) {
            if (value) {
                $.post('https://www.codingfirst.org/teacher_quiz_question_delete.php',{ id : id },function(){  })
                $('.quiz-section-4').slideUp(300, function(element){
                    $(this).remove();
                    if ($('.quiz-section-4').find('.question_item').length == 0) {
                        window.location.reload();
                    }
                });
                console.log(id);

            } else {
            }
        }
    });
}

function DeleteQuiz(id){
    $.fancyConfirm({
        title     : "Are you sure?",
        message   : "Are you sure you want to delete this quiz?",
        okButton  : 'Agree',
        noButton  : 'Disagree',
        callback  : function (value) {
            if (value) {
                $.post('https://www.codingfirst.org/teacher_generate_quiz_delete.php',{ id : id },function(){  })
                $('.quiz-item-section').slideUp(300, function(element){
                    $(this).remove();
                    if ($('.quizzes_section').find('.quiz-item-section').length == 0) {
                        window.location.reload();
                    }
                });
                console.log(id);

            } else {
            }
        }
    });
}

function DeleteQuizInfo(quiz_id,item,action,quiz_grade=0){
    $.fancyConfirm({
        title     : "Are you sure?",
        message   : "Are you sure you want to delete this?",
        okButton  : 'Agree',
        noButton  : 'Disagree',
        callback  : function (value) {
            if (value) {
                $.post('teacher_generate_info_delete.php',{ id : quiz_id, item: item, action :action, quiz_grade :quiz_grade },function(data){ console.log(data);  });
                if(action == 'grade_sections') {
                    $('.quiz-sections').slideUp(300, function (element) {
                        $(this).remove();
                        if ($('#grades-and-sections').find('.quiz-sections').length == 0) {
                            window.location.reload();
                        }
                    });
                } else if(action == 'quizzes'){
                    $('.quiz-item').slideUp(300, function (element) {
                        $(this).remove();
                        if ($('.quiz-section ').find('.quiz-item').length == 0) {
                            window.location.reload();
                        }
                    });
                }

            } else {
            }
        }
    });
}

function ShuffleQuiz(generated_quiz_id){
    $.fancyConfirm({
        title     : "Are you sure?",
        message   : "Are you sure you want to shuffle this quiz?",
        okButton  : 'Agree',
        noButton  : 'Disagree',
        callback  : function (value) {
            if (value) {
                $.post('https://www.codingfirst.org/teacher_generate_shuffle.php',{ id : generated_quiz_id },function(data){ console.log(data);  });
                window.parent.location.reload();
            } else {
            }
        }
    });
}

function AddQuiz(info,lesson){

      $.fancybox.open({
        'type': 'iframe',
        'autoDimensions': false,
        'autoSize' : false,
        'width': 'auto',
        'height': 'auto',
        'autoScale': false,
        'src': 'teacher_quiz_addto.php?info='+info+'&lesson='+lesson
    });

}




function ArchiveItem(page,item){
    $.fancyConfirm({
        title     : "Are you sure?",
        message   : "Are you sure you want to archive this?",
        okButton  : 'Agree',
        noButton  : 'Disagree',
        callback  : function (value) {
            if (value) {
                $.post('item_archive.php',{ id : item, page: page },function(){  })
                $('.student_grid').slideUp(300, function(element){
                    $(this).remove();
                    if ($('.table').find('.student_grid').length == 0) {
                        window.location.reload();
                    }
                });
                console.log(item);
                console.log(page);
                console.log(value);
            } else {
            }
        }
    });
}

function getCoursePic(){
    $('.course-thumbnail').click(function(){
        var i = $(this).attr('attr-index');
        $('.course_pic').trigger('to.owl.carousel', i-1 );
        console.log(i);
    });
}

var Package_Price ;
function GetPriceFunc(package_id,plan,student) {

    $.ajax({
        async:false,
        type:'POST',
        url:'https://www.codingfirst.org/ajax/calculate_package_price.php',
        data:{package_id: package_id, plan_key: plan, student:student, type:'calculate' },
        success:function(response){
            Package_Price = response;
        }
    });

    return Package_Price;


}

var CalPackage_Price ;



function detectmob() {
    if( navigator.userAgent.match(/Android/i)
        || navigator.userAgent.match(/webOS/i)
        || navigator.userAgent.match(/iPhone/i)
        || navigator.userAgent.match(/iPad/i)
        || navigator.userAgent.match(/iPod/i)
        || navigator.userAgent.match(/BlackBerry/i)
        || navigator.userAgent.match(/Windows Phone/i)
    ){
        return true;
    }
    else {
        return false;
    }
}

function add_students(elem,seats) {

    var count_student_info = $('.student-info').length;

  var nbrlast =  $(".student-info").last().attr('data_id');
  var nbrlastadd =  parseInt(nbrlast)+1;

   var  mystring = $('.student-info').last().html();
   var mystring2= '<div class="student-info student-info__'+nbrlastadd+'" data_id="'+nbrlastadd+'">'+mystring+'</div>';
   var mystring3 = mystring2.replace('hide-btn','');



   $('#student-info-section').append(mystring3);
    var count_inputs = $('.student-info').length;

    if(count_inputs >= seats){
        $(elem).addClass('hide-btn');
    }
}

function remove_student(elem,seats) {
    $(elem).parent().remove();
    var count_inputs = $('.student-info').length;

    var check_addbtn = $('.add-btn-border').hasClass('hide-btn');

    if(count_inputs < seats && check_addbtn == true){
        $('.add-btn-border').removeClass('hide-btn');
    }

}



function MakeDefault(type,quiz,id,user){
    $.fancyConfirm({
        title     : "Are you sure?",
        message   : "Are you sure you want make this quiz the default one?",
        okButton  : 'Agree',
        noButton  : 'Disagree',
        callback  : function (value) {
            if (value) {
                $.post('student_quiz_default.php',{ type : type, quiz: quiz, id: id, user : user },function(){  })
                window.location.reload();
            } else {
                console.log('nope');
            }
        }
    });
}

var CheckDuplicate ;
function CheckStudentDuplicate(data) {

    $.ajax({
        async:false,
        type:'POST',
        url:'ajax/check_duplicates.php',
        data:{users: data, type: 1},
        success:function(response){
            CheckDuplicate = response;
        }
    });

    return CheckDuplicate;


}


var CheckDuplicateUser ;
function CheckUsernameDuplicate(username) {

    $.ajax({
        async:false,
        type:'POST',
        url:'ajax/check_duplicates.php',
        data:{username: username, type: 2},
        success:function(response){
            CheckDuplicateUser = response;
        }
    });

    return CheckDuplicateUser;


}

var keyupcheck;
function keyupCheck(elem) {
    var username = $(elem).val();
    var this_id = $(elem).parent().parent().attr('data_id');

    var checkUsername = CheckUsernameDuplicate(username);

    if(checkUsername == 1){
       $('.student-info__'+this_id).addClass('error');
    } else {
        $('.student-info__'+this_id).removeClass('error');
    }


}
function buttonCheck(){
    var username = $('#usernameCheck').val();

    var trimUsername = username.replace(/\s/g, "");
    if(username && trimUsername.length !== 0 ){
        var checkUsername = CheckUsernameDuplicate(username);

        if(checkUsername == 1){
            $('.username_checker .error').addClass('show_msg');
            $('.username_checker .success').removeClass('show_msg');
            $('.username_checker .warning').removeClass('show_msg');
        } else {
            $('.username_checker .success').addClass('show_msg');
            $('.username_checker .error').removeClass('show_msg');
            $('.username_checker .warning').removeClass('show_msg');
        }
    } else {
        $('.username_checker .warning').addClass('show_msg');
        $('.username_checker .error').removeClass('show_msg');
        $('.username_checker .success').removeClass('show_msg');
    }



}

function RemoveStudent(id){
    $.fancyConfirm({
        title     : "Are you sure?",
        message   : "Are you sure you want to remove this student?",
        okButton  : 'Agree',
        noButton  : 'Disagree',
        callback  : function (value) {
            if (value) {
                $.post('ts_teacher_students_remove.php',{ id : id },function(){  });
                console.log(id);
                $('.student').slideUp(300, function(element){
                    $(this).remove();
                    if ($('.student-list').find('.student').length == 0) {
                        // window.location.reload();
                    }
                });
                console.log(id);

            } else {
            }
        }
    });
}

function RemoveClass(id){
    $.fancyConfirm({
        title     : "Are you sure?",
        message   : "Are you sure you want to remove this class?",
        okButton  : 'Agree',
        noButton  : 'Disagree',
        callback  : function (value) {
            if (value) {
                $.post('ts_teacher_class_remove.php',{ id : id },function(){  });
                console.log(id);
                $('.class').slideUp(300, function(element){
                    $(this).remove();
                    if ($('.class-list').find('.class').length == 0) {
                        window.location.reload();
                    }
                });
                console.log(id);

            } else {
            }
        }
    });
}


function RemoveStudentFromClass(id,classID){
    $.fancyConfirm({
        title     : "Are you sure?",
        message   : "Are you sure you want to remove Student from this class?",
        okButton  : 'Agree',
        noButton  : 'Disagree',
        callback  : function (value) {
            if (value) {
                $.post('https://codingfirst.org/ts_teacher_class_user_remove.php',{ student : id, classID : classID },function(response){
                    console.log("Server response:", response);
                });
                console.log(id);

                $('.list_student').slideUp(300, function(element){
                    $(this).remove();
                    if ($('.list_students').find('.list_students').length == 0) {
                        // window.location.reload();
                    }
                });
                console.log(id,classID);

            } else {
            }
        }
    });
}


function showLessonInfo(elem,id){

     var type = $(elem).attr('data-show');

     if(type == 1){
         // $('.lessons-list').addClass('hide-item');
         $('#lesson_info_'+id).removeClass('hide-item');
         $(elem).find('.show').addClass('hidden');
         $(elem).find('.hide').removeClass('hidden');

         $(elem).attr('data-show',0)
     } else {
         // $('.lessons-list').addClass('hide-item');
         $('#lesson_info_'+id).addClass('hide-item');
         $(elem).find('.show').removeClass('hidden');
         $(elem).find('.hide').addClass('hidden');

         $(elem).attr('data-show',1)
     }






}

var lesson_permission;
function LessonPermissionFunc(type,lesson,course, status) {
    $.ajax({
        async:false,
        type:'POST',
        url:'ts_teacher_lessonpermissionsaction.php',
        data:{type: type, lesson: lesson, course: course,status: status },
        success:function(response){
            lesson_permission = response;
            console.log(lesson_permission);
        }
    });

    return lesson_permission;
}


var lesson_permissionAll;
function AllLessonPermissionFunc(type,course, status){
    if(status == true){
        $('.t_'+type).each(function(){
            this.checked = true;
        });
    } else {
        $('.t_'+type).each(function(){
            this.checked = false;
        });
    }


    $.ajax({
        async:false,
        type:'POST',
        url:'ts_teacher_lessonpermissionsaction_all.php',
        data:{type: type,  course: course,status: status },
        success:function(response){
            lesson_permissionAll = response;
            console.log(lesson_permissionAll);
        }
    });

    return lesson_permissionAll;
}

var school_lesson_permission;
function SchoolLessonPermissionFunc(type,lesson,course, grade,section,status,gender) {
    $.ajax({
        async:false,
        type:'POST',
        url:'lesson_permission_action.php',
        data:{type: type, lesson: lesson, course: course, grade: grade, section: section ,status: status, gender: gender },
        success:function(response){
            school_lesson_permission = response;
            console.log(school_lesson_permission);
        }
    });

    return school_lesson_permission;
}


var school_lesson_permissionAll;
function SchoolAllLessonPermissionFunc(type,course, grade,section,status,gender){
    if(status == true){
        $('.t_'+type).each(function(){
            this.checked = true;
        });
    } else {
        $('.t_'+type).each(function(){
            this.checked = false;
        });
    }


    $.ajax({
        async:false,
        type:'POST',
        url:'lesson_permission_action_all.php',
        data:{type: type,  course: course,grade: grade, section: section,status: status, gender: gender },
        success:function(response){
            school_lesson_permissionAll = response;
            console.log(school_lesson_permissionAll);
        }
    });

    return school_lesson_permissionAll;
}

function ShowCheckAll(i){
    $('#lock_all_students_'+i).removeClass('hidden');
}

function ShowCheckAll2(i){
    $('#lock_all_students2').removeClass('hidden');
}

function ShowCheckAll3(i){
    $('#lock_all_certificates').removeClass('hidden');
}

function exportTableToCSV($table, filename) {

    var $rows = $table.find('tr:has(td)'),

        // Temporary delimiter characters unlikely to be typed by keyboard
        // This is to avoid accidentally splitting the actual contents
        tmpColDelim = String.fromCharCode(11), // vertical tab character
        tmpRowDelim = String.fromCharCode(0), // null character

        // actual delimiter characters for CSV format
        colDelim = '","',
        rowDelim = '"\r\n"',

        // Grab text from table into CSV formatted string
        csv = '"' + $rows.map(function(i, row) {
            var $row = $(row),
                $cols = $row.find('td');

            return $cols.map(function(j, col) {
                var $col = $(col),
                    text = $col.text();

                return text.replace(/"/g, '""'); // escape double quotes

            }).get().join(tmpColDelim);

        }).get().join(tmpRowDelim)
            .split(tmpRowDelim).join(rowDelim)
            .split(tmpColDelim).join(colDelim) + '"';

    // Deliberate 'false', see comment below
    if (false && window.navigator.msSaveBlob) {

        var blob = new Blob([decodeURIComponent(csv)], {
            type: 'text/csv;charset=utf8'
        });

        // Crashes in IE 10, IE 11 and Microsoft Edge
        // See MS Edge Issue #10396033
        // Hence, the deliberate 'false'
        // This is here just for completeness
        // Remove the 'false' at your own risk
        window.navigator.msSaveBlob(blob, filename);

    } else if (window.Blob && window.URL) {
        // HTML5 Blob
        var blob = new Blob([csv], {
            type: 'text/csv;charset=utf-8'
        });
        var csvUrl = URL.createObjectURL(blob);

        $(this)
            .attr({
                'download': filename,
                'href': csvUrl
            });
    } else {
        // Data URI
        var csvData = 'data:application/csv;charset=utf-8,' + encodeURIComponent(csv);

        $(this)
            .attr({
                'download': filename,
                'href': csvData,
                'target': '_blank'
            });
    }
}
