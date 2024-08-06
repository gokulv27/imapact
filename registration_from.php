<!DOCTYPE html>
<html>
<head>
  <title >Registration Form</title>
  <meta charset="utf-8">
  <meta name="viewport" content="width=device-width, initial-scale=1">
  <link rel="stylesheet" href="https://maxcdn.bootstrapcdn.com/bootstrap/4.5.2/css/bootstrap.min.css">
  <script src="https://ajax.googleapis.com/ajax/libs/jquery/3.5.1/jquery.min.js"></script>
  <script src="https://cdnjs.cloudflare.com/ajax/libs/popper.js/1.16.0/umd/popper.min.js"></script>
  <script src="https://maxcdn.bootstrapcdn.com/bootstrap/4.5.2/js/bootstrap.min.js"></script>
  <style>
    .error {color: #d81414;}
  </style>
</head>
<body style="background-color: #1a1b37;">

<?php
// define variables and set to empty values
$firstnameErr = $lastnameErr = $emailErr = $mobileErr = $genderErr = $passwordErr = "";
$firstname = $lastname = $email = $mobile = $gender = $password = "";

if ($_SERVER["REQUEST_METHOD"] == "POST") {
  if (empty($_POST["firstname"])) {
    $firstnameErr = "First name is required";
  } else {
    $firstname = test_input($_POST["firstname"]);
    // check if name only contains letters and whitespace
    if (!preg_match("/^[a-zA-Z ]*$/",$firstname)) {
      $firstnameErr = "Only letters and white space allowed";
    }
  }
  
  if (empty($_POST["lastname"])) {
    $lastnameErr = "Last name is required";
  } else {
    $lastname = test_input($_POST["lastname"]);
    // check if name only contains letters and whitespace
    if (!preg_match("/^[a-zA-Z ]*$/",$lastname)) {
      $lastnameErr = "Only letters and white space allowed";
    }
  }
  
  if (empty($_POST["email"])) {
    $emailErr = "Email is required";
  } else {
    $email = test_input($_POST["email"]);
    // check if e-mail address is well-formed
    if (!filter_var($email, FILTER_VALIDATE_EMAIL)) {
      $emailErr = "Invalid email format";
    }
  }
    
  if (empty($_POST["mobile"])) {
    $mobileErr = "Mobile number is required";
  } else {
    $mobile = test_input($_POST["mobile"]);
    // check if mobile number is valid
    if (!preg_match("/^[0-9]{10}$/",$mobile)) {
      $mobileErr = "Invalid mobile number";
    }
  }

  if (empty($_POST["gender"])) {
    $genderErr = "Gender is required";
  } else {
    $gender = test_input($_POST["gender"]);
  }

  if (empty($_POST["password"])) {
    $passwordErr = "Password is required";
  } else {
    $password = test_input($_POST["password"]);
  }

  // if there are no errors, store the data in the database
  if ($firstnameErr == "" && $lastnameErr == "" && $emailErr == "" && $mobileErr == "" && $genderErr == "" && $passwordErr == "") {
    // connect to the database
    $servername = "localhost";
    $username = "root";
    $password = "";
    $dbname = "impact";

    $conn = new mysqli($servername, $username, $password, $dbname);
    // check connection
    if ($conn->connect_error) {
      die("Connection failed: " . $conn->connect_error);
    }
    
    // prepare and bind the SQL statement
    $stmt = $conn->prepare("INSERT INTO users (firstname, lastname, email, mobile, gender, password) VALUES (?, ?, ?, ?, ?, ?)");
    $stmt->bind_param("ssssss", $firstname, $lastname, $email, $mobile, $gender, $password);
    
    // execute the SQL statement and check for errors
    if ($stmt->execute() === TRUE) {
      echo "<div class='alert alert-success'>Registration successful!</div>";
    } else {
      echo "<div class='alert alert-danger'>Error: " . $stmt->error . "</div>";
    }
    
    // close the connection and statement
    $stmt->close();
    $conn->close();
  }
}

function test_input($data) {
$data = trim($data);
$data = stripslashes($data);
$data = htmlspecialchars($data);
return $data;
}
?>

<div class="container">
  <h2 style="color:#ffffff";>New account</h2>
  <form method="post" action="<?php echo htmlspecialchars($_SERVER["PHP_SELF"]);?>">
    <div class="form-group">
      <label style="color:#ffffff" for="firstname">First Name:</label>
      <input type="text" class="form-control" id="firstname" name="firstname" value="<?php echo $firstname;?>">
      <span class="error"><?php echo $firstnameErr;?></span>
    </div>
    <div class="form-group">
      <label style="color:#ffffff" for="lastname">Last Name:</label>
      <input type="text" class="form-control" id="lastname" name="lastname" value="<?php echo $lastname;?>">
      <span class="error"><?php echo $lastnameErr;?></span>
    </div>
    <div class="form-group">
      <label style="color:#ffffff" for="email">Email:</label>
      <input type="email" class="form-control" id="email" name="email" value="<?php echo $email;?>">
      <span class="error"><?php echo $emailErr;?></span>
    </div>
    <div class="form-group">
      <label style="color:#ffffff" for="mobile">Mobile:</label>
      <input type="tel" class="form-control" id="mobile" name="mobile" pattern="[0-9]{10}" value="<?php echo $mobile;?>">
      <span class="error"><?php echo $mobileErr;?></span>
    </div>
    <div class="form-group">
      <label style="color:#ffffff" for="gender">Gender:</label>
      <select class="form-control" id="gender" name="gender">
        <option  value="">--Please choose an option--</option>
        <option value="male" <?php if ($gender == 'male') echo 'selected="selected"'; ?>>Male</option>
        <option value="female" <?php if ($gender == 'female') echo 'selected="selected"'; ?>>Female</option>
        <option value="other" <?php if ($gender == 'other') echo 'selected="selected"'; ?>>Other</option>
      </select>
      <span class="error"><?php echo $genderErr;?></span>
    </div>
    <div class="form-group">
      <label style="color:#ffffff" for="password">Password:</label>
      <input type="password" class="form-control" id="password" name="password">
      <span class="error"><?php echo $passwordErr;?></span>
    </div>
    <button  style="background-color:#d81515" type="submit" class="btn btn-primary">Submit</button>    