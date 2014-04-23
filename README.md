<!-- START doctoc generated TOC please keep comment here to allow auto update -->
<!-- DON'T EDIT THIS SECTION, INSTEAD RE-RUN doctoc TO UPDATE -->
**Table of Contents** 

- [Introduction](#introduction)
- [Installation quest](#installation-quest)
- [Conventions](#conventions)
- [Base\Model](#base\model)
- [Access control](#access-control)
- [i18n](#i18n)
- [CRUD](#crud)
- [Crud components](#crud-components)
- [Image uploading](#image-uploading)
	- [Getting started with image uploading](#getting-started-with-image-uploading)
	- [Usage of image uploading](#usage-of-image-uploading)
	- [Uploading images one by one](#uploading-images-one-by-one) 
	- [Working with uploaded images](#working-with-uploaded-images)
	- [Multiple image uploading](#multiple-image-uploading)
	- [How does image uploading work?](#how-does-image-uploading-work)
- [CSRF protection](#csrf-protection)
- [Admin sidebar configuration](#admin-sidebar-configuration)
- [Generating bundles with cli](#generating-bundles-with-cli)
	- [Generating bundles with admin interface](#generating-bundles-with-admin-interface)
		- [1. Generating a single bundle](#1-generating-a-single-bundle)
		- [2. Generating bundles with SQL file](#2-generating-bundles-with-sql-file)
	- [Other generator possibilities](#other-generator-possibilities)
- [Authors](#authors)
- [Licence](#licence)

# Introduction

Laravel Engine is a result of a constant evolution of a simple Laravel bundle which was originally written to make CRUD generation easier and faster.

With Laravel Engine you get a nice functionality to develop CRUD-oriented applications in a simple and fun way. It is like a LEGO for a progmrammer who just needs to glue all the "bricks" together in order to get a working application.

We call this bricks "Components" and there are two types of them in the Engine: 

1. Grid components - these are bricks for listing data stored in a database 
2. Form components - bricks that are used to add and edit data

To make this possible and easy to implement, we needed to improve most of the standard Laravel 3 libraries adding new functionality that made us write less code and get better results.

The most suiting way to describe all possibilities of the Engine is to write a book but we will try to make this README a good starting point for newbies. 

# Installation quest

Clone the Laravel Engine into a separate folder.

The best way to integrate the Engine with a Laravel 3 application is to create a symlink in the `bundles/` directory:

`cd bundles && ln -s path/to/Laravel/Engine/Mobileka/directory`

Or, if you are on Windows (Vista or newer):

```
cd bundle
mklink /D Mobileka path\to\Laravel\Engine\Mobileka
```

This allows you to get updates with a simple `git pull` command in the Engine directory.

> Please, make sure to .gitignore this folder in your main Laravel 3 project because it can potentially create problems with git.

The Engine consists of three big parts:

- Engine - core bundle which contains CRUD and other Laravel improvements
- Users - users and user groups which depend on Engine and typically are going to be overriden
- Auth - authentication / authorization which depends on Users

You need to register these bundles in the `application/bundles.php`:

```
return array(
	'engine' => array('location' => 'Mobileka/L3/Engine'),
	'auth' => array('location' => 'Mobileka/L3/Auth', 'auto' => true),
	'users' => array('location' => 'Mobileka/L3/Users', 'auto' => true),
);
```

Some of Laravel Engine components use composer packages, so you need to install it and integrate with Laravel 3:

-- Install composer: `curl -sS https://getcomposer.org/installer | php`

-- Add the following code to your `app/start.php` file:

```
if (!File::exists('vendor/autoload.php'))
{
	throw new Exception("You need to run composer update to complete installation of this project.");
}

require 'vendor/autoload.php';
```
-- Create `composer.json` file in the root of your project and add these lines to it:

```
{
	"require" : {
		"nesbot/Carbon": "*",
		"ezyang/htmlpurifier": "dev-master"
	}
}
```
-- Run `php composer.phar update`

> If you get the "allowed memory size exhausted" error try adding `-d memory_limit="1024M"` after `php` in the above command

-- Add `composer.lock` and `vendor/*` to `.gitignore` in the root of your application

Ok, lets continue. It is time to run migrations:

```
$ php artisan migrate:install
$ php artisan migrate
```

... add the following line on top of your `application/routes.php` file:

```
Bundle::start('engine');
```

... and add a route to handle the access to the administration interface:

```
Route::get(admin_uri(), array('as' => 'admin_home', 'uses' => 'users::admin.default@index'));
```

The `admin_uri()` helper allows you to easily change the URI which handles access to admin interface (`admin` is the default one).
To change this URI, you need to add `admin_uri` parameter to the `application/config/application.php` configuration file:

```
'admin_uri' => 'rulethesite'
```

> Please note that the Engine requires every single route to have an alias. This means that other routes (including the default Laravel route) defined before integrating the Engine and not having an alias will break the application. In order to fix this, you either need to remove these routes or add an alias for all of them. We will discuss Laravel Engine routing more closely in an appropriate section.

The Engine contains a shitload of assets which must be published:

```
$ php artisan bundle:publish
```

In order Auth component to work properly, add these permissions to `application/config/acl.php`:

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

Don't forget to create a `.gitignore` file in this folder with the following contents:

```
*
!.gitignore
```

As you are probably going to use the `ImageColumn` component, create `application/config/image.php` file and add these lines to it:

```
<?php

return array(
	'aliases' => array(
		'multiupload_thumb' => array(99, 112), // Dimensions of thumbnails in multiupload
		'admin_grid_thumb' => array(80, 80), // Dimensions of thumbnails in grid
	),
	'allowedFileTypes' => array('jpg', 'jpeg', 'png', 'gif') //modify for your needs
);
```

If you don't use SSL, you need to set these configuration parameters to `false`:
* `ssl` in `application.php`
* `secure` in `session.php`

> Use enviroment configuration files if these settings differ for your production server and local development machine

And you have finally finished the installation process! Now you should be able to go to `http://sitename.dev/admin` (or `http://sitename.dev/whatever_you_have_in_application_config_under_admin_uri_param`) to see the authorization form. Just in case you want to log in, these are the default credentials:

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
CRUD is the fundomental component of the Engine. 
	* Component configuration
	* Language configuration
	* Component value translation

# Base\Model
* Events
* Field validation
* i18n field validation
* Image fields

# Access control

# i18n

# CRUD
* Structure
* Form
* Grid

# Crud components
* Form
    * ImageField
    * TextField
    * ...
    * DropdownChosenLinked

DropdownChosenLinked component exists for CRUD Form and Grid Filter. It allows you to create several selectboxes, values in which hierarchically depend on the values in the previous select boxes. For example, Country -> Region -> City -> Street -> Building. If we have a large number of buildings in the database, it is not rational to load them all in order to provide the user with thousands of options in a selectbox. It is not usable, and is very bad for performance, so you should use DropdownChosenLinked instead.

Using it is slightly harder than a normal DropdownChosen component, but nothing too hard.

First of all, make sure you have published all the assets of Laravel Engine. A new .js file was pushed to the repo recently, so run ``php artisan bundle:publish`` if you haven't done that for a while.

Next, you will need to create a route for your ajax requests. Every time you select a value in one of the linked select boxes, an ajax request is sent in order to retrieve the selected element's children items, so we need to make sure we created the route for that. Just add this to your `application/routes.php` file, and specify the models that you need to work with in the `$possible_linked_items` array:

```
Route::get('admin/linked_list/(:any)/(:num)', array('as' => 'admin_linked_list', function($modelName, $id){

	$result                = array();
	$possible_linked_items = array('Geo', 'Product_Type', 'Feature');

	if (in_array($modelName, $possible_linked_items))
	{
		$model = IoC::resolve($modelName . 'Model');
		$data  = $model->getListByParent($id, 'name');

		foreach ($data as $record)
		{
			$result[] = array(
				'id'   => $record->id,
				'name' => $record->name,
			);
		}

		return Response::json($result);
	}

	throw new Exception("Incorrect value for passed model: $modelName");

}));
```

Now all we need is to specify in the `config.php` for our form or filter the linked items. It must be an array, with its keys being the fields in the database (which we are saving values for in the case of the Form, or which we are filtering by in the case of Filter) and the values being the model class of the children. In the following example I have three levels of product types, followed by one level of product features:

```
$linked_items = array(
	'product_type_id_1' => 'Product_Type',
	'product_type_id_2' => 'Product_Type',
	'product_type_id_3' => 'Feature',
	'feature_id'        => 'Feature',
);

```

And then in the config of the actual fields:

```
'product_type_id_1' => formDropdownChosenLinked::make('product_type_id_1')->options($product_type_1s)->linked_items($linked_items),
'product_type_id_2' => formDropdownChosenLinked::make('product_type_id_2', array('disabled' => true))->options(array()),
'product_type_id_3' => formDropdownChosenLinked::make('product_type_id_3', array('disabled' => true))->options(array()),
'feature_id' => formDropdownChosenLinked::make('feature_id', array('disabled' => true))->options(array()),
```

We only need to fill the first selectbox with initial values, and the rest should be made disabled and empty. The component should do the rest to fill them when you are selecting a value in the first selectbox, or editing a record (all selectboxes will be filled and an appropriate option will be selected).

One last thing: as you can see in the route, the children are retrieved using a model's getListByParent() method. By default, its a simple method of getting the records with their `parent_id` field equal to the specified value, but you can always override either the field (just specify static `$parentField` class variable in your model) or the whole method in your model to make sure you only return the actual children.


* Grid
    * ImageColumn
    * TextColumn
    * ...
* Filters
	* StartsWithFilter
    * DropdownFilter
    * ...

# Image uploading
Laravel Engine has a *kind of* a built-in possibility to upload images. To use this functionality you'll need to solve another difficult quest. But once you've done it, you'll get the following features:
- Standardized way to save and manipulate images (and other types of files)
- Asynchronous image uploading
- Easy way to crop and resize images (with possibility to do this dynamically)
- Multiuple image uploading (async, with previews, with possibility to remove them one by one and select a featured image)
- Built-in caching
- Other cool features that I forgot to mention

## Getting started with image uploading
I wrote *kind of*, because this functionality depends on a composer package which you need to install before using image uploading:

`php composer.phar require intervention/image dev-master`

If you want to make image uploading more efficient, you can also install "intervention/imagecache":

`php composer.phar require intervention/imagecache dev-master`

## Usage of image uploading
Lets start from a simple example when you just need to upload an image and bind it to your, say, Article object.

1. First, your Article model should extend `Mobileka\L3\Engine\Laravel\Base\ImageModel` (*this is confusing and should be fixed*).

2. Then you need to enumerate image fields in your Article model like so:
`public static $imageFields = array('img', 'another_image_field');`

> Please note: right now there is a naming problem and you should not call your field `image` because this crashes the system. Of course, this is going to be fixed some day

3. Now list all the accessible fields of the model (because this is a good practice and Image component will add fields which shouldn't be saved to the database with `fill()` method):
`public static $accessible = array('title', 'description');` 

4. To enable image uploading functionality, you need to create routes which handle uploading requests. But it is better to ask the RestfulRouter to do it for you:
`RestfulRouter::make()->with('images')->resource(array('bundle' => 'articles'));`

> If you don't like how it sounds, you can pass one of these options istead of `images`: 'file', 'files', 'img', 'image', 'uploads'

5. The last step is to configure a component for your form and, optionally, grid:

```
use Mobileka\L3\Engine\Form\Components\Image as ImageField,
	Mobileka\L3\Engine\Grid\Components\Image as ImageColumn;

return array(
	'form' => array(
		'components' => array(
			//...
			'img' => ImageField::make('img'),
			//...
		)
	),
	grid' => array(
		'components' => array(
			//...
			'img' => ImageColumn::make('img'),
			//...
		)
	)
);
```

And... **OH MY GOD!** you did it! :) Now you can upload images and bind them to your Article objects in administration panel.

> Please note that you can change file types that can be uploaded to a server in the `allowedFileTypes` parameter of the `application/config/image.php` config file.

## Uploading images one by one

You just saw the `Image` component usage example.

This component is there to upload images one by one (though you can use several Image components on the same page).
It is worth mentioning that you can add croppoing functionality to this component and that we use `Jcrop` JavaScript library for this.
To enable image cropping you need to pass `asceptRatio` as a Jcrop option like this:

```
Image::make('img')->jcrop(array('aspectRatio' => 1));
```

You can set other [Jcrop options](http://deepliquid.com/content/Jcrop_Manual.html#Setting_Options) this way.

The next section describes how to retrieve these images and work with them.

## Working with uploaded images
Ok, it is time to show the uploaded image to a user. In order to get an original uploaded image you just need to call a `getImageSrc()` method on a model object as follows:

```
$article = Article::find(1);
$article->getImageSrc('img'); // provide a name of an image field
```

But there are also other ways to get the image.
If you were following the [installation quest](#installation-quest) carefully, you remember that it was required to create a `application/config/image.php` configuration file.

Lets review the contents of this file:

```
<?php

return array(
	'aliases' => array(
		'multiupload_thumb' => array(99, 112), // Dimensions of thumbnails in multiupload
		'admin_grid_thumb' => array(80, 80), // Dimensions of thumbnails in grid
	),
	'allowedFileTypes' => array('jpg', 'jpeg', 'png', 'gif') //modify for your needs
);
```

As you have already got from comments, in this file you can create aliases which reflect the image type (e.g. main_article_image) and provide dimensions for them.

This alias can be passed to the `getImageSrc()` method as a second parameter: `$article->getImageSrc('img', 'main_article_image');`

This means that the original image will be rezised according to alias dimensions. By default, the aspect ratio will be preserved, but if you want to change this, add a third boolean item to the alias dimensions array like so:

```
return array(
	'aliases' => array(
		'main_article_image' => array(220, 170, false) //do not preserve the aspect ratio and make the image exactly 220 x 170
	),
);
```

> Please note, that the Engine will generate and save the image for an each alias when you access it the first time: on every other call the image will be read from a filesystem

## Multiple image uploading
It's easy to upload multiple images with the Engine. The only difference between single image uploading and multiuploading is a component that performs this. So, the `Mobileka\L3\Engine\Form\Components\Image` component is used for a single image uploading and `Mobileka\L3\Engine\Form\Components\MultiUpload` for multiple image uploading:

```
use Mobileka\L3\Engine\Form\Components\MultiUpload,
	Mobileka\L3\Engine\Grid\Components\Image as ImageColumn;

return array(
	'form' => array(
		'components' => array(
			//...
			'img' => MultiUpload::make('img'),
			//...
		)
	),
	grid' => array(
		'components' => array(
			//...
			'img' => ImageColumn::make('img'),
			//...
		)
	)
);
```

When using a MultiUpload component, it is a common use case when you need to choose a main or, how it is called in Wordpress community, a featured image. If we take an example above, there are two steps to achieve this:

1. Create `img` field in the `articles` table
2. Call `featuredImageSelector()` method on your `MultiUpload` component like this: `MultiUpload::make('img')->featuredImageSelector()`

As you've probably got, by default the featured image path will be saved in a field with a name of a component (`img` in our case) and then can be accesses like this: `$article->img`. You can change this passing a field name as a parameter to the `featuredImageSelector()` method: `MultiUpload::make('img')->featuredImageSelector('featured_image')` and now you can access this image like `$article->featured_image` (and don't forget to rename the field in the database table).


> BTW not only images can be uploaded with this component. To allow other file types, change the `allowedFileTypes` parameter in the `application/config/image.php` file.

## How does image uploading work?
*Write me*

# CSRF protection

Laravel engine includes CSRF protection for simple forms as well as for ajax calls.

In order to make this work, you need to perform these steps:

1. `php artisan bundle:publish`
2. Add this JavaScript file to your layouts: `{{ HTML::script('bundles/engine/csrf.js') }}`
3. Add a metatag to your layouts in `<head>` section: `{{ csrf_meta_tag() }}` 
4. Add `csrf` before filter for a route like this: `Route::get('something', array('before' => 'csrf', 'uses' => '...', 'as' => '...'));`

If you are generating routes with `RestfulRouter` class, every `POST` and `PUT` request is being protected automatically.
If you want to cancel this, call `csrf()` method with parameter `false` before `resource()` method of the `RestfulRouter`:

`RestfulRouter::make()->csrf(false)->resource(array('bundle' => 'somebundle', 'module' => 'admin'));`

> Please note that CSRF protection is enabled for the administration panel by default

# Admin sidebar configuration

It's easy to configure the sidebar menu in administration interface.

it consists of items devided by sections. In other words, sections are groups or categories of menu items.

The first step is to create an `application/config/menu.php` file and fill it with configuration data according to this syntax:

```
return array(
	'sections' => array(
		array(
			'label' => 'Section Name 1',
			'items' => array(
				array(
					'label' => 'Item Name 1',
					'route' => 'item_route_alias', //according to routing conventions
					'icon' => 'glyphicon-user'
				),
				//...
				array(
					'label' => 'Item Name 2',
					'route' => 'bundle_admin_controller_action',
					'icon' => 'glyphicon-group'
				),
			)
		),
		array(
			'label' => 'Section Name 2',
			'items' => array(
				array(
					'label' => 'User management',
					'route' => 'users_admin_default_index',
					'icon' => 'glyphicon-user'
				),
			)
		),
	)
);
```

There are two things we like about our menu:

1. Integration with user access control library which automatically checks whether the current authorized user has an access to a menu item and hides it when access is denied. When the user has no access to all section items, the section will be hidden too. To read about access control in detail, go to [Access control](#access-control) section.


2. Cli generator generates menu items with proper routes and, if you pass additional information, it generates proper section and item names too. The only thing you need to configure manually is an icon associated with the menu item.

Read about generator in the next section.

# Generating bundles with cli
Laravel Engine includes a script for a fast bundle generation. This is very useful if you need to get a simple (yet powerful and flexible) administration interface in no time.

## Generating bundles with admin interface

There are two possible ways to generate an administration interface:

1. One by one, specifying database fields for each bundle
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

This generates following files with proper contents in `bundles/app/Users` directory:
* config/default.php
* controllers/admin/default.php
* language/ru/default.php
* migrations/xxx_create_users_table.php
* migrations/xxx_add_users_foreign.php (with a foreign key for role_id)
* Models/User.php (with predefined belongs_to relation)
* routes.php
* start.php

Generator will also automatically add the new bundle to the `application/bundles.php` file.

Finally, if `addmenu` argument was not passed, generator will add the new bundle to a menu considering these defaults:
* The section will be called `app`
* The menu item will be called  `Users`

In order to override these defaults, you can pass `addmenu` argument as follows:
```
addmenu:SectionName:ItemName
```

To get more information about menu configuration, read the [Admin sidebar configuration](#admin-sidebar-configuration) section.


### 2. Generating bundles with SQL file

As stated above, Laravel Engine is able to generate bundles by reading an existing SQL file. If you follow Laravel and Laravel Engine conventions while building an architecture of your database, you'll get a fully working administration interface as a gift.

To use this functionality, you need to put the sql file into the  `path('app')/schema` directory and run a command:

```
artisan engine::create:application[ schema_filename][ path_to_bundles]
```

The `schema.sql` file will be expected by default. The second argument is for nesting your bundles in a separate directories inside of the `bundnles` directory.

And here is an example:

Lets assume that you saved a file `my_super_puper_database_schema.sql` in the `path('app')/schema` directory and you also want all of your generated bundles to reside in `bundles/app` directory. To do this, just run the following command:
```
artisan engine::create:application my_super_puper_database_schema.sql app
```

That's it! Now you have a fully functional administration interface with a grid (plus sorting and filtering possibilities) and CRUD with automatic form validation!

## Other generator possibilities

When generating bundles one by one, you have a lot of options to customize the generated code.

For example, you can make a field to be unsigned, create an index on it or make it to be required (a rule will be added to a self-validating model):

```
artisan engine::create:bundle app.Users username:string:required role_id:unsigned:index:required
```

# Authors

# Licence
