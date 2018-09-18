<?php
//echo "<pre>"; var_dump(Yii::app()->user); die;
?>
<!-- Off Canvas Menu -->
<nav id="off-canvas-menu" >
    <div id="off-canvas-menu-title">
        MENU<span class="icon icon-cancel-3" id="off-canvas-menu-close"></span>
    </div>
    <ul class="expander-list">
        <li><span class="name"> <span class="expander">-</span> <a href="#">Productos</a> </span>
            <ul>
                <?php
                foreach ($nivel0 as $n0) {
                    $link = $n0['hijos'] ? '#' : Yii::app()->createAbsoluteUrl('/productos/categoria/' . $n0['categoria_id']);
                    ?>
                    <li>
                        <span class="name"> <?php if ($n0['hijos']) { ?><span class="expander">-</span> <?php } ?>
                            <a href="<?php echo $link ?>"><span class="icon-checkmark-3"></span><?php echo $n0['etiqueta_web']; ?></a>
                        </span>
                    <?php
                    if ($n0['hijos']) {
                        ?>
                        <ul>
                        <?php
                        foreach ($n0['hijos'] as $hijo) {
                            $link = Yii::app()->createAbsoluteUrl('/productos/categoria/' . $hijo['categoria_id']);
                            ?>
                            <li><span class="name"><a href="<?php echo $link ?>"><?php echo $hijo['etiqueta_web'] ?></a></span></li>
                            <?php
                        }
                        ?>
                        </ul>
                        <?php
                    }
                }
                ?>
            </ul>
        </li>
    </ul>
</nav>
<!-- //end Off Canvas Menu -->

