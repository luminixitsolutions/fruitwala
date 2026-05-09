<?php
if (!isset($conn) || !($conn instanceof mysqli)) {
    require_once __DIR__ . '/config.php';
}
require_once __DIR__ . '/includes/company_profile.php';
$company = fruitwala_company_profile_load(isset($conn) && $conn instanceof mysqli ? $conn : null);
?>
  <!-- footer section start -->
      <footer class="footer_section position-relative">
         <div class="footer_section_wrap sec_top_space_50"
            style="background-image: url(assets/images/footer/footer.png)">
            <div class="container">
               <div
                  class="footer_top_content d-flex flex-column flex-lg-row justify-content-between align-items-center">
                  <div class="footer_top_logo">
                     <a href="index.php"><img src="logo.png" alt="image_not_found" style="height: 70px;"></a>
                  </div>
                  <div class="footer_top_subs position-relative">
                     <input class="rounded-pill" type="search" name="search" placeholder="Your Phone Number">
                     <a href="#!"><button type="button"
                           class="btn custom_btn rounded-pill text-white position-absolute">Subscribe Now <i
                              class="fas fa-long-arrow-alt-right"></i></button></a>
                  </div>
                  <div class="footer_top_social">
                    <ul class="list-unstyled d-flex justify-content-end">
                        <li class="me-3">
                            <a href="<?= htmlspecialchars(fruitwala_company_profile_url_or_hash($company['instagram_url']), ENT_QUOTES, 'UTF-8') ?>" target="_blank" rel="noopener noreferrer">
                                <i class="fab fa-instagram"></i>
                            </a>
                        </li>
                        <li class="me-3">
                            <a href="<?= htmlspecialchars(fruitwala_company_profile_url_or_hash($company['facebook_url']), ENT_QUOTES, 'UTF-8') ?>" target="_blank" rel="noopener noreferrer">
                                <i class="fab fa-facebook-f"></i>
                            </a>
                        </li>
                        <li class="me-3">
                            <a href="<?= htmlspecialchars(fruitwala_company_profile_url_or_hash($company['whatsapp_url']), ENT_QUOTES, 'UTF-8') ?>" target="_blank" rel="noopener noreferrer">
                                <i class="fab fa-whatsapp"></i>
                            </a>
                        </li>
                        <li>
                            <a href="<?= htmlspecialchars(fruitwala_company_profile_url_or_hash($company['youtube_url']), ENT_QUOTES, 'UTF-8') ?>" target="_blank" rel="noopener noreferrer">
                                <i class="fab fa-youtube"></i>
                            </a>
                        </li>
                    </ul>
                    </div>

               </div>
               <div class="footer_inner_content sec_space_xs_70">
                  <div class="footer_inner_content_wrap">
                     <div class="row">
                        <div class="col-md-6 col-lg-3">
                           <div class="footer_inner_choose_content">
                              <div class="footer_inner_choose_title">
                                <h4>
                                    <a href="#!" class="text-white">Why Customers Love Fruitwala</a>
                                </h4>
                                </div>
                                <div class="footer_inner_choose_desc pt-2">
                                <p>We deliver farm-fresh fruits in beautifully packed boxes, perfect for healthy breakfasts and thoughtful gifting. 
                                    Our focus on freshness, hygiene, and on-time delivery makes us a favorite choice for fruit lovers.</p>
                                </div>

                              <div class="footer_inner_choose">
                                 <a href="why-choose-us.php"><button type="button"
                                       class="btn custom_btn rounded-pill px-4 text-white">View More <i
                                          class="fas fa-long-arrow-alt-right"></i></button></a>
                              </div>
                           </div>
                        </div>
                        <div class="col-md-6 col-lg-3">
                           <div class="footer_inner_info_content">
                              <div class="footer_inner_info_title">
                                 <h4>
                                    <a href="#!" class="text-white">Information</a>
                                 </h4>
                              </div>
                              <div class="footer_inner_info_item pt-2">
                                 <ul class="list-unstyled">
                                    <li><a href="about-us.php">About Us</a></li>
                                    <li><a href="why-choose-us.php">Why Choose Us</a></li>
                                    <li><a href="blogs.php">Blogs</a></li>
                                    <li><a href="blog-details.php">Blog Details</a></li>
                                    <li><a href="faq.php">FAQ</a></li>
                                    <li><a href="contact.php">Contact Us</a></li>
                                 </ul>
                              </div>
                           </div>
                        </div>
                        <div class="col-md-6 col-lg-3">
                           <div class="footer_inner_acct_content">
                              <div class="footer_inner_acct_title">
                                 <h4>
                                    <a href="#!" class="text-white">Visit Accounts</a>
                                 </h4>
                              </div>
                              <div class="footer_inner_acct_item pt-2">
                                 <ul class="list-unstyled">
                                    <li><a href="<?= htmlspecialchars(fruitwala_company_profile_url_or_hash($company['instagram_url']), ENT_QUOTES, 'UTF-8') ?>" target="_blank" rel="noopener noreferrer">Instagram</a></li>
                                    <li><a href="<?= htmlspecialchars(fruitwala_company_profile_url_or_hash($company['facebook_url']), ENT_QUOTES, 'UTF-8') ?>" target="_blank" rel="noopener noreferrer">Facebook</a></li>
                                    <li><a href="<?= htmlspecialchars(fruitwala_company_profile_url_or_hash($company['twitter_url']), ENT_QUOTES, 'UTF-8') ?>" target="_blank" rel="noopener noreferrer">Twitter</a></li>
                                    <li><a href="<?= htmlspecialchars(fruitwala_company_profile_url_or_hash($company['youtube_url']), ENT_QUOTES, 'UTF-8') ?>" target="_blank" rel="noopener noreferrer">Youtube</a></li>
                                    <li><a href="<?= htmlspecialchars(fruitwala_company_profile_url_or_hash($company['whatsapp_url']), ENT_QUOTES, 'UTF-8') ?>" target="_blank" rel="noopener noreferrer">WhatsApp</a></li>
                                    <?php if (trim($company['linkedin_url']) !== ''): ?>
                                    <li><a href="<?= htmlspecialchars($company['linkedin_url'], ENT_QUOTES, 'UTF-8') ?>" target="_blank" rel="noopener noreferrer">LinkedIn</a></li>
                                    <?php endif; ?>
                                 </ul>
                              </div>
                           </div>
                        </div>
                        <div class="col-md-6 col-lg-3">
                           <div class="footer_inner_cotc_content">
                              <div class="footer_inner_ctc_title">
   <h4>
      <a href="#!" class="text-white">Contact Fruitwala</a>
   </h4>
