<?php

/**
 * @package Tasks Manager
 * @version 1.0
 * @author Michael Javes <dbdreams@aol.com>
 * @canvas template By Diego Andrés <diegoandres_cortes@outlook.com>
 * @copyright Copyright (c) 2023, SMF Tricks
 *@license https://www.mozilla.org/en-US/MPL/2.0/
 */

namespace Pledges;

if (!defined('SMF'))
	die('No direct access...');

class Settings
{
	function __construct()
	{
		global $sourcedir;

		// Load Admin template
		loadTemplate('Admin', 'admin');

		// Load other languages cuz this mod setup is really stupid haha
		loadLanguage('Admin');

		// Settings....
		require_once($sourcedir . '/ManageServer.php');

		// Can you manage settings?
		isAllowedTo('pledges_can_edit_admin');
	}

	/**
	 * Settings::permissions()
	 * 
	 * Display the permissions page
	 * @return void
	 */
	public function permissions()
	{
		global $txt;

		// Page setup
		View::page_setup('permissions', 'show_settings', null, null, 'permissions');

		// Can you manage permissions?
		isAllowedTo('manage_permissions');

		$config_vars = [
			['permissions', 'pledges_can_view_Pledges', 'subtext' => $txt['permissionhelp_pledges_can_view_Pledges']],
			['permissions', 'pledges_can_add_Pledge', 'subtext' => $txt['permissionhelp_pledges_can_add_Pledge']],
		];

		// Save
		$this->save($config_vars, 'permissions');
	}

	/**
	 * Settings::config()
	 * 
	 * Display the settings page
	 * @return void
	 */
	public function config()
	{
		global $txt;

		// Page setup
		View::page_setup('config', 'show_settings', null, null, 'settings');
		
		$config_vars = [
			['text', 'pledges_title', 'subtext' => $txt['pledges_title_desc']],
			['text', 'pledges_default_currency', 'subtext' => $txt['pledges_default_currency_desc']],
			['text', 'pledges_payme_link', 'subtext' => $txt['pledges_payme_link_desc']],
			['text', 'pledges_root_url', 'subtext' => $txt['pledges_root_url_desc']],
			['text', 'pledges_image_folder', 'subtext' => $txt['pledges_image_folder_desc']],
			['large_text', 'pledges_link_title', 'subtext' => $txt['pledges_link_title_desc']],
			['large_text', 'pledges_main_display_text', 'subtext' => $txt['pledges_main_display_text_desc']],
			['large_text', 'pledges_thankyou_text', 'subtext' => $txt['pledges_thankyou_text_desc']],
			'',
			['int', 'pledges_items_per_page', 'subtext' => $txt['pledges_items_per_page_desc']],
			['int', 'pledges_points_per', 'subtext' => $txt['pledges_points_per_desc']],
			['int', 'pledges_points_per_multiply', 'subtext' => $txt['pledges_points_per_multiply_desc']],
			['int', 'pledges_points_per_multiply_for', 'subtext' => $txt['pledges_points_per_multiply_for_desc']],
			'',
			['check', 'pledges_seperate_post_use_points', 'subtext' => $txt['pledges_seperate_post_use_points_desc']],
			['int', 'pledges_points_post_count', 'subtext' => $txt['pledges_points_post_count_desc']],
			'',
			['int', 'pledges_top_members_number', 'subtext' => $txt['pledges_top_members_number_desc']],
			['check', 'pledges_use_menu', 'subtext' => $txt['pledges_use_menu_desc']],
			['check', 'pledges_use_stshop', 'subtext' => $txt['pledges_use_stshop_desc']],
			['check', 'pledges_show_stats', 'subtext' => $txt['pledges_show_stats_desc']],
			['check', 'pledges_email_admin', 'subtext' => $txt['pledges_email_admin_desc']],
			['int', 'pledges_use_member', 'subtext' => $txt['pledges_use_member_desc']],
			['select', 'pledges_block_image', [
					'Donate with picture blue',
					'Donate with picture green',
					'Donate with picture orange',
					'Donate with picture dark blue',
					'Donate with picture brown',
					'Donate with picture pink',
					'Donate with picture purple',
					'Donate with picture red',
					'Donate light blue',
					'Donate green',
					'Donate orange',
					'Donate dark blue',
					'Donate brown',
					'Donate pink',
					'Donate purple',
					'Donate red'
				],
				'subtext' => $txt['pledges_block_image_desc']
			]
		];

		// Save
		$this->save($config_vars, 'config');
	}

	/**
	 * Settings::save
	 * 
	 * Save the settings
	 * @return void
	 */
	private function save($config_vars, $sa)
	{
		global $context, $scripturl;

		// Post url
		$context['post_url'] = $scripturl . '?action=pledge;area='. $sa. ';save';

		// Saving?
		if (isset($_GET['save'])) {
			checkSession();
			saveDBSettings($config_vars);
			redirectexit('action=pledge;area='. $sa. ';saved');
		}
		prepareDBSettingContext($config_vars);
	}
}