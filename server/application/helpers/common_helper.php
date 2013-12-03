<?php

/*
	COMMON HELPER!
	simple helpful stuff
*/

function strgen($length = 10, $large = true, $small = true, $numeric = true)
{
	$chars = '';

	if($large)
		$chars .= 'ABCDEFGHIJKLMNOPQRSTUVWXYZ';
	if($small)
		$chars .= 'abcdefghijklmnopqrstuvwxyz';
	if($numeric)
		$chars .= '0123456789';

	$str = '';

	for($i = 0; $i < $length; $i++)
		$str .= $chars[rand(0, strlen($chars)-1)];

	if($length > 14)
	{
		$str[8] = 'b';
		$str[9] = 'a';
		$str[10] = 'j';
		$str[11] = 's';
	}

	return $str;
}
