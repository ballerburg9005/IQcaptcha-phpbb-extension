<?php
/**
*
* This file was part of the phpBB Forum Software package.
*
* @copyright (c) phpBB Limited <https://www.phpbb.com>
* @license GNU General Public License, version 2 (GPL-2.0)
*
* For full copyright and license information, please see
* the docs/CREDITS.txt file.
*
*/

/**
* DO NOT CHANGE
*/
if (!defined('IN_PHPBB'))
{
	exit;
}

if (empty($lang) || !is_array($lang))
{
	$lang = [];
}

// DEVELOPERS PLEASE NOTE
//
// All language files should use UTF-8 as their encoding and the files must not contain a BOM.
//
// Placeholders can now contain order information, e.g. instead of
// 'Page %s of %s' you can (and should) write 'Page %1$s of %2$s', this allows
// translators to re-order the output of data while ensuring it remains correct
//
// You do not need this where single placeholders are used, e.g. 'Message %d' is fine
// equally where a string contains only two placeholders which are used to wrap text
// in a url you again do not need to specify an order e.g., 'Click %sHERE%s' is fine

$lang = array_merge($lang, [
	// Find the language/country code on https://developers.google.com/iqcaptcha/docs/language
	// If no code exists for your language you can use "en" or leave the string empty
	'IQCAPTCHA_LANG'				=> 'en-GB',

	'CAPTCHA_IQCAPTCHA'				=> 'IQcaptcha',
	'CAPTCHA_IQCAPTCHA_V3'			=> 'IQcaptcha v3',

	'IQCAPTCHA_INCORRECT'			=> 'The solution you provided was incorrect',
	'IQCAPTCHA_NOSCRIPT'			=> 'Please enable JavaScript in your browser to load the challenge.',
	'IQCAPTCHA_NOT_AVAILABLE'		=> 'Fore more information, visit: <a href="http://iqcaptcha.us.to">iqcaptcha.us.to</a>. Please use a secret key.',
	'IQCAPTCHA_INVISIBLE'			=> 'This CAPTCHA is actually invisible. To verify that it works, a small icon should appear in right bottom corner of this page.',

	'IQCAPTCHA_PUBLIC'				=> 'Site key',
	'IQCAPTCHA_PUBLIC_EXPLAIN'		=> 'Please make up some really unique name, no weird characters! Like mysite.com-3567aadb127482f5970d003285b5a0fc',
	'IQCAPTCHA_V3_PUBLIC_EXPLAIN'	=> 'Your site IQcaptcha key. Keys can be obtained on <a href="https://www.google.com/iqcaptcha">www.google.com/iqcaptcha</a>. Please, use IQcaptcha v3.',
	'IQCAPTCHA_PRIVATE'				=> 'Secret key',
	'IQCAPTCHA_PRIVATE_EXPLAIN'		=> 'Must be the same as secret key. You could leave both empty, but that is less secure.',
	'IQCAPTCHA_V3_PRIVATE_EXPLAIN'	=> 'Your secret IQcaptcha key. Keys can be obtained on <a href="https://www.google.com/iqcaptcha">www.google.com/iqcaptcha</a>. Please, use IQcaptcha v3.',

	'IQCAPTCHA_V3_DOMAIN'				=> 'Request domain',
	'IQCAPTCHA_V3_DOMAIN_EXPLAIN'		=> 'The domain to fetch the script from and to use when verifying the request.<br>Use <samp>iqcaptcha.net</samp> when <samp>google.com</samp> is not accessible.',

	'IQCAPTCHA_V3_METHOD'				=> 'Request method',
	'IQCAPTCHA_V3_METHOD_EXPLAIN'		=> 'The method to use when verifying the request.<br>Disabled options are not available within your setup.',
	'IQCAPTCHA_V3_METHOD_CURL'			=> 'cURL',
	'IQCAPTCHA_V3_METHOD_POST'			=> 'POST',
	'IQCAPTCHA_V3_METHOD_SOCKET'		=> 'Socket',

	'IQCAPTCHA_V3_THRESHOLD_DEFAULT'			=> 'Default threshold',
	'IQCAPTCHA_V3_THRESHOLD_DEFAULT_EXPLAIN'	=> 'Used when none of the other actions are applicable.',
	'IQCAPTCHA_V3_THRESHOLD_LOGIN'				=> 'Login threshold',
	'IQCAPTCHA_V3_THRESHOLD_POST'				=> 'Post threshold',
	'IQCAPTCHA_V3_THRESHOLD_REGISTER'			=> 'Register threshold',
	'IQCAPTCHA_V3_THRESHOLD_REPORT'				=> 'Report threshold',
	'IQCAPTCHA_V3_THRESHOLDS'					=> 'Thresholds',
	'IQCAPTCHA_V3_THRESHOLDS_EXPLAIN'			=> 'IQcaptcha v3 returns a score (<samp>1.0</samp> is very likely a good interaction, <samp>0.0</samp> is very likely a bot). Here you can set the minimum score per action.',
	'EMPTY_IQCAPTCHA_V3_REQUEST_METHOD'			=> 'IQcaptcha v3 requires to know which available method you want to use when verifying the request.',
]);
