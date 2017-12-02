<?php

$query2 = "
SELECT id, name, contact, users_id, groups_id, phonetypes_id, phonemodels_id, brand, manufacturers_id, states_id 
FROM glpi_phones
WHERE glpi_phones.is_deleted = 0
AND is_template = 0 
".$ent_phone."
ORDER BY id ASC ";
	
$result2 = $DB->query($query2) or die('erro');
	
echo ' 
<table cellpadding="0" cellspacing="0" border="0" class="display" id="phone_report">
	<thead>
		<tr>
		<th style="color:#555;">'. __('ID').'</th>
		<th style="color:#555;">'. __('Name').'</th>
		<th style="color:#555;">'. __('Status').'</th>
		<th style="color:#555;">'. __('Manufacturer').'</th>
		<th style="color:#555;">'. __('Type').'</th>
		<th style="color:#555;">'. __('Model').'</th>
		<th style="color:#555;">'. __('User').'</th>
		<th style="color:#555;">'. __('Group').'</th>		
		</tr>
	</thead>
	<tbody>'; 		

while ($row = $DB->fetch_assoc($result2))		
{		
	
	$sql_state = "SELECT name FROM glpi_states WHERE id = ".$row['states_id']." ";
	$res_state = $DB->query($sql_state);
	$state = $DB->result($res_state,0,'name');
	
	$sql_fab = "SELECT name FROM glpi_manufacturers WHERE id = ".$row['manufacturers_id']." ";
	$res_fab = $DB->query($sql_fab);
	$fab = $DB->result($res_fab,0,'name');
	
	$sql_type = "SELECT name FROM glpi_phonetypes WHERE id = ".$row['phonetypes_id']." ";
	$res_type = $DB->query($sql_type);
	$type = $DB->result($res_type,0,'name');
	
	$sql_model = "SELECT name FROM glpi_phonemodels WHERE id = ".$row['phonemodels_id']." ";
	$res_model = $DB->query($sql_model);
	$model = $DB->result($res_model,0,'name');
	
	$sql_group = "SELECT name FROM glpi_groups WHERE id = ".$row['groups_id']." ";
	$res_group = $DB->query($sql_group);
	$group = $DB->result($res_group,0,'name');

	echo '<tr>
			<td>'. $row['id'].'</td>
			<td><a href=../../../../front/phone.form.php?id='.$row['id'].' target="_blank"  style="color:#555555;" >'. $row['name'].'</td>
			<td>'. $state.'</td>
			<td>'. $fab.'</td>
			<td>'. $type.'</td>
			<td>'. $model.'</td>
			<td>'. getUserName($row['users_id']).'</td>
			<td>'. $group.'</td>
			</tr>';		
}

echo '		
	</tbody>
</table>';

?>

<script type="text/javascript" >

$('#phone_report')
	.removeClass( 'display' )
	.addClass('table table-striped table-bordered table-hover dataTable');

$(document).ready(function() {
   $('#phone_report').DataTable({    	

		  select: true,	    	    	
        dom: 'Blfrtip',
        filter: false,        
        pagingType: "full_numbers",
        sorting: [[0,'desc'],[1,'desc'],[2,'desc'],[3,'desc'],[4,'desc'],[5,'desc'],[6,'desc'],[7,'desc']],
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
		                 //message: "<div id='print' class='info_box row-fluid' style='margin-bottom:35px; margin-left: -1px;'><table id='print_tb' class='row-fluid'  style='width: 80%; margin-left: 10%; font-size: 18px; font-weight:bold;' cellpadding = '1px'><td style='font-size: 16px; font-weight:bold; vertical-align:middle;'><span style='color:#000;'> <?php echo __('Location'); ?> : </span><?php echo $ent_name['name']; ?> </td> <td colspan='2' style='font-size: 16px; font-weight:bold; vertical-align:middle;'><span style='color:#000;'> <?php echo  __('Tickets','dashboard'); ?> : </span><?php echo //$consulta ; ?></td><td colspan='2' style='font-size: 16px; font-weight:bold; vertical-align:middle; width:200px;'><span style='color:#000;'> <?php echo  __('Period','dashboard'); ?> : </span> <?php //echo conv_data($data_ini2); ?> a <?php //echo conv_data($data_fin2); ?> </td> </table></div>",		     
		                }, 
							  {               
		                 extend: "print",
		                 autoPrint: true,
		                 text: "<?php echo __('Selected','dashboard'); ?>",
		                 //message: "<div id='print' class='info_box row-fluid' style='margin-bottom:35px; margin-left: -1px;'><table id='print_tb' class='row-fluid'  style='width: 80%; margin-left: 10%; font-size: 18px; font-weight:bold;' cellpadding = '1px'><td style='font-size: 16px; font-weight:bold; vertical-align:middle;'><span style='color:#000;'> <?php echo __('Location'); ?> : </span><?php echo $ent_name['name']; ?> </td> <td colspan='2' style='font-size: 16px; font-weight:bold; vertical-align:middle;'><span style='color:#000;'> <?php echo  __('Tickets','dashboard'); ?> : </span><?php echo //$consulta ; ?></td><td colspan='2' style='font-size: 16px; font-weight:bold; vertical-align:middle; width:200px;'><span style='color:#000;'> <?php echo  __('Period','dashboard'); ?> : </span> <?php //echo conv_data($data_ini2); ?> a <?php //echo conv_data($data_fin2); ?> </td> </table></div>",
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
                 		//message: "<?php echo __('Location'); ?> : <?php echo $ent_name['name'] .'  -  '; ?>  <?php echo  __('Tickets','dashboard'); ?> : <?php echo //$consulta . '  -  '; ?> <?php echo  __('Period','dashboard'); ?> : <?php //echo conv_data($data_ini2); ?> a <?php //echo conv_data($data_fin2); ?>",
                  } 
                  ]
             }
        ]
        
    } );
} );
		
</script>  
