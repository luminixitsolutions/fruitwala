<?php include 'header.php'; ?>
<?php
require_once __DIR__ . '/includes/home_content.php';
$home = fruitwala_home_load($conn);
$heroSlides = fruitwala_home_hero_slides($conn);
$homeReels = fruitwala_home_reels($conn);
$saleBanners = fruitwala_home_sale_banners($conn);
$offerBanners = fruitwala_home_offer_banners($conn);
$homeServices = fruitwala_home_services($conn);
$igTiles = fruitwala_home_instagram_tiles($conn);
$homeTestimonials = fruitwala_home_testimonials($conn);
$galleryItems = fruitwala_home_gallery_items($conn);
$galleryStripSidebarRows = fruitwala_home_gallery_strip_sidebar_rows($conn);
?>

<!-- //////////////////////////////////////// HEADER END //////////////////////////////// -->

      <!-- main body start -->
      <main>

      <!-- banner4 section start -->
            <section class="banner4_section position-relative sec_space_large">
    <div class="banner4_section_wrap">
        <div class="container">
            <div class="row slide_content slideshow6_slider mx-3" data-slick='{"dots": false}'>
                <?php
                $bannerGalleryItems = $galleryItems;
                if ($bannerGalleryItems === []) {
                    foreach ($heroSlides as $slide) {
                        $bannerGalleryItems[] = [
                            'title' => (string) ($slide['kicker'] ?? ''),
                            'image' => (string) ($slide['image'] ?? ''),
                        ];
                    }
                }
                ?>
                <?php foreach ($bannerGalleryItems as $item): ?>
                <div class="slide_item_content d-flex justify-content-center align-items-center">
                    <div class="col-12">
                        <div class="banner10_img img_moving_anim1 text-center">
                            <img src="<?= htmlspecialchars((string) ($item['image'] ?? ''), ENT_QUOTES, 'UTF-8') ?>" alt="<?= htmlspecialchars((string) ($item['title'] ?? 'Gallery image'), ENT_QUOTES, 'UTF-8') ?>">
                        </div>
                    </div>
                </div>
                <?php endforeach; ?>
            </div>
        </div>

        <div class="banner4_slide_arrow_cont">
            <div class="banner4_arrow1 d-flex align-items-center position-absolute ss6_left_arrow">
                <i class="fas fa-arrow-left"></i>
                <div class="slide_arrow_left shadow d-flex justify-content-center align-items-center">
                    <img src="assets/images/1.png" alt="image_not_found">
                </div>
            </div>

            <div class="banner4_arrow2 d-flex align-items-center position-absolute ss6_right_arrow">
                <a class="slide_arrow_right shadow d-flex justify-content-center align-items-center" href="#!">
                    <img src="assets/images/2.png" alt="image_not_found">
                </a>
                <i class="fas fa-arrow-right"></i>
            </div>
        </div>
    </div>

    <div class="banner_left_img position-absolute">
        <img src="assets/images/shapes/shape35.png" alt="image_not_found">
    </div>
</section>

            <!-- banner section end -->


         <section class="featured_sec position-relative sec_inner_bottom_80">
    <div class="container">
        <div class="row g-4">

           <?php foreach ($homeReels as $reel): ?>
            <div class="col-12 col-sm-6 col-lg-3">
               <div class="reel-card" onclick="openVideo(<?= json_encode($reel['video'] ?? '', JSON_HEX_TAG | JSON_HEX_APOS | JSON_HEX_QUOT) ?>)">
                  <img src="<?= fruitwala_home_row_h($reel, 'cover') ?>" class="img-fluid" alt="<?= fruitwala_home_row_h($reel, 'alt') ?>">
                  <div class="reel-play"><i class="fas fa-play"></i></div>
               </div>
            </div>
            <?php endforeach; ?>

        </div>
    </div>
