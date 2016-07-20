
<div class="footer">
    <div class="pull-right">
        <?php 
            function HumanSize($Bytes)
            {
              $Type=array("", "KB", "MB", "GB", "TB", "PG", "EB", "ZB", "YB");
              $Index=0;
              while($Bytes>=1024)
              {
                $Bytes/=1024;
                $Index++;
              }
              return("".number_format ($Bytes, 2)." ".$Type[$Index]);
            }
            $df = HumanSize(disk_free_space("../") );  
            $dts = HumanSize(disk_total_space("../") );  
        ?>
        <?php echo $df;?> of <strong><?php echo $dts;?></strong> Free.
    </div>
    <div>
        <strong>Product of</strong> Đỗ Đức Tín &copy; 2016
    </div>
</div>