<?php
/**
* Menu item redirect page.
* 
* 
* @copyright	ImpressCMS Project 2008
* @license		http://www.gnu.org/licenses/old-licenses/gpl-2.0.html GNU General Public License (GPL)
* @since		1.0
* @author		Gustavo Pilla <nekro@impresscms.org>
* @version		$Id$
*
* @todo I think that is clear that it needs much more intelligence!!
*/
include("header.php");

$menuitem_id = isset($_GET['menuitem_id']) ? intval($_GET['menuitem_id']) : 0 ;

if($menuitem_id == 0){
	redirect_header(ICMS_URL.'/index.php',3,_IM_IMMENU_NOTHING_TODO_HERE);
}

$immenu_menuitem_handler = xoops_getModuleHandler('menuitem');
$menuitemObj = $immenu_menuitem_handler->get($menuitem_id);
$menuitemObj->setVar('menuitem_hits', $menuitemObj->getVar('menuitem_hits') + 1);
$immenu_menuitem_handler->insert($menuitemObj,true);

header("Location: ".$menuitemObj->getVar("menuitem_url"));
?>
