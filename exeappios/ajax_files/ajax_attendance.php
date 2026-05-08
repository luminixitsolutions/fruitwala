<?php
session_start();
include_once '../config.php';
$user_id = $_SESSION['User']['id'];



// Function to get address from latitude and longitude
function getAddressFromCoordinates($latitude, $longitude) {
    if (empty($latitude) || empty($longitude)) {
        return "Address not available";
    }
    
    // Test completed - now using real Google Maps API
    
    // First, try Google Maps API using cURL for better reliability
    $google_api_key = "AIzaSyADZAncocVsQMiK8ebIDhli29nk5GWWydk";
    $google_url = "https://maps.googleapis.com/maps/api/geocode/json?latlng={$latitude},{$longitude}&key={$google_api_key}&language=en";
    
    // Try cURL first (more reliable for HTTPS)
    if (function_exists('curl_init')) {
        $ch = curl_init();
        curl_setopt($ch, CURLOPT_URL, $google_url);
        curl_setopt($ch, CURLOPT_RETURNTRANSFER, true);
        curl_setopt($ch, CURLOPT_TIMEOUT, 15);
        curl_setopt($ch, CURLOPT_USERAGENT, 'Mozilla/5.0 (compatible; AttendanceApp/1.0)');
        curl_setopt($ch, CURLOPT_HTTPHEADER, [
            'Accept: application/json',
            'Accept-Language: en-US,en;q=0.9'
        ]);
        curl_setopt($ch, CURLOPT_SSL_VERIFYPEER, false); // For development - remove in production
        curl_setopt($ch, CURLOPT_FOLLOWLOCATION, true);
        
        $response = curl_exec($ch);
        $http_code = curl_getinfo($ch, CURLINFO_HTTP_CODE);
        $curl_error = curl_error($ch);
        curl_close($ch);
        
        error_log("Google API cURL - URL: " . $google_url);
        error_log("Google API cURL - HTTP Code: " . $http_code);
        error_log("Google API cURL - Response Length: " . strlen($response));
        
        if ($curl_error) {
            error_log("Google API cURL Error: " . $curl_error);
        }
        
        if ($response !== false && $http_code === 200) {
            $data = json_decode($response, true);
            
            if (isset($data['status'])) {
                error_log("Google API Status: " . $data['status']);
                
                if ($data['status'] === 'OK' && isset($data['results']) && !empty($data['results'])) {
                    $result = $data['results'][0];
                    if (isset($result['formatted_address'])) {
                        $address = $result['formatted_address'];
                        error_log("Google API Address Found: " . $address);
                        
                        // Clean up the address
                        $address = str_replace(', India', '', $address);
                        $address = preg_replace('/,\s*,/', ',', $address);
                        $address = trim($address, ', ');
                        
                        return $address;
                    }
                } elseif ($data['status'] === 'REQUEST_DENIED') {
                    error_log("Google API Key Issue: " . ($data['error_message'] ?? 'Unknown error'));
                } elseif ($data['status'] === 'OVER_QUERY_LIMIT') {
                    error_log("Google API Quota Exceeded");
                } elseif ($data['status'] === 'ZERO_RESULTS') {
                    error_log("Google API: No results found for coordinates");
                }
            }
        }
    }
    
    // Fallback to file_get_contents if cURL fails
    $context = stream_context_create([
        'http' => [
            'header' => "User-Agent: Mozilla/5.0 (compatible; AttendanceApp/1.0)\r\n" .
                       "Accept: application/json\r\n",
            'timeout' => 15,
            'method' => 'GET'
        ]
    ]);
    
    $response = @file_get_contents($google_url, false, $context);
    if ($response !== false) {
        $data = json_decode($response, true);
        if (isset($data['status']) && $data['status'] === 'OK' && isset($data['results'][0]['formatted_address'])) {
            $address = $data['results'][0]['formatted_address'];
            $address = str_replace(', India', '', $address);
            $address = preg_replace('/,\s*,/', ',', $address);
            $address = trim($address, ', ');
            return $address;
        }
    }
    
    // Fallback to other services
    $fallback_services = [
        // OpenStreetMap Nominatim
        "https://nominatim.openstreetmap.org/reverse?format=json&lat={$latitude}&lon={$longitude}&zoom=18&addressdetails=1",
        // BigDataCloud
        "https://api.bigdatacloud.net/data/reverse-geocode-client?latitude={$latitude}&longitude={$longitude}&localityLanguage=en"
    ];
    
    foreach ($fallback_services as $index => $url) {
        $response = @file_get_contents($url, false, $context);
        
        if ($response !== false) {
            $data = json_decode($response, true);
            $address = '';
            
            if ($index == 0) { // Nominatim
                if (isset($data['display_name']) && !empty($data['display_name'])) {
                    $address = $data['display_name'];
                }
            } else { // BigDataCloud
                if (isset($data['locality']) || isset($data['city']) || isset($data['principalSubdivision'])) {
                    $parts = array_filter([
                        $data['locality'] ?? '',
                        $data['city'] ?? '',
                        $data['principalSubdivision'] ?? ''
                    ]);
                    $address = implode(', ', $parts);
                }
            }
            
            if (!empty($address)) {
                error_log("Fallback service {$index} provided address: " . $address);
                return $address;
            }
        }
        
        usleep(500000); // 0.5 second delay
    }
    
    // Final fallback to basic address
    error_log("All geocoding services failed, using basic address");
    return createBasicAddress($latitude, $longitude);
}

