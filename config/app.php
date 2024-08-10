<?php

return [

	# ruta de logs  de la aplicacion
	'ruta_logs' => [
		'general' =>   dirname( dirname(__FILE__)) . '/logs/',
		'error_log' =>  dirname( dirname(__FILE__)). '/logs/',
	],

	# 0 - no depuración
	# 1 - depuración
	'debug' => 1,

	# development or production
	'environment' => 'development',

	'salt' => 'WQ5+VEy&*m&6qw12Ra!',

];