<?php
declare(strict_types=1);

require_once __DIR__ . '/bootstrap.php';
require_once __DIR__ . '/includes/auth.php';
admin_require_login();

/**
 * @return int Non-negative; 0 if query fails.
 */
function fruitwala_admin_dashboard_count(mysqli $conn, string $sql): int
{
    $r = mysqli_query($conn, $sql);
    if ($r === false) {
        return 0;
    }
    $row = mysqli_fetch_row($r);
    mysqli_free_result($r);
    $n = isset($row[0]) ? (int) $row[0] : 0;
    return $n >= 0 ? $n : 0;
}

$menuCount = fruitwala_admin_dashboard_count($conn, 'SELECT COUNT(*) FROM nav_menus');
$menuActive = fruitwala_admin_dashboard_count($conn, 'SELECT COUNT(*) FROM nav_menus WHERE is_active = 1');

$nHero = fruitwala_admin_dashboard_count($conn, 'SELECT COUNT(*) FROM home_hero_slides');
$nReels = fruitwala_admin_dashboard_count($conn, 'SELECT COUNT(*) FROM home_reels');
$nSaleBanners = fruitwala_admin_dashboard_count($conn, 'SELECT COUNT(*) FROM home_sale_banners');
$nServices = fruitwala_admin_dashboard_count($conn, 'SELECT COUNT(*) FROM home_services');
$nIg = fruitwala_admin_dashboard_count($conn, 'SELECT COUNT(*) FROM home_instagram_tiles');
$nTestimonials = fruitwala_admin_dashboard_count($conn, 'SELECT COUNT(*) FROM home_testimonials');
$nQualityFields = fruitwala_admin_dashboard_count($conn, "SELECT COUNT(*) FROM home_page_fields WHERE section_key LIKE 'quality%'");
$nProductCtgFields = fruitwala_admin_dashboard_count($conn, "SELECT COUNT(*) FROM home_page_fields WHERE section_key LIKE 'product_ctg%'");
$nGalleryFields = fruitwala_admin_dashboard_count($conn, "SELECT COUNT(*) FROM home_page_fields WHERE section_key LIKE 'gallery%'");

$nFaq = fruitwala_admin_dashboard_count($conn, 'SELECT COUNT(*) FROM faqs');
$nFaqActive = fruitwala_admin_dashboard_count($conn, 'SELECT COUNT(*) FROM faqs WHERE is_active = 1');

$nPackages = fruitwala_admin_dashboard_count($conn, 'SELECT COUNT(*) FROM packages');
$nPackagesActive = fruitwala_admin_dashboard_count($conn, 'SELECT COUNT(*) FROM packages WHERE is_active = 1');

$nPortfolio = fruitwala_admin_dashboard_count($conn, 'SELECT COUNT(*) FROM portfolio_items');
$nPortfolioActive = fruitwala_admin_dashboard_count($conn, 'SELECT COUNT(*) FROM portfolio_items WHERE is_active = 1');

$nBlogs = fruitwala_admin_dashboard_count($conn, 'SELECT COUNT(*) FROM blogs');
$nBlogsActive = fruitwala_admin_dashboard_count($conn, 'SELECT COUNT(*) FROM blogs WHERE is_active = 1');

$companyName = '';
$companyUpdated = '';
$cr = mysqli_query($conn, 'SELECT company_name, updated_at FROM company_profile WHERE id = 1 LIMIT 1');
if ($cr && ($crow = mysqli_fetch_assoc($cr))) {
    $companyName = (string) ($crow['company_name'] ?? '');
    $companyUpdated = (string) ($crow['updated_at'] ?? '');
    mysqli_free_result($cr);
}

$aboutTitle = '';
$aboutUpdated = '';
$ar = mysqli_query($conn, 'SELECT breadcrumb_title, updated_at FROM about_us_content WHERE id = 1 LIMIT 1');
if ($ar && ($arow = mysqli_fetch_assoc($ar))) {
    $aboutTitle = (string) ($arow['breadcrumb_title'] ?? '');
    $aboutUpdated = (string) ($arow['updated_at'] ?? '');
    mysqli_free_result($ar);
}

