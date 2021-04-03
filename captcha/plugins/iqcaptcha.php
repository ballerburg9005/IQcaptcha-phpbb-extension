<?php
/**
*
* This file was originally part of the phpBB Forum Software package.
*
* @copyright Ballerburg9005 <https://github.com/ballerburg9005>
* @copyright (c) phpBB Limited <https://www.phpbb.com>
* @license GNU General Public License, version 2 (GPL-3.0)
*
* For full copyright and license information, please see
* the docs/CREDITS.txt file.
*
*/

namespace ballerburg9005\iqcaptcha\captcha\plugins;

require_once(__DIR__ . "/../../vendor/google/iqcaptcha/src/autoload.php");

class iqcaptcha extends \phpbb\captcha\plugins\captcha_abstract
{

	private $iqcaptcha_siteverify; 
	private $iqcaptcha_server;
	private $iqcaptcha_server_secure;

//	var $iqcaptcha_server = 'http://iqcaptcha.us.to/repo/api';
//	var $iqcaptcha_server_secure = 'http://iqcaptcha.us.to/repo/api'; // class constants :(
//	var $iqcaptcha_siteverify = 'http://iqcaptcha.us.to/repo/verify.php';

	var $response;

	/**
	* Constructor
	*/
	public function __construct()
	{
		global $request;
		$this->iqcaptcha_server = $request->is_secure() ? $this->iqcaptcha_server_secure : $this->iqcaptcha_server;

		$url = filter_input(INPUT_SERVER, "REQUEST_SCHEME")."://" 
				. (strlen(filter_input(INPUT_SERVER, 'PHP_AUTH_USER')) > 0 
					? filter_input(INPUT_SERVER, 'PHP_AUTH_USER') . ":" . filter_input(INPUT_SERVER, 'PHP_AUTH_PW') . "@"
					: "")
				. filter_input(INPUT_SERVER, 'HTTP_HOST')
				. ":" .  filter_input(INPUT_SERVER, "SERVER_PORT");

		$this->iqcaptcha_siteverify = $url . "/" . substr(__DIR__, strlen(filter_input(INPUT_SERVER, 'DOCUMENT_ROOT'))) . "/../../IQcaptcha/verify.php";
		$this->iqcaptcha_server =  "/" . substr(__DIR__, strlen(filter_input(INPUT_SERVER, 'DOCUMENT_ROOT'))) . "/../../IQcaptcha/api";
		$this->iqcaptcha_server_secure =   "/" . substr(__DIR__, strlen(filter_input(INPUT_SERVER, 'DOCUMENT_ROOT'))) . "/../../IQcaptcha/api";

	}

	function init($type)
	{
		global $user, $request;

		$user->add_lang_ext('ballerburg9005/iqcaptcha', 'captcha_iqcaptcha');
		parent::init($type);
		$this->response = $request->variable('g-iqcaptcha-response', '');
	}

	public function is_available()
	{
		global $config, $user;
		$user->add_lang_ext('ballerburg9005/iqcaptcha', 'captcha_iqcaptcha');
		return (isset($config['iqcaptcha_pubkey']) && !empty($config['iqcaptcha_pubkey']));
	}

	/**
	*  API function
	*/
	function has_config()
	{
		return true;
	}

	static public function get_name()
	{
		return 'CAPTCHA_IQCAPTCHA';
	}

	/**
	* This function is implemented because required by the upper class, but is never used for reCaptcha.
	*/
	function get_generator_class()
	{
		throw new \Exception('No generator class given.');
	}

