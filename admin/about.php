<?php

include_once("../include/admin/header.php");

include_once(ICMS_ROOT_PATH . "/kernel/icmsmoduleabout.php");
$aboutObj = new IcmsModuleAbout();
$aboutObj->render();

?>