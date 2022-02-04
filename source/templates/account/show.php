<h1> Hello <?= $_SESSION['logged_user']?> </h1>

<a href=<?= $this->generateURL('account/delete')?>> Delete your account </a>