<?php

/**
 * @package Pledges
 * @version 0.1
 * @author Michael Javes <dbdreams@aol.com>
 * @canvas template By Diego Andrés <diegoandres_cortes@outlook.com>
 * @copyright Copyright (c) 2023, Database Dreams
 * @license https://www.mozilla.org/en-US/MPL/2.0/
 */

namespace Pledges;

if (!defined('SMF'))
	die('No direct access...');

class Integration
{
	/**
	 * @var array The mod permissions.
	 * I added a note on this in ::initialize()
	 */
	private $_permissions = [
		'pledges_can_view_Pledges',
		'pledges_can_add_Pledge',
		'pledges_can_edit_admin',
	];

	/**
	 * Integration::initialize()
	 * 
	 * Loads all the hooks and settings for this mod
	 * @return void
	 */
	public static function initialize() : void
	{
		
		// Autoloader
		add_integration_function('integrate_autoload', __CLASS__ . '::autoload', false);
		// The action (or actions) of the mod
		add_integration_function('integrate_actions', __CLASS__ . '::actions', false);
		// The menu button (or buttons)
		add_integration_function('integrate_menu_buttons', __CLASS__ . '::menu_buttons#', false);
		add_integration_function('integrate_pre_css_output', __CLASS__ . '::preCSS', false);
		add_integration_function('integrate_admin_areas', __CLASS__ . '::language', false);
		// Permission
		add_integration_function('integrate_load_permissions', __CLASS__ . '::load_permissions#', false);
		add_integration_function('integrate_load_illegal_guest_permissions', __CLASS__ . '::illegal_guest#', false);
		/** I didn't add permissions or example for that,
		 * figured you might want to work on that later on,
		 * after you manually sort out your info */
				 
		self::setDefaults();
	}
	
	public static function menu_buttons(&$buttons)
	{
		global $scripturl, $txt, $modSettings, $sourcedir;

		// Language
		loadLanguage('Pledges/Pledges');
		

		$total_waiting = self::PledgesToBeConfirmed();
		$total_waiting = ($total_waiting ? ' (' . $total_waiting . ')' : '');

		// Menu Button
		$buttons['pledges' ] = [
			'title' => (!empty($modSettings['pledges_title']) ? $modSettings['pledges_title'] : $txt['pledges_button']),
			'href' => $scripturl . '?action=pledge;area=pledge;sa=index',
			'icon' => 'pledges',
			'show' => (allowedTo('pledges_can_view_Pledges') && $modSettings['pledges_use_menu']),
			'sub_buttons' => [
				'pledgesinxex' => [
					'title' => $modSettings['pledges_title'] . $txt['pledges_index_button'],
					'href' => $scripturl . '?action=pledge;area=pledge;sa=index',
					'show' => allowedTo('pledges_can_view_Pledges'),
					],
				'pledgesexpenses' => [
					'title' => $txt['pledges_button_expence'],
					'href' => $scripturl . '?action=pledge;area=pledge;sa=index;ds=expenses',
					'show' => allowedTo('pledges_can_view_Pledges'),
					],
				'pledgesgoals' => [
					'title' => $txt['pledges_goals_title'],
					'href' => $scripturl . '?action=pledge;area=pledge;sa=goals',
					'show' => allowedTo('pledges_can_view_Pledges'),
					],
				'pledgesconfirm' => [
					'title' => $txt['pledges_confirm_pledges']. '&nbsp;'.$modSettings['pledges_title'].$total_waiting,
					'href' => $scripturl . '?action=pledge;area=pledge;sa=confirm',
					'show' => allowedTo('pledges_can_edit_admin'),
					],
				'pledgesspend' => [
					'title' => $txt['pledges_expense_menu'],
					'href' => $scripturl . '?action=pledge;area=pledge;sa=spend',
					'show' => allowedTo('pledges_can_edit_admin'),
					],
				'pledgeslists' => [
					'title' => $txt['pledges_lists_title'],
					'href' => $scripturl . '?action=pledge;area=pledge;sa=lists',
					'show' => allowedTo('pledges_can_edit_admin'),
					],
				'pledgesconfig' => [
					'title' => $txt['pledges_button_config'],
					'href' => $scripturl . '?action=pledge;area=config',
					'show' => allowedTo('pledges_can_edit_admin'),
					],
				'pledgesperms' => [
					'title' => $txt['pledges_button_permissions'],
					'href' => $scripturl . '?action=pledge;area=permissions',
					'show' => allowedTo('pledges_can_edit_admin'),
					],
				],
		];
	}
	
