<?php
/**
 * JBZoo is universal CCK based Joomla! CMS and YooTheme Zoo component
 * @category   JBZoo
 * @author     smet.denis <admin@joomla-book.ru>
 * @copyright  Copyright (c) 2009-2012, Joomla-book.ru
 * @license    http://joomla-book.ru/info/disclaimer
 * @link       http://joomla-book.ru/projects/jbzoo JBZoo project page
 */
defined('_JEXEC') or die('Restricted access');

$align = $this->app->jbitem->getMediaAlign($item, $layout);

echo $this->renderPosition('title',     array('style' => 'jbtitle'));
echo $this->renderPosition('subtitle',  array('style' => 'jbsubtitle'));
echo $this->renderPosition('likes',     array(
        'style' => 'jbblock',
        'class' => 'align-left'
    )
);
echo $this->renderPosition('rating', array(
        'style' => 'jbblock',
        'class' => 'align-right',
    )
);

?>

	<?php if ($this->checkPosition('banner-image')) : ?>				
	<div class="banner-item" style="background: url('<?php echo trim($this->renderPosition('banner-image')); ?>')
        center 0px no-repeat;">
	
	
		<div class="container">
			<div class="caption vertical-center text-center">
				
				<h1 class="fadeInDown-1 light-color"><?php echo $this->renderPosition('banner-title'); ?></h1>
				<div class="fadeInDown-3">
                    <a href="#demo-form" class="order-demo btn btn-large fancybox visible-xs">Заказать демо</a>
					<a href="#" class="btn btn-large hidden-xs">Начни прямо сейчас</a>
				</div><!-- /.fadeIn -->
				
			</div><!-- /.caption -->
		</div><!-- /.container -->
	</div><!-- /.item -->
	  <?php endif; ?>
	  
	<?php if ($this->checkPosition('text-above-tabs')) : ?>
	<section id="who-we-are">
		<div class="container inner-top inner-bottom-sm">
			
			<div class="row">
				<div class="col-md-9 col-sm-10 center-block text-center online_photo">
				
							
					<?php echo $this->renderPosition('text-above-tabs'); ?>
					
				</div><!-- /.col -->
			</div><!-- /.row -->
		
		
		</div><!-- /.container -->
	</section>
	<?php endif; ?>
	
	 <section id="reasons">
                <div class="container inner">

                    <div class="row inner-top-sm">
                        <div class="col-xs-12">
                            <div class="tabs tabs-reasons tabs-circle-top tab-container">

                                <ul class="etabs text-center">
                                    <li class="tab"><a href="#tab-8" class="tab1 hidden-xs">
                                        <span  class="hidden-xs">Быстрое <br>развертывание, запуск<br> в короткие
                                            сроки</span>
                                    </a>
                                        <a href="#tab-1" class="tab1 visible-xs"> <span class="visible-xs">Быстрое
                                            развертывание, запуск в короткие сроки</span>
                                    </a>

                                        <div class="visible-xs">
                                            <div class="tab-content" id="tab-1">
                                                <div class="row">
                                                    <div class="col-md-8 col-sm-9 center-block text-center">
                                                        <?php echo $this->renderPosition('text-tab1'); ?>
                                                    </div><!-- /.col -->
                                                </div><!-- /.row -->
                                            </div><!-- /.tab-content -->
                                        </div>

                                    </li>
                                    <li class="tab"><a href="#tab-9" class="tab2 hidden-xs">
                                        <span  class="hidden-xs">Облачное решение, <br>не требует собственной <br>IT
                                            -инфраструктуры </span></a>
                                        <a href="#tab-2" class="tab2 visible-xs">  <span class="visible-xs">Облачное
                                            решение, не
                                            требует собственной IT
                                            -инфраструктуры </span>
                                    </a>
                                        <div class="visible-xs">
                                            <div class="tab-content" id="tab-2 ">
                                                <div class="row">
                                                    <div class="col-md-8 col-sm-9 center-block text-center">
                                                       <?php echo $this->renderPosition('text-tab2'); ?>
                                                    </div><!-- /.col -->
                                                </div><!-- /.row -->
                                            </div><!-- /.tab-content -->
                                        </div></li>
                                    <li class="tab"><a href="#tab-10" class="tab3 hidden-xs">
                                        <span class="hidden-xs">Регулярные обновления <br>и поддержка <br>без
                                            остановки сервиса</span></a>
                                        <a href="#tab-3" class="tab3 visible-xs"> <span class="visible-xs">Регулярные
                                            обновления и поддержка без остановки сервиса</span>
                                    </a>
                                        <div class="visible-xs">
                                            <div class="tab-content" id="tab-3">
                                                <div class="row">
                                                    <div class="col-md-8 col-sm-9 center-block text-center">
                                                        <?php echo $this->renderPosition('text-tab3'); ?>
                                                    </div><!-- /.col -->
                                                </div><!-- /.row -->
                                            </div><!-- /.tab-content -->
                                        </div></li>
                                    <li class="tab"><a href="#tab-11" class="tab4 hidden-xs">
                                        <span class="hidden-xs">Легкое встраивание <br>в сайт компании</span></a>
                                        <a href="#tab-4" class="tab4 visible-xs"> <span class="visible-xs">Легкое в
                                            страивание
                                            в сайт компании</span>
                                    </a>
                                        <div class="visible-xs">
                                            <div class="tab-content" id="tab-4">
                                                <div class="row">
                                                    <div class="col-md-8 col-sm-9 center-block text-center">
                                                        <?php echo $this->renderPosition('text-tab4'); ?>
                                                    </div><!-- /.col -->
                                                </div><!-- /.row -->
                                            </div><!-- /.tab-content -->
                                        </div></li>
                                </ul><!-- /.etabs -->

                                <div class="panel-container">


                                   <div class="tab-content" id="tab-8">
                                        <div class="row">
                                            <div class="col-md-8 col-sm-9 center-block text-center">
                                                <?php echo $this->renderPosition('text-tab1'); ?>
                                            </div><!-- /.col -->
                                      </div><!-- /.row -->
                                             </div><!-- /.tab-content -->

                                    <div class="tab-content" id="tab-9">
                                        <div class="row">
                                            <div class="col-md-8 col-sm-9 center-block text-center">
                                                <?php echo $this->renderPosition('text-tab2'); ?>
                                            </div><!-- /.col -->
                                        </div><!-- /.row -->
                                    </div><!-- /.tab-content -->

                                    <div class="tab-content" id="tab-10">
                                        <div class="row">
                                            <div class="col-md-8 col-sm-9 center-block text-center">
                                                <?php echo $this->renderPosition('text-tab3'); ?>
                                            </div><!-- /.col -->
                                        </div><!-- /.row -->
                                    </div><!-- /.tab-content -->

                                    <div class="tab-content" id="tab-11">
                                        <div class="row">
                                            <div class="col-md-8 col-sm-9 center-block text-center">
                                                <?php echo $this->renderPosition('text-tab4'); ?>
                                            </div><!-- /.col -->
                                        </div><!-- /.row -->
                                    </div><!-- /.tab-content -->

                                </div><!-- /.panel-container -->

                            </div><!-- /.tabs -->
                        </div><!-- /.col -->
                    </div><!-- /.row -->

                </div><!-- /.container -->
            </section>
		
