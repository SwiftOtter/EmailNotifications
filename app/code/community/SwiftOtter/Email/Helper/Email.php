<?php
/**
 * SwiftOtter_Base is free software: you can redistribute it and/or modify
 * it under the terms of the GNU General Public License as published by
 * the Free Software Foundation, either version 3 of the License, or
 * (at your option) any later version.
 *
 * SwiftOtter_Base is distributed in the hope that it will be useful,
 * but WITHOUT ANY WARRANTY; without even the implied warranty of
 * MERCHANTABILITY or FITNESS FOR A PARTICULAR PURPOSE.  See the
 * GNU General Public License for more details.
 * You should have received a copy of the GNU General Public License
 * along with SwiftOtter_Base. If not, see <http://www.gnu.org/licenses/>.
 *
 * Copyright: 2013 (c) SwiftOtter Studios
 *
 * @author Joseph Maxwell
 * @copyright Swift Otter Studios, 7/17/14
 * @package default
 **/

class SwiftOtter_Email_Helper_Email extends Mage_Core_Helper_Abstract
{
    const XML_SWIFTOTTER_EMAIL_NOTIFICATIONS = 'global/swiftotter_email/notifications';
    const XML_SWIFTOTTER_EMAIL_NOTIFICATION_TEMPLATE = 'swiftotter_config/notification_email/template';

    public function sendTransactional ($code, $emails, $details, $subject = '')
    {
        if (!$subject) {
            $subject = $this->getEmailLabel($code);
        }

        $translate = Mage::getSingleton('core/translate');
        /* @var $translate Mage_Core_Model_Translate */
        $translate->setTranslateInline(false);

        $mailTemplate = Mage::getModel('core/email_template');
        /* @var $mailTemplate Mage_Core_Model_Email_Template */

        $template = Mage::getStoreConfig(self::XML_SWIFTOTTER_EMAIL_NOTIFICATION_TEMPLATE, Mage::app()->getStore()->getId());
        $sendTo = array();
        foreach ($emails as $recipient)
        {
            if ($recipient) {
                $sendTo[] = $recipient;
            }
        }

        Mage::log('Transactional email template: ' . $template);

        foreach ($sendTo as $recipient) {
            try {
                $mailTemplate->setDesignConfig(array('area'=>'frontend', 'store' => Mage::app()->getStore()->getId()));
                $mailTemplate->sendTransactional(
                    $template,
                    Mage::getStoreConfig(Mage_Sales_Model_Order::XML_PATH_EMAIL_IDENTITY, Mage::app()->getStore()->getId()),
                    $recipient,
                    $this->__('Notifications'),
                    array(
                        'subject' => $subject,
                        'details' => $details
                    )
                );
            } catch (Exception $ex) {
                Mage::log('Transactional email error: ' . $ex->getMessage() . '(' .$ex->getLine() . ', ' . $ex->getFile() . ')');
            }
        }

        $translate->setTranslateInline(true);

        return $this;
    }

    public function getEmailLabel($inputCode)
    {
        $nodes = Mage::getConfig()->getNode(self::XML_SWIFTOTTER_EMAIL_NOTIFICATIONS)->asArray();
        $helper = Mage::helper('SwiftOtter_Email');

        foreach ($nodes as $code => $input) {
            if ($code == $inputCode) {
                return $helper->__($input['label']);
            }
        }
    }
}