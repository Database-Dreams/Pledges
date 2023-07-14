<?php

/**
 * @package Pledges
 * @version 0.1
 * @author Michael Javes <dbdreams@aol.com>
 * @canvas template By Diego Andrés <diegoandres_cortes@outlook.com>
 * @copyright Copyright (c) 2023, Database Dreams
 * @license https://www.mozilla.org/en-US/MPL/2.0/
 */

function template_main_above()
{
	global $scripturl, $modSettings, $txt, $sourcedir;
	
	//Temp
	//Change $modSettings['artists_image_folder'] for $modSettings['pledges_root_url'].$modSettings['pledges_image_folder']
	$lnk = '';
	$total_waiting = '';
	if (!$modSettings['pledges_use_menu']){
		
	require_once($sourcedir . '/Pledges/Pledges_module.php');
	
	$total_waiting = GetPledgesToBeConfirmed();
	
	$total_waiting = ($total_waiting ? ' (' . $total_waiting . ')' : '');

	
	if (AllowedTo('pledges_can_edit_admin'))
		$lnk = '<li><a class="firstlevel" href="' . $scripturl . '?action=pledge;area=config"><span class="firstlevel"><img src="'. $modSettings['pledges_root_url'].$modSettings['pledges_image_folder'] . 'config.png" alt="Config" align="absMiddle" />'.$txt['pledges_button_config'].'</a></span></li>
	<li><a class="firstlevel" href="' . $scripturl . '?action=pledge;area=permissions"><span class="firstlevel"><img src="'. $modSettings['pledges_root_url'].$modSettings['pledges_image_folder'] . 'permisions.png" alt="Permissions" align="absMiddle" />'.$txt['pledges_button_permissions'].'</a></span></li>
	<li><a class="firstlevel" href="' . $scripturl . '?action=pledge;area=pledge;sa=lists"><span class="firstlevel"><img src="'. $modSettings['pledges_root_url'].$modSettings['pledges_image_folder'] . 'lists.png" alt="Lists" align="absMiddle" />'.$txt['pledges_lists_title'].'</a></span></li>
	<li><a class="firstlevel" href="' . $scripturl . '?action=pledge;area=pledge;sa=confirm"><span class="firstlevel"><img src="'. $modSettings['pledges_root_url'].$modSettings['pledges_image_folder'] . 'thumb_up.png" alt="Confir," align="absMiddle" />'.$txt['pledges_confirm_pledges']. '&nbsp;'.$modSettings['pledges_title'].$total_waiting.'</a></span></li>
	<li><a class="firstlevel" href="' . $scripturl . '?action=pledge;area=pledge;sa=spend"><span class="firstlevel"><img src="'. $modSettings['pledges_root_url'].$modSettings['pledges_image_folder'] . 'spending.png" alt="Lists" align="absMiddle" />'.$txt['pledges_expense_menu'].'</a></span></li>';
	
	echo '<div id="top_menu" class="dropmenu">
		<li><a class="firstlevel" href="' . $scripturl . '?action=pledge;area=pledge;sa=index;ds=pledges"><span class="firstlevel"><img src="'. $modSettings['pledges_root_url'].$modSettings['pledges_image_folder'] . 'house.png" alt="New" align="absMiddle" />'.$modSettings['pledges_title'].'</a></span></li><li><a class="firstlevel" href="' . $scripturl . '?action=pledge;area=pledge;sa=index;ds=expenses"><span class="firstlevel"><img src="'. $modSettings['pledges_root_url'].$modSettings['pledges_image_folder'] . 'expenses.png" alt="Re-Entry" align="absMiddle" />'.$txt['pledges_button_expence'].'</a></span></li><li><a class="firstlevel" href="' . $scripturl . '?action=pledge;area=pledge;sa=goals"><span class="firstlevel"><img src="'. $modSettings['pledges_root_url'].$modSettings['pledges_image_folder'] . 'goal.png" alt="goals" align="absMiddle" />'.$txt['pledges_goals_title'].'</a></span></li>'
		. $lnk. '</div><br />' ;
		
	}
	
}

/**
 * Wraps the tasks content with a little message at the end
 */
function template_main_below()
{
	global $context;

	echo '
		<br>
		<div style="text-align: center;">
			<span class="smalltext">
				', $context['pledges']['copyright'], '
			</span>
		</div>';
}

