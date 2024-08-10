<?php

namespace libraries;

class Config
{

	public static $items = [];

	public static function load( array $filepath = [])
	{
		static::$items = include(  dirname( dirname(__FILE__)).'/' . $filepath[0] . '/' . $filepath[1] . '.php');
	}

	public static function get( string $key = null)
	{

		$input = explode('.', $key);
		$filepath[0] = $input[0];
		$filepath[1] = $input[1];
		unset( $input[0]);
		unset( $input[1]);
		$key = implode( '.', $input);

		static::load( $filepath);

		if ( !empty( $key))
		{
			return static::$items[ $key];
		}

		return static::$items;

	}

}