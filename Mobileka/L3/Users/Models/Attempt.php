<?php namespace Mobileka\L3\Users\Models;

use Mobileka\L3\Engine\Laravel\Base\Model;

class Attempt extends Model {
	public static $table = 'user_login_attempts';

	public static function getByUsernameAndIp($username, $ip)
	{
		return static::where_username($username)->where_ip($ip)->first();
	}
}
