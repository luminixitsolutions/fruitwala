<?php
require_once __DIR__ . '/config.php';
require_once __DIR__ . '/includes/portfolio_items.php';
fruitwala_portfolio_ensure_table($conn);
$portfolioItems = fruitwala_get_portfolio_items($conn, true);
include 'header.php';
?>


 <!-- main body start -->
            <main>
                
                <!-- Breadcrumb section start -->
                <section class="breadcrumb_sec_1 position-relative">
                    <div class="breadcrumb_wrap sec_space_mid_small" style="background-image: url(assets/images/breadcrumb/bg.jpg);">
                        <div class="breadcrumb_cont text-center">
                            <div class="breadcrumb_title">
                                <h2 class="text-white"> Portfolio</h2>
                            </div>
                            <ul class="list-unstyled breadcrumb_item d-flex justify-content-center align-items-center text-white">
                                <li><a href="index.php"><i class="fas fa-home active"></i>Home</a></li>
                                <li><i class="fas fa-chevron-right"></i>Portfolio</li>
                            </ul>
                        </div>
                    </div>
                </section><br><br><br>
                <!-- Breadcrumb section end -->


                <!-- featured product section start -->
        <section class="featured_sec position-relative sec_inner_bottom_80">
    <div class="container">
        <div class="row g-4">

            <?php if ($portfolioItems === []): ?>
            <div class="col-12">
              <p class="text-center text-muted mb-0">No portfolio videos yet. Add items from the admin <strong>Portfolio</strong> section.</p>
            </div>
            <?php else: ?>
            <?php foreach ($portfolioItems as $item): ?>
            <div class="col-12 col-sm-6 col-lg-3">
               <div class="reel-card" onclick="openVideo(<?= json_encode($item['video'], JSON_HEX_TAG | JSON_HEX_APOS | JSON_HEX_QUOT) ?>)">
                  <img src="<?= htmlspecialchars($item['cover'], ENT_QUOTES, 'UTF-8') ?>" class="img-fluid" alt="<?= htmlspecialchars($item['alt'] !== '' ? $item['alt'] : 'Portfolio reel', ENT_QUOTES, 'UTF-8') ?>">
                  <div class="reel-play"><i class="fas fa-play"></i></div>
               </div>
            </div>
            <?php endforeach; ?>
            <?php endif; ?>

        </div>
    </div>
</section>


            <!-- featured product section end -->



            </main>

            <?php include 'footer.php'; ?>
