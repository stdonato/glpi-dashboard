<?php

function conv_data($data) {
    if($data != "") {
        $source = $data;
        $date = new DateTime($source);
        
		 switch ($_SESSION['glpidate_format']) {
	    case "0": $dataf = $date->format('Y-m-d'); break;
	    case "1": $dataf = $date->format('d-m-Y'); break;
	    case "2": $dataf = $date->format('m-d-Y'); break;    
    }        
        
        //return $date->format('d-m-Y');}
        return $dataf;}
    else {
        return "";
    }
}


function conv_data_hora($data) {
    if($data != "") {
        $source = $data;
        $date = new DateTime($source);

	    switch ($_SESSION['glpidate_format']) {
		    case "0": $dataf = $date->format('Y-m-d H:i'); break;
		    case "1": $dataf = $date->format('d-m-Y H:i'); break;
		    case "2": $dataf = $date->format('m-d-Y H:i'); break;    
	    }                 
                
    	 return $dataf; 
    } 
    else {
        return "";
    }
}


function time_ext($solvedate)
{

$time = $solvedate; // time duration in seconds

 if ($time == 0){
        return '';
    }

	$days = floor($time / (60 * 60 * 24));
	$time -= $days * (60 * 60 * 24);
	
	$hours = floor($time / (60 * 60));
	$time -= $hours * (60 * 60);
	
	$minutes = floor($time / 60);
	$time -= $minutes * 60;
	
	$seconds = floor($time);
	$time -= $seconds;
	
	$return = "{$days}d {$hours}h {$minutes}m {$seconds}s"; // 1d 6h 50m 31s
	
	return $return;
}


function time_hrs($time)
{

 if ($time == 0){
        return '';
    }

  // $days = floor($time / 86400); // 60*60*24
  // $time -= $days * 86400;
	
	$hours = floor($time / (60 * 60));
	$time -= $hours * (60 * 60);
	
	$minutes = floor($time / 60);
	$time -= $minutes * 60;
	
	$seconds = floor($time);
	$time -= $seconds;
	
	$return = "{$hours}h {$minutes}m {$seconds}s"; // 1d 6h 50m 31s
	
	return $return;
}


function time_hrs2($time)
{

 if ($time == 0){
        return '';
    }

  // $days = floor($time / 86400); // 60*60*24
  // $time -= $days * 86400;
	
	$hours = floor($time / (60 * 60));
	$time -= $hours * (60 * 60);
	
	$minutes = floor($time / 60);
	$time -= $minutes * 60;
	
	$seconds = floor($time);
	$time -= $seconds;
	
	$return = $hours; // 1d 6h 50m 31s
	
	return $return;
}



function dropdown( $name, array $options, $selected=null )
{
    /*** begin the select ***/
    $dropdown = '<select id="sel1" style="width: 300px;" autofocus onChange="javascript: document.form1.submit.focus()" name="'.$name.'" id="'.$name.'">'."\n";

    $selected = $selected;
    /*** loop over the options ***/
    foreach( $options as $key=>$option )
    {
        /*** assign a selected value ***/
        $select = $selected==$key ? ' selected' : null;

        /*** add each option to the dropdown ***/
        $dropdown .= '<option value="'.$key.'"'.$select.'>'.$option.'</option>'."\n";
    }

    /*** close the select ***/
    $dropdown .= '</select>'."\n";

    /*** and return the completed dropdown ***/
    return $dropdown;
}


function dropdown2( $name, array $options, $selected=null )
{
    /*** begin the select ***/
    $dropdown = '<select style="width: 300px; height: 27px;" autofocus name="'.$name.'" id="'.$name.'">'."\n";

    $selected = $selected;
    /*** loop over the options ***/
    foreach( $options as $key=>$option )
    {
        /*** assign a selected value ***/
        $select = $selected==$key ? ' selected' : null;
        /*** add each option to the dropdown ***/
        $dropdown .= '<option value="'.$key.'"'.$select.'>'.$option.'</option>'."\n";
    }
    /*** close the select ***/
    $dropdown .= '</select>'."\n";

    /*** and return the completed dropdown ***/
    return $dropdown;
}


function margins() {

	global $DB;
	$query_lay = "SELECT value FROM glpi_plugin_dashboard_config WHERE name = 'layout' AND users_id = ".$_SESSION['glpiID']." ";																
						$result_lay = $DB->query($query_lay);					
						$layout = $DB->result($result_lay,0,'value');
						
	//redirect to index
	if($layout == '0')
		{
			// sidebar
			$margin = '0px 3% 0px 5%';
		}
	
	if($layout == 1 || $layout == '' )
		{
			//top menu
			$margin = '0px 2% 0px 2%';
		}
		
	return $margin;	
}

