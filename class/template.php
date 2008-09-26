<?php

define( '_IMMENU_TEMPLATE_SOURCE_DEFAULT',
		'<ul>
			<{foreach item=immemu_menuitem from=$block.items}>
				<li><a href="<{$immemu_menuitem.url}>" title="<{$immemu_menuitem.title}>"><{$immemu_menuitem.title}></a></li>
			<{/foreach}>
		</ul>');

class ImmenuTemplate extends IcmsPersistableObject{
	
	public function __construct(){
		$this->IcmsPersistableObject($handler);

        $this->quickInitVar('template_id', XOBJ_DTYPE_INT, true,"ID");
        $this->quickInitVar('template_title', XOBJ_DTYPE_TXTBOX,false ,"Title");
        $this->setFieldAsRequired('template_title');
        $this->quickInitVar('template_desc', XOBJ_DTYPE_TXTAREA,false,"Description");
        $this->quickInitVar('template_tpl_id', XOBJ_DTYPE_INT,false);
        $this->hideFieldFromForm('template_tpl_id');
        $this->quickInitVar('template_source', XOBJ_DTYPE_TXTAREA,false,"Source",false,_IMMENU_TEMPLATE_SOURCE_DEFAULT);
        $this->setFieldAsRequired('template_source');
        $this->quickInitVar('template_status', XOBJ_DTYPE_INT, false, "Status", false, true);
        $this->quickInitVar('template_creation_date', XOBJ_DTYPE_LTIME);
        $this->hideFieldFromForm('template_creation_date');
        $this->quickInitVar('template_modification_date', XOBJ_DTYPE_LTIME);
        $this->hideFieldFromForm('template_modification_date');
        
        //$this->setControl('template_source', 'dhtmltextarea');
        
        $this->initCommonVar('dohtml', false, true);
		$this->initCommonVar('dobr', false);
		$this->initCommonVar('doimage', false, true);
		$this->initCommonVar('dosmiley', false, true);
		$this->initCommonVar('doxcode', false, true);
        
        $this->setControl('template_status', 'yesno');
        
	}
	
	function getVar($key, $format = 's') {
        if ($format == 's' && in_array($key, array('template_status'))) {
            return call_user_func(array($this,$key));
        }
        return parent::getVar($key, $format);
    }
    
    private function template_status(){
    	switch($this->getVar('template_status', 'e') ){
    		case 1:
    			$ret = "<img src='".ICMS_URL."/images/crystal/actions/button_ok.png' alt='1'/>";
    			break;
    		case 0:
    			$ret = "<img src='".ICMS_URL."/images/crystal/actions/button_cancel.png' alt='0'/>";
    			break;
    	}
    	return $ret;
    }
	
	public function getCloneTemplateButton(){
		$ret = "<a href='".IMMENU_ADMIN_URL."template.php?op=clone&template_id=".$this->getVar('template_id')."' title='"._CO_IMMENU_MENUITEM_ADMIN_ITEMS."'>";
		$ret .= "<img src='".ICMS_URL."/images/crystal/actions/editcopy.png' alt='"._CO_IMMENU_MENUITEM_ADMIN_ITEMS."'/>";
		$ret .= "</a>";
		return $ret;
	}
}

class ImmenuTemplateHandler extends IcmsPersistableObjectHandler {	
	
	/**
	 * Class constructor
	 */
	public function __construct($db){
		$this->IcmsPersistableObjectHandler($db, 'template', 'template_id', 'template_title', 'template_desc', 'immenu');
	}
	
	public function beforeSave(&$obj){
		global $xoopsModule;
		
		$tplfile_handler =& xoops_gethandler('tplfile');
		$tplfile = $tplfile_handler->get($obj->getVar('template_tpl_id'));
		if ( is_object($tplfile) && !$tplfile->isNew() ){
			$tplfile->setVar('tpl_source', $obj->getVar('template_source'), true);
			$tplfile->setVar('tpl_tplset', 'default');
			$tplfile->setVar('tpl_file', "immenu_menu_block_".md5($obj->getVar('template_title')).".html");
			$tplfile->setVar('tpl_desc', $obj->getVar('template_desc'), true);
			$tplfile->setVar('tpl_lastmodified', time());
			$tplfile->setVar('tpl_lastimported', 0);
			if ($tplfile_handler->insert($tplfile)) {
				$obj->setVar('template_tpl_id', $tplfile->getVar('tpl_id'));
				return true;
			}
		}else{
			//$obj->getVar('template_source','e'); // Why it is here?
			
			$tplfile =& $tplfile_handler->create();
			$tplfile->setVar('tpl_source', $obj->getVar('template_source','e'),true);
			$tplfile->setVar('tpl_refid', $xoopsModule->getVar('mid'));
			$tplfile->setVar('tpl_tplset', 'default');
			
			$tplfile->setVar('tpl_file', "immenu_menu_block_".md5($obj->getVar('template_title')).".html");
			$tplfile->setVar('tpl_desc', $obj->getVar('template_desc'), true);
			$tplfile->setVar('tpl_module', $xoopsModule->getVar('dirname'));
			$tplfile->setVar('tpl_lastmodified', time());
			$tplfile->setVar('tpl_lastimported', 0);
			$tplfile->setVar('tpl_type', 'block');
			if ($tplfile_handler->insert($tplfile)) {
				$obj->setVar('template_tpl_id', $tplfile->getVar('tpl_id'));
				return true;
			}
			
			
//			$sql = sprintf("INSERT INTO %s (tpl_id, tpl_module, tpl_refid, tpl_tplset, tpl_file, tpl_desc, tpl_lastmodified, tpl_lastimported, tpl_type) VALUES ('%u', %s, '%u', %s, %s, %s, '%u', '%u', %s)", $this->db->prefix('tplfile'), intval($tpl_id), $this->db->quoteString('immenu'), intval($tpl_refid), $this->db->quoteString($tpl_tplset), $this->db->quoteString($tpl_file), $this->db->quoteString($tpl_desc), intval($tpl_lastmodified), intval($tpl_lastimported), $this->db->quoteString($tpl_type));
//            if (!$result = $this->db->query($sql)) {
//                return false;
//            }
//            if (empty($tpl_id)) {
//                $tpl_id = $this->db->getInsertId();
//            }
//            if (isset($tpl_source) && $tpl_source != '') {
//                $sql = sprintf("INSERT INTO %s (tpl_id, tpl_source) VALUES ('%u', %s)", $this->db->prefix('tplsource'), intval($tpl_id), $this->db->quoteString($tpl_source));
//                if (!$result = $this->db->query($sql)) {
//                    $this->db->query(sprintf("DELETE FROM %s WHERE tpl_id = '%u'", $this->db->prefix('tplfile'), intval($tpl_id)));
//                    return false;
//                }
//            }
		}
		return false;      
	}
	
}

?>
