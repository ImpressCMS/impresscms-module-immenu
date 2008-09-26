<?php
include_once("../include/admin/header.php");

$immenu_template_handler = xoops_getModuleHandler('template');

$op = '';

if (isset($_GET['op'])) $op = $_GET['op'];
if (isset($_POST['op'])) $op = $_POST['op'];

$menu_id = isset($_GET['menu_id']) ? intval($_GET['menu_id']) : 0 ;
$template_id = isset($_GET['template_id']) ? intval($_GET['template_id']) : 0 ;

//echo "opcion:".$op;

switch($op){
	case "clone":
		xoops_cp_header();
		$template_id = isset($_GET['template_id']) ? intval($_GET['template_id']) : 0 ;
		//echo $template_id;
		$template = $immenu_template_handler->get($template_id);
		$xoopsModule->displayAdminMenu(1, _AM_IMMENU_TEMPLATES . " > " . _CO_ICMS_CLONE);
		$sform = $template->getForm(_AM_IMMENU_TEMPLATE_EDIT, 'addtemplate');
		$sform->assign($icmsAdminTpl);
		
		break;
	case "mod":
	case "changedField":
		xoops_cp_header();
		$postObj = $immenu_template_handler->get($template_id);
		if (!$postObj->isNew()){
			$xoopsModule->displayAdminMenu(1, _AM_IMMENU_TEMPLATES . " > " . _CO_ICMS_EDITING);
			$sform = $postObj->getForm(_AM_IMMENU_TEMPLATE_EDIT, 'addtemplate');
			$sform->assign($icmsAdminTpl);
		}else{
			$xoopsModule->displayAdminMenu(1, _AM_IMMENU_TEMPLATES . " > " . _CO_ICMS_CREATINGNEW);
			$sform = $postObj->getForm(_AM_IMMENU_TEMPLATE_CREATE, 'addtemplate');
			$sform->assign($icmsAdminTpl);
		}
		$icmsAdminTpl->display('db:immenu_admin_template.html');
		break;
	case "addtemplate":
        include_once ICMS_ROOT_PATH."/kernel/icmspersistablecontroller.php";
        $controller = new IcmsPersistableController($immenu_template_handler);
		$controller->storeFromDefaultForm(_AM_IMMENU_TEMPLATE_CREATED, _AM_IMMENU_TEMPLATE_MODIFIED);
		break;
	case "del":
	    include_once ICMS_ROOT_PATH."/kernel/icmspersistablecontroller.php";
        $controller = new IcmsPersistableController($immenu_template_handler);
		$controller->handleObjectDeletion();
		break;
	default:
		include_once ICMS_ROOT_PATH."/kernel/icmspersistabletable.php";
	
		xoops_cp_header();	
		$xoopsModule->displayAdminMenu(1, _AM_IMMENU_TEMPLATES);
		

		
		// Defining the table object and fields to be shown.
		$objectTable = new IcmsPersistableTable($immenu_template_handler);
		$objectTable->addColumn(new IcmsPersistableColumn('template_status', 'center'));
		$objectTable->addColumn(new IcmsPersistableColumn('template_title', 'left'));
		$objectTable->addColumn(new IcmsPersistableColumn('template_desc', 'left'));		
		
		// Adding extra controls to the table object.
		$objectTable->addIntroButton('addtemplate', 'template.php?op=mod&menu_id='.$menu_id, _AM_IMMENU_TEMPLATE_CREATE);	
		$objectTable->addQuickSearch(array('template_title','template_desc'));
		$objectTable->addCustomAction('getCloneTemplateButton');
		
		// Doing template stuff.
		$icmsAdminTpl->assign('immenu_template_table', $objectTable->fetch());
		$icmsAdminTpl->display('db:immenu_admin_template.html');
		
}
xoops_cp_footer();
?>
