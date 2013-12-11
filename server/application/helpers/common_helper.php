<?php

/*
	COMMON HELPER!
	simple helpful stuff
*/

/**
 * generate a random string
 * @param  integer $length 		the length of the string
 * @param  boolean $large 		capital letters?
 * @param  boolean $small 		small letters?
 * @param  boolean $numeric 	numbers?
 * @return string  the 			string
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

/**
 * a simple way to make sure all the emails we send are formatted in a similar way
 * @param  [type] $subject
 * @param  [type] $message
 * @return [type]
 */
function format_mail($subject, $message)
{
	// load CI into a new var, so that we can use som handy functions
	$CI =& get_instance();

	$msg = '<html>
				<head>
					<style>

					</style>
				</head>
				<body>
					<header>
						<h2>
							'.$subject.'
						</h2>
					</header>
					<main>
						'.$message.'
						<p>
							Cheers,<br />
							The '.$CI->config->item('contact_name').'
						</p>
					</main>
					<footer>
						<p>
							Need to get in touch with the '.$CI->config->item('contact_name').'?
							Send us an email at <a href="mailto:'.$CI->config->item('contact_mail').'">'.$CI->config->item('contact_mail').'</a>.
						</p>
					</footer>
				</body>
			</html>';

	return $msg;
}
