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

class View
{
	/**
	 * @var array The areas of the page/action. E.g: ?music;area=main
	 */
	private $_areas = [];

	/**
	 * @var array It will hold the menu of the page. The name is irrelevant, doesn't make sense anyway.
	 */
	private $_pledge_areas = [];

	/**
	 * @var string The current action
	 */
	private $_area;

	function __construct()
	{
		// Load the language.
		Integration::language();

		// Load the template
		// Themes/default/Music
		// From 'Music' folder, load 'Main' template.
		loadTemplate('Pledges/Main');

		// Permission
		// isAllowedTo(['tasksmanager_can_view', 'tasksmanager_can_edit']);

		// Create the areas/actions array and load the default area.
		$this->actions();

		// Add the submenu using SMF functions.
		//$this->areas();

		// Hide Search from the submenu
		addInlineCss('.admin_search { display: none; }');
		
		// Artists CSS file
		loadCSSFile('Pledges/pledge.css', ['default_theme' => true, 'minimize' => false], 'smf_pledge');
		
		// Load the javascript file
        loadJavaScriptFile('pledge.js', ['defer' => true, 'async' => true, 'default_theme' => true], 'pledge_js');
	}

	/**
	 * View::actions()
	 * 
	 * Set the main areas/actions of the page
	 * @return void
	 */
	private function actions() : void
	{
		$this->_areas = [
			'pledge' => 'Pledges::main',
			'config' => 'Settings::config',
			'permissions' => 'Settings::permissions',
		];

		// Get the current action
		// Set artists as the default one if there's nothing in the URL or request.
		$this->_area = isset($_GET['area'], $this->_areas[$_GET['area']]) ? $_GET['area'] : 'pledge';
	}

	/**
	 * View::main()
	 * 
	 * Provides the basic information for the WHOLE action (aciton=music)
	 * It also loads the correct function based on the area and subsections
	 * @return void
	 */
	public function main()
	{
		global $context;
		
		// Add layers... with the copyright and other stuff
		$context['template_layers'][] = 'main';
		
		// Copyright
		$context['pledges']['copyright'] = $this->copyright();

		// Invoke the function
		call_helper(__NAMESPACE__ . '\\' . $this->_areas[$this->_area] . '#');
	}

	public static function page_setup($action, $sub_template = null, $title = null, $link = null, $icon = 'help')
	{
		global $txt, $context, $scripturl, $modSettings;

		// Page title
		$context['page_title'] = $modSettings['pledges_title'] . ' - ' . $txt['pledges_' . (!empty($title) ? $title : $action)];
		// Linktree
		$context['linktree'][] = [
			'url' => $scripturl . (!empty($link) ? $link : '?action=pledge;area=' . $action),
			'name' => $txt['pledges_' . (!empty($title) ? $title : $action)]
		];
		// Template
		if (!empty($sub_template))
			$context['sub_template'] = $sub_template;

	}
	
	/**
	 * View::copyright()
	 *
	 * @return string A link for copyright notice
	 */
	private function copyright()
	{
		return ' Powered by: <a href="https://custom.simplemachines.org/index.php?mod=4307" target="_blank" rel="noopener">Pledges</a> By <a href="https://www.databasedreams.co.uk/" target="_blank" rel="noopener">Database Dreams</a>';
	}
}