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

/** @var Mage_Eav_Model_Entity_Setup $installer */
$installer = $this;

$installer->startSetup();
$installer->run("
	CREATE TABLE `{$this->getTable("SwiftOtter_Email/Email")}` (
	  id INT unsigned AUTO_INCREMENT,
	  name VARCHAR(100) NOT NULL,
	  email VARCHAR(150) NOT NULL,
	  PRIMARY KEY (id)
	);
");

$installer->run("
	CREATE TABLE `{$this->getTable("SwiftOtter_Email/Notification")}` (
	  id INT unsigned AUTO_INCREMENT,
	  email_id INT UNSIGNED,
	  code VARCHAR(25) NOT NULL,
	  PRIMARY KEY (id),
	  KEY `EMAIL_NOTIFICATION_EMAIL_ID` (`email_id`),
      CONSTRAINT `FK_EMAIL_NOTIFICATION_EMAIL_ID` FOREIGN KEY (`email_id`)
        REFERENCES `{$this->getTable('SwiftOtter_Email/Email')}` (`id`)
        ON DELETE SET NULL
        ON UPDATE SET NULL
	);
");

$installer->endSetup();