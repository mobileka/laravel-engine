<?php

Laravel\Autoloader::namespaces(array(
    'Mobileka\L3\Engine' => __DIR__
));

Laravel\IoC::register('i18n', function ($injection = null) {
    $injection = $injection ? $injection : new \Mobileka\L3\Engine\Models\i18n;

    return new \Mobileka\L3\Engine\i18n($injection);
});

Laravel\IoC::register('Purifier', function ($config = null) {
    $config = (is_object($config) and get_class($config) === 'HTMLPurifier_Config')
        ? $config
        : HTMLPurifier_Config::createDefault()
    ;

    return new HTMLPurifier($config);
});

//Laravel.Base
Laravel\Autoloader::$aliases['BaseClass'] = 'Mobileka\L3\Engine\Laravel\Base\BClass';
Laravel\Autoloader::$aliases['Controller'] = 'Mobileka\L3\Engine\Laravel\Base\Controller';
Laravel\Autoloader::$aliases['FrontendController'] = 'Mobileka\L3\Engine\Laravel\Base\FrontendController';
Laravel\Autoloader::$aliases['BackendController'] = 'Mobileka\L3\Engine\Laravel\Base\BackendController';
Laravel\Autoloader::$aliases['Model'] = 'Mobileka\L3\Engine\Laravel\Base\Model';
Laravel\Autoloader::$aliases['View'] = 'Mobileka\L3\Engine\Laravel\Base\View';

//Laravel.Helpers
Laravel\Autoloader::$aliases['Debug'] = 'Mobileka\L3\Engine\Laravel\Helpers\Debug';
Laravel\Autoloader::$aliases['Misc'] = 'Mobileka\L3\Engine\Laravel\Helpers\Misc';
Laravel\Autoloader::$aliases['Arr'] = 'Mobileka\L3\Engine\Laravel\Helpers\Arr';

//Laravel
Laravel\Autoloader::$aliases['Acl'] = 'Mobileka\L3\Engine\Laravel\Acl';
Laravel\Autoloader::$aliases['Bundle'] = 'Mobileka\L3\Engine\Laravel\Bundle';
Laravel\Autoloader::$aliases['Config'] = 'Mobileka\L3\Engine\Laravel\Config';
Laravel\Autoloader::$aliases['Database'] = 'Mobileka\L3\Engine\Laravel\Database';
Laravel\Autoloader::$aliases['Date'] = 'Mobileka\L3\Engine\Laravel\Date';
Laravel\Autoloader::$aliases['File'] = 'Mobileka\L3\Engine\Laravel\File';
Laravel\Autoloader::$aliases['HTML'] = 'Mobileka\L3\Engine\Laravel\HTML';
Laravel\Autoloader::$aliases['Image'] = 'Mobileka\L3\Engine\Laravel\Image';
Laravel\Autoloader::$aliases['Input'] = 'Mobileka\L3\Engine\Laravel\Input';
Laravel\Autoloader::$aliases['Lang'] = 'Mobileka\L3\Engine\Laravel\Lang';
Laravel\Autoloader::$aliases['Loader'] = 'Mobileka\L3\Engine\Laravel\Loader';
Laravel\Autoloader::$aliases['Notification'] = 'Mobileka\L3\Engine\Laravel\Notification';
Laravel\Autoloader::$aliases['Redirect'] = 'Mobileka\L3\Engine\Laravel\Redirect';
Laravel\Autoloader::$aliases['RestfulRouter'] = 'Mobileka\L3\Engine\Laravel\RestfulRouter';
Laravel\Autoloader::$aliases['Router'] = 'Mobileka\L3\Engine\Laravel\Router';
Laravel\Autoloader::$aliases['Session'] = 'Mobileka\L3\Engine\Laravel\Session';
Laravel\Autoloader::$aliases['Sphinx'] = 'Mobileka\L3\Engine\Laravel\Sphinx';
Laravel\Autoloader::$aliases['SphinxConditionBuilder'] = 'Mobileka\L3\Engine\Laravel\SphinxConditionBuilder';
Laravel\Autoloader::$aliases['Str'] = 'Mobileka\L3\Engine\Laravel\Str';
Laravel\Autoloader::$aliases['URL'] = 'Mobileka\L3\Engine\Laravel\URL';
Laravel\Autoloader::$aliases['UrlConditionBuilder'] = 'Mobileka\L3\Engine\Laravel\UrlConditionBuilder';
Laravel\Autoloader::$aliases['Validator'] = 'Mobileka\L3\Engine\Laravel\Validator';

//Crud
Laravel\Autoloader::$aliases['GridBuilder'] = 'Mobileka\L3\Engine\Grid\Grid';
Laravel\Autoloader::$aliases['FormBuilder'] = 'Mobileka\L3\Engine\Form\Form';

//Vendor
Laravel\Autoloader::$aliases['Carbon'] = 'Carbon\Carbon';

