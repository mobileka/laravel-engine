<a href="{{ URL::to_route('models_admin_default_edit', $component->row->id) }}">
	<img src="{{ $component->value() }}" alt="{{ $component->row->name }}">
</a>