<?php namespace Schemas;

class Comment{

	const COLUMNS = [
		'Id_comment' => ['type' => 'varchar(255)', 'nullable' => '0' , 'default' => ''],
		'content' => ['type' => 'varchar(50)', 'nullable' => '1' , 'default' => ''],
		'title' => ['type' => 'varchar(50)', 'nullable' => '1' , 'default' => ''],
		'release_date' => ['type' => 'datetime', 'nullable' => '1' , 'default' => ''],
		'is_approved' => ['type' => 'tinyint(1)', 'nullable' => '1' , 'default' => ''],
		'is_deleted' => ['type' => 'tinyint(1)', 'nullable' => '0' , 'default' => '0'],
		'Id_meal' => ['type' => 'varchar(255)', 'nullable' => '1' , 'default' => ''],
		'Id_appUser' => ['type' => 'varchar(255)', 'nullable' => '1' , 'default' => '']
	];

}