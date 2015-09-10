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
 
class SwiftOtter_Email_Model_Resource_Notification extends Mage_Core_Model_Resource_Db_Abstract
{
    protected function _construct()
    {
        $this->_init('SwiftOtter_Email/Notification', 'id');
    }

    /**
     * @param SwiftOtter_Email_Model_Email $email
     */
    public function synchronizeNotifications($email)
    {
        $write = $this->_getWriteAdapter();

        $notifications = $email->getNotificationsInput();
        if (!$notifications) {
            $notifications = array();
        }

        $oldNotifications = $this->getNotifications($email);

        $insert = array_diff($notifications, $oldNotifications);
        $delete = array_diff($oldNotifications, $notifications);

        if (!empty($insert)) {
            $data = array();
            foreach ($insert as $code) {
                if ($code) {
                    $data[] = array(
                        'email_id' => $email->getId(),
                        'code'     => $code
                    );
                }
            }

            if (!empty($data)) {
                $write->insertMultiple($this->getMainTable(), $data);
            }
        }

        if (!empty($delete)) {
            foreach ($delete as $code) {
                $where = array(
                    'email_id = ?'  => $email->getId(),
                    'code = ?' => $code,
                );

                $write->delete($this->getMainTable(), $where);
            }
        }
    }

    /**
     * Retrieve product category identifiers
     *
     * @param SwiftOtter_Email_Model_Email $email
     * @return array
     */
    public function getNotifications($email)
    {
        $adapter = $this->_getReadAdapter();

        $select = $adapter->select()
            ->from($this->getMainTable(), 'code')
            ->where('email_id = ?', (int)$email->getId());

        return $adapter->fetchCol($select);
    }
}