function template_pledges_view()
{
	global $context, $txt, $modSettings, $sourcedir, $scripturl, $user_info;
	
	$link = '';
	$showpageindex = '';
	$dslink = 'pledges';
	$has_voted = array();
	$extra_row = '';
	$extra_column = '';
	$admin_expenses = 0;
	$del_image = '';
	$strow = Array();
	
	
	require_once($sourcedir . '/Pledges/Pledges_module.php');
			
	echo DoPledgesHeader();
	
	//Do They have pemission to add Pledges?
	
	if (AllowedTo('pledges_can_add_Pledge')){
	
		echo'<form method="post" enctype="multipart/form-data" name="addpledge" id="addpledge" action="' . $scripturl . '?action=pledge;area=pledge;sa=savep" onsubmit="submitonce(this);">
		<div class="cat_bar">
		    <h3 class="catbg">
		', $txt['pledges_Add_Pledge_title'], '
		</h3>
	</div><div class="information">
	<table border="0" cellpadding="0" cellspacing="0" width="100%"><tbody>';
  
		echo '
		<tr class="title_bar">
			<th scope="col">' . $txt['pledges_form_amount'] . '</th>
			<th scope="col">' . $txt['pledges_form_comment'] . '</th>
			<th scope="col" vertical-align: center"><a title= "'. $txt['pledges_can_publish_title'] . '"><img src="'. $modSettings['pledges_root_url'].$modSettings['pledges_image_folder'] . 'Information.png" alt="Non-Mover" align="absMiddle" /></a>&nbsp;' . $txt['pledges_form_can_publish'] . '</th>
			<th scope="col" vertical-align: center"><a title= "'. $txt['pledges_name_used_title'] . '"><img src="'. $modSettings['pledges_root_url'].$modSettings['pledges_image_folder'] . 'Information.png" alt="Non-Mover" align="absMiddle" /></a>&nbsp;' .$txt['pledges_form_paypal_name'].'</th>

		</tr>';
		
		echo '<tr>
			<td style="text-align: center; width: 15%; vertical-align: center"><strong>',$modSettings['pledges_default_currency'],'</strong>&nbsp;<input type="text" size="10" name="amount" value="" maxlength="8" /></td>
			<td style="text-align: center; width: 55%; vertical-align: center"><input type="text" size="45" name="comment" value="" maxlength="150" /></td>
			<td style="text-align: center; width: 15%; vertical-align: center"><input type="checkbox" name="publish" value="" checked /></td>
			<td style="text-align: center; width: 15%; vertical-align: center"><input type="text" size="15" name="payname" value="" maxlength="150" /></td>';
		  
		echo '<tr class="windowbg2">
		<td colspan="4" align="right">
		<hr><input type="submit" value="' . $txt['pledges_Add_Pledge_button'] . '" name="submit" />
		</td>
	</tr>
	</table></tbody>
	</form></div></center>';
	}
	
	if (!empty($_REQUEST['ds']))
			$dslink = $_REQUEST['ds'];
		
		if ($context['pledges_total'] > $modSettings['pledges_items_per_page']){
			$context['page_index_top'] = constructPageIndex($scripturl . '?action=pledge;area=pledge;sa=index;ds=' . $dslink , $_GET['start'], $context['pledges_total'] , $modSettings['pledges_items_per_page']);
			$showpageindex = '<div class="pagesection">
            <span>'. $context['page_index_top']. '</span></div>';
		}
		
	if ($context['pledges_display'] == 'pledges'){
		
		//Get list of liked Pledges for current user
		$has_voted = UserAddedlikes();
			
		echo '<div class="cat_bar">
		    <h3 class="catbg">'. $modSettings['pledges_title'] .'</h3>
				</div>';
				
		echo '<div class="information"><table class="table_grid" Width="100%"><tbody>';
		
		echo $showpageindex;
		
		echo '<tr class="title_bar">
			<th scope="col">'. $txt['pledges_list_date_added'] .'</th>
			<th scope="col">'. $txt['pledges_list_member'] .'</th>
			<th scope="col">'. $txt['pledges_list_amount'] .'</th>
			<th scope="col"><a title= "'. $txt['pledges_list_points_title'] . '"><img src="'. $modSettings['pledges_root_url'].$modSettings['pledges_image_folder'] . 'Information.png" alt="Non-Mover" align="absMiddle" /></a>&nbsp;'. $txt['pledges_list_points'] .'</th>
			<th scope="col">'. $txt['pledges_list_Comment'] .'</th>
			<th scope="col">'. $txt['pledges_list_likes'] .'</th>
			</tr>';
			
	foreach($context['pledges_data'] as $row)
	{
		//Set Anonymous each loop
		$link = $txt['pledges_list_anonymous'];
		
		if ($row['can_publish']){
			if (!empty($row['online_color']))
				$link = '<a href="' . $scripturl . '?action=profile;u=' . $row['id_member'] . '" style="color: ' . $row['online_color'] . ';">' . $row['real_name'] . '</a>';
			else
				$link = '<a href="' . $scripturl . '?action=profile;u=' . $row['id_member'] . '">' . $row['real_name'] . '</a>';
		}
		//Has member voted for this Pledge, have they permission and is it there pledge?
		If (!in_array($row['pledge_id'],$has_voted) && AllowedTo('pledges_can_add_Pledge') && $context['user']['id'] != $row['member_id'])
			$context['like_link'] = '<a title="" href="'. $scripturl . '?action=pledge;area=pledge;sa=like;pl=' . $row['pledge_id'] . '"><img src="'. $modSettings['pledges_root_url'].$modSettings['pledges_image_folder'] . 'thumb_up.png" alt="Like Pledge" align="absMiddle" /></a>';
		else
			$context['like_link'] = '<img src="'. $modSettings['pledges_root_url'].$modSettings['pledges_image_folder'] . 'thumb_up.png" alt="Like Pledge" align="absMiddle" />';
				
	echo  '<tr class="windowbg">
			<td style="text-align: left; width: 15%">' . date("d M Y",$row['pledge_date']) . '</td>
			<td style="text-align: left; width: 15%">' . $link . '</td>
			<td style="text-align: left; width: 1O%">' . $modSettings['pledges_default_currency'] . $row['pledge_amount'] . '</td>
			<td style="text-align: left; width: 10%">' . $row['points_applied'] . '</td>
			<td style="text-align: left; width: 40%">' . $row['pledger_comment'] . '</td>
			<td style="text-align: right; width: 10%">' . $row['pledges_likes'] . '&nbsp;' . $context['like_link'] . '&nbsp;&nbsp;</td></tr>';
	}
	
	}else{
		
		$admin_expenses = AllowedTo('pledges_can_edit_admin');
		//Setup The Columns
		$extra_column = ($admin_expenses ? '<th scope="col">'. $txt['pledges_expense_options_title'] .'</th>' : '');
		
		echo '<div class="cat_bar">
		    <h3 class="catbg">'. $txt['pledges_trans_list_title'] .'</h3>
				</div>';
				
		echo '<div class="information"><table class="table_grid" Width="100%"><tbody>';
		
		echo $showpageindex;
		
		echo '<tr class="title_bar">
			<th scope="col">'. $txt['pledges_list_date_added'] .'</th>
			<th scope="col">'. $txt['pledges_trans_list_account'] .'</th>
			<th scope="col">'. $txt['pledges_trans_list_type'] .'</th>
			<th scope="col">'. $txt['pledges_trans_list_operation'] .'</th>
			<th scope="col">'. $txt['pledges_trans_list_amount'] .'</th>'
			.$extra_column.'
			</tr>';
		
		foreach($context['pledges_data'] as $row)
		{
			
		if (AllowedTo('pledges_can_edit_admin')){
			$del_image = '<a title="'.$txt['pledges_expense_delete_title'].'" href="'. $scripturl . '?action=pledge;area=pledge;sa=delspend;tr='.$row['transaction_id'].';'. $context['session_var'] . '=' . $context['session_id'] . '" onclick="return confirm(\'' . $txt['pledges_delete_message'] . '\');"><img src="'. $modSettings['pledges_root_url'].$modSettings['pledges_image_folder'] . 'delete.png" alt="Delete" align="absMiddle" /></a>';
		
		}
		$extra_row = ($admin_expenses ? '<td style="text-align: center; width: 7%"><a title="'.$txt['pledges_expense_edit_title'].'" href="'. $scripturl . '?action=pledge;area=pledge;sa=Seditspend;tr='.$row['transaction_id'].'"><img src="'. $modSettings['pledges_root_url'].$modSettings['pledges_image_folder'] . 'lists.png" alt="Edit" align="absMiddle" /></a>&nbsp;&nbsp;' . $del_image . '</td>' : '');
				
		echo  '<tr class="windowbg">
				<td style="text-align: left; width:' . ($admin_expenses  ? '14%' : '15%') . '">' . date("d M Y",$row['transaction_date']) . '</td>
				<td style="text-align: left; width:' . ($admin_expenses  ? '20%' : '22%') . '">' . $row['account_name'] . '</td>
				<td style="text-align: left; width:' . ($admin_expenses  ? '21%' : '23%') . '">' . $row['type_name'] . '</td>
				<td style="text-align: left; width:' . ($admin_expenses  ? '29%' : '30%') . '">' . $row['operation'] . '</td>
				<td style="text-align: center; width:' . ($admin_expenses  ? '9%' : '10%') . '">' . $modSettings['pledges_default_currency'] . $row['debit_amount'] . '</td>'
				. $extra_row. '
				</tr>';
		}
	}
	
			
	echo '</tbody></table>';
	echo $showpageindex . '</div>';

	if ($modSettings['pledges_show_stats'])
	{
	echo '<table border="0" cellpadding="0" cellspacing="0" width="100%" align="center">
  <tr>
    <td width="50%" valign="top">';

		// Show Top 5 members
			echo '
            <div class="cat_bar">
		<h3 class="catbg centertext">
        ', $txt['pledges_stats_top_doners_title'], '
        </h3>
		</div><table class="table_grid" align="center" width="99%">';
			
			echo '<tr class="title_bar" align="left">
			<th scope="col">'. $txt['pledges_list_member'] .'</th>
			<th scope="col">'. $modSettings['pledges_title'] .'</th>
			<th scope="col">'. $txt['pledges_stats_donated_title'] .'</th>
			</tr>';
			foreach($context['pledges_top_members']  as $tprow)
			{
			
			if (!empty($tprow['online_color']))
				$link = '<a href="' . $scripturl . '?action=profile;u=' . $tprow['member_id'] . '" style="color: ' . $tprow['online_color'] . ';">' . $tprow['real_name'] . '</a>';
			else
				$link = '<a href="' . $scripturl . '?action=profile;u=' . $tprow['member_id'] . '">' . $tprow['real_name'] . '</a>';
				
				echo '<tr class="windowbg">
				<td style="text-align: left; width:50%">'.$link. '</td>
				<td style="text-align: left; width:15%">'.$tprow['times_pledged']. '</td>
				<td style="text-align: left; width:35%">'.$modSettings['pledges_default_currency'].$tprow['total_pledged']. '</td>';
				echo '</tr>';
			}
			
			echo '</table>';

	echo '</td>';
		// Show Top 5 hits
	echo '<td width="50%" valign="top">';
		
		$strow = $context['pledges_stats'];

			echo '<div class="cat_bar">
		<h3 class="catbg centertext">
        ', $txt['pledges_stats_general_title'], '
        </h3></div><table class="table_grid" align="center" width="99%">';

			echo '<tr class="windowbg">
				<td style="text-align: left; width:50%">'.$txt['pledges_stats_total_pledges'].$strow['total_pledges']. '</td></tr>
				<tr class="windowbg">
				<td style="text-align: left; width:15%">'.$txt['pledges_stats_total_members'].$strow['members_pledged']. '</td></tr>
				<tr class="windowbg">
				<td style="text-align: left; width:35%">'.$txt['pledges_stats_total_pledged'].$modSettings['pledges_default_currency'].$strow['total_pledged']. '</td>';
				echo '</tr>';
			
			echo '</table>';

	echo '</td>';
	echo '</tr>
	</table>';
	}
}

