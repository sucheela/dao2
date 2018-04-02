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
<!DOCTYPE html PUBLIC "-//W3C//DTD HTML 4.01//EN">
<html>
<head>
  <style>
body {
  background: #0373a3;
  color: #777;
  font-family: Arial, sans-serif, Helvetica;
  font-size: 1em;
  margin: 0px;
  padding: 0px;
}
a {
  color: #0373a3;
  text-decoration: none;
}
a:hover {
  color: #555;
  text-decoration: none;
}
.header {
  background-color: #0373a3;
  width: 100%;
  height: 50px;
}
.container {
  width: 100%;
  background-color: #fff;
  margin: 0px;
  padding: 15px;
}
.footer {
  background-color: #0373a3;
  text-align: center;
  padding: 20px 0;
  color: #fff;
}
.footer a {
  color: #fff;
}
  </style>
</head>
<body>
  <div class="header">
    <a href="http://dao.dating/"><img src="cid:dao-dating-logo-long"/></a>
  </div>
  <div class="container">
    <?= $this->fetch('content') ?>
  </div>
  <div class="footer">
    <p>Copyrights &copy; <?php echo date('Y') ?> Dao.Dating
      | <a href="http://dao.dating/privacy.php">Privacy Policy</a>
      | <a href="http://dao.dating/terms.php">Terms and Conditions</a></p>
  </div>
</body>
</html>
