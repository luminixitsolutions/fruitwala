<?php
declare(strict_types=1);

/**
 * Result of processing testimonial photo upload from $_FILES['testimonial_photo'].
 *
 * @return array{path: ?string, error: ?string} path when a new file was saved; error when upload failed
 */
function fruitwala_admin_save_testimonial_photo_upload(): array
{
    if (empty($_FILES['testimonial_photo']) || !is_array($_FILES['testimonial_photo'])) {
        return ['path' => null, 'error' => null];
    }
    $file = $_FILES['testimonial_photo'];
    $err = (int) ($file['error'] ?? UPLOAD_ERR_NO_FILE);
    if ($err === UPLOAD_ERR_NO_FILE) {
        return ['path' => null, 'error' => null];
    }
    if ($err !== UPLOAD_ERR_OK) {
        return ['path' => null, 'error' => 'Photo upload failed (error ' . $err . ').'];
    }

    $tmp = (string) ($file['tmp_name'] ?? '');
    if ($tmp === '' || !is_uploaded_file($tmp)) {
        return ['path' => null, 'error' => 'Invalid upload.'];
    }

    $maxBytes = 5 * 1024 * 1024;
    $size = (int) ($file['size'] ?? 0);
    if ($size <= 0 || $size > $maxBytes) {
        return ['path' => null, 'error' => 'Photo must be between 1 byte and 5 MB.'];
    }

    $finfo = new finfo(FILEINFO_MIME_TYPE);
    $mime = $finfo->file($tmp);
    $map = [
        'image/jpeg' => 'jpg',
        'image/png' => 'png',
        'image/gif' => 'gif',
        'image/webp' => 'webp',
    ];
    if ($mime === false || !isset($map[$mime])) {
        return ['path' => null, 'error' => 'Please upload a JPEG, PNG, GIF, or WebP image.'];
    }

    $ext = $map[$mime];
    $siteRoot = dirname(__DIR__, 2);
    $relDir = 'uploads/testimonials';
    $absDir = $siteRoot . DIRECTORY_SEPARATOR . str_replace('/', DIRECTORY_SEPARATOR, $relDir);
    if (!is_dir($absDir) && !@mkdir($absDir, 0755, true)) {
        return ['path' => null, 'error' => 'Could not create upload folder.'];
    }

    $basename = bin2hex(random_bytes(16)) . '.' . $ext;
    $absPath = $absDir . DIRECTORY_SEPARATOR . $basename;
    if (!move_uploaded_file($tmp, $absPath)) {
        return ['path' => null, 'error' => 'Could not save the photo.'];
    }

    @chmod($absPath, 0644);

    return ['path' => $relDir . '/' . $basename, 'error' => null];
}

/**
 * Remove a testimonial image stored under uploads/testimonials/ (safe path only).
 */
function fruitwala_admin_remove_testimonial_upload_file(?string $relativePath): void
{
    if ($relativePath === null || $relativePath === '') {
        return;
    }
    $relativePath = str_replace('\\', '/', $relativePath);
    if (strpos($relativePath, '..') !== false) {
        return;
    }
    if (strpos($relativePath, 'uploads/testimonials/') !== 0) {
        return;
    }
    $siteRoot = dirname(__DIR__, 2);
    $full = $siteRoot . '/' . $relativePath;
    $realDir = realpath($siteRoot . '/uploads/testimonials');
    $realFile = realpath($full);
    if ($realDir === false || $realFile === false) {
        return;
    }
    $prefix = $realDir . DIRECTORY_SEPARATOR;
    if (strncmp($realFile, $prefix, strlen($prefix)) !== 0) {
        return;
    }
    if (is_file($realFile)) {
        @unlink($realFile);
    }
}

/**
 * Featured image for blog posts from $_FILES['blog_featured'].
 *
 * @return array{path: ?string, error: ?string}
 */
