<?php namespace Schemas;

class Appuser{

	const COLUMNS = [
		'Id_appUser' => ['type' => 'varchar(255)', 'nullable' => '0' , 'default' => ''],
		'fullname' => ['type' => 'varchar(255)', 'nullable' => '1' , 'default' => ''],
		'country' => ['type' => 'varchar(255)', 'nullable' => '1' , 'default' => ''],
		'is_deleted' => ['type' => 'tinyint(1)', 'nullable' => '1' , 'default' => ''],
		'Id_role' => ['type' => 'varchar(255)', 'nullable' => '1' , 'default' => '']
	];

}