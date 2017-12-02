
<?php

$query2 = "
SELECT glpi_computers.name AS name, count( glpi_tickets.id ) AS conta, glpi_computers.id AS cid
FROM glpi_tickets, glpi_computers, glpi_items_tickets
WHERE glpi_items_tickets.itemtype = 'computer'
AND glpi_items_tickets.items_id = glpi_computers.id
AND glpi_items_tickets.tickets_id = glpi_tickets.id
AND glpi_computers.is_deleted =0
".$ent_comp."
GROUP BY items_id
ORDER BY `conta` DESC ";
		
$result2 = $DB->query($query2) or die('erro');

	
echo ' 
<table cellpadding="0" cellspacing="0" border="0" class="table table-striped table-bordered table-hover dataTable" id="comp_ticket">
	<thead>
		<tr>
		<th style="color:#555;">'. __('Computer').'</th>
		<th style="color:#555;">'. __('Tickets','dashboard').'</th>
		</tr>
	</thead>
	<tbody>'; 		

	while ($row = $DB->fetch_assoc($result2))		
	{		
		echo '<tr>
				<td><a href=../../../../front/computer.form.php?id='.$row['cid'].' target="_blank"  style="color:#555555;" >'. $row['name'].'</td>
				<td>'. $row['conta'].'</td>
				</tr>';		
	}
	
	echo '		
	</tbody>
</table>';

?>

<script type="text/javascript" >

$('#comp_ticket')
	.removeClass( 'display' )
	.addClass('table table-striped table-bordered table-hover dataTable');

$(document).ready(function() {
   $('#comp_ticket').DataTable({    	

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
