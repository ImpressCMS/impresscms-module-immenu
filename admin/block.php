<?php
include_once("../include/admin/header.php");

xoops_cp_header();
$xoopsModule->displayAdminMenu(2, _AM_IMMENU_MENU_BLOCKS);

echo "This feature to admin the menu blocks is not yet finished, it will be ready when the core block class is under the IPF.";
echo "<br />For now, you will have to admin the menu blocks by the <a href='".ICMS_URL."/modules/system/admin.php?fct=blocksadmin'>block manager</a>";
xoops_cp_footer();
?>
