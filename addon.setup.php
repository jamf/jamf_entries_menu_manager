<?php

// Copyright 2023, Jamf

if (! defined('ENTRIES_MENU_MANAGER_VERSION')) {
    define('ENTRIES_MENU_MANAGER_VERSION', '1.0.0');
    define('ENTRIES_MENU_MANAGER_NAME', 'Entries Menu Manager');
    define('ENTRIES_MENU_MANAGER_EXT', 'Entries_menu_manager_ext');
    define('ENTRIES_MENU_MANAGER_DESC', 'Manage the entries menu');
    define('ENTRIES_MENU_MANAGER_DOCS', 'https://github.com/jamf/entries-menu-manager');
}

return [
    'author'        => 'Jamf',
    'author_url'    => 'https://github.com/jamf',
    'docs_url'      => ENTRIES_MENU_MANAGER_DOCS,
    'name'          => ENTRIES_MENU_MANAGER_NAME,
    'description'   => ENTRIES_MENU_MANAGER_DESC,
    'version'       => ENTRIES_MENU_MANAGER_VERSION,
    'namespace'     => 'EntriesMenuManager',
    'settings_exist' => true,
];
