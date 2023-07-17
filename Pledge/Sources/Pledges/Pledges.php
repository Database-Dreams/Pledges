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

class Pledges
{
	/**
	 * @var array The subactions of this individual action/area.
	 */
	private $_subactions;

	/**
	 * Projects::main()
	 */
	 
	public function main()
	{
		global $context, $txt, $scripturl, $modSettings;

		// The page setup.
		// Each page needs certain details to be set, like the title, the template function to load (subtemplate), linktree, etc.

		// And in this use case with the menu, it also requires to setup that so the
		// smf function can automatically grab and fill in the already generated section title and description.
		// In the tasks mode I used a function and passed arguments to it, but in this case
		// I'll just put it again and again on each area to provide visual context.

		// Page title
		$context['page_title'] = $modSettings['pledges_title'];

		// Subactions for this specific area
		$this->_subactions = [
			'index' => 'ViewPledges',
			'savep' => 'SavePledge',
			'like' => 'LikePledge',
			'delete' => 'DeletePledge',
			'confirm' => 'ConfirmPledge',
			'confirmedit' => 'EditConfirmPledge',
			'confirmsave' => 'SaveConfirmPledge',
			'lists' => 'ShowLists',
			'accadd' => 'AddAccount',
			'accedit' => 'EditAccount',
			'doaccedit' => 'DoEditAccount',
			'accdel' => 'DeleteAccount',
			'typeadd' => 'AddTransactionType',
			'typedit' => 'EditTransactionType',
			'dotypedit' => 'DoEditTransactionType',
			'typedel' => 'DeleteTransactionType',
			'spend' => 'ShowAddExpenses',
			'addspend' => 'AddExpenses',
			'Seditspend' => 'ShowEditExpenses',
			'editspend' => 'DoEditExpenses',
			'delspend' => 'DeleteExpenses',
			'goals' => 'ShowGoals',
			'addgoals' => 'AddGoal',
			'seditgoal' => 'ShowEditGoal',
			'editgoal' => 'EditGoal',
			'delgoal' => 'DeleteGoal',
			];

		// Get the current action
		// Default is 'index' which loads the list method/function
		call_helper(__CLASS__ . '::' . $this->_subactions[isset($_GET['sa'], $this->_subactions[$_GET['sa']]) ? $_GET['sa'] : 'index'] . '#');
	}

