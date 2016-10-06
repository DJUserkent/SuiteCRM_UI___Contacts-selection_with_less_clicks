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

    // Debug point - checking to see if get to post_install script
    echo "Made it to the post_execute script.<br />";

    // Use the ParserFactory to edit the view arrays
    // Fetch the existing view into an array called $calls_detailview
    require_once('modules/ModuleBuilder/parsers/ParserFactory.php');
    $calls_detailview = ParserFactory::getParser('detailview','Calls');
    // Declare the additional content 
    $create_call_button = array
    (
        'customCode' => '<input type="submit" class="button" onClick="this.form.action.value=\'EditView\'; this.form.return_action.value=\'DetailView\'; this.form.return_module.value=\'Calls\'; this.form.return_id.value=\'{$fields.id.value}\'; this.form.module.value=\'Calls\'; " value="{$MOD.LBL_CREATE_CALL_BUTTON_TITLE}">',
        'sugar_html' =>
            array (
                'type' => 'submit',
                'value' => '{$APP.LBL_CREATE_CALL_BUTTON_TITLE}',
                'htmlOptions' =>
                    array (
                        'title' => '{$APP.LBL_CREATE_CALL_BUTTON_LABEL}',
                        'accesskey' => '{$APP.LBL_CREATE_CALL_BUTTON_KEY}',
                        'class' => 'button',
                        'style' => 'display: inline-block',
                        'onclick' => 'this.form.action.value=\'EditView\'; this.form.return_action.value=\'DetailView\'; this.form.return_module.value=\'Calls\'; this.form.return_id.value=\'{$fields.id.value}\'; this.form.module.value=\'Calls\'; this.form.record.value=\'\';',
                        'name' => '{$APP.LBL_CREATE_CALL_BUTTON_LABEL}',
                        'id' => 'create_call_button',
                    ),
                'template' => '{if $fields.status.value != "Held" && $bean->aclAccess("edit")}[CONTENT]{/if}',
            ),
    );
    $create_meeting_button = array
    (
        'customCode' => '<input type="submit" class="button" onClick="this.form.action.value=\'EditView\'; this.form.return_action.value=\'DetailView\'; this.form.return_module.value=\'Meetings\'; this.form.return_id.value=\'{$fields.id.value}\'; this.form.module.value=\'Meetings\';',
        'sugar_html' =>
            array (
                'type' => 'submit',
                'value' => '{$APP.LBL_CREATE_MEETING_BUTTON_TITLE}',
                'htmlOptions' =>
                    array (
                        'title' => '{$APP.LBL_CREATE_MEETING_BUTTON_LABEL}',
                        'accesskey' => '{$APP.LBL_CREATE_MEETING_BUTTON_KEY}',
                        'class' => 'button',
                        'style' => 'display: inline-block',
                        'onclick' => 'this.form.action.value=\'EditView\'; this.form.return_action.value=\'DetailView\'; this.form.return_module.value=\'Calls\'; this.form.return_id.value=\'{$fields.id.value}\'; this.form.module.value=\'Meetings\'; this.form.record.value=\'\';',
                        'name' => '{$APP.LBL_CREATE_MEETING_BUTTON_LABEL}',
                        'id' => 'create_meeting_button',
                    ),
                'template' => '{if $fields.status.value != "Held" && $bean->aclAccess("edit")}[CONTENT]{/if}',
            ),
    );

    // Add buttons the desired section of the view array
    $calls_detailview->_viewdefs['templateMeta']['form']['buttons'][] = $create_call_button;
    $calls_detailview->_viewdefs['templateMeta']['form']['buttons'][] = $create_meeting_button;

    //Save the layout
    $calls_detailview->handleSave(false);
    echo "Calls - Detail View is OK.<br />";






    $calls_editview = ParserFactory::getParser('editview','Calls');
    $script = '<script>{literal}$("#contacts_c").change(function() {
        
        //remove unselected options from scheduling bar
        $("#contacts_c").find(\'option:not(:selected)\').each(function( index, element ){
            SugarWidgetScheduleRow.deleteRow_c(element.value);
        });
        
        //add selected options to scheduling bar
        $("#contacts_c").find(\'option:selected\').each(function( index, element ){
            var contactExists = false;
            $.each( GLOBAL_REGISTRY.focus.users_arr, function( index, element){
                if (typeof(this.fields != "undefined")){
                    if (this.fields.id == element.value){
                        contactExists = true;
                    }
                }
            });
            if (contactExists == false){
                //delete any copies of the same entry
                SugarWidgetScheduleRow.deleteRow_c(element.value);
                SugarWidgetSchedulerAttendees.form_add_attendee_c(element);
            }
        });
        
        //finally, display all the above changes made
        GLOBAL_REGISTRY.scheduler_attendees_obj.display();  
    });{/literal}</script>';

    // Add javascript the desired section of the view array    
    $calls_editview->_viewdefs['templateMeta']['javascript'] = $calls_editview->_viewdefs['templateMeta']['javascript'] . $script;

    $calls_editview_panel = array
    (
        1 =>
            array (
                0 =>
                    array (
                        'name' => 'contacts_c',
                        'studio' => 'visible',
                        'label' => 'LBL_CONTACTS',
                    ),
                1 =>
                    array (
                    ),
            ),
    );

    // Add panel with a custom field
    $calls_editview->_viewdefs['panels']['LBL_CONTACTS'] = $calls_editview_panel;

    //Save the layout
    $calls_editview->handleSave(false);
    echo "Calls - Edit View is OK.<br />";






    $meeting_detailview = ParserFactory::getParser('detailview','Meetings');
    // Declare the additional content 
    $create_call_button = array
    (
        'customCode' => '<input type="submit" class="button" onClick="this.form.action.value=\'EditView\'; this.form.return_action.value=\'DetailView\'; this.form.return_module.value=\'Meetings\'; this.form.return_id.value=\'{$fields.id.value}\'; this.form.module.value=\'Calls\'; " value="{$MOD.LBL_CREATE_CALL_BUTTON_TITLE}">',
        'sugar_html' =>
            array (
                'type' => 'submit',
                'value' => '{$APP.LBL_CREATE_CALL_BUTTON_TITLE}',
                'htmlOptions' =>
                    array (
                        'title' => '{$APP.LBL_CREATE_CALL_BUTTON_LABEL}',
                        'accesskey' => '{$APP.LBL_CREATE_CALL_BUTTON_KEY}',
                        'class' => 'button',
                        'style' => 'display: inline-block',
                        'onclick' => 'this.form.action.value=\'EditView\'; this.form.return_action.value=\'DetailView\'; this.form.return_module.value=\'Meetings\'; this.form.return_id.value=\'{$fields.id.value}\'; this.form.module.value=\'Calls\'; this.form.record.value=\'\';',
                        'name' => '{$APP.LBL_CREATE_CALL_BUTTON_LABEL}',
                        'id' => 'create_call_button',
                    ),
                'template' => '{if $fields.status.value != "Held" && $bean->aclAccess("edit")}[CONTENT]{/if}',
            ),
    );
    $create_meeting_button = array
    (
        'customCode' => '<input type="submit" class="button" onClick="this.form.action.value=\'EditView\'; this.form.return_action.value=\'DetailView\'; this.form.return_module.value=\'Meetings\'; this.form.return_id.value=\'{$fields.id.value}\'; this.form.module.value=\'Meetings\';',
        'sugar_html' =>
            array (
                'type' => 'submit',
                'value' => '{$APP.LBL_CREATE_MEETING_BUTTON_TITLE}',
                'htmlOptions' =>
                    array (
                        'title' => '{$APP.LBL_CREATE_MEETING_BUTTON_LABEL}',
                        'accesskey' => '{$APP.LBL_CREATE_MEETING_BUTTON_KEY}',
                        'class' => 'button',
                        'style' => 'display: inline-block',
                        'onclick' => 'this.form.action.value=\'EditView\'; this.form.return_action.value=\'DetailView\'; this.form.return_module.value=\'Meetings\'; this.form.return_id.value=\'{$fields.id.value}\'; this.form.module.value=\'Meetings\'; this.form.record.value=\'\';',
                        'name' => '{$APP.LBL_CREATE_MEETING_BUTTON_LABEL}',
                        'id' => 'create_meeting_button',
                    ),
                'template' => '{if $fields.status.value != "Held" && $bean->aclAccess("edit")}[CONTENT]{/if}',
            ),
    );
    
    // Add buttons the desired section of the view array
    $meeting_detailview->_viewdefs['templateMeta']['form']['buttons'][] = $create_call_button;
    $meeting_detailview->_viewdefs['templateMeta']['form']['buttons'][] = $create_meeting_button;
    
    //Save the layout
    $meeting_detailview->handleSave(false);
    echo "Meeting - Detail View is OK.<br />";







    $meetings_editview = ParserFactory::getParser('editview','Meetings');
    $script = '<script>{literal}$("#contacts_c").change(function() {
        
        //remove unselected options from scheduling bar
        $("#contacts_c").find(\'option:not(:selected)\').each(function( index, element ){
            SugarWidgetScheduleRow.deleteRow_c(element.value);
        });
        
        //add selected options to scheduling bar
        $("#contacts_c").find(\'option:selected\').each(function( index, element ){
            var contactExists = false;
            $.each( GLOBAL_REGISTRY.focus.users_arr, function( index, element){
                if (typeof(this.fields != "undefined")){
                    if (this.fields.id == element.value){
                        contactExists = true;
                    }
                }
            });
            if (contactExists == false){
                //delete any copies of the same entry
                SugarWidgetScheduleRow.deleteRow_c(element.value);
                SugarWidgetSchedulerAttendees.form_add_attendee_c(element);
            }
        });
        
        //finally, display all the above changes made
        GLOBAL_REGISTRY.scheduler_attendees_obj.display();  
    });{/literal}</script>';

    // Add javascript the desired section of the view array
    $meetings_editview->_viewdefs['templateMeta']['javascript'] = $meetings_editview->_viewdefs['templateMeta']['javascript'] . $script;

    $meetings_editview_panel = array
    (
        1 =>
            array (
                0 =>
                    array (
                        'name' => 'contacts_c',
                        'studio' => 'visible',
                        'label' => 'LBL_CONTACTS',
                    ),
                1 =>
                    array (
                    ),
            ),
    );

    // Add panel with a custom field
    $meetings_editview->_viewdefs['panels']['LBL_CONTACTS'] = $meetings_editview_panel;

    //Save the layout
    $meetings_editview->handleSave(false);
    echo "Meetings - Edit View is OK.<br />";


?>