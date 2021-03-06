<?php include('lib/krumo/class.krumo.php'); ?>
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
      <div class="row">

        <!-- submenu -->
        <div class="span3">
          <div class="well sidenav">
            <ul class="nav nav-list">
              <li class="nav-header">Result Output Style</li>
              <li class="active"><a id="css-result" href="#"><i class="icon-tasks"></i> CSS</a></li>
              <li><a id="gc-result" href="#"><i class="icon-picture "></i> Google Charts</a></li>
            </ul>
          </div>
        </div>

        <!-- css-results -->
        <div class="span9 css-results">
          <div class="page-header">
            <h1>Results via CSS</h1>
          </div>
          <?php if (!$disqus->success()): ?>
            <div class="alert alert-error">
              <h4 class="alert-heading">Error!</h4>
              <?php print $disqus->errorMessage(); ?>
            </div>
          <?php else: ?>

            <div class="results-block">
              <h2>Most Likes</h2>
              <?php $posts = $disqus->getMostLikes(); ?>
              <?php foreach ($posts['posts'] as $post): ?>
                <?php if ($post->likes): ?>
                  <div class="row">
                    <div class="span6"><?php print $post->raw_message; ?></div>
                    <?php $percent = $posts['summary_likes'] != 0 ? (int) round((($post->likes / $posts['summary_likes']) * 100), 0) : 0; ?>
                    <div class="span3" style="text-align:right;"><?php print $percent; ?>%</div>
                  </div>
                  <div class="progress progress-success">
                    <div class="bar" style="width: <?php print $percent; ?>%;"></div>
                  </div>
                <?php endif; ?>
              <?php endforeach ?>
            </div>

            <div class="results-block">
              <h2>Most Dislikes</h2>
              <?php $posts = $disqus->getMostDislikes(); ?>
              <?php foreach ($posts['posts'] as $post): ?>
                <?php if ($post->dislikes): ?>
                  <div class="row">
                    <div class="span2"><?php print $post->raw_message; ?></div>
                    <?php $percent = $posts['summary_dislikes'] != 0 ? (int) round((($post->dislikes / $posts['summary_dislikes']) * 100), 0) : 0; ?>
                    <div class="span2" style="text-align:right;"><?php print $percent; ?>%</div>
                  </div>
                  <div class="progress progress-danger">
                    <div class="bar" style="width: <?php print $percent; ?>%;"></div>
                  </div>
                <?php endif; ?>
              <?php endforeach ?>
            </div>

            <div class="results-block">
              <h2>Most Votes</h2>
              <?php $posts = $disqus->getMostVotes(); ?>
              <?php foreach ($posts['posts'] as $post): ?>
                <?php if ($post->votes): ?>
                  <div class="row">
                    <div class="span2"><?php print $post->raw_message; ?></div>
                    <?php $percent = $posts['summary_votes'] != 0 ? (int) round((($post->votes / $posts['summary_votes']) * 100), 0) : 0; ?>
                    <div class="span2" style="text-align:right;"><?php print $percent; ?>%</div>
                  </div>
                  <div class="progress progress-warning">
                    <div class="bar" style="width: <?php print $percent; ?>%;"></div>
                  </div>
                <?php endif; ?>
              <?php endforeach ?>
            </div>
            
          <?php endif; ?>
        </div>

        <!-- google-chats-results -->
        <div class="span9 gc-results">
          <div class="page-header">
            <h1>Results via GoogleCharts API</h1>
          </div>
          <?php if (!$disqus->success()): ?>
            <p>no results</p>
          <?php else: ?>

            <div class="results-block">
              <h2>Most Likes</h2>
              <div id="like_chart_div"></div>
              <?php $posts = $disqus->getMostLikes(); ?>
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
                    print 'var max_value=' . ($posts['posts'][0]->likes + 1) . ';';
                    foreach ($posts['posts'] as $post) {
                      if ($post->likes) {
                        print "data.addRow(['$post->raw_message', $post->likes]);";
                      }
                    }
                  ?>

                  // Set chart options
                  var options = {
                    hAxis: {
                      minValue: 0,
                      gridlines: {count:max_value}
                    },
                    legend: {position:'top', alignment: 'start'},
                    'width':600,
                    'height':300
                  };

                  // Instantiate and draw our chart, passing in some options.
                  var chart = new google.visualization.BarChart(document.getElementById('like_chart_div'));
                  chart.draw(data, options);
                }
              </script>
            </div>

            <div class="results-block">
              <h2>Most <strike>Dislikes</strike> Likes</h2>
              <div id="dislike_chart_div"></div>
              
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
                    foreach ($posts['posts'] as $post) {
                      if ($post->likes) {
                        print "data.addRow(['$post->raw_message', $post->likes]);";
                      }
                    }
                  ?>

                  // Set chart options
                  var options = {
                    'width':600,
                    'height':400,
                    legend: {position:'right', alignment: 'start'},
                    // chartArea:{left:10, top:0, width:"75%"}
                  };

                  // Instantiate and draw our chart, passing in some options.
                  var chart = new google.visualization.PieChart(document.getElementById('dislike_chart_div'));
                  chart.draw(data, options);
                }
              </script>
            </div>

            <div class="results-block">
              <h2>Most Votes</h2>
              <div id="votes_chart_div"></div>
              <?php $posts = $disqus->getMostVotes(); ?>
              <script type="text/javascript">
                // Load the Visualization API and the piechart package.
                google.load('visualization', '1.0', {'packages':['corechart']});
                // Set a callback to run when the Google Visualization API is loaded.
                google.setOnLoadCallback(drawChart);

                function drawChart() {
                  var data = new google.visualization.DataTable();
                  data.addColumn('string', 'Proposals');
                  data.addColumn('number', 'Votes');
                  data.addColumn('number', 'Likes');
                  data.addColumn('number', 'Dislikes');

                  <?php 
                    print 'var max_value=' . ($posts['posts'][0]->votes + 1) . ';';
                    foreach ($posts['posts'] as $post) {
                      if ($post->votes) {
                        print "data.addRow(['$post->raw_message', $post->votes, $post->likes, $post->dislikes]);";
                      }
                    }
                  ?>

                  // Set chart options
                  var options = {
                    vAxis: {
                      minValue: 0,
                      gridlines: {count:max_value}
                    },
                    colors: ['blue', 'green', 'red'],
                    'width':600,
                    'height':300
                  };

                  // Instantiate and draw our chart, passing in some options.
                  var chart = new google.visualization.ColumnChart(document.getElementById('votes_chart_div'));
                  chart.draw(data, options);
                }
              </script>
            </div>
            
          <?php endif; ?>
        </div>

      </div> <!-- end row -->
    </div> <!-- /container -->
    
    <script src="//ajax.googleapis.com/ajax/libs/jquery/1.7.2/jquery.min.js"></script>
    <script>window.jQuery || document.write('<script src="js/jquery.js"><\/script>')</script>
    <script src="js/scripts.js"</script>
    <script src="js/bootstrap.js"</script>
    <script src="js/bootstrap-collapse.js"</script>
    
</body>
</html>