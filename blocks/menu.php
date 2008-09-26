<?php

if (!defined("ICMS_ROOT_PATH")) die("ICMS root path not defined");

function immenu_menu_show($options)
{
	include_once(ICMS_ROOT_PATH . '/modules/immenu/include/common.php');
	$immenu_menuitem_handler = xoops_getModuleHandler('menu', 'immenu');
	var_dump($immenu_menuitem_handler->getItemsForBlock($options));
	$block['items'] = $immenu_menuitem_handler->getItemsForBlock($options);

	return $block;
}

function immenu_menu_edit($options)
{
	include_once(ICMS_ROOT_PATH . '/modules/immenu/include/common.php');

	$form = '<table><tr>';
	$form .= '<td>' . _MB_IMBLOGGING_POST_RECENT_LIMIT . '</td>';
	$form .= '<td>' . '<input type="text" name="options[]" value="' .$options[0] . '"/></td>';
	$form .= '</tr></table>';
    return $form;
}

?>
