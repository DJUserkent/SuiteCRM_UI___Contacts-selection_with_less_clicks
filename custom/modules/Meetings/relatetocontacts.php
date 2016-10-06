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

class relatetocontacts{

    /**
     *
     * check to see if there is a name filled without an id and see if we can find the service partner.
     *
     * @param $bean
     * @param $event
     * @param $args
     */
    function relate($bean, $event, $args){
        if(  empty($bean->fetched_row) && empty($bean->fetched_row['id'])  ) {
            //$tmp = encodeMultienumValue();
            $arrContactIds = unencodeMultienum($bean->contacts_c);

            if (isset($_REQUEST['parent_id']) && !empty($_REQUEST['parent_id'])) {
                $record = $_REQUEST['parent_id'];
            }
            if (isset($_REQUEST['parent_type'])) {
                $parent_type = $_REQUEST['parent_type'];
            }
            //load the relationship in case it is not loaded.
            $bean->load_relationship("contacts");

            //delete only the entries that match the contacts field from the scheduling bar
            if ($parent_type == "Accounts") {
                $related = BeanFactory::getBean($parent_type, $record);
                $rel = "contacts";
                if ($related->load_relationship($rel)) {
                    $arrayContacts = $related->$rel->getBeans();
                    $app_list_strings['contacts'] = array();
                    foreach ($arrayContacts as $contact) {
                        if ($bean->contacts) {
                            $bean->contacts->delete($bean->id, $contact);
                        }
                    }
                }
            } else if ($parent_type == "Opportunities") {
                $related = BeanFactory::getBean($parent_type, $record);
                $relatedAccount = BeanFactory::getBean('Accounts', $related->account_id);
                $rel = "contacts";
                if ($relatedAccount->load_relationship($rel)) {
                    ;
                    $arrayContacts = $relatedAccount->$rel->getBeans();
                    $app_list_strings['contacts'] = array();
                    foreach ($arrayContacts as $contact) {
                        if ($bean->contacts) {
                            $bean->contacts->delete($bean->id, $contact);
                        }
                    }
                }
            }

            //$bean->contacts->delete($bean->id);
            //add selected contacts to scheduling bar
            foreach ($arrContactIds as $val) {
                if ($bean->contacts) {
                    $bean->contacts->add($val);
                }
            }
        }
    }
}