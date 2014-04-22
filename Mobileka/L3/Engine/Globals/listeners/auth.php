<?php

Event::listen('successfully_logged_in', function()
{
	Acl::clearLoginAttempts(user()->username);
});

Event::listen('unsuccessful_login_attempt', function($credentials)
{
	Acl::incLoginAttempts($credentials['username']);
});