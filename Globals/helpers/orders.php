<?php

use Orders\Models\Order;

function shippingMethods()
{
	return Order::$shippingMethods;
}

function paymentMethods()
{
	return Order::$paymentMethods;
}

function orderStatuses()
{
	return Order::$statuses;
}