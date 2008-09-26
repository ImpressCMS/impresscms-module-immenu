<?php
/**
 * Menu Administration Page
 * 
 * @author Gustavo Pilla <nekro@impresscms.org>
 * @version 1.0
 * @copyright ImpressCMS Project <http://www.impresscms.org>
 * @license GPL v2.0
 */
include_once("../include/admin/header.php");

$immenu_menu_handler = xoops_getModuleHandler('menu');

$op = '';

if (isset($_GET['op'])) $op = $_GET['op'];
if (isset($_POST['op'])) $op = $_POST['op'];

$menu_id = isset($_GET['menu_id']) ? intval($_GET['menu_id']) : 0 ;

switch($op){
	case "mod":
	case "changedField":
		// Checking if there is defined templates;
		$immenu_template_handler = xoops_getModuleHandler('template');
		if( count( $immenu_template_handler->getObjects() ) == 0 ){
			redirect_header('template.php',3,_IM_IMMENU_NO_TEMPLATE_CREATED);
		}
		
		xoops_cp_header();
		$menuObj = $immenu_menu_handler->get($menu_id);
		if (!$menuObj->isNew()){
			$xoopsModule->displayAdminMenu(0, _AM_IMMENU_MENUS . " > " . _CO_ICMS_EDITING);
			$sform = $menuObj->getForm(_AM_IMMENU_MENU_EDIT, 'addmenu');
			$sform->assign($icmsAdminTpl);
		} else {
			$xoopsModule->displayAdminMenu(0, _AM_IMMENU_MENUS . " > " . _CO_ICMS_CREATINGNEW);
			$sform = $menuObj->getForm(_AM_IMMENU_MENU_CREATE, 'addmenu');
			$sform->assign($icmsAdminTpl);

		}
		
		$icmsAdminTpl->display('db:immenu_admin_menu.html');
		
		break;
	case "addmenu":
        include_once ICMS_ROOT_PATH."/kernel/icmspersistablecontroller.php";
        $controller = new IcmsPersistableController($immenu_menu_handler);
		$controller->storeFromDefaultForm(_AM_IMMENU_MENU_CREATED, _AM_IMMENU_MENU_MODIFIED);

		break;

	case "del":
	    include_once ICMS_ROOT_PATH."/kernel/icmspersistablecontroller.php";
        $controller = new IcmsPersistableController($immenu_menu_handler);
		$controller->handleObjectDeletion();

		break;
	default:
		xoops_cp_header();
		include_once ICMS_ROOT_PATH."/kernel/icmspersistabletable.php";
		
		$xoopsModule->displayAdminMenu(0, _AM_IMMENU_MENUS);
		
		$objectTable = new IcmsPersistableTable($immenu_menu_handler);
		$objectTable->addColumn(new IcmsPersistableColumn('menu_title', 'left'));
		$objectTable->addColumn(new IcmsPersistableColumn('menu_desc', 'center'));
		$objectTable->addColumn(new IcmsPersistableColumn('menu_template', 'center'));
		$objectTable->addColumn(new IcmsPersistableColumn('menu_modification_date', 'center'));
		//$objectTable->addColumn(new IcmsPersistableColumn('menu_id', 'center'));
		$op = isset($_GET['op']) ? $_GET['op'] : "";
		$objectTable->addIntroButton('addmenu', 'menu.php?op=mod', _AM_IMMENU_MENU_CREATE);
		
		$objectTable->addQuickSearch(array('menu_title',"menu_desc"));
		
		$objectTable->addCustomAction('getMenuItemListButton');
		
		$icmsAdminTpl->assign('immenu_menu_table', $objectTable->fetch());

		$icmsAdminTpl->display('db:immenu_admin_menu.html');
		
}
xoops_cp_footer();
?>