function template_pledges_confirm()
{
	global $context, $txt, $modSettings, $sourcedir, $scripturl;
	
	$showpageindex = '';
	
			if ($context['pledges_total'] > $modSettings['pledges_items_per_page']){
			$context['page_index_top'] = constructPageIndex($scripturl . '?action=pledge;area=pledge;sa=index;ds=' . $dslink , $_GET['start'], $context['pledges_total'] , $modSettings['pledges_items_per_page']);
			$showpageindex = '<div class="pagesection">
            <span>'. $context['page_index_top']. '</span></div>';
		}
	
			echo '<div class="cat_bar">
		    <h3 class="catbg">' . $txt['pledges_confirm_pledges'] .'&nbsp;' . $modSettings['pledges_title'] . '</h3>
				</div>';
				
		echo '<div class="information"><table class="table_grid" Width="100%"><tbody>';
		
		echo $showpageindex;
		
		echo '<tr class="title_bar">
			<th scope="col">'. $txt['pledges_list_date_added'] .'</th>
			<th scope="col">'. $txt['pledges_list_member'] .'</th>
			<th scope="col">'. $txt['pledges_list_amount'] .'</th>
			<th scope="col">'. $txt['pledges_list_Comment'] .'</th>
			<th scope="col">'. $txt['pledges_form_paypal_name'] .'</th>
			<th scope="col">'. $txt['pledges_confirm_options_title'] .'</th>
			</tr>';
			
	foreach($context['pledges_data'] as $row)
	{

			if (!empty($row['online_color']))
				$link = '<a href="' . $scripturl . '?action=profile;u=' . $row['id_member'] . '" style="color: ' . $row['online_color'] . ';">' . $row['real_name'] . '</a>';
			else
				$link = '<a href="' . $scripturl . '?action=profile;u=' . $row['id_member'] . '">' . $row['real_name'] . '</a>';
		
			//This must be able to edit amount of pledge before saving
		$context['like_link'] = '<a title="'.$txt['pledges_confirm_Pledge_title'].'" href="'. $scripturl . '?action=pledge;area=pledge;sa=confirmedit;pl=' . $row['pledge_id'] . '"><img src="'. $modSettings['pledges_root_url'].$modSettings['pledges_image_folder'] . 'thumb_up.png" alt="Like Pledge" align="absMiddle" /></a>
		&nbsp;&nbsp;<a title="'.$txt['pledges_delete_Pledge_title'].'" href="'. $scripturl . '?action=pledge;area=pledge;sa=delete;pl=' . $row['pledge_id']. ';' . $context['session_var'] . '=' . $context['session_id'] . '" onclick="return confirm(\'' . $txt['pledges_delete_message'] . '\');"><img src="'. $modSettings['pledges_root_url'].$modSettings['pledges_image_folder'] . 'delete.png" alt="Like Pledge" align="absMiddle" /></a>';
				
	echo  '<tr class="windowbg">
			<td style="text-align: left; width: 15%">' . date("d M Y",$row['pledge_date']) . '</td>
			<td style="text-align: left; width: 15%">' . $link . '</td>
			<td style="text-align: left; width: 8%">' . $modSettings['pledges_default_currency'] . $row['pledge_amount'] . '</td>
			<td style="text-align: left; width: 40%">' . $row['pledger_comment'] . '</td>
			<td style="text-align: left; width: 14%">' . $row['pledge_paypal_name'] . '</td>
			<td style="text-align: center; width: 8%">' . $context['like_link'] . '</td></tr>';
	}
	
	echo '</tbody></table>';
	echo $showpageindex . '</div>';
}

