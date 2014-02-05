	<div class="row">
		<div class="span12">
			<div class="footer_links fullwidth">
				<div class="row">
					<div class="span1">
						<div class="footer_mikhalych-logo"></div>
					</div> <!-- .span1 -->

					<div class="span2">
						<h5 class="footer_links_title">Как покупать</h5>
						<ul class="linklist">
							<li><a href="#">Оформление заказа</a></li>
							<li><a href="#">Доставка</a></li>
							<li><a href="#">Оплата</a></li>
							<li><a href="#">Возврат</a></li>
						</ul>
					</div> <!-- .span2 -->

					<div class="span3">
						<h5 class="footer_links_title">Помощь</h5>
						<ul class="linklist">
							<li><a href="#">Михалыч позвонит</a></li>
							<li><a href="#">Вопрос-ответ</a></li>
							<li><a href="#">Личный кабинет</a></li>
						</ul>
						<div class="social-icons">
							<a href="#" class="social-icons_vk">Мы Вконтакте</a>
							<a href="#" class="social-icons_twitter">Мы в Twitter</a>
							<a href="#" class="social-icons_facebook">Мы на Facebook</a>
						</div>
					</div> <!-- .span2 -->

					<div class="span2 nopadding">
						<h5 class="footer_links_title">О Михалыче</h5>
						<ul class="linklist">
							<li><a href="#">Проект</a></li>
							<li><a href="#">Знак качества</a></li>
							<li><a href="#">Новости</a></li>
							<li><a href="#">Вакансии</a></li>
						</ul>
					</div> <!-- .span2 -->

					<div class="span3">
						<h5 class="footer_links_title">Партнёрам</h5>
						<ul class="linklist">
							<li><a href="#">Преимущества</a></li>
							<li><a href="#">Как продавать у Михалыча</a></li>
						</ul>
					</div> <!-- .span2 -->
				</div> <!-- .row -->
			</div> <!-- .footer_links -->
		</div> <!-- .span12 -->
	</div> <!-- .row -->

	<div class="row footer_copyright">
		<div class="span4">
			© 2013 <a href="#">Mikhalych.kz</a>
		</div> <!-- .span4 -->

		<div class="span4">
			Способы оплаты:
			<span class="visa"></span>
			<span class="mastercard"></span>
			<span class="cash"></span>
		</div> <!-- .span4 -->

		<div class="span4 text-right">
			Дизайн и разработка: <!-- a href="#" class="chocofamily" --> 
			<a href="#" class="ibecsystems"></a>
		</div> <!-- .span4 -->
	</div> <!-- .row -->

</div> <!-- .container -->

<div class="loader-container">
	<span class="loader-sprite"></span> <span class="loader-label">Загрузка...</span>
</div>

</div> <!-- #skrollr-body -->

{{ notifications() }}
{{ Asset::container('footer_plugins')->scripts() }}
{{ Asset::container('footer_custom')->scripts() }}

</body>
</html>