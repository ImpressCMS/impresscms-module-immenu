<?php
/**
* Configuring the amdin side menu for the module
*
* @copyright	http://smartfactory.ca The SmartFactory
* @license		http://www.gnu.org/licenses/old-licenses/gpl-2.0.html GNU General Public License (GPL)
* @since		1.0
* @author		marcan aka Marc-André Lanciault <marcan@smartfactory.ca>
* @version		$Id$
*/

$i = -1;

$i++;
$adminmenu[$i]['title'] = _MI_IMMENU_MENUS;
$adminmenu[$i]['link'] = "admin/menu.php";

$i++;
$adminmenu[$i]['title'] = _MI_IMMENU_MENU_TEMPLATES;
$adminmenu[$i]['link'] = "admin/template.php";

$i++;
$adminmenu[$i]['title'] = _MI_IMMENU_BLOCKS;
$adminmenu[$i]['link'] = "admin/block.php";



global $xoopsModule;
if (isset($xoopsModule)) {

	$i = -1;

	//$i++;
	//$headermenu[$i]['title'] = _PREFERENCES;
	//$headermenu[$i]['link'] = '../../system/admin.php?fct=preferences&amp;op=showmod&amp;mod=' . $xoopsModule->getVar('mid');

	/*
	$i++;
	$headermenu[$i]['title'] = _CO_ICMS_GOTOMODULE;
	$headermenu[$i]['link'] = ICMS_URL . '/modules/'.$xoopsModule->getVar('dirname').'/';
	*/
	
	$i++;
	$headermenu[$i]['title'] = _CO_ICMS_UPDATE_MODULE;
	$headermenu[$i]['link'] = ICMS_URL . '/modules/system/admin.php?fct=modulesadmin&op=update&module=' . $xoopsModule->getVar('dirname');

	$i++;
	$headermenu[$i]['title'] = _MODABOUT_ABOUT;
	$headermenu[$i]['link'] = ICMS_URL . '/modules/'.$xoopsModule->getVar('dirname').'/admin/about.php';
}
?>
