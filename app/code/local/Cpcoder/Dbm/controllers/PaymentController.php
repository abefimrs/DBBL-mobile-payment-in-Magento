<?php
/*
Dutch Bangla Mobile Payment Controller
Developed By MD SANAULLAH ASIF
Dedicated to Abdul Matin

*/

class Cpcoder_Dbm_PaymentController extends Mage_Core_Controller_Front_Action {
	// The redirect action is triggered when someone places an order
	public function redirectAction() {
		
		$this->loadLayout();
        $block = $this->getLayout()->createBlock('Mage_Core_Block_Template','dbm',array('template' => 'bm/redirect.phtml'));
		$this->getLayout()->getBlock('content')->append($block);
        $this->renderLayout();
		
		
		/*echo "<center><h3>You are redirecting to DBBL Mobile payment Page!.Please be patient. If not redirecting please refresh the page!</h3></center>";
		$this->getResponse()
                ->setHeader('Content-type', 'text/html; charset=utf8')
                ->setBody($this->getLayout()
                ->createBlock('Mage_Core_Block_Template','dbm',array('template' => 'dbm/redirect.phtml'))
                ->toHtml());*/
	}
	
	// The response action is triggered when your gateway sends back a response after processing the customer's payment
	public function responseAction() {
		if($this->getRequest()->isPost()) {
			
			/*
			/* Your gateway's code to make sure the reponse you
			/* just got is from the gatway and not from some weirdo.
			/* This generally has some checksum or other checks,
			/* and is provided by the gateway.
			/* For now, we assume that the gateway's response is valid
			*/
			
			$cmob = $_POST['cmob'];
			$ctrxid = $_POST['ctrxid'];
			$tnxId = $_POST['orderId'];						
			
			if($cmob != "" && $ctrxid!= "")
			{
				$validated = true;
				$orderId   = $tnxId;
				$trnsactionId = $ctrxid;
				$customerMobileNumber = $cmob;
				
			} else if($cmob != "" ) {
				$validated = true;
				$orderId   = $tnxId;				
				$customerMobileNumber = $cmob;				
			} else if($ctrxid!= "") {				
				$validated = true;
				$orderId   = $tnxId;
				$trnsactionId = $ctrxid;
			} else {
				$validated = false;
			}	
			
			if($validated) {
				// Payment was successful, so update the order's state, send order email and move to the success page
				$order = Mage::getModel('sales/order');
				$order->loadByIncrementId($orderId);
				$order->setState(Mage_Sales_Model_Order::STATE_PROCESSING, true, 'Gateway has authorized the payment.');
				
				$order->sendNewOrderEmail();
				$order->setEmailSent(true);
				// customer bkash mobile number and transaction id saved for tracking
				$notification = "Customer mobile: ".$customerMobileNumber." Transaction ID: ".$trnsactionId;
				
				$order->addStatusToHistory($order->getStatus(), $notification);//saving the transaction id 				
				
				$order->save();
			
				Mage::getSingleton('checkout/session')->unsQuoteId();
				
				Mage_Core_Controller_Varien_Action::_redirect('checkout/onepage/success', array('_secure'=>true));
			}
			else {
				// There is a problem in the response we got
				
				$this->cancelAction();
				Mage_Core_Controller_Varien_Action::_redirect('checkout/onepage/failure', array('_secure'=>true));
			}
		}
		else
			Mage_Core_Controller_Varien_Action::_redirect('');
	}
	
	// The cancel action is triggered when an order is to be cancelled
	public function cancelAction() {
        if (Mage::getSingleton('checkout/session')->getLastRealOrderId()) {
            $order = Mage::getModel('sales/order')->loadByIncrementId(Mage::getSingleton('checkout/session')->getLastRealOrderId());
            if($order->getId()) {
				// Flag the order as 'cancelled' and save it
				$order->cancel()->setState(Mage_Sales_Model_Order::STATE_CANCELED, true, 'Gateway has declined the payment.')->save();
			}
        }
	}
}