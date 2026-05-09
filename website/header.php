<?php
require_once __DIR__ . '/config.php';
require_once __DIR__ . '/includes/nav_menus.php';
$nav_menus = fruitwala_get_nav_menus($conn);
if ($nav_menus === []) {
    $nav_menus = fruitwala_nav_menus_fallback();
}
?>
<!DOCTYPE html>
<html lang="en">
<head>
   <meta charset="UTF-8">
   <meta http-equiv="X-UA-Compatible" content="IE=edge">
   <meta name="viewport" content="width=device-width, initial-scale=1.0">
   <title>Fruit-Wala Breakfast</title>
   <!-- favicon icon -->
   <link rel="shortcut icon" href="logo.png" type="image/x-icon">

   <!-- Include fontawesome CDN -->
   <link rel="stylesheet" href="../../../../cdnjs.cloudflare.com/ajax/libs/font-awesome/6.0.0-beta3/css/all.min.css"
      integrity="sha512-Fo3rlrZj/k7ujTnHg4CGR2D7kSs0v4LLanw2qksYuRlEzO+tcaEPQogQ0KaoGN26/zrn20ImR1DfuLWnOo7aBA=="
      crossorigin="anonymous" referrerpolicy="no-referrer" />

   <!-- Include google fonts CDN -->
   <link rel="preconnect" href="https://fonts.googleapis.com/">
   <link rel="preconnect" href="https://fonts.gstatic.com/" crossorigin>
   <link href="https://fonts.googleapis.com/css2?family=Montserrat:wght@400;500;600;700;800;900&amp;family=Roboto:wght@400;500;700;900&amp;display=swap"
      rel="stylesheet">
      <link rel="stylesheet" href="https://cdnjs.cloudflare.com/ajax/libs/font-awesome/6.5.0/css/all.min.css">


   <!-- Include bootstrap CSS -->
   <link rel="stylesheet" href="assets/css/bootstrap.min.css">

   <!-- Include aos CSS -->
   <link rel="stylesheet" href="assets/css/aos.css">

   <!-- Include magnific-popup CSS -->
   <link rel="stylesheet" href="assets/css/magnific-popup.css">

   <!-- Include nice-select CSS -->
   <link rel="stylesheet" href="assets/css/nice-select.css">

   <!-- Include slick-theme CSS -->
   <link rel="stylesheet" href="assets/css/slick-theme.css">

   <!-- Include slick CSS -->
   <link rel="stylesheet" href="assets/css/slick.css">

   <!-- Include stylesheet CSS -->
   <link rel="stylesheet" href="assets/css/style.css">
   <style>
.product_image_wrap {
    /* height: 220px !important; */
    display: flex !important;
    align-items: center !important;
    justify-content: center !important;
    padding: 10px !important;
    background: #fff;
}

.product_image_wrap img {
    max-height: 100% !important;
    max-width: 100% !important;
    width: auto !important;
    height: auto !important;
    object-fit: contain !important;
}
.package-points {
    max-width: 300px;
    text-align: left;
    margin: 0 auto;
    padding-left: 18px;
    line-height: 1.8;
}


.reel-card {
    position: relative;
    cursor: pointer;
    overflow: hidden;
    border-radius: 12px;
}

.reel-card img {
    width: 100%;
    border-radius: 12px;
    transition: 0.3s;
}

.reel-card:hover img {
    transform: scale(1.05);
}

.reel-play {
    position: absolute;
    top: 50%;
    left: 50%;
    transform: translate(-50%, -50%);
    background: rgba(0,0,0,0.6);
    width: 60px;
    height: 60px;
    border-radius: 50%;
    display: flex;
    justify-content: center;
    align-items: center;
    color: #fff;
    font-size: 20px;
}

/* ///////////////////////////////////////////////////////////////////// */

.reel-card {
    position: relative;
    cursor: pointer;
    overflow: hidden;
    border-radius: 12px;
}

