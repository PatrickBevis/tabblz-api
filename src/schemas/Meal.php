<?php namespace Schemas;

class Meal{

	const COLUMNS = [
		'Id_meal' => ['type' => 'varchar(255)', 'nullable' => '0' , 'default' => ''],
		'title' => ['type' => 'varchar(50)', 'nullable' => '1' , 'default' => ''],
		'number' => ['type' => 'int(11)', 'nullable' => '1' , 'default' => ''],
		'price' => ['type' => 'decimal(15,2)', 'nullable' => '1' , 'default' => ''],
		'release_date' => ['type' => 'datetime', 'nullable' => '1' , 'default' => ''],
		'is_deleted' => ['type' => 'tinyint(1)', 'nullable' => '0' , 'default' => '0'],
		'Id_command' => ['type' => 'varchar(255)', 'nullable' => '1' , 'default' => ''],
		'Id_appuser' => ['type' => 'varchar(255)', 'nullable' => '1' , 'default' => '']
	];

}