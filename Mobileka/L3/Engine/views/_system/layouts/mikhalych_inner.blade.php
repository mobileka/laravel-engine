@include('_system.layouts.frontend.header')

	<div class="top-categories top-categories-basic row">
		<div class="span3 top-category current">
			<a href="{{ URL::base() . '/oils' }}">
				<div class="top-category-orange-overlay"></div>
				<div class="top-category-grey-overlay"></div>
				<span class="pic pic-oils-basic"></span>
				<div class="top-category-title">Масла и жидкости</div>
			</a>
		</div>
		<div class="span3 top-category">
			<a href="{{ URL::base() . '/tyres' }}">
				<div class="top-category-orange-overlay"></div>
				<div class="top-category-grey-overlay"></div>
				<span class="pic pic-tyres-basic"></span>
				<div class="top-category-title">Шины</div>
			</a>
		</div>
		<div class="span3 top-category">
			<a href="{{ URL::base() . '/accumulators' }}">
				<div class="top-category-orange-overlay"></div>
				<div class="top-category-grey-overlay"></div>
				<span class="pic pic-accumulators-basic"></span>
				<div class="top-category-title">Аккумуляторы</div>
			</a>
		</div>
		<div class="span3 top-category">
			<a href="{{ URL::base() . '/accessories' }}">
				<div class="top-category-orange-overlay"></div>
				<div class="top-category-grey-overlay"></div>
				<span class="pic pic-accessories-basic"></span>
				<div class="top-category-title">Аксессуары</div>
			</a>
		</div>
	</div> <!-- .row -->

	{{ $content }}

@render('_system.layouts.frontend.footer')