<?php include 'header.php'; ?>
<?php include "config.php"; ?>

<?php
if(isset($_POST['submit']))
{
    function clean($data){
        global $conn;
        return mysqli_real_escape_string($conn, trim($data));
    }

    $full_name = clean($_POST['full_name']);
    $mobile = clean($_POST['mobile']);
    $email = clean($_POST['email']);
    $age = clean($_POST['age']);
    $gender = clean($_POST['gender']);
    $city = clean($_POST['city']);

    $height = clean($_POST['height']);
    $current_weight = clean($_POST['current_weight']);
    $target_weight = clean($_POST['target_weight']);
    $weight_change = clean($_POST['weight_change']);

    $health_issues = isset($_POST['health_issues']) ? implode(", ", $_POST['health_issues']) : "";

    $activity_level = clean($_POST['activity_level']);
    $work_timing = clean($_POST['work_timing']);
    $sleep_duration = clean($_POST['sleep_duration']);
    $stress_level = clean($_POST['stress_level']);

    $diet_type = clean($_POST['diet_type']);
    $food_avoid = clean($_POST['food_avoid']);
    $food_allergy = clean($_POST['food_allergy']);
    $water_intake = clean($_POST['water_intake']);

    $sql = "INSERT INTO tbl_diet_consultation 
    (full_name,mobile,email,age,gender,city,height,current_weight,target_weight,
    weight_change,health_issues,activity_level,work_timing,sleep_duration,
    stress_level,diet_type,food_avoid,food_allergy,water_intake)

    VALUES
    ('$full_name','$mobile','$email','$age','$gender','$city','$height',
    '$current_weight','$target_weight','$weight_change','$health_issues',
    '$activity_level','$work_timing','$sleep_duration','$stress_level',
    '$diet_type','$food_avoid','$food_allergy','$water_intake')";

    mysqli_query($conn,$sql);

    echo "<script>alert('Consultation Request Submitted Successfully!');</script>";
}
?>

<style>
/* FIX DROPDOWN CUT ISSUE */
.product_section_2,
.product_section_2_wrap,
.product_section_content,
.container,
.row,
.col-lg-6 {
    overflow: visible !important;
}

/* FORM DESIGN */
.diet-form-card {
    background: #fff;
    padding: 30px;
    border-radius: 15px;
    box-shadow: 0 15px 40px rgba(0,0,0,0.1);
}

.diet-form-card h3 {
    color: #28a745;
    margin-bottom: 20px;
    font-weight: 600;
}

.diet-form-card .form-control,
.diet-form-card .form-select {
    border-radius: 8px;
    margin-bottom: 15px;
    padding: 10px;
}

.diet-form-card button {
    background: linear-gradient(135deg, #28a745, #1e7e34);
    border: none;
    padding: 12px;
    border-radius: 8px;
    width: 100%;
    color: #fff;
    font-weight: 600;
    transition: 0.3s;
}

.diet-form-card button:hover {
    transform: translateY(-2px);
    box-shadow: 0 10px 20px rgba(0,0,0,0.2);
}
</style>

<main>

<section class="breadcrumb_sec_1 position-relative">
    <div class="breadcrumb_wrap sec_space_mid_small" style="background-image: url(assets/images/breadcrumb/bg.jpg);">
        <div class="breadcrumb_cont text-center">
            <h2 class="text-white">Diet Consultation</h2>
        </div>
    </div>
</section>

<section class="product_section_2 sec_space_small">
    <div class="container">
        <div class="row">

            <!-- LEFT IMAGE -->
            <div class="col-lg-6 mb-4">
                <img src="diet.jpeg" class="img-fluid rounded" alt="Diet Consultation">
            </div>

            <!-- RIGHT FORM -->
            <div class="col-lg-6">
                <div class="diet-form-card">

                    <h3>Free Dietitian Consultation</h3>

                    <form method="post">

                        <input type="text" name="full_name" class="form-control" placeholder="Full Name" required>
                        <input type="text" name="mobile" class="form-control" placeholder="Mobile Number" required>
                        <input type="email" name="email" class="form-control" placeholder="Email">
                        <input type="number" name="age" class="form-control" placeholder="Age">

                        <select name="gender" class="form-select">
                            <option value="">Select Gender</option>
                            <option>Male</option>
                            <option>Female</option>
                            <option>Other</option>
                        </select>

                        <input type="text" name="city" class="form-control" placeholder="City">

                        <input type="text" name="height" class="form-control" placeholder="Height">
                        <input type="text" name="current_weight" class="form-control" placeholder="Current Weight">
                        <input type="text" name="target_weight" class="form-control" placeholder="Target Weight">

                        <textarea name="weight_change" class="form-control" placeholder="Recent Weight Change Details"></textarea>

                        <label><strong>Health Issues</strong></label><br>

                        <div class="form-check">
                            <input type="checkbox" class="form-check-input" name="health_issues[]" value="Diabetes"> Diabetes
                        </div>
                        <div class="form-check">
                            <input type="checkbox" class="form-check-input" name="health_issues[]" value="Thyroid"> Thyroid
                        </div>
                        <div class="form-check">
                            <input type="checkbox" class="form-check-input" name="health_issues[]" value="BP"> BP
                        </div>

                        <select name="activity_level" class="form-select mt-3">
                            <option>Sedentary</option>
                            <option>Moderate</option>
                            <option>Active</option>
                        </select>

                        <select name="work_timing" class="form-select">
                            <option>Day</option>
                            <option>Night</option>
                            <option>Shift</option>
                        </select>

                        <input type="text" name="sleep_duration" class="form-control" placeholder="Sleep Duration">

                        <select name="stress_level" class="form-select">
                            <option>Low</option>
                            <option>Medium</option>
                            <option>High</option>
                        </select>

                        <select name="diet_type" class="form-select">
                            <option>Veg</option>
                            <option>Eggetarian</option>
                            <option>Non-veg</option>
                        </select>

                        <input type="text" name="food_avoid" class="form-control" placeholder="Foods you avoid">
                        <input type="text" name="food_allergy" class="form-control" placeholder="Food allergy">
                        <input type="text" name="water_intake" class="form-control" placeholder="Daily water intake">

                        <div class="form-check mt-3">
                            <input type="checkbox" class="form-check-input" required>
                            I confirm the information is correct.
                        </div>

                        <br>

                        <button type="submit" name="submit">Submit Consultation</button>

                    </form>

                </div>
            </div>

        </div>
    </div>
</section>

</main>

<?php include 'footer.php'; ?>