<?php


Event::listen('successfully_logged_in', function()
{
	/**
	 * Если корзина неавторизованного пользователя не пуста,
	 * записать ее содержимое в базу данных, затерев старую.
	 * Также удалить корзину из сессии
	 */
	$items = \Carts\Models\Cart::getItems(true);
	Session::forget('cart');

	if ($items)
	{
		 \Carts\Models\Cart::where_user_id(uid())->delete();

		 foreach ($items as $item)
		 {
			$item->user_id = uid();
			$item->save();
		 }
	}

});

Event::listen('A new user was created', function($user, $password)
{
	/**
	 * @test
	 */
	$data = \Config::get('email.registration');

	\Message::to($user->email)
		->from($data['from'], $data['sender'])
		->subject($data['subject'])
		->body(View::make($data['content'], compact('user', 'password')))
		->send();
});

Event::listen('A new password recovery request was sent', function($user)
{
	/**
	 * @test all this shit! :)
	 */
	$data = \Config::get('email.password_recovery');

	$token = $user->recovery_token;
	$confirmationUrl = \URL::to_route('auth_default_password_recovery_confirmation', compact('token'));
	$email = $user->email;

	\Message::to($email)
		->from($data['from'], $data['sender'])
		->subject($data['subject'])
		->body(View::make($data['content'], compact('user', 'confirmationUrl')))
		->send();
});

Event::listen('A password was successfully recovered', function($user, $newPassword)
{
	/**
	 * @test all this shit! :)
	 */
	$data = \Config::get('email.password_recovery_success');
	$email = $user->email;

	\Message::to($email)
		->from($data['from'], $data['sender'])
		->subject($data['subject'])
		->body(View::make($data['content'], compact('user', 'newPassword')))
		->send();
});