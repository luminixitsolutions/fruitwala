<?php include 'header.php'; ?>
<?php
require_once __DIR__ . '/includes/about_us_content.php';
$about = fruitwala_about_us_load($conn);
?>
            <!-- main body start -->
            <main>
                
                <!-- Breadcrumb section start -->
                <section class="breadcrumb_sec_1 position-relative">
                    <div class="breadcrumb_wrap sec_space_mid_small" style="background-image: url(assets/images/breadcrumb/bg.jpg);">
                        <div class="breadcrumb_cont text-center">
                            <div class="breadcrumb_title">
                                <h2 class="text-white"><?= fruitwala_about_us_h($about, 'breadcrumb_title') ?></h2>
                            </div>
                            <ul class="list-unstyled breadcrumb_item d-flex justify-content-center align-items-center text-white">
                                <li><a href="index.php"><i class="fas fa-home active"></i>Home</a></li>
                                <li><i class="fas fa-chevron-right"></i>About</li>
                            </ul>
                        </div>
                    </div>
                </section>
                <!-- Breadcrumb section end -->
                
                <!-- product section-2 start -->
                <section class="product_section_2 sec_space_small" data-aos="fade-up" data-aos-duration="2000">
                    <div class="product_section_2_wrap">
                        <div class="container">
                            <div class="row">
                                <div class="col-lg-6">
                                <div class="product_gallery">
                                    <img src="<?= fruitwala_about_us_h($about, 'hero_image') ?>" alt="<?= fruitwala_about_us_h($about, 'hero_image_alt') ?>" style="height: 500px;">
                                </div>
                                </div>

                                <div class="col-lg-6">
                                <div class="product_section_content about_section_content">

                                    <div class="product_sec_sub_title d-flex align-items-center pb-2">
                                        <i class="far fa-circle"></i>
                                        <i class="far fa-circle"></i>
                                        <i class="far fa-circle"></i>
                                        <span class="ps-1"><?= fruitwala_about_us_h($about, 'badge_text') ?></span>
                                    </div>

                                    <div class="product_section_title text-effect py-2">
                                        <h2><?= fruitwala_about_us_heading_html($about) ?></h2>
                                    </div>

                                    <div class="product_section_subtitle position-relative">
                                        <p class="pb-0"><?= nl2br(fruitwala_about_us_h($about, 'subtitle')) ?></p>
                                    </div>

                                    <div class="product_section_desc">
                                        <p><?= nl2br(fruitwala_about_us_h($about, 'body_text')) ?></p>
                                    </div>

                                    <div class="product_section_btn">
                                        <a href="<?= fruitwala_about_us_h($about, 'btn_url') ?>"><button type="button" class="btn custom_btn load_more_1 rounded-pill px-5 py-3 text-white">
                                            <?= fruitwala_about_us_h($about, 'btn_text') ?> <i class="fas fa-long-arrow-alt-right"></i>
                                        </button></a>
                                    </div>

                                </div>
                                </div>
                            </div>
                        </div>
                    </div>
                    </section>

                <!-- product section-2 end -->
                
                <!-- category section start -->
         <!-- <section class="category_section sec_ptb_100 position-relative overflow-hidden clearfix" data-aos="fade-up"
    data-aos-duration="2000">
    <div class="container">
        <div class="row">
            <div class="category_top_content d-flex justify-content-between">
                <div class="category_top_content_text">
                    <div class="category_sub_title d-flex align-items-center">
                        <i class="far fa-circle"></i>
                        <i class="far fa-circle"></i>
                        <i class="far fa-circle"></i>
                        <span class="ps-2">FRESHLY PREPARED FRUIT BOXES</span>
                    </div>
                    <div class="category_title pb-3">
                        <h2>Our Fruit Box Categories</h2>
                    </div>
                </div>
                <div class="category_top_btn_cont d-flex">
                    <button type="button" class="ss1_left_arrow rounded-pill me-2">
                        <i class="fas fa-arrow-left"></i>
                    </button>
                    <button type="button" class="ss1_right_arrow rounded-pill">
                        <i class="fas fa-arrow-right"></i>
                    </button>
                </div>
            </div>

            <div class="category_slick slideshow1_slider clearfix d-flex justify-content-center align-items-center justify-content-between px-0"
                data-slick='{"dots": false}'>

                <div class="col item_content slider_item text-center" data-aos="fade-up" data-aos-duration="300">
                    <a href="#" target="_blank">
                        <div class="item_image_content overflow-hidden position-relative">
                            <img src="assets/images/5.jpg" alt="image_not_found">
                            <h6 class="item_title position-absolute rounded-pill">Daily Breakfast Boxes</h6>
                        </div>
                    </a>
                </div>

                <div class="col item_content slider_item text-center" data-aos="fade-up" data-aos-duration="600">
                    <a href="#" target="_blank">
                        <div class="item_image_content overflow-hidden position-relative">
                            <img src="assets/images/6.jpg" alt="image_not_found">
                            <h6 class="item_title position-absolute rounded-pill">Premium Fruit Baskets</h6>
                        </div>
                    </a>
                </div>

                <div class="col item_content slider_item text-center" data-aos="fade-up" data-aos-duration="900">
                    <a href="#" target="_blank">
                        <div class="item_image_content overflow-hidden position-relative">
                            <img src="assets/images/7.jpg" alt="image_not_found">
                            <h6 class="item_title position-absolute rounded-pill">Gift Fruit Hampers</h6>
                        </div>
                    </a>
                </div>

                <div class="col item_content slider_item text-center" data-aos="fade-up" data-aos-duration="1200">
                    <a href="#" target="_blank">
                        <div class="item_image_content overflow-hidden position-relative">
                            <img src="assets/images/8.jpg" alt="image_not_found">
                            <h6 class="item_title position-absolute rounded-pill">Seasonal Special Boxes</h6>
                        </div>
                    </a>
                </div>

                <div class="col item_content slider_item text-center" data-aos="fade-up" data-aos-duration="1500">
                    <a href="#" target="_blank">
                        <div class="item_image_content overflow-hidden position-relative">
                            <img src="assets/images/9.jpg" alt="image_not_found">
                            <h6 class="item_title position-absolute rounded-pill">Family Fruit Packs</h6>
                        </div>
                    </a>
                </div>

                <div class="col item_content slider_item text-center" data-aos="fade-up" data-aos-duration="1800">
                    <a href="#" target="_blank">
                        <div class="item_image_content overflow-hidden position-relative">
                            <img src="assets/images/10.jpg" alt="image_not_found">
                            <h6 class="item_title position-absolute rounded-pill">Office Fruit Deliveries</h6>
                        </div>
                    </a>
                </div>

                <div class="col item_content slider_item text-center" data-aos="fade-up" data-aos-duration="2100">
                    <a href="#" target="_blank">
                        <div class="item_image_content overflow-hidden position-relative">
                            <img src="assets/images/11.jpg" alt="image_not_found">
                            <h6 class="item_title position-absolute rounded-pill">Custom Fruit Arrangements</h6>
                        </div>
                    </a>
                </div>

            </div>
        </div>
    </div>

    <img class="category_left_thumb position-absolute" src="assets/images/shapes/shape3.png" alt="image_not_found">
