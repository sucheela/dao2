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
    <?= $this->Html->css('font-awesome.min.css') ?>
    <?= $this->Html->css('global.css') ?>
    <?= $this->Html->css('message.css') ?>
    <?= $this->Html->css('home.css') ?>
    
    <?= $this->fetch('css') ?>
</head>
<body>

  <?php if (isset($is_loggedin)){ ?>
  <header class="hidden-print">
    <?php if (!$is_loggedin &&
              $this->fetch('title') == 'About'){ ?>
    <a href="/users/register">
      <div id="home-hero" class="hidden-xs">
        <div class="camera-overlay"></div>
        <div class="camera">
          <div class="camera-inner">
            <h2>Register for free!</h2>
            <p>Find your perfect match today</p>
            <h3>Get Started!</h3>
          </div><!-- .camera-inner -->
        </div><!-- .camera -->
    </a>   
    </div><!-- #home-hero -->
    <?php } // end if user not logged in ?>
      
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
            <?php if ($is_loggedin){ ?>
            <li class="dropdown">
              <a href="/matches" class="dropdown-toggle" data-toggle="dropdown"
                 role="button" aria-haspopup="true" aria-expanded="false">
                My Account<span class="caret"></span>
              </a>
              <ul class="dropdown-menu">
                <li><a href="/matches">My Matches</a></li>
                <li><a href="/favorites">My Favorites</a></li>
                <li><a href="/messages">My Messages (<?php echo $msg_num; ?>)</a></li>
                <li><a href="/visitors">Recent Visitors</a></li>
                <li><a href="/blocks">My Black List</a></li>
                <li><a href="/users/edit">My Profile</a></li>
              </ul>
            </li>
            <?php } else { // else if user not logged in ?>
            <li><a href="/users/login" class="login">Login</a></li>
            <?php } // end else if user not logged in ?>
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
              <?php echo ($is_loggedin ?
                    '<li><a href="/users/logout">Logout</a></li>' : '') ?>
          </ul>
        </div><!-- .navbar-right -->
      </div><!-- .container-fluid -->
    </nav>
  </header>
  <?php } else {  // elseif !isset($is_loggedin) ?>
  <header>
    <nav class="navbar navbar-default navbar-static-top">
      <div class="container-fluid">
        <div class="navbar-header">
          <a class="navbar-brand" href="/">
            <span>Dao.Dating</span>
          </a>
        </div><!-- .navbar-header -->
      </div><!-- .container-fluid -->
    </nav>
  </header>
  <?php } // end elseif !isset($is_loggedin) ?>

  <div class="container-fluid">
    <div class="container-inner flash">
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
  <?= $this->Html->script('global.js') ?>
  <?= $this->Html->script('message.js') ?>
  <?= $this->fetch('script') ?>
</body>
</html>
