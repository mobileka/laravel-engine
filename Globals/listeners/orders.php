<?php

\Event::listen('The order status has been changed', function($order, $originalStatus, $currentStatus)
{
	$result = array('result' => true);

	$errorMessage = \Lang::findLine(
		'default',
		'unacceptable_status_update',
		array('from' => $originalStatus, 'to' => $currentStatus)
	);

	if ($originalStatus === 'placed')
	{
		switch ($currentStatus)
		{
			case 'canceled':
				\Event::fire('The order was canceled', array($order));
			break;

			case 'confirmed':
				\Event::fire('The order was confirmed', array($order));
			break;

			case 'finished':
				\Event::fire('The order was finished', array($order));
			break;
		}
	}

	if ($originalStatus === 'confirmed')
	{
		switch ($currentStatus)
		{
			case 'placed':
				return $result = array('result' => false, 'message' => $errorMessage);
			break;

			case 'canceled':
				\Event::fire('The order was canceled', array($order));
			break;

			case 'finished':
				\Event::fire('The order was finished', array($order));
			break;
		}
	}

	if ($originalStatus === 'canceled')
	{
		switch ($currentStatus)
		{
			case 'placed':
			case 'confirmed':
			case 'finished':
				\Event::fire('The order was recovered', array($order));
			break;
		}
	}

	if ($originalStatus === 'finished')
	{
		switch ($currentStatus)
		{
			case 'placed':
			case 'confirmed':
			case 'canceled':
				return $result = array('result' => false, 'message' => $errorMessage);
			break;
		}
	}

	return $result;
});

\Event::listen('A new order was placed', function($order)
{
	//очищаем корзину
	\Carts\Models\Cart::clear();

	//находим заказ вместе с данными о товарах
	$order = \Orders\Models\Order::with('orderItems')->find($order->id);

	/**
	 * @test
	 */
	$data = \Config::get('email.order_placed');

	\Message::to($order->email)
		->from($data['from'], $data['sender'])
		->subject($data['subject'])
		->body(View::make($data['content'], compact('order')))
		->send();

	//Если пользователь заказывает не для себя, то на его мыло тоже отправить письмо с подтверждением
	if ($order->email != user()->email)
	{
		\Message::to(user()->email)
			->from($data['from'], $data['sender'])
			->subject($data['subject'])
			->body(View::make($data['content'], compact('order')))
			->send();
	}
});

\Event::listen('The order was confirmed', function($order)
{
	//находим заказ вместе с данными о товарах
	$order = \Orders\Models\Order::with('orderItems')->find($order->id);

	/**
	 * @test
	 */
	$data = \Config::get('email.order_confirmed');

	\Message::to($order->email)
		->from($data['from'], $data['sender'])
		->subject($data['subject'])
		->body(View::make($data['content'], compact('order')))
		->send();

	//Если пользователь заказывает не для себя, то на его мыло тоже отправить письмо с подтверждением
	/*if ($order->email != user()->email)
	{
		\Message::to(user()->email)
			->from($data['from'], $data['sender'])
			->subject($data['subject'])
			->body(View::make($data['content'], compact('order')))
			->send();
	}*/
});

\Event::listen('The order was finished', function($order)
{
	//находим заказ вместе с данными о товарах
	$order = \Orders\Models\Order::with('orderItems')->find($order->id);

	/**
	 * @test
	 */
	$data = \Config::get('email.order_finished');

	\Message::to($order->email)
		->from($data['from'], $data['sender'])
		->subject($data['subject'])
		->body(View::make($data['content'], compact('order')))
		->send();

	//Если пользователь заказывает не для себя, то на его мыло тоже отправить письмо с подтверждением
	/*if ($order->email != user()->email)
	{
		\Message::to(user()->email)
			->from($data['from'], $data['sender'])
			->subject($data['subject'])
			->body(View::make($data['content'], compact('order')))
			->send();
	}*/
});

\Event::listen('The order was canceled', function($order)
{
	//находим заказ вместе с данными о товарах
	$order = \Orders\Models\Order::with('orderItems')->find($order->id);

	//Возвращаем stock
	foreach ($order->orderItems as $orderItem)
	{
		$orderItem->model->quantity += $orderItem->quantity;
		$orderItem->model->save();
	}

	/**
	 * @test
	 */
	$data = \Config::get('email.order_canceled');

	\Message::to($user->email)
		->from($data['from'], $data['sender'])
		->subject($data['subject'])
		->body(View::make($data['content'], compact('order')))
		->send();

	//Если пользователь заказывает не для себя, то на его мыло тоже отправить письмо с подтверждением
	/*if ($order->email != user()->email)
	{
		\Message::to(user()->email)
			->from($data['from'], $data['sender'])
			->subject($data['subject'])
			->body(View::make($data['content'], compact('order')))
			->send();
	}*/
});

\Event::listen('The order was recovered', function($order)
{
	//находим заказ вместе с данными о товарах
	$order = \Orders\Models\Order::with('orderItems')->find($order->id);

	/**
	 * @test
	 */
	$data = \Config::get('email.order_recovered');

	\Message::to($order->email)
		->from($data['from'], $data['sender'])
		->subject($data['subject'])
		->body(View::make($data['content'], compact('order')))
		->send();

	//Если пользователь заказывает не для себя, то на его мыло тоже отправить письмо с подтверждением
	/*if ($order->email != user()->email)
	{
		\Message::to(user()->email)
			->from($data['from'], $data['sender'])
			->subject($data['subject'])
			->body(View::make($data['content'], compact('order')))
			->send();
	}*/
});