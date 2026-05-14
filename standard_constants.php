<?php

// =============================
// 📌 STANDARD MENU CONSTANTS
// =============================

/* =============================
   BASE URL (SAFE + GLOBAL)
============================= */
if (!defined('BASE_URL')) {

    $protocol = (!empty($_SERVER['HTTPS']) && $_SERVER['HTTPS'] !== 'off') ? "https://" : "http://";

    $host = $_SERVER['HTTP_HOST'];

    // Get project folder dynamically
    $scriptPath = str_replace(basename($_SERVER['SCRIPT_NAME']), '', $_SERVER['SCRIPT_NAME']);

    define('BASE_URL', $protocol . $host . $scriptPath);
}

// define("SCRATCHWITHMAKEYMAKEYBUILDPATH", "makeymakey/build/");
//define("SCRATCHWITHMAKEYMAKEYBUILDPATH", "http://localhost:8601/");
define("SCRATCHWITHMAKEYMAKEYBUILDPATH", "https://scratch.coding4schools.org");


/* =============================
   USER ROLES
============================= */
define("TEACHER", 2);
define("STUDENT", 3);
define("SITEADMIN", 1);
define("SCHOOLADMIN", 4);
define("SUPERADMIN", 6);
define("COMPLETED", 'completed');
define("UN_AUTHORIZED_USER", 0);
define("AVAILABLE_FOR_SCHOOL", 0);
define("DISPATCH", 'dispatch');


define("DECRYPT_KEY", 'mySecretKey123');

define('TRANSACTION_STATUS_SUCCESS','success');
define('TRANSACTION_STATUS_ERROR','error');



// ----- GRADE -----
define('GRADE_ADD', 15);
define('GRADE_EDIT', 16);
define('GRADE_DELETE', 17);
define('GRADE_VIEW_ICON', 38);
define("GRADE_SEARCH",48);
define("GRADE_VIEW",50);
// ----- SECTION -----
define('SECTION_ADD', 18);
define('EDIT_SECTION', 19);
define('DELETE_SECTION', 20);
define('SECTION_VIEW_ICON', 52);

define("SECTION_SEARCH",49);
define("SECTION_VIEW",51);
define("MANAGE_SECTION",73);




// ----- COURSE -----
define('COURSE_ADD', 7);
define('COURSE_EDIT', 8);
define('COURSE_DELETE', 9);
define('COURSE_EXPORT', 10);
define('COURSE_SEARCH', 67);
define('COURSE_VIEW', 68);

// ----- LESSON -----
define('LESSON_ADD', 11);
define('LESSON_EDIT', 12);
define('LESSON_DELETE', 13);
define('COURSE_CULTURAL_ACTIVITY_ADD', 14);
define('COURSE_SYLLABUS_ADD',69);
define('LESSON_MANAGE_PRACTICES', 70);
define('LESSON_VIEW', 71);
define('LESSON_SEARCH', 72);
define('LESSON_MANAGE_QUIZ', 74);
define('LESSON_MANAGE_EXERCISE', 84);
define('LESSON_MANAGE_PROBLEMS', 85);




// ----- TEACHER -----
define('TEACHER_ADD', 21);
define('TEACHER_EDIT', 22);
define('TEACHER_DELETE', 23);
define('TEACHER_IMPORT', 24);
define('TEACHER_SEARCH', 59);
define('TEACHER_VIEW', 60);
define('TEACHER_VIEW_ICON', 61);

// ----- STUDENT -----
define('STUDENT_ADD', 25);
define('STUDENT_EDIT', 26);
define('STUDENT_DELETE', 27);
define('STUDENT_IMPORT', 28);
define('STUDENT_SEARCH', 62);
define('STUDENT_VIEW', 63);
define('STUDENT_VIEW_ICON', 64);

// ----- SPECIAL -----
define('LESSON_PERMISSION_MANAGE', 29);
define('QUIZ_EXPORT', 30);
define('QUIZ_ADD', 31);
define('QUIZ_EDIT', 32);
define('QUIZ_DELETE', 75);
define('QUIZ_ATTEMPT', 76);
define('EXAM_ATTEMPT', 77);
define('QUIZ_RESULT_VIEW_ICON', 78);
define('QUIZ_DISPATCH', 79);
define('QUESTION_ADD', 80);
define('QUESTION_EDIT', 81);
define('QUESTION_DELETE', 82);
define('ANSWER_HIDE', 86);



define('QUESTION_BANK_MANAGE', 33);
define('PRACTICES_MANAGE', 34);
define('MENUS_MANAGE', 35);
define('ROLES_MANAGE', 36);
define('PERMISSIONS_MANAGE', 37);
define('MANAGE_STUDENTS_ICON', 40);
define('MANAGE_TEACHERS_ICON', 41);
define('MANAGE_COURSE_ICON', 42);
define('REMOVE_STUDENT_FROM_SECTION', 43);
define('REMOVE_TEACHER_FROM_SECTION', 44);
define('REMOVE_COURSE_FROM_SECTION', 45);
define('ADD_TEACHER_TO_SECTION',46);
define('ADD_COURSE_TO_SECTION',47);
define('SECTION_STUDENT_LIST_VIEW',53);
define('SECTION_STUDENT_SEARCH',54);
define('SECTION_TEACHER_LIST_VIEW',55);
define('SECTION_TEACHER_SEARCH',56);
define('SECTION_COURSE_LIST_VIEW',57);
define('SECTION_COURSE_SEARCH',58);

define('ASSETS_USE_BUTTON',87);
define('SAVE_CODE_LOCALLY',88);
define('LOAD_SAVED_CODE',89);




?>















