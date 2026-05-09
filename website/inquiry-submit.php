<?php
include 'config.php';

if ($_SERVER["REQUEST_METHOD"] == "POST") {

    $package_name = mysqli_real_escape_string($conn, $_POST['PkgName'] ?? '');
    $name         = mysqli_real_escape_string($conn, $_POST['name'] ?? '');
    $phone        = mysqli_real_escape_string($conn, $_POST['phone'] ?? '');
    $email        = mysqli_real_escape_string($conn, $_POST['email'] ?? '');
    $subject      = mysqli_real_escape_string($conn, $_POST['subject'] ?? '');
    $message      = mysqli_real_escape_string($conn, $_POST['message'] ?? '');
    $address      = mysqli_real_escape_string($conn, $_POST['address'] ?? '');
    $map_link     = mysqli_real_escape_string($conn, $_POST['map'] ?? '');

    $sql = "INSERT INTO tbl_breakfast_inquiries 
            (package_name, name, phone, email, subject, message, address, map_link)
            VALUES 
            ('$package_name', '$name', '$phone', '$email', '$subject', '$message', '$address', '$map_link')";

    if (mysqli_query($conn, $sql)) {
        echo "<script>
                alert('Thank you! Your enquiry has been sent.');
                window.location.href = document.referrer;
              </script>";
    } else {
        echo "<script>
                alert('Something went wrong. Please try again.');
                window.history.back();
              </script>";
    }
}
?>
