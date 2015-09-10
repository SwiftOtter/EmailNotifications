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
 
class SwiftOtter_Email_Model_Resource_Notification_Collection extends Mage_Core_Model_Resource_Db_Collection_Abstract
{
    const XML_SWIFTOTTER_EMAIL_NOTIFICATIONS = 'global/swiftotter_email/notifications';

    protected $_config;
    protected $_merged;

    protected function _construct()
    {
        $this->_init('SwiftOtter_Email/Notification');
    }

    public function getConfig()
    {
        if (!$this->_config) {
            $nodes = Mage::getConfig()->getNode(self::XML_SWIFTOTTER_EMAIL_NOTIFICATIONS)->asArray();
            $helper = Mage::helper('SwiftOtter_Email');

            $data = array();

            foreach ($nodes as $code => $input) {
                if (!isset($input['private']) || (int)$input['private'] <= 0) {
                    $data[] = array(
                        'code' => $code,
                        'applied' => false,
                        'label' => $helper->__($input['label'])
                    );
                }
            }

            $this->_config = $data;
        }

        return $this->_config;
    }

    public function getMerged()
    {
        if (!$this->_merged) {
            $merged = Mage::getResourceModel('SwiftOtter_Email/Notification_Collection');
            $config = $this->getConfig();
            $pseudoId = 0;

            foreach ($config as $input) {
                $selected = null;
                /** @var SwiftOtter_Email_Model_Notification $item */
                foreach ($this as $item) {
                    if ($item->getCode() == $input['code']) {
                        $selected = $item;
                        break;
                    }
                }

                if (!$selected) {
                    $selected = Mage::getModel('SwiftOtter_Email/Notification');
                    $selected->setCode($input['code']);
                    $selected->setLabel($input['label']);
                    $selected->setId($pseudoId++);
                } else {
                    $selected->setApplied(true);
                }

                if (!$merged->itemExistsById($selected->getId())) {
                    $merged->addItem($selected);
                }
            }

            $merged->setIsLoaded(true);
            $this->_merged = $merged;
        }

        return $this->_merged;
    }

    public function setIsLoaded($flag)
    {
        $this->_setIsLoaded($flag);
    }

    public function itemExistsById($id)
    {
        if (is_array($this->_items)) {
            foreach ($this->_items as $item) {
                if ($item->getId() == $id) {
                    return true;
                }
            }
        }
    }


    protected function _afterLoad()
    {
        $config = $this->getConfig();

        /** @var SwiftOtter_Email_Model_Notification $item */
        foreach ($this as $item) {
            foreach ($config as $input) {
                if ($input['code'] == $item->getCode()) {
                    $item->setLabel($input['label']);
                }
            }
        }

        return parent::_afterLoad();
    }

    /**
     * Loads collection, filtered by email
     *
     * @param $email
     * @return $this
     */
    public function loadByEmail($email)
    {
        $this->join(
            array('email' => 'SwiftOtter_Email/Email'),
            'email.id = main_table.email_id',
            array(
                'email'
            )
        );

        $this->addFieldToFilter('email', array('eq' => $email));

        return $this;
    }

    public function loadByEmailId($emailId)
    {
        $this->addFieldToFilter('email_id', array('eq' => $emailId));

        return $this;
    }
}