function fruitwala_admin_save_blog_featured_upload(): array
{
    if (empty($_FILES['blog_featured']) || !is_array($_FILES['blog_featured'])) {
        return ['path' => null, 'error' => null];
    }
    $file = $_FILES['blog_featured'];
    $err = (int) ($file['error'] ?? UPLOAD_ERR_NO_FILE);
    if ($err === UPLOAD_ERR_NO_FILE) {
        return ['path' => null, 'error' => null];
    }
    if ($err !== UPLOAD_ERR_OK) {
        return ['path' => null, 'error' => 'Image upload failed (error ' . $err . ').'];
    }

    $tmp = (string) ($file['tmp_name'] ?? '');
    if ($tmp === '' || !is_uploaded_file($tmp)) {
        return ['path' => null, 'error' => 'Invalid upload.'];
    }

    $maxBytes = 5 * 1024 * 1024;
    $size = (int) ($file['size'] ?? 0);
    if ($size <= 0 || $size > $maxBytes) {
        return ['path' => null, 'error' => 'Image must be between 1 byte and 5 MB.'];
    }

    $finfo = new finfo(FILEINFO_MIME_TYPE);
    $mime = $finfo->file($tmp);
    $map = [
        'image/jpeg' => 'jpg',
        'image/png' => 'png',
        'image/gif' => 'gif',
        'image/webp' => 'webp',
    ];
    if ($mime === false || !isset($map[$mime])) {
        return ['path' => null, 'error' => 'Please upload a JPEG, PNG, GIF, or WebP image.'];
    }

    $ext = $map[$mime];
    $siteRoot = dirname(__DIR__, 2);
    $relDir = 'uploads/blogs';
    $absDir = $siteRoot . DIRECTORY_SEPARATOR . str_replace('/', DIRECTORY_SEPARATOR, $relDir);
    if (!is_dir($absDir) && !@mkdir($absDir, 0755, true)) {
        return ['path' => null, 'error' => 'Could not create upload folder.'];
    }

    $basename = bin2hex(random_bytes(16)) . '.' . $ext;
    $absPath = $absDir . DIRECTORY_SEPARATOR . $basename;
    if (!move_uploaded_file($tmp, $absPath)) {
        return ['path' => null, 'error' => 'Could not save the image.'];
    }

    @chmod($absPath, 0644);

    return ['path' => $relDir . '/' . $basename, 'error' => null];
}

/**
 * Remove a blog image stored under uploads/blogs/ (safe path only).
 */
function fruitwala_admin_remove_blog_upload_file(?string $relativePath): void
{
    if ($relativePath === null || $relativePath === '') {
        return;
    }
    $relativePath = str_replace('\\', '/', $relativePath);
    if (strpos($relativePath, '..') !== false) {
        return;
    }
    if (strpos($relativePath, 'uploads/blogs/') !== 0) {
        return;
    }
    $siteRoot = dirname(__DIR__, 2);
    $full = $siteRoot . '/' . $relativePath;
    $realDir = realpath($siteRoot . '/uploads/blogs');
    $realFile = realpath($full);
    if ($realDir === false || $realFile === false) {
        return;
    }
    $prefix = $realDir . DIRECTORY_SEPARATOR;
    if (strncmp($realFile, $prefix, strlen($prefix)) !== 0) {
        return;
    }
    if (is_file($realFile)) {
        @unlink($realFile);
    }
}

/**
 * Cover image for portfolio reel cards from $_FILES['portfolio_cover'].
 *
 * @return array{path: ?string, error: ?string}
 */
function fruitwala_admin_save_portfolio_cover_upload(): array
{
    if (empty($_FILES['portfolio_cover']) || !is_array($_FILES['portfolio_cover'])) {
        return ['path' => null, 'error' => null];
    }
    $file = $_FILES['portfolio_cover'];
    $err = (int) ($file['error'] ?? UPLOAD_ERR_NO_FILE);
    if ($err === UPLOAD_ERR_NO_FILE) {
        return ['path' => null, 'error' => null];
    }
    if ($err !== UPLOAD_ERR_OK) {
        return ['path' => null, 'error' => 'Cover image upload failed (error ' . $err . ').'];
    }

    $tmp = (string) ($file['tmp_name'] ?? '');
    if ($tmp === '' || !is_uploaded_file($tmp)) {
        return ['path' => null, 'error' => 'Invalid upload.'];
    }

    $maxBytes = 5 * 1024 * 1024;
    $size = (int) ($file['size'] ?? 0);
    if ($size <= 0 || $size > $maxBytes) {
        return ['path' => null, 'error' => 'Cover image must be between 1 byte and 5 MB.'];
    }

    $finfo = new finfo(FILEINFO_MIME_TYPE);
    $mime = $finfo->file($tmp);
    $map = [
        'image/jpeg' => 'jpg',
        'image/png' => 'png',
        'image/gif' => 'gif',
        'image/webp' => 'webp',
    ];
    if ($mime === false || !isset($map[$mime])) {
        return ['path' => null, 'error' => 'Please upload a JPEG, PNG, GIF, or WebP cover image.'];
    }

    $ext = $map[$mime];
    $siteRoot = dirname(__DIR__, 2);
    $relDir = 'uploads/portfolio';
    $absDir = $siteRoot . DIRECTORY_SEPARATOR . str_replace('/', DIRECTORY_SEPARATOR, $relDir);
    if (!is_dir($absDir) && !@mkdir($absDir, 0755, true)) {
        return ['path' => null, 'error' => 'Could not create upload folder.'];
    }

    $basename = bin2hex(random_bytes(16)) . '.' . $ext;
    $absPath = $absDir . DIRECTORY_SEPARATOR . $basename;
    if (!move_uploaded_file($tmp, $absPath)) {
        return ['path' => null, 'error' => 'Could not save the cover image.'];
    }

    @chmod($absPath, 0644);

    return ['path' => $relDir . '/' . $basename, 'error' => null];
}

