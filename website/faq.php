<?php
require_once __DIR__ . '/config.php';
require_once __DIR__ . '/includes/faqs.php';
$faqs = fruitwala_get_faqs($conn);
$faqCount = count($faqs);
$mid = $faqCount > 0 ? (int) ceil($faqCount / 2) : 0;
$faqsLeft = $mid > 0 ? array_slice($faqs, 0, $mid) : [];
$faqsRight = $mid > 0 ? array_slice($faqs, $mid) : [];
include 'header.php';
?>
            <!-- main body start -->
            <main>
                
                <!-- Breadcrumb section start -->
                <section class="breadcrumb_sec_1 position-relative">
                    <div class="breadcrumb_wrap sec_space_mid_small" style="background-image: url(assets/images/breadcrumb/bg.jpg);">
                        <div class="breadcrumb_cont text-center">
                            <div class="breadcrumb_title">
                                <h2 class="text-white">FAQ</h2>
                            </div>
                            <ul class="list-unstyled breadcrumb_item d-flex justify-content-center align-items-center text-white">
                                <li><a href="index.php"><i class="fas fa-home active"></i>Home</a></li>
                                <li><i class="fas fa-chevron-right"></i>FAQ</li>
                            </ul>
                        </div>
                    </div>
                </section>
                <!-- Breadcrumb section end -->

                <section class="faqs_section sec_space_small" data-aos="fade-up" data-aos-duration="2000">
    <div class="faqs_sec_wrap">
        <div class="container">
            <div class="row">
                <div class="col-lg-6">
                    <div class="faqs_sec_cont1">
                        <div class="accordion" id="accordionFaqLeft">
                            <?php foreach ($faqsLeft as $idx => $item): ?>
                              <?php
                                $fid = (int) $item['id'];
                                $isFirst = $idx === 0;
                                $headingId = 'headingFaq' . $fid;
                                $collapseId = 'collapseFaq' . $fid;
                              ?>
                            <div class="accordion-item<?= $idx > 0 ? ' mt-2' : '' ?>">
                                <h2 class="accordion-header" id="<?= htmlspecialchars($headingId, ENT_QUOTES, 'UTF-8') ?>">
                                    <button class="accordion-button<?= $isFirst ? '' : ' collapsed' ?>" type="button" data-bs-toggle="collapse"
                                        data-bs-target="#<?= htmlspecialchars($collapseId, ENT_QUOTES, 'UTF-8') ?>" aria-expanded="<?= $isFirst ? 'true' : 'false' ?>"
                                        aria-controls="<?= htmlspecialchars($collapseId, ENT_QUOTES, 'UTF-8') ?>">
                                        <?= htmlspecialchars((string) $item['question'], ENT_QUOTES, 'UTF-8') ?>
                                    </button>
                                </h2>
                                <div id="<?= htmlspecialchars($collapseId, ENT_QUOTES, 'UTF-8') ?>" class="accordion-collapse collapse<?= $isFirst ? ' show' : '' ?>"
                                    aria-labelledby="<?= htmlspecialchars($headingId, ENT_QUOTES, 'UTF-8') ?>" data-bs-parent="#accordionFaqLeft">
                                    <div class="accordion-body">
                                        <?= nl2br(htmlspecialchars((string) $item['answer'], ENT_QUOTES, 'UTF-8')) ?>
                                    </div>
                                </div>
                            </div>
                            <?php endforeach; ?>
                        </div>
                    </div>
                </div>

                <div class="col-lg-6">
                    <div class="faqs_sec_cont2">
                        <div class="accordion" id="accordionFaqRight">
                            <?php foreach ($faqsRight as $idx => $item): ?>
                              <?php
                                $fid = (int) $item['id'];
                                $isFirst = $idx === 0;
                                $headingId = 'headingFaq' . $fid;
                                $collapseId = 'collapseFaq' . $fid;
                              ?>
                            <div class="accordion-item<?= $idx > 0 ? ' mt-2' : '' ?>">
                                <h2 class="accordion-header" id="<?= htmlspecialchars($headingId, ENT_QUOTES, 'UTF-8') ?>">
                                    <button class="accordion-button<?= $isFirst ? '' : ' collapsed' ?>" type="button" data-bs-toggle="collapse"
                                        data-bs-target="#<?= htmlspecialchars($collapseId, ENT_QUOTES, 'UTF-8') ?>" aria-expanded="<?= $isFirst ? 'true' : 'false' ?>"
                                        aria-controls="<?= htmlspecialchars($collapseId, ENT_QUOTES, 'UTF-8') ?>">
                                        <?= htmlspecialchars((string) $item['question'], ENT_QUOTES, 'UTF-8') ?>
                                    </button>
                                </h2>
                                <div id="<?= htmlspecialchars($collapseId, ENT_QUOTES, 'UTF-8') ?>" class="accordion-collapse collapse<?= $isFirst ? ' show' : '' ?>"
                                    aria-labelledby="<?= htmlspecialchars($headingId, ENT_QUOTES, 'UTF-8') ?>" data-bs-parent="#accordionFaqRight">
                                    <div class="accordion-body">
                                        <?= nl2br(htmlspecialchars((string) $item['answer'], ENT_QUOTES, 'UTF-8')) ?>
                                    </div>
                                </div>
                            </div>
                            <?php endforeach; ?>
                        </div>
                    </div>
                </div>

            </div>
        </div>
    </div>
</section>

        </main>

<?php include 'footer.php'; ?>