</div>
<div class="footer_inner_ctc_info pt-2 text-white">
   <p>Address: <font><?= htmlspecialchars($company['address'], ENT_QUOTES, 'UTF-8') ?></font></p>
   <?php if (trim($company['email']) !== ''): ?>
   <p>Email: <font><a href="mailto:<?= htmlspecialchars($company['email'], ENT_QUOTES, 'UTF-8') ?>" class="text-white"><?= htmlspecialchars($company['email'], ENT_QUOTES, 'UTF-8') ?></a></font></p>
   <?php endif; ?>
   <?php if (trim($company['phone']) !== ''): ?>
   <p>Phone: <font><?= htmlspecialchars($company['phone'], ENT_QUOTES, 'UTF-8') ?></font></p>
   <?php endif; ?>

   <div class="footer_inner_payment_ctc">
      <div class="footer_inner_payment_title">
         <h5 class="text-white">Payment Accepted</h5>
      </div>
      <div class="footer_inner_payment_thumb pt-3">
         <a href="#!"><img src="assets/images/payment/payment.png" alt="payment methods"></a>
      </div>
   </div>
</div>

                              </div>
                           </div>
                        </div>
                     </div>
                  </div>
               </div>
               <div class="footer_bootom_content">
                  <div class="footer_bootom_wrap">
                     <div class="container">
                        <div class="row">
                           <div class="col-md-6">
                              <div class="footer_bootom_copyright">
                                 <p>Copyright © 2026 <a href="https://www.luminixitsolutions.com/"><font>LUMINIX</font></a> Inc. All rights reserved.</p>
                              </div>
                           </div>
                           <div class="col-md-6">
                              <div class="footer_bootom_privicy_cont d-flex justify-content-center align-items-center">
                                 <div class="footer_bootom_privicy pe-5">
                                    <a href="#!">
                                       <p class="priv position-relative">Privacy Policy</p>
                                    </a>
                                 </div>
                                 <div class="footer_bootom_terms pe-5">
                                    <p class="position-relative">Terms of Use</p>
                                 </div>
                                 <div class="footer_bootom_refunds">
                                    <p>Sales and Refunds</p>
                                 </div>
                              </div>
                           </div>
                        </div>
                     </div>
                  </div>
               </div>
            </div>
         </div>
      </footer>
      <!-- footer section end -->

      <!-- quick-view start -->

      <div class="modal fade quick_view" id="product_quick_view" tabindex="-1" aria-labelledby="exampleModalLabel"
         aria-hidden="true">
         <div class="modal-dialog modal-dialog-centered">
            <div class="modal-content position-relative">
               <button type="button" class="btn-close position-absolute" data-bs-dismiss="modal" aria-label="Close"><i
                     class="fas fa-times"></i></button>
               <div class="modal-body p-0">
                  <div class="container-fluid-full product10_wrap sec_space_small"
                     style="background-image: url(assets/images/backgrounds/bg17.png)">
                     <div class="row justify-content-center p-5">
                        <div class="col-lg-6">
                           <div class="product10_thumb_content d-flex flex-column">
                              <div class="product11_slide_item">
                                 <div
                                    class="d-flex justify-content-center align-items-center position-relative overflow-hidden">
                                    <img src="assets/images/product/product40.png" alt="image_not_found">
                                 </div>
                                 <div
                                    class="d-flex justify-content-center align-items-center position-relative overflow-hidden">
                                    <img src="assets/images/product/product40.png" alt="image_not_found">
                                 </div>
                                 <div
                                    class="d-flex justify-content-center align-items-center position-relative overflow-hidden">
                                    <img src="assets/images/product/product40.png" alt="image_not_found">
                                 </div>
                              </div>

                              <div class="product10_thumb_item product11_slide_thumb">
                                 <div class="thumb_item">
                                    <a href="#!"><img src="assets/images/product/product6.png"
                                          alt="image_not_found"></a>
                                 </div>
                                 <div class="thumb_item">
                                    <a href="#!"><img src="assets/images/product/product8.png"
                                          alt="image_not_found"></a>
                                 </div>
                                 <div class="thumb_item">
                                    <a href="#!"><img src="assets/images/product/product23.png"
                                          alt="image_not_found"></a>
                                 </div>
                                 <div class="thumb_item">
                                    <a href="#!"><img src="assets/images/product/product6.png"
                                          alt="image_not_found"></a>
                                 </div>
                                 <div class="thumb_item">
                                    <a href="#!"><img src="assets/images/product/product8.png"
                                          alt="image_not_found"></a>
                                 </div>
                                 <div class="thumb_item">
                                    <a href="#!"><img src="assets/images/product/product23.png"
                                          alt="image_not_found"></a>
                                 </div>
                              </div>
                           </div>
                        </div>
                        <div class="col-lg-6">
                           <div class="rating_wrap d-flex justify-content-between">
                              <div class="rating_review_cont d-flex d-flex align-items-center">
                                 <ul class="rating_star ul_li">
                                    <li class="active"><i class="fas fa-star"></i></li>
                                    <li class="active"><i class="fas fa-star"></i></li>
                                    <li class="active"><i class="fas fa-star"></i></li>
                                    <li class="active"><i class="fas fa-star"></i></li>
                                    <li><i class="far fa-star"></i></li>
                                 </ul>
                                 <a href="#!" class="review">Read 3 Reviews</a>
                              </div>
                              <div class="product_btn">
                                 <a href="#"><button type="button"
                                       class="btn custom_btn rounded-pill px-4 text-white">Smoothies</button></a>
                              </div>
                           </div>
                           <h2 class="product_detail_title">Good Organic Products</h2>
                           <p class="product_detail_desc py-2">Morbi eget congue lectus. Donec eleifend ultricies urna
                              et euismod. Sed consectetur tellus eget odio aliquet, vel vestibulum tellus sollicitudin.
                              Morbi maximus metus eu eros tincidunt, vitae mollis ante imperdiet. Nulla imperdiet at
                              mauris ut posuere. Nam at ultrices justo.</p>
                           <div class="product10_quantity_btn_wrap d-flex align-items-center">
                              <div class="quantity_input bg-white">
                                 <form action="#">
                                    <span class="input_number_decrement">–</span>
                                    <input class="input_number" value="2KG">
                                    <span class="input_number_increment">+</span>
                                 </form>
                              </div>
                              <a href="#"><button type="button"
                                    class="btn custom_btn rounded-pill ms-3 px-5 py-3 text-white">Order Now <i
                                       class="fas fa-long-arrow-alt-right"></i></button></a>
                           </div>
                           <div class="product_tags_wrap d-flex align-items-center mt-5">
                              <h6 class="product_tags_title text-uppercase">tags:</h6>
                              <div class="tags_item d-flex align-items-center">
                                 <a href="#!">T-shirt,</a>
                                 <a class="ms-1" href="#!">Clothes,</a>
                                 <a class="ms-1" href="#!">Fashion,</a>
                                 <a class="ms-1" href="#!">Shop</a>
                              </div>
                           </div>
                           <div class="product_social_links d-flex align-items-center">
                              <h6 class="product_social_title text-uppercase">share:</h6>
                              <ul class="list-unstyled d-flex mb-0">
                                 <li><a href="#!"><i class="fab fa-facebook-f"></i></a></li>
                                 <li><a href="#!"><i class="fab fa-twitter"></i></a></li>
                                 <li><a href="#!"><i class="fab fa-google-plus-g"></i></a></li>
                                 <li><a href="#!"><i class="fab fa-pinterest-p"></i></a></li>
                              </ul>
                           </div>
                        </div>
                     </div>
                  </div>
               </div>
            </div>
         </div>
      </div>

      <!-- quick-view end -->

   </div>
   <!-- body wrap end -->

   <div class="modal fade" id="videoModal" tabindex="-1" aria-hidden="true">
  <div class="modal-dialog modal-dialog-centered modal-lg">
    <div class="modal-content bg-transparent border-0">
      <div class="modal-body p-0 position-relative">

        <!-- Close Button -->
        <button type="button"
         class="position-absolute"
         style="top:10px; right:10px; z-index:10;
               background:rgba(0,0,0,0.6);
               border:none;
               width:35px;
               height:35px;
               border-radius:50%;
               display:flex;
               align-items:center;
               justify-content:center;"
         data-bs-dismiss="modal">
         <span style="color:white; font-size:18px;">×</span>
      </button>


        <!-- Video -->
        <div class="video-wrapper d-flex justify-content-center align-items-center">
    <video id="reelVideo" controls autoplay playsinline
        style="max-height:85vh; width:auto; max-width:100%; border-radius:12px;">
        <source src="" type="video/mp4">
    </video>