// Fallback function to create basic address info
function createBasicAddress($latitude, $longitude) {
    // Format coordinates to 4 decimal places
    $lat_formatted = number_format(abs($latitude), 4);
    $lon_formatted = number_format(abs($longitude), 4);
    
    // Enhanced geographical region detection for India
    $region = "Location";
    $state = "";
    
    if ($latitude >= 8 && $latitude <= 37 && $longitude >= 68 && $longitude <= 97) {
        // More specific location detection for Indian coordinates
        
        // Maharashtra region (where your coordinates 21.139005, 79.1262898 fall)
        if ($latitude >= 20.5 && $latitude <= 22 && $longitude >= 78.5 && $longitude <= 80) {
            $region = "Nagpur Area";
            $state = "Maharashtra";
        }
        // Other Maharashtra regions
        else if ($latitude >= 15.5 && $latitude <= 22 && $longitude >= 72 && $longitude <= 81) {
            $region = "Maharashtra";
            $state = "India";
        }
        // Delhi/NCR
        else if ($latitude >= 28 && $latitude <= 29 && $longitude >= 76.5 && $longitude <= 77.5) {
            $region = "Delhi NCR";
            $state = "India";
        }
        // Mumbai/Pune region
        else if ($latitude >= 18 && $latitude <= 20 && $longitude >= 72.5 && $longitude <= 74) {
            $region = "Mumbai-Pune";
            $state = "Maharashtra";
        }
        // Bangalore region
        else if ($latitude >= 12.5 && $latitude <= 13.5 && $longitude >= 77 && $longitude <= 78) {
            $region = "Bangalore";
            $state = "Karnataka";
        }
        // General regions
        else if ($latitude >= 20 && $latitude <= 30) {
            if ($longitude >= 70 && $longitude <= 80) {
                $region = "Western India";
            } else if ($longitude >= 80 && $longitude <= 90) {
                $region = "Central India";
            }
        } else if ($latitude >= 15 && $latitude <= 20) {
            $region = "Southern India";
        } else if ($latitude >= 25 && $latitude <= 35) {
            $region = "Northern India";
        }
    }
    
    $stateInfo = !empty($state) ? ", {$state}" : "";
    return "{$region}{$stateInfo} ({$lat_formatted}°N, {$lon_formatted}°E)";
}