</section>


         <!-- quality section start -->
         <section class="quality_section position-relative" data-aos="fade-up" data-aos-duration="2000">
   <div class="quality_section_wrap sec_ptb_100"
      style="background-image: url('<?= fruitwala_home_h($home, 'quality_wrap', 'bg_image') ?>')">
      <div class="container">

         <!-- Top Heading -->
         <div class="quality_top_content text-center">
            <div class="quality_sub_title d-flex justify-content-center align-items-center pb-2">
               <i class="far fa-circle"></i>
               <i class="far fa-circle"></i>
               <i class="far fa-circle"></i>
               <span class="px-2"><?= fruitwala_home_h($home, 'quality_header', 'badge') ?></span>
               <i class="far fa-circle"></i>
               <i class="far fa-circle"></i>
               <i class="far fa-circle"></i>
            </div>
            <div class="quality_top_title">
               <h2><?= fruitwala_home_h($home, 'quality_header', 'heading') ?></h2>
            </div>
         </div>

         <div class="quality_inner_content">
            <div class="row justify-content-center align-items-end">

               <!-- Left Side Points -->
               <div class="col-lg-4">

                  <div class="quality_content d-flex justify-content-center align-items-start text-end pe-4" data-aos="fade-right" data-aos-duration="800">
                     <div class="quality_text">
                        <div class="quality_title">
                           <h4><?= fruitwala_home_title_html($home, 'quality_l1', 'title') ?></h4>
                        </div>
                        <div class="quality_desc">
                           <p><?= nl2br(fruitwala_home_h($home, 'quality_l1', 'desc')) ?></p>
                        </div>
                     </div>
                     <div class="quality_img bg-white ms-4">
                        <img src="<?= fruitwala_home_h($home, 'quality_l1', 'image') ?>" alt="<?= fruitwala_home_img_alt($home, 'quality_l1', 'title') ?>">
                     </div>
                  </div>

                  <div class="quality_content d-flex justify-content-center align-items-start text-end my-4" data-aos="fade-right" data-aos-duration="1000">
                     <div class="quality_text">
                        <div class="quality_title">
                           <h4><?= fruitwala_home_title_html($home, 'quality_l2', 'title') ?></h4>
                        </div>
                        <div class="quality_desc">
                           <p><?= nl2br(fruitwala_home_h($home, 'quality_l2', 'desc')) ?></p>
                        </div>
                     </div>
                     <div class="quality_img bg-white ms-4">
                        <img src="<?= fruitwala_home_h($home, 'quality_l2', 'image') ?>" alt="<?= fruitwala_home_img_alt($home, 'quality_l2', 'title') ?>">
                     </div>
                  </div>

                  <div class="quality_content d-flex justify-content-center align-items-start text-end pe-4" data-aos="fade-right" data-aos-duration="1200">
                     <div class="quality_text">
                        <div class="quality_title">
                           <h4><?= fruitwala_home_title_html($home, 'quality_l3', 'title') ?></h4>
                        </div>
                        <div class="quality_desc">
                           <p><?= nl2br(fruitwala_home_h($home, 'quality_l3', 'desc')) ?></p>
                        </div>
                     </div>
                     <div class="quality_img bg-white ms-4">
                        <img src="<?= fruitwala_home_h($home, 'quality_l3', 'image') ?>" alt="<?= fruitwala_home_img_alt($home, 'quality_l3', 'title') ?>">
                     </div>
                  </div>

               </div>

               <!-- Center Image -->
               <!-- Center Video -->
                  <div class="col-lg-4">
                     <div class="quality_middle_gallery img_moving_anim1 text-center">

                        <video id="qualityVideo"
                              muted
                              loop
                              playsinline
                              preload="metadata"
                              style="width:100%; max-height:500px; border-radius:20px; object-fit:cover;">
                           <source src="<?= fruitwala_home_h($home, 'quality_center', 'video_src') ?>" type="video/mp4">
                        </video>

                     </div>
                  </div>


               <!-- Right Side Points -->
               <div class="col-lg-4">

                  <div class="quality_content d-flex justify-content-center align-items-start ps-4" data-aos="fade-left" data-aos-duration="800">
                     <div class="quality_img bg-white me-4">
                        <img src="<?= fruitwala_home_h($home, 'quality_r1', 'image') ?>" alt="<?= fruitwala_home_img_alt($home, 'quality_r1', 'title') ?>">
                     </div>
                     <div class="quality_text">
                        <div class="quality_title">
                           <h4><?= fruitwala_home_title_html($home, 'quality_r1', 'title') ?></h4>
                        </div>
                        <div class="quality_desc">
                           <p><?= nl2br(fruitwala_home_h($home, 'quality_r1', 'desc')) ?></p>
                        </div>
                     </div>
                  </div>

                  <div class="quality_content d-flex justify-content-center align-items-start my-4" data-aos="fade-left" data-aos-duration="1000">
                     <div class="quality_img bg-white me-4">
                        <img src="<?= fruitwala_home_h($home, 'quality_r2', 'image') ?>" alt="<?= fruitwala_home_img_alt($home, 'quality_r2', 'title') ?>">
                     </div>
                     <div class="quality_text">
                        <div class="quality_title">
                           <h4><?= fruitwala_home_title_html($home, 'quality_r2', 'title') ?></h4>
                        </div>
                        <div class="quality_desc">
                           <p><?= nl2br(fruitwala_home_h($home, 'quality_r2', 'desc')) ?></p>
                        </div>
                     </div>
                  </div>

                  <div class="quality_content d-flex justify-content-center align-items-start ps-4" data-aos="fade-left" data-aos-duration="1200">
                     <div class="quality_img bg-white me-4">
                        <img src="<?= fruitwala_home_h($home, 'quality_r3', 'image') ?>" alt="<?= fruitwala_home_img_alt($home, 'quality_r3', 'title') ?>">
                     </div>
                     <div class="quality_text">
                        <div class="quality_title">
                           <h4><?= fruitwala_home_title_html($home, 'quality_r3', 'title') ?></h4>
                        </div>
                        <div class="quality_desc">
                           <p><?= nl2br(fruitwala_home_h($home, 'quality_r3', 'desc')) ?></p>
                        </div>
                     </div>
                  </div>

               </div>

            </div>
         </div>
      </div>
   </div>