</section> -->

         <!-- category section end -->
                
                <!-- product_8 section start -->
                <section class="product8_sec sec_ptb_100" data-aos="fade-up" data-aos-duration="2000">
   <div class="product8_sec_wrap">
      <div class="container-sm">
         <div class="team_top_content text-center">
            <div class="offer_sub_title d-flex align-items-center justify-content-center pb-2">
               <i class="far fa-circle"></i>
               <i class="far fa-circle"></i>
               <i class="far fa-circle"></i>
               <span class="text-uppercase px-3">WHY FRUITWALA BREAKFAST</span>
               <i class="far fa-circle"></i>
               <i class="far fa-circle"></i>
               <i class="far fa-circle"></i>
            </div>
            <div class="product_top_title text-center py-1">
               <h2>Why Choose Fruitwala</h2>
            </div>

            <div class="d-flex justify-content-center align-items-center">
               <ul class="product_tabnav_4 nav nav-pills my-5" role="tablist">
                  <li class="nav-item">
                     <button class="nav-link first_btn text-uppercase" data-bs-toggle="pill" data-bs-target="#pills-natural">
                        <img src="assets/images/services/service15.png" alt=""> 100% Fresh Fruits
                     </button>
                  </li>
                  <li class="nav-item">
                     <button class="nav-link active text-uppercase" data-bs-toggle="pill" data-bs-target="#pills-handmade">
                        <img src="assets/images/services/service15.png" alt=""> Hygienically Packed
                     </button>
                  </li>
                  <li class="nav-item">
                     <button class="nav-link last_btn text-uppercase" data-bs-toggle="pill" data-bs-target="#pills-curated">
                        <img src="assets/images/services/service17.png" alt=""> Curated Fruit Boxes
                     </button>
                  </li>
               </ul>
            </div>
         </div>

         <div class="tab-content">

            <!-- TAB 1 -->
            <div class="tab-pane fade" id="pills-natural">
               <div class="row mt-5">
                  <div class="col-lg-6">
                     <img src="assets/images/21.png" alt="">
                  </div>
                  <div class="col-lg-6">
                     <h6 class="text-white text-uppercase">Farm Fresh Quality</h6>
                     <h2>Fresh Seasonal Fruits For A Healthy Lifestyle</h2>
                     <p>We carefully source high-quality seasonal fruits to ensure natural taste, freshness, and nutrition in every fruit box delivered.</p>

                     <p>✔ Daily Fresh Fruit Selection</p>
                     <p>✔ Premium Seasonal Varieties</p>
                     <p>✔ Hygienically Cleaned & Packed</p>
                     <p>✔ Rich in Vitamins & Immunity Boosting</p>
                     <p>✔ Perfect for Breakfast & Healthy Snacking</p>
                     <p>✔ Safe Packaging for Maximum Freshness</p>
                     <p>✔ Beautifully Packed — Great for Gifting</p>

                     <a href="contact.php">
                        <button class="btn custom_btn rounded-pill px-5 py-3 text-white">
                              Contact Us Now <i class="fas fa-long-arrow-alt-right"></i>
                        </button>
                     </a>
                  </div>

               </div>
            </div>

            <!-- TAB 2 -->
            <div class="tab-pane fade show active" id="pills-handmade">
               <div class="row mt-5">
                  <div class="col-lg-6">
                     <img src="assets/images/19.png" alt="">
                  </div>
                  <div class="col-lg-6">
                     <h6 class="text-white text-uppercase">Clean & Safe Preparation</h6>
                     <h2>Hygienically Packed Fruits You Can Trust</h2>
                     <p>Every fruit is cleaned, handled with care, and packed in a hygienic environment to maintain safety, quality, and freshness.</p>

                     <p>✔ Safe & Sanitized Handling Process</p>
                     <p>✔ Washed with Clean, Food-Safe Water</p>
                     <p>✔ Packed in a Hygienic Environment</p>
                     <p>✔ Gloves & Safety Gear Used by Staff</p>
                     <p>✔ No Direct Hand Contact After Cleaning</p>
                     <p>✔ Secure Packaging for Safe Delivery</p>
                     <p>✔ Freshness-Sealed Boxes</p>

                     <a href="contact.php">
                        <button class="btn custom_btn rounded-pill px-5 py-3 text-white">
                              Contact Us Now <i class="fas fa-long-arrow-alt-right"></i>
                        </button>
                     </a>
                  </div>

               </div>
            </div>

            <!-- TAB 3 -->
            <div class="tab-pane fade" id="pills-curated">
               <div class="row mt-5">
                  <div class="col-lg-6">
                     <img src="assets/images/20.png" alt="">
                  </div>
                  <div class="col-lg-6">
                     <h6 class="text-white text-uppercase">Specially Designed Boxes</h6>
                     <h2>Beautifully Curated Fruit Boxes For Every Occasion</h2>
                     <p>From daily breakfast packs to premium fruit gift baskets, we create thoughtfully arranged fruit combinations for health and happiness.</p>

                     <p>✔ Elegant Fruit Gift Hampers</p>
                     <p>✔ Office & Family Fruit Packs</p>
                     <p>✔ Custom Fruit Box Combinations</p>
                     <p>✔ Seasonal Premium Fruit Collections</p>
                     <p>✔ Perfect for Festivals & Celebrations</p>
                     <p>✔ Healthy Gifting Alternative</p>
                     <p>✔ Corporate Fruit Gifting Solutions</p>

                     <a href="contact.php">
                        <button class="btn custom_btn rounded-pill px-5 py-3 text-white">
                              Contact Us Now <i class="fas fa-long-arrow-alt-right"></i>
                        </button>
                     </a>
                  </div>

               </div>
            </div>

         </div>
      </div>
   </div>