//segundos para h:m:s
/*
$segundos = 15058084;
//$converter = date('H:i:s',mktime(0,0,$segundos,15,03,2013));//Converter os segundos em no formato mm:ss
$converter = date('H:i:s',mktime(0,0,$segundos));//Converter os segundos em no formato mm:ss
echo $converter;//no exemplo ira retornar 02:15	
*/


function getEntityName($id) {

	global $DB;
	
 	if ($id == ''){
   	return '';
   }
   
   else { 
	$sql_ent = "
	SELECT name
	FROM `glpi_entities`
	WHERE id = ".$id." ";
	
	$result_ent = $DB->query($sql_ent);
	$name = $DB->result($result_ent,0,'name');
	return $name;
	}

}



function getEntityLevel($id) {

	global $DB;
	
 	if ($id == ''){
   	return '';
   }
   
   else { 
	$sql_ent = "
	SELECT level
	FROM `glpi_entities`
	WHERE id = ".$id." ";
	
	$result_ent = $DB->query($sql_ent);
	$level = $DB->result($result_ent,0,'level');
	return $level;
	}

}



function getChildren($node) {
	
global $DB;
global $nodes;	

if ($node == -1) {
      $pos = 0;

      foreach ($_SESSION['glpiactiveprofile']['entities'] as $entity) {
         $ID                           = $entity['id'];
         $is_recursive                 = $entity['is_recursive'];

         $path = [
            // append r for root nodes, id are uniques in jstree.
            // so, in case of presence of this id in subtree of other nodes,
            // it will be removed from root nodes
            'id'   => $ID.'r',
            'text' => Dropdown::getDropdownName("glpi_entities", $ID)
         ];

         if ($is_recursive) {
            $path['children'] = true;
            $query2 = "SELECT count(*)
                       FROM `glpi_entities`
                       WHERE `entities_id` = '$ID'";
            $result2 = $DB->query($query2);
            if ($DB->result($result2, 0, 0) > 0) {
               //apend a i tag (one of shortest tags) to have the is_recursive link
               //$path['text'].= '<i/>';
               if (isset($ancestors[$ID])) {
                  $path['state']['opened'] = 'true';
               }
            }
         }
         $nodes[] = $path;
      }
   } else { // standard node
      $node_id = $node;
      $query   = "SELECT ent.`id`, ent.`name`, ent.`sons_cache`, count(sub_entities.id) as nb_subs, ent.`level`
                  FROM `glpi_entities` as ent
                  LEFT JOIN `glpi_entities` as sub_entities
                     ON sub_entities.entities_id = ent.id
                  WHERE ent.`entities_id` = '$node_id'
                  GROUP BY ent.`id`, ent.`name`, ent.`sons_cache`
                  ORDER BY `name`";

      if ($result = $DB->query($query)) {
         while ($row = $DB->fetch_assoc($result)) {
            $path = [
               'id'   => $row['id'],
               'text' => $row['name'],
               'level' => $row['level'],
            ];

            if ($row['nb_subs'] > 0) {
               //apend a i tag (one of shortest tags) to have the is_recursive link
               //$path['text'].= '<i/>';
               $path['children'] = true;

               if (isset($ancestors[$row['id']])) {
                  $path['state']['opened'] = 'true';
               }
            }
            $nodes[] = $path;
         }
      }
   }

//return json_encode($nodes);
return $nodes;

}


function unique_multidim_array($array, $key) {
    $temp_array = array();
    $i = 0;
    $key_array = array();
   
    foreach($array as $val) {
        if (!in_array($val[$key], $key_array)) {
            $key_array[$i] = $val[$key];
            $temp_array[$i] = $val;
        }
        $i++;
    }
    return $temp_array;
} 


function super_unique($array)
{
  $result = array_map("unserialize", array_unique(array_map("serialize", $array)));

  foreach ($result as $key => $value)
  {
    if ( is_array($value) )
    {
      $result[$key] = super_unique($value);
    }
  }

  return $result;
}


//calcular custos de chamado

   function computeCost($item) {
      global $DB;

      $totalcost = 0;

      $query = "SELECT `glpi_ticketcosts`.*
                FROM `glpi_tickets`, `glpi_ticketcosts`
                WHERE `glpi_ticketcosts`.`tickets_id` = `glpi_tickets`.`id`
                AND glpi_tickets.id = ".$item."
                      AND (`glpi_ticketcosts`.`cost_time` > '0'
                           OR `glpi_ticketcosts`.`cost_fixed` > '0'
                           OR `glpi_ticketcosts`.`cost_material` > '0')";
      $result = $DB->query($query);

      $i = 0;
      if ($DB->numrows($result)) {
         while ($data = $DB->fetch_assoc($result)) {
            $totalcost += TicketCost::computeTotalCost($data["actiontime"], $data["cost_time"],
                                                       $data["cost_fixed"], $data["cost_material"]);
         }
      }
      return $totalcost;
   }
?>