// Function to get Google Maps static image
function getGoogleMapsImage($latitude, $longitude) {
    $google_api_key = "AIzaSyADZAncocVsQMiK8ebIDhli29nk5GWWydk";
    $map_url = "https://maps.googleapis.com/maps/api/staticmap?" .
               "center={$latitude},{$longitude}" .
               "&zoom=15" .
               "&size=100x70" .
               "&maptype=roadmap" .
               "&markers=color:red%7Clabel:●%7C{$latitude},{$longitude}" .
               "&style=feature:poi%7Cvisibility:off" .
               "&key={$google_api_key}";
    
    // Try to get the map image
    $context = stream_context_create([
        'http' => [
            'header' => "User-Agent: Mozilla/5.0 (compatible; AttendanceApp/1.0)\r\n",
            'timeout' => 10
        ]
    ]);
    
    $map_data = @file_get_contents($map_url, false, $context);
    
    if ($map_data !== false) {
        // Create image from the downloaded data
        $map_image = imagecreatefromstring($map_data);
        if ($map_image !== false) {
            return $map_image;
        }
    }
    
    return false;
}

function uploadImage($filename, $filesize, $tempfile, $latitude, $longitude) {
    $ext = strtolower(pathinfo($filename, PATHINFO_EXTENSION));
    $allowed_ext = ['png', 'jpg', 'jpeg'];
    if (!in_array($ext, $allowed_ext)) return false;

    $new_name = md5(rand()) . '.' . $ext;
    $path = '../../attendanceimages/' . $new_name;

    list($width, $height) = getimagesize($tempfile);
    $src_image = ($ext === 'png') ? imagecreatefrompng($tempfile) : imagecreatefromjpeg($tempfile);

    // Resize image
    $new_width = 500;
    $new_height = ($height / $width) * 500;
    $image = imagecreatetruecolor($new_width, $new_height);
    imagecopyresampled($image, $src_image, 0, 0, 0, 0, $new_width, $new_height, $width, $height);

    // Get address from coordinates
    $address = getAddressFromCoordinates($latitude, $longitude);
    
    // Get Google Maps static image
    $map_image = getGoogleMapsImage($latitude, $longitude);

    // Format address in GPS Map Camera style
    $formatted_address = "";
    $city_state = "";
    $detailed_address = "";
    $coordinates_text = "";
    
    if (!empty($address)) {
        // Extract city and state for the main title
        $address_parts = explode(',', $address);
        $address_parts = array_map('trim', $address_parts);
        
        // Try to find city and state
        $city = "";
        $state = "";
        
        // Look for common Indian cities and states
        foreach ($address_parts as $part) {
            $part_lower = strtolower($part);
            
            // Cities
            if (strpos($part_lower, 'nagpur') !== false) {
                $city = "Nagpur";
                $state = "Maharashtra";
            } elseif (strpos($part_lower, 'mumbai') !== false || strpos($part_lower, 'bombay') !== false) {
                $city = "Mumbai";
                $state = "Maharashtra";
            } elseif (strpos($part_lower, 'pune') !== false) {
                $city = "Pune";
                $state = "Maharashtra";
            } elseif (strpos($part_lower, 'delhi') !== false) {
                $city = "Delhi";
                $state = "Delhi";
            } elseif (strpos($part_lower, 'bangalore') !== false || strpos($part_lower, 'bengaluru') !== false) {
                $city = "Bangalore";
                $state = "Karnataka";
            } elseif (strpos($part_lower, 'hyderabad') !== false) {
                $city = "Hyderabad";
                $state = "Telangana";
            } elseif (strpos($part_lower, 'chennai') !== false || strpos($part_lower, 'madras') !== false) {
                $city = "Chennai";
                $state = "Tamil Nadu";
            }
            
            // States (override if found)
            if (strpos($part_lower, 'maharashtra') !== false) {
                $state = "Maharashtra";
            } elseif (strpos($part_lower, 'karnataka') !== false) {
                $state = "Karnataka";
            } elseif (strpos($part_lower, 'gujarat') !== false) {
                $state = "Gujarat";
            } elseif (strpos($part_lower, 'rajasthan') !== false) {
                $state = "Rajasthan";
            } elseif (strpos($part_lower, 'uttar pradesh') !== false) {
                $state = "Uttar Pradesh";
            }
        }
        
        // Create city_state title
        if (!empty($city) && !empty($state)) {
            $city_state = "{$city}, {$state}, India";
        } elseif (!empty($city)) {
            $city_state = "{$city}, India";
        } else {
            // Fallback: use last 2-3 parts of address
            $last_parts = array_slice($address_parts, -3);
            $city_state = implode(', ', $last_parts);
            if (strpos($city_state, 'India') === false) {
                $city_state .= ", India";
            }
        }
        
        // Use the full address as detailed address
        $detailed_address = trim($address);
        // Clean any invalid characters that might cause display issues
        $detailed_address = preg_replace('/[^\x20-\x7E\x09\x0A\x0D]/', '', $detailed_address);
        $detailed_address = str_replace(['  ', '   '], ' ', $detailed_address);
    } else {
        $city_state = "Location, India";
        $detailed_address = "Address not available";
    }
    
    // Format coordinates
    $coordinates_text = "Lat " . number_format($latitude, 6) . "° Long " . number_format($longitude, 6) . "°";
    
    // Format timestamp like GPS Map Camera
    $timestamp = date("d/m/Y h:i A") . " GMT +05:30";
    
    // Calculate overlay height for GPS Map Camera style (increased for larger fonts)
    $overlay_height = 140; // Fixed height for consistent look with larger fonts
    
    // Create overlay with gradient effect
    $overlay_color = imagecolorallocatealpha($image, 0, 0, 0, 80);
    imagefilledrectangle($image, 0, $new_height - $overlay_height, $new_width, $new_height, $overlay_color);
    
    // Add a subtle border at the top of overlay
    $border_color = imagecolorallocatealpha($image, 255, 255, 255, 50);
    imageline($image, 0, $new_height - $overlay_height, $new_width, $new_height - $overlay_height, $border_color);

    // Text colors
    $white = imagecolorallocate($image, 255, 255, 255);
    $light_gray = imagecolorallocate($image, 220, 220, 220);
    
    $font_large = 5; // For main title
    $font_medium = 4; // For detailed address
    $font_small = 4; // For coordinates and timestamp (increased from 3)
    
    $y = $new_height - $overlay_height + 10;
    $line_height = 15; // Increased line height for better spacing
    
    // Main city/state title (larger font)
    if (!empty($city_state)) {
        imagestring($image, $font_large, 10, $y, $city_state, $white);
        $y += $line_height + 4;
    }
    
    // Detailed address (medium font, can wrap to multiple lines)
    if (!empty($detailed_address)) {
        $max_chars = 50; // Reduced to accommodate larger font
        if (strlen($detailed_address) > $max_chars) {
            // Split long address into multiple lines
            $words = explode(' ', $detailed_address);
            $line = '';
            foreach ($words as $word) {
                if (strlen($line . ' ' . $word) > $max_chars) {
                    if (!empty($line)) {
                        imagestring($image, $font_medium, 10, $y, trim($line), $light_gray);
                        $y += $line_height;
                        $line = $word;
                    } else {
                        imagestring($image, $font_medium, 10, $y, substr($word, 0, $max_chars), $light_gray);
                        $y += $line_height;
                        $line = '';
                    }
                } else {
                    $line .= (empty($line) ? '' : ' ') . $word;
                }
            }
            if (!empty($line)) {
                imagestring($image, $font_medium, 10, $y, trim($line), $light_gray);
                $y += $line_height;
            }
        } else {
            imagestring($image, $font_medium, 10, $y, $detailed_address, $light_gray);
            $y += $line_height;
        }
    }
    
    // Coordinates
    imagestring($image, $font_small, 10, $y, $coordinates_text, $light_gray);
    $y += $line_height;
    
    // Timestamp
    imagestring($image, $font_small, 10, $y, $timestamp, $light_gray);
    
    // Add Google Maps thumbnail in bottom-left corner
    if ($map_image !== false) {
        $map_width = imagesx($map_image);
        $map_height = imagesy($map_image);
        
        // Position for map (bottom-left, above the text overlay)
        $map_x = 10;
        $map_y = $new_height - $overlay_height - $map_height - 10;
        
        // Create a border around the map
        $border_color = imagecolorallocate($image, 255, 255, 255);
        imagefilledrectangle($image, $map_x - 2, $map_y - 2, $map_x + $map_width + 2, $map_y + $map_height + 2, $border_color);
        
        // Place the map image
        imagecopy($image, $map_image, $map_x, $map_y, 0, 0, $map_width, $map_height);
        
        // Add "Google" watermark on the map
        $google_color = imagecolorallocate($image, 255, 255, 255);
        $google_bg = imagecolorallocatealpha($image, 0, 0, 0, 50);
        imagefilledrectangle($image, $map_x, $map_y + $map_height - 15, $map_x + 45, $map_y + $map_height, $google_bg);
        imagestring($image, 2, $map_x + 2, $map_y + $map_height - 12, "Google", $google_color);
        
        // Cleanup map image
        imagedestroy($map_image);
    }

    imagejpeg($image, $path, 100);
    imagedestroy($src_image);
    imagedestroy($image);

    return $new_name;
}




