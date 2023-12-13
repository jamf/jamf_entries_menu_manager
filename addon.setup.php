<?php

// Copyright 2023, Jamf

if (! defined('JAMF_ENTRIES_MENU_MANAGER_VERSION')) {
    define('JAMF_ENTRIES_MENU_MANAGER_VERSION', '1.0.0');
    define('JAMF_ENTRIES_MENU_MANAGER_NAME', 'Jamf Entries Menu Manager');
    define('JAMF_ENTRIES_MENU_MANAGER_EXT', 'Jamf_entries_menu_manager_ext');
    define('JAMF_ENTRIES_MENU_MANAGER_DESC', 'Manage the entries menu');
    define('JAMF_ENTRIES_MENU_MANAGER_DOCS', 'https://github.com/jamf/jamf_entries_menu_manager');
}

return [
    'author'        => 'Jamf',
    'author_url'    => 'https://github.com/jamf',
    'docs_url'      => JAMF_ENTRIES_MENU_MANAGER_DOCS,
    'name'          => JAMF_ENTRIES_MENU_MANAGER_NAME,
    'description'   => JAMF_ENTRIES_MENU_MANAGER_DESC,
    'version'       => JAMF_ENTRIES_MENU_MANAGER_VERSION,
    'namespace'     => 'JamfEntriesMenuManager',
    'settings_exist' => true,
];