</section>

         <!-- quality section end -->



            <!-- sale-3 section start -->
            <section class="sale3_sec sec_inner_bottom_80" data-aos="fade-up" data-aos-duration="2000">
                <div class="sale3_sec_wrap">
                    <div class="container">
                        <div class="row gx-3">
                            <?php foreach ($offerBanners as $idx => $offerBanner): ?>
                            <div class="col-lg-6">
                                <a href="<?= fruitwala_home_row_h($offerBanner, 'link') ?>" class="d-block">
                                    <div class="<?= $idx % 2 === 0 ? 'sale3_content d-flex flex-column justify-content-center align-items-center overflow-hidden' : 'sale3_content2' ?> sec_space_xs_70"
                                        style="background-image: url('<?= fruitwala_home_row_h($offerBanner, 'image') ?>'); height: 300px;"
                                        data-aos="<?= $idx % 2 === 0 ? 'fade-right' : 'fade-left' ?>" data-aos-duration="1000">
                                    </div>
                                </a>
                            </div>
                            <?php endforeach; ?>
                        </div>
                    </div>
                </div>
            </section>
            <!-- sale-3 section end -->



         <!-- sale section start -->
         <section class="sale_section position-relative" data-aos="fade-up" data-aos-duration="2000">
    <div class="sale_content">
        <div class="container">
            <div class="row">

                <!-- Big Slider Left -->
                <div class="col-lg-5 overflow-hidden position-relative">
                    <div class="sale_slider_content slideshow2_slider position-relative overflow-hidden"
                        data-slick='{"dots": false}'>

                        <?php if ($galleryItems !== []): ?>
                            <?php foreach ($galleryItems as $galleryItem): ?>
                                <a href="javascript:void(0);">
                                    <div class="sale_item_content position-relative" data-aos="fade-up" data-aos-duration="1000">
                                        <div class="sale_item position-relative">
                                            <img src="<?= htmlspecialchars((string) ($galleryItem['image'] ?? ''), ENT_QUOTES, 'UTF-8') ?>" alt="<?= htmlspecialchars((string) ($galleryItem['title'] ?? 'Gallery image'), ENT_QUOTES, 'UTF-8') ?>">
                                        </div>
                                    </div>
                                </a>
                            <?php endforeach; ?>
                        <?php else: ?>
                            <a href="#!">
                                <div class="sale_item_content position-relative" data-aos="fade-up" data-aos-duration="1000">
                                    <div class="sale_item position-relative">
                                        <img src="assets/images/18.webp" alt="image_not_found">
                                    </div>
                                </div>
                            </a>
                            <a href="javascript:void(0);">
                                <div class="sale_item_content position-relative">
                                    <div class="sale_item position-relative">
                                        <img src="assets/images/16.jpg" alt="image_not_found">
                                    </div>
                                </div>
                            </a>
                        <?php endif; ?>

                    </div>

                    <!-- Slider Arrows -->
                    <div class="sale_item_arrow d-flex position-absolute">
                        <button type="button" class="ss2_left_arrow me-2">
                            <i class="fas fa-arrow-left rounded-pill"></i>
                        </button>
                        <button type="button" class="ss2_right_arrow">
                            <i class="fas fa-arrow-right rounded-pill"></i>
                        </button>
                    </div>
                </div>

                <!-- Right Side Small Banners -->
                <div class="col-lg-7">
                    <div class="row">

                        <?php
                        $saleDur = 1500;
                        foreach ($saleBanners as $banner):
                            ?>
                        <div class="col-sm-6 overflow-hidden">
                            <a href="<?= fruitwala_home_row_h($banner, 'link') ?>" class="d-block">
                                <div class="sale_item_content position-relative" data-aos="fade-up"
                                    data-aos-duration="<?= (int) $saleDur ?>">
                                    <div class="sale_item position-relative">
                                        <img src="<?= fruitwala_home_row_h($banner, 'image') ?>" alt="sale banner">
                                        <div class="sale_sm_title position-absolute">
                                            <h3>
                                                <?= fruitwala_home_sale_banner_title_html($banner) ?>
                                            </h3>
                                            <span class="text-white"><?= fruitwala_home_row_h($banner, 'subtitle') ?></span>
                                        </div>
                                    </div>
                                </div>
                            </a>
                        </div>
                            <?php
                            $saleDur += 500;
                        endforeach;
                        ?>

                    </div>
                </div>

            </div>
        </div>
    </div>

    <!-- Side Shape -->
    <img class="sale_right_thumb position-absolute" src="assets/images/shapes/shape4.png" alt="image_not_found">
