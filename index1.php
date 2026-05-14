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



<div class="banner-section">
    <div class="single-carousel owl-carousel">

        <picture class="slideShow">
            <source media="(max-width: 800px)" data-srcset="https://www.codingfirst.org/images/7_mobile.png" />
            <img class="lazy" data-src="https://www.codingfirst.org/images/7.png">

            <div class="slideContent slide-3">
                <h1 class="color-white">
                    
                    Every child deserves
                    <span>to learn how to <b class="color-yellow">&lt;code/&gt;</b></span>

                </h1>

                <div class="sep"></div>

                <h3 class="color-white" style="width: unset">
                    the best in class programs to raise future innovators
                </h3>

                <div class="sep"></div>


                <a class="slide-btn bg-yellow" href="https://www.codingfirst.org/schools.php">Explore now</a>
            </div>

        </picture>

        <picture class="slideShow">
            <source media="(max-width: 800px)" data-srcset="https://www.codingfirst.org/images/8_mobile.png" />
            <img class="lazy" data-src="https://www.codingfirst.org/images/8.png">

            <div class="slideContent slide-1">
                <h1 class="color-white">Let Your Children Build </h1>
                <h2 class="color-pink">a Brighter Future</h2>


                <h3 class="color-white" style="width: unset">
                    Learn Coding, AI, and 3D Design From Our Courses
                </h3>
                <div class="sep"></div>
                <div class="sep"></div>

                <a class="slide-btn color-blue-light" data-type="iframe" data-fancybox  href="https://www.codingfirst.org/enroll.php">book a free 1-1 counselling</a>

            </div>
        </picture>

        <picture class="slideShow">
            <source media="(max-width: 800px)" data-srcset="https://www.codingfirst.org/images/3_mobile.png" />
            <img class="lazy" data-src="https://www.codingfirst.org/images/3.png">

            <div class="slideContent slide-2">
                <h1 class="color-gray">
                    The best coding curriculum
                </h1>

                <h2 class="color-orange">
                    For Kids of All Ages!
                </h2>

                <h3 class="color-gray" style="width: unset">
                    STEM Certified, Comprehensive & Fun Curriculum
                </h3>

                <div class="sep"></div>
                <div class="sep"></div>
                <div class="sep"></div>

                <a class="slide-btn orange" target="_blank" href="https://www.codingfirst.org/course_1.php">Explore now</a>
            </div>
        </picture>

    </div>
</div>

<div class="gray-section">

    <div class="row p-80  m-50">
        <div class="col-50">
            <div class="pictures">
                <img src="https://www.codingfirst.org/images/item_27.png">
            </div>

        </div>
        <div class="col-50">
            <div class="main-title-section">
                Learn | Create | Innovate
            </div>
            <p class="section-text">
                Coding First’s curriculum empowers kids and youth with the 21st century skills and gives them a competitive advantage in an AI ruled future!
            </p>
        </div>
    </div>

</div>

<div class="white-section">

    <div class="row p-80 m-50">

        <div class="main-title text-center" style="font-size: 30px;max-width: 800px; margin: 10px auto;">
           The leading STEAM learning Platform
        </div>
        <p style="width: 100%; font-size: 18px;font-weight: 300;max-width: 800px;margin: auto;" class="text-center">
            Discover a whole new way of learning and problem solving with cutting edge technology and a leading stem
            certified curriculum.
        </p>

        <div class="sep"></div>
        <div class="sep"></div>
        <div class="sep"></div>
        <div class="sep"></div>


        <div class="col-50">
            <div class="main-title-section">
                Graphical Programming
            </div>
            <p class="section-text">
                Learn programming, machine learning, programs for robots, code and design games and much more with Scratch a blocks based coding platform developed by the Massachusetts Institute of Technology!
            </p>
        </div>
        <div class="col-50">
            <div class="pictures">
                <img src="https://www.codingfirst.org/images/item_28.png">
            </div>
        </div>
    </div>
    <div class="row p-80 m-50">
        <div class="col-50">
            <div class="pictures">
                <img src="https://www.codingfirst.org/images/item_26.png">
            </div>
        </div>
        <div class="col-50">
            <div class="main-title-section">
                Get Certified
            </div>
            <p class="section-text">
                Receive a certificate at the end of each course and share your new skills with your family and friends.
                Coding First has the leading STEM curriculum in Asia and the Gulf. Its curriculum is developed by a team of Engineers and doctorates, led by Dr. Houry Keoshkerian.

            </p>
        </div>
    </div>

</div>

<div class="gray-section">
    <div class="row p-80 m-50">




        <div class="col-50">
            <div class="main-title text-center">
                Become a Changemaker
            </div>
            <p class="text-center">
                Learn programming and AI to be well equipped for tomorrow’s world.
            </p>

            <div class="sep"></div>

            <div class="items">
                <div class="item">
                    <img src="https://www.codingfirst.org/images/item_1.png">
                    <p>Innovate</p>
                </div>
                <div class="item">
                    <img src="https://www.codingfirst.org/images/item_2.png">
                    <p>Reason Logically </p>
                </div>
                <div class="item">
                    <img src="https://www.codingfirst.org/images/item_3.png">
                    <p>Create</p>
                </div>
                <div class="item">
                    <img src="https://www.codingfirst.org/images/item_4.png">
                    <p>Think Critically</p>
                </div>
                <div class="item">
                    <img src="https://www.codingfirst.org/images/item_5.png">
                    <p>Solve Problems</p>
                </div>
                <div class="item">
                    <img src="https://www.codingfirst.org/images/item_6.png">
                    <p>Be a team player</p>
                </div>
            </div>

        </div>

        <div class="col-50">
            <div class="pictures">
                <img src="https://www.codingfirst.org/images/girl_sitting_5.png">
            </div>
        </div>



    </div>

