<?php

use yii\helpers\Html;
use yii\widgets\Menu;
use yii\widgets\Breadcrumbs;
use app\assets\AppAsset;

/**
 * @var \yii\web\View $this
 * @var string $content
 */
AppAsset::register($this);
?>
<?php $this->beginPage() ?>
<!DOCTYPE html>
<html lang="<?= Yii::$app->language ?>">
    <head>
        <meta charset="<?= Yii::$app->charset ?>"/>
        <meta name="viewport" content="width=device-width, initial-scale=1, maximum-scale=1, user-scalable=0"/> 
		<title><?= Html::encode($this->title) ?></title>
        <?php $this->head() ?>
    </head>
    <body class="skin-black fixed <?= $this->context->id ?> <?= $this->context->action->id ?>">
        <?php $this->beginBody() ?>
        <!-- header logo: style can be found in header.less -->
        <header class="header">
            <?=
            Html::a("NWC", ['/dashboard'], [
                'class' => 'logo'
            ]);
            ?>
            <!-- Header Navbar: style can be found in header.less -->
            <nav class="navbar navbar-static-top" role="navigation">                            
                <!-- Sidebar toggle button-->
                <a href="#" class="navbar-btn sidebar-toggle" 
                   onclick="$('.offcanvas').toggleClass('active').toggleClass('relative')" role="button">
                    <span class="sr-only">Toggle navigation</span>
                    <span class="icon-bar"></span>
                    <span class="icon-bar"></span>
                    <span class="icon-bar"></span>
                </a>

                <!-- Content Header (Page header) -->
                <section class="content-header">
                    <h1>
                        <?= ucfirst($this->context->id); ?>
                        <small>
                            <?= ucfirst($this->context->action->id); ?>
                        </small>
                    </h1>
					
                </section>
                <div class="navbar-right">
                    <ul class="nav navbar-nav">
                        <li class="dropdown">
                            <a href="#" class="dropdown-toggle">
                                <i class="glyphicon glyphicon-user"></i>
                                <span> &nbsp; <?= ucfirst(Yii::$app->user->identity->username); ?> <i class="caret"></i></span>
                            </a>
                            <?php
                            echo Menu::widget([
                                'encodeLabels' => false,
                                'options' => ['class' => 'dropdown-menu'],
                                'items' => [
                                    [
                                        'template' => '<a href="{url}" data-method="post">{label}</a>',
                                        'label' => '<i class="fa fa-power-off"></i> Logout',
                                        'url' => ['/site/logout'],
                                    ]
                                ]
                            ]);
                            ?>
                        </li>
                    </ul>
                </div>
                <div class="top-button">
                    <?php echo $this->blocks['top-button']; ?>
                </div>
            </nav>
        </header>
        <div class="wrapper offcanvas row-offcanvas row-offcanvas-left">
            <!-- Left side column. contains the logo and sidebar -->
            <aside class="left-side sidebar-offcanvas">                
                <!-- sidebar: style can be found in sidebar.less -->
                <section class="sidebar">
                    <!-- sidebar menu: : style can be found in sidebar.less -->
                    <?php
                    echo Menu::widget([
                        'encodeLabels' => false,
                        'options' => [
                            'class' => 'sidebar-menu'
                        ],
                        'items' => [
                            [
                                'label' => '<i class="fa fa-dashboard"></i> <span>Dashboard</span>',
                                'url' => ['dashboard/index']
                            ],
                            [
                                'label' => '<i class="fa fa-globe"></i> <span>Sites</span>',
                                'url' => ['sites/index']
                            ],
                            [
                                'label' => '<i class="fa fa-wrench"></i> <span>Settings</span>',
                                'url' => ['setting/index']
                            ],
                        ]
                    ]);
                    ?>

                </section>
                <!-- /.sidebar -->
            </aside>

            <!-- Right side column. Contains the navbar and content of the page -->
            <aside class="right-side">    
                <!-- Main content -->
                <section class="content">
                    <?= $content ?>
                </section><!-- /.content -->
            </aside><!-- /.right-side -->
        </div><!-- ./wrapper -->
        <?php $this->endBody() ?>
    </body>
</html>
<?php $this->endPage() ?>
