<?php

class ImmenuMenu extends IcmsPersistableObject{

	public function __construct(&$handler){
		global $xoopsConfig;

    	$this->IcmsPersistableObject($handler);

        $this->quickInitVar('menu_id', XOBJ_DTYPE_INT, true,_AM_IMMENU_MENU_ID);
        $this->quickInitVar('menu_title', XOBJ_DTYPE_TXTBOX,false ,_AM_IMMENU_MENU_TITLE);
        $this->setFieldAsRequired('menu_title');
        $this->quickInitVar('menu_desc', XOBJ_DTYPE_TXTAREA,false,_AM_IMMENU_MENU_DESC);
        $this->quickInitVar('menu_template', XOBJ_DTYPE_TXTBOX,false,_AM_IMMENU_MENU_TEMPLATE);
        $this->setFieldAsRequired('menu_template');
        $this->quickInitVar('menu_creation_date', XOBJ_DTYPE_LTIME,false,_AM_IMMENU_MENU_CREATION_DATE);
        $this->quickInitVar('menu_modification_date', XOBJ_DTYPE_LTIME,false,_AM_IMMENU_MODIFICATION_DATE);
        $this->quickInitVar('menu_bid', XOBJ_DTYPE_INT,false,_AM_IMMENU_MENU_BID);
        
        $this->hideFieldFromForm('menu_bid');
        $this->hideFieldFromForm('menu_creation_date');
        $this->hideFieldFromForm('menu_modification_date');
		
		$this->setControl('menu_template', array( 'itemHandler' => 'menu',
											      'method' => 'getMenuTemplateArray',
											      'module' => 'immenu'
											    ));
		
	}
	
	function getVar($key, $format = 's') {
        if ($format == 's' && in_array($key, array('menu_template'))) {
            return call_user_func(array($this,$key));
        }
        return parent::getVar($key, $format);
    }
    
    private function menu_template(){
    	//$ret = "<a href='template.php?op=mod&template_id=".$this->getVar('menu_template','e')."'>".$this->getVar('menu_template', 'e')."</a>"; 
    	$ret = $this->getVar('menu_template', 'e');
    	return $ret;
    }	
	
	public function getMenuItemListButton(){
		$ret = "<a href='".IMMENU_ADMIN_URL."menuitem.php?menu_id=".$this->getVar('menu_id')."' title='"._CO_IMMENU_MENUITEM_ADMIN_ITEMS."'>";
		$ret .= "<img src='".ICMS_URL."/images/crystal/actions/filenew2.png' alt='"._CO_IMMENU_MENUITEM_ADMIN_ITEMS."'/>";
		$ret .= "</a>";
		return $ret;
	}

}

class ImmenuMenuHandler extends IcmsPersistableObjectHandler {	
	
	public function __construct($db){
		$this->IcmsPersistableObjectHandler($db, 'menu', 'menu_id', 'menu_title', 'menu_title', 'immenu');
	}

	public function getMenuTemplateArray(){
		$immenu_menu_handler = xoops_getModuleHandler('template');
		foreach($immenu_menu_handler->getObjects() as $template){
			$ret[$template->getVar('template_title')] = $template->getVar('template_title');
		}
		return $ret;
	}
	
	public function beforeSave(&$obj){
		
		global $xoopsModule;
		
		// Create the core block.
		$block_handler =& xoops_gethandler('block');
		$block = $block_handler->get($obj->getVar('menu_bid'));
		if ( is_object($block) && !$block->isNew() ){
	
			// setting the modification time.
			$obj->setVar("menu_modification_date",mktime());
	
			$block->setVar("name", $obj->getVar('menu_title'));
			$block->setVar("title", $obj->getVar('menu_title'));
		    $block->setVar('template', 'immenu_menu_block_'.md5($obj->getVar('menu_template')).".html");
		    $block->setVar('last_modified', mktime());
		    
		    if($block_handler->insert($block)){
			    return true;
			}
		}else{
			$block = $block_handler->create();
			// setting the creation and modification time.
			$obj->setVar("menu_creation_date",mktime());
			$obj->setVar("menu_modification_date",mktime());
			
			$block->setVar("mid", $xoopsModule->getVar('mid'));
			$block->setVar("func_num", $obj->getVar('menu_id'));
			$block->setVar("options", $obj->getVar('menu_id')); //
			$block->setVar("name", $obj->getVar('menu_title'));
			$block->setVar("title", $obj->getVar('menu_title'));
			$block->setVar('side', 1);
		    $block->setVar('weight', 0);
		    $block->setVar('visible', 0);
		    $block->setVar('block_type', 'M');
		    $block->setVar('c_type', 'H');
		    $block->setVar('isactive', 1);
		    $block->setVar('dirname', 'immenu');
		    $block->setVar('func_file', 'menu.php');
		    $block->setVar('show_func', 'immenu_menu_show');
		    $block->setVar('edit_func', '');
		    $block->setVar('template', 'immenu_menu_block_'.md5($obj->getVar('menu_template')).".html");
		    $block->setVar('bcachetime', 0);
		    $block->setVar('last_modified', mktime());
		    
		    // This is not the best way to do it.. i know.. but is how is done in the core.
		    // TODO: Do the next in any object way.
		    if($block_handler->insert($block)){
		    	$obj->setVar('menu_bid',$block->getVar('bid'));
		    	$db =& Database::getInstance();	
				$sql = 'INSERT INTO '.$db->prefix('block_module_link').' (block_id, module_id,page_id) VALUES ('.$block->getVar('bid').', 0,1)';
				$db->query($sql);
				$groups = array(XOOPS_GROUP_ADMIN, XOOPS_GROUP_USERS, XOOPS_GROUP_ANONYMOUS);
				$count = count($groups);
				for ($i = 0; $i < $count; $i++) {
					$sql = "INSERT INTO ".$db->prefix('group_permission')." (gperm_groupid, gperm_itemid, gperm_name, gperm_modid) VALUES ('".$groups[$i]."', '".$block->getVar('bid')."', 'block_read', '1')";
		    	    $db->query($sql);
		    	}
		    	return true;
		    }
		    
        }
		return false;
	}
	
	/**
	 * Trigger afterSave
	 *
	 * @param object $obj
	 * @return bool
	 */
	public function afterSave(&$obj){
		$block_handler =& xoops_gethandler('block');
		$block = $block_handler->get($obj->getVar('menu_bid'));
		$block->setVar("func_num", $obj->getVar('menu_id'));
		$block->setVar("options", $obj->getVar('menu_id'));
	    $block->setVar('last_modified', mktime());
	    
	    if($block_handler->insert($block))
	    	return true;
	    return false;
	}
	
	/**
	 * Get the items to be shown in a block
	 *
	 * @param options $options
	 * @return array
	 */
	public function getItemsForBlock($options){
		$menu_id = $options[0];
		$immenu_menuitem_handler = xoops_getModuleHandler('menuitem',"immenu");
		$criteria = new CriteriaCompo();
		$criteria->add(new Criteria('menuitem_menu_id', $menu_id));
		$criteria->add(new Criteria('menuitem_status', 1));
		$criteria->setSort("menuitem_weight");
		$criteria->setSort("menuitem_id");
		$menus = $immenu_menuitem_handler->getObjects($criteria);
		$ret = array();
		foreach( $menus as $menu){
			$ret[] = array("title"=>$menu->getVar('menuitem_title'),"url"=>$menu->getVar('menuitem_url'));
		}
		return $ret;
	}
	
}

?>
