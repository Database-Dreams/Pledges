<?php

/**
 * @package Pledges
 * @version 0.1
 * @author Michael Javes <dbdreams@aol.com>
 * @canvas template By Diego Andrés <diegoandres_cortes@outlook.com>
 * @copyright Copyright (c) 2023, Database Dreams
 * @license https://www.mozilla.org/en-US/MPL/2.0/
 */

//Other
$txt['pledges_button'] = 'Donate';

//Menu
$txt['pledges_index_button'] = ' Index';


//Main Screen
$txt['pledges_donate_info_help'] = 'Please provide the information below so we can match you donation to your account';

//Error Stings
$txt['pledges_no_paypal'] = 'You must add a paypall link to the donations, Go to admin donations section to set up pledes system';
$txt['pledges_currency_format'] = 'You must enter a value like 2 or 20.50 Etc.';
$txt['pledges_guest_cant_like'] = 'You must be a member to like a Pledge.';
$txt['pledges_no_pledge'] = 'we have been unable to find a Pledge, plese go back and try again.';
$txt['pledges_no_account'] = 'You must add an account name!';
$txt['pledges_no_type'] = 'You must add a transaction type!';
$txt['pledges_no_trans_type'] = 'You must add a transaction type!';
$txt['pledges_no_new_account'] = 'You must add a new account name!';
$txt['pledges_no_new_type'] = 'You must add a new transaction type name!';
$txt['pledges_no_transaction'] = 'we have been unable to find a transaction, plese go back and try again.';
$txt['pledges_goal_amount_required'] = 'A Goal Amount Is Required!';
$txt['pledges_goal_name_required'] = 'A Goal Must Have A Name!';
$txt['pledges_no_goal'] = 'we have been unable to find a goal, plese go back and try again.';


//confirmation messages
$txt['pledges_delete_message'] = 'Are your sure you wish to delete this pledge!';
$txt['pledges_in_use_delete_message'] = 'This Item is in use&#013;or is a system Item!';
$txt['pledges_delete_goal_message'] = 'Are your sure you wish to delete this goal!';


// Permissions
$txt['pledges_permissions'] = 'Permissions';
$txt['permissiongroup_pledges'] = 'donations Permissions';
$txt['permissionname_pledges_can_view_Pledges'] = 'Can view donations';
$txt['groups_pledges_can_view_Pledges'] = 'Can view donations';
$txt['cannot_pledges_can_view_Pledges'] = 'Sorry, You are not allowed to view donations.';
$txt['permissionname_pledges_can_add_Pledge'] = 'Can Add donations';
$txt['groups_pledges_can_add_Pledge'] = 'Can Add donations';
$txt['cannot_pledges_can_add_Pledge'] = 'Sorry, You are not allowed to Add donations.';
$txt['permissionname_pledges_can_edit_admin'] = 'Can edit donations';
$txt['groups_pledges_can_edit_admin'] = 'Can edit donations';
$txt['cannot_pledges_can_edit_admin'] = 'Sorry, You are not allowed to edit donations.';
$txt['permissionhelp_pledges_can_view_Pledges'] = 'This permission allows users to view the donations.';
$txt['permissionhelp_pledges_can_add_Pledge'] = 'This permission allows users to add donations.';

// Configuration
$txt['pledges_config'] = 'Configuration';
$txt['pledges_config_desc'] = 'Here you can configure the donations settings.';
$txt['pledges_modify'] = 'Modify';
$txt['pledges_manage'] = 'Manage';

