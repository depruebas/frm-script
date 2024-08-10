<?php

use libraries\PDO;
use modules\CommonClass;

class Test extends CommonClass
{

	public function Init( ?string $data)
	{

		echo EOF . "I'm Init method" . EOF . EOF;

		$params_ins['table'] = 'city';
		$params_ins['fields'] = [
			'city' => 'BBBBB',
			'country_id' => 18
		];

		$rows_ins = PDO::Insert( $params_ins);

		dc ( $rows_ins);

		$params['query'] = "select * from city order by last_update  desc limit 10 ";
    	$params['params'] =  [];
    	$rows = PDO::Execute( $params);

		dc($rows, true);

		echo "hola";
	}

}