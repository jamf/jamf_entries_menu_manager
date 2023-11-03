<?php
if (!defined('BASEPATH')) {
    exit('No direct script access allowed');
}

$lang = [
    'entries_menu_manager' => 'Entries Menu Manager',
    'entries_menu_manager_module_name' => 'Entries Menu Manager',
    'entries_menu_manager_module_description' => 'Manage the entries menu',
    'module_home' => 'Entries Menu Manager Home',
    'instructions' => 'Drag and drop to reorder the channels in the Entries menu into 1-3 columns.<br>Channels can be either root-level or nested one level under another Channel or under a Title.<br>Click the "add" icon to add a Title input field as a parent to the channel to output text in the Entries menu. Titles must be root-level, must not be blank, and must have at least one child Channel.<br>If a user does not have access to a given channel, the nesting will automatically resolve itself in the Entries menu. <i>i.e. if they do not have access to a parent channel, then any child channels become root nodes. And if they do not have access to all child channels of a Title, then the Title is hidden too.</i>',
];
