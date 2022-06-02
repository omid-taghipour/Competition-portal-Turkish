<!DOCTYPE html>
<html style="font-size: 16px;">

<head>
  <meta name="viewport" content="width=device-width, initial-scale=1.0">
  <meta charset="utf-8">
  <meta name="keywords" content="">
  <meta name="description" content="">
  <meta name="page_type" content="np-template-header-footer-from-plugin">
  <title>Teams</title>
  <link rel="stylesheet" href="nicepage.css" media="screen">
  <link rel="stylesheet" href="Teams.css" media="screen">
  <script class="u-script" type="text/javascript" src="jquery.js" defer=""></script>
  <script class="u-script" type="text/javascript" src="nicepage.js" defer=""></script>
  <link rel="stylesheet" href="https://cdn.jsdelivr.net/npm/bootstrap@4.0.0/dist/css/bootstrap.min.css" integrity="sha384-Gn5384xqQ1aoWXA+058RXPxPg6fy4IWvTNh0E263XmFcJlSAwiGgFAW/dAiS6JXm" crossorigin="anonymous">
  <meta name="generator" content="Nicepage 4.8.2, nicepage.com">
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
  <meta property="og:title" content="Teams">
  <meta property="og:type" content="website">
</head>

<body class="u-body u-xl-mode">
  <?php
  require_once('incs/login.check.inc.php');

  include_once('incs/header.inc.php');
  ?>
  <section class="u-align-center u-clearfix u-section-1" id="sec-359e">
    <div class="u-clearfix u-sheet u-valign-middle u-sheet-1">
      <div class="school-cards">

        <?php
        require_once('incs/db.inc.php');
        $query = "SELECT * FROM schools";
        $stmt = $conn->prepare($query);
        $stmt->execute();
        $result = $stmt->fetchAll(PDO::FETCH_ASSOC);
        foreach ($result as $index => $school) {
          echo '<div class="school-child">
                <img src="images/school-icon.png" class="school-icon">
                <h3 class="school-name">' . ($index + 1) . '. ' . $school['school_name'] . '</h3>
                <p class="school-grade-help">Click on the "Grade" button to enter/change grade</p>
                <a href="grading.php?id=' . $school['school_id'] . '&name=' . $school['school_name'] . '" class="btn btn-primary btn-lg btn-block grade-btn-cus">Grade</a>
              </div>';
        }
        ?>
      </div>


    </div>
  </section>
  <?php
  include_once('incs/footer.inc.php');
  ?>
</body>

</html>