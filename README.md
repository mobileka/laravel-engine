# Installation

Copy or make a symlink to Mobileka directory inside `bundles/`. Make sure to
gitignore them. There are 3 bundles:

- Engine - core bundle, contains CRUD and other Laravel improvements
- Users - users and user groups, depends on Engine
- Auth - authentication/authorization, depends on Users

Register bundles in `application/bundles.php`:

```
return array(
	'engine' => array('location' => 'Mobileka/L3/Engine', 'auto' => true),
	'auth' => array('location' => 'Mobileka/L3/Auth', 'auto' => true),
	'users' => array('location' => 'Mobileka/L3/Users', 'auto' => true),
);
```

Also, these aliases must be defined (this must be fixed) in
`application/config/application.php`:

```
	'aliases' => array(
		// ... snip
		'Helpers\Arr' => 'Mobileka\L3\Engine\Laravel\Helpers\Arr',
	),
```

Default Laravel route must be removed (all routes must have aliases)

Run migrations:

```
$ php artisan migrate:install
$ php artisan migrate
```

Add route to handle access to admin interface:

```
Route::get('admin', array('as' => 'admin_home', 'uses' => 'users::admin.default@index'));
```

If you have other defined routes, the Engine requires all of them to be aliased.

The Engine bundle contains a shitload of assets which must be published:

```
$ php artisan bundle:publish
```
If you're using Auth bundle, specify permissions in `application/config/acl.php`:

```
<?php

return array(
	'defaultResult' => false,

	'allowedRoutes' => array(
		'auth_admin_default_login',
		'auth_admin_default_logout',
	),

	'permissions' => array(

		'aliases' => array(
			'(:any)_admin_(:any)' => array('admins'),
			'admin_home' => array('admins'),
		),

		'paths' => array(),
	),

	'actions' => array(
		'upload_files_without_restrictions' => array('admins')
	),
);
```

and specify model for authenticating users in `application/config/auth.php`:


```
'model' => IoC::resolve('UserModel'),
```

If you use image upload functionality, make sure to create a directory writable
by a webserver and specify it in `paths.php`:

```
$paths['uploads'] = 'public/uploads';
```


If you want to use ImageColumn component, you need to create a config file
`application/config/image.php` and write:

```
<?php

return array(
	'aliases' => array(
		'multiupload_thumb' => array(99, 112), // Dimensions of thumbnails in multiupload
		'admin_grid_thumb' => array(80, 80), // Dimensions of thumbnails in grid
	),
);
```

Go to http://sitename.dev/admin

Default authorization credentials:

email: admin@example.com

password: 123456

# Conventions
* Routing
	* Route aliases
	* RESTful urls (RestfulRouter class)
	* .json and .ajax
* Models
	* naming
	* saveData()

* CRUD
	* Component configuration
	* Language configuration
	* Component value translation

# Base\Model
* Events
* Field validation
* i18n field validation
* Image fields

# ACL

# i18n

# Image uploading
* ImageField component
* MultiUploadField component
* ImageColumn component

# CRUD
* Structure
* Form
* Grid

# Crud components
* Form
	* ImageField
    * TextField
    * ...
* Grid
	* ImageColumn
    * TextColumn
    * ...
* Filters
	* StartsWithFilter
    * DropdownFilter
    * ...

# Admin sidebar menu configuration

# Generating CRUD modules with cli
Mass Bundle Creator for Laravel Engine

Create single bundle:
```
artisan engine::create:bundle path.to.bundle.bundleName fieldName:laravelColumnType:option[ fieldName:laravelColumnType:option ...][ addmenu:section:item]
```
* Create next items with all stuff:
	* config/config.php
	* controllers/admin/default.php
	* language/ru/default.php
	* migrations/migrations with foreign
	* Models/bundleName.php
	* routes.php
	* start.php
* Add this to Admin sidebar menu:
	* ```bundle``` will be Admin sidebar menu section (if ```addmenu``` is not passed)
	* ```bundleName``` will be Admin sidebar menu item (if ```addmenu``` is not passed)
* Add this to application/bundles.php.
* Run migrations.

Type ```fieldName:laravelColumnType:required``` and this column will be required.

Type ```modelName_id:unsigned``` and create relations and foreign keys automatically.


To create many bundles from SQL, put ```schema.sql``` into ```path('app')/schema```, and type command: (not tested)
```
artisan engine::create:application[ schema_filename][ path_to_bundles]
```

Write less code, go have beer sooner!
-------------------------------------
