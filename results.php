<?php include('lib/disqus.php'); ?>
<?php include('lib/disqus.api.keys.php'); ?>
<?php
  $params = array(
    'forum' => 'stadtlandflussvoting',
    'thread' => '736430416',
  );
  $disqus = Disqus::connect(ACCESS_TOKEN, API_KEY, API_SECRET)->getPosts($params);
?>
<!DOCTYPE html>
<html>
<head>
    <meta charset="utf-8">
    <title>Disqus Vote</title>
    <meta name="description" content="">
    <meta name="viewport" content="width=device-width">

    <link rel="stylesheet" href="css/bootstrap.css">
    <link rel="stylesheet" href="css/styles.css">
    <link rel="stylesheet" href="css/bootstrap-responsive.css">

    <script type="text/javascript" src="https://www.google.com/jsapi"></script>
</head>
<body>
    <div class="navbar navbar-fixed-top">
      <div class="navbar-inner">
        <div class="container">
          <a class="btn btn-navbar" data-toggle="collapse" data-target=".nav-collapse">
            <span class="icon-bar"></span>
            <span class="icon-bar"></span>
            <span class="icon-bar"></span>
          </a>
          <a class="brand" href="./">Vote via Disqus</a>
          <div class="nav-collapse">
            <ul class="nav">
              <li><a href="./">Proposals</a></li>
              <li class="active"><a href="results.php">Results</a></li>
            </ul>
          </div><!--/.nav-collapse -->
        </div>
      </div>
    </div>

    <div class="container">
    <?php if ($disqus->success()): ?>
      <div class="page-header">
        <h1>Results via DisqusAPI</h1>
      </div>
      <div class="row">
        <div class="span4">
          <h2>Most Likes</h2>
          <?php $posts = $disqus->getMostLikes(); ?>
          <?php foreach ($posts['posts'] as $post): ?>
            <div class="row">
              <div class="span2"><?php print $post->raw_message; ?></div>
              <?php $percent = $posts['summary_likes'] != 0 ? (int) (($post->likes / $posts['summary_likes']) * 100) : 0; ?>
              <div class="span2" style="text-align:right;"><?php print $percent; ?>%</div>
            </div>
            <div class="progress">
              <div class="bar" style="width: <?php print $percent; ?>%;"></div>
            </div>
          <?php endforeach ?>
        </div>

        <div class="span4 offset2">
          <h2>Most Dislikes</h2>
          <?php $posts = $disqus->getMostDislikes(); ?>
          <?php foreach ($posts['posts'] as $post): ?>
            <div class="row">
              <div class="span2"><?php print $post->raw_message; ?></div>
              <?php $percent = $posts['summary_dislikes'] != 0 ? (int) (($post->dislikes / $posts['summary_dislikes']) * 100) : 0; ?>
              <div class="span2" style="text-align:right;"><?php print $percent; ?>%</div>
            </div>
            <div class="progress">
              <div class="bar" style="width: <?php print $percent; ?>%;"></div>
            </div>
          <?php endforeach ?>
        </div>

      </div>
      <div class="row">
        <div class="span4">
          <h2>Most Votes</h2>
          <?php $posts = $disqus->getMostVotes(); ?>
          <?php foreach ($posts['posts'] as $post): ?>
            <div class="row">
              <div class="span2"><?php print $post->raw_message; ?></div>
              <?php $percent = $posts['summary_likes'] != 0 ? (int) (($post->votes / $posts['summary_votes']) * 100) : 0; ?>
              <div class="span2" style="text-align:right;"><?php print $percent; ?>%</div>
            </div>
            <div class="progress">
              <div class="bar" style="width: <?php print $percent; ?>%;"></div>
            </div>
          <?php endforeach ?>
        </div>

        <div class="span4 offset2">
          <h2>GoogleChartsAPI MostLikes</h2>
          <div id="chart_div"></div>
          <?php $posts = $disqus->getMostVotes(); ?>
          <script type="text/javascript">
            // Load the Visualization API and the piechart package.
            google.load('visualization', '1.0', {'packages':['corechart']});
            // Set a callback to run when the Google Visualization API is loaded.
            google.setOnLoadCallback(drawChart);

            function drawChart() {
              var data = new google.visualization.DataTable();
              data.addColumn('string', 'Proposals');
              data.addColumn('number', 'Likes');

              <?php 
                print 'var max_value=' . ($posts['posts'][0]->votes + 1) . ';';
                foreach ($posts['posts'] as $post) {
                  print "data.addRow(['$post->raw_message', $post->votes]);";
                }
              ?>

              // Set chart options
              var options = {
                hAxis: {
                  minValue: 0,
                  gridlines: {count:max_value}
                },
                legend: {position:'top', alignment: 'start'},
              };

              // Instantiate and draw our chart, passing in some options.
              var chart = new google.visualization.BarChart(document.getElementById('chart_div'));
              chart.draw(data, options);
            }
          </script>
        </div>
      </div>

    <?php endif ?>
    </div> <!-- /container -->
    
    <script src="//ajax.googleapis.com/ajax/libs/jquery/1.7.2/jquery.min.js"></script>
    <script>window.jQuery || document.write('<script src="js/jquery.js"><\/script>')</script>
    <script src="js/bootstrap.js"</script>
    <script src="js/bootstrap-collapse.js"</script>
</body>
</html>