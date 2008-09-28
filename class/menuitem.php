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

define('_IMMENU_TYPE_REMOTE', 1);
define('_IMMENU_TYPE_LOCAL', 2);
define('_IMMENU_TYPE_LOCAL_FULL', 3);

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

        $this->quickInitVar('menuitem_id', XOBJ_DTYPE_INT, true,_AM_IMMENU_MENUITEM_ID,_AM_IMMENU_MENUITEM_ID_DSC);
        $this->quickInitVar('menuitem_menu_id', XOBJ_DTYPE_INT, true);
        $this->hideFieldFromForm('menuitem_menu_id');
        $this->quickInitVar('menuitem_title', XOBJ_DTYPE_TXTBOX,false,_AM_IMMENU_MENUITEM_TITLE, _AM_IMMENU_MENUITEM_TITLE_DSC);
        $this->setFieldAsRequired('menuitem_title');
        $this->quickInitVar('menuitem_desc', XOBJ_DTYPE_TXTAREA, false, _AM_IMMENU_MENUITEM_DESC,_AM_IMMENU_MENUITEM_DESC_DSC);
        $this->quickInitVar('menuitem_parent_id', XOBJ_DTYPE_INT, false, _AM_IMMENU_MENUITEM_PARENTID,_AM_IMMENU_MENUITEM_PARENTID_DSC);
        $this->quickInitVar('menuitem_weight', XOBJ_DTYPE_INT, true, _AM_IMMENU_MENUITEM_WEIGHT,_AM_IMMENU_MENUITEM_WEIGHT_DSC, 0);
        $this->quickInitVar('menuitem_url', XOBJ_DTYPE_TXTBOX,false, _AM_IMMENU_MENUITEM_URL,_AM_IMMENU_MENUITEM_URL_DSC);
        $this->setFieldAsRequired('menuitem_url');
        $this->quickInitVar('menuitem_type', XOBJ_DTYPE_INT, true,_AM_IMMENU_MENUITEM_TYPE,_AM_IMMENU_MENUITEM_TYPE_DSC);
        $this->hideFieldFromForm('menuitem_type');
        $this->quickInitVar('menuitem_status', XOBJ_DTYPE_INT, false, _AM_IMMENU_MENUITEM_STATUS,_AM_IMMENU_MENUITEM_STATUS_DSC, true);
        
        $this->quickInitVar('menuitem_hits', XOBJ_DTYPE_INT, false, _AM_IMMENU_MENUITEM_HITS,_AM_IMMENU_MENUITEM_HITS_DSC);
	
        $this->quickInitVar('menuitem_docount', XOBJ_DTYPE_INT, false, _AM_IMMENU_MENUITEM_DOCOUNT,_AM_IMMENU_MENUITEM_DOCOUNT_DSC, false);
		
		//$this->initCommonVar('counter', false);
		
		$this->setControl('menuitem_status', 'yesno');
		
		$this->setControl('menuitem_docount', 'yesno');
		
		$this->setControl('menuitem_parent_id', array( 'itemHandler' => 'menuitem',
											    'method' => 'getMenuitemParentIdArray',
											    'module' => 'immenu'
											  ));
		
		
		$this->setControl('menuitem_type', array( 'itemHandler' => 'menuitem',
											    'method' => 'getMenuitemTypeArray',
											    'module' => 'immenu'
											  ));
	}
		
	public function getVar($key, $format = 's') {
        if ($format == 's' && in_array($key, array('menuitem_status','menuitem_hits'))) {
            return call_user_func(array($this,$key));
        }
        return parent::getVar($key, $format);
    }
    
    private function menuitem_hits(){
    	if($this->getVar('menuitem_docount') == 1){
    		return $this->getVar('menuitem_hits','e');
    	}else{
    		return _AM_IMMENU_MENUITEM_NOCOUNTER;
    	}
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
		$ret = "<a href='".IMMENU_ADMIN_URL."menuitem.php?menu_id=".$this->getVar('menuitem_menu_id')."&menuitem_id=".$this->getVar('menuitem_id')."&op=wu' title='"._AM_IMMENU_MENUITEM_WU_ITEM."'>";
		$ret .= "<img src='".ICMS_URL."/images/crystal/actions/up.png' alt='"._AM_IMMENU_MENUITEM_WU_ITEM."'/>";
		$ret .= "</a>";
		return $ret;
	}
	
	public function getWeightDownButton(){
		$ret = "<a href='".IMMENU_ADMIN_URL."menuitem.php?menu_id=".$this->getVar('menuitem_menu_id')."&menuitem_id=".$this->getVar('menuitem_id')."&op=wd' title='"._AM_IMMENU_MENUITEM_WD_ITEM."'>";
		$ret .= "<img src='".ICMS_URL."/images/crystal/actions/down.png' alt='"._AM_IMMENU_MENUITEM_WD_ITEM."'/>";
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
	public function getMenuitemParentIdArray(){		
		//$criteria = new CriteriaCompo();
		//$criteria->add(new Criteria('menuitem_menu_id', $_GET['menu_id']));
		//$ret = $this->getList($criteria);
		$ret[0] = "---";
		ksort($ret);
		return $ret;
	}
	
	public function getMenuitemTypeArray(){
		$ret = array();
		$ret[_IMMENU_TYPE_REMOTE] = _AM_IMMENU_MENUITEM_TYPE_REMOTE;
		$ret[_IMMENU_TYPE_LOCAL] = _AM_IMMENU_MENUITEM_TYPE_LOCAL;
		$ret[_IMMENU_TYPE_LOCAL_FULL] = _AM_IMMENU_MENUITEM_TYPE_LOCAL_FULL;
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