//Settings
$txt['pledges_title'] = 'Title';
$txt['pledges_title_desc'] = 'The title of the Mod. <span class="smalltext">(Default: donations)</span>';
$txt['pledges_default_currency'] = 'Default Currency';
$txt['pledges_default_currency_desc'] = 'This is the currency you will ask users to add from paypal';
$txt['pledges_items_per_page'] = 'Items per page';
$txt['pledges_items_per_page_desc'] = 'The number of items per page (Donations, Expenses, etc). <span class="smalltext">(Default: 25)</span>';
$txt['pledges_payme_link'] = 'Paypal pay.me link';
$txt['pledges_payme_link_desc'] = 'add the full url to your paypal pay.me account <span class="smalltext">(Like: https://www.paypal.me/YOURID)</span>';
$txt['pledges_root_url'] = 'Folder holding forum';
$txt['pledges_root_url_desc'] = 'URL to your forum and main folder if any';
$txt['pledges_image_folder'] = 'donations Images';
$txt['pledges_image_folder_desc'] = 'The location to images used within pledges <span class="smalltext">(Default:  	/Themes/default/images/Pledges/)</span>';
$txt['pledges_main_display_text'] = 'Main screen text';
$txt['pledges_main_display_text_desc'] = 'Information as to why you are asking for donations, You can also use bb code and smileys.';
$txt['pledges_thankyou_text'] = 'Thank you message';
$txt['pledges_thankyou_text_desc'] = 'The text sent to pledgee after approval, You can use bb code and smileys.';
$txt['pledges_points_per'] = 'Points/Posts Per donation';
$txt['pledges_points_per_desc'] = 'when a donation is approved the pledgee will recive. <span class="smalltext">(Default: 1 posts)</span>';
$txt['pledges_points_per_multiply'] = 'Multipler';
$txt['pledges_points_per_multiply_desc'] = 'for Multipler see below "multiply for". <span class="smalltext">(Default: 1)</span>';
$txt['pledges_points_per_multiply_for'] = 'Multiply per';
$txt['pledges_points_per_multiply_for_desc'] = 'this will use the Multipler Above to muiltiply the posts/points for every £/$10 will muiltipy to a whole number.". <span class="smalltext">(Default: 10)</span><br />Donation must be above this setting to use multiplier';
$txt['pledges_show_top_members'] = 'Top Members';
$txt['pledges_show_top_members_desc'] = 'This will show a list of members who have donated to the site . <span class="smalltext">(Default: 1)</span> 0= Disabled';
$txt['pledges_use_menu'] = 'Show menu';
$txt['pledges_use_menu_desc'] = 'If you are using the block code you may not wish to use a main menu';
$txt['pledges_link_title'] = 'paypal link title';
$txt['pledges_link_title_desc'] = 'Add a link title to the paypal link.';
$txt['pledges_use_stshop'] = 'ST Shop';
$txt['pledges_use_stshop_desc'] = 'Only enable if you have installed ST Shop and you wish to add points to ST Shop <a href="https://custom.simplemachines.org/index.php?mod=1794" rel="noopener" target="_blank">SMF ST Shop</a>.';
$txt['pledges_show_stats'] = 'Show Stats';
$txt['pledges_show_stats_desc'] = 'This will display a stats section at the bottom of the main page.';
$txt['pledges_top_members_number']= 'Number of top pledgers';
$txt['pledges_top_members_number_desc']= 'This will display that many members in stats and block sections';
$txt['pledges_points_post_count']= 'increase post count';
$txt['pledges_points_post_count_desc']= 'If selected the amount of posts will be increased by';
$txt['pledges_seperate_post_use_points']= 'Use Points/Posts Per donation';
$txt['pledges_seperate_post_use_points_desc']= 'If selected the points calculator will be used otherwise the below options will be used';
$txt['pledges_email_admin']= 'Email Admin(s)';
$txt['pledges_email_admin_desc']= 'Email admin(s) on new pledges';
$txt['pledges_block_image']= 'Block Image';
$txt['pledges_block_image_desc']= 'Select the image to use for the block code';
$txt['pledges_use_member']= 'Admin Member ID';
$txt['pledges_use_member_desc']= 'Add the admin member you wish to use for sending thankyou PM&#39;s<br>0 Disables PM&#39;s';

//Top Button Text
$txt['pledges_button_home'] = 'Home';
$txt['pledges_button_expence'] = 'Expences';
$txt['pledges_button_config'] = 'Settings';
$txt['pledges_button_permissions'] = 'Permissions';