	public static function preCSS() : void
	{
		global $settings;

		// Add the icon using inline css
		addInlineCss('
			.main_icons.pledges::before {
				background-position: 0;
				background-image: url("' .$settings['default_images_url'] . '/icons/pledges.png");
				background-size: contain;
			}
		');
	}

	public static function setDefaults() : void
	{
		global $modSettings, $boardurl;

		$defaults = [
			'pledges_default_currency' => '$',
			'pledges_image_folder' => '/Themes/default/images/Pledges/',
			'pledges_items_per_page' => 25,
			'pledges_main_display_text' => 'Still thinking on this, it will be on main donations screen.[br]this is a bbcode test.',
			'pledges_payme_link' => '',
			'pledges_points_per' => 1,
			'pledges_points_per_multiply' => 1,
			'pledges_points_per_multiply_for' => 10,
			'pledges_root_url' => $boardurl,
			'pledges_thankyou_text' => 'Thank you for your donation',
			'pledges_title' => 'Donations',
			'pledges_use_menu' => 1,
			'pledges_use_stshop' => 0,
			'pledges_link_title' => 'Help Us keep improving this site and add more prizes to the prize system',
			'pledges_top_members_number' => 5,
			'pledges_seperate_post_use_points' => 1,
			'pledges_points_post_count' => 0,
			'pledges_transaction_setup' => 0,
			'pledges_email_admin' => 1,
			'pledges_block_image' => 0,
			'pledges_use_member' => 0,
			'pledges_show_stats' => 1,
			
		];
		$modSettings = array_merge($defaults, $modSettings);
	}
	/**
	 * Integration::autoload()
	 * 
	 *'pledges_transaction_type' => 4,
	 *'pledges_account' => 2,
	 * Add the tasks manager to the autoloader
	 * @param array $classMap The autoloader map
	 * @return void
	 */
	public static function autoload(&$classMap) : void
	{
		$classMap[__NAMESPACE__ . '\\'] = __NAMESPACE__ . '/';
	}

	/**
	 * Integration::actions()
	 * 
	 * Adds the music action into the actions array.
	 * 
	 * @param array $actions The forum actions
	 * @return void
	 */
	public static function actions(&$actions) : void
	{
		// The music action.
		// $actions['name_of_action'] = [file_name with path, function_name];
		$actions['pledge'] = [__NAMESPACE__ . '/View.php', __NAMESPACE__  . '\View::main#'];
	}

	/**
	 * Integration::language()
	 * 
	 * It will only load the language file.
	 * It's a function so that other hooks can call it in case it's needed
	 * somewhere else without actually doing anything else.
	 */
	public static function language() : void
	{
		// Language file
		loadLanguage('Pledges/Pledges');
	}
	
	/**
	 * Integration::load_permissions()
	 * 
	 * Load the permissions
	 * @param array $permissionGroups
	 * @param array $permissionList
	 */
	public function load_permissions(&$permissionGroups, &$permissionList)
	{
		$permissionGroups['membergroup'][] = 'pledges';
		foreach ($this->_permissions as $permission)
			$permissionList['membergroup'][$permission] = [false, 'pledges'];
	}

function PledgesToBeConfirmed()
{
	global $smcFunc;
	
	
		$totalquery = $smcFunc['db_query']('', '
		SELECT p.pledge_id
		FROM {db_prefix}pledges AS p
		WHERE p.pledge_confirmed = {int:this_confirmed}',
				[
				'this_confirmed' => 0,
				]
			);
		
		$total = $smcFunc['db_num_rows']($totalquery);
		$smcFunc['db_free_result']($totalquery);
	

	
	return $total;
}

	/**
	 * Integration::illegal_guest()
	 * 
	 * Remove the permissions from guests
	 */
	public function illegal_guest()
	{
		global $context;

		// Guest should not be able to edit or add anything
		$context['non_guest_permissions'][] = 'pledge_can_add_Pledges';
	}
}