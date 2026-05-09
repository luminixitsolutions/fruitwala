<?php
require_once __DIR__ . '/config.php';
require_once __DIR__ . '/includes/blogs_db.php';
$blogRows = fruitwala_blogs_list_active($conn);
include 'header.php';
?>
            <!-- main body start -->
            <main>
                
                <!-- Breadcrumb section start -->
                <section class="breadcrumb_sec_1 position-relative">
                    <div class="breadcrumb_wrap sec_space_mid_small" style="background-image: url(assets/images/breadcrumb/bg.jpg);">
                        <div class="breadcrumb_cont text-center">
                            <div class="breadcrumb_title">
                                <h2 class="text-white">Blogs</h2>
                            </div>
                            <ul class="list-unstyled breadcrumb_item d-flex justify-content-center align-items-center text-white">
                                <li><a href="index.php"><i class="fas fa-home active"></i>Home</a></li>
                                <li><i class="fas fa-chevron-right"></i>Blogs</li>
                            </ul>
                        </div>
                    </div>
                </section>
                <!-- Breadcrumb section end -->

            <div class="blog_grid_sec sec_space_small blog_list_sec">
   <div class="blog_grid_wrap blog_list_wrap">
      <div class="container">
         <div class="row g-4">

            <?php if ($blogRows === []): ?>
            <div class="col-12">
              <p class="text-center sec_space_small" style="max-width:36rem;margin-left:auto;margin-right:auto;color:#555;">
                No blog posts yet. Please check back soon, or visit the admin panel to add stories under <strong>Blogs</strong>.
              </p>
            </div>
            <?php else: ?>
            <?php foreach ($blogRows as $idx => $post):
                $img = trim((string) ($post['image'] ?? ''));
                $imgSrc = $img !== '' ? htmlspecialchars($img, ENT_QUOTES, 'UTF-8') : 'assets/images/19.png';
                $title = htmlspecialchars((string) $post['title'], ENT_QUOTES, 'UTF-8');
                $excerpt = htmlspecialchars((string) $post['excerpt'], ENT_QUOTES, 'UTF-8');
                $author = htmlspecialchars((string) ($post['author'] ?? 'Fruitwala Team'), ENT_QUOTES, 'UTF-8');
                $category = htmlspecialchars((string) ($post['category'] ?? ''), ENT_QUOTES, 'UTF-8');
                $detailUrl = 'blog-details.php?id=' . (int) $post['id'];
                $aosDur = 1000 + (int) ($idx % 3) * 500;
                ?>
            <div class="col-sm-6 col-md-4">
               <div class="blog_grid_cont blog_list_cont" data-aos="fade-up" data-aos-duration="<?= (int) $aosDur ?>">
                  <div class="grid_img d-flex blog_list_img">
                     <img src="<?= $imgSrc ?>" alt="<?= $title ?>">
                  </div>
                  <div class="blog_grid_text">
                     <a href="<?= htmlspecialchars($detailUrl, ENT_QUOTES, 'UTF-8') ?>">
                        <h3 class="grid_title"><?= $title ?></h3>
                     </a>
                     <div class="gallery_mid_author_content py-2 d-flex justify-content-between">
                        <span><i class="far fa-user pe-1"></i> <?= $author ?></span>
                        <?php if ($category !== ''): ?>
                        <span><i class="far fa-clock pe-1"></i> <?= $category ?></span>
                        <?php endif; ?>
                     </div>
                     <p class="grid_desc"><?= $excerpt ?></p>
                  </div>
               </div>
            </div>
            <?php endforeach; ?>
            <?php endif; ?>

         </div>
      </div>
   </div>
</div>

            </main>
<?php include 'footer.php'; ?>
