<h1>Hi, <?= $username ?></h1>
<strong>
  Link Route: <?= ($router->linkRoute('user_show', array('username' => 'fizz'))) ?>
</strong>
