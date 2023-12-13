<?php

/**
 * @package     ExpressionEngine
 * @subpackage  Modules
 * @category    Jamf Entries Menu Manager
 * @author      Eric Swierczek
 * @copyright   Copyright 2023, Jamf
 * @license     MIT
 */

if (!defined('BASEPATH')) {
    exit('No direct script access allowed');
}

require_once PATH_THIRD . 'jamf_entries_menu_manager/addon.setup.php';

class Jamf_entries_menu_manager_ext
{
    const TITLE = 'title';
    const CHANNEL = 'channel';

    /**
     * @var string
     */
    public $description = JAMF_ENTRIES_MENU_MANAGER_DESC;

    /**
     * @var string
     */
    public $docs_url = JAMF_ENTRIES_MENU_MANAGER_DOCS;

    /**
     * @var string
     */
    public $name = JAMF_ENTRIES_MENU_MANAGER_NAME;

    /**
     * @var array
     */
    public $settings = [];

    /**
     * @var string
     */
    public $settings_exist = 'y';

    /**
     * @var integer
     */
    public $version = JAMF_ENTRIES_MENU_MANAGER_VERSION;

    /**
     * @var array
     */
    private $userData = [];

    /**
     * @param array|null $settings
     */
    public function __construct($settings = null)
    {
        $this->settings = $settings;
    }

    /**
     * @return string
     */
    public function cp_css_end()
    {
        $styles = '';

        // If another extension shares the same hook
        if (ee()->extensions->last_call !== false) {
            $styles .= ee()->extensions->last_call;
        }

        $styles = $this->getFile('css/menu-manager.css');

        return $styles;
    }

    /**
     * @return string
     */
    public function cp_js_end()
    {
        $scripts = [];

        // If another extension shares the same hook
        if (ee()->extensions->last_call !== false) {
            $scripts[] = ee()->extensions->last_call;
        }

        $treeData = $this->getTreeData();
        $length = 1;

        if (count($treeData[2]) > 0) {
            $length = 2;
        }

        if (count($treeData[3]) > 0) {
            $length = 3;
        }

        $data = 'var treeData = ' . json_encode($this->getTreeData()) . '; var colCount = ' . $length . ';';

        $scripts[] = '$(function () {' . $data . $this->getFile('js/menu-manager.js') . '});';

        return implode("\n", $scripts);
    }

    /**
     * @param array $settings
     * @return string
     */
    public function settings_form($settings)
    {
        $vars = [
            'save_url' => ee('CP/URL')->make('addons/settings/jamf_entries_menu_manager/save'),
            'alerts_name' => 'jamf-entries-menu-manager-save',
            'instructions' => lang('instructions'),
            'channels' => $this->getAllChannels(),
            'treeData' => $this->getTreeData(),
            'treeStrings' => [
                1 => $settings['tree1'] ?? '',
                2 => $settings['tree2'] ?? '',
                3 => $settings['tree3'] ?? '',
            ],
        ];

        ee()->cp->add_to_head('<style>' . $this->getFile('css/jquery-ui.min.css') . '</style>');
        ee()->cp->add_to_head('<style>' . $this->getFile('css/edit.css') . '</style>');

        ee()->cp->add_to_foot('<script>' . $this->getFile('js/jquery-ui.min.js') . '</script>');
        ee()->cp->add_to_foot('<script>' . $this->getFile('js/jquery.mjs.nestedSortable.js') . '</script>');
        ee()->cp->add_to_foot('<script>' . $this->getFile('js/edit.js') . '</script>');

        return ee('View')->make('jamf_entries_menu_manager:menu-manager')->render($vars);
    }

