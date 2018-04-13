<?php

include ("../../../../inc/includes.php");
global $DB, $CFG_GLPI;

Session::checkLoginUser();
Session::checkRight("profile", READ);
?>

<html> 
<head>
<title> GLPI - <?php echo __('Metrics','dashboard'); ?> </title>
<!-- <base href= "<?php $_SERVER['SERVER_NAME'] ?>" > -->
<meta http-equiv="content-type" content="text/html; charset=UTF-8" />
  <meta http-equiv="X-UA-Compatible" content="IE=EmulateIE7" />
  <meta http-equiv="content-language" content="en-us" />
  <link href="../css/styles.css" rel="stylesheet" type="text/css" />
  <link href="../css/bootstrap.css" rel="stylesheet" type="text/css" />
  <link href="../css/bootstrap-responsive.css" rel="stylesheet" type="text/css" />
  <link href="../css/font-awesome.css" type="text/css" rel="stylesheet" />
  
  <script src="../js/jquery.min.js" type="text/javascript" ></script>
  <script src="../js/jquery.jclock.js"></script>
  
	<link href="../inc/select2/select2.css" rel="stylesheet" type="text/css">
	<script src="../inc/select2/select2.js" type="text/javascript" language="javascript"></script>
	
	<?php echo '<link rel="stylesheet" type="text/css" href="../css/style-'.$_SESSION['style'].'">';  ?> 

</head>
<body style="background-color: #e5e5e5; margin-left:0%;">

<?php

$status = "('5','6')"	;	

$sql = "SELECT COUNT( * ) AS total
FROM glpi_tickets
WHERE glpi_tickets.status
NOT IN ".$status."
AND glpi_tickets.is_deleted = 0" ;

$result = $DB->query($sql);
$data = $DB->fetch_assoc($result);

$abertos = $data['total']; 

function dropdown( $name, array $options, $selected=null )
{
    /*** begin the select ***/
    $dropdown = '<select class="chosen-select" tabindex="-1" style="width: 300px; height: 27px;" autofocus onChange="javascript: document.form1.submit.focus()" name="'.$name.'" id="'.$name.'">'."\n";

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

# entity
$sql_e = "SELECT value FROM glpi_plugin_dashboard_config WHERE name = 'entity' AND users_id = ".$_SESSION['glpiID']."";
$result_e = $DB->query($sql_e);
$sel_ent = $DB->result($result_e,0,'value');

//select entity
if($sel_ent == '' || $sel_ent == -1) {
	
	$entities = $_SESSION['glpiactiveentities'];										
	$ent = implode(",",$entities);
	
	$entidade = "WHERE entities_id IN (".$ent.") OR is_recursive = 1 ";

}
else {
	$entidade = "WHERE entities_id IN (".$sel_ent.") OR is_recursive = 1 ";
}


$sql_grp = "
SELECT id AS id , name AS name
FROM `glpi_groups`
".$entidade."
ORDER BY `name` ASC";

$result_grp = $DB->query($sql_grp);
$ent = $DB->fetch_assoc($result_grp);

$res_grp = $DB->query($sql_grp);
$arr_grp = array();
$arr_grp[0] = "-- ". __('Select a group', 'dashboard') . " --" ;

$DB->data_seek($result_grp, 0) ;

while ($row_result = $DB->fetch_assoc($result_grp))		
	{ 
	$v_row_result = $row_result['id'];
	$arr_grp[$v_row_result] = $row_result['name'] ;			
	} 
	
$name = 'sel_grp';
$options = $arr_grp;
$selected = "0";

?>

<div id='content' >
<div id='container-fluid' style="margin: 0px 5% 0px 5%;"> 

	<div id="charts" class="fluid chart"> 
		<div id="head" class="fluid">		
		<a href="../index.php"><i class="fa fa-home" style="font-size:14pt; margin-left:25px;"></i><span></span></a>		
		<div id="titulo_graf"> <?php echo __('Metrics', 'dashboard') .'  '. __('by Group', 'dashboard') ?> </div>		
			<div id="datas-cham" class="col-md-12 fluid" >	
				<form id="form1" name="form1" class="form_rel" method="post" action="select_grupo.php?sel=1">			
					<table border="0" cellspacing="0" cellpadding="1" bgcolor="#efefef" width="300px">
						<tr>
						<td>
							<?php echo dropdown( $name, $options, $selected ); ?>
						</td>
						</tr>						
						<tr><td>&nbsp;</td></tr>						
						<tr>
						<td align="center" >
							<button class="btn btn-primary btn-sm" type="submit" name="submit" value="Atualizar" ><i class="fa fa-search"></i>&nbsp; <?php echo __('Consult', 'dashboard'); ?></button>
							<button class="btn btn-primary btn-sm" type="button" name="Limpar" value="Limpar" onclick="location.href='select_grupo.php'" > <i class="fa fa-trash-o"></i>&nbsp; <?php echo __('Clean', 'dashboard'); ?> </button></td>
						</td>
						</tr>					
					</table>
				<?php Html::closeForm(); ?>
				<!-- </form> -->
			</div>	
		</div>
	
	<script type="text/javascript" >
	$(document).ready(function() { $("#sel_grp").select2(); });
	</script>
	
	<?php
	
	if(isset($_REQUEST['sel'])){
		$sel = $_REQUEST['sel'];
	}
	else {$sel = '';}
	
	if($sel == "1") {
	 
	if(!isset($_POST["sel_grp"])) {
	$id_grp = $_REQUEST["ent"];	
	}
	
	else {
	$id_grp = $_POST["sel_grp"];
	}
	
	if($id_grp == " " || $id_grp == 0) {
	echo '<script language="javascript"> alert(" ' . __('Select a group', 'dashboard') . ' "); </script>';
	echo '<script language="javascript"> location.href="select_grupo.php"; </script>';
	}	
	?>	
	<script type="text/javascript" >
	location.href="index.php?grp=<?php echo $id_grp; ?>";
	</script>		
	
	</div>
</div>
</div>
</body>
</html>

<?php } ?>

