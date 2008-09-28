<?php
include_once("../include/admin/header.php");

$immenu_menu_handler = xoops_getModuleHandler('menuitem');

$op = '';
if (isset($_GET['op'])) $op = $_GET['op'];
if (isset($_POST['op'])) $op = $_POST['op'];

$menu_id = isset($_GET['menu_id']) ? intval($_GET['menu_id']) : 0 ;
$menuitem_id = isset($_GET['menuitem_id']) ? intval($_GET['menuitem_id']) : 0 ;

//echo "opcion:".$op;

switch($op){
	case "wd":
		$menuitem = $immenu_menu_handler->get($menuitem_id);
		$weight = $menuitem->getVar("menuitem_weight") + 1;
		$menuitem->setVar("menuitem_weight", $weight);
		$immenu_menu_handler->insert($menuitem, true);
		header("Location: ".ICMS_URL."/modules/immenu/admin/menuitem.php?menu_id=".$menu_id);
		exit();
		break;
	case "wu":
		$menuitem = $immenu_menu_handler->get($menuitem_id);
		$weight = $menuitem->getVar("menuitem_weight") - 1;
		$menuitem->setVar("menuitem_weight", $weight);
		$immenu_menu_handler->insert($menuitem, true);
		header("Location: ".ICMS_URL."/modules/immenu/admin/menuitem.php?menu_id=".$menu_id);
		exit();
		break;
	case "mod":
	case "changedField":
		xoops_cp_header();
		$postObj = $immenu_menu_handler->get($menuitem_id);
		if (!$postObj->isNew()){
			$xoopsModule->displayAdminMenu(0, _AM_IMMENU_MENUS." > "._AM_IMMENU_MENUITEMS . " > " . _CO_ICMS_EDITING);
			$sform = $postObj->getForm(_AM_IMMENU_MENUITEM_EDIT, 'addmenuitem');
			$sform->assign($icmsAdminTpl);
		}else{
			$xoopsModule->displayAdminMenu(0, _AM_IMMENU_MENUS." > "._AM_IMMENU_MENUITEMS . " > " . _CO_ICMS_CREATINGNEW);
			$sform = $postObj->getForm(_AM_IMMENU_MENUITEM_CREATE, 'addmenuitem');
			$sform->assign($icmsAdminTpl);
		}
		$icmsAdminTpl->display('db:immenu_admin_menuitem.html');
		break;
	case "addmenuitem":
        include_once ICMS_ROOT_PATH."/kernel/icmspersistablecontroller.php";
        $controller = new IcmsPersistableController($immenu_menu_handler);
		$controller->storeFromDefaultForm(_AM_IMMENU_MENUITEM_CREATED, _AM_IMMENU_MENUITEM_MODIFIED);
		break;
	case "del":
	    include_once ICMS_ROOT_PATH."/kernel/icmspersistablecontroller.php";
        $controller = new IcmsPersistableController($immenu_menu_handler);
		$controller->handleObjectDeletion();
		break;
	default:
		include_once ICMS_ROOT_PATH."/kernel/icmspersistabletable.php";
	
		xoops_cp_header();	
		$xoopsModule->displayAdminMenu(0, _AM_IMMENU_MENUS." > "._AM_IMMENU_MENUITEMS);
		
		// Defining the "criteria" to load in the table.
		$criteria = new CriteriaCompo();
		$criteria->add(new Criteria('menuitem_menu_id', $menu_id));
		
		// Defining the table object and fields to be shown.
		$objectTable = new IcmsPersistableTable($immenu_menu_handler, $criteria);
		$objectTable->addColumn(new IcmsPersistableColumn('menuitem_status', 'center'));
		$objectTable->addColumn(new IcmsPersistableColumn('menuitem_id', 'left'));
		$objectTable->addColumn(new IcmsPersistableColumn('menuitem_title', 'left'));
		//$objectTable->addColumn(new IcmsPersistableColumn('menuitem_parent_id', 'center'));
		$objectTable->addColumn(new IcmsPersistableColumn('menuitem_desc', 'left'));
		$objectTable->addColumn(new IcmsPersistableColumn('menuitem_url', 'left'));
		$objectTable->addColumn(new IcmsPersistableColumn('menuitem_weight', 'center'));
		$objectTable->addColumn(new IcmsPersistableColumn('menuitem_hits', 'center'));
		
		
		// Adding extra controls to the table object.
		$objectTable->addIntroButton('addmenuitem', 'menuitem.php?op=mod&menu_id='.$menu_id, _AM_IMMENU_MENUITEM_CREATE);	
		$objectTable->addQuickSearch(array('menuitem_title','menuitem_desc','menuitem_url'));
		$objectTable->addCustomAction('getWeightUpButton');
		$objectTable->addCustomAction('getWeightDownButton');
		
		// Doing template stuff.
		$icmsAdminTpl->assign('immenu_menuitem_table', $objectTable->fetch());
		$icmsAdminTpl->display('db:immenu_admin_menuitem.html');
		
}
xoops_cp_footer();
?>