</div>

<div class="testimonials-section">

    <div class="row p-80 m-50">

        <div class="main-title text-center">What They Think About Us</div>
        <div class="sep"></div>
        <div class="sep"></div>

        <div class="testimonials">
            <div class="testimonial">
                <picture style="background-image: url('https://www.codingfirst.org/images/testimonial1.png')"></picture>
                <div>
                <div class="title-1">imad</div>
                <div class="title-2">Teacher</div>
                <div class="text">
                    <span><i class="fas fa-quote-left"></i></span>I feel that having such a good system in a website allowed me and other teachers to focus more on the students’ learning experience and less on curriculum preparation, quiz creation and correction.
                    Quizzes are corrected automatically, the curriculum is very well structured and fun. <span><i class="fas fa-quote-right"></i></span>
                </div>
                </div>
            </div>

            <div class="testimonial">
                <picture style="background-image: url('https://www.codingfirst.org/images/testimonial2.png')"></picture>
                <div>
                <div class="title-1">Hans</div>
                <div class="title-2">Director</div>
                <div class="text">
                    <span><i class="fas fa-quote-left"></i></span>  Since I adopted the Coding First system and curriculum in my school, I noticed a tremendous improvement in my business: Students, teachers and parents all gave us a much better feedback for having a very well organized and trackable system.
                    Owner of Hansen Education Consulting and director at Wuyan Training school <span><i class="fas fa-quote-right"></i></span>
                </div>
                </div>
            </div>

            <div class="testimonial">
                <picture style="background-image: url('https://www.codingfirst.org/images/testimonial3.png')"></picture>
                <div>
                <div class="title-1">Becky</div>
                <div class="title-2">Student</div>
                <div class="text">
                    <span><i class="fas fa-quote-left"></i></span>  The curriculum is fun, easy and challenging. I enjoy it every time. I also like it much better than the old-fashioned ICT course we used to learn in the past. The online Coding First website allows me to login anytime to review lessons of the past, take quizzes and compete with fellow students.
                    <span><i class="fas fa-quote-right"></i></span>
                </div>
                </div>

            </div>

            <div class="testimonial">
                <picture style="background-image: url('https://www.codingfirst.org/images/testimonial4.png')"></picture>
                <div>
                <div class="title-1">Zao</div>
                <div class="title-2">Parent</div>
                <div class="text">
                    <span><i class="fas fa-quote-left"></i></span>
                    My 13 year old daughter has been learning at Coding First for four years. Her love and passion for coding keep increasing. I am so happy to see her get hands-on experience with coding concepts at such a young age.
                    <span><i class="fas fa-quote-right"></i></span>
                </div>
                </div>
            </div>

            <div class="testimonial">
                <picture style="background-image: url('https://www.codingfirst.org/images/testimonial5.png')"></picture>
                <div>
                    <div class="title-1">Andrew</div>
                    <div class="title-2">STEM.org Founder</div>
                    <div class="text">
                        <span><i class="fas fa-quote-left"></i></span>
                        Coding First's inherent role in the educational ecosystem has placed it in the unique, but challenging position of leading others by example, while striving for continued improvement of its program in the ever-changing, highly competitive 21st century global economy. It is a well-deserved and hard-won achievement in which you should be proud.
                        <span><i class="fas fa-quote-right"></i></span>
                    </div>
                </div>
            </div>

            <div class="testimonial">
                <picture style="background-image: url('https://www.codingfirst.org/images/testimonial7.png')"></picture>
                <div>
                    <div class="title-1">Justin</div>
                    <div class="title-2">Teacher</div>
                    <div class="text">
                        <span><i class="fas fa-quote-left"></i></span>
                        While we have been using the codingfirst.org platform, the team has been open to feedback and always finds ways to make the platform better.

                        The students have found it easy to use and are given plenty of opportunities to practice their new skill.

                        The curriculum itself is different from many other ones that are offered on the wider internet.
                        <span><i class="fas fa-quote-right"></i></span>
                    </div>
                </div>
            </div>


        </div>

    </div>

</div>

<div>
    <div class="row p-80 m-50">

        <div class="col-50">
            <div class="pictures">
                <img src="https://www.codingfirst.org/images/pic-ideas.png">
            </div>
        </div>

        <div class="col-50">
            <div class="text-capitalize">
                Learn with the Best-In Class STEM education resources to innovate and become a change maker.

            </div>
            <div class="sep"></div>
            <div class="sep"></div>
            <div class="sep"></div>

            <div class="buttons">
                   <a href="https://www.codingfirst.org/login.php" class="main-btn">i'm a student</a>
                   <a href="https://www.codingfirst.org/login.php" class="main-btn">i'm an educator</a>
            </div>

        </div>

    </div>
</div>