</section>

                <!-- product_8 section end -->
                
                <!-- product video section start -->
                
            <!-- product video section end -->
            
            <!-- brand section start -->
            <!-- <section class="brand_section mb_50 sec_space_xxs_40" style="background-color: #f9f9f9;" data-aos="fade-up"
            data-aos-duration="2000">
                <div class="container">
                    <div class="brand_thumb text-center">
                        <img src="assets/images/brands/brand1.png" alt="image_not_found">
                    </div>
                </div>
            </section> -->
            <!-- brand section end -->
            
            <!-- team section start -->
<!--         <section class="team_section sec_space_xxs_50 position-relative" data-aos="fade-up" data-aos-duration="2000">-->
<!--   <div class="team_section_content">-->
<!--      <div class="team_top_content">-->
<!--         <div class="offer_sub_title d-flex align-items-center justify-content-center pb-1">-->
<!--            <i class="far fa-circle"></i>-->
<!--            <i class="far fa-circle"></i>-->
<!--            <i class="far fa-circle"></i>-->
<!--            <span class="text-uppercase px-2">MEET THE FRUITWALA TEAM</span>-->
<!--            <i class="far fa-circle"></i>-->
<!--            <i class="far fa-circle"></i>-->
<!--            <i class="far fa-circle"></i>-->
<!--         </div>-->
<!--         <div class="team_top_title text-center pb-4">-->
<!--            <h2>The People Behind Your Fresh Fruit Boxes</h2>-->
<!--         </div>-->
<!--      </div>-->

