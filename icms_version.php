<?php
/**
* imMenu version infomation
*
* This file holds the configuration information of this module
*
* @copyright	ImpressCMS Project 2008
* @license		http://www.gnu.org/licenses/old-licenses/gpl-2.0.html GNU General Public License (GPL)
* @since		1.0
* @author		Gustavo Pilla <nekro@impresscms.org>
* @version		$Id$
*/

if (!defined("ICMS_ROOT_PATH")) die("ICMS root path not defined");

/**
 * General Information
 */
$modversion['name'] = _MI_IMMENU_MD_NAME;
$modversion['version'] = 1.0;
$modversion['description'] = _MI_IMMENU_MD_DESC;
$modversion['author'] = "Gustavo Pilla <nekro@impresscms.org>";
$modversion['credits'] = "ImpressCMS Project <http://www.impresscms.org>";
$modversion['help'] = "";
$modversion['license'] = "GNU General Public License (GPL)";
$modversion['official'] = 1; // This module is official
$modversion['dirname'] = basename( dirname( __FILE__ ) ) ;

// Definition of the module.
// This will be applicated in 1.2... when the core get modularized.
/*
$modversion['category']['id'] = _ICMS_MODULE_CATEGORY_CORE_EXTENSION;
$modversion['category']['name'] = _ICMS_MODULE_CATEGORY_CORE_EXTENSION_TITLE;
$modversion['type']['id'] = _ICMS_MODULE_TYPE_MENU;
$modversion['type']['name'] = _ICMS_MODULE_TYPE_MENU_TITLE;
*/

/**
 * Images information
 */
$modversion['iconsmall'] = "images/icon_small.png";
$modversion['iconbig'] = "images/icon_big.png";
// for backward compatibility
$modversion['image'] = $modversion['iconbig'];

/**
 * Development information
 */
$modversion['status_version'] = "Alpha 1";
$modversion['status'] = "Alpha";
$modversion['date'] = "?";
$modversion['author_word'] = "This current version, is a test of concept, and a prove of the power of the IPF (ImpressCMS Persistable Framework)";

/**
 * Contributors
 */
$modversion['developer_website_url'] = "http://www.nubee.com.ar";
$modversion['developer_website_name'] = "Nubee Software Developments";
$modversion['developer_email'] = "info@nubee.com.ar";
$modversion['people']['developers'][] = "Gustavo Pilla nekro@impresscms.org";

//$modversion['people']['testers'][] = "";
//$modversion['people']['translators'][] = "";
//$modversion['people']['documenters'][] = "";
$modversion['people']['other'][] = "sato-san";
//$modversion['warning'] = _CO_SOBJECT_WARNING_ALPHA;

/**
 * Administrative information
 */
$modversion['hasAdmin'] = 1;
$modversion['adminindex'] = "admin/index.php";
$modversion['adminmenu'] = "include/icms_menu.php";// Renamed... it could be a good future option not?

/**
 * Database information
 */
$modversion['object_items'][1] = 'menu';
$modversion['object_items'][2] = 'menuitem';
$modversion['object_items'][3] = 'template';
$modversion["tables"] = icms_getTablesArray($modversion['dirname'], $modversion['object_items']);

/**
 * Install and update informations
 */
$modversion['onInstall'] = "include/icms_onupdate.php"; // Also renamed... i think that is a better way.
$modversion['onUpdate'] = "include/icms_onupdate.php";


/**
 * Search information
 */
// TODO: If is posible to search in a symlink. Also should be posible in a menu.
//$modversion['hasSearch'] = 1;
//$modversion['search']['file'] = "include/icms_search.php";
//$modversion['search']['func'] = "immenu_search";

/**
 * Menu information
 */
// This module never will have user side... i think...
$modversion['hasMain'] = 0;

/**
 * Templates information
 */

$i = 0;

$i++;
$modversion['templates'][$i]['file'] = 'immenu_admin_menu.html';
$modversion['templates'][$i]['description'] = 'Menu Index';

$i++;
$modversion['templates'][$i]['file'] = 'immenu_admin_menuitem.html';
$modversion['templates'][$i]['description'] = 'Menu Item Index';

$i++;
$modversion['templates'][$i]['file'] = 'immenu_admin_template.html';
$modversion['templates'][$i]['description'] = 'Menu Item Index';

?>
