<?php

$query_os = "
SELECT glpi_items_tickets.itemtype AS tipo, glpi_items_tickets.`items_id` AS id , COUNT(*) AS conta
FROM glpi_items_tickets, glpi_tickets
WHERE glpi_items_tickets.`itemtype` <> ''
AND glpi_items_tickets.`items_id` > 0
AND glpi_items_tickets.`itemtype` <> 'PluginProjetProjet'
AND glpi_items_tickets.tickets_id = glpi_tickets.id
AND glpi_tickets.is_deleted = 0
".$ent_global1."
GROUP BY glpi_items_tickets.`itemtype` , glpi_items_tickets.`items_id`
ORDER BY conta DESC
LIMIT 100  ";

$result_os = $DB->query($query_os) or die('erro_global');


$arr_grf_os = array();

while ($row_result = $DB->fetch_assoc($result_os))	
	{ 
	$v_row_result = $row_result['tipo'];
	$arr_grf_os[$v_row_result] = $row_result['conta'];			
	} 
	
$grf_os2 = array_keys($arr_grf_os);
$quant_os2 = array_values($arr_grf_os);

$conta_os = count($arr_grf_os);


echo ' 
<table cellpadding="0" cellspacing="0" border="0" class="display" id="a_tickets">
	<thead>
		<tr>
		<th>'. __('Assets').'</th>
		<th>'. __('Type').'</th>
		<th>'. __('Tickets').'</th>
		</tr>
	</thead>
	<tbody>'; 		

$DB->data_seek($result_os,0);
while ($row_result = $DB->fetch_assoc($result_os))	
{		

$tipo = strtolower($row_result['tipo']);
$name = strtolower($row_result['tipo'])."s";
$id = $row_result['id'];

$query = "
SELECT name AS name
FROM glpi_".$name."
WHERE id = ".$id." ";

		
$result = $DB->query($query) or die('erro');

while ($row = $DB->fetch_assoc($result))
{
	echo '<tr>
			<td><a href=../../../../front/'.$tipo.'.form.php?id='.$id.' target="_blank"  style="color:#555555;" >'. $row['name'].'</a></td>
			<td>'. __($row_result['tipo']).'</td>
			<td>'. $row_result['conta'].'</td>
			</tr>';		
}

}

echo '		
	</tbody>
</table>';
?>

<script type="text/javascript" >
$('#a_tickets')
	.removeClass( 'display' )
	.addClass('table table-striped table-bordered table-hover dataTable');

$(document).ready(function() {
   $('#a_tickets').DataTable({    	

		  select: false,	    	    	
        dom: 'Blfrtip',
        filter: false,        
        pagingType: "full_numbers",
        sorting: [[2,'desc'],[1,'desc']],
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
