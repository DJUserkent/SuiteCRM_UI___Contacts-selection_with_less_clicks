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


require_once('include/MVC/View/views/view.classic.php');

class CallsViewClassic extends ViewClassic{

    public function display(){
        $this->populateUsers();
        parent::display();
        echo '<script> $(function() { sqs_objects["form_SubpanelQuickCreate_Meetings_parent_name"]["post_onblur_function"] =  "getContacts();"; }); </script>';
    }

    function populateUsers(){
        global $app_list_strings;

        if(isset($_REQUEST['record'])){
            $record = $_REQUEST['record'];
        } else {
            $record = $_REQUEST['return_id'];
        }

        $module = 'Calls';
        $focus = BeanFactory::getBean($module, $record); //meeting

        function custom_sort($a, $b){
            return strnatcmp($a->name, $b->name);
        }

        //Accounts
        if($focus->parent_type == "Accounts") {
            $related = BeanFactory::getBean($focus->parent_type, $focus->parent_id);
            $rel = "contacts";
            if($related->load_relationship($rel)){;
                $arrayContacts = $related->$rel->getBeans();
                $app_list_strings['contacts'] = array();
                uasort($arrayContacts, 'custom_sort');
                foreach($arrayContacts as $contact){
                    $app_list_strings['contacts'][ $contact->id ] = $contact->full_name;
                }

            }
        }

        //Opportunities
        if($focus->parent_type == "Opportunities") {
            $related = BeanFactory::getBean($focus->parent_type, $focus->parent_id);
            $relatedAccount = BeanFactory::getBean('Accounts', $related->account_id);
            $rel = "contacts";
            if($relatedAccount->load_relationship($rel)){;
                $arrayContacts = $relatedAccount->$rel->getBeans();
                $app_list_strings['contacts'] = array();
                uasort($arrayContacts, 'custom_sort');
                foreach($arrayContacts as $contact){
                    $app_list_strings['contacts'][ $contact->id ] = $contact->full_name;
                }

            }
        }

echo "<script>
    $(function() {
        $('#parent_type').change(function() {
            var parent_type = $('#parent_type').val();
            var parent_id = $('#parent_id').val();
            if(parent_type != 'Accounts' && parent_type != 'Opportunities'){
                 $('#contacts_c').html('');
            }
        });

        $('#btn_parent_name').unbind('click').removeAttr('onclick').click(function(e) {
              e.preventDefault();
              open_popup($('#parent_type').val(), 600, 400, '', true, false, {'call_back_function':'custom_set_return','form_name':'form_SubpanelQuickCreate_Calls','field_to_name_array':{'id':'parent_id','name':'parent_name'}}, 'single', true);

         });
    })

    getContacts();

    function custom_set_return(return_data){
        set_return(return_data);
        getContacts();
    }

    function getContacts(){
        var parent_type = $('#parent_type').val();
        var parent_id = $('#parent_id').val();
         if(parent_id){
          $.ajax({dataType: 'html', url: \"index.php?module=Calls&action=getcontactresults&record=$record&parent_type=\"+parent_type+\"&parent_id=\"+parent_id, success: function(result){
            $('#contacts_c').html(result);
          }});
        }
    }

</script>";

    }
}