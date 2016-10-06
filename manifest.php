<?php
if(!defined('sugarEntry') || !sugarEntry) die('Not A Valid Entry Point');
/*********************************************************************************
 * SugarCRM Community Edition is a customer relationship management program developed by
 * SugarCRM, Inc. Copyright (C) 2004-2013 SugarCRM Inc.

 * SuiteCRM is an extension to SugarCRM Community Edition developed by Salesagility Ltd.
 * Copyright (C) 2011 - 2014 Salesagility Ltd.
 *
 * This program is free software; you can redistribute it and/or modify it under
 * the terms of the GNU Affero General Public License version 3 as published by the
 * Free Software Foundation with the addition of the following permission added
 * to Section 15 as permitted in Section 7(a): FOR ANY PART OF THE COVERED WORK
 * IN WHICH THE COPYRIGHT IS OWNED BY SUGARCRM, SUGARCRM DISCLAIMS THE WARRANTY
 * OF NON INFRINGEMENT OF THIRD PARTY RIGHTS.
 *
 * This program is distributed in the hope that it will be useful, but WITHOUT
 * ANY WARRANTY; without even the implied warranty of MERCHANTABILITY or FITNESS
 * FOR A PARTICULAR PURPOSE.  See the GNU Affero General Public License for more
 * details.
 *
 * You should have received a copy of the GNU Affero General Public License along with
 * this program; if not, see http://www.gnu.org/licenses or write to the Free
 * Software Foundation, Inc., 51 Franklin Street, Fifth Floor, Boston, MA
 * 02110-1301 USA.
 *
 * You can contact SugarCRM, Inc. headquarters at 10050 North Wolfe Road,
 * SW2-130, Cupertino, CA 95014, USA. or at email address contact@sugarcrm.com.
 *
 * The interactive user interfaces in modified source and object code versions
 * of this program must display Appropriate Legal Notices, as required under
 * Section 5 of the GNU Affero General Public License version 3.
 *
 * In accordance with Section 7(b) of the GNU Affero General Public License version 3,
 * these Appropriate Legal Notices must retain the display of the "Powered by
 * SugarCRM" logo and "Supercharged by SuiteCRM" logo. If the display of the logos is not
 * reasonably feasible for  technical reasons, the Appropriate Legal Notices must
 * display the words  "Powered by SugarCRM" and "Supercharged by SuiteCRM".
 ********************************************************************************/

    $manifest =array(
        'acceptable_sugar_flavors' => array('CE'),
        'acceptable_sugar_versions' => array(
            'exact_matches' => array(),
            'regex_matches' => array('(6)\\.(5)\\.(.*?)$'),
        ),
        'author' => 'SuiteCRM',
        'description' => 'Allows to choose Contacts from a combo list which shows only the contacts that are at the same Account as the Opportunity; 
                          Adds a "Create Call" button on the meeting page that will create a call with the same Opportunity and Contacts as the Meeting;
                          Adds a "Create Meeting" button on the meeting page that will Create a Meeting with the same Opportunity and Contacts as the Call;',
        'icon' => '',
        'is_uninstallable' => true,
        'name' => 'Related Contacts plugin',
        'published_date' => '2016-09-01',
        'type' => 'module',
        'version' => '1.0',
    );
    
    $installdefs =array(
        'id' => 'package_1_0',
        'copy' => array(
            1 => array(
                'from' => '<basepath>/custom/Extension/application/Ext/Language/en_us.related_contacts_plugin.php',
                'to' => 'custom/Extension/application/Ext/Language/en_us.related_contacts_plugin.php',
            ),
            2 => array(
                'from' => '<basepath>/custom/Extension/modules/Calls/Ext/LogicHooks/relatetocontacts.php',
                'to' => 'custom/Extension/modules/Calls/Ext/LogicHooks/relatetocontacts.php',
            ),
            3 => array(
                'from' => '<basepath>/custom/Extension/modules/Calls/Ext/Vardefs/contacts_c.php',
                'to' => 'custom/Extension/modules/Calls/Ext/Vardefs/contacts_c.php',
            ),
            4 => array(
                'from' => '<basepath>/custom/Extension/modules/Meetings/Ext/LogicHooks/relatetocontacts.php',
                'to' => 'custom/Extension/modules/Meetings/Ext/LogicHooks/relatetocontacts.php',
            ),
            5 => array(
                'from' => '<basepath>/custom/Extension/modules/Meetings/Ext/Vardefs/contacts_c.php',
                'to' => 'custom/Extension/modules/Meetings/Ext/Vardefs/contacts_c.php',
            ),
            6 => array(
                'from' => '<basepath>/custom/modules/Calls/controller.php',
                'to' => 'custom/modules/Calls/controller.php',
            ),
            7 => array(
                'from' => '<basepath>/custom/modules/Calls/relatetocontacts.php',
                'to' => 'custom/modules/Calls/relatetocontacts.php',
            ),
            8 => array(
                'from' => '<basepath>/custom/Extension/modules/Calls/Ext/Language/en_us.related_contacts_plugin.php',
                'to' => 'custom/Extension/modules/Calls/Ext/Language/en_us.related_contacts_plugin.php',
            ),
            9 => array(
                'from' => '<basepath>/custom/modules/Calls/views/view.classic.php',
                'to' => 'custom/modules/Calls/views/view.classic.php',
            ),
            10 => array(
                'from' => '<basepath>/custom/modules/Calls/views/view.edit.php',
                'to' => 'custom/modules/Calls/views/view.edit.php',
            ),
            11 => array(
                'from' => '<basepath>/custom/modules/Meetings/controller.php',
                'to' => 'custom/modules/Meetings/controller.php',
            ),
            12 => array(
                'from' => '<basepath>/custom/modules/Meetings/jsclass_scheduler_c.js',
                'to' => 'custom/modules/Meetings/jsclass_scheduler_c.js',
            ),
            13 => array(
                'from' => '<basepath>/custom/modules/Meetings/relatetocontacts.php',
                'to' => 'custom/modules/Meetings/relatetocontacts.php',
            ),
            14 => array(
                'from' => '<basepath>/custom/Extension/modules/Meetings/Ext/Language/en_us.related_contacts_plugin.php',
                'to' => 'custom/Extension/modules/Meetings/Ext/Language/en_us.related_contacts_plugin.php',
            ),
            15 => array(
                'from' => '<basepath>/custom/modules/Meetings/views/view.classic.php',
                'to' => 'custom/modules/Meetings/views/view.classic.php',
            ),
            16 => array(
                'from' => '<basepath>/custom/modules/Meetings/views/view.edit.php',
                'to' => 'custom/modules/Meetings/views/view.edit.php',
            ),
            17 => array(
                'from' => '<basepath>/custom/Extension/application/Ext/JSGroupings/JSGroupings.php',
                'to' => 'custom/Extension/application/Ext/JSGroupings/JSGroupings.php',
            ),
        ),
        'post_execute' => array(0 => '<basepath>/post_execute.php',),
    );

?>




































































