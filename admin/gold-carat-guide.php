<?php
session_start();
include_once 'config.php';
include_once 'auth.php';
$user_id = $_SESSION['Admin']['id'];
$MainPage = 'Gold';
$Page = 'Gold Karat Guide';

$gold_bar_img = 'https://media.istockphoto.com/id/1184141145/vector/one-gold-bar-or-ingot.jpg?s=612x612&w=0&k=20&c=TrVTJvRAg2AGdkg2Bc1tQKhZ1EQc23dNT_3AaVqeelM=';

$carats = [
  ['k' => 24, 'pct' => '99.9%', 'note' => 'Fine / pure gold'],
  ['k' => 23, 'pct' => '95.8%', 'note' => ''],
  ['k' => 22, 'pct' => '91.7%', 'note' => 'Common in India (916)'],
  ['k' => 21, 'pct' => '87.5%', 'note' => ''],
  ['k' => 20, 'pct' => '83.3%', 'note' => ''],
  ['k' => 18, 'pct' => '75.0%', 'note' => '750 hallmark'],
  ['k' => 16, 'pct' => '66.7%', 'note' => ''],
  ['k' => 15, 'pct' => '62.5%', 'note' => ''],
  ['k' => 14, 'pct' => '58.3%', 'note' => '585 (US / EU)'],
  ['k' => 12, 'pct' => '50.0%', 'note' => ''],
  ['k' => 10, 'pct' => '41.7%', 'note' => 'Minimum karat in US'],
  ['k' => 9, 'pct' => '37.5%', 'note' => 'UK minimum'],
];
?>
<!DOCTYPE html>
<html lang="en" class="default-style layout-fixed layout-navbar-fixed">
<head>
<title><?php echo $Proj_Title; ?> | Gold Karat Guide</title>
<meta charset="utf-8">
<meta http-equiv="X-UA-Compatible" content="IE=edge" />
<meta name="viewport" content="width=device-width, initial-scale=1.0, user-scalable=0, minimal-ui">
<?php include_once 'header_script.php'; ?>
<style>
.gold-carat-card {
  border-radius: 14px;
  overflow: hidden;
  border: 1px solid rgba(212, 175, 55, 0.35);
  background: linear-gradient(180deg, #fffef8 0%, #fff 100%);
  box-shadow: 0 6px 20px rgba(180, 140, 40, 0.12);
  transition: transform 0.2s ease, box-shadow 0.2s ease;
}
.gold-carat-card:hover {
  transform: translateY(-4px);
  box-shadow: 0 12px 28px rgba(180, 140, 40, 0.2);
}
.gold-carat-card .img-wrap {
  position: relative;
  background: #fff;
  padding: 1rem;
  text-align: center;
}
.gold-carat-card img {
  max-width: 100%;
  height: auto;
  max-height: 140px;
  object-fit: contain;
}
.gold-carat-badge {
  position: absolute;
  left: 50%;
  bottom: 12px;
  transform: translateX(-50%);
  background: linear-gradient(135deg, #c9a227, #f4e4a6, #c9a227);
  color: #3d2e0a;
  font-weight: 700;
  font-size: 1rem;
  padding: 6px 18px;
  border-radius: 999px;
  box-shadow: 0 2px 8px rgba(0,0,0,0.15);
  letter-spacing: 0.04em;
}
.gold-carat-body {
  padding: 1rem 1.1rem 1.15rem;
  border-top: 1px solid rgba(212, 175, 55, 0.2);
}
.gold-carat-body .pct {
  font-size: 1.05rem;
  font-weight: 600;
  color: #6b5420;
}
.gold-carat-body .note {
  font-size: 0.82rem;
  color: #888;
  margin-top: 4px;
  min-height: 1.25rem;
}
.gold-page-intro {
  background: linear-gradient(135deg, rgba(212, 175, 55, 0.12), rgba(255, 255, 255, 0.9));
  border: 1px solid rgba(212, 175, 55, 0.25);
  border-radius: 12px;
  padding: 1rem 1.25rem;
  margin-bottom: 1.5rem;
}
</style>
</head>
<body>

<div class="layout-wrapper layout-2">
<div class="layout-inner">

<?php include_once 'sidebar.php'; ?>

<div class="layout-container">
<?php include_once 'top_header.php'; ?>

<div class="layout-content">
<div class="container-fluid flex-grow-1 container-p-y">

<h4 class="font-weight-bold py-3 mb-2">Gold karat reference</h4>

<div class="gold-page-intro text-muted small mb-4">
  Each card uses the same gold-bar artwork with the karat label on top. Purity is the approximate gold content by weight (out of 24 parts).
</div>

<div class="row">
<?php foreach ($carats as $row) { ?>
  <div class="col-6 col-md-4 col-lg-3 mb-4">
    <div class="gold-carat-card h-100">
      <div class="img-wrap">
        <img src="<?php echo htmlspecialchars($gold_bar_img); ?>" alt="<?php echo (int)$row['k']; ?>K gold bar" loading="lazy" referrerpolicy="no-referrer">
        <span class="gold-carat-badge"><?php echo (int)$row['k']; ?>K</span>
      </div>
      <div class="gold-carat-body">
        <div class="pct"><?php echo htmlspecialchars($row['pct']); ?> gold</div>
        <?php if (!empty($row['note'])) { ?>
          <div class="note"><?php echo htmlspecialchars($row['note']); ?></div>
        <?php } else { ?>
          <div class="note">&nbsp;</div>
        <?php } ?>
      </div>
    </div>
  </div>
<?php } ?>
</div>

</div>
</div>

<?php include_once 'footer.php'; ?>

</div>

</div>

<div class="layout-overlay layout-sidenav-toggle"></div>
</div>

<?php include_once 'footer_script.php'; ?>
</body>
</html>
