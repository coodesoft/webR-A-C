<!-- Master Wrap : starts -->
<section class="mastwrap page-top-space">

	<div class="container-fluid">
		<div class="row">
			<article class="col-md-12 text-center services-bg page-bg parallax">
				<h1 class="main-heading font2 dark"><span>Servicios</span></h1>
			</article>
		</div>
	</div>

	<div class="container">

		<div class="row add-top">

			<article class="col-md-10 col-md-offset-1 text-center">
				<h1 class="super-heading grey font2"><span>Asesoramiento personalizado</span></h1>
				<div class="separator"><img alt="" title="" src="<?php echo Yii::app()->theme->baseUrl; ?>/images/separator/01-white.png"/></div>
			</article>

		</div>

		<div class="row add-bottom">

			<article class="col-md-6 text-left">

				<div class="row">
					<article class="col-md-12 agency-service-block">
						<div class="service-inner-wrap service-bg-01">
							<img alt="" title="" class="img-responsive" src="<?php echo Yii::app()->theme->baseUrl; ?>/images/services/1.png"/>
							<h3 class="black font2">Asesoramiento</h3>
							<p>Visitamos su hogar. Previa llamada a nuesto local y pactar una cita.</p>
						</div>
					</article>
				</div>

				<div class="row">
					<article class="col-md-12 agency-service-block">
						<div class="service-inner-wrap service-bg-03">
							<img alt="" title="" class="img-responsive" src="<?php echo Yii::app()->theme->baseUrl; ?>/images/services/3.png"/>
							<h3 class="black font2">Materiales</h3>
							<p>Materiales de primer nivel. Melamina. Enchapado y madera. Maderas macizas.</p>
						</div>
					</article>
				</div>

			</article>

			<article class="col-md-6 text-left">

				<div class="row">
					<article class="col-md-12 agency-service-block">
						<div class="service-inner-wrap service-bg-02">
							<img alt="" title="" class="img-responsive" src="<?php echo Yii::app()->theme->baseUrl; ?>/images/services/2.png"/>
							<h3 class="black font2">Diseño personalizado</h3>
							<p>De un simple boceto inicial le damos un presupuesto estimativo.</p>
						</div>
					</article>
				</div>

				<div class="row">
					<article class="col-md-12 agency-service-block">
						<div class="service-inner-wrap service-bg-06">
							<img alt="" title="" class="img-responsive" src="<?php echo Yii::app()->theme->baseUrl; ?>/images/services/6.png"/>
							<h3 class="black font2">Presupuesto</h3>
							<p>Brindamos imágenes digitales y animadas. Opciones de diseños, texturas y colores.</p>
						</div>
					</article>
				</div>

			</article>

		</div>

	</div>

	<div class="container-fluid offwhite-bg">
		<div class="container">
			<div class="row add-top add-bottom">
				<div class="row">
					<article class="col-md-12 text-center">

						<div id="bx-pager" class="agency-slider-triggers">
							<a class="agency-triggered" data-slide-index="0" href=""><img alt="" title="" src="<?php echo Yii::app()->theme->baseUrl; ?>/images/agency/01.png" /></a>
							<a data-slide-index="1" href=""><img alt="" title="" src="<?php echo Yii::app()->theme->baseUrl; ?>/images/agency/02.png" /></a>
							<a data-slide-index="2" href=""><img alt="" title="" src="<?php echo Yii::app()->theme->baseUrl; ?>/images/agency/03.png" /></a>
						</div>

						<div class="separator2"><img alt="" title="" src="<?php echo Yii::app()->theme->baseUrl; ?>/images/separator/02-color.png"/></div>

						<ul class="bxslider agency-slider">
						  	<li>
						  		<div class="row">
									<article class="col-md-12 text-center">
										<h3 class="agency-feature-heading dark font3"><span>Reputación</span></h3>
										<h1 class="agency-feature-text black font2">Empresa familiar de amplia trayectoria que está dedicada al diseño y fabricación a medida de muebles.</h1>
									</article>
								</div>
						  	</li>

						  	<li>
						  		<div class="row">
									<article class="col-md-12 text-center">
										<h3 class="agency-feature-heading dark font3"><span>Creatividad</span></h3>
										<h1 class="agency-feature-text black font2">Mas de 20 años fabricando muebles.</h1>
									</article>
								</div>
						  	</li>

						  	<li>
						  		<div class="row">
									<article class="col-md-12 text-center">
										<h3 class="agency-feature-heading dark font3"><span>Capabilidad</span></h3>
										<h1 class="agency-feature-text font2">Mucha experiencia, ideas claras y superación constante son las características que reune nuestro equipo de trabajo.</h1>
									</article>
								</div>
						  	</li>
						</ul>
					</article>
				</div>
			</div>
		</div>
	</div>

	<?php echo $this->renderPartial('/shared/workWithUs', array('colorClass' => 'color-bg', 'separatorImg' => '02-dark.png', 'btnExposeClass' => 'btn-expose-white')); ?>

</section>
<!-- Master Wrap : ends -->