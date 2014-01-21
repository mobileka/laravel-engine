<?php

use \Carts\Models\Cart;

function numberOfCartItems()
{
	return Cart::numberOfItems();
}

function getCartItems()
{
	return Cart::getItems();
}

function cartTotal()
{
	return money(Cart::total());
}