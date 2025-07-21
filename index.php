<?php 
$insert = false;
$notInsert = false;
$errorMsg = "";

// Handle form submission
if ($_SERVER["REQUEST_METHOD"] == "POST") {
    $server = "localhost";
    $username = "root";
    $password = "";
    $database = "dubai_trip";

    // Create connection
    $con = mysqli_connect($server, $username, $password, $database);

    if (!$con) {
        die("Connection failed: " . mysqli_connect_error());
    }

    // Sanitize input
    function clean_input($data) {
        return htmlspecialchars(stripslashes(trim($data)));
    }

    $name = clean_input($_POST['name'] ?? '');
    $age = clean_input($_POST['age'] ?? '');
    $gender = clean_input($_POST['gender'] ?? '');
    $email = clean_input($_POST['email'] ?? '');
    $phone = clean_input($_POST['phone'] ?? '');
    $desc = clean_input($_POST['desc'] ?? '');

    // Validation
    if (!preg_match("/^[a-zA-Z\s]{2,}$/", $name)) {
        $errorMsg .= "Name must contain only letters and be at least 2 characters. ";
    }

    if (!filter_var($age, FILTER_VALIDATE_INT) || $age < 1 || $age > 120) {
        $errorMsg .= "Age must be a number between 1 and 120. ";
    }

    $validGenders = ["Male", "Female", "Other"];
    if (!in_array($gender, $validGenders)) {
        $errorMsg .= "Gender must be Male, Female, or Other. ";
    }

    if (!filter_var($email, FILTER_VALIDATE_EMAIL)) {
        $errorMsg .= "Please enter a valid email address. ";
    }

    if (!preg_match("/^[6-9]\d{9}$/", $phone)) {
        $errorMsg .= "Phone must be a 10-digit number starting with 6-9. ";
    }

    if (strlen($desc) < 5 || strlen($desc) > 300) {
        $errorMsg .= "Description must be between 5 and 300 characters. ";
    }

    // Insert if no errors
    if (empty($errorMsg)) {
        $stmt = $con->prepare("INSERT INTO trip (name, age, gender, email, phone, other, dt) VALUES (?, ?, ?, ?, ?, ?, current_timestamp())");

        if ($stmt) {
            $stmt->bind_param("sissss", $name, $age, $gender, $email, $phone, $desc);

            if ($stmt->execute()) {
                $insert = true;
            } else {
                $notInsert = true;
                $errorMsg = "Error executing query: " . $stmt->error;
            }

            $stmt->close();
        } else {
            $notInsert = true;
            $errorMsg = "Error preparing statement: " . $con->error;
        }
    } else {
        $notInsert = true;
    }

    $con->close();
}
?>

<!DOCTYPE html>
<html lang="en">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Welcome to travel Form</title>
    <link rel="preconnect" href="https://fonts.googleapis.com">
    <link rel="preconnect" href="https://fonts.gstatic.com" crossorigin>
    <link href="https://fonts.googleapis.com/css2?family=Inter&family=Open+Sans&family=Plus+Jakarta+Sans&display=swap" rel="stylesheet">
    <link rel="stylesheet" href="style.css">
</head>
<body>
    <img src="bg.webp" alt="Dubai" class="bg">
    <div class="container">
        <h1>Welcome to TCOER Dubai Trip Form</h1>
        <br>
        <p>Enter your details and submit form to confirm your participation in the trip</p>
        <br>

        <?php 
            if ($insert) {
                echo "<p class='submitMsg'>Thanks for submitting this form. We are happy to see you at the trip!</p>";
            }
            if ($notInsert && !empty($errorMsg)) {
                echo "<p class='submitMsg' style='color:red;'>$errorMsg</p>";
            }
        ?>

        <form action="index.php" method="post">
            <input type="text" name="name" id="name" placeholder="Enter your name" required>
            <input type="number" name="age" id="age" placeholder="Enter your age" required>
            <select name="gender" id="gender" required>
                <option value="">Select Gender</option>
                <option value="Male">Male</option>
                <option value="Female">Female</option>
                <option value="Other">Other</option>
            </select>
            <input type="email" name="email" id="email" placeholder="Enter your email" required>
            <input type="number" name="phone" id="phone" placeholder="Enter your Mobile Number" required>
            <textarea name="desc" id="desc" cols="30" rows="8" placeholder="Enter any other Information" required></textarea>
            <br>
            <button class="btn">Submit</button>
        </form>
    </div>

    <script src="app.js"></script>
</body>
</html>
