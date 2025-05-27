 <?php
 error_reporting(0);
include 'configuration/config_connect.php';
        $queryback="SELECT * FROM backset";
		$resultback=mysqli_query($conn,$queryback);
		$rowback=mysqli_fetch_assoc($resultback);
		$footer=$rowback['footer'];

                        
                
 ?>

 <footer class="main-footer">
                <strong>Copyright © 2025  MC.Barkel, Coffe Patani.</strong>  All rights
                reserved. <?php echo $footer;?>
				</div>
            </footer>


