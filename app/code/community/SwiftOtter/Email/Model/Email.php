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
 * @copyright Swift Otter Studios, 7/7/14
 * @package default
 **/

/**
 * Class SwiftOtter_Email_Model_Email
 *
 * @method string getName()
 * @method $this  setName($name)
 * @method string getEmail()
 * @method $this  setEmail($email)
 */

class SwiftOtter_Email_Model_Email extends Mage_Core_Model_Abstract
{
    protected $_notifications;

    protected function _construct()
    {
        $this->_init('SwiftOtter_Email/Email');
    }

    protected function _afterSave()
    {
        if ($this->getNotificationsInput() || $this->getHasNotificationsInput()) {
            Mage::getResourceModel('SwiftOtter_Email/Notification')->synchronizeNotifications($this);
        }
    }

    public function getNotifications()
    {
        if (!$this->_notifications) {
            $this->_notifications = Mage::getResourceModel('SwiftOtter_Email/Notification_Collection');
        }

        return $this->_notifications;
    }
}