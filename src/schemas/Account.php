<?php namespace Schemas;

class Account{

	const COLUMNS = [
		'Id_account' => ['type' => 'varchar(255)', 'nullable' => '0' , 'default' => ''],
		'fullname' => ['type' => 'varchar(255)', 'nullable' => '1' , 'default' => ''],
		'country' => ['type' => 'varchar(255)', 'nullable' => '1' , 'default' => ''],
		'adress' => ['type' => 'varchar(255)', 'nullable' => '1' , 'default' => ''],
		'cp' => ['type' => 'int(11)', 'nullable' => '1' , 'default' => ''],
		'city' => ['type' => 'varchar(255)', 'nullable' => '1' , 'default' => ''],
		'login' => ['type' => 'varchar(255)', 'nullable' => '1' , 'default' => ''],
		'password' => ['type' => 'varchar(255)', 'nullable' => '1' , 'default' => ''],
		'is_deleted' => ['type' => 'tinyint(1)', 'nullable' => '1' , 'default' => ''],
		'Id_appUser' => ['type' => 'varchar(255)', 'nullable' => '1' , 'default' => '']
	];

}