function template_do_edit_confirm()
{
	global $context, $txt, $modSettings, $sourcedir, $scripturl;
	
	isAllowedTo('pledges_can_add_Pledge');
		
		$row = $context['pledge_edit_data'];
	
		echo '<br /><center><div id="confirm_pledges" class="tborder">';
	
		echo'<form method="post" enctype="multipart/form-data" name="addpledge" id="addpledge" action="' . $scripturl . '?action=pledge;area=pledge;sa=confirmsave;pl=' . $row['pledge_id'] . '" onsubmit="submitonce(this);">
		<div class="cat_bar">
		<h3 class="catbg">
		', $txt['pledges_confirm_Pledge_title'], '
		</h3>
	</div><div class="information">
	<table border="0" cellpadding="0" cellspacing="0" width="100%"><tbody>';

		echo '<tr>
			<td style="text-align: right; width: 30%; vertical-align: center"><strong>',$txt['pledges_list_amount'],'</strong>&nbsp;</td>
			<td style="text-align: left; width: 55%; vertical-align: center"><strong>',$modSettings['pledges_default_currency'],'</strong>&nbsp;<input type="text" size="10" name="amount" value="' . $row['pledge_amount'] . '" maxlength="8" /></td>
			
			<td style="text-align: center; width: 15%; vertical-align: center"><input type="submit" value="' . $txt['pledges_confirm_Pledge_button'] . '" name="submit" /></td>';
		  
		echo '</tr>
		</table></tbody>
		</form></div></div></center>';
	
}

