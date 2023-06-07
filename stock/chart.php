<?php require_once 'includes/header.php';

$servername = "localhost";
$username = "root";
$password = "";
$dbname = "stock";

$conn = new mysqli($servername, $username, $password, $dbname);

if ($conn->connect_error) {
    die("Connection failed: " . $conn->connect_error);
}

// Retrieve data
$sql = "SELECT DATE_FORMAT(order_date, '%Y-%m') AS month, brands.brand_name, SUM(order_item.quantity) AS total_quantity
        FROM orders
        INNER JOIN order_item ON orders.order_id = order_item.order_id
        INNER JOIN product ON order_item.product_id = product.product_id
        INNER JOIN brands ON product.brand_id = brands.brand_id
        GROUP BY month, brands.brand_name";

$result = $conn->query($sql);

// Prepare data for the chart
$chartData = array();
$brands = array();
$months = array();

if ($result->num_rows > 0) {
    while ($row = $result->fetch_assoc()) {
        $brand = $row["brand_name"];
        $month = $row["month"];
        $quantity = intval($row["total_quantity"]);

        // Build the data array
        if (!isset($chartData[$brand])) {
            $chartData[$brand] = array();
        }
        $chartData[$brand][$month] = $quantity;

        // Collect unique brand names and months
        if (!in_array($brand, $brands)) {
            $brands[] = $brand;
        }
        if (!in_array($month, $months)) {
            $months[] = $month;
        }
    }
}

// Generate chart labels and data series
$chartLabels = json_encode($months);
$chartSeries = array();

foreach ($brands as $brand) {
    $data = array();

    foreach ($months as $month) {
        $data[] = isset($chartData[$brand][$month]) ? $chartData[$brand][$month] : 0;
    }

    $chartSeries[] = array(
        "label" => $brand,
        "data" => $data
    );
}

// Close the database connection
$conn->close();
?>

<!DOCTYPE html>
<html>
<head>
    <title>Total Quantity Sold by Brand and Month</title>
    <script src="https://cdn.jsdelivr.net/npm/chart.js"></script>
</head>
<body>
    <canvas id="chart"></canvas>

    <script>
        var ctx = document.getElementById("chart").getContext("2d");

        var chart = new Chart(ctx, {
            type: 'bar',
            data: {
                labels: <?php echo $chartLabels; ?>,
                datasets: <?php echo json_encode($chartSeries); ?>
            },
            options: {
                scales: {
                    y: {
                        beginAtZero: true,
                        title: {
                            display: true,
                            text: 'Total Quantity Sold'
                        }
                    },
                    x: {
                        title: {
                            display: true,
                            text: 'Month'
                        }
                    }
                }
            }
        });
    </script>
</body>
</html>