<section id="latest-works" class="light-bg">
	<div class="container inner view_product" style="padding-bottom: 18px">
		
		<div class="row">
			<div class="col-md-8 col-sm-9 center-block text-center" style="margin-bottom: 50px;">
			
				<?php echo trim($this->renderPosition('text-above-products')); ?>
				
			</div><!-- /.col -->
		</div><!-- /.row -->
	 
		<div class="row">
		
		
		<div class="container inner-top-sm">
			<div id="owl-latest-works" class="owl-carousel owl-item-gap">
				
				<?php echo trim($this->renderPosition('products')); ?>


			</div><!-- /.owl-carousel -->
		</div><!-- /.row -->
		
	</div><!-- /.container -->
</section>
		
		
		<section id="who-we-are">
                <div class="container inner-top inner-bottom-sm perevagu" style="padding-bottom: 20px">

                      <div class="row">
                             <div class="col-md-12 col-sm-12 center-block text-center">
                               
                                     <h3>Преимущества платформы для владельца бизнеса</h3>
                                 
                             </div><!-- /.col -->
                    </div><!-- /.row -->

                    	<div class="row inner-top-sm platform">


                    		<div class="col-md-4 col-sm-4 inner-bottom-xs">
                                <span class="sell"></span>
                                <p class="text-small">Позволяет продавать<br>
                                    любые виды плоской<br>
                                    продукции</p>
                            </div><!-- /.col -->

                   	<div class="col-md-4 col-sm-4 inner-bottom-xs">
                        <span class="download"></span>
                            <p class="text-small">Можно загружать<br>
                                собственный макет<br>
                                в формате psd</p>
                        </div><!-- /.col -->


                    	<div class="col-md-4 col-sm-4 inner-bottom-xs">
                            <span class="unloads"></span>
                            <p class="text-small">Выгружает макеты<br> во всех популярных<br> форматах</p>
                        </div><!-- /.col -->


                            <div class="col-md-4 col-sm-4 inner-bottom-xs">
                                <span class="automatic"></span>
                                <p class="text-small">Автоматически информирует<br>
                                   <span class="hidden-sm hidden-xs"> администратора<br></span>
                                    о поступлении заказа</p>
                            </div><!-- /.col -->
                            <div class="col-md-4 col-sm-4 inner-bottom-xs">
                                <span class="flexible"></span>
                                <p class="text-small">Гибкое управление<br> прайс-листом</p>
                            </div><!-- /.col -->
                            <div class="col-md-4 col-sm-4 inner-bottom-xs">
                                <span class="marketing"></span>
                                <p class="text-small">Возможность<br> проведения<br> маркетинговых акций</p>
                            </div><!-- /.col -->



                            <div class="col-md-4 col-sm-4 inner-bottom-xs">
                                <span class="good"></span>
                                <p class="text-small">Удобное и быстрое<br> оформление заказа<br>
                                    с полной информацией</p>
                            </div><!-- /.col -->
                            <div class="col-md-4 col-sm-4 inner-bottom-xs">
                                <span class="information"></span>
                                <p class="text-small">Информация о клиентах<br> и их действиях на сайте<br> хранится в базе данных</p>
                            </div><!-- /.col -->
                            <div class="col-md-4 col-sm-4 inner-bottom-xs">
                                <span class="analitic"></span>
                                <p class="text-small">Возможность<br> построения<br> аналитических отчетов</p>
                            </div><!-- /.col -->


                            <div class="col-md-4 col-sm-4 inner-bottom-xs hidden-xs">

                            </div><!-- /.col -->
                            <div class="col-md-4 col-sm-4 inner-bottom-xs last_product">
                                <span class="price"></span>
                                <p class="text-small">Доступная цена<br> платформы</p>
                            </div><!-- /.col -->
                            <div class="col-md-4 col-sm-4 inner-bottom-xs hidden-xs">

                            </div><!-- /.col -->

                    </div><!-- /.row -->

                </div><!-- /.container -->
            </section>

            <!-- ============================================================= SECTION – Преимущества платформы для владельца бизнеса : END ============================================================= -->

            <!-- ============================================================= SECTION – просто и удобно для покупателя ============================================================= -->

            <section id="who-we-are" class="back_for_sell">
                <div class="container inner-top inner-bottom-sm ">

                     <div class="row">
                             <div class="col-md-12 col-sm-12 center-block text-center">
                                 <header>
                                     <h3>просто и удобно для покупателя</h3>
                                  <!--   <p>Здесь должна кратко расшифровываться суть предложения + перечисление
                                         каких-то ключвых плющек, которые получит клиент. Здесь должна кратко расшифровываться суть предложения + перечисление каких-то ключвых плющек, которые получит клиент. Здесь должна кратко расшифровываться суть предло жения + перечисление каких-то ключвых плющек, которые получит клиент.
                                         </p> -->
                                 </header>
                             </div><!-- /.col -->
                     </div><!-- /.row -->

                    <div class="row inner-top-sm for_sell">

                <!--        <div class="col-md-1">
                            <div class="icon pull-right">
                                <i class="icon-heart-empty-1 icn"></i>
                            </div><!-- /.icon -->
                        <!--    </div><!-- /.col -->

                        <div class="col-md-4 col-sm-4 inner-bottom-xs">
                            <span class="instal"></span>
                            <p class="text-small">Не требуется установка<br> программы и обучение
                                <span class="hidden-sm hidden-xs">клиента</span><br> работе с программой
                            </p>
                        </div><!-- /.col -->

                        <!--     <div class="col-md-1">
                                 <div class="icon pull-right">
                                     <i class="icon-lamp icn"></i>
                                 </div><!-- /.icon -->
                        <!--     </div><!-- /.col -->

                        <div class="col-md-4 col-sm-4 inner-bottom-xs">
                            <span class="fast"></span>
                            <p class="text-small">Простой, быстрый, <br>интуитивно понятный <br>онлайн редактор</p>
                        </div><!-- /.col -->

                        <!--    <div class="col-md-1">
                                <div class="icon pull-right">
                                    <i class="icon-star-empty-1 icn"></i>
                                </div><!-- /.icon -->
                        <!--    </div><!-- /.col -->

                        <div class="col-md-4 col-sm-4 inner-bottom-xs">
                            <span class="regestration"></span>
                            <p class="text-small">Простая регистрация <br>на сервисе <br>&nbsp;</p>
                        </div><!-- /.col -->



                        <div class="col-md-4 col-sm-4 inner-bottom-xs">
                            <span class="client"></span>
                            <p class="text-small">Клиент может сделать заказ <br>в любое время, нужен только <br>доступ в Интернет и браузер</p>
                        </div><!-- /.col -->

                        <div class="col-md-4 col-sm-4 inner-bottom-xs">
                            <span class="all"></span>
                            <p class="text-small">Любые способы <br>оплаты заказа</p>
                        </div><!-- /.col -->

                        <div class="col-md-4 col-sm-4 inner-bottom-xs">
                            <span class="auto"></span>
                            <p class="text-small">Автоматическое<br> информирование клиента <br>о ходе исполнения заказа<br>
                                по электронной почте</p>
                        </div><!-- /.col -->


                    </div><!-- /.row -->

                </div><!-- /.container -->
            </section>

            <!-- ============================================================= SECTION – просто и удобно для покупателя : END ============================================================= -->

			<!-- ============================================================= SECTION – GET IN TOUCH ============================================================= -->
			
			<section id="get-in-touch">
				<div class="container inner">
					<div class="row">
						<div class="col-md-10 col-sm-11 center-block text-center">
							<header>
								<h3>хотите увидеть нашу платформу в работе</h3>
								<p>Мы готовы продемонстрировать полный функционал </p>
							</header>
							<a href="#demo-form" class="btn btn-large fancybox">Заказать демо</a>
						</div><!-- /.col -->
					</div><!-- /.row -->
				</div><!-- /.container -->
			</section>
		

            <section id="product">
                <div class="container inner for_comments" style="padding-top: 45px">

                    <div class="row">
                        <div class="col-md-11 col-sm-12 center-block text-center ">
                            <header>
                                <h3>в России, украине и белоруси уже работает 123 сервиса
                                    на платформе print on demand solution</h3>
                                <p>Вот отзывы лишь некоторых из них</p>
                            </header>
                        </div><!-- /.col -->
                    </div><!-- /.row -->


                   
				<div id="reviews-list">	
                 <?php echo $this->renderPosition('reviews'); ?>
				</div>

                </div><!-- /.container -->
            </section>
			
			<script type="text/javascript">
			var $j = jQuery.noConflict();
			$j(document).ready(function(){
				$j('#reviews-list .comments:eq(0), #reviews-list .comments:eq(1)').wrapAll('<div class="row"></div>');
				$j('#reviews-list .comments:eq(2), #reviews-list .comments:eq(3)').wrapAll('<div class="row"></div>');
			});
		</script>