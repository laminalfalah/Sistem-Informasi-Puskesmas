<?php include_once '../master/header.php'; ?>
  <div class="login-box">
    <div class="login-logo">
      <a href="<?= BASE_URL . 'home/' ?>">
        <b><?= APPNAME; ?></b>
      </a>
    </div>
    <div class="login-box-body">
      <p class="login-box-msg">Please Login to Dashboard</p>
      <div id="message"></div>
      <form method="POST" id="login">
        <div class="form-group has-feedback">
          <span class="fa fa-user form-control-feedback"></span>
          <input type="text" name="username" class="form-control" placeholder="Username" required>
        </div>
        <div class="form-group has-feedback">
          <span class="glyphicon glyphicon-lock form-control-feedback"></span>
          <input type="password" name="password" class="form-control" placeholder="Password" required>
        </div>
        <div class="row">
          <!--div class="col-xs-4 pull-left">
            <a href="<?= BASE_URL . 'home/' ?>" class="btn btn-default btn-block">Home</a>
          </div-->
          <div class="col-xs-4 pull-right">
            <input type="submit" class="btn btn-info btn-block" value="Login">
          </div>
        </div>
      </form>
    </div>
  </div>
<?php include_once '../master/footer.php'; ?>
