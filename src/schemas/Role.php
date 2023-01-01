<?php namespace Schemas;

class Role{

	const COLUMNS = [
		'Id_role' => ['type' => 'varchar(255)', 'nullable' => '0' , 'default' => ''],
		'title' => ['type' => 'varchar(50)', 'nullable' => '1' , 'default' => ''],
		'weight' => ['type' => 'int(11)', 'nullable' => '1' , 'default' => ''],
		'is_deleted' => ['type' => 'tinyint(1)', 'nullable' => '0' , 'default' => '0']
	];

}