function template_lists()
{
	global $context, $txt, $modSettings, $sourcedir, $scripturl;
	
	isAllowedTo('pledges_can_edit_admin');
	
	$used_accounts = array();
	
	require_once($sourcedir . '/Pledges/Pledges_module.php');
	
	//Get list of liked Pledges for current user
	$used_accounts = AccountsUsed();
	$used_transactions = TransactionsUsed();
	
	//This will create 2 lists side by side
	$accounts_list = '<div><table class="table_grid" Width="100%"><tbody>
					<tr class="title_bar">
					<th scope="col">'. $txt['pledges_lists_account_header'] .'</th>
					<th scope="col">'. $txt['pledges_lists_table_option'] .'</th></tr>
					<tr class="windowbg">
			<td colspan="2" style="text-align: center">
			<form method="post" enctype="multipart/form-data" name="addpledge" id="addpledge" action="' . $scripturl . '?action=pledge;area=pledge;sa=accadd" onsubmit="submitonce(this);">
			<table border="0" cellpadding="0" cellspacing="0" width="100%"><tbody>
			<tr>
			<td style="text-align: right; width: 30%; vertical-align: center"><strong>'.$txt['pledges_lists_account_new_title'].'</strong>&nbsp;</td>
			<td style="text-align: left; width: 55%; vertical-align: center"><strong><input type="text" size="30" name="account" value="" maxlength="60" /></td>
			<td style="text-align: center; width: 15%; vertical-align: center"><input type="submit" value="' . $txt['pledges_lists_account_add_button'] . '" name="submit" /></td>
			</tr>
		</table></tbody>
		</form>
			</td>
			</tr>';
					
	$types_list = '<div><table class="table_grid" Width="100%"><tbody>
					<tr class="title_bar">
					<th scope="col">'. $txt['pledges_lists_trans_types_header'] .'</th>
					<th scope="col">'. $txt['pledges_lists_table_option'] .'</th></tr>
					<tr class="windowbg">
			<td colspan="2" style="text-align: center">
			<form method="post" enctype="multipart/form-data" name="addpledge" id="addpledge" action="' . $scripturl . '?action=pledge;area=pledge;sa=typeadd" onsubmit="submitonce(this);">
			<table border="0" cellpadding="0" cellspacing="0" width="100%"><tbody>
			<tr>
			<td style="text-align: right; width: 30%; vertical-align: center"><strong>'.$txt['pledges_lists_trans_types_new_title'].'</strong>&nbsp;</td>
			<td style="text-align: left; width: 55%; vertical-align: center"><strong><input type="text" size="30" name="type" value="" maxlength="60" /></td>
			<td style="text-align: center; width: 15%; vertical-align: center"><input type="submit" value="' . $txt['pledges_lists_trans_types_add_button'] . '" name="submit" /></td>
			</tr>
		</table></tbody>
		</form>
			</td>
			</tr>';
	
	//Accounts
	foreach($context['pledges_accounts_data'] as $accrow)
	{
		//Check Not System Account or thast it's not in use
		if ($modSettings['pledges_account'] == $accrow['account_id'] || in_array($accrow['account_id'],$used_accounts))
			$del_image = '<a title="'.$txt['pledges_in_use_delete_message'].'" href="#"><img src="'. $modSettings['pledges_root_url'].$modSettings['pledges_image_folder'] . 'delete.png" alt="Delete" align="absMiddle" /></a>';
		else
			$del_image = '<a title="'.$txt['pledges_lists_account_delete_title'].'" href="'. $scripturl . '?action=pledge;area=pledge;sa=accdel;acc=' . $accrow['account_id'] . ';'. $context['session_var'] . '=' . $context['session_id'] . '" onclick="return confirm(\'' . $txt['pledges_delete_message'] . '\');"><img src="'. $modSettings['pledges_root_url'].$modSettings['pledges_image_folder'] . 'delete.png" alt="Delete" align="absMiddle" /></a>';
		
		$accounts_list .= '<tr class="windowbg"><td style="text-align: left; width: 85%">' . $accrow['account_name'] . '</td>
						   <td style="text-align: center; width: 15%"><a title="" href="'. $scripturl . '?action=pledge;area=pledge;sa=accedit;acc=' . $accrow['account_id'] . '"><img src="'. $modSettings['pledges_root_url'].$modSettings['pledges_image_folder'] . 'lists.png" alt="Like Pledge" align="absMiddle" /></a>&nbsp;&nbsp;' . $del_image . '</td></tr>';
	}
	
	//Transaction Types
	foreach($context['pledges_types_data'] as $tranrow)
	{
		//Check Not System Account or thast it's not in use
		if ($modSettings['pledges_transaction_type'] == $tranrow['type_id'] || in_array($tranrow['type_id'],$used_transactions))
			$del_image = '<a title="'.$txt['pledges_in_use_delete_message'].'" href="#"><img src="'. $modSettings['pledges_root_url'].$modSettings['pledges_image_folder'] . 'delete.png" alt="Delete" align="absMiddle" /></a>';
		else
			$del_image = '<a title="'.$txt['pledges_lists_trans_types_delete_title'].'" href="'. $scripturl . '?action=pledge;area=pledge;sa=typedel;typ=' . $tranrow['type_id'] . ';'. $context['session_var'] . '=' . $context['session_id'] . '" onclick="return confirm(\'' . $txt['pledges_delete_message'] . '\');"><img src="'. $modSettings['pledges_root_url'].$modSettings['pledges_image_folder'] . 'delete.png" alt="Delete" align="absMiddle" /></a>';		
		
		$types_list .= '<tr class="windowbg"><td style="text-align: left; width: 85%">' . $tranrow['type_name'] . '</td>
						   <td style="text-align: center; width: 15%"><a title="" href="'. $scripturl . '?action=pledge;area=pledge;sa=typedit;typ=' . $tranrow['type_id'] . '"><img src="'. $modSettings['pledges_root_url'].$modSettings['pledges_image_folder'] . 'lists.png" alt="Edit" align="absMiddle" /></a>&nbsp;&nbsp;'. $del_image. '</td></tr>';
	}
	
	//End Accounts Table
	$accounts_list .= '</table></tbody></div>';
	//End Transaction Types Table
	$types_list .= '</table></tbody></div>';
	
	echo '<center><br><table Width="50%"><tbody>
			<tr>
			<td style="text-align: center">' . $accounts_list . '</td>
			</tr>
			<tr>
			<td style="text-align: center"><hr></td>
			</tr>
			<tr>
			<td style="text-align: center">' . $types_list . '</td>
			</tr>
			</tr></table></tbody></center>';
	
}	

