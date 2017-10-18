<?php

define('GLPI_ROOT', '../../../..');
include (GLPI_ROOT . "/inc/includes.php");
//include (GLPI_ROOT . "/inc/config.php");

global $DB;

Session::checkLoginUser();
Session::checkRight("profile", READ);

$ver = explode(" ",implode(" ",plugin_version_dashboard()));

?>

<html> 
<head>
<title>GLPI - <?php echo __('Tickets') .'  '. __('by Assets','dashboard').'s' ?></title>
<!-- <base href= "<?php $_SERVER['SERVER_NAME'] ?>" > -->
<meta http-equiv="content-type" content="text/html; charset=UTF-8" />
<meta http-equiv="X-UA-Compatible" content="IE=EmulateIE7" />
<meta http-equiv="content-language" content="en-us" />

<link rel="icon" href="../img/dash.ico" type="image/x-icon" />
<link rel="shortcut icon" href="../img/dash.ico" type="image/x-icon" />  
<link href="../css/styles.css" rel="stylesheet" type="text/css" />
<link href="../css/bootstrap.css" rel="stylesheet" type="text/css" />
<link href="../css/font-awesome.css" type="text/css" rel="stylesheet" />

<script type="text/javascript" src="../js/jquery.min.js"></script> 
<script type="text/javascript" src="../js/bootstrap.min.js"></script>

<script type="text/javascript">

    $(window).load(function(){
        $('#myModal').modal('show');
    });
</script> 

</head>

<body>

<!-- Trigger the modal with a button 
<button type="button" class="btn btn-info btn-lg" data-toggle="modal" data-target="#myModal">Open Modal</button>
-->

<!-- Modal -->
<div id="myModal" class="modal fade" role="dialog">
  <div class="modal-dialog">

    <!-- Modal content-->
    <div class="modal-content">
      <div class="modal-header">
        <button type="button" class="close" data-dismiss="modal">&times;</button>
        <h4 class="modal-title">Changelog - <?php echo __('Version')." ". $ver['1']; ?><br></h4>
      </div>
      <div class="modal-body">
      	<!-- Modal content -->
        <p>Some text in the modal.</p>
      </div>
      <div class="modal-footer">
        <button type="button" class="btn btn-default" data-dismiss="modal"><?php echo __('Close'); ?><br></button>
      </div>
    </div>

  </div>
</div>


</body>
</html>