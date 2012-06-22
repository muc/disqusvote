<?php
  include('lib/http.php');

  $data = json_decode(Http::connect('ergast.com')->doGet('api/f1/current.json'));
  $season = $data->MRData->RaceTable->season;
  $races = $data->MRData->RaceTable->Races;
?>

<!doctype html>
<html lang="en">
<head>
  <meta charset="utf-8">

  <title>Formel 1 <?php print $season; ?></title>

  <meta name="viewport" content="width=device-width,initial-scale=1">
  <link rel="stylesheet" href="css/bootstrap.css">
  <link rel="stylesheet" href="css/style.css">
  <link rel="stylesheet" href="css/bootstrap-responsive.css">
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
        <a class="brand" href="./">Formel 1 <?php print $season; ?></a>
        <div class="nav-collapse">
          <ul class="nav">
            <li class="active"><a href="./">Races</a></li>
            <li><a href="standings.php">Standings</a></li>
            <li><a href="test.html">Test</a></li>
          </ul>
        </div><!--/.nav-collapse -->
      </div>
    </div>
  </div>

  <div class="container">
    <div class="row">
      <div class="span12">
        <table class="table table-condensed">
          <thead>
            <tr>
              <th>#</th>
              <th>Race</th>
              <th>Date</th>
            </tr>
          </thead>
          <tbody>
            <?php foreach ($races as $race): ?>
            <?php
              $class = '';
              $today = strtotime('today');
              if ($today > strtotime($race->date)) {
                $class = 'race-done';
              }
              if ($today == strtotime($race->date)) {
                $class = 'race-active';
              }
            ?>
            <tr<?php print ($class ? ' class="' . $class . '"' : ''); ?>>
              <td><?php print $race->round; ?></td>
              <?php if ($today > strtotime($race->date)): ?>
                <td><a href="race.php?race=<?php print $race->round; ?>"><?php print $race->raceName; ?></a></td>
              <?php else: ?>
                <td><?php print $race->raceName; ?></td>
              <?php endif; ?>
              <td><?php print date('d.m.Y', strtotime($race->date)); ?></td>
            </tr>
            <?php endforeach; ?>
          </tbody>
        </table>
      </div>
    </div>
  </div>

  <script src="//ajax.googleapis.com/ajax/libs/jquery/1.7.2/jquery.min.js"></script>
  <script>window.jQuery || document.write('<script src="js/"><\/script>')</script>
  <script defer src="js/bootstrap-collapse.js"></script>

</body>
</html>