.reel-card img {
    width: 100%;
    display: block;
    transition: 0.3s;
}

.reel-card:hover img {
    transform: scale(1.05);
}

.reel-play {
    position: absolute;
    top: 50%;
    left: 50%;
    transform: translate(-50%, -50%);
    background: rgba(0,0,0,0.6);
    width: 60px;
    height: 60px;
    border-radius: 50%;
    display: flex;
    justify-content: center;
    align-items: center;
    color: white;
    font-size: 22px;
}

#qualityVideo {
    box-shadow: 0 20px 50px rgba(0,0,0,0.25);
}

#qualityVideo {
    transition: transform 0.5s ease;
}
#qualityVideo:hover {
    transform: scale(1.03);
}

.breadcrumb_wrap {
    position: relative;
    z-index: 1;
}

.breadcrumb_wrap::before {
    content: "";
    position: absolute;
    top: 0;
    left: 0;
    width: 100%;
    height: 100%;
    background: rgba(0, 0, 0, 0.55); /* Darkness level (0.55 = perfect) */
    z-index: -1;
}
.breadcrumb_item li {
    display: flex;
    align-items: center;
    gap: 8px;
    font-weight: 500;
}

.breadcrumb_item li i.fa-chevron-right {
    font-size: 14px;
    margin: 0 10px;
    color: #ffffffcc; /* soft white */
}
/* Make testimonial images circular */
.testimonial_author {
    width: 220px;              /* Control circle size */
    height: 220px;
    border-radius: 50%;
    overflow: hidden;
    flex-shrink: 0;            /* Prevents shrinking in flex */
    margin-right: 30px;        /* Space between image & text */
}

.testimonial_author img {
    width: 100%;
    height: 100%;
    object-fit: cover;         /* Keeps face centered */
    display: block;
}
@media (max-width: 768px) {
    .testimonial_layout_1 {
        flex-direction: column;
        text-align: center;
    }

    .testimonial_author {
        margin-right: 0;
        margin-bottom: 20px;
        width: 160px;
        height: 160px;
    }
}
/* Wrapper must allow overlap */
.call-hover-wrapper {
    position: relative;
    z-index: 10;
}

/* Main button */
.call-hover-btn {
    position: relative;
    display: flex;
    align-items: center;
    justify-content: flex-end;
    text-decoration: none;
}

/* Phone Circle */
.call-icon {
    background: #8cc63f;
    color: #fff;
    width: 45px;
    height: 45px;
    border-radius: 50%;
    display: flex;
    align-items: center;
    justify-content: center;
    transition: all 0.4s ease;
    z-index: 2;
}

/* Hidden expanding part */
.call-text {
    position: absolute;
    right: 45px;
    height: 45px;
    line-height: 45px;
    padding: 0 18px;
    background: #8cc63f;
    color: #fff;
    border-radius: 30px 0 0 30px;
    white-space: nowrap;
    font-weight: 600;

    opacity: 0;
    transform: translateX(20px);
    transition: all 0.4s ease;
    z-index: 1;
}

/* HOVER MAGIC ✨ */
.call-hover-btn:hover .call-text {
    opacity: 1;
    transform: translateX(0);
}

.call-hover-btn:hover .call-icon {
    border-radius: 0 50% 50% 0;
}
.whatsapp-float {
    position: fixed;
    width: 60px;
    height: 60px;
    bottom: 20px;
    right: 20px;
    z-index: 9999;
    border-radius: 50%;
    box-shadow: 0 4px 12px rgba(0,0,0,0.3);
    animation: pulse 1.8s infinite;
    cursor: pointer;
}

.whatsapp-float img {
    width: 100%;
    height: 100%;
    border-radius: 50%;
}

.whatsapp-float:hover {
    transform: scale(1.12);
    transition: 0.3s ease;
}

