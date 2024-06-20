<?php
include 'dbconnect.php';

// Check if the registration form was submitted
if (isset($_POST['submit'])) {
   // Sanitize and escape user inputs
   $name = mysqli_real_escape_string($conn, $_POST['name']);
   $email = mysqli_real_escape_string($conn, $_POST['email']);
   $pass = mysqli_real_escape_string($conn, md5($_POST['password']));
   $cpass = mysqli_real_escape_string($conn, md5($_POST['cpassword']));
   $phone = mysqli_real_escape_string($conn, $_POST['phone']);

   // Check if user with the same email and password exists
   $select = mysqli_query($conn, "SELECT * FROM `form` WHERE email = '$email' AND password = '$pass'") or die('query failed');

   if (mysqli_num_rows($select) > 0) {
      $message[] = 'User already exists'; 
   } else {
      if ($pass != $cpass) {
         $message[] = 'Confirm password not matched!';
      } else {
         // Insert user data into the database
         $insert = mysqli_query($conn, "INSERT INTO `form`(name, email, password, phone) VALUES('$name', '$email', '$pass', '$phone')") or die('query failed');

         if ($insert) {
            echo "<script>alert('Registered successfully!'); window.location.href='login.php';</script>";
               exit;
         } else {
            $message[] = 'Registration failed!';
         }
      }
   }
}
?> 

<!DOCTYPE html>
<html lang="en">
<head>
   <meta charset="UTF-8">
   <meta http-equiv="X-UA-Compatible" content="IE=edge">
   <meta name="viewport" content="width=device-width, initial-scale=1.0">
   <title>Customer Register</title>
   <link rel="stylesheet" href="css/admin.css">
   <link rel="stylesheet" href="css/customer.css">
</head>
<body>
<div class="form-main-container">

   <div class="form-container">
      <form id="registrationForm" action="" method="post">
         <h3>Customer Register Now</h3>
         <?php
            if(isset($message)){
               foreach($message as $message){
                  echo '<div class="message">'.$message.'</div>';
               }
            }
         ?>
         
         <input type="text" name="name" id="name" placeholder="Enter username" class="box" novalidate>
         <input type="email" name="email" id="email" placeholder="Enter email" class="box" novalidate>
         <input type="password" name="password" id="password" placeholder="Enter password" class="box" novalidate>
         <input type="password" name="cpassword" id="cpassword" placeholder="Confirm password" class="box" novalidate>
         <input type="text" name="phone" id="phone" placeholder="Enter your 10-digit phone number" class="box" novalidate>
         <input type="submit" name="submit" value="Register now" class="btn">
         <p>Already have an account? <a href="login.php">Login now</a></p>
      </form>
   </div>
</div>

<script>
    document.addEventListener("DOMContentLoaded", function () {
        const registrationForm = document.getElementById("registrationForm");
        const nameInput = document.getElementById("name");
        const emailInput = document.getElementById("email");
        const passwordInput = document.getElementById("password");
        const cpasswordInput = document.getElementById("cpassword");
        const phoneInput = document.getElementById("phone");

        registrationForm.addEventListener("submit", function (event) {
            let isValid = true;

            // Validate Name
            if (nameInput.value.trim() === "") {
                isValid = false;
                displayErrorMessage(nameInput, "Username is required.");
            } else {
                hideErrorMessage(nameInput);
            }

            // Validate Email
            if (emailInput.value.trim() === "") {
                isValid = false;
                displayErrorMessage(emailInput, "Email is required.");
            } else {
                hideErrorMessage(emailInput);
            }

            // Validate Password
            if (passwordInput.value.trim() === "") {
                isValid = false;
                displayErrorMessage(passwordInput, "Password is required.");
            } else {
                hideErrorMessage(passwordInput);
            }

            // Validate Confirm Password
            if (cpasswordInput.value.trim() === "") {
                isValid = false;
                displayErrorMessage(cpasswordInput, "Confirm password is required.");
            } else if (cpasswordInput.value !== passwordInput.value) {
                isValid = false;
                displayErrorMessage(cpasswordInput, "Passwords do not match.");
            } else {
                hideErrorMessage(cpasswordInput);
            }

            // Validate Phone Number
            if (phoneInput.value.trim() === "") {
                isValid = false;
                displayErrorMessage(phoneInput, "Phone number is required.");
            } else if (!/^\d{10}$/.test(phoneInput.value)) {
                isValid = false;
                displayErrorMessage(phoneInput, "Phone number must be 10 digits.");
            } else {
                hideErrorMessage(phoneInput);
            }

            if (!isValid) {
                event.preventDefault(); // Prevent form submission
            }
        });

        function displayErrorMessage(inputElement, message) {
            const errorElement = document.createElement("div");
            errorElement.className = "message";
            errorElement.textContent = message;

            // Remove any existing error message
            hideErrorMessage(inputElement);

            // Add the new error message after the input element
            inputElement.parentNode.insertBefore(errorElement, inputElement.nextSibling);
        }

        function hideErrorMessage(inputElement) {
            const nextElement = inputElement.nextElementSibling;
            if (nextElement && nextElement.classList.contains("message")) {
                nextElement.remove();
            }
        }
    });
</script>
</body>
</html>