<div id="outer">
    <div id="outer-canvas"> <!-- Navbar -->
        <header>

        <!-- Back to top -->
        <div class="back-to-top"><span class="icon-arrow-up-4"></span></div>
        <!-- //end Back to top -->

        <div class=" hidden-xs hidden-sm hidden-md" style="position: absolute; width: 100%; background: #f09700; height: 50px; bottom: 0; z-index: 999"></div>
        <section class="navbar">
            <div class="background">
                <div class="container">
                    <!-- Logo -->
                    <div class="navbar-logo pull-left col-lg-3"> <a href="<?php echo Yii::app()->createAbsoluteUrl('/') ?>"><img src="<?php echo Yii::app()->theme->baseUrl; ?>/images/header-logo.png" alt="RAC"></a></div>
                    <div class="navbar-welcome pull-left compact-hidden hidden-xs col-sm-6 col-lg-6">
                        <form action="<?php echo Yii::app()->createAbsoluteUrl('/productos/search') ?>" method="GET" id="topSearchForm">
                            <input name="keyword" type="text" class="form-control" placeholder="Buscá por palabras y encontrá fácilmente lo que querés" />
                        </form>
                    </div>
                    <div class="clearfix visible-sm"></div>
                    <!-- //end Logo -->

                    <!-- Secondary menu -->
                    <div class="navbar-secondary-menu pull-right hidden-xs hidden-sm hidden-md col-lg-3">
                        <?php if (Yii::app()->user->id) { ?>
                            <span class="welcome-user">Bienvenido, <?php echo Yii::app()->user->name ?>!</span>
                        <?php } ?>
                        <div class="btn-group compact-hidden"> <a href="#"  class="btn btn-xs btn-default dropdown-toggle" data-toggle="dropdown"> <span class="icon icon-vcard"></span> Cuenta <span class="caret"></span> </a>
                            <ul class="dropdown-menu" role="menu">
                                <li><a href="<?php echo Yii::app()->createAbsoluteUrl('/user/profile/edit') ?>">Cuenta</a></li>
                                <li class="hidden"><a href="#">Marcados</a></li>
                                <li><a href="<?php echo Yii::app()->createAbsoluteUrl('/productos/checkout') ?>">Checkout</a></li>
                                <li class="divider"></li>
                                <?php if (!Yii::app()->user->id) { ?>
                                    <li><a href="<?php echo Yii::app()->createAbsoluteUrl('/user/login') ?>">Login</a></li>
                                    <li><a href="<?php echo Yii::app()->createAbsoluteUrl('/user/registration') ?>">Registro</a></li>
                                <?php } ?>
                                <?php if (Yii::app()->user->id) { ?>
                                    <li><a href="<?php echo Yii::app()->createAbsoluteUrl('/user/logout') ?>">Logout</a></li>
                                <?php } ?>
                            </ul>
                        </div>

                        <?php if ($comparador_enabled == 1) { ?>
                        <div class="btn-group compact-hidden"> <a href="#" class="btn btn-xs btn-default dropdown-toggle" data-toggle="dropdown"> <span class="icon icon-justice"></span> Comparar (0) <span class="caret"></span> </a>
                            <div class="dropdown-menu pull-right shoppingcart-box empty" role="menu"> No hay productos para comparar </div>
                        </div>
                        <?php } ?>

                        <div class="btn-group">
                            <a
                                href="#"
                                class="btn btn-xs btn-default dropdown-toggle"
                                data-toggle="dropdown">
                                <span class="compact-hidden">
                                    Carrito - <strong>$<span class="menuCartTotal"><?php echo Commons::formatPrice($cartItems['total']) ?></span></strong>
                                </span>
                                <span class="icon-xcart-animate">
                                    <span class="box menuCartItemsCount"><?php echo $cartItems['itemsCount'] ?></span>
                                    <span class="handle"></span>
                                </span>
                            </a>
                            <div class="dropdown-menu pull-right shoppingcart-box shoppingcart-box-desktop" role="menu">
                                <?php
                                Yii::app()->controller->renderFile(
                                    Yii::app()->basePath.'/views/shared/_menuCart.php',
                                    array(
                                        'cartItems' => $cartItems
                                    )
                                );
                                ?>
                            </div>
                        </div>
                    </div>

                    <!-- Main menu -->
                    <dl class="navbar-main-menu hidden-xs hidden-sm hidden-md">
                        <dt class="item">
                            <ul class="sf-menu">
                                <li><a href="<?php echo Yii::app()->createAbsoluteUrl('/'); ?>" class="btn-main btn-home"></a></li>
                            </ul>
                        </dt>
                        <dd></dd>

                        <?php
                        foreach ($nivel0 as $n0) {
                            $link = $n0['hijos'] ? '#' : Yii::app()->createAbsoluteUrl('/productos/categoria/' . $n0['categoria_id']);
                            ?>
                            <dt class="item compact-hidden">
                                <a href="<?php echo $link ?>" class="btn-main line"><?php echo $n0['etiqueta_web']; ?></a>
                            </dt>
                            <?php
                            if ($n0['hijos']) {
                                ?>
                                <dd class="item-content">
                                    <div class="navbar-main-submenu">
                                        <div class="wrapper-border">
                                            <div class="row">
                                                <!-- caregories -->
                                                <div class="col-xs-9">
                                                    <div class="row">
                                                        <?php
                                                        foreach ($n0['hijos'] as $hijo) {
                                                            $link = Yii::app()->createAbsoluteUrl('/productos/categoria/' . $hijo['categoria_id']);
                                                            if ($hijo['es_accesorios'] == 1) {
                                                                ?>
                                                                </div>
                                                                <div class="row">
                                                                <?php
                                                            }
                                                            ?>
                                                            <?php
                                                            if ($hijo['es_accesorios'] == 1) {
                                                                ?>
                                                                <div class="col-xs-6 col-md-12 col-lg-12 level1-menu level-accesorios">
                                                                <?php
                                                            } else {
                                                                ?>
                                                                <div class="col-xs-6 col-md-4 col-lg-3 level1-menu">
                                                                <?php
                                                            }
                                                            ?>
                                                                <div class="submenu-block">
                                                                    <span class="icon-checkmark-3"></span>
                                                                    <a class="name" href="<?php echo $link ?>"><?php echo $hijo['etiqueta_web'] ?></a>
                                                                    <?php
                                                                    if ($hijo['hijos']) {
                                                                        ?>
                                                                        <ul>
                                                                        <?php
                                                                        foreach ($hijo['hijos'] as $hijon2) {
                                                                            $link = Yii::app()->createAbsoluteUrl('/productos/categoria/' . $hijon2['categoria_id']);
                                                                            ?>
                                                                            <li><a href="<?php echo $link; ?>"><?php echo $hijon2['etiqueta_web'] ?></a></li>
                                                                            <?php
                                                                        }
                                                                        ?>
                                                                        </ul>
                                                                        <?php
                                                                    }
                                                                    ?>
                                                                </div>
                                                            </div>
                                                            <?php
                                                        }
                                                        ?>
                                                    </div>
                                                </div>
                                                <!-- //end caregories -->

                                                <!-- html block -->
                                                <div class="col-xs-3">
                                                    <div class="img-fullheight">
                                                        <img
                                                            class="img-responsive"
                                                            src="<?php echo Yii::app()->baseUrl; ?>/../repository/<?php echo $n0['imagen'] ?>"
                                                            alt="" />
                                                    </div>
                                                </div>
                                                <!-- //end html block -->
                                            </div>
                                        </div>
                                    </div>
                                </dd>
                                <?php
                            }
                        }
                        ?>
                    </dl>
                    <!-- //end Main menu -->

                </div>
            </div>

            <!-- Mobile menu -->
            <div class="container visible-xs visible-sm visible-md">
              <div class="mobile-nav row">
                <div class="nav-item item-01"><a href="#" id="off-canvas-menu-toggle"><span class="icon icon-list-4"></span></a></div>
                <div class="nav-item item-02"><a href="#"><span class="icon icon-vcard"></span></a>
                  <div class="tab-content">
                    <ul class="menu-list">
                      <li><a href="<?php echo Yii::app()->createAbsoluteUrl('/user/profile/edit') ?>">Cuenta</a></li>
                      <li class="hidden"><a href="#">Marcados</a></li>
                      <li><a href="<?php echo Yii::app()->createAbsoluteUrl('/productos/checkout') ?>">Checkout</a></li>
                      <li><a href="<?php echo Yii::app()->createAbsoluteUrl('/user/login') ?>">Login</a></li>
                      <li><a href="<?php echo Yii::app()->createAbsoluteUrl('/user/register') ?>">Registro</a></li>
                    </ul>
                  </div>
                </div>
                <div class="nav-item item-03 hidden"><a href="#"><span class="icon icon-search-2"></span></a>
                  <div class="tab-content"> <!-- Search -->
                    <form class="navbar-search form-inline" role="form">
                      <div class="form-group">
                        <button type="submit" class="button"><span class="icon-search-2"></span></button>
                        <input type="text" class="form-control" placeholder="Buscar" />
                      </div>
                    </form>
                    <!-- //end Search -->
                  </div>
                </div>
                <div class="nav-item item-04"><a href="#"><span class="icon-xcart-white menuCartItemsCount"><?php echo $cartItems['itemsCount'] ?></span></a>
                  <div class="tab-content">
                    <div class="shoppingcart-box shoppingcart-box-mobile">
                        <?php
                        $this->renderFile(
                            Yii::app()->basePath.'/views/shared/_menuCartMobile.php',
                            array(
                                'cartItems' => $cartItems
                            )
                        );
                        ?>
                    </div>
                  </div>
                </div>
              </div>
            </div>
            <!-- //end Mobile menu -->

            <!-- Navbar switcher -->
            <div class="navbar-switcher-container">
                <div class="navbar-switcher"> <span class="i-inactive"><img src="<?php echo Yii::app()->theme->baseUrl; ?>/images/icon-megatron.png" width="35" height="35" alt=""></span> <span class="i-active icon-cancel-3"></span> </div>
            </div>
            <!-- //end Navbar switcher -->
        </section>

        <!-- Navbar height -->
        <div class="navbar-height-inner"></div>
        <!-- Navbar height -->

        <!-- Navbar height -->
        <div class="navbar-height"></div>
        <!-- Navbar height -->

        </header>
        <!-- //end Navbar -->