</div>



      </div>
    </div>
  </div>
</div>

<!-- Inquiry Popup Modal -->
<div class="modal fade" id="inquiryModal" tabindex="-1" aria-hidden="true">
  <div class="modal-dialog modal-dialog-centered">
    <div class="modal-content p-4" style="border-radius:15px;">
      
      <div class="modal-header border-0 pb-0">
        <h5 class="modal-title fw-bold">Fruitwala Breakfast Inquiry</h5>
        <button type="button" class="btn-close" data-bs-dismiss="modal"></button>
      </div>

      <div class="modal-body">
        <p class="mb-3">Looking for fresh fruit boxes or gift baskets? Tell us what you need 🍓</p>

        <form action="inquiry-submit.php" method="POST">
             <input type="hidden" name="PkgName" value="Home">
          <div class="mb-3">
            <input type="text" name="name" class="form-control" placeholder="Your Name" required>
          </div>

          <div class="mb-3">
            <input type="tel" name="phone" class="form-control" placeholder="Mobile Number" required>
          </div>

          <div class="mb-3">
            <textarea name="message" class="form-control" rows="3" placeholder="What are you looking for?"></textarea>
          </div>

          <button type="submit" class="btn w-100 text-white" style="background:#8cc63f;">
            Send Inquiry
          </button>
        </form>
      </div>

    </div>
  </div>
