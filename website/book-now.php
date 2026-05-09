<?php include 'header.php'; ?>
            <!-- main body start -->
            <main>
                
                



            <section class="contact_us_info position-relative" data-aos="fade-up" data-aos-duration="2000">
    <div class="comment_area_wrap" style="background-image: url(assets/images/backgrounds/bg15.png)">
        <div class="container">
            <div class="row">

                <div class="col-lg-6 sec_space_small position-relative">
                    

                    <h3 class="comment_area_title mb-5">Book Now</h3>

                    <div class="comment_form_area">
                        <form action="inquiry-submit.php" method="POST">

                            <div class="row">
               
                
                <div class="col-lg-12">
                                    <div class="form_item">
                                        <input class="rounded-pill" type="text" name="PkgName"
                                            placeholder="" value="<?php echo $_GET['PkgName'];?>" readonly>
                                    </div>
                                </div>
                                <div class="col-lg-6">
                                    <div class="form_item">
                                        <input class="rounded-pill" type="text" name="name"
                                            placeholder="Your Name*" required>
                                    </div>
                                </div>

                                <div class="col-lg-6">
                                    <div class="form_item">
                                        <input class="rounded-pill" type="tel" name="phone"
                                            placeholder="Phone Number*" required>
                                    </div>
                                </div>

                                <div class="col-lg-6">
                                    <div class="form_item">
                                        <input class="rounded-pill" type="text" name="address"
                                            placeholder=" Address">
                                    </div>
                                </div>

                                <div class="col-lg-6">
                                    <div class="form_item">
                                        <input class="rounded-pill" type="text" name="map"
                                            placeholder="Addres Link">
                                    </div>
                                </div>
                               

                            </div>


                            <button type="submit"
                                class="btn custom_btn rounded-pill py-3 text-white text-uppercase">
                                Send Enquiry <i class="fas fa-long-arrow-alt-right"></i>
                            </button>
                        </form>
                    </div>

                    <img class="contact_info_thumb_right position-absolute"
                        src="assets/images/product/product36.png" alt="image_not_found">
                </div>


            </div>
        </div>
    </div>

    <img class="contact_info_thumb_left position-absolute" src="assets/images/shapes/shape22.png"
        alt="image_not_found">
</section>

            <!-- contact-info section end -->



            <!-- address-section end -->

            </main>

<?php include 'footer.php'; ?>