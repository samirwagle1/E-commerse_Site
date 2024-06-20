<?php
$activePage = 'cart.php';
?>
<?php
include "header.php";

// Check if the user is not registered
if (isset($_SESSION['not_registered']) && $_SESSION['not_registered'] === true) {
    require 'dbconnect.php'; // Include the database connection code if needed

    // Check if the email input is provided
    if (isset($_POST['email']) && !empty($_POST['email'])) {
        $email = $_POST['email'];

        $stmt = $conn->prepare('SELECT id FROM form WHERE email = ?');
        $stmt->bind_param('s', $email);
        $stmt->execute();
        $result = $stmt->get_result();

        if ($result->num_rows > 0) {
        } else {
            // User is not registered
            echo '<div class="text-center">You are not registered. Please register and log in <a href="register.php">here</a>.</div>';
            unset($_SESSION['not_registered']); // Clear the session variable
        }
    } else {
        // Email input not provided
        echo '<div class="alert alert-danger">Please provide your email address.</div>';
    }
}
?>
<?php
require 'dbconnect.php';

$grand_total = 0;
$allItems = '';
$items = [];

$sql = "SELECT CONCAT(product_name, '(',quantity,')') AS ItemQty, total_price FROM cart";
$stmt = $conn->prepare($sql);
$stmt->execute();
$result = $stmt->get_result();
while ($row = $result->fetch_assoc()) {
    $grand_total += $row['total_price'];
    $items[] = $row['ItemQty'];
}
$allItems = implode(', ', $items);
?>
<!-- Checkout Payment page -->
<div class="checkout-container">
    <div class="row justify-content-center">
        <div class="col-lg-6 px-4 pb-4" id="order">
            <h4 class="text-center text-info p-2">Complete your order!</h4>
            <div class="jumbotron p-3 mb-2 text-center">
                <h6 class="lead"><b>Product(s) : </b><?= $allItems; ?></h6>
                <h6 class="lead"><b>Delivery Charge : </b>Free</h6>
                <h5><b>Total Amount Payable : </b><?= number_format($grand_total, 2) ?>/-</h5>
            </div>
            <form action="thankyou.php" method="post" id="placeOrder" novalidate>
                <input type="hidden" name="">
                <input type="hidden" name="products" value="<?= $allItems; ?>">
                <input type="hidden" name="grand_total" value="<?= $grand_total; ?>">
                <div class="form-group">
                    <input type="text" name="name" class="form-control" placeholder="Enter Name">
                    <div class="validation-message" id="name-error"></div>
                </div>
                <div class="form-group">
                    <input type="email" name="email" class="form-control" placeholder="Enter E-Mail">
                    <div class="validation-message" id="email-error"></div>
                </div>
                <div class="form-group">
                    <input type="tel" name="phone" class="form-control" placeholder="Enter Phone">
                    <div class="validation-message" id="phone-error"></div>
                </div>
                <div class="form-group">
                    <textarea name="address" class="form-control" rows="3" cols="10"
                        placeholder="Enter Delivery Address Here..."></textarea>
                    <div class="validation-message" id="address-error"></div>
                </div>
                <h6 class="text-center lead">Select Payment Mode</h6>
                <div class="form-group">
                    <select name="payment_mode" class="form-control">
                        <option value="" selected disabled>-Select Payment Mode-</option>
                        <option value="cod">Cash On Delivery</option>
                    </select>
                    <div class="validation-message" id="payment-mode-error"></div>
                </div>
                <div class="form-group">
                    <input type="submit" name="submit" value="Place Order" class="btn btn-danger btn-block">
                </div>
                <p>Not registered yet? <a href="register.php?from=checkout">Register here</a></p>
            </form>
        </div>
    </div>
</div>

<script src='https://cdnjs.cloudflare.com/ajax/libs/jquery/3.5.1/jquery.min.js'></script>

<script type="text/javascript">
    $(document).ready(function () {

        // Sending Form data to the server
        $("#placeOrder").submit(function (e) {
            e.preventDefault();
            if (validateForm()) {
                $.ajax({
                    url: 'action.php',
                    method: 'post',
                    data: $('form').serialize() + "&action=order",
                    success: function (response) {
                        $("#order").html(response);
                    }
                });
            }
        });

        // Load total no.of items added in the cart and display in the navbar
        load_cart_item_number();

        function load_cart_item_number() {
            $.ajax({
                url: 'action.php',
                method: 'get',
                data: {
                    cartItem: "cart_item"
                },
                success: function (response) {
                    $("#cart-item").html(response);
                }
            });
        }

      // function to validate form

        function validateForm() {
            // Reset error messages
            $(".validation-message").text("");

            let isValid = true;

            // Validate Name
            const name = $("input[name='name']").val().trim();
            if (name === "") {
                $("#name-error").text("Name is required.");
                isValid = false;
            }

            // Validate Email
            const email = $("input[name='email']").val().trim();
            if (email === "") {
                $("#email-error").text("Email is required.");
                isValid = false;
            } else if (!isValidEmail(email)) {
                $("#email-error").text("Invalid email format.");
                isValid = false;
            }

            // Validate Phone
            const phone = $("input[name='phone']").val().trim();
            if (phone === "") {
                $("#phone-error").text("Phone is required.");
                isValid = false;
            } else if (!isValidPhone(phone)) {
                $("#phone-error").text("Invalid phone number format.");
                isValid = false;
            }

            // Validate Address
            const address = $("textarea[name='address']").val().trim();
            if (address === "") {
                $("#address-error").text("Address is required.");
                isValid = false;
            }

            // Validate Payment Mode
            const paymentMode = $("select[name='payment_mode']").val();
            if (paymentMode === "") {
                $("#payment-mode-error").text("Payment Mode is required.");
                isValid = false;
            }

            return isValid;
        }

        function isValidEmail(email) {
            // You can implement a more advanced email validation here
            // For simplicity, this example checks if there's an "@" symbol
            return email.includes("@");
        }

        function isValidPhone(phone) {
            // You can implement phone number validation according to your requirements
            // For simplicity, this example checks if it contains only numbers and optional hyphens or spaces
            return /^[0-9]+[-\s]?[0-9]*$/.test(phone);
        }
    });
</script>

<?php include "footer.php"; ?>
