<?php
require_once __DIR__ . '/config.php';
require_once __DIR__ . '/includes/packages_db.php';
$packageRows = fruitwala_packages_load_public($conn);
include 'header.php';
?>
            <!-- main body start -->
            <main>


                <!-- Breadcrumb section end -->

                <!-- featured product section start -->
            <!-- featured product section start -->
<section class="featured_sec position-relative sec_inner_bottom_80" data-aos="fade-up"
    data-aos-duration="2000">
    <div class="row justify-content-center text-center">

    <?php if ($packageRows === []): ?>
    <div class="col-12">
      <p class="text-muted py-5">No packages are available at the moment. Please check back soon.</p>
    </div>
    <?php else: ?>
    <?php foreach ($packageRows as $idx => $pkg): ?>
    <?php
      $aos = ($idx % 2 === 0) ? 'fade-right' : 'fade-left';
      $bullets = fruitwala_package_bullet_lines((string) ($pkg['bullet_points'] ?? ''));
      $imgAlt = htmlspecialchars((string) ($pkg['title'] ?? 'Package'), ENT_QUOTES, 'UTF-8');
      $bookName = rawurlencode((string) ($pkg['book_pkg_name'] ?? ''));
    ?>
    <div class="col-md-6 col-lg-5">
        <div class="product_layout3_content bg-white position-relative p-4" data-aos="<?= htmlspecialchars($aos, ENT_QUOTES, 'UTF-8') ?>"
            data-aos-duration="1500">

            <div class="product_image_wrap">
                <img src="<?= htmlspecialchars((string) ($pkg['image'] ?? ''), ENT_QUOTES, 'UTF-8') ?>" alt="<?= $imgAlt ?>">
            </div>

            <h3 class="product_title mt-3"><?= htmlspecialchars((string) ($pkg['title'] ?? ''), ENT_QUOTES, 'UTF-8') ?></h3>
            <p><?= htmlspecialchars((string) ($pkg['delivery_line'] ?? ''), ENT_QUOTES, 'UTF-8') ?></p>

            <div class="product_price mb-2">
                <span class="sale_price fs-4 text-success">₹<?= htmlspecialchars((string) ($pkg['sale_price'] ?? ''), ENT_QUOTES, 'UTF-8') ?></span>
                <?php if (trim((string) ($pkg['mrp'] ?? '')) !== ''): ?>
                <del class="text-muted ms-2">₹<?= htmlspecialchars((string) ($pkg['mrp'] ?? ''), ENT_QUOTES, 'UTF-8') ?></del>
                <?php endif; ?>
            </div>

            <div class="d-flex justify-content-center gap-2 mb-3">
                <?php if (trim((string) ($pkg['badge_1'] ?? '')) !== ''): ?>
                <span class="badge bg-success"><?= htmlspecialchars((string) $pkg['badge_1'], ENT_QUOTES, 'UTF-8') ?></span>
                <?php endif; ?>
                <?php if (trim((string) ($pkg['badge_2'] ?? '')) !== ''): ?>
                <span class="badge bg-primary"><?= htmlspecialchars((string) $pkg['badge_2'], ENT_QUOTES, 'UTF-8') ?></span>
                <?php endif; ?>
            </div>

            <ul class="package-points mx-auto">
                <?php foreach ($bullets as $pt): ?>
                <li><?= htmlspecialchars($pt, ENT_QUOTES, 'UTF-8') ?></li>
                <?php endforeach; ?>
            </ul>
            <a href="book-now.php?PkgName=<?= htmlspecialchars($bookName, ENT_QUOTES, 'UTF-8') ?>"><button type="button" class="btn custom_btn load_more_1 rounded-pill px-5 py-3 text-white">
                                    Book Now <i class="fas fa-long-arrow-alt-right"></i>
                                </button></a>
        </div>
    </div>
    <?php endforeach; ?>
    <?php endif; ?>

</div>


    <div class="fearure_right_side_img position-absolute">
        <img src="assets/images/shapes/shape30.png" alt="image_not_found">
    </div>
</section>

<!-- featured product section end -->

            <!-- featured product section end -->



        </main>
        <!-- main body end -->

<?php include 'footer.php'; ?>
