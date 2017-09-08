<?php

class PluginDashboardConfig extends CommonDBTM {
	

   static protected $notable = true;
   
   /**
    * @see CommonGLPI::getMenuName()
   **/
   static function getMenuName() {
      return __('Dashboard');
   }
   
   /**
    *  @see CommonGLPI::getMenuContent()
    *
    *  @since version 0.5.6
   **/
   static function getMenuContent() {
   	global $CFG_GLPI;
   
   	$menu = array();

      $menu['title']   = __('Dashboard','dashboard');
      $menu['page']    = '/plugins/dashboard/front/index.php';
   	return $menu;
   }	

// Entity Tab	

   function getTabNameForItem(CommonGLPI $item, $withtemplate = 0) {
      switch (get_class($item)) {
         case 'Entity':
            return array(1 => __('Dashboard map','dashboard'));
         default:
            return '';
      }
   }

   static function displayTabContentForItem(CommonGLPI $item, $tabnum = 1, $withtemplate = 0) {
      switch (get_class($item)) {
         case 'Entity':
            //$config = new self($item->fields['id']);
            $config = new self();
            $config->showFormDisplay();
            break;
      }
      return true;
   }

  /**
    * Print the config form for display
    *
    * @return Nothing (display)
    * */
   function showFormDisplay() {
      global $CFG_GLPI, $DB;

      if (!Config::canView()) {
         return false;
      }           
      
      //get entity coordinates
      if(isset($_GET['id'])) {
	      $query_coo = "SELECT * FROM glpi_plugin_dashboard_map WHERE entities_id = ".$_GET['id'];
	      $result_coo = $DB->query($query_coo) or die ("erro");
			$ent_info = $DB->fetch_assoc($result_coo);
			
			$LNG = $ent_info['lng'];
			$LAT = $ent_info['lat'];
		}
		else {
			$LNG = '';
			$LAT = '';
		}	

      $canedit = Session::haveRight(Config::$rightname, UPDATE);
      if ($canedit) {         
         echo "<form name='form' action='../plugins/dashboard/front/map/insert_coord.php' method='post'>";
      }
      echo Html::hidden('config_context', ['value' => 'dashboard']);
      echo Html::hidden('config_class', ['value' => __CLASS__]);            

      echo "<div class='center' id='tabsbody'>";

      echo "<table class='tab_cadre_fixe' style='width:95%;'>";

      echo "<tr><th colspan='4'>" . __('Setup') . "</th></tr>";     
      
      echo "<tr class='tab_bg_2'></tr>";      		

      echo "<tr class='tab_bg_2'>";      
      echo "<td>". __('Latitude') ."</td>";      
      echo "<td><input type='text' class='form-control' id='lat' name='lat' value=".$LAT."></td>";           
		echo "</tr>";		

      echo "<tr class='tab_bg_2'>";      
      echo "<td width='110px'>". __('Longitude') ."</td>";
      echo "<td><input type='text' class='form-control' id='lng' name='lng' value=".$LNG."></td>";
		echo "</tr>";
		
      echo "<tr class='tab_bg_2'><td>&nbsp;</td></tr>";      

      echo "<td><input type='hidden' id='id' name='id' value=".$_GET['id']."></td>";           
		
		if ($canedit) {
         echo "<tr class='tab_bg_2'>";
         echo "<td colspan='4' class='center'>";
         echo "<input type='submit' name='update' class='submit' value=\"" . _sx('button', 'Save') . "\">";
         echo "</td></tr>";
      }
		
      echo "</table></div>";
      Html::closeForm();
   }


}
?>   