if($_POST['action'] == 'takeAttendance'){
    $date = $_POST['date'];
    $userid = $_POST['userid'];
    $sql55 = "SELECT Lattitude,Longitude FROM `tbl_users` WHERE id='$userid'";
    $row55 = getRecord($sql55);
    $Lattitude = $row55['Lattitude'];
    $Longitude = $row55['Longitude'];
    //$Status = $_POST['status'];
    $Status = 1;
    $SourceLat = $_POST['SourceLat'];
    $SourceLong = $_POST['SourceLong'];
    $SourceAddress = $_POST['SourceAddress'];
    $TempPrdId = $_POST['TempPrdId'];
    $HandoverAmt = $_POST['HandoverAmt'];
$HandoverUserId = $_POST['HandoverUserId'];



    $CreatedTime = date('H:i:s');
    if($CreatedTime > '10:10:00'){
            $Latemark = 1;
        }
        else{
            $Latemark = 0;
        }
        
        if($CreatedTime > '14:00:00'){
            $HalfDay = 1;
        }
        else{
            $HalfDay = 0;
        }
        
        $FileName1 = $_FILES["Photo"]["name"];
$FileSize1 = $_FILES["Photo"]["size"];
$TempFile1 = $_FILES["Photo"]["tmp_name"];
$Image = uploadImage($FileName1,$FileSize1,$TempFile1,$Lattitude,$Longitude);


    $sql = "SELECT * FROM tbl_attendance WHERE UserId='$userid' AND CreatedDate='$date' AND Type=1";
    $rncnt = getRow($sql);

    if($rncnt > 0){
        $sql2 = "UPDATE tbl_attendance SET HandoverAmt='$HandoverAmt',HandoverUserId='$HandoverUserId',Salary='$PerDaySalary',Status='$Status',Latitude='$SourceLat',Longitude='$SourceLong',Address='$SourceAddress',CreatedTime='$CreatedTime',Latemark='$Latemark',HalfDay='$HalfDay',Photo='$Image',Type=1,TempPrdId='$TempPrdId' WHERE UserId='$userid' AND CreatedDate='$date' AND Type=1";
        $conn->query($sql2);
         echo 1;
       
    }
    else{
       $sql2 = "INSERT INTO tbl_attendance SET HandoverAmt='$HandoverAmt',HandoverUserId='$HandoverUserId',Salary='$PerDaySalary',Status='$Status',UserId='$userid',CreatedDate='$date',Latitude='$Latitude',Longitude='$Longitude',Address='$SourceAddress',CreatedTime='$CreatedTime',Latemark='$Latemark',HalfDay='$HalfDay',Type=1,Photo='$Image',TempPrdId='$TempPrdId'";
        $conn->query($sql2);
      echo 1;
    }

 
   
   
}


