<?php
// @TODO ページャ
// @TODO latest50件ごと表示
// @TODO 特定レスの表示

require_once dirname(__FILE__) . '/storage.php';

// @note スレッド内のレス番は、識別子の先頭8桁。

$pathinfo = explode('/', $_SERVER['PATH_INFO']);
$title = $pathinfo[1];
$threads = Storage::getThread($title);
if ($threads === false) {
    exit('対象のスレッドは存在しないか、巨大になりすぎているため表示できません。');
}

if (isset($pathinfo[2])) {
    $ident_prefix = $pathinfo[2];
    foreach (array_keys($threads) as $md5) {
        if ($ident_prefix == substr($md5, 0, 8)) {
            $threads = array($md5 => $threads[$md5]);
        }
    }
}

header('Content-Type: text/html; charset=UTF-8');
?>
<html>
<head>
  <title><?php echo $title; ?></title>
<link href="/css/bootstrap.css.1" rel="stylesheet">
<link href="/css/bootstrap-responsive.css" rel="stylesheet">
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
    <a class="brand" href="/">sgphp - 新月PHP実装</a>
  </li>
</ul>
    </div>
  </div>
</div>

<div class="container">
  <h1><?php echo $title; ?></h1>
  <hr>
  <?php $i = 1 ?>
  <?php foreach($threads as $thread): ?>
    <?php echo $i . ' : '  ?>
    <?php echo '<a href="/thread.php/' . $title . '/' . substr($thread['id'], 0, 8) . '">' . substr($thread['id'], 0, 8) . '</a>' ?>
    <span style="color:green;"><?php echo $thread['datetime']; ?></span><br />
    <?php echo $thread['body']; ?><br />
<?php if (isset($thread['attach']) && $thread['suffix'] == 'jpg') echo "<img src=\"data:image/jpg;base64,{$thread['attach']}\">"; ?>
    <hr>
    <?php $i += 1 ?>
  <?php endforeach; ?>
</div>
</body>
</html>
