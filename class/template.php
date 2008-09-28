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

        $this->quickInitVar('template_id', XOBJ_DTYPE_INT, true, _AM_IMMENU_TEMPLATE_ID, _AM_IMMENU_TEMPLATE_ID_DSC);
        $this->quickInitVar('template_title', XOBJ_DTYPE_TXTBOX, false, _AM_IMMENU_TEMPLATE_TITLE, _AM_IMMENU_TEMPLATE_TITLE_DSC);
        $this->setFieldAsRequired('template_title');
        $this->quickInitVar('template_desc', XOBJ_DTYPE_TXTAREA, false, _AM_IMMENU_TEMPLATE_DESC, _AM_IMMENU_TEMPLATE_DESC_DSC);
        $this->quickInitVar('template_tpl_id', XOBJ_DTYPE_INT, false, _AM_IMMENU_TEMPLATE_TPL_ID, _AM_IMMENU_TEMPLATE_TPL_ID_DSC);
        $this->hideFieldFromForm('template_tpl_id');
        $this->quickInitVar('template_source', XOBJ_DTYPE_TXTAREA,false, _AM_IMMENU_TEMPLATE_SOURCE, _AM_IMMENU_TEMPLATE_SOURCE_DSC, _IMMENU_TEMPLATE_SOURCE_DEFAULT);
        $this->setFieldAsRequired('template_source');
        $this->quickInitVar('template_status', XOBJ_DTYPE_INT, false, _AM_IMMENU_TEMPLATE_STATUS, _AM_IMMENU_TEMPLATE_STATUS_DSC, true);
        $this->quickInitVar('template_creation_date', XOBJ_DTYPE_LTIME, _AM_IMMENU_TEMPLATE_CREATION_DATE, _AM_IMMENU_TEMPLATE_CREATION_DATE_DSC);
        $this->hideFieldFromForm('template_creation_date');
        $this->quickInitVar('template_modification_date', XOBJ_DTYPE_LTIME, _AM_IMMENU_TEMPLATE_MODIFICATION_DATE, _AM_IMMENU_TEMPLATE_MODIFICATION_DATE_DSC);
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
        if ($format == 's' && in_array($key, array('template_status', 'template_source'))) {
            return call_user_func(array($this,$key));
        }
        return parent::getVar($key, $format);
        
    }
    
    private function template_source(){
    	$ret = $this->vars['template_source']['value'];
    	return $ret;
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
		$ret = "<a href='".IMMENU_ADMIN_URL."template.php?op=clone&template_id=".$this->getVar('template_id')."' title='"._AM_IMMENU_TEMPLATE_CLONE."'>";
		$ret .= "<img src='".ICMS_URL."/images/crystal/actions/editcopy.png' alt='"._AM_IMMENU_TEMPLATE_CLONE."'/>";
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
		//echo $obj->getVar('template_source');
		return true;
				
	}
	
	public function afterSave(&$obj){
		global $xoopsModule;
		
		$tplfile_handler =& xoops_gethandler('tplfile');
		$criteria = new CriteriaCompo();
		$criteria->add(new Criteria('tpl_file', "immenu_menu_block_tpl".$obj->getVar('template_id').".html"));
		$tplarray = $tplfile_handler->getObjects($criteria);
		$tplfile = $tplarray[0];
		if ( is_object($tplfile) && !$tplfile->isNew() ){
			$tplfile->setVar('tpl_source', $obj->getVar('template_source'), true);
			$tplfile->setVar('tpl_tplset', 'default');
			$tplfile->setVar('tpl_file', "immenu_menu_block_tpl".$obj->getVar('template_id').".html");
			$tplfile->setVar('tpl_desc', $obj->getVar('template_desc'), true);
			$tplfile->setVar('tpl_lastmodified', time());
			$tplfile->setVar('tpl_lastimported', 0);
			if (!$tplfile_handler->insert($tplfile)) {
				return false;
			}
		}else{
			$tplfile =& $tplfile_handler->create();
			$tplfile->setVar('tpl_source', $obj->getVar('template_source'),true);
			$tplfile->setVar('tpl_refid', $xoopsModule->getVar('mid'));
			$tplfile->setVar('tpl_tplset', 'default');
			
			$tplfile->setVar('tpl_file', "immenu_menu_block_tpl".$obj->getVar('template_id').".html");
			$tplfile->setVar('tpl_desc', $obj->getVar('template_desc'), true);
			$tplfile->setVar('tpl_module', $xoopsModule->getVar('dirname'));
			$tplfile->setVar('tpl_last$tpl_typemodified', time());
			$tplfile->setVar('tpl_lastimported', 0);
			$tplfile->setVar('tpl_type', 'block');
			if ($tplfile_handler->insert($tplfile)) {
				//$obj->setVar('template_tpl_id', $tplfile->getVar('tpl_id'));
				//$this->insert($obj);
				return true;
			}else{
				$this->delete($obj);
				return false;
			}
		}
	}
	
	public function beforeDelete(&$obj){
		$tplfile_handler =& xoops_gethandler('tplfile');
		$criteria = new CriteriaCompo();
		$criteria->add(new Criteria('tpl_file', "immenu_menu_block_tpl".$obj->getVar('template_id').".html"));
		$tplarray = $tplfile_handler->getObjects($criteria);
		$tplfile = $tplarray[0];
		if($tplfile_handler->delete($tplfile)){
			return true;
		}
		return false;
	}
}
?>