<!--      <div class="team_inner_content">-->
<!--         <div class="container">-->
<!--            <div class="card-group justify-content-center align-items-center">-->
<!--               <div class="team_content_wrap position-relative col-md-10 col-lg-8 col-xl-12 m-auto">-->
<!--                  <div class="row g-4">-->

                     <!-- Team Member 1 -->
<!--                     <div class="col-sm-6 col-md-6 col-xl-3">-->
<!--                        <div class="card team_content text-center" data-aos="fade-right" data-aos-duration="2000">-->
<!--                           <img class="rounded-pill" src="assets/images/testimonials/2.jpg" alt="team">-->
<!--                           <div class="card-body team_author_content">-->
<!--                              <h5 class="card-title team_author_title">Rohit Jain</h5>-->
<!--                              <div class="card-text team_author_post mb-2">Founder & Quality Head</div>-->
<!--                              <div class="team_author_social_link d-flex justify-content-center justify-content-around align-items-center">-->
<!--                                 <a class="social_face" href="#"><i class="fab fa-facebook-square"></i></a>-->
<!--                                 <a class="social_linked" href="#"><i class="fab fa-linkedin"></i></a>-->
<!--                              </div>-->
<!--                           </div>-->
<!--                        </div>-->
<!--                     </div>-->

                     <!-- Team Member 2 -->
