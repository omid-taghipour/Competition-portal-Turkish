<!DOCTYPE html>
<html style="font-size: 16px;">

<head>
  <meta name="viewport" content="width=device-width, initial-scale=1.0">
  <meta charset="utf-8">
  <meta name="keywords" content="First 3 highest score groups">
  <meta name="description" content="">
  <meta name="page_type" content="np-template-header-footer-from-plugin">
  <title>Results</title>
  <link rel="stylesheet" href="nicepage.css" media="screen">
  <link rel="stylesheet" href="Results.css" media="screen">
  <script class="u-script" type="text/javascript" src="jquery.js" defer=""></script>
  <script class="u-script" type="text/javascript" src="nicepage.js" defer=""></script>
  <meta name="generator" content="Nicepage 4.8.2, nicepage.com">
  <link rel="stylesheet" href="https://cdn.jsdelivr.net/npm/bootstrap@4.0.0/dist/css/bootstrap.min.css" integrity="sha384-Gn5384xqQ1aoWXA+058RXPxPg6fy4IWvTNh0E263XmFcJlSAwiGgFAW/dAiS6JXm" crossorigin="anonymous">
  <link id="u-theme-google-font" rel="stylesheet" href="https://fonts.googleapis.com/css?family=Roboto:100,100i,300,300i,400,400i,500,500i,700,700i,900,900i|Open+Sans:300,300i,400,400i,500,500i,600,600i,700,700i,800,800i">


  <script type="application/ld+json">
    {
      "@context": "http://schema.org",
      "@type": "Organization",
      "name": "",
      "logo": "images/logo-2.png",
      "sameAs": [
        "https://www.facebook.com/daubitek",
        "mailto:bitek@emu.edu.tr?subject=Contact%20us",
        "tel:+90 392 630 12 45"
      ]
    }
  </script>
  <meta name="theme-color" content="#478ac9">
  <meta property="og:title" content="Results">
  <meta property="og:type" content="website">
</head>

