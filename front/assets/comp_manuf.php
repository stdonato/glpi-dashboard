
<?php
$query2 = "
SELECT glpi_manufacturers.name AS name, count( glpi_computers.id ) AS conta
FROM glpi_manufacturers, glpi_computers
WHERE glpi_computers.is_deleted = 0
AND glpi_manufacturers.id = glpi_computers.manufacturers_id
".$ent_comp."
GROUP BY glpi_manufacturers.name
ORDER BY count( glpi_computers.id ) DESC ";
		
$result2 = $DB->query($query2) or die('erro');
	
echo ' 
<table cellpadding="0" cellspacing="0" border="0" class="table table-striped table-bordered table-hover dataTable" id="manufac">
	<thead>
		<tr>
			<th>'. __('Manufacturer').'</th>
			<th>'. __('Quantity','dashboard').'</th>
		</tr>
	</thead>
	<tbody>'; 		

while ($row = $DB->fetch_assoc($result2))		
{		
	echo '<tr>
				<td><a href=../../../../front/computer.php?is_deleted=0&field[0]=view&searchtype[0]=contains&contains[0]='. urlencode($row['name']) .'&itemtype=Computer&start=0
				 	target="_blank"  style="color:#555555;" >'. $row['name'].'</td>
				<td>'. $row['conta'].'</td>
			</tr>';		
}

echo '		
	</tbody>
</table>';

?>

<script type="text/javascript">

$('#manufac')
	.removeClass( 'display' )
	.addClass('table table-striped table-bordered table-hover dataTable');

$(document).ready(function() {
    $('#manufac').DataTable( {    	

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
		                 7
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