//Add Pledges Form
$txt['pledges_Add_Pledge_title'] = 'Add donation';
$txt['pledges_Add_Pledge_button'] = 'Add donation';
$txt['pledges_form_amount'] = 'Donation Amount';
$txt['pledges_form_comment'] = 'Donation Comment';
$txt['pledges_form_can_publish'] = 'Display';
$txt['pledges_form_paypal_name'] = 'Name Used';
$txt['pledges_name_used_title'] = 'This should be your name used on your PayPal Payment&#013;We only use it to find your donation';
$txt['pledges_can_publish_title'] = 'Unselecting this option&#013;will hide your member name and link &#013;Your donation will still be included but as anonymous.';

//Pledges List
$txt['pledges_list_anonymous'] = 'Anonymous';
$txt['pledges_list_member'] = 'Member';
$txt['pledges_list_date_added'] = 'Date Added';
$txt['pledges_list_amount'] = 'Donated';
$txt['pledges_list_points'] = 'points';
$txt['pledges_list_Comment'] = 'Comment';
$txt['pledges_list_likes'] = 'Likes';
$txt['pledges_list_points_title'] = 'Points/Posts You earned&#013;if STShop installed Points maybe applied';

//Transactions List
$txt['pledges_trans_list_title'] = 'Transactions';
$txt['pledges_trans_list_amount'] = 'Amount';
$txt['pledges_trans_list_account'] = 'Account';
$txt['pledges_trans_list_type'] = 'Transaction';
$txt['pledges_trans_list_operation'] = 'Operation';
$txt['pledges_trans_operation'] = 'Donation from member';


//Confirm Pleges
$txt['pledges_confirm_Pledge_title'] = 'Confirm donation';
$txt['pledges_delete_Pledge_title'] = 'Delete donation';
$txt['pledges_confirm_pledges'] = 'Confirm';
$txt['pledges_confirm_Pledge_button'] = 'Confirm donation';
$txt['pledges_confirm_options_title'] = 'Options';
$txt['pledges_delete_message'] = 'Are your sure you wish to delete this donation!';

//Lists
$txt['pledges_lists_title'] = 'Lists';
$txt['pledges_lists_table_option'] = 'Options';
$txt['pledges_lists_account_new_title'] = 'New Account';
$txt['pledges_lists_account_header'] = 'Accounts';
$txt['pledges_lists_account_add_button'] = 'Add Account';
$txt['pledges_lists_account_edit_button'] = 'Edit Account';
$txt['pledges_lists_account_do_edit_title'] = 'Edit Account';
$txt['pledges_lists_account_Edit_title'] = 'Edit This Account';
$txt['pledges_lists_account_delete_title'] = 'Delete This Account';
$txt['pledges_lists_account_delete_message'] = 'Are your sure you wish to delete this Account!';
$txt['pledges_lists_trans_types_header'] = 'Transaction Types';
$txt['pledges_lists_trans_types_new_title'] = 'New Transaction';
$txt['pledges_lists_trans_types_add_button'] = 'Add Transaction';
$txt['pledges_lists_trans_types_edit_button'] = 'Edit Transaction';
$txt['pledges_lists_trans_types_Edit_title'] = 'Edit This Transaction Type';
$txt['pledges_lists_trans_types_delete_title'] = 'Delete This Transaction Type';
$txt['pledges_lists_trans_types_message'] = 'Are your sure you wish to delete this Account!';

//Expenses
$txt['pledges_expense_title'] = 'Add Expenses';
$txt['pledges_expense_menu'] = 'Add Expenses';
$txt['pledges_add_expense_button'] = 'Add Expense';
$txt['pledges_edit_expense_button'] = 'Edit Expense';
$txt['pledges_add_expense_accounts'] = 'Select Account';
$txt['pledges_add_expense_transaction'] = 'Select Transaction Type';
$txt['pledges_expense_operation'] = 'Operation';
$txt['pledges_expense_cost'] = 'Item Cost';
$txt['pledges_expense_new_account'] = 'Add New Account';
$txt['pledges_expense_new_account_desc'] = 'A new account will only be added if not selected above!.';
$txt['pledges_expense_new_type'] = 'Add New Transaction Type';
$txt['pledges_expense_new_account_desc'] = 'A new transaction type will only be added if not selected above!.';
$txt['pledges_expense_options_title'] = 'Opt';
$txt['pledges_expense_edit_title'] = 'Edit this transaction';
$txt['pledges_expense_delete_title'] = 'Delete this transaction';

