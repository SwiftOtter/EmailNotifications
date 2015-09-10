<?php
/**
 * SwiftOtter_Base is free software: you can redistribute it and/or modify
 * it under the terms of the GNU General Public License as published by
 * the Free Software Foundation, either version 3 of the License, or
 * (at your option) any later version.
 *
 * SwiftOtter_Base is distributed in the hope that it will be useful,
 * but WITHOUT ANY WARRANTY; without even the i^mplied warranty of
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

class SwiftOtter_Email_Adminhtml_EmailController extends Mage_Adminhtml_Controller_Action
{
    protected function _isAllowed()
    {
        return Mage::getSingleton('admin/session')->isAllowed('system/swiftotter_email');
    }

    public function testAction()
    {
        $details = Mage::getModel('SwiftOtter_Email/Details')
            ->setSubject('Test Email')
            ->setDescription('Information goes here.')
            ->setTitle('Information')
            ->setText('A lot of text goes here');

        Mage::dispatchEvent('swiftotter_email_notification_send', array(
            'details' => $details,
            'code' => 'temp'
        ));
    }

    public function indexAction()
    {
        $this->loadLayout();
        $this->renderLayout();
    }

    public function newAction()
    {
        $this->_forward('edit');
    }

    public function editAction()
    {
        $email = $this->_loadEmail();

        $this->loadLayout();
        $this->renderLayout();
    }

    public function saveAction() {
        $email = $this->_loadEmail();

        if ($data = $this->getRequest()->getPost()) {
            $id = $this->getRequest()->getParam('id');
            $email->setData($data);

            Mage::getSingleton('adminhtml/session')->setFormData($data);

            try {
                if ($id) {
                    $email->setId($id);
                }
                $email->save();

                Mage::getSingleton('adminhtml/session')->addSuccess($this->__('Success!'))->setFormData(false);

                if ($this->getRequest()->getParam('back')) {
                    $this->_redirect('*/*/edit', array('id' => $email->getId()));
                } else {
                    $this->_redirect('*/*/');
                }
            } catch (Exception $e) {
                Mage::getSingleton('adminhtml/session')->addError($e->getMessage());
                if ($email && $email->getId()){
                    $this->_redirect('*/*/edit', array('id' => $email->getId()));
                } else {
                    $this->_redirect('*/*/');
                }
            }
            return;
        }

        Mage::getSingleton('adminhtml/session')->addError($this->__('No data specified!'));
        $this->_redirect('*/*/');
    }

    public function deleteAction() {
        $email = $this->_loadEmail();

        try {
            if ($email->getId()) {
                $email->delete();

                Mage::getSingleton('adminhtml/session')->addSuccess($this->__('Successfully deleted'));
                $this->_redirect('*/*/');
            }
        } catch(Exception $ex) {
            Mage::getSingleton('adminhtml/session')->addError($ex->getMessage());
            $this->_redirect('*/*/');
        }
    }


    protected function _loadEmail($emailId = null)
    {
        if (!$emailId) {
            $emailId = $this->getRequest()->getParam('id');
        }

        $email = Mage::getModel('SwiftOtter_Email/Email')->load($emailId);

        if ($email->getId()) {
            Mage::register('form_data', $email);
            $this->_title($this->__('Editing Email: %s', $email->getName()));
        }

        return $email;
    }
}