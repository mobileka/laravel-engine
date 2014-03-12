# Introduction

Laravel Engine is a result of a constant evolution of a simple Laravel bundle which was originally written to make CRUD generation easier and faster.

With Laravel Engine you get a nice functionality to develop CRUD-oriented applications in a simple and fun way. It is like a LEGO for a progmrammer who just needs to glue all the "bricks" together in order to get a working application.

We call this bricks "Components" and there are two types of them in the Engine: 

1. Grid components - these are bricks for listing data stored in a database 
2. Form components - bricks that are used to add and edit data

To make this possible and easy to implement, we needed to improve most of the standard Laravel 3 libraries adding new functionality that made us write less code and get better results.

Some day we desided to share all this handy shit with everyone in the world, just in case somebody benefit from it.

The best way to describe all possibilities of the Engine is to write a book but we will try to make this README a good starting point for newbies. 

# Installation quest

Clone the Laravel Engine into a separate folder.

The best way to integrate the Engine with a Laravel 3 application is to create a symlink in the `bundles/` directory:

`cd bundles && ln -s path/to/Laravel/Engine/Mobileka/directory`

This allows you to get updates with a simple `git pull` command in the Engine directory.

*Please, make sure to .gitignore this folder in your main Laravel 3 project because it can potentially create problems with git.*

The Engine consists of three big parts:

- Engine - core bundle which contains CRUD and other Laravel improvements
- Users - users and user groups which depend on Engine and typically are going to be overriden
- Auth - authentication / authorization which depends on Users

You need to register these bundles in the `application/bundles.php`:

```
return array(
	'engine' => array('location' => 'Mobileka/L3/Engine', 'auto' => true),
	'auth' => array('location' => 'Mobileka/L3/Auth', 'auto' => true),
	'users' => array('location' => 'Mobileka/L3/Users', 'auto' => true),
);
```

Also, the following alias should be defined in the `application/config/application.php` (*to be fixed soon*):

```
'aliases' => array(
	// ... snip
	'Helpers\Arr' => 'Mobileka\L3\Engine\Laravel\Helpers\Arr',
),
```

Now run migrations:

```
$ php artisan migrate:install
$ php artisan migrate
```

... and add a route to handle an access to an administration interface:

```
Route::get('admin', array('as' => 'admin_home', 'uses' => 'users::admin.default@index'));
```

*Please note that the Engine requires every single route to have an alias. This means that other routes (including the default Laravel route) defined before integrating the Engine and not having an alias will break the application. In order to fix this, you either need to remove these routes or add an alias for all of them. We will discuss Laravel Engine routing more closely in an appropriate section.*

The Engine bundle contains a shitload of assets which must be published:

```
$ php artisan bundle:publish
```

In order Auth component to work properly, add these ACL permissions to `application/config/acl.php`:

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

and specify a model which maps the `users` table `application/config/auth.php`:

```
'model' => IoC::resolve('UserModel'),
```

If you are going to use an image uploading functionality, make sure to create a directory writable
by a webserver and specify it in `paths.php`:

```
$paths['uploads'] = 'public/uploads';
```

And you probably will use the `ImageColumn` component, so create a `application/config/image.php` file with the following contents:

```
<?php

return array(
	'aliases' => array(
		'multiupload_thumb' => array(99, 112), // Dimensions of thumbnails in multiupload
		'admin_grid_thumb' => array(80, 80), // Dimensions of thumbnails in grid
	),
);
```

And you finally finished the freaking hard installation process and now should be able to go to `http://sitename.dev/admin` to see an authorization form. Just in case you want to log in, these are the default credentials:

```
email: admin@example.com
password: 123456
```

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

# Generating bundles with cli
Laravel Engine includes a script for a fast bundle generation. This is very useful if you need to get a simple (yet powerful and flexible) administration interface in no time.

There are two possible ways to generate an administration interface:

1. One by one specifying database fields for each bundle
2. Mass bundle generation by reverse engineering of an existing SQL file

### 1. Generating a single bundle

To create a new bundle run this command:
```
artisan engine::create:bundle path.to.bundle.bundleName fieldName:laravelColumnType:option[ fieldName:laravelColumnType:option ...][ addmenu:section:item]
```

Here is a simple example:
```
artisan engine::create:bundle app.Users username:string password:string role_id:unsigned:index
```

* This generates following files with proper contents in `bundles/app/Users` directory:
	* config/config.php
	* controllers/admin/default.php
	* language/ru/default.php
	* migrations/xxx_create_users_table.php
	* migrations/xxx_add_users_foreign.php (with a foreign key for role_id)
	* Models/User.php (with predefined belongs_to relation)
	* routes.php
	* start.php

* Add this to Admin sidebar menu:
	* ```app``` will be Admin sidebar menu section (if ```addmenu``` is not passed)
	* ```Users``` will be Admin sidebar menu item (if ```addmenu``` is not passed)
* Add this to application/bundles.php.

Type ```fieldName:laravelColumnType:required``` and this column will be required.

Type ```modelName_id:unsigned``` and create relations and foreign keys automatically.


### 2. Generating bundles with SQL file

As stated above, Laravel Engine is able to generate bundles by reading an existing SQL file. If you follow Laravel and Laravel Engine conventions while building an architecture of your database, you'll get a fully working administration interface as a gift.

To use this functionality, you need to put the sql file into the  ```path('app')/schema``` directory and run a command:

```
artisan engine::create:application[ schema_filename][ path_to_bundles]
```

The ```schema.sql``` file will be expected by default. The second argument is for nesting your bundles in a separate directories inside of the ```bundnles``` directory.

And here is an example:

Lets assume that you saved a file ```my_super_puper_database_schema.sql``` in the ```path('app')/schema``` directory and you also want all of your generated bundles to reside in ```bundles/app``` directory. To do this, just run the following command:
```
artisan engine::create:application my_super_puper_database_schema.sql app
```

That's it! Now you have a fully functional administration interface with a grid (plus sorting and filtering possibilities) and CRUD with validations, Blackjack and Whores!

Write less code, go have beer sooner!
-------------------------------------
