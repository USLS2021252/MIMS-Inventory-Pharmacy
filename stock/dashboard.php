<?php require_once 'includes/header.php';
$permission_id = $_SESSION['permission_id']; ?>

<?php 

$sql = "SELECT * FROM product WHERE status = 1";
$query = $connect->query($sql);
$countProduct = $query->num_rows;

$orderSql = "SELECT * FROM orders WHERE order_status = 1";
$orderQuery = $connect->query($orderSql);
$countOrder = $orderQuery->num_rows;

$totalRevenue = "";
while ($orderResult = $orderQuery->fetch_assoc()) {
      $totalRevenue = $orderResult['paid'];
}

$lowStockSql = "SELECT * FROM product WHERE quantity <= 3 AND status = 1";
$lowStockQuery = $connect->query($lowStockSql);
$countLowStock = $lowStockQuery->num_rows;

$connect->close();

?>


<style type="text/css">
	.ui-datepicker-calendar {
		display: none;
	}
</style>


    <link rel="stylesheet" href="assests/plugins/fullcalendar/fullcalendar.min.css">
    <link rel="stylesheet" href="assests/plugins/fullcalendar/fullcalendar.print.css" media="print">


<div class="row">
	
	<div class="col-md-4">
		<div class="panel panel-success">
			<div class="panel-heading">
				
				<a href="product.php" style="text-decoration:none;color:black;">
					Total Product
					<span class="badge pull pull-right"><?php echo $countProduct; ?></span>	
				</a>
				
			</div> 
		</div> 
	</div> 

		<div class="col-md-4">
			<div class="panel panel-info">
			<div class="panel-heading">
				<a href="orders.php?o=manord" style="text-decoration:none;color:black;">
					Total Orders
					<span class="badge pull pull-right"><?php echo $countOrder; ?></span>
				</a>
					
			</div> 
		</div> 
		</div> 

	<div class="col-md-4">
		<div class="panel panel-danger">
			<div class="panel-heading">
				<a href="product.php" style="text-decoration:none;color:black;">
					Low Stock
					<span class="badge pull pull-right"><?php echo $countLowStock; ?></span>	
				</a>
				
			</div> 
		</div> 
	</div> 

	<div class="col-md-4">
    <div class="card">
        <div class="cardHeader">
            <h1>Welcome!</h1>
            
        </div>

        <div class="cardContainer">
            <p><?php echo date('l') .' '.date('d').', '.date('Y'); ?></p>
			<?php if ($permission_id != 1): ?>
                <a href="chart.php" class="btn btn-success btn-block">Charts</a>
            <?php endif; ?>
        </div>
    </div> 
</div>



	<div class="col-md-8">
		
			<center>
			<img src="logo.png" alt="..." width="50%">
		</center>
		
		
	</div>

	
</div> 


<script src="assests/plugins/moment/moment.min.js"></script>
<script src="assests/plugins/fullcalendar/fullcalendar.min.js"></script>


<script type="text/javascript">
	$(function () {
			// top bar active
	$('#navDashboard').addClass('active');

      //Date for the calendar events (dummy data)
      var date = new Date();
      var d = date.getDate(),
      m = date.getMonth(),
      y = date.getFullYear();

      $('#calendar').fullCalendar({
        header: {
          left: '',
          center: 'title'
        },
        buttonText: {
          today: 'today',
          month: 'month'          
        }        
      });


    });
</script>

<?php require_once 'includes/footer.php'; ?>