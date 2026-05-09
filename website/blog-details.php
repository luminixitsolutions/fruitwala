<?php
require_once __DIR__ . '/config.php';
require_once __DIR__ . '/includes/blogs_db.php';
$blogId = isset($_GET['id']) ? (int) $_GET['id'] : 0;
$blog = fruitwala_blogs_get_by_id($conn, $blogId, false);
$pageHeading = 'Blog details';
$breadcrumbTrail = 'Blog details';
if (is_array($blog)) {
    $pageHeading = htmlspecialchars((string) $blog['title'], ENT_QUOTES, 'UTF-8');
    $breadcrumbTrail = htmlspecialchars(fruitwala_blogs_trunc((string) $blog['title'], 42), ENT_QUOTES, 'UTF-8');
}
include 'header.php';
?>
 <!-- main body start -->
            <main>
                
                <!-- Breadcrumb section start -->
                <section class="breadcrumb_sec_1 position-relative">
                    <div class="breadcrumb_wrap sec_space_mid_small" style="background-image: url(assets/images/breadcrumb/bg.jpg);">
                        <div class="breadcrumb_cont text-center">
                            <div class="breadcrumb_title">
                                <h2 class="text-white"><?= is_array($blog) ? $pageHeading : 'Blog details' ?></h2>
                            </div>
                            <ul class="list-unstyled breadcrumb_item d-flex justify-content-center align-items-center text-white">
                                <li><a href="index.php"><i class="fas fa-home active"></i>Home</a></li>
                                <li><i class="fas fa-chevron-right"></i><a href="blogs.php">Blogs</a></li>
                                <li><i class="fas fa-chevron-right"></i><?= is_array($blog) ? $breadcrumbTrail : 'Not found' ?></li>
                            </ul>
                        </div>
                    </div>
                </section>
                <!-- Breadcrumb section end -->
<?php if (!is_array($blog)): ?>
<section class="blog_details sec_space_xs_70">
    <div class="container-sm text-center sec_space_small">
        <p style="max-width:28rem;margin:0 auto;color:#555;">This blog post is not available or has been unpublished. <a href="blogs.php">Back to blogs</a></p>
    </div>
</section>
<?php else:
    $cat = trim((string) ($blog['category'] ?? ''));
    $author = htmlspecialchars((string) ($blog['author'] ?? 'Fruitwala Team'), ENT_QUOTES, 'UTF-8');
    $img = trim((string) ($blog['image'] ?? ''));
    $imgSrc = $img !== '' ? htmlspecialchars($img, ENT_QUOTES, 'UTF-8') : 'assets/images/17.jpg';
    $bodyHtml = fruitwala_blogs_format_body_html((string) ($blog['content'] ?? ''));
    ?>
<section class="blog_details sec_space_xs_70">
    <div class="container-sm">
        <div class="row justify-content-center">
            <div class="col-md-9">
                <div class="blog_details_cont">
                    <?php if ($cat !== ''): ?>
                    <span class="blog_date"><?= htmlspecialchars($cat, ENT_QUOTES, 'UTF-8') ?></span>
                    <?php endif; ?>
                    <h2 class="blog_title text-capitalize"><?= htmlspecialchars((string) $blog['title'], ENT_QUOTES, 'UTF-8') ?></h2>
                    <div class="blog_author_cont d-flex align-items-center py-3 flex-wrap">
                        <span class="author_name me-5"><font>Posted by</font> <?= $author ?></span>
                        <?php if ($cat !== ''): ?>
                        <span class="author_position me-5"><font>In:</font> <?= htmlspecialchars($cat, ENT_QUOTES, 'UTF-8') ?></span>
                        <?php endif; ?>
                    </div>
                    <?php if (trim((string) ($blog['excerpt'] ?? '')) !== ''): ?>
                    <p class="blog_desc py-3"><?= nl2br(htmlspecialchars((string) $blog['excerpt'], ENT_QUOTES, 'UTF-8')) ?></p>
                    <?php endif; ?>
                </div>
            </div>
            <div class="col">
                <div class="blog_image">
                    <img src="<?= $imgSrc ?>" alt="<?= htmlspecialchars((string) $blog['title'], ENT_QUOTES, 'UTF-8') ?>">
                </div>
            </div>
            <div class="col-md-10">
                <?= $bodyHtml ?>
            </div>
        </div>

        <hr>

        <div class="row">
            <div class="col-md-8 sec_space_xxs_50">
                <div class="share_cont d-flex align-items-center mt-3">
                    <span class="share_title text-uppercase me-5">share:</span>
                    <ul class="share_socials ul_li_right clearfix">
                        <li><a href="#"><i class="fab fa-facebook-f"></i></a></li>
                        <li><a href="#"><i class="fab fa-whatsapp"></i></a></li>
                        <li><a href="#"><i class="fab fa-twitter"></i></a></li>
                        <li><a href="https://www.instagram.com/fruitwala_breakfast/" target="_blank" rel="noopener"><i class="fab fa-instagram"></i></a></li>
                    </ul>
                </div>
            </div>
        </div>
    </div>
</section>
<?php endif; ?>

            </main>

<?php include 'footer.php'; ?>