    /**
     * Restructure the serialized settings data into a tree-like array.
     * This does some minor data cleanup when a channel no longer exists but is still in the EMM settings.
     * Any new channels to the CMS that aren't in EMM settings will be appended to the first column of data.
     *
     * @return array
     */
    private function getTreeData(): array
    {
        /** @var \ExpressionEngine\Model\Addon\Extension $model */
        $model = ee('Model')->get('Extension')
            ->filter('enabled', 'y')
            ->filter('class', JAMF_ENTRIES_MENU_MANAGER_EXT)
            ->first();

        $allChannels = $this->getAllChannels();
        $foundChannels = [];
        $channelData = [
            1 => [],
            2 => [],
            3 => []
        ];

        // parse tree data into an array
        foreach([1, 2, 3] as $treeIndex) {
            $treeData = explode('&', $model->settings['tree'.$treeIndex] ?? '');

            foreach ($treeData as $d) {

                // data is empty the first time this is loaded
                if ($d === '') {
                    continue;
                }

                preg_match('/(channel|title|text-template)\[(\d+)\]=(.*)/', $d, $matches);

                $type = $matches[1];
                $id = $matches[2];
                $parentId = $matches[3];
                $textData = '';

                if ($type === 'text-template') {
                    continue;
                }

                $data = [
                    'id' => $id,
                    'li_id' => $type . '_' . $id,
                    'type' => $type,
                ];

                if ($type === self::CHANNEL) {
                    // if the channel has been deleted, but it's still stored in the serialized EMM data, skip it
                    // we'll also restructure any children of this to be root nodes by checking $allChannels[$parentId] below
                    if (!isset($allChannels[$id])) {
                        continue;
                    }

                    $data['channel'] = $allChannels[$id];

                    // keep track of all found channels, so we know if any are new/unmanaged in the current data
                    $foundChannels[] = $id;
                } else if ($type === self::TITLE) {
                    $data['text'] = $model->settings['title_' . $id] ?? '';
                }

                // set node as root if it's defined that way or if it's defined as a child, but the parent channel no longer exists
                if ($parentId !== 'null') {
                    // the way the data is serialized, we know the parent is the previous item
                    $parentIndex = count($channelData[$treeIndex])-1;

                    // if parent is a channel, but that channel doesn't exist anymore, consider this a root node
                    if ($channelData[$treeIndex][$parentIndex]['type'] == self::CHANNEL && !isset($allChannels[$parentId])) {
                        $data['children'] = [];
                        $channelData[$treeIndex][] = $data;
                    } else {
                        // otherwise this is a child channel - add it to the parent
                        $channelData[$treeIndex][$parentIndex]['children'][] = $data;
                    }
                } else {
                    $data['children'] = []; // only root nodes can have children
                    $channelData[$treeIndex][] = $data;
                }
            }
        }

        $missingChannels = array_diff(array_keys($allChannels), $foundChannels);

        // add any new/unmanaged channels to the bottom of this list
        foreach ($missingChannels as $missingChannelId) {
            $channelData[1][] = [
                'id' => $missingChannelId,
                'li_id' => 'channel_' . $missingChannelId,
                'type' => 'channel',
                'channel' => $allChannels[$missingChannelId],
                'children' => [],
            ];
        }

        return $channelData;
    }

    /**
     * @return array of channelId => channelTitle
     */
    private function getAllChannels(): array
    {
        // all channels (id => name) ordered by name (default order)
        $channels = [];
        foreach (ee('Model')->get('Channel')->order('channel_title')->all() as $channel) {
            $channels[$channel->channel_id] = $channel->channel_title;
        }

        return $channels;
    }

    /**
     * Get file contents to append to JS/CSS end
     *
     * @return string
     */
    private function getFile(string $file): string
    {
        return file_get_contents(PATH_THIRD .'jamf_entries_menu_manager/resources/' . $file);
    }

    /**
     * Save settings
     */
    public function save_settings()
    {
        if (empty($_POST)) {
            show_error(lang('unauthorized_access'));
        }

        /** @var \ExpressionEngine\Model\Addon\Extension $model */
        $model = ee('Model')->get('Extension')
            ->filter('enabled', 'y')
            ->filter('class', JAMF_ENTRIES_MENU_MANAGER_EXT)
            ->all();

        $reset = $_POST['submit'] === 'reset';
        unset($_POST['submit']);
        unset($_POST['csrf_token']);

        if ($reset) {
            $_POST = [];
        }

        $model->settings = $_POST;
        $model->save();

        ee('CP/Alert')->makeInline('jamf-entries-menu-manager-save')
            ->asSuccess()
            ->withTitle(lang('preferences_updated'))
            ->defer();

        ee()->functions->redirect(ee('CP/URL')->make('addons/settings/jamf_entries_menu_manager'));
    }

    /**
     * Install
     *
     * @return bool
     */
    public function activate_extension()
    {
        foreach(['cp_css_end', 'cp_js_end'] as $hook) {
            $data = array(
                'class'     => JAMF_ENTRIES_MENU_MANAGER_EXT,
                'method'    => $hook,
                'hook'      => $hook,
                'settings'  => '',
                'version'   => $this->version,
                'enabled'   => 'y',
                'priority'  => 5,
            );

            ee()->db->insert('extensions', $data);
        }


        return true;
    }

    /**
     * Uninstall
     *
     * @return  bool
     */
    public function disable_extension()
    {
        ee()->db
            ->where('class', JAMF_ENTRIES_MENU_MANAGER_EXT)
            ->delete('extensions');

        return true;
    }

    /**
     * Update
     *
     * @param string $current
     * @return bool true
     */
    public function update_extension($current = ''): bool
    {
        if (! $current || $current == $this->version) {
            return false;
        }

        return true;
    }
}
