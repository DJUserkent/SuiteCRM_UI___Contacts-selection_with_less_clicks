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


require_once('modules/Meetings/views/view.edit.php');

class customMeetingsViewEdit extends MeetingsViewEdit{
    public function display(){
        $this->populateUsers();
        parent::display();
    }

    function populateUsers(){
        global $app_list_strings;

        if(!empty($_REQUEST['record'])){
            $record = $_REQUEST['record'];
        } else {
            $record = $_REQUEST['return_id'];
        }

        $module = 'Meetings';
        $focus = BeanFactory::getBean($module, $record); //meeting


        if(!isset($focus->parent_type)){
            $module = 'Calls';
            $focus = BeanFactory::getBean($module, $record); //meeting
        }

        //Accounts
        if($focus->parent_type == "Accounts") {
            $related = BeanFactory::getBean($focus->parent_type, $focus->parent_id);
            $rel = "contacts";
            if($related->load_relationship($rel)){;
                $arrayContacts = $related->$rel->getBeans();
                $app_list_strings['contacts'] = array();
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
                foreach($arrayContacts as $contact){
                    $app_list_strings['contacts'][ $contact->id ] = $contact->full_name;
                }
            }
        }

        $this->bean->parent_id = $focus->parent_id;
        $this->bean->parent_type = $focus->parent_type;
        $this->bean->parent_name = $focus->parent_name;

        if(!empty($focus)){
            $relContacts = "contacts";
            if($focus->load_relationship($relContacts)){
                $parent_id=$focus->parent_id;
                $parent_type=$focus->parent_type;
                $temp = $focus->$relContacts->getBeans();
            }
        }

        if(!empty($temp)){
            foreach($temp as $contact){
                $tempArr[] = $contact->id;
            }
            $temp = implode(",", $tempArr);
        }

echo "<script>
   $(function() {

       sqs_objects[\"EditView_parent_name\"] = {
        \"form\": \"EditView\",
        \"method\": \"query\",
        \"modules\": [\"Opportunities\"],
        \"group\": \"or\",
        \"field_list\": [\"name\", \"id\"],
        \"populate_list\": [\"parent_name\", \"parent_id\"],
        \"required_list\": [\"parent_id\"],
        \"conditions\": [{
            \"name\": \"name\",
            \"op\": \"like_custom\",
            \"end\": \"%\",
            \"value\": \"\"
        }],
        \"order\": \"name\",
        \"limit\": \"30\",
        \"post_onblur_function\": \"getContacts();\",
        \"no_match_text\": \"No Match\"
    };


                $('#parent_type').change(function() {
                    var parent_type = $('#parent_type').val();
                    var parent_id = $('#parent_id').val();
                    if(parent_type != 'Accounts' && parent_type != 'Opportunities'){
                         $('#contacts_c').html('');
                    }
                });

                $(document).ready(function() {
                    var parent_type = $('#parent_type').val();
                    var parent_id = $('#parent_id').val();
                    if(parent_type != 'Accounts' && parent_type != 'Opportunities'){
                         $('#contacts_c').html('');
                    }
                    getContacts();
                });

                $('#btn_parent_name').unbind('click').removeAttr('onclick').click(function(e) {
                      e.preventDefault();
                      open_popup($('#parent_type').val(), 600, 400, '', true, false, {'call_back_function':'custom_set_return','form_name':'EditView','field_to_name_array':{'id':'parent_id','name':'parent_name'}}, 'single', true);

                 });
})


                    function custom_set_return(return_data){
                        set_return(return_data);
                        getContacts();
                    }

                    function getContacts(){
                        var parent_type = $('#parent_type').val();
                        var parent_id = $('#parent_id').val();
                        if(!parent_id){
                            parent_id = \"$parent_id\";
                        }
                        if(!parent_type){
                            parent_type = \"$parent_type\";
                        }

                         if(parent_id){
                          $.ajax({dataType: 'html', url: \"index.php?module=Meetings&action=getcontactresults&record=$record&parent_type=\"+parent_type+\"&parent_id=\"+parent_id, success: function(result){
                            $('#contacts_c').html(result);
                            if(result){
                               var values = \"$temp\";
                                $.each(values . split(\",\"), function (i,e){
                                    $(\"#contacts_c option[value='\" + e + \"']\").prop(\"selected\", true);
                                });
                            }
                          }});
                        }
                    }


</script>";

    }
}