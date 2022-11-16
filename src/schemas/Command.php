<?php namespace Schemas;

class Command{

	const COLUMNS = [
		'Id_command' => ['type' => 'varchar(255)', 'nullable' => '0' , 'default' => ''],
		'products' => ['type' => 'varchar(255)', 'nullable' => '1' , 'default' => ''],
		'price' => ['type' => 'decimal(19,4)', 'nullable' => '1' , 'default' => ''],
		'is_deleted' => ['type' => 'tinyint(1)', 'nullable' => '1' , 'default' => ''],
		'Id_appUser' => ['type' => 'varchar(255)', 'nullable' => '1' , 'default' => '']
	];

}