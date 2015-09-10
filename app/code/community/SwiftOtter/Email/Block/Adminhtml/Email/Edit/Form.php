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

class SwiftOtter_Email_Block_Adminhtml_Email_Edit_Form extends Mage_Adminhtml_Block_Widget_Form
{
    protected function _prepareForm()
    {
        $data = Mage::registry('form_data');
        if (!$data) {
            $data = new Varien_Object();
        }

        $form = new Varien_Data_Form(array(
            'id' => 'edit_form',
            'action' => $this->getUrl('*/*/save', array('id'=> $this->getRequest()->getParam('id'))),
            'method' => 'post',
            'enctype' => 'multipart/form-data',
            'class' => 'fieldset-wide'
        ));

        $form->setUseContainer(true);

        $this->setForm($form);

        $fieldset = $form->addFieldset('menu_form', array('legend' => $this->__('Email Information')));
        $yesNo = array("0" => "No", "1" => "Yes");

        $fieldset->addField('name', 'text', array(
                'label' => $this->__('Name'),
                'class' => 'required-entry',
                'required' => true,
                'name' => 'name',
            ));

        $fieldset->addField('email', 'text', array(
            'label' => $this->__('Email'),
            'required' => true,
            'name' => 'email'
        ));

        $values = array();
        $notifications = array();
        $collection = Mage::getResourceModel('SwiftOtter_Email/Notification_Collection')
            ->loadByEmail($data->getEmail())->getMerged();

        /* @var SwiftOtter_Email_Model_Notification $value */
        foreach ($collection as $value) {
            $values[] = array(
                'value' => $value->getCode(),
                'label' => $value->getLabel()
            );

            if ($value->getApplied()) {
                $notifications[] = $value->getCode();
            }
        }

        $data->setNotifications($notifications);

        $fieldset->addField('notifications', 'checkboxes', array(
            'label'     => $this->__('Global Notifications'),
            'name'      => 'notifications_input[]',
            'values'    => $values
        ));

        $data->setHasNotificationsInput(1);

        $fieldset->addField('has_notifications_input', 'hidden', array(
            'value'     => '1',
            'name'     => 'has_notifications_input'
        ));



        $form->setValues($data);

        return parent::_prepareForm();
    }
}