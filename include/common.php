<?php
/**
* Common file of the module included on all pages of the module
*
* @copyright	The ImpressCMS Project <http://www.impresscms.org/>
* @license		http://www.gnu.org/licenses/old-licenses/gpl-2.0.html GNU General Public License (GPL)
* @since		1.0
* @author		Gustavo Pilla <nekro@impresscms.org>
* @version		$Id$
*/

if (!defined("ICMS_ROOT_PATH")) die("ICMS root path not defined");

if(!defined("IMMENU_DIRNAME"))		define("IMMENU_DIRNAME", $modversion['dirname'] = basename(dirname(dirname(__FILE__))));
if(!defined("IMMENU_URL"))			define("IMMENU_URL", ICMS_URL.'/modules/'.IMMENU_DIRNAME.'/');
if(!defined("IMMENU_ROOT_PATH"))	define("IMMENU_ROOT_PATH", ICMS_ROOT_PATH.'/modules/'.IMMENU_DIRNAME.'/');
if(!defined("IMMENU_IMAGES_URL"))	define("IMMENU_IMAGES_URL", IMMENU_URL.'images/');
if(!defined("IMMENU_ADMIN_URL"))	define("IMMENU_ADMIN_URL", IMMENU_URL.'admin/');

// Include the common language file of the module
icms_loadLanguageFile('immenu', 'common');

include_once(IMMENU_ROOT_PATH . "include/functions.php");

// Creating the module object to make it available throughout the module
$imMenuModule = icms_getModuleInfo(IMMENU_DIRNAME);
if (is_object($imMenuModule)){
	$immenu_moduleName = $imMenuModule->getVar('name');
}

// Find if the user is admin of the module and make this info available throughout the module
$imMenu_isAdmin = icms_userIsAdmin(IMMENU_DIRNAME);

// Creating the module config array to make it available throughout the module
$imMenuConfig = icms_getModuleConfig(IMMENU_DIRNAME);

// including the post class
include_once(IMMENU_ROOT_PATH . 'class/menu.php');
include_once(IMMENU_ROOT_PATH . 'class/menuitem.php');
include_once(IMMENU_ROOT_PATH . 'class/template.php');

// creating the icmsPersistableRegistry to make it available throughout the module
global $icmsPersistableRegistry;
$icmsPersistableRegistry = IcmsPersistableRegistry::getInstance();

?>