<!--                     <div class="col-sm-6 col-md-6 col-xl-3">-->
<!--                        <div class="card team_content text-center" data-aos="fade-right" data-aos-duration="1000">-->
<!--                           <img class="rounded-pill" src="assets/images/testimonials/1.jpg" alt="team">-->
<!--                           <div class="card-body team_author_content">-->
<!--                              <h5 class="card-title team_author_title">Priya Shah</h5>-->
<!--                              <div class="card-text team_author_post mb-2">Fruit Sourcing Expert</div>-->
<!--                              <div class="team_author_social_link d-flex justify-content-center justify-content-around align-items-center">-->
<!--                                 <a class="social_face" href="#"><i class="fab fa-facebook-square"></i></a>-->
<!--                                 <a class="social_linked" href="#"><i class="fab fa-linkedin"></i></a>-->
<!--                              </div>-->
<!--                           </div>-->
<!--                        </div>-->
<!--                     </div>-->

                     <!-- Team Member 3 -->
<!--                     <div class="col-sm-6 col-md-6 col-xl-3">-->
<!--                        <div class="card team_content text-center" data-aos="fade-left" data-aos-duration="1000">-->
<!--                           <img class="rounded-pill" src="assets/images/testimonials/3.jpg" alt="team">-->
<!--                           <div class="card-body team_author_content">-->
<!--                              <h5 class="card-title team_author_title">Amit Verma</h5>-->
<!--                              <div class="card-text team_author_post mb-2">Packaging & Hygiene Lead</div>-->
<!--                              <div class="team_author_social_link d-flex justify-content-center justify-content-around align-items-center">-->
<!--                                 <a class="social_face" href="#"><i class="fab fa-facebook-square"></i></a>-->
<!--                                 <a class="social_linked" href="#"><i class="fab fa-linkedin"></i></a>-->
<!--                              </div>-->
<!--                           </div>-->
<!--                        </div>-->
<!--                     </div>-->

                     <!-- Team Member 4 -->
<!--                     <div class="col-sm-6 col-md-6 col-xl-3">-->
<!--                        <div class="card team_content text-center" data-aos="fade-left" data-aos-duration="2000">-->
<!--                           <img class="rounded-pill" src="assets/images/testimonials/5.jpg" alt="team">-->
<!--                           <div class="card-body team_author_content">-->
<!--                              <h5 class="card-title team_author_title">Sneha Kulkarni</h5>-->
<!--                              <div class="card-text team_author_post mb-2">Customer Happiness Manager</div>-->
<!--                              <div class="team_author_social_link d-flex justify-content-center justify-content-around align-items-center">-->
<!--                                 <a class="social_face" href="#"><i class="fab fa-facebook-square"></i></a>-->
<!--                                 <a class="social_linked" href="#"><i class="fab fa-linkedin"></i></a>-->
<!--                              </div>-->
<!--                           </div>-->
<!--                        </div>-->
<!--                     </div>-->

<!--                  </div>-->
<!--               </div>-->
<!--            </div>-->
<!--         </div>-->
<!--      </div>-->
<!--   </div>-->