<body class="u-body u-xl-mode">
  <?php
  require_once('incs/login.check.inc.php');
  include_once('incs/header.inc.php');
  require_once('incs/db.inc.php');

  $query = "SELECT * FROM schools ORDER BY result DESC";
  $stmt = $conn->prepare($query);
  $stmt->execute();
  $school_result = $stmt->fetchAll(PDO::FETCH_ASSOC);

  ?>
  <section>
    <div class="u-clearfix u-sheet u-valign-middle u-sheet-1">
      <!-- <br> -->
      <!-- <div class="bg-white clearfix">
        <button type="button" class="btn btn-primary float-left" id="average-tab">Schools averages</button>
        <button type="button" class="btn btn-primary float-right" id="result-tab">Overall results</button>
      </div> -->
      <!-- <br> -->
      <br><br>
      <div class="jumbotron jumbotron-fluid">
        <h2 class="display-4 text-center">Competition result is shown in the tables below. </h2>
      </div>

      <div class="table-responsive" id="over-div">
        <h2 class="text-center">Overall scores</h2>
        <!-- FINAL RESULTS -->
        <table class="table table-hover" id="overall-result-table">
          <thead>
            <tr>
              <th scope="col" class="number-column">#</th>
              <th scope="col">School Name</th>
              <th scope="col">City</th>
              <th scope="col">Score</th>
            </tr>
          </thead>
          <tbody>
            <?php
            if (count($school_result) > 0) {
              for ($i = 0; $i < count($school_result); $i++) {
                if ($i < 3) {
                  if ($i == 0) {
                    //first place
                    echo '<tr title="first_place">';
                    echo '<th scope="row" class="number-column"><img src="images/first_place.png" class="place-icon"></th>';
                    echo '<td >' . $school_result[$i]['school_name'] . '</td>';
                    echo '<td>' . $school_result[$i]['city'] . '</td>';
                    echo '<td>' . $school_result[$i]['result'] . '</td>';
                    echo '</tr>';
                  } elseif ($i == 1) {
                    echo '<tr title="first_place">';
                    echo '<th scope="row" class="number-column"><img src="images/second_place.png" class="place-icon"></th>';
                    echo '<td >' . $school_result[$i]['school_name'] . '</td>';
                    echo '<td>' . $school_result[$i]['city'] . '</td>';
                    echo '<td>' . $school_result[$i]['result'] . '</td>';
                    echo '</tr>';
                  } else {
                    echo '<tr title="first_place">';
                    echo '<th scope="row" class="number-column"><img src="images/third_place.png" class="place-icon"></th>';
                    echo '<td >' . $school_result[$i]['school_name'] . '</td>';
                    echo '<td>' . $school_result[$i]['city'] . '</td>';
                    echo '<td>' . $school_result[$i]['result'] . '</td>';
                    echo '</tr>';
                  }
                } else {
                  echo '<tr>';
                  echo '<th scope="row" class="number-column">' . ($i + 1) . '</th>';
                  echo '<td >' . $school_result[$i]['school_name'] . '</td>';
                  echo '<td>' . $school_result[$i]['city'] . '</td>';
                  echo '<td>' . $school_result[$i]['result'] . '</td>';
                  echo '</tr>';
                }
              }
            }
            ?>
          </tbody>
        </table>
      </div>
      <br>
      <br>
      <!-- AVERAGE RESULTS -->
      <div class="table-responsive" id="average-results-div">
        <h2 class="text-center">Average of grades given to each school for each criteria</h2>

        <table class="table table-hover">
          <thead>
            <tr>
              <th scope="col"></th>
              <?php
              $query = "SELECT COUNT(*) FROM schools";
              $stmt = $conn->prepare($query);
              $stmt->execute();
              $school_number = $stmt->fetchColumn();

              $query = "SELECT COUNT(*) FROM criterias";
              $stmt = $conn->prepare($query);
              $stmt->execute();
              $criteria_number = $stmt->fetchColumn();

              $query = "SELECT criterias.criteria_id, criterias.criteria_name, criterias.percent, schools.school_id, schools.school_name, schools.result, grades.grade
                          FROM schools, criterias, grades
                          WHERE grades.school_id = schools.school_id and grades.criteria_id = criterias.criteria_id
                          ORDER BY criterias.criteria_id ASC ,schools.school_id ASC";
              $stmt = $conn->prepare($query);
              $stmt->execute();
              $result = $stmt->fetchAll(PDO::FETCH_ASSOC);
              // print_r($result);

              for ($i = 0; $i <= $school_number; $i++) {
                $flag = FALSE;
                foreach ($result as $idnex => $value) {
                  if (($value['school_id'] == $i) && ($flag == FALSE)) {
                    echo '<th scope="col">' . $value['school_name'] . '</th>';
                    $flag = true;
                  }
                }
              }
              ?>
            </tr>
          </thead>
          <tbody>
            <?php
            for ($i = 1; $i <= $criteria_number; $i++) {
              $query = "SELECT DISTINCT  criterias.criteria_name, criterias.percent
                        FROM criterias
                        WHERE criterias.criteria_id = ? ";
              $stmt = $conn->prepare($query);
              $stmt->execute([$i]);
              $row_result = $stmt->fetchAll(PDO::FETCH_ASSOC);
              foreach ($row_result as $key => $criteria_name) {
                echo "<tr>";
                echo "<th>" . $criteria_name['criteria_name'] . " (" . $criteria_name['percent'] . "%)</th>";
                for ($j = 1; $j <= $school_number; $j++) {
                  $query = "SELECT DISTINCT schools.school_id, schools.school_name, grades.juri_id,  criterias.criteria_name, criterias.percent, grades.grade
                          FROM grades, criterias, schools
                          WHERE criterias.criteria_id = ? AND grades.criteria_id = criterias.criteria_id  AND grades.school_id = ?
                          ORDER BY schools.school_id ASC";
                  $stmt = $conn->prepare($query);
                  $stmt->execute([$i, $j]);
                  $row_result = $stmt->fetchAll(PDO::FETCH_ASSOC);
                  $sum = 0.0;
                  foreach ($row_result as $key => $criteria_info) {
                    $sum += $criteria_info['grade'];
                    // print_r($criteria_info);
                  }
                  echo "<td>" . (($sum / (count($row_result))) * 2) . "</td>";
                }
              }
              echo "</tr>";
            }
            echo "<tr>";
            echo "<th>Toplam(100%):</th>";

            $query = "SELECT grades.grade
                      FROM grades, schools 
                      WHERE schools.school_id = ? and grades.school_id = schools.school_id
                      ORDER BY grades.criteria_id ASC";

            $stmt = $conn->prepare($query);
            for ($i = 1; $i <= $school_number; $i++) {
              $stmt->execute([$i]);
              $toplam_result = $stmt->fetchAll(PDO::FETCH_ASSOC);
              $sum = 0;
              foreach ($toplam_result as $key => $value) {
                $sum += ($value['grade']) * 2;
              }
              echo "<td>" . $sum . "</td>";
            }

            echo "</tr>";

            ?>

          </tbody>
        </table>
      </div>
    </div>
    <br><br>
  </section>
  <?php
  include_once('incs/footer.inc.php');
  ?>
</body>

</html>

<script>
  const average_btn = document.getElementById('average-tab');
  average_btn.addEventListener('click', function() {
    const over_div = document.getElementById("average-results-div");
    if (over_div.style.display == "none") {
      over_div.style.display = "block";
    } else {
      over_div.style.display = "none";
    }
  });

  const result_btn = document.getElementById('result-tab');
  result_btn.addEventListener('click', function() {
    const over_div = document.getElementById("over-div");
    if (over_div.style.display == "none") {
      over_div.style.display = "block";
    } else {
      over_div.style.display = "none";
    }
  });
</script>