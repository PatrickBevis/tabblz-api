<?php namespace Schemas;

class Appuser{

	const COLUMNS = [
		'Id_appuser' => ['type' => 'varchar(255)', 'nullable' => '0' , 'default' => ''],
		'login' => ['type' => 'varchar(255)', 'nullable' => '1' , 'default' => ''],
		'password' => ['type' => 'varchar(255)', 'nullable' => '1' , 'default' => ''],
		'pseudo' => ['type' => 'varchar(255)', 'nullable' => '1' , 'default' => ''],
		'is_deleted' => ['type' => 'tinyint(1)', 'nullable' => '0' , 'default' => '0'],
		'Id_role' => ['type' => 'varchar(255)', 'nullable' => '1' , 'default' => ''],
		'Id_account' => ['type' => 'varchar(255)', 'nullable' => '1' , 'default' => '']
	];

}