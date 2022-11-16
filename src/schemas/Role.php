<?php namespace Schemas;

class Role{

	const COLUMNS = [
		'Id_role' => ['type' => 'varchar(255)', 'nullable' => '0' , 'default' => ''],
		'title' => ['type' => 'varchar(255)', 'nullable' => '1' , 'default' => ''],
		'is__deleted' => ['type' => 'tinyint(1)', 'nullable' => '1' , 'default' => '']
	];

}