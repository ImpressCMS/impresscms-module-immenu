<?php
/**
 * ImMenu Menu Item Object Class File
 *
 * @author Gustavo Pilla <nekro@impresscms.org>
 * @license	http://www.gnu.org/licenses/old-licenses/gpl-2.0.html GNU General Public License (GPL)
 * @copyright ImpressCMS Project <http://www.impresscms.org>
 * @since imMenu 1.0
 * @version $Id:$
 */

/**
 * ImMenu Menu Item Object Class
 *
 * @author Gustavo Pilla <nekro@impresscms.org>
 * @license	http://www.gnu.org/licenses/old-licenses/gpl-2.0.html GNU General Public License (GPL)
 * @copyright ImpressCMS Project <http://www.impresscms.org>
 * @since imMenu 1.0
 * @version $Id:$
 */
class ImmenuMenuitem extends IcmsPersistableObject{
	
	/**
	 * Class constructor
	 */ 
	public function __construct(&$handler){
		global $xoopsConfig;

    	$this->IcmsPersistableObject($handler);

        $this->quickInitVar('menuitem_id', XOBJ_DTYPE_INT, true,"id");
        $this->quickInitVar('menuitem_menu_id', XOBJ_DTYPE_INT, true, "menu_id");
        $this->hideFieldFromForm('menuitem_menu_id');
        //$this->quickInitVar('menuitem_parent_id', XOBJ_DTYPE_INT,false, "parent id");
        $this->quickInitVar('menuitem_title', XOBJ_DTYPE_TXTBOX,false,"title");
        $this->setFieldAsRequired('menuitem_title');
        $this->quickInitVar('menuitem_desc', XOBJ_DTYPE_TXTAREA,false,"desc");
        $this->quickInitVar('menuitem_url', XOBJ_DTYPE_TXTBOX,false,"url");   
        $this->setFieldAsRequired('menuitem_url');
        $this->quickInitVar('menuitem_weight', XOBJ_DTYPE_INT, true,"weight");
        $this->quickInitVar('menuitem_status', XOBJ_DTYPE_INT, false, "status", false, true);
        $this->quickInitVar('menuitem_hits', XOBJ_DTYPE_INT,true,"hits");
		$this->hideFieldFromForm('menuitem_hits');
		
		$this->setControl('menuitem_status', 'yesno');
		/*
		$this->setControl('menuitem_parent_id', array( 'itemHandler' => 'menuitem',
											    'method' => 'getMenuitem_parentIdArray',
											    'module' => 'immenu'
											  ));
		*/
	}
	
	function getVar($key, $format = 's') {
        if ($format == 's' && in_array($key, array('menuitem_status'))) {
            return call_user_func(array($this,$key));
        }
        return parent::getVar($key, $format);
    }
    
    private function menuitem_status(){
    	switch($this->getVar('menuitem_status', 'e') ){
    		case 1:
    			$ret = "<img src='".ICMS_URL."/images/crystal/actions/button_ok.png' alt='1'/>";
    			break;
    		case 0:
    			$ret = "<img src='".ICMS_URL."/images/crystal/actions/button_cancel.png' alt='0'/>";
    			break;
    	}
    	return $ret;
    }
        
    public function getWeightUpButton(){
		$ret = "<a href='".IMMENU_ADMIN_URL."menuitem.php?menu_id=".$this->getVar('menuitem_menu_id')."&menuitem_id=".$this->getVar('menuitem_id')."&op=wu' title='"._CO_IMMENU_MENUITEM_ADMIN_ITEMS."'>";
		$ret .= "<img src='".ICMS_URL."/images/crystal/actions/up.png' alt='"._CO_IMMENU_MENUITEM_ADMIN_ITEMS."'/>";
		$ret .= "</a>";
		return $ret;
	}
	
	public function getWeightDownButton(){
		$ret = "<a href='".IMMENU_ADMIN_URL."menuitem.php?menu_id=".$this->getVar('menuitem_menu_id')."&menuitem_id=".$this->getVar('menuitem_id')."&op=wd' title='"._CO_IMMENU_MENUITEM_ADMIN_ITEMS."'>";
		$ret .= "<img src='".ICMS_URL."/images/crystal/actions/down.png' alt='"._CO_IMMENU_MENUITEM_ADMIN_ITEMS."'/>";
		$ret .= "</a>";
		return $ret;
	}
	
}

/**
 * ImMenu Menu Item Object Handler Class
 *
 * @author Gustavo Pilla <nekro@impresscms.org>
 * @version 1.0
 */
class ImmenuMenuitemHandler extends IcmsPersistableObjectHandler {	
	
	/**
	 * Class constructor
	 */
	public function __construct($db){
		$this->IcmsPersistableObjectHandler($db, 'menuitem', 'menuitem_id', 'menuitem_title', 'menuitem_desc', 'immenu');
	}
	
	/**
	 * Before Save trigger.
	 */
	public function beforeSave(&$obj){
		$obj->setVar('menuitem_menu_id', $_GET['menu_id']);
		$url = str_replace( ICMS_URL, "",trim($obj->getVar('menuitem_url')) );
		$obj->setVar('menuitem_url', $url);
		return true;
	}
	
	/**
	 * Get Menu Item Parent Id Array
	 *
	 * todo: Improve!!! this is a shit!
	 */
	public function getMenuitem_parentIdArray(){		
		$criteria = new CriteriaCompo();
		$criteria->add(new Criteria('menuitem_menu_id', $_GET['menu_id']));
		$ret = $this->getList($criteria);
		$ret[0] = "---";
		ksort($ret);
		return $ret;
	}
	
	
    function getItemsForBlock($menu_id) {
		$criteria = new CriteriaCompo();
		$criteria->add(new Criteria('menuitem_menu_id', $menu_id));
    	$ret = $this->getObjects($criteria, true, false);
    	return $ret;
    }
	
}

?>
