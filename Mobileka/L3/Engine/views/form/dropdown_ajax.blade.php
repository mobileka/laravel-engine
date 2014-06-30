<?php try {  ?>
<?php $value = Input::old($component->name, $component->value($lang)); ?>

{{ Form::select($component->name, $component->options, $value, $component->attributes) }}

<div class="dropdown_ajax_separator"></div>

<?php $component->boundElement->row($component->row); ?>

{{ $this->validation($errors->get($component->boundElement->name)) }}
{{ $component->boundElement->render() }}

<script>

var AjaxDropdown = {
		routes: [],
		parent: null,
		parentVal: null,
		child: null,
		childVal: null,
		childType: "{{ $component->htmlElement }}",

		init: function()
		{
			this.parent = $('[name={{$component->name}}]');
			this.parentVal = parentVal = this.parent.val();
			this.child = $('[name={{$component->boundElement->name}}]');
			this.childVal = "{{ $component->boundElement->value($lang) }}";

			@foreach($component->routes as $key => $route)
				var route = "{{ URL::to_existing_route($route) }}";

				if (route)
				{
					route += '.json';
				}

				this.routes.push({ key: "{{ $key }}", route: route });
			@endforeach

			if (!parentVal)
			{
				this.child.prop('disabled', true);
			}
			else
			{
				this.getItems(this.getRoute(parentVal), true);
			}

			return this;
		},

		getRoute: function(val)
		{
			url = null;

			for (var i in this.routes)
			{
				if (this.routes[i].key === val)
				{
					url = this.routes[i].route;
					break;
				}
			}

			return url;
		},

		getItems: function(url, fristBlood)
		{
			var childType = this.childType,
				child = this.child,
				childVal = this.childVal;

			firstBlood = typeof fristBlood === "undefined" ? false : true;

			if (url)
			{
				$.ajax({
					url: url,
					type: 'GET',
					success: function(response)
					{
						if (childType === 'select')
						{
							options = '';

							for (var i in response)
							{
								options += '<option value="' + response[i].id + '">' + response[i].title + '</option>';
							}

							child.html(options);

							if (firstBlood)
							{
								child.val(childVal);
							}

							child.prop('disabled', false);
						}
						else
						{
							alert('todo: functionality for text inputs is not implemented yet');
						}
					}
				});
			}
			else
			{
				child.html('');
				child.prop('disabled', true);
			}
		}

	},
	ad = AjaxDropdown.init();

$(ad.parent).change(function(){
	var val = $(this).val();
	ad.getItems(ad.getRoute(val));
});

</script>

<?php } catch (Exception $e) { exit($e->getMessage()); } ?>