	function acp_page($id, $module)
	{
		global $config, $template, $user, $phpbb_log, $request;

		$captcha_vars = array(
			'iqcaptcha_pubkey'				=> 'IQCAPTCHA_PUBKEY',
			'iqcaptcha_privkey'				=> 'IQCAPTCHA_PRIVKEY',
		);

		$module->tpl_name = '@ballerburg9005_iqcaptcha/captcha_iqcaptcha_acp';
		$module->page_title = 'ACP_VC_SETTINGS';
		$form_key = 'acp_captcha';
		add_form_key($form_key);

		$submit = $request->variable('submit', '');

		if ($submit && check_form_key($form_key))
		{
			$captcha_vars = array_keys($captcha_vars);
			foreach ($captcha_vars as $captcha_var)
			{
				$value = $request->variable($captcha_var, '');
				if ($value)
				{
					$config->set($captcha_var, $value);
				}
			}

			$phpbb_log->add('admin', $user->data['user_id'], $user->ip, 'LOG_CONFIG_VISUAL');
			trigger_error($user->lang['CONFIG_UPDATED'] . adm_back_link($module->u_action));
		}
		else if ($submit)
		{
			trigger_error($user->lang['FORM_INVALID'] . adm_back_link($module->u_action));
		}
		else
		{
			foreach ($captcha_vars as $captcha_var => $template_var)
			{
				$var = (isset($_REQUEST[$captcha_var])) ? $request->variable($captcha_var, '') : ((isset($config[$captcha_var])) ? $config[$captcha_var] : '');
				$template->assign_var($template_var, $var);
			}

			$template->assign_vars(array(
				'CAPTCHA_PREVIEW'	=> $this->get_demo_template($id),
				'CAPTCHA_NAME'		=> $this->get_service_name(),
				'U_ACTION'			=> $module->u_action,
			));

		}
	}

	// not needed
	function execute_demo()
	{
	}

	// not needed
	function execute()
	{
	}

	function get_template()
	{
		global $config, $user, $template, $phpbb_root_path, $phpEx;

		if ($this->is_solved())
		{
			return false;
		}
		else
		{
			$contact_link = phpbb_get_board_contact_link($config, $phpbb_root_path, $phpEx);
			$explain = $user->lang(($this->type != CONFIRM_POST) ? 'CONFIRM_EXPLAIN' : 'POST_CONFIRM_EXPLAIN', '<a href="' . $contact_link . '">', '</a>');

			$template->assign_vars(array(
				'IQCAPTCHA_SERVER'			=> $this->iqcaptcha_server,
				'IQCAPTCHA_PUBKEY'			=> isset($config['iqcaptcha_pubkey']) ? $config['iqcaptcha_pubkey'] : '',
				'S_IQCAPTCHA_AVAILABLE'		=> self::is_available(),
				'S_CONFIRM_CODE'			=> true,
				'S_TYPE'					=> $this->type,
				'L_CONFIRM_EXPLAIN'			=> $explain,
			));

			return '@ballerburg9005_iqcaptcha/captcha_iqcaptcha.html';
		}
	}

	function get_demo_template($id)
	{
		return $this->get_template();
	}

	function get_hidden_fields()
	{
		$hidden_fields = array();

		// this is required for posting.php - otherwise we would forget about the captcha being already solved
		if ($this->solved)
		{
			$hidden_fields['confirm_code'] = $this->code;
		}
		$hidden_fields['confirm_id'] = $this->confirm_id;
		return $hidden_fields;
	}

	function uninstall()
	{
		$this->garbage_collect(0);
	}

	function install()
	{
		return;
	}

	function validate()
	{
		if (!parent::validate())
		{
			return false;
		}
		else
		{
			return $this->iqcaptcha_check_answer();
		}
	}

	/**
	* Calls an HTTP POST function to verify if the user's guess was correct
	*
	* @return bool|string Returns false on success or error string on failure.
	*/
	function iqcaptcha_check_answer()
	{
		global $config, $user;

		//discard spam submissions
		if ($this->response == null || strlen($this->response) == 0)
		{
			return $user->lang['IQCAPTCHA_INCORRECT'];
		}

		$iqcaptcha = new \IQCaptcha\IQCaptcha($config['iqcaptcha_privkey'], null, $this->iqcaptcha_siteverify);
		$result = $iqcaptcha->verify($this->response, $user->ip);
	

		if ($result->isSuccess())
		{
			$this->solved = true;
			return false;
		}
		else
		{
			return $user->lang['IQCAPTCHA_INCORRECT'];
		}
	}
}