</section>

         <!-- sale section start -->

       




         <!-- product category section start -->
         <section class="product_ctg_section sec_space_xs_70">
   <div class="container">
      <div class="col">
         <div class="product_ctg_content_wrap position-relative prl_60 sec_ptb_100"
            style="background-image: url(assets/images/backgrounds/bg3.png)">

            <div class="product_ctg_content">

               <div class="product_ctg_sub_title d-flex align-items-center pb-2">
                  <i class="far fa-circle"></i>
                  <i class="far fa-circle"></i>
                  <i class="far fa-circle"></i>
                  <span class="text-uppercase ps-2"><?= fruitwala_home_h($home, 'product_ctg', 'badge') ?></span>
               </div>

               <div class="product_ctg_title py-1">
                  <h2><?= fruitwala_home_title_html($home, 'product_ctg', 'title') ?></h2>
               </div>

               <div class="product_ctg_desc">
                  <p><?= nl2br(fruitwala_home_h($home, 'product_ctg', 'desc')) ?></p>
               </div>

               <!-- Point 1 -->
               <div class="product_ctg_items d-flex align-items-start">
                  <div class="product_ctg_items_icon pe-3">
                     <img src="assets/images/product/product16.png" alt="product16">
                  </div>
                  <div class="product_ctg_items_text">
                     <div class="product_ctg_items_title">
                        <h5><?= fruitwala_home_h($home, 'product_ctg', 'p1_title') ?></h5>
                     </div>
                     <div class="product_ctg_items_desc">
                        <p><?= nl2br(fruitwala_home_h($home, 'product_ctg', 'p1_desc')) ?></p>
                     </div>
                  </div>
               </div>

               <!-- Point 2 -->
               <div class="product_ctg_items d-flex align-items-start">
                  <div class="product_ctg_items_icon pe-3">
                     <img src="assets/images/product/product17.png" alt="product17">
                  </div>
                  <div class="product_ctg_items_text">
                     <div class="product_ctg_items_title">
                        <h5><?= fruitwala_home_h($home, 'product_ctg', 'p2_title') ?></h5>
                     </div>
                     <div class="product_ctg_items_desc">
                        <p><?= nl2br(fruitwala_home_h($home, 'product_ctg', 'p2_desc')) ?></p>
                     </div>
                  </div>
               </div>

               <div class="product_ctg_btn load_more_1">
                  <a href="<?= fruitwala_home_h($home, 'product_ctg', 'btn_url') ?>"><button type="button" class="btn custom_btn rounded-pill px-4 text-white">
                     <?= fruitwala_home_h($home, 'product_ctg', 'btn_text') ?> <i class="fas fa-long-arrow-alt-right"></i></button></a>
               </div>

            </div>

            <!-- Side Images (kept same) -->
            <img class="product_ctg_right_thumb position-absolute" src="assets/images/product/product14.png" alt="image_not_found">
            <img class="product_ctg_left_thumb position-absolute" src="assets/images/product/product13.png" alt="image_not_found">

         </div>
      </div>
   </div>