$fmtTime = static function (string $ts): string {
    if ($ts === '') {
        return '—';
    }
    $t = strtotime($ts);
    if ($t === false) {
        return '—';
    }
    return date('M j, Y · g:i A', $t);
};

$username = admin_current_username();
$contentTotal =
    $nHero + $nReels + $nSaleBanners + $nServices + $nIg
    + $nQualityFields + $nProductCtgFields + $nGalleryFields + $nTestimonials
    + $nFaq + $nPackages + $nPortfolio + $nBlogs;

$pageTitle = 'Dashboard';
$activeNav = 'dashboard';
require __DIR__ . '/includes/layout_header.php';
?>

<div class="admin-topbar">
  <div>
    <h2>Dashboard</h2>
    <p class="admin-topbar-sub">Overview of your site content, aligned with the sidebar.</p>
  </div>
  <div class="admin-topbar-actions">
    <a class="btn btn-primary btn-sm" href="../index.php" target="_blank" rel="noopener"><i class="fas fa-external-link-alt"></i> View site</a>
    <a class="btn btn-ghost btn-sm" href="home.php"><i class="fas fa-house"></i> Home hub</a>
  </div>
</div>

<div class="dashboard-hero">
  <div class="dashboard-hero-text">
    <h1 class="dashboard-hero-title">Welcome back<?= $username !== '' ? ', ' . htmlspecialchars($username, ENT_QUOTES, 'UTF-8') : '' ?></h1>
    <p class="dashboard-hero-lead">Jump into any area below. Counts reflect what is stored in the database right now.</p>
  </div>
  <div class="dashboard-hero-stats">
    <div class="dashboard-hero-pill">
      <span class="dashboard-hero-pill-value"><?= (int) $contentTotal ?></span>
      <span class="dashboard-hero-pill-label">Tracked items</span>
    </div>
    <div class="dashboard-hero-pill dashboard-hero-pill--muted">
      <span class="dashboard-hero-pill-value"><?= (int) $menuActive ?> / <?= (int) $menuCount ?></span>
      <span class="dashboard-hero-pill-label">Nav links live</span>
    </div>
  </div>
</div>

