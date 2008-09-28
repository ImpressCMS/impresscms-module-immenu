<?php

if (!defined("ICMS_ROOT_PATH")) die("ICMS root path not defined");

function immenu_menu_show($options){
	include_once(ICMS_ROOT_PATH . '/modules/immenu/include/common.php');
	
	$immenu_menuitem_handler = xoops_getModuleHandler('menu', 'immenu');
	$block['items'] = $immenu_menuitem_handler->getItemsForBlock($options);

	return $block;
}

?>
