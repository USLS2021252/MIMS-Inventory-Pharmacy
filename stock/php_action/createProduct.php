<?php
require_once 'core.php';

$valid['success'] = array('success' => false, 'messages' => array());

if ($_POST) {

    $productName    = $_POST['productName'];
    $quantity       = $_POST['quantity'];
    $rate           = $_POST['rate'];
    $brandName      = $_POST['brandName'];
    $categoryName   = $_POST['categoryName'];
    $productStatus  = $_POST['productStatus'];

    // Validate rate as a number
    if (!is_numeric($rate)) {
        $valid['success'] = false;
        $valid['messages'] = "Rate must be a number.";
        echo json_encode($valid);
        exit;
    }

    $type = explode('.', $_FILES['productImage']['name']);
    $type = $type[count($type) - 1];
    $url = '../assets/images/stock/' . uniqid(rand()) . '.' . $type;

    if (in_array($type, array('gif', 'jpg', 'jpeg', 'png', 'JPG', 'GIF', 'JPEG', 'PNG'))) {
        if (is_uploaded_file($_FILES['productImage']['tmp_name'])) {
            if (move_uploaded_file($_FILES['productImage']['tmp_name'], $url)) {

                $sql = "INSERT INTO product (product_name, product_image, brand_id, categories_id, quantity, rate, active, status) 
                VALUES ('$productName', '$url', '$brandName', '$categoryName', '$quantity', '$rate', '$productStatus', 1)";

                if ($connect->query($sql) === TRUE) {
                    $valid['success'] = true;
                    $valid['messages'] = "Successfully Added";
                } else {
                    $valid['success'] = false;
                    $valid['messages'] = "Error while adding the members";
                }
            } else {
                $valid['success'] = false;
                $valid['messages'] = "Error uploading the product image.";
            }
        } else {
            $valid['success'] = false;
            $valid['messages'] = "Failed to upload the product image.";
        }
    } else {
        $valid['success'] = false;
        $valid['messages'] = "Invalid file type for the product image.";
    }

    $connect->close();

    echo json_encode($valid);
}
?>