/* Pulse Animation */
@keyframes pulse {
    0% {
        transform: scale(1);
        box-shadow: 0 0 0 0 rgba(37, 211, 102, 0.7);
    }
    70% {
        transform: scale(1.08);
        box-shadow: 0 0 0 18px rgba(37, 211, 102, 0);
    }
    100% {
        transform: scale(1);
        box-shadow: 0 0 0 0 rgba(37, 211, 102, 0);
    }
}

/* Mobile size adjustment */
@media (max-width: 576px) {
    .whatsapp-float {
        width: 55px;
        height: 55px;
        bottom: 15px;
        right: 15px;
    }
}

</style>




</head>

<body>
    <!-- WhatsApp Floating Button -->
<a href="https://wa.me/918812925014?text=Hello%20I%20want%20more%20details"
   class="whatsapp-float"
   target="_blank">
    <img src="https://cdn-icons-png.flaticon.com/512/733/733585.png"
         alt="WhatsApp Chat">
</a>
   <!-- body wrap start -->
   <div class="body-wrap overflow-hidden">
      <!-- back-to-top start -->
      <div class="backtotop">
         <a href="#!" class="scroll">
            <i class="fas fa-arrow-up fw-bold"></i>
         </a>
      </div>
      <!-- back-to-top end -->

      <!-- header section start -->
      <header class="header_section header_1">
         <!-- top header start -->
         <div class="top_header_main d-none d-sm-block">
    <div class="container">
        <div class="header_top d-flex align-items-center justify-content-between">
            
            <div class="header_top_content d-flex pt-2">
                
                <!-- Email -->
                <div class="mail_text_content d-flex">
                    <p class="mail_icon">
                        <span><i class="far fa-envelope text-white pe-2"></i></span>
                    </p>
                    <p class="mail_text">contact@fruitwalabreakfast.in</p>
                </div>

                <!-- Location -->
                <div class="address_text_content d-flex ms-4">
                    <p class="mail_icon">
                        <span><i class="fas fa-map-marker-alt text-white pe-2"></i></span>
                    </p>
                    <p class="address_text">Nagpur, Maharashtra</p>
                </div>

            </div>

            <!-- Social Media -->
            <div class="header_top_socials pt-2">
    <ul class="list-unstyled d-flex mb-0">

        <!-- Facebook (Replace with real page link if available) -->
        <li>
            <a href="https://www.facebook.com/fruitwalabreakfast" target="_blank">
                <i class="fab fa-facebook-f text-white pe-3"></i>
            </a>
        </li>

        <!-- (Optional) Twitter — Remove if not used -->
        <li>
            <a href="https://twitter.com/fruitwalabreak" target="_blank">
                <i class="fab fa-twitter text-white pe-3"></i>
            </a>
        </li>

        <!-- Instagram -->
        <li>
            <a href="https://www.instagram.com/fruitwala_breakfast/" target="_blank">
                <i class="fab fa-instagram text-white pe-3"></i>
            </a>
        </li>

        <!-- WhatsApp Order Link -->
        <li>
            <a href="https://wa.me/918812925014" target="_blank">
                <i class="fab fa-whatsapp text-white"></i>
            </a>
        </li>

    </ul>
</div>


        </div>
    </div>