function template_edit_account_list()
{
	global $context, $txt, $scripturl;
	
	isAllowedTo('pledges_can_add_Pledge');
		
		$row = $context['edit_account_data'];
	
		echo '<br /><center><div id="edit_account" class="tborder">';
	
		echo'<form method="post" enctype="multipart/form-data" name="addpledge" id="addpledge" action="' . $scripturl . '?action=pledge;area=pledge;sa=doaccedit;acc=' . $row['account_id'] . '" onsubmit="submitonce(this);">
		<div class="cat_bar">
		<h3 class="catbg">
		', $txt['pledges_lists_account_do_edit_title'], '
		</h3>
	</div><div class="information">
	<table border="0" cellpadding="0" cellspacing="0" width="100%"><tbody>';

		echo '<tr>
			<td style="text-align: right; width: 30%; vertical-align: center"><strong>',$txt['pledges_lists_account_do_edit_title'],'</strong>&nbsp;</td>
			<td style="text-align: left; width: 55%; vertical-align: center"><input type="text" size="40" name="account_name" value="' . $row['account_name'] . '" maxlength="60" /></td>
			
			<td style="text-align: center; width: 15%; vertical-align: center"><input type="submit" value="' . $txt['pledges_lists_account_edit_button'] . '" name="submit" /></td>';
		  
		echo '</tr>
		</table></tbody>
		</form></div></div></center>';
	
}

function template_edit_transaction_list()
{
	global $context, $txt, $scripturl;
	
	isAllowedTo('pledges_can_add_Pledge');
		
		$row = $context['edit_transaction_data'];
	
		echo '<br /><center><div id="edit_account" class="tborder">';
	
		echo'<form method="post" enctype="multipart/form-data" name="addpledge" id="addpledge" action="' . $scripturl . '?action=pledge;area=pledge;sa=dotypedit;typ=' . $row['type_id'] . '" onsubmit="submitonce(this);">
		<div class="cat_bar">
		<h3 class="catbg">
		', $txt['pledges_lists_trans_types_Edit_title'], '
		</h3>
	</div><div class="information">
	<table border="0" cellpadding="0" cellspacing="0" width="100%"><tbody>';

		echo '<tr>
			<td style="text-align: right; width: 30%; vertical-align: center"><strong>',$txt['pledges_lists_trans_types_edit_button'],'</strong>&nbsp;</td>
			<td style="text-align: left; width: 55%; vertical-align: center"><input type="text" size="40" name="type_name" value="' . $row['type_name'] . '" maxlength="60" /></td>
			
			<td style="text-align: center; width: 15%; vertical-align: center"><input type="submit" value="' . $txt['pledges_lists_trans_types_edit_button'] . '" name="submit" /></td>';
		  
		echo '</tr>
		</table></tbody>
		</form></div></div></center>';
	
}

function template_add_expenses()
{
	global $scripturl, $txt, $context, $sourcedir;
	
	isAllowedTo('pledges_can_edit_admin');
	
	//Add Functions
	require_once($sourcedir . '/Pledges/Pledges_module.php');
	
		echo'<form method="post"  enctype="multipart/form-data" name="adderror" id="adderror" action="' . $scripturl . '?action=pledge;area=pledge;sa=addspend" onsubmit="submitonce(this);">
		<div class="cat_bar">
		<h3 class="catbg">
		', $txt['pledges_expense_title'], '
		</h3>
		</div><div class="information">
		<table border="0" cellpadding="0" cellspacing="0" width="100%">
			<tr class="windowbg2">
				<td width="28%" align="right"><b>' . $txt['pledges_add_expense_accounts'] .'</b>:&nbsp;</td>
				<td width="72%">'.GetAccountsList(0).'</td>
			</tr>
			<tr class="windowbg2">
				<td width="28%" align="right"><b>' . $txt['pledges_expense_new_account'] .'</b>:&nbsp;</td>
				<td width="72%"><input type="text" size="45" name="new_account" value="" maxlength="60" />&nbsp;'
				.$txt['pledges_expense_new_account_desc'].'</td>
			</tr>
			<tr class="windowbg2">
				<td width="28%" align="right"><b>' . $txt['pledges_add_expense_transaction'] .'</b>:&nbsp;</td>
				<td width="72%">'.GetTransactionTypesList(0).'</td>
			</tr>
			<tr class="windowbg2">
				<td width="28%" align="right"><b>' . $txt['pledges_expense_new_type'] .'</b>:&nbsp;</td>
				<td width="72%"><input type="text" size="45" name="new_type" value="" maxlength="60" />&nbsp;'
				.$txt['pledges_expense_new_account_desc'].'</td>
			</tr>
			<tr class="windowbg2">
				<td width="28%" align="right"><b>' . $txt['pledges_expense_operation'] .'</b>:&nbsp;</td>
				<td width="72%"><input type="text" size="60" name="comment" value="" maxlength="100" /></td>
			</tr>
			<tr class="windowbg2">
				<td width="28%" align="right"><b>' . $txt['pledges_expense_cost'] .'</b>:&nbsp;</td>
				<td width="72%"><input type="text" size="10" name="debit" value="" maxlength="8" /></td>
			</tr>';

	echo '<tr class="windowbg2">
	<td colspan="2" align="center">
	<input type="submit" value="' . $txt['pledges_add_expense_button'] . '" name="submit" />
	</td>
  </tr>
</table>
</form></div>';

}

