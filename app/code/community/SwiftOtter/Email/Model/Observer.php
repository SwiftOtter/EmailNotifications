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

class SwiftOtter_Email_Model_Observer
{
    public function swiftotterEmailNotificationSend($observer)
    {
        $code = $observer->getCode();
        $emails = $observer->getEmails();
        $inputEmailsOnly = $observer->getInputEmailsOnly();

        if (!is_array($emails)) {
            $emails = array($emails);
        }

        /** @var SwiftOtter_Email_Model_Details $details */
        $details = $observer->getDetails();

        if (get_class($details) !== 'SwiftOtter_Email_Model_Details') {
            throw new Exception('Event must be dispatched with class of type: SwiftOtter_Email_Model_Details');
        }

        if ($code && !$inputEmailsOnly) {
            $globalEmails = Mage::getResourceModel('SwiftOtter_Email/Email')->getEmailsByCode($code);
            $emails = array_merge($emails, $globalEmails);
        }

        Mage::log('Transactional email sending...');
        Mage::log($emails);

        Mage::helper('SwiftOtter_Email/Email')->sendTransactional($code, $emails, $details);
    }
}