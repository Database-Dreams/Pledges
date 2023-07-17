<?php

/**
 * @package Pledges
 * @version 0.1
 * @author Michael Javes <dbdreams@aol.com>
 * @canvas template By Diego Andrés <diegoandres_cortes@outlook.com>
 * @copyright Copyright (c) 2023, Database Dreams
 * @license https://www.mozilla.org/en-US/MPL/2.0/
 */

	if (file_exists(dirname(__FILE__) . '/SSI.php') && !defined('SMF'))
		require_once(dirname(__FILE__) . '/SSI.php');

	elseif (!defined('SMF'))
		exit('<b>Error:</b> Cannot install - please verify you put this in the same place as SMF\'s index.php.');

	global $smcFunc, $context;

	db_extend('packages');

	if (empty($context['uninstalling']))
	{
		// pledges DONE
		$tables[] = [
			'table_name' => '{db_prefix}pledges',
			'columns' => [
				[
					'name' => 'pledge_id',
					'type' => 'mediumint',
					'size' => 8,
					'auto' => true,
					'unsigned' => true,
				],
				[
					'name' => 'member_id',
					'type' => 'mediumint',
					'size' => 8,
					'unsigned' => true,
					'not_null' => true,
				],
				[
					'name' => 'pledge_date',
					'type' => 'int',
					'size' => 10,
					'unsigned' => true,
					'not_null' => true,
				],
				[
					'name' => 'pledge_amount',
					'type' => 'decimal',
					'size' => '10,2',
					'unsigned' => true,
					'default' => '0.00',
					'not_null' => true,
				],
				[
					'name' => 'pledger_comment',
					'type' => 'varchar',
					'size' => 150,
					'default' => 'null',
				],
				[
					'name' => 'can_publish',
					'type' => 'tinyint',
					'size' => 2,
				],
				[
					'name' => 'pledge_confirmed',
					'type' => 'tinyint',
					'size' => 2,
				],
				[
					'name' => 'points_applied',
					'type' => 'mediumint',
					'unsigned' => true,
					'size' => 6,
				],
				[
					'name' => 'pledges_likes',
					'type' => 'mediumint',
					'unsigned' => true,
					'size' => 8,
				],
				[
					'name' => 'pledge_comments',
					'type' => 'smallint',
					'size' => 5,
				],
				[
					'name' => 'pledge_paypal_name',
					'type' => 'varchar',
					'size' => 30,
					'not_null' => true,
				],
			],
			'indexes' => [
				[
					'type' => 'primary',
					'columns' => ['pledge_id']
				],
				[
					'type' => 'index',
					'columns' => ['pledge_id', 'member_id', 'pledge_date']
				],
			],
			'if_exists' => 'ignore',
			'error' => 'fatal',
			'parameters' => [],
		];

		// Pledges Accounts DONE
		$tables[] = [
			'table_name' => '{db_prefix}pledge_accounts',
			'columns' => [
				[
					'name' => 'account_id',
					'type' => 'mediumint',
					'size' => 5,
					'auto' => true,
					'unsigned' => true,
				],
				[
					'name' => 'account_name',
					'type' => 'varchar',
					'size' => 60,
					'not_null' => true,
				],
			],
			'indexes' => [
				[
					'type' => 'primary',
					'columns' => ['account_id']
				],
			],
			'if_exists' => 'ignore',
			'error' => 'fatal',
			'parameters' => [],
		];

		// pledge_goals DONE
		$tables[] = [
			'table_name' => '{db_prefix}pledge_goals',
			'columns' => [
				[
					'name' => 'goal_id',
					'type' => 'mediumint',
					'size' => 8,
					'auto' => true,
					'unsigned' => true,
				],
				[
					'name' => 'goal_name',
					'type' => 'varchar',
					'size' => 30,
					'not_null' => true,

				]
				[
					'name' => 'goal_year',
					'type' => 'smallint',
					'size' => 4,
					'unsigned' => true,
				],
				[
					'name' => 'goal_type_id',
					'type' => 'tinyint',
					'size' => 2,
					'unsigned' => true,
					'default' => 0,
				],
				[
					'name' => 'goal_amount',
					'type' => 'decimal',
					'size' => '10,2',
					'unsigned' => true,
					'default' => '0.00',
				],
			],
			'indexes' => [
				[
					'type' => 'primary',
					'columns' => ['goal_id']
				],
				[
					'type' => 'index',
					'columns' => ['goal_year', 'goal_type_id']
				],
			],
			'if_exists' => 'ignore',
			'error' => 'fatal',
			'parameters' => [],
		];
		
		// pledge goals values DONE
		$tables[] = [
			'table_name' => '{db_prefix}pledge_goals_values',
			'columns' => [
				[
					'name' => 'value_id',
					'type' => 'mediumint',
					'size' => 8,
					'auto' => true,
					'unsigned' => true,
				],
				[
					'name' => 'goal_id',
					'type' => 'mediumint',
					'size' => 8,
					'unsigned' => true,
					'not_null' => true,
				],
				[
					'name' => 'unit_id',
					'type' => 'smallint',
					'size' => 3,
					'unsigned' => true,
					'not_null' => true,
				],
				[
					'name' => 'unit_amount',
					'type' => 'decimal',
					'size' => '10,2',
					'unsigned' => true,
					'default' => '0.00',
					'not_null' => true,
				],
			],
			'indexes' => [
				[
					'type' => 'primary',
					'columns' => ['value_id']
				],
				[
					'type' => 'index',
					'columns' => ['goal_id', 'unit_id']
				],
			],
			'if_exists' => 'ignore',
			'error' => 'fatal',
			'parameters' => [],
		];

		// pledge transactions DONE
		$tables[] = [
			'table_name' => '{db_prefix}pledge_transactions',
			'columns' => [
				[
					'name' => 'transaction_id',
					'type' => 'int',
					'size' => 8,
					'auto' => true,
					'unsigned' => true,
				],
				[
					'name' => 'transaction_date',
					'type' => 'int',
					'size' => 10,
					'unsigned' => true,
				],
				[
					'name' => 'transaction_acc_id',
					'type' => 'mediumint',
					'size' => 5,
					'unsigned' => true,
				],
				[
					'name' => 'transaction_type_id',
					'type' => 'mediumint',
					'size' => 5,
					'unsigned' => true,
				],
				[
					'name' => 'operation',
					'type' => 'varchar',
					'size' => 100,
				],
				[
					'name' => 'debit_amount',
					'type' => 'decimal',
					'size' => '10,2',
					'unsigned' => true,
					'default' => '0.00',
				],
				[
					'name' => 'credit_amount',
					'type' => 'decimal',
					'size' => '10,2',
					'unsigned' => true,
					'default' => '0.00',
				],
				[
					'name' => 'member_id',
					'type' => 'mediumint',
					'size' => 8,
					'unsigned' => true,
				],
			],
			'indexes' => [
				[
					'type' => 'primary',
					'columns' => ['transaction_id']
				],
				[
					'type' => 'index',
					'columns' => ['transaction_date', 'transaction_acc_id', 'transaction_type_id', 'credit_amount', 'credit_amount', 'member_id']
				],
			],
			'if_exists' => 'ignore',
			'error' => 'fatal',
			'parameters' => [],
		];

		// Pledges transaction types DONE
		$tables[] = [
			'table_name' => '{db_prefix}pledge_transaction_types',
			'columns' => [
				[
					'name' => 'type_id',
					'type' => 'mediumint',
					'size' => 8,
					'auto' => true,
					'unsigned' => true,
				],
				[
					'name' => 'type_name',
					'type' => 'varchar',
					'size' => 60,
				],
			],
			'indexes' => [
				[
					'type' => 'primary',
					'columns' => ['type_id']
				],
			],
			'if_exists' => 'ignore',
			'error' => 'fatal',
			'parameters' => [],
		];

		// Installing
		foreach ($tables as $table)
		$smcFunc['db_create_table']($table['table_name'], $table['columns'], $table['indexes'], $table['parameters'], $table['if_exists'], $table['error']);

	}