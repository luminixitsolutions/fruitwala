<?php
declare(strict_types=1);
/** @var string $pageTitle */
/** @var string $activeNav dashboard|menus|home|about|company|change_password|faq|testimonial|gallery|packages|blogs|portfolio */
/** @var string $faqAdminSub add|view when $activeNav is faq (faq_add.php / faq_view.php) */
/** @var string $activeHomeSlug editor slug when on home_edit.php */
$pageTitle = $pageTitle ?? 'Dashboard';
$activeNav = $activeNav ?? 'dashboard';
$activeHomeSlug = $activeHomeSlug ?? '';
$tSub = (string) ($_GET['sub'] ?? '');
$testimonialsOnForm = ($activeHomeSlug === 'testimonials' && in_array($tSub, ['add', 'edit'], true));
$testimonialNavOpen = ($activeNav === 'testimonial');
$galleryNavOpen = ($activeNav === 'gallery');
$homeNavOpen = ($activeNav === 'home');
$pkgSub = (string) ($_GET['sub'] ?? '');
$packagesOnForm = ($activeNav === 'packages' && in_array($pkgSub, ['add', 'edit'], true));
$packagesNavOpen = ($activeNav === 'packages');
$blogSub = (string) ($_GET['sub'] ?? '');
$blogsOnForm = ($activeNav === 'blogs' && in_array($blogSub, ['add', 'edit'], true));
$blogsNavOpen = ($activeNav === 'blogs');
$portfolioNavOpen = ($activeNav === 'portfolio');
$portfolioOnForm = ($activeNav === 'portfolio' && in_array($tSub, ['add', 'edit'], true));
$faqAdminSub = $faqAdminSub ?? '';
$faqNavOpen = ($activeNav === 'faq');
$settingsNavOpen = ($activeNav === 'company' || $activeNav === 'change_password');
?>
<!DOCTYPE html>
<html lang="en">
<head>
  <meta charset="UTF-8">
  <meta name="viewport" content="width=device-width, initial-scale=1.0">
  <title><?= htmlspecialchars($pageTitle, ENT_QUOTES, 'UTF-8') ?> — Fruitwala Admin</title>
  <link rel="preconnect" href="https://fonts.googleapis.com">
  <link rel="preconnect" href="https://fonts.gstatic.com" crossorigin>
  <link href="https://fonts.googleapis.com/css2?family=Montserrat:wght@400;500;600;700;800&display=swap" rel="stylesheet">
  <link rel="stylesheet" href="https://cdnjs.cloudflare.com/ajax/libs/font-awesome/6.5.0/css/all.min.css">
  <link rel="stylesheet" href="assets/admin.css">
