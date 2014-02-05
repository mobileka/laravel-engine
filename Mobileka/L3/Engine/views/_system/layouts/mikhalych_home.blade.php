@include('_system.layouts.frontend.header')

	<div class="top-categories row">
		<div class="span3 top-category current">
			<a href="{{ URL::base() . '/oils' }}">
				<div class="top-category-orange-overlay"></div>
				<div class="top-category-grey-overlay"></div>
				<span class="pic pic-oils"></span>
				<div class="top-category-title">Масла и жидкости</div>
			</a>
		</div>
		<div class="span3 top-category">
			<a href="{{ URL::base() . '/tyres' }}">
				<div class="top-category-orange-overlay"></div>
				<div class="top-category-grey-overlay"></div>
				<span class="pic pic-tyres"></span>
				<div class="top-category-title">Шины</div>
			</a>
		</div>
		<div class="span3 top-category">
			<a href="{{ URL::base() . '/accumulators' }}">
				<div class="top-category-orange-overlay"></div>
				<div class="top-category-grey-overlay"></div>
				<span class="pic pic-accumulators"></span>
				<div class="top-category-title">Аккумуляторы</div>
			</a>
		</div>
		<div class="span3 top-category">
			<a href="{{ URL::base() . '/accessories' }}">
				<div class="top-category-orange-overlay"></div>
				<div class="top-category-grey-overlay"></div>
				<span class="pic pic-accessories"></span>
				<div class="top-category-title">Аксессуары</div>
			</a>
		</div>
	</div> <!-- .row -->

	<div class="row">
		<div class="relative">
			<div class="hider hider-left"></div>
			<div class="hider"></div>

			<div class="top-category-filters">
				<div class="top-category-filter top-category-filter-oils">

					<div class="row">

						<div class="span3">
							<div class="top-category-filter-column">
								<h4>Подбор моторного масла</h4>
								<form action="" method="GET">
									<div>
										{{ Form::select('brand', Arr::getItem($brands, 'oils', array('Выбор')), 0, array('class' => 'chosen-select')) }}
									</div>

									<div>
										{{ Form::select('v', Arr::searchRecursively($properties, 'oils', 'Вязкость', array('Выбор')), 0, array('class' => 'chosen-select')) }}
									</div>
									<div>
										{{ Form::select('q', Arr::searchRecursively($properties, 'oils', 'Качество', array('Выбор')), 0, array('class' => 'chosen-select')) }}
									</div>
								</form>
							</div> <!-- .top-category-filter-column -->
						</div> <!-- .span3 -->

						@render('home.menu.filter_block', array('category' => Arr::getItem($categories, 'oils', array('Выбор')), 'columns' => 3))

					</div> <!-- .row -->

				</div>

				<div class="top-category-filter top-category-filter-tyres">

					<h4 class="tyres-top-filter-heading">Подбор шин</h4>

					<div class="row">

						<div class="span3 offset1">
							<ul class="list-arrow-long list-arrow-tyres">
								<li>
									<span class="chosen-select-label">Ширина</span>
									<span class="chosen-select-short">
										{{ Form::select('w', Arr::searchRecursively($properties, 'tyres', 'Ширина', array('Выбор')), 0, array('class' => 'chosen-select')) }}
									</span>
								</li>
								<li>
									<span class="chosen-select-label">Высота</span>
									<span class="chosen-select-short">
										{{ Form::select('h', Arr::searchRecursively($properties, 'tyres', 'Высота', array('Выбор')), 0, array('class' => 'chosen-select')) }}
									</span>
								</li>
								<li>
									<span class="chosen-select-label">Диаметр</span>
									<span class="chosen-select-short">
										{{ Form::select('d', Arr::searchRecursively($properties, 'tyres', 'Диаметр', array('Выбор')), 0, array('class' => 'chosen-select')) }}
									</span>
								</li>
							</ul> <!-- .list-arrow-long -->
						</div> <!-- .span3.offset1 -->

						<div class="span3">
							<ul class="tyres-second-column-filters">
								<li>
									<span class="chosen-select-label">Бренд</span>
									<span class="chosen-select-medium">
										{{ Form::select('brand', Arr::getItem($brands, 'tyres', array('Выбор')), 0, array('class' => 'chosen-select')) }}
									</span>
								</li>
								<li>
									<span class="chosen-select-label">Сезон</span>
									<span class="chosen-select-medium">
										{{ Form::select('s', Arr::searchRecursively($properties, 'tyres', 'Сезон', array('Выбор')), 0, array('class' => 'chosen-select')) }}
									</span>
								</li>
							</ul>
						</div> <!-- .span3 -->

						<div class="span4">
							<div class="top-filter-found-block">
								<div class="top-filter-found-label">Найдено: <span id="top_filter_found_count">100</span> шин</div>
								<div class="top-filter-found-view">
									<a href="#">
										<span>Посмотреть</span>
										
									</a>
								</div>

								<ul class="top-filter-found_ul list-inline">
									<li><a href="#">Все</a></li>
									<li><a href="#">Как выбрать</a></li>
								</ul>
							</div>
						</div> <!-- .span4 -->

					</div> <!-- .row -->

				</div>

				<div class="top-category-filter top-category-filter-accumulators">

					<h4 class="top-category_left160">Подбор аккумулятора</h4>

					<div class="row">

						<div class="span4 offset1">
							<ul class="list-arrow-long">
								<li class="align-left">
									<span class="chosen-select-label-medium">Полярность</span>
									<span class="chosen-select-medium">
										{{ Form::select('p', Arr::searchRecursively($properties, 'accumulators', 'Полярность', array('Выбор')), 0, array('class' => 'chosen-select')) }}
									</span>
								</li>
								<li class="align-left">
									<span class="chosen-select-label-medium">Ёмкость</span>
									<span class="chosen-select-short">
										{{ Form::select('c', Arr::searchRecursively($properties, 'accumulators', 'Ёмкость', array('Выбор')), 0, array('class' => 'chosen-select')) }}
									</span>
								</li>
								<li class="no-arrow align-left">
									<span class="chosen-select-label-medium">Бренд</span>
									<span class="chosen-select-medium">
										{{ Form::select('brand', Arr::getItem($brands, 'accumulators'), 0, array('class' => 'chosen-select')) }}
									</span>
								</li>
							</ul> <!-- .list-arrow-long -->
						</div> <!-- .span4.offset1 -->

						<div class="span2 nopadding">
							<ul class="tyres-second-column-filters">
								<li>
									<span class="chosen-select-label">Длина</span>
									<span class="chosen-select-short">
										{{ Form::select('l', Arr::searchRecursively($properties, 'accumulators', 'Длина', array('Выбор')), 0, array('class' => 'chosen-select')) }}
									</span>
								</li>
								<li>
									<span class="chosen-select-label">Ширина</span>
									<span class="chosen-select-short">
										{{ Form::select('w', Arr::searchRecursively($properties, 'accumulators', 'Ширина', array('Выбор')), 0, array('class' => 'chosen-select')) }}
									</span>
								</li>
								<li>
									<span class="chosen-select-label">Высота</span>
									<span class="chosen-select-short">
										{{ Form::select('h', Arr::searchRecursively($properties, 'accumulators', 'Высота', array('Выбор')), 0, array('class' => 'chosen-select')) }}
									</span>
								</li>
							</ul>
						</div> <!-- .span3 -->

						<div class="span4">
							<div class="top-filter-found-block">
								<div class="top-filter-found-label">Найдено: <span id="top_filter_found_count">100</span> аккумуляторов</div>
								<div class="top-filter-found-view">
									<a href="#">
										<span>Посмотреть</span>
									</a>
								</div>

								<ul class="top-filter-found_ul list-inline">
									<li><a href="#">Все</a></li>
									<li><a href="#">Как выбрать</a></li>
								</ul>
							</div>
						</div> <!-- .span4 -->

					</div> <!-- .row -->

				</div>

				<div class="top-category-filter top-category-filter-accessories">

					<div class="row">

						@render('home.menu.filter_block', array('category' => $categories['accessories'], 'columns' => 4))

					</div> <!-- .row -->

				</div>

			</div> <!-- .span12 -->
		</div>

		<div class="top-categories-pagination" id="top_categories_pagination"></div>

	</div> <!-- .row -->

	@render('models::default.widgets.swiper_carousel', array('title' => 'Последние предложения', 'models' => carouselModels()))

	<div class="row infoBlocks">
		<div class="span6 infoBlocks__grey">
			<div class="infoBlocks__fonts infoBlocks__stripes">
				{{ infoblock('home_left') }}
			</div>
		</div> <!-- .span6 -->

		<div class="span6 infoBlocks__orange">
			<div class="infoBlocks__fonts">
				{{ infoblock('home_right') }}
			</div>
		</div> <!-- .span6 -->
	</div> <!-- .row -->

@render('_system.layouts.frontend.footer')