<div class="dashboard-hub">

  <section class="dashboard-panel dashboard-panel--home" aria-labelledby="dash-home-heading">
    <header class="dashboard-panel-head">
      <div class="dashboard-panel-icon" aria-hidden="true"><i class="fas fa-house"></i></div>
      <div class="dashboard-panel-titles">
        <h3 id="dash-home-heading">Home page</h3>
        <p>Hero, reels, sections, and blocks shown on <code>index.php</code></p>
      </div>
      <a class="btn btn-primary btn-sm dashboard-panel-cta" href="home.php"><i class="fas fa-layer-group"></i> All sections</a>
    </header>
    <div class="dashboard-tiles">
      <a class="dashboard-tile" href="home_edit.php?s=hero">
        <span class="dashboard-tile-icon"><i class="fas fa-panorama"></i></span>
        <span class="dashboard-tile-label">Hero / banner</span>
        <span class="dashboard-tile-meta"><?= (int) $nHero ?> slide<?= $nHero === 1 ? '' : 's' ?></span>
      </a>
      <a class="dashboard-tile" href="home_edit.php?s=reels">
        <span class="dashboard-tile-icon"><i class="fas fa-film"></i></span>
        <span class="dashboard-tile-label">Featured reels</span>
        <span class="dashboard-tile-meta"><?= (int) $nReels ?> reel<?= $nReels === 1 ? '' : 's' ?></span>
      </a>
      <a class="dashboard-tile" href="home_edit.php?s=quality">
        <span class="dashboard-tile-icon"><i class="fas fa-circle-check"></i></span>
        <span class="dashboard-tile-label">Why choose</span>
        <span class="dashboard-tile-meta"><?= (int) $nQualityFields ?> field<?= $nQualityFields === 1 ? '' : 's' ?></span>
      </a>
      <a class="dashboard-tile" href="home_edit.php?s=sale_banners">
        <span class="dashboard-tile-icon"><i class="fas fa-tags"></i></span>
        <span class="dashboard-tile-label">Sale banners</span>
        <span class="dashboard-tile-meta"><?= (int) $nSaleBanners ?> banner<?= $nSaleBanners === 1 ? '' : 's' ?></span>
      </a>
      <a class="dashboard-tile" href="home_edit.php?s=product_ctg">
        <span class="dashboard-tile-icon"><i class="fas fa-gift"></i></span>
        <span class="dashboard-tile-label">Offers block</span>
        <span class="dashboard-tile-meta"><?= (int) $nProductCtgFields ?> field<?= $nProductCtgFields === 1 ? '' : 's' ?></span>
      </a>
      <a class="dashboard-tile" href="home_edit.php?s=services">
        <span class="dashboard-tile-icon"><i class="fas fa-truck-fast"></i></span>
        <span class="dashboard-tile-label">Service row</span>
        <span class="dashboard-tile-meta"><?= (int) $nServices ?> service<?= $nServices === 1 ? '' : 's' ?></span>
      </a>
      <a class="dashboard-tile" href="home_edit.php?s=instagram">
        <span class="dashboard-tile-icon"><i class="fab fa-instagram"></i></span>
        <span class="dashboard-tile-label">Instagram</span>
        <span class="dashboard-tile-meta"><?= (int) $nIg ?> tile<?= $nIg === 1 ? '' : 's' ?></span>
      </a>
    </div>
  </section>

  <section class="dashboard-panel dashboard-panel--company" aria-labelledby="dash-company-heading">
    <header class="dashboard-panel-head">
      <div class="dashboard-panel-icon" aria-hidden="true"><i class="fas fa-building"></i></div>
      <div class="dashboard-panel-titles">
        <h3 id="dash-company-heading">Company profile</h3>
        <p>Contact details and social links site-wide</p>
      </div>
      <a class="btn btn-primary btn-sm dashboard-panel-cta" href="company_profile_edit.php"><i class="fas fa-pen"></i> Edit</a>
    </header>
    <div class="dashboard-panel-body dashboard-panel-body--single">
      <p class="dashboard-blurb"><?= $companyName !== '' ? htmlspecialchars($companyName, ENT_QUOTES, 'UTF-8') : 'Not loaded yet — open the editor to create defaults.' ?></p>
      <p class="dashboard-muted">Last updated: <?= htmlspecialchars($fmtTime($companyUpdated), ENT_QUOTES, 'UTF-8') ?></p>
    </div>
  </section>

  <section class="dashboard-panel dashboard-panel--about" aria-labelledby="dash-about-heading">
    <header class="dashboard-panel-head">
      <div class="dashboard-panel-icon" aria-hidden="true"><i class="fas fa-circle-info"></i></div>
      <div class="dashboard-panel-titles">
        <h3 id="dash-about-heading">About Us</h3>
        <p>Main story on <code>about-us.php</code></p>
      </div>
      <a class="btn btn-primary btn-sm dashboard-panel-cta" href="about_us_edit.php"><i class="fas fa-pen"></i> Edit</a>
    </header>
    <div class="dashboard-panel-body dashboard-panel-body--single">
      <p class="dashboard-blurb"><?= $aboutTitle !== '' ? htmlspecialchars($aboutTitle, ENT_QUOTES, 'UTF-8') : '—' ?></p>
      <p class="dashboard-muted">Last updated: <?= htmlspecialchars($fmtTime($aboutUpdated), ENT_QUOTES, 'UTF-8') ?></p>
    </div>
  </section>

  <section class="dashboard-panel dashboard-panel--faq" aria-labelledby="dash-faq-heading">
    <header class="dashboard-panel-head">
      <div class="dashboard-panel-icon" aria-hidden="true"><i class="fas fa-circle-question"></i></div>
      <div class="dashboard-panel-titles">
        <h3 id="dash-faq-heading">FAQ</h3>
        <p><?= (int) $nFaq ?> question<?= $nFaq === 1 ? '' : 's' ?> · <?= (int) $nFaqActive ?> visible on site</p>
      </div>
    </header>
    <div class="dashboard-tiles dashboard-tiles--duo">
      <a class="dashboard-tile" href="faq_add.php">
        <span class="dashboard-tile-icon"><i class="fas fa-plus"></i></span>
        <span class="dashboard-tile-label">Add</span>
        <span class="dashboard-tile-meta">New entry</span>
      </a>
      <a class="dashboard-tile" href="faq_view.php">
        <span class="dashboard-tile-icon"><i class="fas fa-eye"></i></span>
        <span class="dashboard-tile-label">View all</span>
        <span class="dashboard-tile-meta">Manage list</span>
      </a>
    </div>
  </section>

  <section class="dashboard-panel dashboard-panel--testimonial" aria-labelledby="dash-test-heading">
    <header class="dashboard-panel-head">
      <div class="dashboard-panel-icon" aria-hidden="true"><i class="fas fa-quote-left"></i></div>
      <div class="dashboard-panel-titles">
        <h3 id="dash-test-heading">Testimonial</h3>
        <p><?= (int) $nTestimonials ?> quote<?= $nTestimonials === 1 ? '' : 's' ?> on the homepage block</p>
      </div>
    </header>
    <div class="dashboard-tiles dashboard-tiles--duo">
      <a class="dashboard-tile" href="home_edit.php?s=testimonials&amp;sub=add">
        <span class="dashboard-tile-icon"><i class="fas fa-plus"></i></span>
        <span class="dashboard-tile-label">Add</span>
        <span class="dashboard-tile-meta">New quote</span>
      </a>
      <a class="dashboard-tile" href="home_edit.php?s=testimonials">
        <span class="dashboard-tile-icon"><i class="fas fa-eye"></i></span>
        <span class="dashboard-tile-label">View</span>
        <span class="dashboard-tile-meta">Reorder &amp; edit</span>
      </a>
    </div>
  </section>

  <section class="dashboard-panel dashboard-panel--gallery" aria-labelledby="dash-gal-heading">
    <header class="dashboard-panel-head">
      <div class="dashboard-panel-icon" aria-hidden="true"><i class="fas fa-images"></i></div>
      <div class="dashboard-panel-titles">
        <h3 id="dash-gal-heading">Gallery</h3>
        <p><?= (int) $nGalleryFields ?> configured field<?= $nGalleryFields === 1 ? '' : 's' ?> in the gallery strip</p>
      </div>
    </header>
    <div class="dashboard-tiles dashboard-tiles--duo">
      <a class="dashboard-tile" href="home_edit.php?s=gallery">
        <span class="dashboard-tile-icon"><i class="fas fa-sliders"></i></span>
        <span class="dashboard-tile-label">Edit content</span>
        <span class="dashboard-tile-meta">Copy &amp; images</span>
      </a>
      <a class="dashboard-tile" href="../index.php#gallery" target="_blank" rel="noopener">
        <span class="dashboard-tile-icon"><i class="fas fa-external-link-alt"></i></span>
        <span class="dashboard-tile-label">View on site</span>
        <span class="dashboard-tile-meta">Live section</span>
      </a>
    </div>
  </section>

  <section class="dashboard-panel dashboard-panel--packages" aria-labelledby="dash-pkg-heading">
    <header class="dashboard-panel-head">
      <div class="dashboard-panel-icon" aria-hidden="true"><i class="fas fa-box-open"></i></div>
      <div class="dashboard-panel-titles">
        <h3 id="dash-pkg-heading">Packages</h3>
        <p><?= (int) $nPackages ?> total · <?= (int) $nPackagesActive ?> published</p>
      </div>
    </header>
    <div class="dashboard-tiles dashboard-tiles--duo">
      <a class="dashboard-tile" href="packages_master.php?sub=add">
        <span class="dashboard-tile-icon"><i class="fas fa-plus"></i></span>
        <span class="dashboard-tile-label">Add</span>
        <span class="dashboard-tile-meta">New package</span>
      </a>
      <a class="dashboard-tile" href="packages_master.php">
        <span class="dashboard-tile-icon"><i class="fas fa-eye"></i></span>
        <span class="dashboard-tile-label">View</span>
        <span class="dashboard-tile-meta">Full list</span>
      </a>
    </div>
  </section>

  <section class="dashboard-panel dashboard-panel--portfolio" aria-labelledby="dash-port-heading">
    <header class="dashboard-panel-head">
      <div class="dashboard-panel-icon" aria-hidden="true"><i class="fas fa-briefcase"></i></div>
      <div class="dashboard-panel-titles">
        <h3 id="dash-port-heading">Portfolio</h3>
        <p><?= (int) $nPortfolio ?> item<?= $nPortfolio === 1 ? '' : 's' ?> · <?= (int) $nPortfolioActive ?> active</p>
      </div>
    </header>
    <div class="dashboard-tiles dashboard-tiles--duo">
      <a class="dashboard-tile" href="portfolio_items.php?sub=add">
        <span class="dashboard-tile-icon"><i class="fas fa-plus"></i></span>
        <span class="dashboard-tile-label">Add</span>
        <span class="dashboard-tile-meta">New clip</span>
      </a>
      <a class="dashboard-tile" href="portfolio_items.php">
        <span class="dashboard-tile-icon"><i class="fas fa-eye"></i></span>
        <span class="dashboard-tile-label">View</span>
        <span class="dashboard-tile-meta">Manage</span>
      </a>
    </div>
  </section>

  <section class="dashboard-panel dashboard-panel--blogs" aria-labelledby="dash-blog-heading">
    <header class="dashboard-panel-head">
      <div class="dashboard-panel-icon" aria-hidden="true"><i class="fas fa-newspaper"></i></div>
      <div class="dashboard-panel-titles">
        <h3 id="dash-blog-heading">Blogs</h3>
        <p><?= (int) $nBlogs ?> post<?= $nBlogs === 1 ? '' : 's' ?> · <?= (int) $nBlogsActive ?> visible</p>
      </div>
    </header>
    <div class="dashboard-tiles dashboard-tiles--duo">
      <a class="dashboard-tile" href="blogs.php?sub=add">
        <span class="dashboard-tile-icon"><i class="fas fa-plus"></i></span>
        <span class="dashboard-tile-label">Add</span>
        <span class="dashboard-tile-meta">New article</span>
      </a>
      <a class="dashboard-tile" href="blogs.php">
        <span class="dashboard-tile-icon"><i class="fas fa-eye"></i></span>
        <span class="dashboard-tile-label">View</span>
        <span class="dashboard-tile-meta">All posts</span>
      </a>
    </div>
  </section>

  <section class="dashboard-panel dashboard-panel--nav" aria-labelledby="dash-nav-heading">
    <header class="dashboard-panel-head">
      <div class="dashboard-panel-icon" aria-hidden="true"><i class="fas fa-bars"></i></div>
      <div class="dashboard-panel-titles">
        <h3 id="dash-nav-heading">Header navigation</h3>
        <p>Top menu links (not grouped in the sidebar, but part of the public header)</p>
      </div>
      <a class="btn btn-primary btn-sm dashboard-panel-cta" href="menus.php"><i class="fas fa-pen"></i> Edit menus</a>
    </header>
    <div class="dashboard-panel-body dashboard-panel-body--single">
      <p class="dashboard-blurb"><strong><?= (int) $menuActive ?></strong> of <strong><?= (int) $menuCount ?></strong> links are turned on for visitors.</p>
    </div>
  </section>

</div>

<?php require __DIR__ . '/includes/layout_footer.php'; ?>