</head>
<body class="admin-body">
<div class="admin-layout">
  <aside class="admin-sidebar">
    <div class="admin-brand">
      <a href="index.php" class="admin-brand-mark"><img src="../logo.png" alt="Fruitwala" class="admin-brand-logo" width="200" height="56" decoding="async"></a>
      <span>Website admin</span>
    </div>
    <nav class="admin-nav">
      <a class="<?= $activeNav === 'dashboard' ? 'active' : '' ?>" href="index.php"><i class="fas fa-gauge-high"></i> Dashboard</a>

      <details class="admin-nav-dropdown"<?= $homeNavOpen ? ' open' : '' ?>>
        <summary class="admin-nav-dropdown-summary">
          <span class="admin-nav-dropdown-title"><i class="fas fa-house"></i> Home page</span>
          <i class="fas fa-chevron-down admin-nav-dropdown-chevron" aria-hidden="true"></i>
        </summary>
        <div class="admin-nav-dropdown-body">

          <a class="admin-nav-sub<?= $activeHomeSlug === 'hero' ? ' active' : '' ?>" href="home_edit.php?s=hero"><i class="fas fa-panorama admin-nav-sub-icon" aria-hidden="true"></i><span class="admin-nav-sub-label">Hero / banner</span></a>
          <a class="admin-nav-sub<?= $activeHomeSlug === 'reels' ? ' active' : '' ?>" href="home_edit.php?s=reels"><i class="fas fa-film admin-nav-sub-icon" aria-hidden="true"></i><span class="admin-nav-sub-label">Featured reels</span></a>
          <a class="admin-nav-sub<?= $activeHomeSlug === 'quality' ? ' active' : '' ?>" href="home_edit.php?s=quality"><i class="fas fa-circle-check admin-nav-sub-icon" aria-hidden="true"></i><span class="admin-nav-sub-label">Why choose</span></a>
          <a class="admin-nav-sub<?= $activeHomeSlug === 'sale_banners' ? ' active' : '' ?>" href="home_edit.php?s=sale_banners"><i class="fas fa-tags admin-nav-sub-icon" aria-hidden="true"></i><span class="admin-nav-sub-label">Sale banners</span></a>
          <a class="admin-nav-sub<?= $activeHomeSlug === 'product_ctg' ? ' active' : '' ?>" href="home_edit.php?s=product_ctg"><i class="fas fa-gift admin-nav-sub-icon" aria-hidden="true"></i><span class="admin-nav-sub-label">Offers block</span></a>
          <a class="admin-nav-sub<?= $activeHomeSlug === 'services' ? ' active' : '' ?>" href="home_edit.php?s=services"><i class="fas fa-truck-fast admin-nav-sub-icon" aria-hidden="true"></i><span class="admin-nav-sub-label">Service row</span></a>
          <a class="admin-nav-sub<?= $activeHomeSlug === 'instagram' ? ' active' : '' ?>" href="home_edit.php?s=instagram"><i class="fab fa-instagram admin-nav-sub-icon" aria-hidden="true"></i><span class="admin-nav-sub-label">Instagram</span></a>
        </div>
      </details>

      <a class="<?= $activeNav === 'about' ? 'active' : '' ?>" href="about_us_edit.php"><i class="fas fa-circle-info"></i> About Us</a>

      <details class="admin-nav-dropdown"<?= $faqNavOpen ? ' open' : '' ?>>
        <summary class="admin-nav-dropdown-summary">
          <span class="admin-nav-dropdown-title"><i class="fas fa-circle-question"></i> FAQ</span>
          <i class="fas fa-chevron-down admin-nav-dropdown-chevron" aria-hidden="true"></i>
        </summary>
        <div class="admin-nav-dropdown-body">
          <a class="admin-nav-sub<?= $faqAdminSub === 'add' ? ' active' : '' ?>" href="faq_add.php"><i class="fas fa-plus admin-nav-sub-icon" aria-hidden="true"></i><span class="admin-nav-sub-label">Add</span></a>
          <a class="admin-nav-sub<?= $faqAdminSub === 'view' ? ' active' : '' ?>" href="faq_view.php"><i class="fas fa-eye admin-nav-sub-icon" aria-hidden="true"></i><span class="admin-nav-sub-label">View</span></a>
        </div>
      </details>

      <details class="admin-nav-dropdown"<?= $testimonialNavOpen ? ' open' : '' ?>>
        <summary class="admin-nav-dropdown-summary">
          <span class="admin-nav-dropdown-title"><i class="fas fa-quote-left"></i> Testimonial</span>
          <i class="fas fa-chevron-down admin-nav-dropdown-chevron" aria-hidden="true"></i>
        </summary>
        <div class="admin-nav-dropdown-body">
          <a class="admin-nav-sub<?= $activeHomeSlug === 'testimonials' && $testimonialsOnForm ? ' active' : '' ?>" href="home_edit.php?s=testimonials&amp;sub=add"><i class="fas fa-plus admin-nav-sub-icon" aria-hidden="true"></i><span class="admin-nav-sub-label">Add</span></a>
          <a class="admin-nav-sub<?= $activeHomeSlug === 'testimonials' && !$testimonialsOnForm ? ' active' : '' ?>" href="home_edit.php?s=testimonials"><i class="fas fa-eye admin-nav-sub-icon" aria-hidden="true"></i><span class="admin-nav-sub-label">View</span></a>
        </div>
      </details>

      <details class="admin-nav-dropdown"<?= $galleryNavOpen ? ' open' : '' ?>>
        <summary class="admin-nav-dropdown-summary">
          <span class="admin-nav-dropdown-title"><i class="fas fa-images"></i> Gallery</span>
          <i class="fas fa-chevron-down admin-nav-dropdown-chevron" aria-hidden="true"></i>
        </summary>
        <div class="admin-nav-dropdown-body">
          <a class="admin-nav-sub<?= $activeHomeSlug === 'gallery' ? ' active' : '' ?>" href="home_edit.php?s=gallery"><i class="fas fa-plus admin-nav-sub-icon" aria-hidden="true"></i><span class="admin-nav-sub-label">Add</span></a>
          <a class="admin-nav-sub" href="../index.php#gallery" target="_blank" rel="noopener"><i class="fas fa-eye admin-nav-sub-icon" aria-hidden="true"></i><span class="admin-nav-sub-label">View</span></a>
        </div>
      </details>

      <details class="admin-nav-dropdown"<?= $packagesNavOpen ? ' open' : '' ?>>
        <summary class="admin-nav-dropdown-summary">
          <span class="admin-nav-dropdown-title"><i class="fas fa-box-open"></i> Packages</span>
          <i class="fas fa-chevron-down admin-nav-dropdown-chevron" aria-hidden="true"></i>
        </summary>
        <div class="admin-nav-dropdown-body">
          <a class="admin-nav-sub<?= $packagesOnForm ? ' active' : '' ?>" href="packages_master.php?sub=add"><i class="fas fa-plus admin-nav-sub-icon" aria-hidden="true"></i><span class="admin-nav-sub-label">Add</span></a>
          <a class="admin-nav-sub<?= $activeNav === 'packages' && !$packagesOnForm ? ' active' : '' ?>" href="packages_master.php"><i class="fas fa-eye admin-nav-sub-icon" aria-hidden="true"></i><span class="admin-nav-sub-label">View</span></a>
        </div>
      </details>

      <details class="admin-nav-dropdown"<?= $portfolioNavOpen ? ' open' : '' ?>>
        <summary class="admin-nav-dropdown-summary">
          <span class="admin-nav-dropdown-title"><i class="fas fa-briefcase"></i> Portfolio</span>
          <i class="fas fa-chevron-down admin-nav-dropdown-chevron" aria-hidden="true"></i>
        </summary>
        <div class="admin-nav-dropdown-body">
          <a class="admin-nav-sub<?= $portfolioOnForm ? ' active' : '' ?>" href="portfolio_items.php?sub=add"><i class="fas fa-plus admin-nav-sub-icon" aria-hidden="true"></i><span class="admin-nav-sub-label">Add</span></a>
          <a class="admin-nav-sub<?= $activeNav === 'portfolio' && !$portfolioOnForm ? ' active' : '' ?>" href="portfolio_items.php"><i class="fas fa-eye admin-nav-sub-icon" aria-hidden="true"></i><span class="admin-nav-sub-label">View</span></a>
        </div>
      </details>

      <details class="admin-nav-dropdown"<?= $blogsNavOpen ? ' open' : '' ?>>
        <summary class="admin-nav-dropdown-summary">
          <span class="admin-nav-dropdown-title"><i class="fas fa-newspaper"></i> Blogs</span>
          <i class="fas fa-chevron-down admin-nav-dropdown-chevron" aria-hidden="true"></i>
        </summary>
        <div class="admin-nav-dropdown-body">
          <a class="admin-nav-sub<?= $blogsOnForm ? ' active' : '' ?>" href="blogs.php?sub=add"><i class="fas fa-plus admin-nav-sub-icon" aria-hidden="true"></i><span class="admin-nav-sub-label">Add</span></a>
          <a class="admin-nav-sub<?= $activeNav === 'blogs' && !$blogsOnForm ? ' active' : '' ?>" href="blogs.php"><i class="fas fa-eye admin-nav-sub-icon" aria-hidden="true"></i><span class="admin-nav-sub-label">View</span></a>
        </div>
      </details>

      <details class="admin-nav-dropdown"<?= $settingsNavOpen ? ' open' : '' ?>>
        <summary class="admin-nav-dropdown-summary">
          <span class="admin-nav-dropdown-title"><i class="fas fa-gear"></i> Settings</span>
          <i class="fas fa-chevron-down admin-nav-dropdown-chevron" aria-hidden="true"></i>
        </summary>
        <div class="admin-nav-dropdown-body">
          <a class="admin-nav-sub<?= $activeNav === 'company' ? ' active' : '' ?>" href="company_profile_edit.php"><i class="fas fa-building admin-nav-sub-icon" aria-hidden="true"></i><span class="admin-nav-sub-label">Company profile</span></a>
          <a class="admin-nav-sub<?= $activeNav === 'change_password' ? ' active' : '' ?>" href="change_password.php"><i class="fas fa-key admin-nav-sub-icon" aria-hidden="true"></i><span class="admin-nav-sub-label">Change password</span></a>
        </div>
      </details>

      <a class="admin-nav-logout" href="logout.php"><i class="fas fa-right-from-bracket" aria-hidden="true"></i> Logout</a>
    </nav>
  </aside>
  <main class="admin-main">
