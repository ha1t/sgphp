<?php
require_once dirname(__FILE__) . '/storage.php';

$items = Storage::getThreads();
header('Content-Type: text/html; charset=UTF-8');
?>
<html>
<head>
<meta charset="utf-8">
<title>sgphp gateway</title>
<link href="./css/bootstrap.css.1" rel="stylesheet">
<link href="./css/bootstrap-responsive.css" rel="stylesheet">
<script type="text/javascript">
  var _gaq = _gaq || [];
  _gaq.push(['_setAccount', 'UA-8020577-13']);
  _gaq.push(['_trackPageview']);

  (function() {
    var ga = document.createElement('script'); ga.type = 'text/javascript'; ga.async = true;
    ga.src = ('https:' == document.location.protocol ? 'https://ssl' : 'http://www') + '.google-analytics.com/ga.js';
    var s = document.getElementsByTagName('script')[0]; s.parentNode.insertBefore(ga, s);
  })();
</script>
</head>
<body>
<div class="navbar">
  <div class="navbar-inner">
    <div class="container">
<ul class="nav">
  <li>
    <a class="brand" href="#">sgphp - 新月PHP実装</a>
  </li>
</ul>
    </div>
  </div>
</div>

<div class="row">
  <div class="span6 alert">
    sgphpは開発途中です。本家の新月と比較すると、未実装の機能などがあるため、一部正常に動作していない画面などがあります。
  </div>
  <div class="span6">
    <h2>TODO</h2>
    <ul>
      <li>Serverの実装</li>
      <li>Serverの実装:投稿ができるように</li>
      <li>Threadのパーサを強化:attachを表示できるように</li>
    </ul>
  </div>
</div>

<div class="container">
<h2>スレッド一覧</h2>
<ul>
<?php foreach ($items as $item): ?>
<?php echo "<li>{$item['datetime']}&nbsp;<a href=\"thread.php/{$item['title']}\">{$item['title']}</a></li>" . PHP_EOL; ?>
<?php endforeach; ?>
</ul>
</div>

</body>
</html>
