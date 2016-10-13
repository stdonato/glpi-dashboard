
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
<table cellpadding="0" cellspacing="0" border="0" class="display" id="ticket">
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
$(document).ready(function() {
    oTable = $('#ticket').dataTable({
        "bJQueryUI": true,
        "sPaginationType": "full_numbers",
        "bFilter":false,
        "aaSorting": [[1,'desc'], [0,'asc']],
        "aoColumnDefs": [{ "sWidth": "60%", "aTargets": [1] }],
         "sDom": 'T<"clear">lfrtip',
         "oTableTools": {
         "aButtons": [
             {
                 "sExtends": "copy",
                 "sButtonText": "<?php echo __('Copy'); ?>"
             },
             {
                 "sExtends": "print",
                 "sButtonText": "<?php echo __('Print','dashboard'); ?>"
                 
             },
             {
                 "sExtends":    "collection",
                 "sButtonText": "<?php echo _x('button', 'Export'); ?>",
                 "aButtons":    [ "csv", "xls",
                  {
                 "sExtends": "pdf",
                 "sPdfOrientation": "landscape",
                 "sPdfMessage": ""
                  } ]
             }
         ]
        }
        
    });
} );
		
</script>  
