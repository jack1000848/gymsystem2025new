<!DOCTYPE html>
<html lang="en">

<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <link rel="stylesheet" href="https://unpkg.com/aos@next/dist/aos.css" />
    <link href='https://unpkg.com/boxicons@2.1.4/css/boxicons.min.css' rel='stylesheet'>
    <link rel="stylesheet" href="<?= base_url('assets/css/frontend.css') ?>">


    <title>iShowFitnessGYM</title>
</head>

<body>
    <!-- Header Section -->

    <header>
        <a href="#" class="logo">iShow Fitness <span>GYM</span></a>

        <div class="bx bx-menu" id="menu-icon"></div>

        <ul class="navbar">
            <li><a href="#home">Home</a></li>
            <li><a href="#services">Services</a></li>
            <li><a href="#about">About Us</a></li>
            <li><a href="#plans">Pricing</a></li>
            <li><a href="#review">Review</a></li>
        </ul>

        <div class="top-btn">
            <a href="<?= base_url('joinus') ?>" class="nav-btn">Join Us</a>
        </div>
    </header>

    <!-- Home Section -->

    <section id="home" class="home">
        <div class="home-content" data-aos="zoom-in">
            <h3>Build Your</h3>
            <h1>Dream Physique</h1>
            <h3><span class="multiple-text"></span></h3>
            <p>Lorem ipsum dolor sit, bus earum, aliquam ipsa repellat iusto esse laudantium animi vitae consectetur
                obcaecati.</p>

            <a href="<?= base_url('joinus') ?>" class="btn">Join Us</a>
            
        </div>

        <div class="home-img" data-aos="zoom-in">
            <img src="<?= base_url('assets/img/alex.jpg.png') ?>" alt="HeroImage">
        </div>
    </section>

    <!-- services Section Code -->

    <section class="services" id="services">

        <h2 class="heading" data-aos="zoom-in-down">Our <span>Services</span></h2>

        <div class="services-content" data-aos="zoom-in-up">
            <div class="row">
                <img src="<?= base_url('assets/img/physicalfit.jpg.jpg') ?>" alt="">

                <h4>Physical Fitness</h4>
            </div>

            <div class="row">
                <img src="<?= base_url('assets/img/weightgain.jpg.jpg') ?>" alt="">

                <h4>Weight Gain</h4>
            </div>

            <div class="row">
                <img src="<?= base_url('assets/img/strenghtrain.jpg.jpg') ?>" alt="">

                <h4>Strength Training</h4>
            </div>

            <div class="row">
                <img src="<?= base_url('assets/img/fatloss.jpg.jpg') ?>" alt="">

                <h4>Fat Loss</h4>
            </div>

            <div class="row">
                <img src="<?= base_url('assets/img/weightlif.jpg.jpg') ?>" alt="">

                <h4>Weightlifting</h4>
            </div>

            <div class="row">
                <img src="<?= base_url('assets/img/running.jpg.jpg') ?>" alt="">

                <h4>Running</h4>
            </div>

            <div class="row">
                <img src="<?= base_url('assets/img/weightgain.jpg.jpg') ?>" alt="">

                <h4>Wsheesh Gain</h4>
            </div>
        </div>
    </section>

    <!-- About Section -->
    <section id="about" class="about">
        <div class="about-img" data-aos="zoom-in-down">
            <img src=<?= base_url('assets/img/rdg.jpg.jpg') ?> alt="">
        </div>

        <div class="about-content" data-aos="zoom-in-up">
            <h2 class="heading">Why Choose Us?</h2>
            <p>Our diverse membership base creates a friendly and supportive atmosphere, where you can make friends and
                stay motivated.</p>
            <p>Unlock your potential with our expert Personal Trainers.</p>
            <p>Elevate your fitness with practice sessions.</p>
            <p>We provide Supportive management, for your fitness success.</p>
            <a href="<?= base_url('joinus') ?>" class="btn">Book Now!</a>
        </div>
    </section>



    <!-- Pricing Section Code -->

    <section class="plans" id="plans">
        <h2 class="heading" data-aos="zoom-in-down">Our <span>Plans</span></h2>


        <div class="plans-content" data-aos="zoom-in-up">
            <div class="box">
                <h3>BASIC</h3>
                <h2><span>₱1000/Month</span></h2>
                <ul>
                    <li>Smart workout plan</li>
                    <li>At home workouts</li>
                </ul>
                <a href="<?= base_url('joinus') ?>">
                    Join Now
                    <i class='bx bx-right-arrow-alt'></i>
                </a>
            </div>
            <div class="box">
                <h3>PRO</h3>
                <h2><span>₱1500/Month</span></h2>
                <ul>
                    <li>Pro GYMS</li>
                    <li>Smart workout plan</li>
                    <li>At home workouts</li>
                </ul>
                <a href="<?= base_url('joinus') ?>">
                    Join Now
                    <i class='bx bx-right-arrow-alt'></i>
                </a>
            </div>
            <div class="box">
                <h3>PREMIUM</h3>
                <h2><span>₱3500/Month</span></h2>
                <ul>
                    <li>ELITE Gyms & Classes</li>
                    <li>Pro GYMS</li>
                    <li>Smart workout plan</li>
                    <li>At home workouts</li>
                    <li>Personal Training</li>
                </ul>
                <a href="<?= base_url('joinus') ?>">
                    Join Now
                    <i class='bx bx-right-arrow-alt'></i>
                </a>
            </div>
        </div>
    </section>

    <!-- Review Section -->

    <section id="review" class="review">
        <div class="review-box">
            <h2 class="heading" data-aos="zoom-in-down">Client Reviews</h2>

            <div class="wrapper" data-aos="zoom-in-up">
                <div class="review-item">
                    <img src="<?= base_url('assets/img/luiereview.jpg') ?>" alt="Review1">
                    <h2>Ninoy Achino</h2>
                    <div class="rating">
                        <i class='bx bxs-star' id="star"></i>
                        <i class='bx bxs-star' id="star"></i>
                        <i class='bx bxs-star' id="star"></i>
                        <i class='bx bxs-star' id="star"></i>
                        <i class='bx bxs-star' id="star"></i>
                    </div>
                    <p>I'm Ninoy Achino from NAIA 1, i'm glad to give 5 star this gym website, this is totaly good for me knowing everything in the gym how to used this shit.</p>
                </div>

                <div class="review-item">
                    <img src="<?= base_url('assets/img/robinreview.jpg') ?>" alt="Review2">
                    <h2>Rodrie Padilla</h2>
                    <div class="rating">
                        <i class='bx bxs-star' id="star"></i>
                        <i class='bx bxs-star' id="star"></i>
                        <i class='bx bxs-star' id="star"></i>
                        <i class='bx bxs-star' id="star"></i>
                    </div>
                    <p>yes i am, Rodrie ill give 100star review bcause this is good for me helping me a lot and easy transaction for payment.</p>
                </div>

                <div class="review-item">
                    <img src="<?= base_url('assets/img/jomsreview.jpg') ?>" alt="Review3">
                    <h2>David Tumaex</h2>
                    <div class="rating">
                        <i class='bx bxs-star' id="star"></i>
                        <i class='bx bxs-star' id="star"></i>
                        <i class='bx bxs-star' id="star"></i>
                        <i class='bx bxs-star' id="star"></i>
                        <i class='bx bxs-star' id="star"></i>
                    </div>
                    <p>owshitt who the fucked guy create this system? im so fucking happy that this system is give me a good way body building, to build my self and learn how to use everything in the gym how the shit works on me like what the hell is goin on here like helps me in a easy way, like men what the fuck kuddos to fukcing people who made this.</p>
                </div>
            </div>
        </div>
    </section>

    <!-- Footer Section -->

    <footer class="footer">
        <div class="social">
            <a href="https://www.instagram.com/miguelito_tayson/"><i class='bx bxl-instagram-alt'></i></a>
            <a href="#"><i class='bx bxl-facebook-square'></i></a>
            <a href="#"><i class='bx bxl-linkedin-square'></i></a>
        </div>

        <p class="copyright">
            &copy; MIGSS RDG - All Rights Reserved
        </p>
    </footer>

    <script src="https://unpkg.com/aos@next/dist/aos.js"></script>
    <script>
        AOS.init({
            offset: 300,
            duration: 1400,
        });
    </script>
    <script src="https://unpkg.com/typed.js@2.1.0/dist/typed.umd.js"></script>
    <script src="<?= base_url('assets/js/frontend.js') ?>"></script>

</body>

</html>
