
<?php
    require "../dbconnect.php";
    session_start();
    $statusMsg = "";
// Handle form submission
// if ($_SERVER["REQUEST_METHOD"] == "POST") // add <?php echo $_SERVER['PHP_SELF']; in form action if req meth is used
if ($_SERVER["REQUEST_METHOD"] == "POST") {
   
    $product_name = $_POST["product_name"];
    $price = $_POST["price"];
    $quantity = $_POST["quantity"];
    $description = $_POST["description"];
    // File upload directory
    $targetDir = "../admin/uploads/";

    if (!empty($_FILES["image"]["name"])) {
        $fileName = basename($_FILES["image"]["name"]);
        $targetFilePath = $targetDir . $fileName;
        $fileType = pathinfo($targetFilePath, PATHINFO_EXTENSION);

        // Allow certain file formats
        $allowTypes = array('jpg', 'png', 'jpeg', 'gif');

        if (in_array($fileType, $allowTypes)) {
            // Upload file to server
            if (move_uploaded_file($_FILES["image"]["tmp_name"], $targetFilePath)) {
                // Insert data into database
                $sql = "INSERT INTO product_tbl (product_name, price, quantity, image, description)
                        VALUES ('$product_name', $price, $quantity, '$fileName', '$description')";
                
                if ($conn->query($sql) === TRUE) {
                    echo '<script>alert("New Product created successfully");</script>';
                } else {
                    echo '<script>alert("Error: Failed to add product");</script>';
                }
            } else {
                echo '<script>alert("Sorry, there was an error uploading your file.");</script>';
            }
        } else {
            echo '<script>alert("Sorry, only JPG, JPEG, PNG, & GIF files are allowed to upload.");</script>';
        }
    } else {
        echo '<script>alert("Please select a file to upload.");</script>';
    }
}    


    // Execute the query for the prev code without img
    // if ($conn->query($sql) === TRUE) {
    //     echo "New record created successfully";
    // } else {
    //     echo "Error: Failed to add product";
    // }


?>

<!DOCTYPE html>
<html lang="en">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Ecommerce-Samir Wagle</title>
    <link rel="stylesheet" href="../css/styles.css">
    <link rel="stylesheet" href="../css/admin.css">
    <script src="https://kit.fontawesome.com/18e31dbed1.js" crossorigin="anonymous"></script>
</head>
<body>

    <!-- Main Dashboard -->
    <section id="dashboard">
        <div class="left-dash-sec section-p1">
            <div class="recent-order">
                <div class="order-head">
                    <!-- <h3>Recent Customers</h3>
                    <button>View All</button> -->
                </div>
                
                <div class="add-pro-container">
                    <h3>Insert Product</h3>
                    
                    <form method="POST" action="<?php echo $_SERVER['PHP_SELF'];?>" enctype="multipart/form-data" class="add-pro-form" name="addProduct" id="addProduct">
                    <div class="add-pro-group">
                        <label class="add-pro-label">Product Name:</label>
                        <input type="text" class="add-pro-input" name="product_name" id="product_name" required>
                        <div class="validation-message" id="nameValidation"></div>
                    </div>

                    <div class="add-pro-group">
                        <label class="add-pro-label">Price:</label>
                        <input type="number" class="add-pro-input" name="price" id="price" required>
                        <div class="validation-message" id="priceValidation"></div>
                    </div>

                    <div class="add-pro-group">
                        <label class="add-pro-label">Quantity:</label>
                        <input type="number" class="add-pro-input qty" name="quantity" id="quantity" required>
                        <div class="validation-message" id="quantityValidation"></div>
                    </div>

                    <div class="add-pro-group">
                        <label class="add-pro-label">Image:</label>
                        <input type="file" class="add-pro-input" name="image" id="image" accept=".jpg, .jpeg, .png" value="" required>
                        <div class="validation-message" id="imageValidation"></div>
                    </div>

                    <div class="add-pro-group">
                        <label class="add-pro-label">Description:</label>
                        <textarea class="add-pro-input" name="description" id="description" required></textarea>
                        <div class="validation-message" id="descriptionValidation"></div>
                    </div>

                    <div class="add-pro-group">
                        <input type="submit" class="add-pro-submit" value="Submit" name="submit" onclick="return validateForm()">
                    </div>
                </form>
                    
                </div>

            </div>
        </div>

        <div class="right-dash-sec">
            <div class="sidebar">
                <div class='name'>
                    <span>Username</span>
                    <h3><?php echo $_SESSION['admin_name']; ?></h3>
                </div>
                <nav>
                    <ul>
                        <li><a href="admin.php"><i class="fas fa-tachometer-alt"></i> Dashboard</a></li>
                        <li><a href="manageProducts.php"><i class="fas fa-box-open"></i> Product Management</a></li>
                        <li><a class="active" href="#"><i class="fas fa-tags"></i>Add Product</a></li>
                        <li><a href="orders.php"><i class="fas fa-shopping-cart"></i> Order Management</a></li>
                        <li><a href="#"><i class="fas fa-credit-card"></i> Payment Option Management</a></li>
                        <li><a href="#"><i class="fas fa-file"></i> Page Management</a></li>
                        <li><a href="manageCustomer.php"><i class="fas fa-users"></i>Customer Management</a></li>
                        <li><a href="#"><i class="fas fa-message"></i>Contact Message</a></li>
                
                    </ul>
                </nav>
                <div class="setting">
                    <a><i class="fa-solid fa-gear"></i>Setting</a>
                    <a href="adminLogout.php"><i class="fa-solid fa-right-from-bracket"></i>Log Out</a>
                </div>
            </div>
        </div>
    </section>

    <script src="script.js"></script>
    <script>
        function validateForm() {
            var productName = document.getElementById("product_name").value;
            var price = document.getElementById("price").value;
            var quantity = document.getElementById("quantity").value;
            var image = document.getElementById("image").value;
            var description = document.getElementById("description").value;

            var isValid = true;

            document.getElementById("nameValidation").textContent = "";
            document.getElementById("priceValidation").textContent = "";
            document.getElementById("quantityValidation").textContent = "";
            document.getElementById("imageValidation").textContent = "";
            document.getElementById("descriptionValidation").textContent = "";

            // Validate Product Name
            if (productName.trim() === "") {
                document.getElementById("nameValidation").textContent = "Product Name is required.";
                isValid = false;
            }

            // Validate Price
            if (price.trim() === "") {
                document.getElementById("priceValidation").textContent = "Price is required.";
                isValid = false;
            }

            // Validate Quantity
            if (quantity.trim() === "") {
                document.getElementById("quantityValidation").textContent = "Quantity is required.";
                isValid = false;
            }

            // Validate Image
            if (image.trim() === "") {
                document.getElementById("imageValidation").textContent = "Image is required.";
                isValid = false;
            }

            // Validate Description
            if (description.trim() === "") {
                document.getElementById("descriptionValidation").textContent = "Description is required.";
                isValid = false;
            }

            return isValid;
        }
        
    </script>
</body>
</html>