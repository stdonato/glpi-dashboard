
<?php
$query2 = "
SELECT * FROM `glpi_consumableitems`
".$ent_cons." 
AND is_deleted = 0
ORDER BY name ASC ";
		
$result2 = $DB->query($query2) or die('erro');
	
echo ' 
<table cellpadding="0" cellspacing="0" border="0" class="table table-striped table-bordered table-hover dataTable" id="consumables">
	<thead>
		<tr>
			<th>'. _n('Consumable','Consumables',2).'</th>
			<th>'. __('Quantity','dashboard').'</th>
		</tr>
	</thead>
	<tbody>'; 		

while ($row = $DB->fetch_assoc($result2))	{		

	$query = "
	SELECT count(id) AS conta
	FROM glpi_consumables 
	WHERE consumableitems_id = ".$row['id']." ";
	
	$result = $DB->query($query);
	$quant = $DB->fetch_assoc($result);


	echo '<tr>
				<td><a href=../../../../front/consumableitem.form.php?id='.$row['id'].'
				 	target="_blank"  style="color:#555555;" >'. $row['name'].'</td>
				<td>'. $quant['conta'].'</td>
			</tr>';		
}

echo '		
	</tbody>
</table>';

?>

<script type="text/javascript">

$('#consumables')
	.removeClass( 'display' )
	.addClass('table table-striped table-bordered table-hover dataTable');

$(document).ready(function() {
    $('#consumables').DataTable( {    	

		  select: false,	    	    	
        dom: 'Blfrtip',
        filter: false,        
        pagingType: "full_numbers",
        sorting: [[1,'desc'],[0,'desc']],
		  displayLength: 10,
        lengthMenu: [[10, 25, 50, 100, -1], [10, 25, 50, 100, "All"]],        
        buttons: [
        	    {
                 extend: "copyHtml5",
                 text: "<?php echo __('Copy'); ?>"
             },
             {
             	  extend: "collection",
                 text: "<?php echo __('Print','dashboard'); ?>",
						  buttons:[ 
						  	{               
		                 extend: "print",
		                 autoPrint: true,
		                 text: "<?php echo __('All','dashboard'); ?>",		                		     
		                }, 
							  {               
		                 extend: "print",
		                 autoPrint: true,
		                 text: "<?php echo __('Selected','dashboard'); ?>",		                 
		                 exportOptions: {
		                    modifier: {
		                        selected: true
		                    }
		                }
		                }
	                ]
             },
             {
                 extend: "collection",
                 text: "<?php echo _x('button', 'Export'); ?>",
                 buttons: [ "csvHtml5", "excelHtml5",
                  {
                 		extend: "pdfHtml5",
                 		orientation: "landscape",
                 		message: "",                 		
                  } 
                  ]
             }
        ]
        
    } );
} );
		
</script>  