</div>

         <!-- top header end -->

         <!-- bottom header start -->
         <div class="header_bottom_main">
            <div class="container">
               <!-- web menubar start-->
               <div class="webMenu d-none d-lg-block position-relative">
                  <nav class="navbar navbar-expand-lg navbar-light">
                     <a class="navbar-brand position-relative" href="index.php"><img src="logo.png" style="height: 100px;"
                           alt="image_not_found"></a>
                     <button class="navbar-toggler" type="button" data-bs-toggle="collapse"
                        data-bs-target="#navbarSupportedContent" aria-controls="navbarSupportedContent"
                        aria-expanded="false" aria-label="Toggle navigation">
                        <span class="navbar-toggler-icon"></span>
                     </button>
                     <div class="collapse navbar-collapse" id="navbarSupportedContent">
                        <ul class="navbar-nav main_menu_list m-auto after_navbar">
                           <?php foreach ($nav_menus as $nav_item): ?>
                           <li class="nav-item nav_item_has_child px-2">
                              <a class="nav-link" href="<?= htmlspecialchars($nav_item['url'], ENT_QUOTES, 'UTF-8') ?>"><?= htmlspecialchars($nav_item['title'], ENT_QUOTES, 'UTF-8') ?></a>
                           </li>
                           <?php endforeach; ?>
                        </ul>
                     </div>
                     <div class="navbar_user me-2">
                        <div class="navbar_user_icon">
                           <ul class="list-unstyled d-flex mb-0">
                              <!-- <li class="pe-3">
                                 <button class="main_search_btn" data-bs-toggle="collapse"
                                    data-bs-target="#main_search_collapse" aria-expanded="false"
                                    aria-controls="main_search_collapse">
                                    <i class="search_icon fas fa-search"></i>
                                    <i class="search_close fas fa-times"></i>
                                 </button>
                              </li> -->
                             <!-- <li class="pe-2"><a href="javascript:void(0);"><i class="far fa-heart"></i></a></li> -->

                              <li class="call-hover-wrapper">
                                 <a href="contact.php" class="call-hover-btn">
                                    <span class="call-text">+91 88129 25014</span>
                                    <span class="call-icon">
                                       <i class="fas fa-phone"></i>
                                    </span>
                                 </a>
                              </li>

                           </ul>
                        </div>
                     </div>
                  </nav>
                  <!-- webmenu bottom shape -->
                  <!-- <div class="webmenu_bottom_shape_left position-absolute">
                     <img src="assets/images/shapes/shape1.png" alt="image_not_found">
                  </div> -->
               </div>
               <!-- mobile menubar start -->
               <div class="mobileMenu d-block d-lg-none">
                  <nav class="navbar navbar-expand-lg navbar-light">
                     <a class="navbar-brand" href="index.php"><img src="logo.png"
                           alt="image_not_found"></a>
                     <button class="navbar-toggler" type="button" data-bs-toggle="offcanvas"
                        data-bs-target="#offcanvasScrolling" aria-controls="offcanvasScrolling" aria-expanded="false"
                        aria-label="Toggle navigation">
                        <span class="navbar-toggler-icon"></span>
                     </button>
                     <div class="offcanvas offcanvas-start" data-bs-scroll="true" data-bs-backdrop="false" tabindex="-1"
                        id="offcanvasScrolling" aria-labelledby="offcanvasScrollingLabel">
                        <div class="offcanvas-header">
                           <button type="button" class="btn-close mobile_menu_close text-reset"
                              data-bs-dismiss="offcanvas" aria-label="Close"></button>
                        </div>
                        <div class="offcanvas-body">
                           <ul class="navbar-nav main_menu_list m-auto">
                                 <?php foreach ($nav_menus as $nav_item): ?>
                                 <li class="nav-item">
                                    <a class="nav-link" href="<?= htmlspecialchars($nav_item['url'], ENT_QUOTES, 'UTF-8') ?>"><?= htmlspecialchars($nav_item['title'], ENT_QUOTES, 'UTF-8') ?></a>
                                 </li>
                                 <?php endforeach; ?>
                              </ul>

                        </div>
                     </div>
                     <div class="navbar_user me-2">
                        <div class="navbar_user_icon">
                           <ul class="list-unstyled d-flex mb-0">
                               <li class="call-hover-wrapper">
                                 <a href="contact.php" class="call-hover-btn">
                                    <span class="call-text">+91 88129 25014</span>
                                    <span class="call-icon">
                                       <i class="fas fa-phone"></i>
                                    </span>
                                 </a>
                              </li>
                           </ul>
                        </div>
                     </div>
                  </nav>
               </div>
            </div>
            
         </div>
      </header>

      