if($_POST['action'] == 'takeAttendance2'){
    $date = $_POST['date'];
    $userid = $_POST['userid'];
    
     $sql55 = "SELECT Lattitude,Longitude FROM `tbl_users` WHERE id='$userid'";
    $row55 = getRecord($sql55);
    $Lattitude = $row55['Lattitude'];
    $Longitude = $row55['Longitude'];
    
    //$Status = $_POST['status'];
    $Status = 1;
    $SourceLat = $_POST['SourceLat'];
    $SourceLong = $_POST['SourceLong'];
    $SourceAddress = $_POST['SourceAddress'];
     $TempPrdId = $_POST['TempPrdId2'];

$HandoverAmt = $_POST['HandoverAmt'];
$HandoverUserId = $_POST['HandoverUserId'];

    $CreatedTime = date('H:i:s');
    if($CreatedTime > '10:10:00'){
            $Latemark = 1;
        }
        else{
            $Latemark = 0;
        }
        
        /*if($CreatedTime > '14:00:00'){
            $HalfDay = 1;
        }
        else{
            $HalfDay = 0;
        }*/
         
      $sql = "SELECT CreatedTime FROM tbl_attendance WHERE UserId='$userid' AND CreatedDate='$date' AND Type=1";
      $row = getRecord($sql);
        $StartTime = $row['CreatedTime'];
        $dateTime1 = new DateTime($CreatedTime); // Current date and time
$dateTime2 = new DateTime($StartTime); // 1 hour 45 minutes ago
// Calculate the difference between the two dates
$interval = $dateTime1->diff($dateTime2);
// Format the difference
$hours = $interval->h;
$minutes = $interval->i;

if($hours <= 6){
    $HalfDay = 1;
}
else if($hours > 6 && $hours <= 10){
    $HalfDay = 0;
}
else if($hours > 10){
    $HalfDay = 2;
}
        $FileName2 = $_FILES["Photo2"]["name"];
$FileSize2 = $_FILES["Photo2"]["size"];
$TempFile2 = $_FILES["Photo2"]["tmp_name"];
$Photo2 = uploadImage($FileName2,$FileSize2,$TempFile2,$Lattitude,$Longitude);

    $sql = "SELECT * FROM tbl_attendance WHERE UserId='$userid' AND CreatedDate='$date' AND Type=2";
    $rncnt = getRow($sql);
    

    if($rncnt > 0){
        $sql2 = "UPDATE tbl_attendance SET HandoverAmt='$HandoverAmt',HandoverUserId='$HandoverUserId',Salary='$PerDaySalary',Status='$Status',Latitude='$SourceLat',Longitude='$SourceLong',Address='$SourceAddress',CreatedTime='$CreatedTime',Latemark='$Latemark',HalfDay='$HalfDay',Photo='$Photo2',Type=2,TempPrdId='$TempPrdId' WHERE UserId='$userid' AND CreatedDate='$date' AND Type=2";
        $conn->query($sql2);
        
   
  
        echo 1;
        /*}
        else{
            echo 0;
        }*/
    }
    else{
        
       /* if($rncnt2 > 0){*/
       $sql2 = "INSERT INTO tbl_attendance SET HandoverAmt='$HandoverAmt',HandoverUserId='$HandoverUserId',Salary='$PerDaySalary',Status='$Status',UserId='$userid',CreatedDate='$date',Latitude='$Latitude',Longitude='$Longitude',Address='$SourceAddress',CreatedTime='$CreatedTime',Latemark='$Latemark',HalfDay='$HalfDay',Photo='$Photo2',Type=2,TempPrdId='$TempPrdId'";
        $conn->query($sql2);

          
        echo 1;
        /*}
        else{
            echo 0;
        }*/
    }
    

}


if($_POST['action'] == 'checkToday'){
    $date = $_POST['date'];
    $userid = $_POST['userid'];
     $sql = "SELECT * FROM tbl_attendance WHERE UserId='$userid' AND CreatedDate='$date'";
     $row = getRecord($sql);
     echo $row['Status'];
    
}

if($_POST['action'] == 'checkHoEmp'){
    $userid = $_POST['userid'];
    $sql = "SELECT * FROM tbl_users WHERE id='$userid' AND MainBrEmp=1";
    $rncnt = getRow($sql);
    
    $sql2 = "SELECT * FROM tbl_users WHERE id='$userid' AND MarkAttendance=1";
    $rncnt2 = getRow($sql2);
    if($rncnt > 0){
        echo 1;
    }
    else if($rncnt2 > 0){
        echo 2;
    }
    else{
        echo 0;
    }
}
?>