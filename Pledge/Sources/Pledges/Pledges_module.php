<?php
/**
 * @package Pledges
 * @version 0.1
 * @author Michael Javes <dbdreams@aol.com>
 * @canvas template By Diego Andrés <diegoandres_cortes@outlook.com>
 * @copyright Copyright (c) 2023, Database Dreams
 * @license https://www.mozilla.org/en-US/MPL/2.0/
 */
 
function DoPledgesHeader()
{
	global $modSettings;
	
	//This will be used on a number of views	
	$pledge_header = '<div class="cat_bar">
		    <h3 class="catbg">'. $modSettings['pledges_title'] .'</h3>
				</div><div id="pledge_header" class="information"><center>'
		. parse_bbc($modSettings['pledges_main_display_text']) . '<br />
		<a title="' . $modSettings['pledges_link_title'] . '" href="' .$modSettings['pledges_payme_link']. '" rel="noopener" target="_blank"><img src="'. $modSettings['pledges_root_url'] . $modSettings['pledges_image_folder'] . 'paypal_donate.png" alt="" /></a><br />
		</center></div>';
		
	return $pledge_header;
}

function GetAccountsList($acc)
{
	global $smcFunc;

	$dbquery = $smcFunc['db_query']('', '
    SELECT a.account_id, a.account_name
	FROM {db_prefix}pledge_accounts AS a 
	ORDER BY a.account_name ASC');

	$account = '';

		//Create The Header Of List BOX
		$account .= '<select name=account value="">accounts</option><option value="New">(Add New Account)</option>';
		
		//Loop Through Accounts Adding them to the list
		while ($row = $smcFunc['db_fetch_assoc']($dbquery))
		{
			
			//Get Each Row
			$account .= '<option value="' . $row['account_id'] . '" ' . (($row['account_id'] == $acc) ? ' selected="selected"' : '') .'>' . $row['account_name'] . '</option>';
		}

		$account .= '</select>';// Closing off list box

	$smcFunc['db_free_result']($dbquery);
	
	return $account;
}

function GetTransactionTypesList($tra)
{
	global $smcFunc;

	$dbquery = $smcFunc['db_query']('', '
    SELECT t.type_id, t.type_name
	FROM {db_prefix}pledge_transaction_types AS t 
	ORDER BY t.type_name ASC');

	$transaction = '';

		//Create The Header Of List BOX
		$transaction .= '<select name=type value="">transaction</option><option value="New">(Add New Transaction Type)</option>'; 
		//Loop Through tyes Adding them to the list
		while ($row = $smcFunc['db_fetch_assoc']($dbquery))
		{
			//Get Each Row
			$transaction .= '<option value="' . $row['type_id'] . '" ' . (($row['type_id'] == $tra) ? ' selected="selected"' : '') .'>' . $row['type_name'] . '</option>';
		}

		$transaction .= '</select>';// Closing off list box

	$smcFunc['db_free_result']($dbquery);
	
	return $transaction;
}

function UserAddedlikes()
{
	global $smcFunc, $user_info;
	
	if ($user_info['is_guest'])
		$mem_id = 0;
	else
	$mem_id = $user_info['id'];
	
	$myrow = array();
	
	$likequery = $smcFunc['db_query']('', '
    SELECT l.content_id AS pledge_id
	FROM {db_prefix}user_likes AS l
	WHERE l.id_member = {int:this_member} AND l.content_type = {string:this_type}',
			[
				'this_member' => (int) $mem_id,
				'this_type' => (string) 'pledge'
			]
		);
	
	while ($row = $smcFunc['db_fetch_assoc']($likequery))
		$myrow[] = $row['pledge_id'];
	
	$smcFunc['db_free_result']($likequery);
	
	return $myrow;
}

function GetPledgesToBeConfirmed()
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

function DoTrancastionsSettings()
{
	global $smcFunc;
	
	$acc_name = 'Pledges System';
	$type_name = 'Donation';
	$settings1 = 'pledges_transaction_setup';
	$settings1_value = 1;
	$settings2 = 'pledges_account';
	$acc_id = 0;
	$type_id = 0;
	$settings3 = 'pledges_transaction_type';
	
	//Update The Settings table So Its Marked As Done
		$smcFunc['db_query']('', "INSERT INTO {db_prefix}settings 
					(variable, value)
					VALUES('$settings1','$settings1_value')");
	
		//Accounts Table
		$smcFunc['db_query']('', "INSERT INTO {db_prefix}pledge_accounts 
					(account_name)
					VALUES('$acc_name')");
		// Get the Account ID
		$acc_id = $smcFunc['db_insert_id']('{db_prefix}pledge_accounts', 'account_id');
	
		//Add the setting
		$smcFunc['db_query']('', "INSERT INTO {db_prefix}settings 
				(variable, value)
				VALUES('$settings2','$acc_id')");
				
		//transaction type
		$smcFunc['db_query']('', "INSERT INTO {db_prefix}pledge_transaction_types 
					(type_name)
					VALUES('$type_name')");
					
		// Get the Type ID
	$type_id = $smcFunc['db_insert_id']('{db_prefix}pledge_transaction_types', 'type_id');
		
		//Add the setting
		$smcFunc['db_query']('', "INSERT INTO {db_prefix}settings 
				(variable, value)
				VALUES('$settings3','$type_id')");
}

function AccountsUsed()
{
	global $smcFunc, $user_info;
	
	$used_accounts = array();
	
	$transquery = $smcFunc['db_query']('', '
    SELECT DISTINCT t.transaction_acc_id
	FROM {db_prefix}pledge_transactions AS t');
	
	while ($row = $smcFunc['db_fetch_assoc']($transquery))
		$used_accounts[] = $row['transaction_acc_id'];
	
	$smcFunc['db_free_result']($transquery);
	
	return $used_accounts;
}

function TransactionsUsed()
{
	global $smcFunc, $user_info;
	
	$used_transactions = array();
	
	$transquery = $smcFunc['db_query']('', '
    SELECT DISTINCT t.transaction_type_id
	FROM {db_prefix}pledge_transactions AS t');
	
	while ($row = $smcFunc['db_fetch_assoc']($transquery))
		$used_transactions[] = $row['transaction_type_id'];
	
	$smcFunc['db_free_result']($transquery);
	
	return $used_transactions;
}

function GetBlockCode()
{
	global $smcFunc, $modSettings, $txt, $scripturl, $sourcedir;
	
	//Load The Language
	loadLanguage('Pledges/Pledges');
	
	$member_list = '';
	$link = '';
	$donate_image = '';
	$block_image = 'donatewithpictureblue';
	$new_pledges = '';
	$goal = Array();
	$unit = 0;
	$goal_unit = Array();
	$goal_total = Array();
	$stats_section = '';
	$unit_name = '';
	
//	$total_waiting = GetPledgesToBeConfirmed();
	
//	if ($total_waiting && AllowedTo('pledges_can_edit_admin'))
//		$new_pledges = $txt['pledges_block_new'] . $total_waiting;
	
	$dbquery = $smcFunc['db_query']('', '
		SELECT p.pledge_id, p.pledge_date, p.pledge_amount, p.member_id,
		p.can_publish, p.pledge_confirmed,
		mem.id_member, mem.real_name, mg.online_color, mg.id_group
		FROM {db_prefix}pledges AS p
		LEFT JOIN {db_prefix}members AS mem ON (mem.id_member = p.member_id)
		LEFT JOIN {db_prefix}membergroups AS mg ON (mg.id_group = IF(mem.id_group = 0, mem.id_post_group, mem.id_group)) 
		WHERE p.pledge_confirmed = {int:this_confirmed}
		ORDER BY p.pledge_date DESC
		LIMIT {int:this_start}',
				[
				'this_confirmed' => 1,
				'this_start' => $modSettings['pledges_top_members_number'],
				]
		);
		
	while($row = $smcFunc['db_fetch_assoc']($dbquery))
	{
		//Set Anonymous each loop
		$link = $txt['pledges_list_anonymous'];
		
		if ($row['can_publish']){
			if (!empty($row['online_color']))
				$link = '<a href="' . $scripturl . '?action=profile;u=' . $row['id_member'] . '" style="color: ' . $row['online_color'] . ';">' . $row['real_name'] . '</a>';
			else
				$link = '<a href="' . $scripturl . '?action=profile;u=' . $row['id_member'] . '">' . $row['real_name'] . '</a>';
		}
		$member_list .= date("d M y",$row['pledge_date']) . '&nbsp;' . $link . '&nbsp;' . $modSettings['pledges_default_currency'] . $row['pledge_amount'] . '<br>';
	}
	
	switch ($modSettings['pledges_block_image']){
		case 0:
			$block_image = 'donatewithpictureblue';
			break;
		case 1:
			$block_image = 'donatewithpicturegreen';
			break;
		case 2:
			$block_image = 'donatewithpictureorange';
			break;
		case 3:
			$block_image = 'donatewithpicturedarkblue';
			break;
		case 4:
			$block_image = 'donatewithpicturebrown';
			break;
		case 5:
			$block_image = 'donatewithpicturepink';
			break;
		case 6:
			$block_image = 'donatewithpicturepurple';
			break;
		case 7:
			$block_image = 'donatewithpicturered';
			break;
		case 8:
			$block_image = 'donatelightblue';
			break;
		case 9:
			$block_image = 'donategreen';
			break;	
		case 10:
			$block_image = 'donateorange';
			break;
		case 11:
			$block_image = 'donatedarkblue';
			break;
		case 12:
			$block_image = 'donatebrown';
			break;
		case 13:
			$block_image = 'donatepink';
			break;
		case 14:
			$block_image = 'donatepurple';
			break;
		case 15:
			$block_image = 'donatered';
			break;
	}
		
	
	$donate_image = '<a title="' . $modSettings['pledges_link_title'] . '" href="' . $scripturl . '?action=pledge;area=pledge;sa=index;ds=pledges"><img src="'. $modSettings['pledges_root_url'] . $modSettings['pledges_image_folder'] . $block_image .'.png" alt="View Donations" /></a><br />';
	
	//Now Get the Total For Year, Current Month/Quater/Year Etc
	//First We Need The Goal for details
	$dbquery = $smcFunc['db_query']('', '
		SELECT g.goal_id, g.goal_name, g.goal_year, g.goal_type_id,	g.goal_amount
		FROM {db_prefix}pledge_goals AS g
		WHERE g.goal_year = {int:this_year}',
					[
						'this_year' => (int) DATE("Y"),
					]
				);
				
	if ($smcFunc['db_affected_rows']() != 0){
		//If we got here then we have a goal
			$goal = $smcFunc['db_fetch_assoc']($dbquery);
			//Now we need to sort out which type it is
			switch ($goal['goal_type_id']){
				case 1: //Yearly
					$unit = 1;
					$unit_name = $txt['pledges_goals_year'];
					break;
				case 12: //Monthly
					$unit = DATE("n");
					$unit_name = GetMonthlyNames($unit);
					break;
				case 4: //Quartly
					$unit = floor(DATE("n") / 3) + 1;
					$unit_name = GetQuartlyNames($unit);
					break;
				case 2: //6 monthly
					$unit = ceil(DATE("n")/6);
					$unit_name = GetHalfYearlyNames($unit);
					break;
			}
		//Now Get The Related Value Record
		$valuequery = $smcFunc['db_query']('', '
		SELECT g.goal_id, g.unit_id, g.unit_amount
		FROM {db_prefix}pledge_goals_values AS g
		WHERE g.goal_id = {int:this_goal} AND g.unit_id = {int:this_unit}',
					[
						'this_goal' => (int) $goal['goal_id'],
						'this_unit' => (int) $unit
					]
				);
		//There can only be one value record
		$goal_unit = $smcFunc['db_fetch_assoc']($valuequery);
		//Clean Up
		$smcFunc['db_free_result']($valuequery);
		//How Rich Are we
		$totalquery = $smcFunc['db_query']('', '
		SELECT SUM(g.unit_amount) AS total_pledged
		FROM {db_prefix}pledge_goals_values AS g
		WHERE g.goal_id = {int:this_goal}',
					[
						'this_goal' => (int) $goal['goal_id'],
					]
				);
			//There can only be one value record
			$goal_total = $smcFunc['db_fetch_assoc']($totalquery);
			//Clean Up
		$smcFunc['db_free_result']($totalquery);
		
		//Now Create the Display
		$stats_section = '<h3>'. $goal['goal_name']. ' ' . $txt['pledges_block_stats'] .'</h3>'
		.$unit_name. $modSettings['pledges_default_currency'].$goal_unit['unit_amount']. '<br />'
		. $txt['pledges_block_total'] .$modSettings['pledges_default_currency'].$goal_total['total_pledged']. '<br />'
		. $txt['pledges_block_goal'] .$modSettings['pledges_default_currency'].$goal['goal_amount'];
		
	}
	
	$smcFunc['db_free_result']($dbquery);
	
	return '<center>' . $donate_image.$member_list.$new_pledges. '</center>'.$stats_section;
}

function PledgesEmailAdmins($subject, $body, $additional_recipients = array())
{
	global $smcFunc, $sourcedir;

    // Fix subject line/body
    $body = str_replace("&#039;","'",$body);
    $subject = str_replace("&#039;","'",$subject);

    // We certainly want this.
	require_once($sourcedir . '/Subs-Post.php');

	// Load all groups which are effectively admins.
	$request = $smcFunc['db_query']('', '
		SELECT id_group
		FROM {db_prefix}permissions
		WHERE permission = {string:admin}
			AND add_deny = {int:add_deny}
			AND id_group != {int:id_group}',
			[
			'add_deny' => 1,
			'id_group' => 0,
			'admin' => 'pledges_can_edit_admin',
			]
	);
	$groups = array(1);
	while ($row = $smcFunc['db_fetch_assoc']($request))
		$groups[] = $row['id_group'];
	$smcFunc['db_free_result']($request);

	$request = $smcFunc['db_query']('', '
		SELECT id_member, member_name, real_name, lngfile, email_address
		FROM {db_prefix}members
		WHERE (id_group IN ({array_int:group_list}) OR FIND_IN_SET({raw:group_array_implode}, additional_groups) != 0)
		ORDER BY lngfile',
			[
			'group_list' => $groups,
			'group_array_implode' => implode(', additional_groups) != 0 OR FIND_IN_SET(', $groups),
			]
	);
	$emails_sent = array();
	while ($row = $smcFunc['db_fetch_assoc']($request))
	{
		// Stick their particulars in the replacement data.

		// Then send the actual email.
		sendmail($row['email_address'], $subject, $body, null, 'errors', false, 1);

		// Track who we emailed so we don't do it twice.
		$emails_sent[] = $row['email_address'];
	}
	$smcFunc['db_free_result']($request);

	// Any additional users we must email this to?
	if (!empty($additional_recipients))
		foreach ($additional_recipients as $recipient)
		{
			if (in_array($recipient['email'], $emails_sent))
				continue;

			// Send off the email.
			sendmail($recipient['email'], $subject, $body, null, 'errors', false, 1);
		}
}


function PledgesConfirmationPM($owner)
{
	global $smcFunc, $modSettings, $sourcedir, $user_profile, $txt;
	
	//If no admin added in settings get out quick
	if (!$modSettings['pledges_use_member'])
		return;
	
	require_once($sourcedir . '/Subs-Post.php');
	
	$pm_sender = $modSettings['pledges_use_member'];

	    loadMemberData($pm_sender, false, 'normal');
	
        $pm_subject = $txt['pledges_PM_title'];
		$pm_body = $modSettings['pledges_thankyou_text'];
			
	    $pm_to = array(
			'to' => array($owner),
			'bcc' => array(),
	    );
	   
        $pm_from = array(
			'id' => $pm_sender,
			'name' => (isset($user_profile[$pm_sender]['real_name'])),
			'username' => (isset($user_profile[$pm_sender]['member_name'])),
	    );

		sendpm($pm_to, $pm_subject, $pm_body, false, $pm_from);
}

function GetGoalYears($y)
{
	global $smcFunc, $context;

	//create list of years

	$Ddatefrom = GetNextYearGoal();
	$Ddateto = $Ddatefrom+5;
	$number = range($Ddatefrom,$Ddateto);
	
	//Setting Selected needs to take into account if year used?
	$y = ($y ? $y : $Ddatefrom);
	
	$lst = '';

		$lst .= '<select name=goal_year value="">Years</option>'; // list box select command
		//Loop Through Years Adding them to the list
		foreach ($number as $years)
		{
			//Get Each Row
			$lst .= '<option value="' . $years . '" ' . (($years == $y) ? ' selected="selected"' : '') .'>' . $years . '</option>';
		}

		$lst .= '</select>';// Closing of list box

	return $lst;
}

function GetNextYearGoal()
{
	global $smcFunc;
	
	$year = 0;
	//This just makes sure current year hasn't been used
		$checkquery = $smcFunc['db_query']('', '
		SELECT MAX(g.goal_year) As Last_year
		FROM {db_prefix}pledge_goals AS g');
			
		if ($smcFunc['db_affected_rows']() == 0){
			$year = DATE("Y");
		}else{
			$row = $smcFunc['db_fetch_assoc']($checkquery);
			$year = $row['Last_year']+1;
		}
		$smcFunc['db_free_result']($checkquery);
		
		return $year;
}

function GetMonthlyNames($unit)
{
	global $txt;
	
		switch ($unit){
			case 1:
				$month_name = $txt['pledges_goal_january'];
				break;
			case 2:
				$month_name = $txt['pledges_goal_february'];
				break;
			case 3:
				$month_name = $txt['pledges_goal_march'];
				break;
			case 4:
				$month_name = $txt['pledges_goal_april'];
				break;
			case 5:
				$month_name = $txt['pledges_goal_may'];
				break;
			case 6:
				$month_name = $txt['pledges_goal_june'];
				break;
			case 7:
				$month_name = $txt['pledges_goal_july'];
				break;
			case 8:
				$month_name = $txt['pledges_goal_august'];
				break;
			case 9:
				$month_name = $txt['pledges_goal_september'];
				break;
			case 10:
				$month_name = $txt['pledges_goal_october'];
				break;
			case 11:
				$month_name = $txt['pledges_goal_november'];
				break;
			case 12:
				$month_name = $txt['pledges_goal_december'];
				break;
			}
		return $month_name;
}

function GetQuartlyNames($unit)
{
	global $txt;
	
		switch ($unit){
			case 1:
				$quater_name = $txt['pledges_goal_first_quater'];
				break;
			case 2:
				$quater_name = $txt['pledges_goal_second_quater'];
				break;
			case 3:
				$quater_name = $txt['pledges_goal_third_quater'];
				break;
			case 4:
				$quater_name = $txt['pledges_goal_fourth_quater'];
				break;
			}
		return $quater_name;
}
function GetHalfYearlyNames($unit)
{
	global $txt;
	
		switch ($unit){
			case 1:
				$half_name = $txt['pledges_goal_first_six_months'];
				break;
			case 2:
				$half_name = $txt['pledges_goal_last_six_months'];
				break;
			}
		return $half_name;
}

function GetAllGoalValues()
{
	global $smcFunc, $context;

	$com = '';
	$valuesarray = array();
	$i=0;
	
	$dbquery = $smcFunc['db_query']('', '
    SELECT v.goal_id, v.unit_id, v.unit_amount
    FROM {db_prefix}pledge_goals_values AS v 
	ORDER BY v.goal_id ASC, v.unit_id ASC');
	
	
	if ($smcFunc['db_affected_rows']() != 0)
	{
		while ($row = $smcFunc['db_fetch_assoc']($dbquery))
		{
			
				$valuesarray[$i]['goal_id'] = $row['goal_id'];
				$valuesarray[$i]['unit_id'] = $row['unit_id'];
				$valuesarray[$i]['unit_amount'] = $row['unit_amount'];
				$i++;				
		}	
	}
	$smcFunc['db_free_result']($dbquery);
	
	return $valuesarray;
}

function GetGoalValues($goal, $values, $type)
{
		global $txt, $modSettings;
		
		$valuename = '';
		$valueline = '';
		$newline = '';
		$i=0;
		
		foreach ($values as $row)
		{
			if ($row['goal_id'] == $goal){
			switch ($type){
				case 1: //Yearly
				$valuename = $txt['pledges_goals_year'];
				break;
			case 12: //Monthly Array
				$valuename = GetMonthlyNames($row['unit_id']);
				break;
			case 4: //Quartly Array
				$valuename = GetQuartlyNames($row['unit_id']);
				break;
			case 2: //6 monthly
				$valuename = GetHalfYearlyNames($row['unit_id']);
				break;
			}
				$i++;
				if ($i==4 && $type == 12)
					$newline =  '<br />';
				elseif ($i==2 && $type == 4)
					$newline =  '<br />';
				else
					$newline = '';
				
				$valueline .= $valuename . $modSettings['pledges_default_currency'] . $row['unit_amount'] . $newline . ', ' ;
				if ($i==4 && $type == 12){
					$i=0;
					$valueline = rtrim($valueline, ', ');
				}elseif ($i==2 && $type == 4){
					$i=0;
					$valueline = rtrim($valueline, ', ');
				}
			}
		}
		

		return rtrim($valueline, ', ');
}

function CheckForExistingDonations($y, $type)
{
	global $smcFunc, $context;
	
	$donations = Array();
	$sql_query = '';
	
		switch ($type){
			case 1: //Yearly
				$sql_query = 'SELECT from_unixtime(p.pledge_date,"%Y") AS pledge_month, SUM(p.pledge_amount) As Pledged
				FROM {db_prefix}pledges AS p
				WHERE from_unixtime(p.pledge_date,"%Y") = {int:this_year}
				GROUP BY from_unixtime(p.pledge_date,"%Y")';
				break;
			case 12: //Monthly Query
				$sql_query = 'SELECT from_unixtime(p.pledge_date,"%c") AS pledge_month, SUM(p.pledge_amount) As Pledged
				FROM {db_prefix}pledges AS p
				WHERE from_unixtime(p.pledge_date,"%Y") = {int:this_year}
				GROUP BY from_unixtime(p.pledge_date,"%c")
				ORDER BY from_unixtime(p.pledge_date,"%c")';
				break;
			case 4: //Quarterly Query
				$sql_query = 'SELECT QUARTER(from_unixtime(p.pledge_date,"%Y-%m-%d")) AS pledge_month, SUM(p.pledge_amount) As Pledged
				FROM {db_prefix}pledges AS p
				WHERE from_unixtime(p.pledge_date,"%Y") = {int:this_year}
				GROUP BY QUARTER(from_unixtime(p.pledge_date,"%Y-%m-%d"))
				ORDER BY QUARTER(from_unixtime(p.pledge_date,"%Y-%m-%d"))';
				break;
			case 2: //6 monthly
				$sql_query = 'SELECT CEILING(MONTH(from_unixtime(p.pledge_date,"%Y-%m-%d"))/6) AS pledge_month, SUM(p.pledge_amount) As Pledged
				FROM {db_prefix}pledges AS p
				WHERE from_unixtime(p.pledge_date,"%Y") = {int:this_year}
				GROUP BY CEILING(MONTH(from_unixtime(p.pledge_date,"%Y-%m-%d"))/6)
				ORDER BY CEILING(MONTH(from_unixtime(p.pledge_date,"%Y-%m-%d"))/6)';
				break;
		}
		
				$dbquery = $smcFunc['db_query']('', $sql_query,
					[
						'this_year' => (int)$y,
					]
				);

		while($row = $smcFunc['db_fetch_assoc']($dbquery))
			$donations[] = $row;
		
		return $donations;
}

function UpdatePledgeByUnit($m, $goal, $amount)
{
	global $smcFunc;
	
		$smcFunc['db_query']('', "UPDATE {db_prefix}pledge_goals_values 
					SET unit_amount = unit_amount + $amount
					WHERE goal_id = $goal AND unit_id = $m  LIMIT 1");
	
}
?>