/**
 * Remove a portfolio cover stored under uploads/portfolio/ (safe path only).
 */
function fruitwala_admin_remove_portfolio_upload_file(?string $relativePath): void
{
    if ($relativePath === null || $relativePath === '') {
        return;
    }
    $relativePath = str_replace('\\', '/', $relativePath);
    if (strpos($relativePath, '..') !== false) {
        return;
    }
    if (strpos($relativePath, 'uploads/portfolio/') !== 0) {
        return;
    }
    $siteRoot = dirname(__DIR__, 2);
    $full = $siteRoot . '/' . $relativePath;
    $realDir = realpath($siteRoot . '/uploads/portfolio');
    $realFile = realpath($full);
    if ($realDir === false || $realFile === false) {
        return;
    }
    $prefix = $realDir . DIRECTORY_SEPARATOR;
    if (strncmp($realFile, $prefix, strlen($prefix)) !== 0) {
        return;
    }
    if (is_file($realFile)) {
        @unlink($realFile);
    }
}

/**
 * Package card image from $_FILES['package_image'].
 *
 * @return array{path: ?string, error: ?string}
 */
function fruitwala_admin_save_package_image_upload(): array
{
    if (empty($_FILES['package_image']) || !is_array($_FILES['package_image'])) {
        return ['path' => null, 'error' => null];
    }
    $file = $_FILES['package_image'];
    $err = (int) ($file['error'] ?? UPLOAD_ERR_NO_FILE);
    if ($err === UPLOAD_ERR_NO_FILE) {
        return ['path' => null, 'error' => null];
    }
    if ($err !== UPLOAD_ERR_OK) {
        return ['path' => null, 'error' => 'Image upload failed (error ' . $err . ').'];
    }

    $tmp = (string) ($file['tmp_name'] ?? '');
    if ($tmp === '' || !is_uploaded_file($tmp)) {
        return ['path' => null, 'error' => 'Invalid upload.'];
    }

    $maxBytes = 5 * 1024 * 1024;
    $size = (int) ($file['size'] ?? 0);
    if ($size <= 0 || $size > $maxBytes) {
        return ['path' => null, 'error' => 'Image must be between 1 byte and 5 MB.'];
    }

    $finfo = new finfo(FILEINFO_MIME_TYPE);
    $mime = $finfo->file($tmp);
    $map = [
        'image/jpeg' => 'jpg',
        'image/png' => 'png',
        'image/gif' => 'gif',
        'image/webp' => 'webp',
    ];
    if ($mime === false || !isset($map[$mime])) {
        return ['path' => null, 'error' => 'Please upload a JPEG, PNG, GIF, or WebP image.'];
    }

    $ext = $map[$mime];
    $siteRoot = dirname(__DIR__, 2);
    $relDir = 'uploads/packages';
    $absDir = $siteRoot . DIRECTORY_SEPARATOR . str_replace('/', DIRECTORY_SEPARATOR, $relDir);
    if (!is_dir($absDir) && !@mkdir($absDir, 0755, true)) {
        return ['path' => null, 'error' => 'Could not create upload folder.'];
    }

    $basename = bin2hex(random_bytes(16)) . '.' . $ext;
    $absPath = $absDir . DIRECTORY_SEPARATOR . $basename;
    if (!move_uploaded_file($tmp, $absPath)) {
        return ['path' => null, 'error' => 'Could not save the image.'];
    }

    @chmod($absPath, 0644);

    return ['path' => $relDir . '/' . $basename, 'error' => null];
}

/**
 * Remove a package image stored under uploads/packages/ (safe path only).
 */
function fruitwala_admin_remove_package_upload_file(?string $relativePath): void
{
    if ($relativePath === null || $relativePath === '') {
        return;
    }
    $relativePath = str_replace('\\', '/', $relativePath);
    if (strpos($relativePath, '..') !== false) {
        return;
    }
    if (strpos($relativePath, 'uploads/packages/') !== 0) {
        return;
    }
    $siteRoot = dirname(__DIR__, 2);
    $full = $siteRoot . '/' . $relativePath;
    $realDir = realpath($siteRoot . '/uploads/packages');
    $realFile = realpath($full);
    if ($realDir === false || $realFile === false) {
        return;
    }
    $prefix = $realDir . DIRECTORY_SEPARATOR;
    if (strncmp($realFile, $prefix, strlen($prefix)) !== 0) {
        return;
    }
    if (is_file($realFile)) {
        @unlink($realFile);
    }
}