function template_edit_expenses()
{
	global $scripturl, $txt, $context, $sourcedir;
	
	isAllowedTo('pledges_can_edit_admin');
	
	$row = $context['edit_expence_data'];
	
	//Add Functions
	require_once($sourcedir . '/Pledges/Pledges_module.php');

		echo '<br><div class="tborder">';
	
		echo'<form method="post"  enctype="multipart/form-data" name="editspend" id="editspend" action="' . $scripturl . '?action=pledge;area=pledge;sa=editspend;tr='.$row['transaction_id'].';dt='.$row['transaction_date'].'" onsubmit="submitonce(this);">
		<div class="cat_bar">
		<h3 class="catbg centertext">
		', $txt['pledges_expense_title'], '
		</h3>
		</div><div class="information">
		<table border="0" cellpadding="0" cellspacing="0" width="100%">
			<tr class="windowbg2">
				<td width="28%" align="right"><b>' . $txt['pledges_add_expense_accounts'] .'</b>:&nbsp;</td>
				<td width="72%">'.GetAccountsList($row['transaction_acc_id']).'</td>
			</tr>
			<tr class="windowbg2">
				<td width="28%" align="right"><b>' . $txt['pledges_expense_new_account'] .'</b>:&nbsp;</td>
				<td width="72%"><input type="text" size="45" name="new_account" value="" maxlength="60" />&nbsp;'
				.$txt['pledges_expense_new_account_desc'].'</td>
			</tr>
			<tr class="windowbg2">
				<td width="28%" align="right"><b>' . $txt['pledges_add_expense_transaction'] .'</b>:&nbsp;</td>
				<td width="72%">'.GetTransactionTypesList($row['transaction_type_id']).'</td>
			</tr>
			<tr class="windowbg2">
				<td width="28%" align="right"><b>' . $txt['pledges_expense_new_type'] .'</b>:&nbsp;</td>
				<td width="72%"><input type="text" size="45" name="new_type" value="" maxlength="60" />&nbsp;'
				.$txt['pledges_expense_new_account_desc'].'</td>
			</tr>
			<tr class="windowbg2">
				<td width="28%" align="right"><b>' . $txt['pledges_expense_operation'] .'</b>:&nbsp;</td>
				<td width="72%"><input type="text" size="60" name="comment" value="'.$row['operation'].'" maxlength="100" /></td>
			</tr>
			<tr class="windowbg2">
				<td width="28%" align="right"><b>' . $txt['pledges_expense_cost'] .'</b>:&nbsp;</td>
				<td width="72%"><input type="text" size="10" name="debit" value="'.$row['debit_amount'].'" maxlength="8" /></td>
			</tr>';

	echo '<tr class="windowbg2">
	<td colspan="2" align="center">
	<input type="submit" value="' . $txt['pledges_edit_expense_button'] . '" name="submit" />
	</td>
  </tr>
</table>
</form></div></div>';

}

