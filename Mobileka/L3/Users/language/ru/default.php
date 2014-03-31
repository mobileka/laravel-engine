<?php

return array(
	'controllers' => array(
		'admin' => array(
			'default' => array(
				'titles' => array(
					'index' => 'Список пользователей',
					'view' => 'Просмотр профиля пользователя',
					'add' => 'Создание нового пользователя',
					'edit' => 'Редактирование пользователя',
					'singin' => 'Вход для своих'
				)
			),
			'groups' => array(
				'titles' => array(
					'index' => 'Список групп пользователей',
					'add' => 'Создание новой группы пользователей',
					'edit' => 'Редактирование группы пользователей',
				)
			),
		)
	),
	'messages' => array(
		'signin' => 'Добро пожаловать в панель управления Михалычем!'
	),
	'labels' => array(
		'email' => 'Email',
		'name' => 'Полное имя',
		'contacts' => 'Контакты',
		'group_id' => 'Группа пользователя'
	),
	'form' => array(),
	'grid' => array(
		'name' => 'Полное имя',
		'contacts' => 'Контакты',
		'group_id' => 'Группа пользователя'
	),
);