</section>

         <!-- product category section end -->

         <!-- offer section start -->
         
         <!-- offer section end -->

         <!-- testimonial section start -->
         <section id="testimonials" class="testimonial_section sec_bottom_space_70 position-relative" data-aos="fade-up"
   data-aos-duration="2000">
   <div class="testimonial_sec_content sec_space_xxs_50"
      style="background-image: url(assets/images/testimonials/testimonial1.png)">
      <div class="container">
         <div class="row">
            <div class="col">
               <div class="slider_item_content slideshow3_slider" data-slick='{"dots": false}'>

                  <?php foreach ($homeTestimonials as $tm): ?>
                  <div class="slider_item">
                     <div class="testimonial_layout_1 d-flex justify-content-center align-items-center">
                        <div class="testimonial_author">
                           <img src="<?= htmlspecialchars((string) ($tm['image'] ?? ''), ENT_QUOTES, 'UTF-8') ?>" alt="image_not_found">
                        </div>
                        <div class="testimonial_text">
                           <div class="testimonial_comment_text">
                              <h3><?= htmlspecialchars((string) ($tm['heading'] ?? ''), ENT_QUOTES, 'UTF-8') ?></h3>
                           </div>
                           <div class="testimonial_comment py-3">
                              <p><?= nl2br(htmlspecialchars((string) ($tm['body'] ?? ''), ENT_QUOTES, 'UTF-8')) ?></p>
                           </div>
                           <div class="testimonial_author_title">
                              <h6><?= htmlspecialchars((string) ($tm['author'] ?? ''), ENT_QUOTES, 'UTF-8') ?></h6>
                           </div>
                        </div>
                     </div>
                  </div>
                  <?php endforeach; ?>

               </div>
            </div>
         </div>
      </div>
   </div>
</section>

         <!-- testimonial section end -->

       
         <!-- team section start -->

         <!-- gallery section start -->
         <section id="gallery" class="gallery_section sec_space_xxs_50 position-relative" data-aos="fade-up"
   data-aos-duration="2000">
   <div class="gallery_content_wrap inner_sec_sm"
      style="background-image: url('<?= fruitwala_home_h($home, 'gallery_wrap', 'bg_image') ?>')">
      <div class="container">
         <div class="row align-items-center">

            <!-- Left Content -->
            <div class="col-lg-4">
               <div class="gallery_lft_content">
                  <div class="gallery_lft_sub_title d-flex align-items-center pb-2">
                     <i class="far fa-circle"></i>
                     <i class="far fa-circle"></i>
                     <i class="far fa-circle"></i>
                     <span class="text-uppercase ps-2"><?= fruitwala_home_h($home, 'gallery_left', 'badge') ?></span>
                  </div>
                  <div class="gallery_lft_title py-2">
                     <h2><?= fruitwala_home_h($home, 'gallery_left', 'title') ?></h2>
                  </div>
                  <div class="gallery_lft_desc py-2">
                     <p><?= nl2br(fruitwala_home_h($home, 'gallery_left', 'desc')) ?></p>
                  </div>
                  <div class="gallery_lft_btn">
                     <a href="<?= fruitwala_home_h($home, 'gallery_left', 'btn_url') ?>"><button type="button" class="btn custom_btn rounded-pill px-4 text-white">
                        <?= fruitwala_home_h($home, 'gallery_left', 'btn_text') ?> <i class="fas fa-long-arrow-alt-right"></i>
                     </button></a>
                  </div>
               </div>
            </div>

            <!-- Middle Featured Post -->
            <div class="col-md-6 col-lg-4">
               <div class="gallery_mid_content overflow-hidden bg-white shadow-lg">
                  <div class="gallery_mid_thumb">
                     <img src="<?= fruitwala_home_h($home, 'gallery_mid', 'image') ?>" alt="image_not_found">
                  </div>
                  <div class="gallery_mid_inner_content px-5 py-4">
                     <a href="<?= fruitwala_home_h($home, 'gallery_mid', 'link') ?>">
                        <h2><?= fruitwala_home_h($home, 'gallery_mid', 'title') ?></h2>
                     </a>
                     <div class="gallery_mid_author_content py-2 d-flex justify-content-between">
                        <div class="gallery_mid_author_title">
                           <span><i class="far fa-user pe-1"></i> <?= fruitwala_home_h($home, 'gallery_mid', 'author') ?></span>
                        </div>
                        <div class="gallery_mid_author_time">
                           <span><i class="far fa-clock pe-1"></i> <?= fruitwala_home_h($home, 'gallery_mid', 'time_label') ?></span>
                        </div>
                     </div>
                     <div class="gallery_mid_desc">
                        <p><?= nl2br(fruitwala_home_h($home, 'gallery_mid', 'desc')) ?></p>
                     </div>
                  </div>
               </div>
            </div>

            <!-- Right side list (admin: Strip sidebar posts + datatable) -->
            <div class="col-md-6 col-lg-4">
               <div class="gallery_end_content gallery_strip_sidebar_list px-4 py-5 overflow-auto bg-white">
                  <?php
                  $sidebarCount = count($galleryStripSidebarRows);
                  foreach ($galleryStripSidebarRows as $si => $sbRow):
                      ?>
                  <div class="gallery_end_content_item<?= $si < $sidebarCount - 1 ? ' mb-5' : '' ?> d-flex align-items-start">
                     <div class="gallery_end_thumb me-3">
                        <img src="<?= htmlspecialchars((string) ($sbRow['thumb'] ?? ''), ENT_QUOTES, 'UTF-8') ?>" alt="<?= htmlspecialchars((string) ($sbRow['title'] ?? ''), ENT_QUOTES, 'UTF-8') ?>">
                     </div>
                     <div class="gallery_end_inner_content">
                        <a href="<?= htmlspecialchars((string) ($sbRow['link'] ?? '#'), ENT_QUOTES, 'UTF-8') ?>"><h4><?= htmlspecialchars((string) ($sbRow['title'] ?? ''), ENT_QUOTES, 'UTF-8') ?></h4></a>
                        <div class="gallery_end_author_content d-flex">
                           <div class="gallery_end_author_title pe-2">
                              <span><i class="far fa-user pe-1"></i> <?= htmlspecialchars((string) ($sbRow['meta1'] ?? ''), ENT_QUOTES, 'UTF-8') ?></span>
                           </div>
                           <div class="gallery_end_author_time">
                              <span><i class="far fa-clock pe-1"></i> <?= htmlspecialchars((string) ($sbRow['meta2'] ?? ''), ENT_QUOTES, 'UTF-8') ?></span>
                           </div>
                        </div>
                     </div>
                  </div>
                  <?php endforeach; ?>
               </div>
            </div>

         </div>
      </div>
   </div>
