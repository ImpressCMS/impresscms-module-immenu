<?php
/**
*
* @copyright	ImpressCMS Project 2008
* @license		http://www.gnu.org/licenses/old-licenses/gpl-2.0.html GNU General Public License (GPL)
* @since		1.0
* @author		Gustavo Pilla <nekro@impresscms.org>
* @version		$Id$
*
* @todo I think that is clear that it needs much more intelligence!!
*/
include("../../mainfile.php");
$immenu_menuitem_handler = xoops_getModuleHandler('menuitem');
$menuitem_id = isset($_GET['menuitem_id']) ? intval($_GET['menuitem_id']) : 0 ;

$menuitemObj = $immenu_menuitem_handler->get($menuitem_id);

header("Location: ".$menuitemObj->getVar("menuitem_url"));
?>