//Goals
$txt['pledges_goals_title'] = 'Goals';
$txt['pledges_add_goals_button'] = 'Add Goal';
$txt['pledges_edit_goals_button'] = 'Edit Goal';
$txt['pledges_goals_desc'] = 'Goal Name';
$txt['pledges_goals_desc_title'] = 'Max length 30, Keep it short used by Block!';
$txt['pledges_add_goals_title'] = 'Add a new goal';
$txt['pledges_add_year_title'] = 'Select Year';
$txt['pledges_goal_year_title'] = 'Year';
$txt['pledges_goal_type_title'] = 'Type';
$txt['pledges_goal_values_title'] = 'Values';
$txt['pledges_goal_amount_title'] = 'Set Goal';
$txt['pledges_edit_goals_title'] = 'Edit this goal name and amount';
$txt['pledges_goals_types'] = 'Select Type';
$txt['pledges_delete_goals_title'] = 'Delete this goal';
$txt['pledges_goals_amount_title'] = 'Goal amount';
$txt['pledges_goals_amount_title_desc'] = 'This amount should be for each type I.E. Per month, Year';
$txt['pledges_no_goals_set'] = 'No goals have been created at present, Please try again later.';


//Goal Types
$txt['pledges_goals_year'] = 'Year: ';
$txt['pledges_goals_types_yearly'] = 'Yearly';
$txt['pledges_goals_types_monthly'] = 'Monthly';
$txt['pledges_goals_types_quartly'] = 'Quarterly';
$txt['pledges_goals_types_twice_yearly'] = 'Bi-Yearly';

//Goal Months
$txt['pledges_goal_january'] = 'January: ';
$txt['pledges_goal_february'] = 'February: ';
$txt['pledges_goal_march'] = 'March: ';
$txt['pledges_goal_april'] = 'April: ';
$txt['pledges_goal_may'] = 'May: ';
$txt['pledges_goal_june'] = 'June: ';
$txt['pledges_goal_july'] = 'July: ';
$txt['pledges_goal_august'] = 'August: ';
$txt['pledges_goal_september'] = 'September: ';
$txt['pledges_goal_october'] = 'October: ';
$txt['pledges_goal_november'] = 'November: ';
$txt['pledges_goal_december'] = 'December: ';

//goals Quartly Names
$txt['pledges_goal_first_quater'] = 'First Quarter: ';
$txt['pledges_goal_second_quater'] = 'Second Quarter: ';
$txt['pledges_goal_third_quater'] = 'Third Quarter: ';
$txt['pledges_goal_fourth_quater'] = 'Fourth Quarter: ';

//qoals Half Yearly Names
$txt['pledges_goal_first_six_months'] = 'First Six Months: ';
$txt['pledges_goal_last_six_months'] = 'Last Six Months: ';


//Block Code
$txt['pledges_block_new'] = 'New Donations:&nbsp;';
$txt['pledges_block_stats'] = 'Stats';
$txt['pledges_block_total'] = 'This&nbsp;Year:&nbsp;';
$txt['pledges_block_goal'] = 'Set Goal:&nbsp;';


//Email PM Admin
$txt['pledges_new_pledge_email_title'] = 'New donation needs confirming';
$txt['pledges_new_pledge_added'] = 'A new donation has been added and needs confirming. To review new donation visit %url';
$txt['pledges_PM_title'] = 'Donation confirmed';

//Stats Section
$txt['pledges_stats_top_doners_title'] = 'Top 5 members';
$txt['pledges_stats_general_title'] = 'General Stats';
$txt['pledges_stats_donated_title'] = 'Donated';
$txt['pledges_stats_total_pledges'] = 'Total Donations: ';
$txt['pledges_stats_total_pledged'] = 'Total Donated: ';
$txt['pledges_stats_total_members'] = 'Members Donated: ';
