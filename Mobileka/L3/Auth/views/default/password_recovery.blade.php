<div class="row-fluid">
	<div class="span3 offset4">
		<h2>{{ ___('default', 'password_recovery') }}</h2>

		{{ Form::open(URL::to_route('auth_default_password_recovery'), 'POST', array('class' => 'form-validate', 'id' => 'loginform')) }}
		{{ Form::token() }}

		@if ($error = Session::get('error'))
			<div class="alert alert-error">
				{{ $error }}
			</div>
		@endif

		<div class="box box-bordered">

			<div class="control-group">
				{{ Form::label('email', ___('default.labels', 'email'), array('class' => 'control-label'), false) }}

				<div class="controls">
					{{ $this->validation($errors->get('email')) }}
					{{ Form::text('email', Input::old('email', ''), array('autocomplete' => 'off', 'class' => 'input-block-level', 'placeholder' => 'Email', 'data-rule-required' => 'true', 'data-rule-email' => 'true')) }}
				</div>
			</div>

			<div class="control-group">
				{{ Form::label('password', ___('default.labels', 'new_password'), array('class' => 'control-label'), false) }}

				<div class="controls">
					{{ $this->validation($errors->get('password')) }}
					{{ Form::password('password', array('autocomplete' => 'off', 'class' => 'input-block-level', 'placeholder' => ___('default', 'password'), 'data-rule-required' => 'true')) }}
				</div>
			</div>

			<div class="control-group">
				{{ Form::label('password_confirmation', ___('default.labels', 'password_confirmation'), array('class' => 'control-label'), false) }}

				<div class="controls">
					{{ $this->validation($errors->get('password_confirmation')) }}
					{{ Form::password('password_confirmation', array('id' => 'password_confirmation', 'class' => 'input-block-level', 'placeholder' => ___('default', 'password_confirmation'), 'data-rule-required' => 'true')) }}
				</div>
			</div>

			<div class="submit">
				{{ Form::submit(___('default', 'save'), array('class' => 'btn btn-primary')) }}
			</div>

		</div> <!-- .box.box-bordered -->

		{{ Form::close() }}

		<div class="forget">
			<a href="{{ URL::to_route('auth_default_login') }}"><span>{{ ___('default', 'back') }}</span></a>
		</div>
	</div> <!-- .span4 -->
</div> <!-- .row-fluid -->