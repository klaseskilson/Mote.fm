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

	return $str;
}
