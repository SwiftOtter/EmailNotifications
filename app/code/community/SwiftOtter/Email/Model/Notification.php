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
 * Class SwiftOtter_Email_Model_Notification
 *
 * @method int getEmailId()
 * @method SwiftOtter_Email_Model_Notification setEmailId()
 * @method bool getApplied()
 * @method SwiftOtter_Email_Model_Notification setApplied($applied)
 * @method int getCode()
 * @method SwiftOtter_Email_Model_Notification setCode($code)
 * @method int getLabel()
 * @method SwiftOtter_Email_Model_Notification setLabel($code)
 */

class SwiftOtter_Email_Model_Notification extends Mage_Core_Model_Abstract
{
    protected $_email;

    protected function _construct()
    {
        $this->_init('SwiftOtter_Email/Notification');
    }

    /**
     * @param SwiftOtter_Email_Model_Email|string $email
     * @param bool $quiet
     */
    public function setEmail($email, $quiet = false)
    {
        $id = -1;
        if (get_class($email) == 'SwiftOtter_Email_Model_Email') {
            $id = $email->getId();

            if (!$id) {
                $email->save();
            }
        } else if (is_string($email) && !$quiet) {
            $email = Mage::getModel('SwiftOtter_Email/Email');
            $email->setEmail($email);
            $email->setName($email);

            $email->save();

            $id = $email->getId();
        }

        $this->_email = $email;
        $this->setEmailId($id);
    }

    public function getEmail()
    {
        if (!$this->_email) {
            $this->_email = Mage::getModel('SwiftOtter_Email/Notification')->load($this->getEmailId());
        }

        return $this->_email;
    }
}