//Form Components
Laravel\Autoloader::$aliases['AutocompleteField'] = 'Mobileka\L3\Engine\Form\Components\Autocomplete';
Laravel\Autoloader::$aliases['CKEditorField'] = 'Mobileka\L3\Engine\Form\Components\CKEditor';
Laravel\Autoloader::$aliases['DatepickerField'] = 'Mobileka\L3\Engine\Form\Components\Datepicker';
Laravel\Autoloader::$aliases['DropdownField'] = 'Mobileka\L3\Engine\Form\Components\Dropdown';
Laravel\Autoloader::$aliases['DropdownAjaxField'] = 'Mobileka\L3\Engine\Form\Components\DropdownAjax';
Laravel\Autoloader::$aliases['ChosenDropdownField'] = 'Mobileka\L3\Engine\Form\Components\DropdownChosen';
Laravel\Autoloader::$aliases['DualMultiselectField'] = 'Mobileka\L3\Engine\Form\Components\DualMultiselect';
Laravel\Autoloader::$aliases['EmailField'] = 'Mobileka\L3\Engine\Form\Components\Email';
Laravel\Autoloader::$aliases['ModelListField'] = 'Mobileka\L3\Engine\Form\Components\ModelList';
Laravel\Autoloader::$aliases['ImageField'] = 'Mobileka\L3\Engine\Form\Components\Image';
Laravel\Autoloader::$aliases['MultiUploadField'] = 'Mobileka\L3\Engine\Form\Components\MultiUpload';
Laravel\Autoloader::$aliases['PasswordField'] = 'Mobileka\L3\Engine\Form\Components\Password';
Laravel\Autoloader::$aliases['PriceField'] = 'Mobileka\L3\Engine\Form\Components\Price';
Laravel\Autoloader::$aliases['RadioField'] = 'Mobileka\L3\Engine\Form\Components\Radio';
Laravel\Autoloader::$aliases['SpinnerField'] = 'Mobileka\L3\Engine\Form\Components\Spinner';
Laravel\Autoloader::$aliases['TagField'] = 'Mobileka\L3\Engine\Form\Components\Tag';
Laravel\Autoloader::$aliases['TextField'] = 'Mobileka\L3\Engine\Form\Components\Text';
Laravel\Autoloader::$aliases['TextareaField'] = 'Mobileka\L3\Engine\Form\Components\TextArea';
Laravel\Autoloader::$aliases['CheckboxField'] = 'Mobileka\L3\Engine\Form\Components\Checkbox';
Laravel\Autoloader::$aliases['HiddenField'] = 'Mobileka\L3\Engine\Form\Components\Hidden';
Laravel\Autoloader::$aliases['YandexMapLocationField'] = 'Mobileka\L3\Engine\Form\Components\YandexMapLocation';
Laravel\Autoloader::$aliases['PhoneField'] = 'Mobileka\L3\Engine\Form\Components\Phone';

//Grid Components
Laravel\Autoloader::$aliases['TextColumn'] = 'Mobileka\L3\Engine\Grid\Components\Column';
Laravel\Autoloader::$aliases['NestedTextColumn'] = 'Mobileka\L3\Engine\Grid\Components\ColumnNested';
Laravel\Autoloader::$aliases['DateColumn'] = 'Mobileka\L3\Engine\Grid\Components\Date';
Laravel\Autoloader::$aliases['ImageColumn'] = 'Mobileka\L3\Engine\Grid\Components\Image';
Laravel\Autoloader::$aliases['LinkColumn'] = 'Mobileka\L3\Engine\Grid\Components\Link';
Laravel\Autoloader::$aliases['PriceColumn'] = 'Mobileka\L3\Engine\Grid\Components\Price';
Laravel\Autoloader::$aliases['SwitcherColumn'] = 'Mobileka\L3\Engine\Grid\Components\Switcher';
Laravel\Autoloader::$aliases['BooleanColumn'] = 'Mobileka\L3\Engine\Grid\Components\Boolean';

//Filters
Laravel\Autoloader::$aliases['ContainsFilter'] = 'Mobileka\L3\Engine\Grid\Filters\Contains';
Laravel\Autoloader::$aliases['DateFilter'] = 'Mobileka\L3\Engine\Grid\Filters\Date';
Laravel\Autoloader::$aliases['DateRangeFilter'] = 'Mobileka\L3\Engine\Grid\Filters\DateRange';
Laravel\Autoloader::$aliases['DropdownFilter'] = 'Mobileka\L3\Engine\Grid\Filters\Dropdown';
Laravel\Autoloader::$aliases['EndsWithFilter'] = 'Mobileka\L3\Engine\Grid\Filters\EndsWith';
Laravel\Autoloader::$aliases['StartsWithFilter'] = 'Mobileka\L3\Engine\Grid\Filters\StartsWith';
Laravel\Autoloader::$aliases['TextFilter'] = 'Mobileka\L3\Engine\Grid\Filters\Text';

\Mobileka\L3\Engine\Laravel\Loader::requireDirectory(Bundle::path('engine') . 'Globals');

Laravel\Event::fire('engine: ready');