</div>



   <!-- Include jquery js -->
   <script src="assets/js/jquery.min.js"></script>

   <!-- Include bootstrap-bundle js -->
   <script src="assets/js/bootstrap.bundle.min.js"></script>

   <!-- Include aos js -->
   <script src="assets/js/aos.js"></script>

   <!-- Include jquery-magnific-popup js -->
   <script src="assets/js/magnific-popup.min.js"></script>

   <!-- Include nice-select js -->
   <script src="assets/js/nice-select.min.js"></script>

   <!-- Include jquery-countdown js -->
   <script src="assets/js/countdown.min.js"></script>

   <!-- Include slick js -->
   <script src="assets/js/slick.min.js"></script>

   <!-- Include custom js -->
   <script src="assets/js/custom.js"></script>

   <script async src="//www.instagram.com/embed.js"></script>

   <script>
function openVideo(src) {
    const video = document.getElementById("reelVideo");
    video.pause();
    video.src = src;
    video.load();

    var modal = new bootstrap.Modal(document.getElementById('videoModal'));
    modal.show();

    video.onloadeddata = () => {
        video.play().catch(() => {});
    };
}

function stopVideo() {
    const video = document.getElementById("reelVideo");
    video.pause();
    video.currentTime = 0;
    video.src = "";
}

const videoModal = document.getElementById('videoModal');

videoModal.addEventListener('hidden.bs.modal', function () {
    stopVideo();
});

</script>
<script>
document.addEventListener("DOMContentLoaded", function () {
    const video = document.getElementById("qualityVideo");

    const observer = new IntersectionObserver((entries) => {
        entries.forEach(entry => {
            if (entry.isIntersecting) {
                video.play().catch(() => {});
            } else {
                video.pause();
            }
        });
    }, { threshold: 0.6 }); // Plays when 60% visible

    observer.observe(video);
});
</script>


<?php if(basename($_SERVER['PHP_SELF']) == 'index.php'): ?>
<script>
  window.addEventListener('load', function () {
      var inquiryModal = new bootstrap.Modal(document.getElementById('inquiryModal'));
      inquiryModal.show();
  });
</script>
<?php endif; ?>


</body>

</html>