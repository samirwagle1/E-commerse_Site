<?php
include '../dbconnect.php';
session_start();

$message = array(); // Initialize an empty array for messages

if (isset($_POST['submit'])) {
    $email = mysqli_real_escape_string($conn, $_POST['email']);
    $pass = mysqli_real_escape_string($conn, md5($_POST['password']));

    if (!empty($email) && !empty($pass)) {
        $select = mysqli_query($conn, "SELECT * FROM `users_table` WHERE email = '$email' AND password = '$pass'") or die('query failed');
        if (mysqli_num_rows($select) > 0) {
            $row = mysqli_fetch_assoc($select);
            $_SESSION['user_id'] = $row['id'];
            header('location:admin.php');
            exit(); // Terminate the script after redirect
        } else {
            $message[] = 'Incorrect email or password!';
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
   <title>Admin Login</title>

   <link rel="stylesheet" href="../css/style.css">
   <link rel="stylesheet" href="../css/customer.css">
   <!-- <link rel="stylesheet" href="../css/admin.css"> -->

</head>
<body>
   
<div class="form-main-container">
   <div class="form-container">
      <form id="loginForm" action="" method="post" enctype="multipart/form-data">
         <h3>Admin Login</h3>
         <?php
            if(isset($message)){
               foreach($message as $message){
                  echo '<div class="message">'.$message.'</div>';
               }
            }
         ?>
         <input type="email" id="email" name="email" placeholder="enter email" class="box" novalidate>
         <input type="password" id="password" name="password" placeholder="enter password" class="box" novalidate>

         <input type="submit" name="submit" value="login now" class="btn">
         <p>don't have an account? <a href="adminregister.php">Regiser now</a></p>
      </form>
   </div>
</div>

<script>
    document.addEventListener("DOMContentLoaded", function () {
        const loginForm = document.getElementById("loginForm");
        const emailInput = document.getElementById("email");
        const passwordInput = document.getElementById("password");

        loginForm.addEventListener("submit", function (event) {
            let isValid = true;

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
