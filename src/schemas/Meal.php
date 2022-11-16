<?php namespace Schemas;

class Meal{

	const COLUMNS = [
		'Id_meal' => ['type' => 'varchar(255)', 'nullable' => '0' , 'default' => ''],
		'title' => ['type' => 'varchar(255)', 'nullable' => '1' , 'default' => ''],
		'price' => ['type' => 'decimal(15,2)', 'nullable' => '1' , 'default' => ''],
		'release_date' => ['type' => 'datetime', 'nullable' => '1' , 'default' => ''],
		'is_deleted' => ['type' => 'tinyint(1)', 'nullable' => '1' , 'default' => ''],
		'Id_command' => ['type' => 'varchar(255)', 'nullable' => '1' , 'default' => ''],
		'Id_appUser' => ['type' => 'varchar(255)', 'nullable' => '1' , 'default' => '']
	];

}