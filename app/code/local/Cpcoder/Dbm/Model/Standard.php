<?php
/*
Dutch Bangla Mobile Payment Controller
Developed By MD SANAULLAH ASIF
Dedicated to Abdul Matin

*/
class Cpcoder_Dbm_Model_Standard extends Mage_Payment_Model_Method_Abstract {
	protected $_code = 'Dbm';
	
	protected $_isInitializeNeeded      = true;
	protected $_canUseInternal          = true;
	protected $_canUseForMultishipping  = false;
	
	public function getOrderPlaceRedirectUrl() {
		return Mage::getUrl('Dbm/payment/redirect', array('_secure' => true));
	}
}
?>