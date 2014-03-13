<?php try { ?>

@if (!$component->row->orderItems)
	{{ ___('default', 'no_items') }}
@else
	<table class="table">
		<thead>
			<tr>
				<th>{{ ___('default', 'model_id') }}</th>
				<th>{{ ___('default', 'name') }}</th>
				<th>{{ ___('default', 'price') }}</th>
				<th>{{ ___('default', 'quantity') }}</th>
				<th>{{ ___('default', 'total') }}</th>
			</tr>
		</thead>
		<tbody>
		@foreach ($component->row->orderItems as $item)
			<tr>
				<td>{{ $item->model_id }}</td>
				<td>{{ $item->name }}</td>
				<td>{{ $item->price }}</td>
				<td>{{ $item->quantity }}</td>
				<td>{{ $item->price * $item->quantity }}</td>
			</tr>
		@endforeach
		</tbody>
	</table>
@endif

<?php } catch (Exception $e) { exit($e->getMessage()); } ?>