	public function ViewPledges()
	{

		global $context, $scripturl, $txt, $smcFunc, $modSettings;

		// Is the user allowed to Search the Artists?
		
		$show_list = '';
		$pledges_total = 0;
		
		isAllowedTo('pledges_can_view_Pledges');
		
		//Make sure there is a pledge paypal link added
		if (!$modSettings['pledges_payme_link'])
			fatal_error($txt['pledges_no_paypal'],false);
		
		if (!empty($_REQUEST['start']))
			$context['start'] = (int) $_REQUEST['start'];	
		else
			$context['start'] = 0;
		
		$context['page_title'] = $modSettings['pledges_title'];

		// Linktree

		$context['linktree'][] = [

			'url' => $scripturl . '?action=pledge;area=pledge;sa=index',
			'name' => $modSettings['pledges_title'],

		];
		
		$show_list = (isset($_REQUEST['ds']) ? $_REQUEST['ds'] : 'pledges');
		
		if ($show_list == 'expenses'){
			$context['pledges_display']= 'expenses';
		
		//Create Type Filters
		
		
		$totalquery = $smcFunc['db_query']('', '
		SELECT p.transaction_id
		FROM {db_prefix}pledge_transactions  AS p
		WHERE p.debit_amount > 0');
		
		$context['pledges_total'] = $smcFunc['db_num_rows']($totalquery);
		$smcFunc['db_free_result']($totalquery);
		
		$dbquery = $smcFunc['db_query']('', '
		SELECT p.transaction_id, p.transaction_date, p.operation, p.debit_amount, p.member_id,
		a.account_name, t.type_name
		FROM {db_prefix}pledge_transactions AS p
		INNER JOIN {db_prefix}pledge_accounts AS a ON (p.transaction_acc_id = a.account_id)
		INNER JOIN {db_prefix}pledge_transaction_types AS t ON (p.transaction_type_id = t.type_id)
		WHERE p.debit_amount > {int:this_debit}
		ORDER BY p.transaction_date DESC
		LIMIT {int:this_start},{int:this_items_per_page}',
				[
				'this_debit' => 0,
				'this_start' => $context['start'],
				'this_items_per_page' => $modSettings['pledges_items_per_page'],
				]
		);
		
		$context['pledges_data'] = array();

		while($row = $smcFunc['db_fetch_assoc']($dbquery))
			$context['pledges_data'][] = $row;
		
		
		}else{
			
		$context['pledges_display']= 'pledges';
		
		$totalquery = $smcFunc['db_query']('', '
		SELECT p.pledge_id
		FROM {db_prefix}pledges AS p');
		
		$context['pledges_total'] = $smcFunc['db_num_rows']($totalquery);
		$smcFunc['db_free_result']($totalquery);
			
		$dbquery = $smcFunc['db_query']('', '
		SELECT p.pledge_id, p.pledge_date, p.pledge_amount, p.member_id, p.pledger_comment,
		p.can_publish, p.pledge_confirmed, p.points_applied, p.pledges_likes, p.pledge_comments,
		mem.id_member, mem.real_name, mg.online_color, mg.id_group
		FROM {db_prefix}pledges AS p
		LEFT JOIN {db_prefix}members AS mem ON (mem.id_member = p.member_id)
		LEFT JOIN {db_prefix}membergroups AS mg ON (mg.id_group = IF(mem.id_group = 0, mem.id_post_group, mem.id_group)) 
		WHERE p.pledge_confirmed = {int:this_confirmed}
		ORDER BY p.pledge_date DESC
		LIMIT {int:this_start},{int:this_items_per_page}',
				[
				'this_confirmed' => 1,
				'this_start' => $context['start'],
				'this_items_per_page' => $modSettings['pledges_items_per_page'],
				]
		);
		
		$context['pledges_data'] = array();

		while($row = $smcFunc['db_fetch_assoc']($dbquery))
			$context['pledges_data'][] = $row;
		
		}
		
		$smcFunc['db_free_result']($dbquery);
		
		//Do Stats Section If Enabled
		if ($modSettings['pledges_show_stats'])
		{
		//Top 5 Members
		$topquery = $smcFunc['db_query']('', '
		SELECT p.member_id, SUM(p.pledge_amount) AS total_pledged, COUNT(p.member_id) AS times_pledged,
		mem.real_name, mg.online_color
		FROM {db_prefix}pledges AS p
		LEFT JOIN {db_prefix}members AS mem ON (mem.id_member = p.member_id)
		LEFT JOIN {db_prefix}membergroups AS mg ON (mg.id_group = IF(mem.id_group = 0, mem.id_post_group, mem.id_group))
		WHERE p.pledge_confirmed = {int:this_confirmed} AND p.can_publish = {int:this_publish}
		GROUP BY p.member_id, mem.real_name, mg.online_color
		ORDER BY SUM(p.pledge_amount) DESC
		LIMIT 5',
				[
				'this_confirmed' => 1,
				'this_publish' => 1
				]
		);
		
		$context['pledges_top_members'] = array();

		while($toprow = $smcFunc['db_fetch_assoc']($topquery))
			$context['pledges_top_members'][] = $toprow;
		
		$smcFunc['db_free_result']($topquery);
		
		/*General Stats
		Total Pledges
		Total Pledged
		Members Pledged
		*/
		$statsquery = $smcFunc['db_query']('', '
		SELECT SUM(p.pledge_amount) AS total_pledged, COUNT(DISTINCT p.member_id) AS members_pledged, COUNT(p.pledge_id) AS total_pledges
		FROM {db_prefix}pledges AS p
		WHERE p.pledge_confirmed = {int:this_confirmed}
		LIMIT 5',
				[
				'this_confirmed' => 1,
				]
		);
		
		$context['pledges_stats'] = array();
		$context['pledges_stats'] = $smcFunc['db_fetch_assoc']($statsquery);
		$smcFunc['db_free_result']($statsquery);
		}
		
		$context['sub_template'] = 'pledges_view';

	}

	public function SavePledge()
	{

		global $smcFunc, $user_info, $scripturl, $sourcedir, $txt,$modSettings;

		require_once($sourcedir . '/Pledges/Pledges_module.php');

		isAllowedTo('pledges_can_add_Pledge');
		
		if (!empty($_REQUEST['amount']))
			$pledge =  (float) $_REQUEST['amount'];
		else
			$pledge = 0;

		if (empty($pledge))
			fatal_error($txt['pledges_currency_format'], false);
		
		
		$pub = isset($_REQUEST['publish']) ? 1 : 0;
		$comment = $smcFunc['htmlspecialchars']($_REQUEST['comment'],ENT_QUOTES);
		$pay_name = $smcFunc['htmlspecialchars']($_REQUEST['payname'],ENT_QUOTES);
		$pay_date = time();
		
	if ($user_info['is_guest'])
		$mem_id = 0;
	else
	$mem_id = $user_info['id'];
	
		$smcFunc['db_query']('', "INSERT INTO {db_prefix}pledges 
					(member_id, pledge_amount, pledger_comment, can_publish, pledge_paypal_name, pledge_date)
					VALUES('$mem_id', '$pledge', '$comment', '$pub', '$pay_name', '$pay_date')");
					
		//Edit link below for your Forum
	
	$body = $txt['pledges_new_pledge_added'];
    $body = str_replace("%url", $scripturl . '?action=pledge;area=pledge;sa=confirm',$body);
	
	if ($modSettings['pledges_image_folder'])
	PledgesEmailAdmins($txt['pledges_new_pledge_email_title'],$body);
	
	redirectexit('action=pledge;area=pledge;sa=index;tp=saved;ds=pledges');

	}

	public function LikePledge()
	{

		global $smcFunc, $scripturl, $user_info, $sourcedir, $txt;

		isAllowedTo('pledges_can_add_Pledge');
		
		if (!empty($_REQUEST['pl']))
			$pledge =  (int) $_REQUEST['pl'];
		else
			$pledge = 0;

		if (empty($pledge))
			fatal_error($txt['pledges_no_pledge'], false);
		
		$like_date = time();
		$type = 'pledge';
		
	if ($user_info['is_guest'])
		fatal_error($txt['pledges_guest_cant_like'], false);
	else
	$mem_id = $user_info['id'];
	
		$smcFunc['db_query']('', "INSERT INTO {db_prefix}user_likes 
					(id_member, content_id, like_time, content_type)
					VALUES('$mem_id', '$pledge', '$like_date', '$type')");
					
		//Now Increment Pledge Likes
		$smcFunc['db_query']('', "UPDATE {db_prefix}pledges
					SET pledges_likes = pledges_likes + 1
					WHERE pledge_id = $pledge LIMIT 1");
					
		 //Redirect to the category listing
	redirectexit('action=pledge;area=pledge;sa=index;ds=pledges');

	}

	public function ConfirmPledge()
	{

		global $context, $scripturl, $txt, $smcFunc, $modSettings, $sourcedir;
		
		isAllowedTo('pledges_can_edit_admin');
		
		require_once($sourcedir . '/Pledges/Pledges_module.php');
	
		if (!$modSettings['pledges_transaction_setup'])
			DoTrancastionsSettings();
		
		if (!empty($_REQUEST['start']))
			$context['start'] = (int) $_REQUEST['start'];	
		else
			$context['start'] = 0;
		
		$context['page_title'] = $txt['pledges_confirm_pledges']. '&nbsp;'.$modSettings['pledges_title'];

		// Linktree
		$context['linktree'][] = [

			'url' => $scripturl . '?action=pledge;area=pledge;sa=confirm',
			'name' => $txt['pledges_confirm_pledges']. '&nbsp;'.$modSettings['pledges_title'],

		];
		
		$totalquery = $smcFunc['db_query']('', '
		SELECT p.pledge_id
		FROM {db_prefix}pledges AS p
		WHERE p.pledge_confirmed = {int:this_confirmed}',
				[
				'this_confirmed' => 0,
				]
			);
		
		$context['pledges_total'] = $smcFunc['db_num_rows']($totalquery);
		$smcFunc['db_free_result']($totalquery);
			
		$dbquery = $smcFunc['db_query']('', '
		SELECT p.pledge_id, p.pledge_date, p.pledge_amount, p.member_id, p.pledger_comment, 
		p.pledge_comments, p.pledge_paypal_name, mem.id_member, mem.real_name, mg.online_color, mg.id_group
		FROM {db_prefix}pledges AS p
		LEFT JOIN {db_prefix}members AS mem ON (mem.id_member = p.member_id)
		LEFT JOIN {db_prefix}membergroups AS mg ON (mg.id_group = IF(mem.id_group = 0, mem.id_post_group, mem.id_group)) 
		WHERE p.pledge_confirmed = {int:this_confirmed}
		ORDER BY p.pledge_date DESC
		LIMIT {int:this_start},{int:this_items_per_page}',
				[
				'this_confirmed' => 0,
				'this_start' => $context['start'],
				'this_items_per_page' => $modSettings['pledges_items_per_page'],
				]
		);
		
		$context['pledges_data'] = array();

		while($row = $smcFunc['db_fetch_assoc']($dbquery))
			$context['pledges_data'][] = $row;	
		
		$smcFunc['db_free_result']($dbquery);
		
		$context['sub_template'] = 'pledges_confirm';

	}
	
	public function EditConfirmPledge()
	{

		global $context, $scripturl, $txt, $smcFunc, $modSettings;
		
		isAllowedTo('pledges_can_edit_admin');
		
		if (!empty($_REQUEST['pl']))
			$pledge =  (int) $_REQUEST['pl'];
		else
			$pledge = 0;

		if (empty($pledge))
			fatal_error($txt['pledges_no_pledge'], false);
		
		$context['page_title'] = $txt['pledges_confirm_pledges']. '&nbsp;'.$modSettings['pledges_title'];

		// Linktree
		$context['linktree'][] = [

			'url' => $scripturl . '?action=pledge;area=pledge;sa=confirm',
			'name' => $txt['pledges_confirm_pledges']. '&nbsp;'.$modSettings['pledges_title'],

		];
		
		$dbquery = $smcFunc['db_query']('', '
		SELECT p.pledge_id, p.pledge_amount
		FROM {db_prefix}pledges AS p
		WHERE p.pledge_id = {int:this_pledge}',
				[
				'this_pledge' => $pledge,
				]
			);

		$context['pledge_edit_data'] = $smcFunc['db_fetch_assoc']($dbquery);
		
		$smcFunc['db_free_result']($dbquery);
		
	$context['sub_template'] = 'do_edit_confirm';
		
	}
	
	public function SaveConfirmPledge()
	{

		global $smcFunc, $modSettings, $txt, $sourcedir;

		isAllowedTo('pledges_can_edit_admin');
		
		$shop_points = '';
		$pledge_calc_amount = 0;
		$memberposts = 0;
		$points = 0;
		$goal = Array();
		
		if (!empty($_REQUEST['amount']))
			$pledge_amount =  (float) $_REQUEST['amount'];
		else
			$pledge_amount = 0;

		if (empty($pledge_amount))
			fatal_error($txt['pledges_currency_format'], false);
		
		if (!empty($_REQUEST['pl']))
			$pledge =  (int) $_REQUEST['pl'];
		else
			$pledge = 0;

		if (empty($pledge))
			fatal_error($txt['pledges_no_pledge'], false);
		
		//Get The Pledge Details
		$dbquery = $smcFunc['db_query']('', '
		SELECT p.pledge_id, p.member_id
		FROM {db_prefix}pledges AS p
		WHERE p.pledge_id = {int:this_pledge}',
				[
				'this_pledge' => $pledge,
				]
			);
		
		//Make sure there is a record
		if ($smcFunc['db_affected_rows']() == 0)
			fatal_error($txt['pledges_no_pledge']);
		
		$row = $smcFunc['db_fetch_assoc']($dbquery);
		$smcFunc['db_free_result']($dbquery);
		
		/*
		Add The posts And Shop Points If Installed
		Devide The Amount By pledges_points_per_multiply_for
		 */
		//Make sure of whole number
		$pledge_calc_amount = (int) $pledge_amount;
		if ($pledge_calc_amount >= $modSettings['pledges_points_per_multiply_for'])
			//if default amount is £10 and payment is £10 or £11 this will be 1, £20 will be 2
			//Rounds Down
			$pledge_for = (int) ($pledge_calc_amount / $modSettings['pledges_points_per_multiply_for']);
		else
			$pledge_for = 1;
		
		$points = (int) ($modSettings['pledges_points_per_multiply'] * $pledge_for);
		$points = (int) ($modSettings['pledges_points_per'] * $points);
		
		//Now Increment Pledge Likes
		$smcFunc['db_query']('', "UPDATE {db_prefix}pledges
					SET pledge_amount = '$pledge_amount', pledge_confirmed = '1', points_applied = '$points'
					WHERE pledge_id = $pledge LIMIT 1");
				
		// Update the SMF Shop Points
		if ($modSettings['pledges_use_stshop'])
			$shop_points = ',shopMoney = shopMoney + ' . $points;
		
		//Check what to do with post count
		if ($modSettings['pledges_seperate_post_use_points'])
			//Use The Calculator
			$memberposts = $points;
		else
			$memberposts = $modSettings['pledges_points_post_count'];
			
		
			$smcFunc['db_query']('', "UPDATE {db_prefix}members
				SET posts = posts + " . $memberposts . $shop_points ."
				WHERE id_member = " . $row['member_id'] . "
				LIMIT 1");
				
	//Now Update the Transactions		
	$smcFunc['db_insert']('',
			'{db_prefix}pledge_transactions',
				[
					'transaction_date' => 'int',
					'transaction_acc_id' => 'int',
					'transaction_type_id' => 'int',
					'Operation' => 'string',
					'credit_amount' => 'float',
					'member_id' => 'int'
				],
				[
					(int) time(),
					(int) $modSettings['pledges_account'],
					(int) $modSettings['pledges_transaction_type'],
					(string) $txt['pledges_trans_operation'],
					(float) $pledge_amount,
					(int) $row['member_id']
				],
				[]
			);
					
	require_once($sourcedir . '/Pledges/Pledges_module.php');
	PledgesConfirmationPM($row['member_id']);
	
	//Now Check For A Goal For This year.
	$checkquery = $smcFunc['db_query']('', '
		SELECT g.goal_id, g.goal_type_id
		FROM {db_prefix}pledge_goals AS g
		WHERE g.goal_year = {int:this_year}',
				[
				'this_year' => DATE("Y"),
				]
			);
		//Add The Amount If There is a goal for this year
		if ($smcFunc['db_affected_rows']() != 0){
				$goal = $smcFunc['db_fetch_assoc']($checkquery);
				//Sort The Type If Yearly
			switch ($goal['goal_type_id']){
				case 1: //Yearly
					$unit = 1;
					break;
				case 12: //Monthly
					$unit = DATE("n");
					break;
				case 4: //Quartly
					$unit = floor(DATE("n") / 3) + 1;
					break;
				case 2: //6 monthly
					$unit = ceil(DATE("n")/6);
					break;
			}
					
				UpdatePledgeByUnit($unit, $goal['goal_id'], $pledge_amount);
			
		}
		$smcFunc['db_free_result']($checkquery);
		
		 //Redirect to the category listing
	redirectexit('action=pledge;area=pledge;sa=index;ds=pledges');

	}
	
	public function DeletePledge()
	{

		global $smcFunc, $context, $scripturl, $txt , $user_info;

		// Is the user allowed to Search the Artists?

		isAllowedTo('pledges_can_edit_admin');
		
		if (!empty($_REQUEST['pl']))
			$pledge =  (int) $_REQUEST['pl'];
		else
			$pledge = 0;

		if (empty($pledge))
			fatal_error($txt['pledges_no_pledge'], false);
	
		$smcFunc['db_query']('', "DELETE FROM {db_prefix}pledges WHERE pledge_id = $pledge LIMIT 1");

		// Redirect to the category listing
	redirectexit('action=pledge;area=pledge;sa=index;ds=pledges');

	}

	public function ShowLists()
	{
		global $context, $scripturl, $txt, $smcFunc, $modSettings, $sourcedir;
		
		isAllowedTo('pledges_can_edit_admin');
		
		//Make Sure The Lists Have been created and settings updated!.
		//This has also been added to Confirm Template!.
		require_once($sourcedir . '/Pledges/Pledges_module.php');
	
		if (!$modSettings['pledges_transaction_setup'])
			DoTrancastionsSettings();
		
		$context['page_title'] = $modSettings['pledges_title']. '&nbsp;'.$txt['pledges_lists_title'];

		// Linktree
		$context['linktree'][] = [

			'url' => $scripturl . '?action=pledge;area=pledge;sa=lists',
			'name' => $modSettings['pledges_title']. '&nbsp;'.$txt['pledges_lists_title'],

		];
		
		//Accounts List
		$accquery = $smcFunc['db_query']('', '
		SELECT a.account_id, a.account_name
		FROM {db_prefix}pledge_accounts AS a
		ORDER BY a.account_name ASC');
		
		$context['pledges_accounts_data'] = array();

		while($row = $smcFunc['db_fetch_assoc']($accquery))
			$context['pledges_accounts_data'][] = $row;	
		
		$smcFunc['db_free_result']($accquery);
		
		//Transaction Types
		$typesquery = $smcFunc['db_query']('', '
		SELECT t.type_id, t.type_name
		FROM {db_prefix}pledge_transaction_types AS t
		ORDER BY t.type_name DESC');
		
		$context['pledges_types_data'] = array();

		while($row = $smcFunc['db_fetch_assoc']($typesquery))
			$context['pledges_types_data'][] = $row;	
		
		$smcFunc['db_free_result']($typesquery);
		
		$context['sub_template'] = 'lists';
	}
	
	public function AddAccount()
	{
		global $txt, $smcFunc;
		
		isAllowedTo('pledges_can_edit_admin');
		
		if (empty($_REQUEST['account']))
			fatal_error($txt['pledges_no_account'], false);
		
		$acc_name = $smcFunc['htmlspecialchars']($_REQUEST['account'],ENT_QUOTES);
		
				//Accounts Table
		$smcFunc['db_query']('', "INSERT INTO {db_prefix}pledge_accounts 
					(account_name)
					VALUES('$acc_name')");
					
	redirectexit('action=pledge;area=pledge;sa=lists');
		
	}
	
	public function EditAccount()
	{
		global $txt, $smcFunc, $context;
		
		isAllowedTo('pledges_can_edit_admin');
		
		if (!empty($_REQUEST['acc']))
			$account =  (int) $_REQUEST['acc'];
		else
			$account = 0;

		if (empty($account))
			fatal_error($txt['pledges_no_account'], false);
		
		$accquery = $smcFunc['db_query']('', '
		SELECT a.account_id, a.account_name
		FROM {db_prefix}pledge_accounts AS a
		WHERE a.account_id = {int:this_account}',
			[
			'this_account' => $account,
			]
		);
		
		$context['edit_account_data'] = array();
		$context['edit_account_data'] = $smcFunc['db_fetch_assoc']($accquery);
		$smcFunc['db_free_result']($accquery);
		
		$context['sub_template'] = 'edit_account_list';
		
	}
	
	public function DoEditAccount()
	{

		global $txt, $smcFunc;
		
		isAllowedTo('pledges_can_edit_admin');
		
		if (!empty($_REQUEST['acc']))
			$account =  (int) $_REQUEST['acc'];
		else
			$account = 0;

		if (empty($account))
			fatal_error($txt['pledges_no_account'], false);
		
		$account_name = $smcFunc['htmlspecialchars']($_REQUEST['account_name'],ENT_QUOTES);
		
		$smcFunc['db_query']('', "UPDATE {db_prefix}pledge_accounts
				SET account_name = '$account_name'
				WHERE account_id = $account 
				LIMIT 1");
		
	redirectexit('action=pledge;area=pledge;sa=lists');
		
	}
	
	public function DeleteAccount()
	{

		global $smcFunc, $txt;

		// Is the user allowed to Search the Artists?

		isAllowedTo('pledges_can_edit_admin');
		
		if (!empty($_REQUEST['acc']))
			$account =  (int) $_REQUEST['acc'];
		else
			$account = 0;

		if (empty($account))
			fatal_error($txt['pledges_no_account'], false);
	
		$smcFunc['db_query']('', "DELETE FROM {db_prefix}pledge_accounts WHERE account_id = $account LIMIT 1");

		// Redirect to the category listing
	redirectexit('action=pledge;area=pledge;sa=lists');

	}
	
	public function AddTransactionType()
	{
		global $txt, $smcFunc;
		
		isAllowedTo('pledges_can_edit_admin');
		
		if (empty($_REQUEST['type']))
			fatal_error($txt['pledges_no_trans_type'], false);
		
		$type_name = $smcFunc['htmlspecialchars']($_REQUEST['type'],ENT_QUOTES);
		
				//Accounts Table
		$smcFunc['db_query']('', "INSERT INTO {db_prefix}pledge_transaction_types 
					(type_name)
					VALUES('$type_name')");
					
	redirectexit('action=pledge;area=pledge;sa=lists');
		
	}
	
	public function EditTransactionType()
	{
		global $txt, $smcFunc, $context;
		
		isAllowedTo('pledges_can_edit_admin');
		
		if (!empty($_REQUEST['typ']))
			$type =  (int) $_REQUEST['typ'];
		else
			$type = 0;

		if (empty($type))
			fatal_error($txt['pledges_no_account'], false);
		
		$tranquery = $smcFunc['db_query']('', '
		SELECT t.type_id, t.type_name
		FROM {db_prefix}pledge_transaction_types AS t
		WHERE t.type_id = {int:this_type}',
			[
			'this_type' => $type,
			]
		);
		
		$context['edit_transaction_data'] = array();
		$context['edit_transaction_data'] = $smcFunc['db_fetch_assoc']($tranquery);
		$smcFunc['db_free_result']($tranquery);
		
		$context['sub_template'] = 'edit_transaction_list';
		
	}
	
	public function DoEditTransactionType()
	{
		global $txt, $smcFunc;
		
		isAllowedTo('pledges_can_edit_admin');
		
		if (!empty($_REQUEST['typ']))
			$type =  (int) $_REQUEST['typ'];
		else
			$type = 0;

		if (empty($type))
			fatal_error($txt['pledges_no_trans_type'], false);
		
		$type_name = $smcFunc['htmlspecialchars']($_REQUEST['type_name'],ENT_QUOTES);
		
		$smcFunc['db_query']('', "UPDATE {db_prefix}pledge_transaction_types
				SET type_name = '$type_name'
				WHERE type_id = $type 
				LIMIT 1");
		
		
	redirectexit('action=pledge;area=pledge;sa=lists');
		
	}
	
	public function DeleteTransactionType()
	{

		global $smcFunc, $txt;

		// Is the user allowed to Search the Artists?

		isAllowedTo('pledges_can_edit_admin');
		
		if (!empty($_REQUEST['typ']))
			$type =  (int) $_REQUEST['typ'];
		else
			$type = 0;

		if (empty($type))
			fatal_error($txt['pledges_no_type'], false);
	
		$smcFunc['db_query']('', "DELETE FROM {db_prefix}pledge_transaction_types WHERE type_id = $type LIMIT 1");

		// Redirect to the category listing
	redirectexit('action=pledge;area=pledge;sa=lists');

	}
	
	public function ShowAddExpenses()
	{
		global $context, $scripturl, $txt, $smcFunc, $modSettings;
	
		isAllowedTo('pledges_can_edit_admin');
		
		$context['page_title'] = $modSettings['pledges_title']. '&nbsp;'.$txt['pledges_expense_title'];

		// Linktree
		$context['linktree'][] = [
			'url' => $scripturl . '?action=pledge;area=pledge;sa=lists',
			'name' => $modSettings['pledges_title']. '&nbsp;'.$txt['pledges_expense_title'],
		];
		
		//Now just load the template
		$context['sub_template'] = 'add_expenses';
	}
	
	public function AddExpenses()
	{

		global $smcFunc, $user_info, $txt;

		$account = 0;
		$debit_amount = 0;
		$acc_name = '';
		$add_account = 0;
		
		
		isAllowedTo('pledges_can_edit_admin');
		
		if (!empty($_REQUEST['debit']))
			$debit_amount =  (float) $_REQUEST['debit'];
		else
			$debit_amount = 0;

		if (empty($debit_amount))
			fatal_error($txt['pledges_currency_format'], false);
		
		$operation = $smcFunc['htmlspecialchars']($_REQUEST['comment'],ENT_QUOTES);
		
		//Are we using selected account or adding a new type
		if ($_REQUEST['account'] != 'New'){
			$account = (int) $_REQUEST['account'];
			$add_account = 0;
		}elseif (!empty($_REQUEST['new_account'])){		
			//if we got here then add then tell code after the Transactions to add the account
			$add_account = 1;
		}else{
			fatal_error($txt['pledges_no_new_account'], false);
		}

		//Are we using selected type or adding a new type
		if ($_REQUEST['type'] != 'New'){
			$trans_type = (int) $_REQUEST['type'];
		}elseif (!empty($_REQUEST['new_type'])){
		//Clean the data
		$type_name = $smcFunc['htmlspecialchars']($_REQUEST['new_type'],ENT_QUOTES);
		$smcFunc['db_query']('', "INSERT INTO {db_prefix}pledge_transaction_types 
					(type_name)
					VALUES('$type_name')");
					
		// Get the Type ID
		$trans_type = $smcFunc['db_insert_id']('{db_prefix}pledge_transaction_types', 'type_id');
		}else{
			fatal_error($txt['pledges_no_new_type'], false);
		}
		
		if ($add_account){
			$acc_name = $smcFunc['htmlspecialchars']($_REQUEST['new_account'],ENT_QUOTES);
			//if we got here then add the account
			$smcFunc['db_query']('', "INSERT INTO {db_prefix}pledge_accounts 
					(account_name)
					VALUES('$acc_name')");
			// Get the Account ID
			$account = $smcFunc['db_insert_id']('{db_prefix}pledge_accounts', 'account_id');
		}
		
		$smcFunc['db_insert']('',
		'{db_prefix}pledge_transactions',
			[
				'transaction_acc_id' => 'int',
				'transaction_type_id' => 'int',
				'operation' => 'string',
				'debit_amount' => 'float',
				'transaction_date' => 'int'
			],
			[
				(int) $account,
				(int) $trans_type,
				(string) $operation,
				(float) $debit_amount,
				(int) time()
			],
			[]
		);
					

	redirectexit('action=pledge;area=pledge;sa=index;ds=expenses');

	}
	
	public function ShowEditExpenses()
	{
		global $context, $scripturl, $txt, $smcFunc, $modSettings;
	
		isAllowedTo('pledges_can_edit_admin');
		
		if (!empty($_REQUEST['tr']))
			$transaction =  (int) $_REQUEST['tr'];
		else
			$transaction = 0;

		if (empty($transaction))
			fatal_error($txt['pledges_no_transaction'], false);
		
		$context['page_title'] = $modSettings['pledges_title']. '&nbsp;'.$txt['pledges_expense_title'];

		// Linktree
		$context['linktree'][] = [
			'url' => $scripturl . '?action=pledge;area=pledge;sa=lists',
			'name' => $modSettings['pledges_title']. '&nbsp;'.$txt['pledges_expense_title'],
		];
		
		$expquery = $smcFunc['db_query']('', '
		SELECT p.transaction_id, p.operation, p.debit_amount, p.transaction_date,
		p.transaction_acc_id, p.transaction_type_id
		FROM {db_prefix}pledge_transactions AS p
		WHERE p.transaction_id = {int:this_type}',
			[
			'this_type' => $transaction,
			]
		);
		
		//Make sure there is a entry
		if ($smcFunc['db_affected_rows']() == 0)
			fatal_error($txt['pledges_no_transaction'], false);
		
		$context['edit_expence_data'] = array();
		$context['edit_expence_data'] = $smcFunc['db_fetch_assoc']($expquery);
		$smcFunc['db_free_result']($expquery);
		
		//Now just load the template
		$context['sub_template'] = 'edit_expenses';
	}
	
	public function DoEditExpenses()
	{

		global $smcFunc, $user_info, $txt;

		$account = 0;
		$debit_amount = 0;
		$acc_name = '';
		$add_account = 0;
		$transaction = 0;
		
		
		isAllowedTo('pledges_can_edit_admin');
		
		if (!empty($_REQUEST['tr']))
			$transaction =  (int) $_REQUEST['tr'];
		else
			$transaction = 0;

		if (empty($transaction))
			fatal_error($txt['pledges_no_transaction'], false);
		
		if (!empty($_REQUEST['debit']))
			$debit_amount =  (float) $_REQUEST['debit'];
		else
			$debit_amount = 0;

		if (empty($debit_amount))
			fatal_error($txt['pledges_currency_format'], false);
		
		$operation = $smcFunc['htmlspecialchars']($_REQUEST['comment'],ENT_QUOTES);
		
		//Are we using selected account or adding a new type
		if ($_REQUEST['account'] != 'New'){
			$account = (int) $_REQUEST['account'];
			$add_account = 0;
		}elseif (!empty($_REQUEST['new_account'])){		
			//if we got here then add then tell code after the Transactions to add the account
			$add_account = 1;
		}else{
			fatal_error($txt['pledges_no_new_account'], false);
		}

		//Are we using selected type or adding a new type
		if ($_REQUEST['type'] != 'New'){
			$trans_type = (int) $_REQUEST['type'];
		}elseif (!empty($_REQUEST['new_type'])){
		//Clean the data
		$type_name = $smcFunc['htmlspecialchars']($_REQUEST['new_type'],ENT_QUOTES);
		$smcFunc['db_query']('', "INSERT INTO {db_prefix}pledge_transaction_types 
					(type_name)
					VALUES('$type_name')");
					
		// Get the Type ID
		$trans_type = $smcFunc['db_insert_id']('{db_prefix}pledge_transaction_types', 'type_id');
		}else{
			fatal_error($txt['pledges_no_new_type'], false);
		}
		
		if ($add_account){
			$acc_name = $smcFunc['htmlspecialchars']($_REQUEST['new_account'],ENT_QUOTES);
			//if we got here then add the account
			$smcFunc['db_query']('', "INSERT INTO {db_prefix}pledge_accounts 
					(account_name)
					VALUES('$acc_name')");
			// Get the Account ID
			$account = $smcFunc['db_insert_id']('{db_prefix}pledge_accounts', 'account_id');
		}
		
		$smcFunc['db_insert']('replace',
		'{db_prefix}pledge_transactions',
			[
				'transaction_id' => 'int',
				'transaction_acc_id' => 'int',
				'transaction_type_id' => 'int',
				'operation' => 'string',
				'debit_amount' => 'float',
				'transaction_date' => 'int'
				
			],
			[	(int) $transaction,
				(int) $account,
				(int) $trans_type,
				(string) $operation,
				(float) $debit_amount,
				(int) $_REQUEST['dt']
			],
			['transaction_id']
		);
					

	redirectexit('action=pledge;area=pledge;sa=index;ds=expenses');

	}
	
	public function DeleteExpenses()
	{

		global $smcFunc, $txt;

		// Is the user allowed to Search the Artists?

		isAllowedTo('pledges_can_edit_admin');
		
		if (!empty($_REQUEST['tr']))
			$transaction =  (int) $_REQUEST['tr'];
		else
			$transaction = 0;

		if (empty($transaction))
			fatal_error($txt['pledges_no_transaction'], false);
	
		$smcFunc['db_query']('', "DELETE FROM {db_prefix}pledge_transactions WHERE transaction_id = $transaction LIMIT 1");

		// Redirect to the category listing
	redirectexit('action=pledge;area=pledge;sa=index;ds=expenses');

	}
	
		public function ShowGoals()
	{
		global $context, $scripturl, $txt, $smcFunc, $modSettings;
		
		//Main Page can be viewed by any vistors but entry system will be admin only
		isAllowedTo('pledges_can_view_Pledges');
		
		$context['page_title'] = $modSettings['pledges_title']. '&nbsp;'.$txt['pledges_goals_title'];

		// Linktree
		$context['linktree'][] = [
			'url' => $scripturl . '?action=pledge;area=pledge;sa=goals',
			'name' => $modSettings['pledges_title']. '&nbsp;'.$txt['pledges_goals_title'],
		];
		//Load All The Values
		
		
		//Get the list of goals if none display custom message
		$goalsquery = $smcFunc['db_query']('', '
		SELECT g.goal_id, g.goal_name, g.goal_year, g.goal_type_id,	g.goal_amount
		FROM {db_prefix}pledge_goals AS g
		ORDER BY g.goal_year DESC');
		
		//If no goals tell the template so message can be displayed
		$context['total_goals'] = $smcFunc['db_num_rows']($goalsquery);
		
		if ($smcFunc['db_affected_rows']() != 0){
			while($row = $smcFunc['db_fetch_assoc']($goalsquery))
				$context['goals_data'][] = $row;
		}
		
		
		//Now just load the template
		$context['sub_template'] = 'show_goals';
	}
	
	public function AddGoal()
	{

		global $smcFunc, $user_info, $txt, $sourcedir;
		
		isAllowedTo('pledges_can_edit_admin');
		
		$account = 0;
		$debit_amount = 0;
		$acc_name = '';
		$add_account = 0;
		$current_donations = Array();
		
		if (!empty($_REQUEST['goal_amount']))
			$goal_amount =  (float) $_REQUEST['goal_amount'];
		else
			$goal_amount = 0;

		if (empty($goal_amount) || !$goal_amount)
			fatal_error($txt['pledges_goal_amount_required'], false);
		
		if (empty($_REQUEST['goal_name']))
			fatal_error($txt['pledges_goal_name_required'], false);
		
		$goal_name = $smcFunc['htmlspecialchars']($_REQUEST['goal_name'],ENT_QUOTES);
		$goal_year = (int) $_REQUEST['goal_year'];
		$goal_type = (int) $_REQUEST['goal_type'];
		
		$smcFunc['db_insert']('',
		'{db_prefix}pledge_goals',
			[
				'goal_name' => 'string',
				'goal_year' => 'int',
				'goal_type_id' => 'int',
				'goal_amount' => 'float',
			],
			[
				(string) $goal_name,
				(int) $goal_year,
				(int) $goal_type,
				(float) $goal_amount,
			],
			[]
		);
		
		//Get the ID for new Goal
	$goal_id = $smcFunc['db_insert_id']('{db_prefix}pledge_goals', 'goal_id');
	
	//Now sort The Type Values
		switch ($goal_type){
			case 1: //Yearly
				$values = 1;
				break;
			case 12: //Monthly Array
				$values = 12;
				break;
			case 4: //Quartly Array
				$values = 4;
				break;
			case 2: //6 monthly
				$values = 2;
				break;
		}
		//Add records For selected Type
		for ($g = 1 ; $g <= $values; $g++){
			$smcFunc['db_insert']('',
			'{db_prefix}pledge_goals_values',
				[
					'goal_id' => 'int',
					'unit_id' => 'int'
				],
				[
					(int) $goal_id,
					(int) $g
				],
				[]
			);
		}
		
		//Now Check For any Amounts added for this year
		require_once($sourcedir . '/Pledges/Pledges_module.php');
		
		$current_donations = CheckForExistingDonations($goal_year, $goal_type);
		if ($current_donations){
			//Update The Values
			foreach ($current_donations as $row)
			{
			if ($goal_type == 1)
				UpdatePledgeByUnit(1, $goal_id, $row['Pledged']);
			else
				UpdatePledgeByUnit($row['pledge_month'], $goal_id, $row['Pledged']);
			} 
		}
		
	redirectexit('action=pledge;area=pledge;sa=goals');

	}
	
	public function ShowEditGoal()
	{

		global $context, $scripturl, $txt, $smcFunc, $modSettings;
		
		isAllowedTo('pledges_can_edit_admin');
		
		if (!empty($_REQUEST['goal']))
			$goal =  (int) $_REQUEST['goal'];
		else
			$goal = 0;

		if (empty($goal))
			fatal_error($txt['pledges_no_goal'], false);
		
		$context['page_title'] = $modSettings['pledges_title']. '&nbsp;'.$txt['pledges_goals_title'];

		// Linktree
		$context['linktree'][] = [
			'url' => $scripturl . '?action=pledge;area=pledge;sa=goals',
			'name' => $modSettings['pledges_title']. '&nbsp;'.$txt['pledges_goals_title'],
		];
		
		$dbquery = $smcFunc['db_query']('', '
		SELECT g.goal_id, g.goal_name, g.goal_amount
		FROM {db_prefix}pledge_goals AS g
		WHERE g.goal_id = {int:this_goal}',
				[
				'this_goal' => $goal,
				]
			);

		$context['edit_goal_data'] = $smcFunc['db_fetch_assoc']($dbquery);
		
		$smcFunc['db_free_result']($dbquery);
		
	$context['sub_template'] = 'edit_goals';
		
	}

	public function EditGoal()
	{
	global $smcFunc, $context;

		// Is the user allowed to Search the Artists?

		isAllowedTo('pledges_can_edit_admin');
		
		if (!empty($_REQUEST['goal']))
			$goal =  (int) $_REQUEST['goal'];
		else
			$goal = 0;

		if (empty($goal))
			fatal_error($txt['pledges_no_goal'], false);
		
		if (!empty($_REQUEST['goal_amount']))
			$goal_amount =  (float) $_REQUEST['goal_amount'];
		else
			$goal_amount = 0;

		if (empty($goal_amount) || !$goal_amount)
			fatal_error($txt['pledges_goal_amount_required'], false);
		
		if (empty($_REQUEST['goal_name']))
			fatal_error($txt['pledges_goal_name_required'], false);
		
		$goal_name = $smcFunc['htmlspecialchars']($_REQUEST['goal_name'],ENT_QUOTES);
		
		$smcFunc['db_query']('', "UPDATE {db_prefix}pledge_goals
					SET goal_name = '$goal_name', goal_amount = '$goal_amount'
					WHERE goal_id = $goal LIMIT 1");
		
		// Redirect to the category listing
	redirectexit('action=pledge;area=pledge;sa=goals');

	}	
	public function DeleteGoal()
	{

		global $smcFunc, $txt;

		// Is the user allowed to Search the Artists?

		isAllowedTo('pledges_can_edit_admin');
		
		if (!empty($_REQUEST['goal']))
			$goal =  (int) $_REQUEST['goal'];
		else
			$goal = 0;

		if (empty($goal))
			fatal_error($txt['pledges_no_goal'], false);
		
		//Delete The Main Goal Record
		$smcFunc['db_query']('', "DELETE FROM {db_prefix}pledge_goals WHERE goal_id = $goal LIMIT 1");
		//Now Delete values record(s)
		$smcFunc['db_query']('', "DELETE FROM {db_prefix}pledge_goals_values WHERE goal_id = $goal");

		// Redirect to the category listing
	redirectexit('action=pledge;area=pledge;sa=goals');

	}
	
}