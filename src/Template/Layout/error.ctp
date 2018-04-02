<?php
/**
 * CakePHP(tm) : Rapid Development Framework (https://cakephp.org)
 * Copyright (c) Cake Software Foundation, Inc. (https://cakefoundation.org)
 *
 * Licensed under The MIT License
 * For full copyright and license information, please see the LICENSE.txt
 * Redistributions of files must retain the above copyright notice.
 *
 * @copyright     Copyright (c) Cake Software Foundation, Inc. (https://cakefoundation.org)
 * @link          https://cakephp.org CakePHP(tm) Project
 * @since         0.10.0
 * @license       https://opensource.org/licenses/mit-license.php MIT License
 */
?>
<!DOCTYPE html>
<html>
<head>
    <?= $this->Html->charset() ?>
    <meta http-equiv="X-UA-Compatible" content="IE=edge"/>
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>
        Dao.Dating: <?= $this->fetch('title') ?>
    </title>
    <?= $this->Html->meta('icon') ?>

    <?= $this->Html->css('bootstrap.min.css') ?>
    <?= $this->Html->css('global.css') ?>

    <?= $this->fetch('meta') ?>
    <?= $this->fetch('css') ?>
</head>
<body>
  <header>
    <nav class="navbar navbar-default navbar-static-top">
      <div class="container-fluid">
        <div class="navbar-header">
          <button type="button" class="navbar-toggle collapsed" data-toggle="collapse" data-target="#navbar-top-collapse" aria-expanded="false">
            <span class="sr-only">Toggle navigation</span>
            <span class="icon-bar"></span>
            <span class="icon-bar"></span>
            <span class="icon-bar"></span>
          </button>
          <a class="navbar-brand" href="/">
            <span>Dao.Dating</span>
          </a>
        </div><!-- .navbar-header -->
        <div class="collapse navbar-collapse" id="navbar-top-collapse">
          <ul class="nav navbar-nav navbar-right">
            <li class="dropdown">
              <a href="/" class="dropdown-toggle" data-toggle="dropdown"
                 role="button" aria-haspopup="true" aria-expanded="false">
                About Dao.Dating<span class="caret"></span>
              </a>
              <ul class="dropdown-menu">
                <li><a href="/#chinese-astrology">Chinese Astrology</a></li>
                <li><a href="/#who-we-are">Who We Are</a></li>
              </ul>
            </li>
            <li><a href="/#contacts">Contacts</a>
          </ul>
        </div><!-- .navbar-right -->
      </div><!-- .container-fluid -->
    </nav>
  </header>
  
    <div class="container-fluid">
        <div class="container-inner">
            <h1><?= __('Error') ?></h1>
            <?= $this->Flash->render() ?>

            <?= $this->fetch('content') ?>
        </div>
    </div>
    
  <footer>
    <p>Copyrights &copy; <?php echo date('Y') ?> Dao.Dating
      | <a href="privacy.php">Privacy Policy</a>
      | <a href="terms.php">Terms and Conditions</a></p>
  </footer>

  <?= $this->Html->script('jquery.min.js') ?>
  <?= $this->Html->script('bootstrap.min.js') ?>
  <?= $this->fetch('script') ?>

</body>
</html>