</section>

         <!-- gallery section end -->

         <!-- service section start -->
         <section class="service_setion sec_space_xxs_50" data-aos="fade-up" data-aos-duration="2000">
   <div class="service_content_wrap">
      <div class="container">
         <div class="row">

            <?php
            $svcDurations = [500, 1000, 1500, 2000];
            foreach ($homeServices as $si => $svc):
                $dur = $svcDurations[$si % count($svcDurations)];
                ?>
            <div class="col-6 col-md-4 col-xl-3">
               <div class="service_inner_content d-flex justify-content-center align-items-center" data-aos="fade-up" data-aos-duration="<?= (int) $dur ?>">
                  <div class="service_content_icon rounded-pill me-2">
                     <i class="<?= fruitwala_home_row_h($svc, 'icon') ?>"></i>
                  </div>
                  <div class="service_content_text">
                     <div class="service_content_title">
                        <h6 class="text-uppercase"><?= fruitwala_home_row_h($svc, 'title') ?></h6>
                     </div>
                     <div class="service_content_sub_title">
                        <span><?= fruitwala_home_row_h($svc, 'subtitle') ?></span>
                     </div>
                  </div>
               </div>
            </div>
            <?php endforeach; ?>

         </div>
      </div>
   </div>
</section>

         <!-- service section end -->

         <!-- instagram section start -->
         <section class="instagram_section instagram_style_1 sec_space_xs_70" data-aos="fade-up"
   data-aos-duration="2000">
   <div class="container">
      <h2 class="instagram_title pb-5 text-center"><?= fruitwala_home_h($home, 'instagram', 'heading') ?></h2>
      <ul class="zoom-gallery instagram_image_content ul_li">

         <?php foreach ($igTiles as $tile): ?>
         <li>
            <a class="popup_image" href="<?= fruitwala_home_row_h($tile, 'popup') ?>">
               <img src="<?= fruitwala_home_row_h($tile, 'img') ?>" alt="<?= fruitwala_home_row_h($tile, 'alt') ?>">
               <i class="fab fa-instagram"></i>
               <span><?= fruitwala_home_h($home, 'instagram', 'handle') ?></span>
            </a>
         </li>
         <?php endforeach; ?>

      </ul>
   </div>
</section>

         <!-- instagram section end -->
          



      </main>
      <!-- main body end -->


    <?php include 'footer.php'; ?>