function template_show_goals()
{
	global $scripturl, $txt, $context, $sourcedir, $modSettings;

	//Add Functions
	require_once($sourcedir . '/Pledges/Pledges_module.php');
	
	$all_goals_values = Array();
	$line = '';
	$admin_edit = '';
	$extra_column = '';
	$del_image = '';
	$extra_row = '';
	
	if (AllowedTo('pledges_can_edit_admin')){
	
		echo'<form method="post"  enctype="multipart/form-data" name="adderror" id="adderror" action="' . $scripturl . '?action=pledge;area=pledge;sa=addgoals" onsubmit="submitonce(this);">
		<div class="cat_bar">
		<h3 class="catbg">
		', $txt['pledges_add_goals_button'], '
		</h3>
		</div><div class="information">
		<table border="0" cellpadding="0" cellspacing="0" width="100%">
			<tr class="windowbg2">
				<td width="28%" align="right"><b>' . $txt['pledges_goals_desc'] .'</b>:&nbsp;</td>
				<td width="72%"><input type="text" size="30" name="goal_name" value="" maxlength="30" />&nbsp;'
				.$txt['pledges_goals_desc_title'].'</td>
			</tr>
			<tr class="windowbg2">
				<td width="28%" align="right"><b>' . $txt['pledges_add_year_title'] .'</b>:&nbsp;</td>
				<td width="72%">'.GetGoalYears(0).'</td>
			</tr>
			<tr class="windowbg2">
				<td width="28%" align="right"><b>' . $txt['pledges_goals_types'] .'</b>:&nbsp;</td>
				<td width="72%"><select name=goal_type value="">Goal Type</option>
					<option value="1"selected="selected">',$txt['pledges_goals_types_yearly'],'</option>
					<option value="12">',$txt['pledges_goals_types_monthly'],'</option>
					<option value="4">',$txt['pledges_goals_types_quartly'],'</option>
					<option value="2">',$txt['pledges_goals_types_twice_yearly'],'</option></select></td>
			</tr>
			<tr class="windowbg2">
				<td width="28%" align="right"><b>' . $txt['pledges_goals_amount_title'] .'</b>:&nbsp;</td>
				<td width="72%"><input type="text" size="10" name="goal_amount" value="" maxlength="8" /></td>
			</tr>';

	echo '<tr class="windowbg2">
	<td colspan="2" align="center">
	<input type="submit" value="' . $txt['pledges_add_goals_button'] . '" name="submit" />
	</td>
  </tr>
</table>
</form></div>';
	}

//Now Add The Goals List
if ($context['total_goals'])
{
		$all_goals_values = GetAllGoalValues();
		
		$admin_edit = AllowedTo('pledges_can_edit_admin');		
		//Setup The Columns
		$extra_column = ($admin_edit ? '<th scope="col">'. $txt['pledges_expense_options_title'] .'</th>' : '');
		
		echo '<div class="cat_bar">
		    <h3 class="catbg">'. $txt['pledges_goals_title'] .'</h3>
				</div>';
				
		echo '<div class="information"><table class="table_grid" Width="100%"><tbody>';
		
		echo '<tr class="title_bar">
			<th scope="col">'. $txt['pledges_goals_desc'] .'</th>
			<th scope="col">'. $txt['pledges_goal_year_title'] .'</th>
			<th scope="col">'. $txt['pledges_goal_type_title'] .'</th>
			<th scope="col">'. $txt['pledges_goal_values_title'] .'</th>
			<th scope="col">'. $txt['pledges_goal_amount_title'] .'</th>'
			.$extra_column.
			'</tr>';
			
		foreach($context['goals_data'] as $row)
		{	
			switch ($row['goal_type_id']){
			case 1: //Yearly
				$goal_type = $txt['pledges_goals_types_yearly'];
				break;
			case 12: //Monthly Array
				$goal_type = $txt['pledges_goals_types_monthly'];
				break;
			case 4: //Quartly Array
				$goal_type = $txt['pledges_goals_types_quartly'];
				break;
			case 2: //6 monthly
				$goal_type = $txt['pledges_goals_types_twice_yearly'];
				break;
			}
			
		if (AllowedTo('pledges_can_edit_admin')){
			$del_image = '<a title="'.$txt['pledges_delete_goals_title'].'" href="'. $scripturl . '?action=pledge;area=pledge;sa=delgoal;goal='.$row['goal_id'].';'. $context['session_var'] . '=' . $context['session_id'] . '" onclick="return confirm(\'' . $txt['pledges_delete_goal_message'] . '\');"><img src="'. $modSettings['pledges_root_url'].$modSettings['pledges_image_folder'] . 'delete.png" alt="Delete" align="absMiddle" /></a>';
			$extra_row = '<td style="text-align: center; width: 6%"><a title="'.$txt['pledges_edit_goals_title'].'" href="'. $scripturl . '?action=pledge;area=pledge;sa=seditgoal;goal='.$row['goal_id'].'"><img src="'. $modSettings['pledges_root_url'].$modSettings['pledges_image_folder'] . 'lists.png" alt="Edit" align="absMiddle" /></a>&nbsp;&nbsp;' . $del_image . '</td>';
		}
		
			echo  '<tr class="windowbg">
			<td style="text-align: left; width:' . ($admin_edit  ? '17%' : '19%') . '">' . $row['goal_name'] . '</td>
			<td style="text-align: left; width: 5%">' . $row['goal_year'] . '</td>
			<td style="text-align: left; width: 10%">' . $goal_type . '</td>
			<td style="text-align: left; width:' . ($admin_edit  ? '54%' : '55%') . '">' . GetGoalValues($row['goal_id'],$all_goals_values, $row['goal_type_id']) . '</td>
			<td style="text-align: left; width:' . ($admin_edit  ? '8%' : '11%') . '">' . $modSettings['pledges_default_currency'].$row['goal_amount'] . '</td>'
			. $extra_row.
				'</tr>';
		}
		
		echo '</tbody></table></div>';
	}else{
		//No Goals Set
		echo  '<div class="cat_bar">
		    <h3 class="catbg">'. $txt['pledges_goals_title'] .'</h3>
				</div><div class="roundframe centertext">'.$txt['pledges_no_goals_set'].'</div>';
	}

}

function template_edit_goals()
{
	
	global $scripturl, $txt, $context;
	
	isAllowedTo('pledges_can_edit_admin');
	
	$row = $context['edit_goal_data'];
	
		echo'<form method="post"  enctype="multipart/form-data" name="adderror" id="adderror" action="' . $scripturl . '?action=pledge;area=pledge;sa=editgoal;goal='.$row['goal_id'].'" onsubmit="submitonce(this);">
		<div class="cat_bar">
		<h3 class="catbg">
		', $txt['pledges_edit_goals_title'], '
		</h3>
		</div><div class="information">
		<table border="0" cellpadding="0" cellspacing="0" width="100%">
			<tr class="windowbg2">
				<td width="28%" align="right"><b>' . $txt['pledges_goals_desc'] .'</b>:&nbsp;</td>
				<td width="72%"><input type="text" size="30" name="goal_name" value="'.$row['goal_name'].'" maxlength="30" />&nbsp;'
				.$txt['pledges_goals_desc_title'].'</td>
			</tr>
			<tr class="windowbg2">
				<td width="28%" align="right"><b>' . $txt['pledges_goals_amount_title'] .'</b>:&nbsp;</td>
				<td width="72%"><input type="text" size="10" name="goal_amount" value="'.$row['goal_amount'].'" maxlength="8" /></td>
			</tr>';

	echo '<tr class="windowbg2">
	<td colspan="2" align="center">
	<input type="submit" value="' . $txt['pledges_edit_goals_button'] . '" name="submit" />
	</td>
  </tr>
</table>
</form></div>';
	}
