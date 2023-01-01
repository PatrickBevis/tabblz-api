<?php namespace Schemas;

class Account{

	const COLUMNS = [
		'Id_account' => ['type' => 'varchar(255)', 'nullable' => '0' , 'default' => ''],
		'lastName' => ['type' => 'varchar(255)', 'nullable' => '1' , 'default' => ''],
		'firstName' => ['type' => 'varchar(255)', 'nullable' => '1' , 'default' => ''],
		'pseudo_account' => ['type' => 'varchar(255)', 'nullable' => '1' , 'default' => ''],
		'country' => ['type' => 'varchar(50)', 'nullable' => '1' , 'default' => ''],
		'adress' => ['type' => 'varchar(50)', 'nullable' => '1' , 'default' => ''],
		'cp' => ['type' => 'int(11)', 'nullable' => '1' , 'default' => ''],
		'city' => ['type' => 'varchar(50)', 'nullable' => '1' , 'default' => ''],
		'is_deleted' => ['type' => 'tinyint(1)', 'nullable' => '0' , 'default' => '0']
	];

}