<div class="row-fluid">
	<div class="span3 offset4">
		<h2>{{ ___('default', 'login') }}</h2>

		{{ Form::open($crud->actionUrl, 'POST', array('class' => 'form-validate', 'id' => 'loginform')) }}
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
					{{ Form::text('email', Input::old('email', ''), array('class' => 'input-block-level', 'placeholder' => 'Email', 'data-rule-required' => 'true', 'data-rule-email' => 'true')) }}
				</div>
			</div>

			<div class="control-group">
				{{ Form::label('password', ___('default.labels', 'password'), array('class' => 'control-label'), false) }}

				<div class="controls">
					{{ $this->validation($errors->get('password')) }}
					{{ Form::password('password', array('class' => 'input-block-level', 'placeholder' => 'Пароль', 'data-rule-required' => 'true')) }}
				</div>
			</div>

			<div class="submit">
				<div class="remember">
					{{ Form::checkbox('remember', 'remember', false, array('class' => 'icheck-me', 'data-skin' => 'square', 'data-color' => 'blue', 'id' => 'remember')) }}
					{{ Form::label('remember', ___('default', 'remember_me')) }}
				</div>

				{{ Form::submit(___('default', 'save'), array('class' => 'btn btn-primary')) }}
			</div>

		</div> <!-- .box.box-bordered -->

		{{ Form::close() }}

		<div class="forget">
			<a href="{{ URL::to_route('auth_default_password_recovery') }}"><span>{{ ___('default', 'forgot_password') }}?</span></a>
		</div>
	</div> <!-- .span4 -->
</div> <!-- .row-fluid -->