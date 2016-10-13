
<?php

$query2 = "
SELECT glpi_deviceprocessors.designation AS name, glpi_items_deviceprocessors.frequency AS freq, count( glpi_items_deviceprocessors.id ) AS conta
FROM glpi_items_deviceprocessors
LEFT JOIN glpi_deviceprocessors ON ( glpi_deviceprocessors.id = glpi_items_deviceprocessors.deviceprocessors_id )
WHERE glpi_items_deviceprocessors.is_deleted = 0

GROUP BY name
ORDER BY `conta` DESC ";

		
$result2 = $DB->query($query2) or die('erro');
$conta = $DB->numrows($result2);

if($conta != 0) {
	
echo ' 
<table cellpadding="0" cellspacing="0" border="0" class="display" id="cpu">
	<thead>
		<tr>
		<th>'. __('CPU').'</th>
		<th>'. __('Frequency').'</th>
		<th>'. __('Quantity','dashboard').'</th>
		</tr>
	</thead>
	<tbody>'; 		

while ($row = $DB->fetch_assoc($result2))		
{		
	echo '<tr>
			<td>'. $row['name'].'</td>
			<td>'. $row['freq'].'</td>
			<td>'. $row['conta'].'</td>
			</tr>';		
}

echo '		
	</tbody>
</table>';
}

?>

<script type="text/javascript" >
$(document).ready(function() {
    oTable = $('#cpu').dataTable({
        "bJQueryUI": true,
        "sPaginationType": "full_numbers",
        "bFilter":false,
        "aaSorting": [[2,'desc'], [0,'asc']],
        "aoColumnDefs": [{ "sWidth": "45%", "aTargets": [1] }],
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
                 "sButtonText": "<?php echo __('Export'); ?>",
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