<!--   <img class="team_left_thumb position-absolute" src="assets/images/shapes/shape11.png" alt="image_not_found">-->
<!--   <img class="team_right_thumb position-absolute" src="assets/images/shapes/shape12.png" alt="image_not_found">-->
<!--</section>-->

         <!-- team section start -->
            
            <!-- service section start -->
            <section class="service_setion sec_space_xxs_50" data-aos="fade-up" data-aos-duration="2000">
   <div class="service_content_wrap">
      <div class="container">
         <div class="row">

            <div class="col-6 col-md-4 col-xl-3">
               <div class="service_inner_content d-flex justify-content-center align-items-center" data-aos="fade-up" data-aos-duration="500">
                  <div class="service_content_icon rounded-pill me-2">
                     <i class="fas fa-shipping-fast"></i>
                  </div>
                  <div class="service_content_text">
                     <div class="service_content_title">
                        <h6 class="text-uppercase">Fast Home Delivery</h6>
                     </div>
                     <div class="service_content_sub_title">
                        <span>Fresh fruits delivered to your doorstep</span>
                     </div>
                  </div>
               </div>
            </div>

            <div class="col-6 col-md-4 col-xl-3">
               <div class="service_inner_content d-flex justify-content-center align-items-center" data-aos="fade-up" data-aos-duration="1000">
                  <div class="service_content_icon rounded-pill me-2">
                     <i class="fas fa-apple-alt"></i>
                  </div>
                  <div class="service_content_text">
                     <div class="service_content_title">
                        <h6 class="text-uppercase">Premium Quality Fruits</h6>
                     </div>
                     <div class="service_content_sub_title">
                        <span>Handpicked & carefully packed</span>
                     </div>
                  </div>
               </div>
            </div>

            <div class="col-6 col-md-4 col-xl-3">
               <div class="service_inner_content d-flex justify-content-center align-items-center" data-aos="fade-up" data-aos-duration="1500">
                  <div class="service_content_icon rounded-pill me-2">
                     <i class="fas fa-box-open"></i>
                  </div>
                  <div class="service_content_text">
                     <div class="service_content_title">
                        <h6 class="text-uppercase">Custom Fruit Baskets</h6>
                     </div>
                     <div class="service_content_sub_title">
                        <span>Perfect for gifts & special occasions</span>
                     </div>
                  </div>
               </div>
            </div>

            <div class="col-6 col-md-4 col-xl-3">
               <div class="service_inner_content d-flex justify-content-center align-items-center" data-aos="fade-up" data-aos-duration="2000">
                  <div class="service_content_icon rounded-pill me-2">
                     <i class="fas fa-headset"></i>
                  </div>
                  <div class="service_content_text">
                     <div class="service_content_title">
                        <h6 class="text-uppercase">Customer Support</h6>
                     </div>
                     <div class="service_content_sub_title">
                        <span>We’re here to help you anytime</span>
                     </div>
                  </div>
               </div>
            </div>

         </div>
      </div>
   </div>
</section>
            <!-- service section end -->
            
            <!-- instagram section start -->
         <section class="instagram_section instagram_style_1 sec_space_xs_70" data-aos="fade-up"
   data-aos-duration="2000">
   <div class="container">
      <h2 class="instagram_title pb-5 text-center">Follow Fruitwala Breakfast on Instagram</h2>
      <ul class="zoom-gallery instagram_image_content ul_li">

         <li>
            <a class="popup_image" href="assets/images/5.jpg">
               <img src="assets/images/5.jpg" alt="Fruit Basket">
               <i class="fab fa-instagram"></i>
               <span>@fruitwala_breakfast</span>
            </a>
         </li>

         <li>
            <a class="popup_image" href="assets/images/7.jpg">
               <img src="assets/images/7.jpg" alt="Fresh Fruit Box">
               <i class="fab fa-instagram"></i>
               <span>@fruitwala_breakfast</span>
            </a>
         </li>

         <li>
            <a class="popup_image" href="assets/images/8.jpg">
               <img src="assets/images/8.jpg" alt="Gift Fruit Basket">
               <i class="fab fa-instagram"></i>
               <span>@fruitwala_breakfast</span>
            </a>
         </li>

         <li>
            <a class="popup_image" href="assets/images/9.jpg">
               <img src="assets/images/9.jpg" alt="Premium Fruit Hamper">
               <i class="fab fa-instagram"></i>
               <span>@fruitwala_breakfast</span>
            </a>
         </li>

         <li>
            <a class="popup_image" href="assets/images/10.jpg">
               <img src="assets/images/10.jpg" alt="Healthy Fruit Gift">
               <i class="fab fa-instagram"></i>
               <span>@fruitwala_breakfast</span>
            </a>
         </li>

      </ul>
   </div>
</section>

         <!-- instagram section end -->
            
        </main>
        <!-- main body end -->
        
        <?php include 'footer.php'; ?>