<!DOCTYPE html>
<html style="font-size: 16px;">

<head>
  <meta name="viewport" content="width=device-width, initial-scale=1.0">
  <meta charset="utf-8">
  <meta name="keywords" content="Grading for School Name">
  <meta name="description" content="">
  <meta name="page_type" content="np-template-header-footer-from-plugin">
  <title>Grading</title>
  <link rel="stylesheet" href="nicepage.css" media="screen">
  <link rel="stylesheet" href="Grading.css" media="screen">
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
  <meta property="og:title" content="Grading">
  <meta property="og:type" content="website">
</head>

<body class="u-body u-xl-mode">
  <?php
  require_once('incs/login.check.inc.php');
  include_once('incs/header.inc.php');

  if (isset($_GET['msg'])) {
    echo '<br/><div class="alert alert-success" role="alert"><h3 align="center">Grades are successfully saved.</h3></div>';
  }
  if (isset($_GET['id'])) {
    require_once('incs/db.inc.php');
    $school_id = $_GET['id'];
    $school_name = $_GET['name'];

    $query = "SELECT * FROM activation";
    $stmt_activation = $conn->prepare($query);
    $stmt_activation->execute();
    $status_result = $stmt_activation->fetchAll(PDO::FETCH_ASSOC);
    if ($status_result[0]['activation_status'] == 0) {
      echo '<div class="alert alert-danger" role="alert"><h2 align="center">Evaluation process is over!</h2></div>';
    }
    // Reading previous grades from the database
    $query = "SELECT grade FROM grades WHERE school_id =? and juri_id=?";
    $stmt = $conn->prepare($query);
    $stmt->execute([$school_id, ($_SESSION['userid'])]);
    $school_result = $stmt->fetchAll(PDO::FETCH_ASSOC);

    // NOT the first submission
    $first_grade_flag = FALSE;

    if (empty($school_result)) {
      //First grade submission
      $first_grade_flag = TRUE;
    }
  } else {
    echo "<script>window.location.href='teams.php';</script>";
  }
  if (isset($_POST['sbt'])) {
    if ($first_grade_flag == TRUE) {
      // first time to submit grades
      $query = "INSERT INTO grades(juri_id, school_id, criteria_id, grade) VALUES(?,?,?,?)";
      $stmt = $conn->prepare($query);
      try {
        for ($i = 0; $i < 10; $i++) {
          $stmt->execute([$_SESSION['userid'], $school_id, ($i + 1), $_POST['inp' . $i]]);
        }
        if ($status_result[0]['activation_status'] != 0) {
          include_once('incs/calculate.inc.php');
        }
        echo "<script>document.location.href='grading.php?id=" . $school_id . "&name=" . $school_name . "&msg';</script>";
      } catch (Exception $ex) {
        echo '<div class="alert alert-danger" role="alert"><h3 align="center">Grades could not be saved.' . $ex->getMessage() . '</h3></div>';
      }
    } elseif ($first_grade_flag == FALSE) {
      // Updating the existing data
      $query = "UPDATE grades 
                SET grade =?
                WHERE juri_id = ? AND school_id = ? AND criteria_id = ?";
      $stmt = $conn->prepare($query);
      try {
        for ($i = 0; $i < 10; $i++) {
          $stmt->execute([$_POST['inp' . $i], $_SESSION['userid'], $_GET['id'], ($i + 1)]);
        }
        if($status_result[0]['activation_status'] != 0){
          include_once('incs/calculate.inc.php');
        }
        echo '<div class="alert alert-success" role="alert"><h3 align="center">Grades are successfully updated.</h3></div>';
        echo "<script>
                document.location.href='grading.php?id=" . $school_id . "&name=" . $school_name . "&msg';
              </script>";
      } catch (Exception $ex) {
        echo '<div class="alert alert-danger" role="alert"><h3 align="center">Grades could not be updated.' . $ex->getMessage() . '</h3></div>';
      }
    }
  }



  ?>



  <section class="u-clearfix u-section-1" id="sec-4483">
    <div class="u-clearfix u-sheet u-sheet-1">
      <br>
      <h1 class="u-align-center u-text u-text-default u-text-1"><?php echo $school_name ?> is under evaluation <img src="images/help.png" class="help-icon" title="Help information"></h1>
      <br />

      <div class="alert alert-success" id="help-alert" role="alert">
        <h4 class="alert-heading">Need some help?</h4>
        <p>This page is for evaluating <?php echo $school_name ?>. We provided the evaluation criterias in the form below. You can give mark to this school for each criteria.
          <br>You can use the same page later on (before end of evaluation time) to change marks given.
          <br>At the end of entering marks, scroll all the way down and click on the "Save changes" button to save your marks.
          <br>In case if you changed your mind to not updating the marks, you can scroll all the way down and click on "Bring back the previouse grades" button to remove the changes you have made.
        </p>
        <hr>
        <p class="mb-0"><strong>Marking policy is from 1 to 5. (1 is the lowest score and 5 is the highest)</strong> </p>
      </div>

      <hr>
      <div class="u-align-center u-expanded-width u-form u-form-1">
        <form action="<?php echo $_SERVER['REQUEST_URI']; ?>" method="POST" name="form" style="padding: 10px;">
          <div class="u-form-email u-form-group form-elements">
            <label for="name-c3ca"><strong>1. Problem Sunumu:</strong> <i class="text-info">(Problemin açıklanması ve çözüm üretilmesi için dayanaklrın sıralanması.)</i></label>

            <!-- <input type="number" placeholder="Problem Sunumu..." value="<?php //if (!$first_grade_flag) echo $school_result[0]['grade']; 
                                                                              //
                                                                              ?>" min="1" max="5" id="name-c3ca" name="inp0" class="form-control" required> -->
            <br>
            <div class="form-check form-check-inline">
              <div class="radio-grade-btn">
                <input class="form-check-input " type="radio" name="inp0" id="inp01" value="1" <?php if (!$first_grade_flag) {
                                                                                                  if ($school_result[0]['grade'] == 1)
                                                                                                    echo 'checked';
                                                                                                } ?>>
                <label class="form-check-label text-danger" for="inp01"><strong>1- Gelişmesi Gerekir</strong></label>
              </div>
            </div>
            <div class="form-check form-check-inline">
              <div class="radio-grade-btn">
                <input class="form-check-input" type="radio" name="inp0" id="inp02" value="2" <?php if (!$first_grade_flag) {
                                                                                                if ($school_result[0]['grade'] == 2)
                                                                                                  echo 'checked';
                                                                                              } ?>>
                <label class="form-check-label" for="inp02" style="color:darkorange"><strong>2- Az</strong></label>
              </div>
            </div>
            <div class="form-check form-check-inline">
              <div class="radio-grade-btn">
                <input class="form-check-input" type="radio" name="inp0" id="inp03" value="3" <?php if ($first_grade_flag || ($school_result[0]['grade'] == 3)) {
                                                                                                echo 'checked';
                                                                                              } ?>>
                <label class="form-check-label" for="inp03"><strong>3- Yeterli</strong></label>
              </div>
            </div>
            <div class="form-check form-check-inline">
              <div class="radio-grade-btn">
                <input class="form-check-input" type="radio" name="inp0" id="inp04" value="4" <?php if (!$first_grade_flag) {
                                                                                                if ($school_result[0]['grade'] == 4)
                                                                                                  echo 'checked';
                                                                                              } ?>>
                <label class="form-check-label text-info" for="inp04"><strong>4-İyi</strong></label>
              </div>
            </div>
            <div class="form-check form-check-inline">
              <div class="radio-grade-btn">
                <input class="form-check-input" type="radio" name="inp0" id="inp05" value="5" <?php if (!$first_grade_flag) {
                                                                                                if ($school_result[0]['grade'] == 5)
                                                                                                  echo 'checked';
                                                                                              } ?>>
                <label class="form-check-label text-success" for="inp05"><strong>5- Çok iyi</strong></label>
              </div>
            </div>
          </div>
          <hr width="85%">
          <div class="u-form-email u-form-group form-elements">
            <label for="email-c3ca" class="u-label"><strong>2. Geleceğe uygunluk:</strong> <i class="text-info">(Anlatılan konuları (yeni teknolojiler) sunuma dahil ederek sunum hazırlama ve çözüm tasarlama.)</i></label>
            <br>
            <div class="form-check form-check-inline">
              <div class="radio-grade-btn">
                <input class="form-check-input " type="radio" name="inp1" id="inp11" value="1" <?php if (!$first_grade_flag) {
                                                                                                  if ($school_result[1]['grade'] == 1)
                                                                                                    echo 'checked';
                                                                                                } ?>>
                <label class="form-check-label text-danger" for="inp11"><strong>1- Gelişmesi Gerekir</strong></label>
              </div>
            </div>
            <div class="form-check form-check-inline">
              <div class="radio-grade-btn">
                <input class="form-check-input" type="radio" name="inp1" id="inp12" value="2" <?php if (!$first_grade_flag) {
                                                                                                if ($school_result[1]['grade'] == 2)
                                                                                                  echo 'checked';
                                                                                              } ?>>
                <label class="form-check-label" for="inp12" style="color:darkorange"><strong>2- Az</strong></label>
              </div>
            </div>
            <div class="form-check form-check-inline">
              <div class="radio-grade-btn">
                <input class="form-check-input" type="radio" name="inp1" id="inp13" value="3" <?php if ($first_grade_flag || ($school_result[1]['grade'] == 3)) {
                                                                                                echo 'checked';
                                                                                              } ?>>
                <label class="form-check-label" for="inp13"><strong>3- Yeterli</strong></label>
              </div>
            </div>
            <div class="form-check form-check-inline">
              <div class="radio-grade-btn">
                <input class="form-check-input" type="radio" name="inp1" id="inp14" value="4" <?php if (!$first_grade_flag) {
                                                                                                if ($school_result[1]['grade'] == 4)
                                                                                                  echo 'checked';
                                                                                              } ?>>
                <label class="form-check-label text-info" for="inp14"><strong>4-İyi</strong></label>
              </div>
            </div>
            <div class="form-check form-check-inline">
              <div class="radio-grade-btn">
                <input class="form-check-input" type="radio" name="inp1" id="inp15" value="5" <?php if (!$first_grade_flag) {
                                                                                                if ($school_result[1]['grade'] == 5)
                                                                                                  echo 'checked';
                                                                                              } ?>>
                <label class="form-check-label text-success" for="inp15"><strong>5- Çok iyi</strong></label>
              </div>
            </div>
          </div>
          <hr width="85%">

          <div class="u-form-group u-form-group-3 form-elements">
            <label for="text-fd09" class="u-label"><strong>3. Etik:</strong> <i class="text-info">(Anlatılan konuları (yeni teknolojiler) sunuma dahil ederek sunum hazırlama ve çözüm tasarlama.)</i></label>
            <br>
            <div class="form-check form-check-inline">
              <div class="radio-grade-btn">
                <input class="form-check-input " type="radio" name="inp2" id="inp21" value="1" <?php if (!$first_grade_flag) {
                                                                                                  if ($school_result[2]['grade'] == 1)
                                                                                                    echo 'checked';
                                                                                                } ?>>
                <label class="form-check-label text-danger" for="inp21"><strong>1- Gelişmesi Gerekir</strong></label>
              </div>
            </div>
            <div class="form-check form-check-inline">
              <div class="radio-grade-btn">
                <input class="form-check-input" type="radio" name="inp2" id="inp22" value="2" <?php if (!$first_grade_flag) {
                                                                                                if ($school_result[2]['grade'] == 2)
                                                                                                  echo 'checked';
                                                                                              } ?>>
                <label class="form-check-label" for="inp22" style="color:darkorange"><strong>2- Az</strong></label>
              </div>
            </div>
            <div class="form-check form-check-inline">
              <div class="radio-grade-btn">
                <input class="form-check-input" type="radio" name="inp2" id="inp23" value="3" <?php if ($first_grade_flag || ($school_result[2]['grade'] == 3)) {
                                                                                                echo 'checked';
                                                                                              } ?>>
                <label class="form-check-label" for="inp23"><strong>3- Yeterli</strong></label>
              </div>
            </div>
            <div class="form-check form-check-inline">
              <div class="radio-grade-btn">
                <input class="form-check-input" type="radio" name="inp2" id="inp24" value="4" <?php if (!$first_grade_flag) {
                                                                                                if ($school_result[2]['grade'] == 4)
                                                                                                  echo 'checked';
                                                                                              } ?>>
                <label class="form-check-label text-info" for="inp24"><strong>4-İyi</strong></label>
              </div>
            </div>
            <div class="form-check form-check-inline">
              <div class="radio-grade-btn">
                <input class="form-check-input" type="radio" name="inp2" id="inp25" value="5" <?php if (!$first_grade_flag) {
                                                                                                if ($school_result[2]['grade'] == 5)
                                                                                                  echo 'checked';
                                                                                              } ?>>
                <label class="form-check-label text-success" for="inp25"><strong>5- Çok iyi</strong></label>
              </div>
            </div>
          </div>
          <hr width="85%">


          <div class="u-form-group u-form-group-4 form-elements">
            <label for="text-1e43" class="u-label"><strong>4.Güvenlik:</strong> <i class="text-info">(Anlatılan konuları (yeni teknolojiler) sunuma dahil ederek sunum hazırlama ve çözüm tasarlama.)</i></label>
            <br>
            <div class="form-check form-check-inline">
              <div class="radio-grade-btn">
                <input class="form-check-input " type="radio" name="inp3" id="inp31" value="1" <?php if (!$first_grade_flag) {
                                                                                                  if ($school_result[3]['grade'] == 1)
                                                                                                    echo 'checked';
                                                                                                } ?>>
                <label class="form-check-label text-danger" for="inp31"><strong>1- Gelişmesi Gerekir</strong></label>
              </div>
            </div>

            <div class="form-check form-check-inline">
              <div class="radio-grade-btn">
                <input class="form-check-input" type="radio" name="inp3" id="inp32" value="2" <?php if (!$first_grade_flag) {
                                                                                                if ($school_result[3]['grade'] == 2)
                                                                                                  echo 'checked';
                                                                                              } ?>>
                <label class="form-check-label" for="inp32" style="color:darkorange"><strong>2- Az</strong></label>
              </div>
            </div>
            <div class="form-check form-check-inline">
              <div class="radio-grade-btn">
                <input class="form-check-input" type="radio" name="inp3" id="inp33" value="3" <?php if ($first_grade_flag  || ($school_result[3]['grade'] == 3)) {
                                                                                                echo 'checked';
                                                                                              } ?>>
                <label class="form-check-label" for="inp33"><strong>3- Yeterli</strong></label>
              </div>
            </div>
            <div class="form-check form-check-inline">
              <div class="radio-grade-btn">
                <input class="form-check-input" type="radio" name="inp3" id="inp34" value="4" <?php if (!$first_grade_flag) {
                                                                                                if ($school_result[3]['grade'] == 4)
                                                                                                  echo 'checked';
                                                                                              } ?>>
                <label class="form-check-label text-info" for="inp34"><strong>4-İyi</strong></label>
              </div>
            </div>
            <div class="form-check form-check-inline">
              <div class="radio-grade-btn">
                <input class="form-check-input" type="radio" name="inp3" id="inp35" value="5" <?php if (!$first_grade_flag) {
                                                                                                if ($school_result[3]['grade'] == 5)
                                                                                                  echo 'checked';
                                                                                              } ?>>
                <label class="form-check-label text-success" for="inp35"><strong>5- Çok iyi</strong></label>
              </div>
            </div>
          </div>
          <hr width="85%">

          <div class="u-form-group u-form-group-5 form-elements">
            <label for="text-cf69" class="u-label"><strong>5. Gerçekleşebilirlik:</strong> <i class="text-info">(Verilen problem ve çözüm arasında uyum var mı? Uygulanbilir bir proje mi?)</i></label>
            <br>
            <div class="form-check form-check-inline">
              <div class="radio-grade-btn">
                <input class="form-check-input " type="radio" name="inp4" id="inp41" value="1" <?php if (!$first_grade_flag) {
                                                                                                  if ($school_result[4]['grade'] == 1)
                                                                                                    echo 'checked';
                                                                                                } ?>>
                <label class="form-check-label text-danger" for="inp41"><strong>1- Gelişmesi Gerekir</strong></label>
              </div>
            </div>
            <div class="form-check form-check-inline">
              <div class="radio-grade-btn">
                <input class="form-check-input" type="radio" name="inp4" id="inp42" value="2" <?php if (!$first_grade_flag) {
                                                                                                if ($school_result[4]['grade'] == 2)
                                                                                                  echo 'checked';
                                                                                              } ?>>
                <label class="form-check-label" for="inp42" style="color:darkorange"><strong>2- Az</strong></label>
              </div>
            </div>
            <div class="form-check form-check-inline">
              <div class="radio-grade-btn">
                <input class="form-check-input" type="radio" name="inp4" id="inp43" value="3" <?php if ($first_grade_flag || ($school_result[4]['grade'] == 3)) {
                                                                                                echo 'checked';
                                                                                              } ?>>
                <label class="form-check-label" for="inp43"><strong>3- Yeterli</strong></label>
              </div>
            </div>
            <div class="form-check form-check-inline">
              <div class="radio-grade-btn">
                <input class="form-check-input" type="radio" name="inp4" id="inp44" value="4" <?php if (!$first_grade_flag) {
                                                                                                if ($school_result[4]['grade'] == 4)
                                                                                                  echo 'checked';
                                                                                              } ?>>
                <label class="form-check-label text-info" for="inp44"><strong>4-İyi</strong></label>
              </div>
            </div>

            <div class="form-check form-check-inline">
              <div class="radio-grade-btn">
                <input class="form-check-input" type="radio" name="inp4" id="inp45" value="5" <?php if (!$first_grade_flag) {
                                                                                                if ($school_result[4]['grade'] == 5)
                                                                                                  echo 'checked';
                                                                                              } ?>>
                <label class="form-check-label text-success" for="inp45"><strong>5- Çok iyi</strong></label>
              </div>
            </div>
          </div>
          <hr width="85%">

          <div class="u-form-group u-form-group-6 form-elements">
            <label for="text-37b7" class="u-label"><strong>6. Topluma Yarar:</strong> <i class="text-info">(Projenin uygulanması durumunda projenin bireysel ve toplumsal ne gibi yararları olacaktır?)</i></label>
            <br>
            <div class="form-check form-check-inline">
              <div class="radio-grade-btn">
                <input class="form-check-input " type="radio" name="inp5" id="inp51" value="1" <?php if (!$first_grade_flag) {
                                                                                                  if ($school_result[5]['grade'] == 1)
                                                                                                    echo 'checked';
                                                                                                } ?>>
                <label class="form-check-label text-danger" for="inp51"><strong>1- Gelişmesi Gerekir</strong></label>
              </div>
            </div>
            <div class="form-check form-check-inline">
              <div class="radio-grade-btn">
                <input class="form-check-input" type="radio" name="inp5" id="inp52" value="2" <?php if (!$first_grade_flag) {
                                                                                                if ($school_result[5]['grade'] == 2)
                                                                                                  echo 'checked';
                                                                                              } ?>>
                <label class="form-check-label" for="inp52" style="color:darkorange"><strong>2- Az</strong></label>
              </div>
            </div>
            <div class="form-check form-check-inline">
              <div class="radio-grade-btn">
                <input class="form-check-input" type="radio" name="inp5" id="inp53" value="3" <?php if ($first_grade_flag || ($school_result[5]['grade'] == 3)) {
                                                                                                echo 'checked';
                                                                                              } ?>>
                <label class="form-check-label" for="inp53"><strong>3- Yeterli</strong></label>
              </div>
            </div>

            <div class="form-check form-check-inline">
              <div class="radio-grade-btn">
                <input class="form-check-input" type="radio" name="inp5" id="inp54" value="4" <?php if (!$first_grade_flag) {
                                                                                                if ($school_result[5]['grade'] == 4)
                                                                                                  echo 'checked';
                                                                                              } ?>>
                <label class="form-check-label text-info" for="inp54"><strong>4-İyi</strong></label>
              </div>
            </div>
            <div class="form-check form-check-inline">
              <div class="radio-grade-btn">
                <input class="form-check-input" type="radio" name="inp5" id="inp55" value="5" <?php if (!$first_grade_flag) {
                                                                                                if ($school_result[5]['grade'] == 5)
                                                                                                  echo 'checked';
                                                                                              } ?>>
                <label class="form-check-label text-success" for="inp55"><strong>5- Çok iyi</strong></label>
              </div>
            </div>
          </div>
          <hr width="85%">

          <div class="u-form-group u-form-group-7 form-elements">
            <label for="text-0940" class="u-label"><strong>7. Kapsayıcılık:</strong> <i class="text-info">(Kapsayıcılık daha yaşanılabilir bir dünya için fayda ve mutluluğun da paylaşılması fikrini savunuyor. Projenin uygulanması durumunda proje tüm toplum bireylerini kapsayıcı olacak mı?)</i></label>
            <br>
            <div class="form-check form-check-inline">
              <div class="radio-grade-btn">
                <input class="form-check-input " type="radio" name="inp6" id="inp61" value="1" <?php if (!$first_grade_flag) {
                                                                                                  if ($school_result[6]['grade'] == 1)
                                                                                                    echo 'checked';
                                                                                                } ?>>
                <label class="form-check-label text-danger" for="inp61"><strong>1- Gelişmesi Gerekir</strong></label>
              </div>
            </div>
            <div class="form-check form-check-inline">
              <div class="radio-grade-btn">
                <input class="form-check-input" type="radio" name="inp6" id="inp62" value="2" <?php if (!$first_grade_flag) {
                                                                                                if ($school_result[6]['grade'] == 2)
                                                                                                  echo 'checked';
                                                                                              } ?>>
                <label class="form-check-label" for="inp62" style="color:darkorange"><strong>2- Az</strong></label>
              </div>
            </div>
            <div class="form-check form-check-inline">
              <div class="radio-grade-btn">
                <input class="form-check-input" type="radio" name="inp6" id="inp63" value="3" <?php if ($first_grade_flag || ($school_result[6]['grade'] == 3)) {
                                                                                                echo 'checked';
                                                                                              } ?>>
                <label class="form-check-label" for="inp63"><strong>3- Yeterli</strong></label>
              </div>
            </div>
            <div class="form-check form-check-inline">
              <div class="radio-grade-btn">
                <input class="form-check-input" type="radio" name="inp6" id="inp64" value="4" <?php if (!$first_grade_flag) {
                                                                                                if ($school_result[6]['grade'] == 4)
                                                                                                  echo 'checked';
                                                                                              } ?>>
                <label class="form-check-label text-info" for="inp64"><strong>4-İyi</strong></label>
              </div>
            </div>
            <div class="form-check form-check-inline">
              <div class="radio-grade-btn">
                <input class="form-check-input" type="radio" name="inp6" id="inp65" value="5" <?php if (!$first_grade_flag) {
                                                                                                if ($school_result[6]['grade'] == 5)
                                                                                                  echo 'checked';
                                                                                              } ?>>
                <label class="form-check-label text-success" for="inp65"><strong>5- Çok iyi</strong></label>
              </div>
            </div>
          </div>
          <hr width="85%">

          <div class="u-form-group u-form-group-8 form-elements">
            <label for="text-4eea" class="u-label"><strong>8. Yaratıcılık:</strong> <i class="text-info">(Verilen problem ve çözüm arasında uyum var mı? Akıl yürütme becererileri ve hayal gücü bir denge üzerine dayandırılıp probleme yeni bir çözüm getirildi mi?)</i></label>
            <br>
            <div class="form-check form-check-inline">
              <div class="radio-grade-btn">
                <input class="form-check-input " type="radio" name="inp7" id="inp71" value="1" <?php if (!$first_grade_flag) {
                                                                                                  if ($school_result[7]['grade'] == 1)
                                                                                                    echo 'checked';
                                                                                                } ?>>
                <label class="form-check-label text-danger" for="inp71"><strong>1- Gelişmesi Gerekir</strong></label>
              </div>
            </div>
            <div class="form-check form-check-inline">
              <div class="radio-grade-btn">
                <input class="form-check-input" type="radio" name="inp7" id="inp72" value="2" <?php if (!$first_grade_flag) {
                                                                                                if ($school_result[7]['grade'] == 2)
                                                                                                  echo 'checked';
                                                                                              } ?>>
                <label class="form-check-label" for="inp72" style="color:darkorange"><strong>2- Az</strong></label>
              </div>
            </div>
            <div class="form-check form-check-inline">
              <div class="radio-grade-btn">
                <input class="form-check-input" type="radio" name="inp7" id="inp73" value="3" <?php if ($first_grade_flag || ($school_result[7]['grade'] == 3)) {
                                                                                                echo 'checked';
                                                                                              } ?>>
                <label class="form-check-label" for="inp73"><strong>3- Yeterli</strong></label>
              </div>
            </div>
            <div class="form-check form-check-inline">
              <div class="radio-grade-btn">
                <input class="form-check-input" type="radio" name="inp7" id="inp74" value="4" <?php if (!$first_grade_flag) {
                                                                                                if ($school_result[7]['grade'] == 4)
                                                                                                  echo 'checked';
                                                                                              } ?>>
                <label class="form-check-label text-info" for="inp74"><strong>4-İyi</strong></label>
              </div>
            </div>
            <div class="form-check form-check-inline">
              <div class="radio-grade-btn">
                <input class="form-check-input" type="radio" name="inp7" id="inp75" value="5" <?php if (!$first_grade_flag) {
                                                                                                if ($school_result[7]['grade'] == 5)
                                                                                                  echo 'checked';
                                                                                              } ?>>
                <label class="form-check-label text-success" for="inp75"><strong>5- Çok iyi</strong></label>
              </div>
            </div>
          </div>
          <hr width="85%">

          <div class="u-form-group u-form-group-9 form-elements">
            <label for="text-35a9" class="u-label"><strong>9. Etkili Sunum / Zaman Yönetim:</strong> <i class="text-info">(Sunum yeteneği, sunumda zaman kullanımı ve ekip dayanışması. Verilen zaman etkin kullanılıp proje geliştirilebildi mi? Toplam zamanda fikir üretilip sunuma hazır hale getirilebildi mi?)</i></label>
            <br>
            <div class="form-check form-check-inline">
              <div class="radio-grade-btn">
                <input class="form-check-input " type="radio" name="inp8" id="inp81" value="1" <?php if (!$first_grade_flag) {
                                                                                                  if ($school_result[8]['grade'] == 1)
                                                                                                    echo 'checked';
                                                                                                } ?>>
                <label class="form-check-label text-danger" for="inp81"><strong>1- Gelişmesi Gerekir</strong></label>
              </div>
            </div>
            <div class="form-check form-check-inline">
              <div class="radio-grade-btn">
                <input class="form-check-input" type="radio" name="inp8" id="inp82" value="2" <?php if (!$first_grade_flag) {
                                                                                                if ($school_result[8]['grade'] == 2)
                                                                                                  echo 'checked';
                                                                                              } ?>>
                <label class="form-check-label" for="inp82" style="color:darkorange"><strong>2- Az</strong></label>
              </div>
            </div>
            <div class="form-check form-check-inline">
              <div class="radio-grade-btn">
                <input class="form-check-input" type="radio" name="inp8" id="inp83" value="3" <?php if ($first_grade_flag || ($school_result[8]['grade'] == 3)) {
                                                                                                echo 'checked';
                                                                                              } ?>>
                <label class="form-check-label" for="inp83"><strong>3- Yeterli</strong></label>
              </div>
            </div>
            <div class="form-check form-check-inline">
              <div class="radio-grade-btn">
                <input class="form-check-input" type="radio" name="inp8" id="inp84" value="4" <?php if (!$first_grade_flag) {
                                                                                                if ($school_result[8]['grade'] == 4)
                                                                                                  echo 'checked';
                                                                                              } ?>>
                <label class="form-check-label text-info" for="inp84"><strong>4-İyi</strong></label>
              </div>
            </div>
            <div class="form-check form-check-inline">
              <div class="radio-grade-btn">
                <input class="form-check-input" type="radio" name="inp8" id="inp85" value="5" <?php if (!$first_grade_flag) {
                                                                                                if ($school_result[8]['grade'] == 5)
                                                                                                  echo 'checked';
                                                                                              } ?>>
                <label class="form-check-label text-success" for="inp85"><strong>5- Çok iyi</strong></label>
              </div>
            </div>
          </div>
          <hr width="85%">

          <div class="u-form-group u-form-group-10 form-elements">
            <label for="text-ff93" class="u-label"><strong>10. Jüriye verilen cevaplar:</strong> <i class="text-info">(Verilen soruların etkin cevaplanması.)</i> </label>
            <br>
            <div class="form-check form-check-inline">
              <div class="radio-grade-btn">
                <input class="form-check-input " type="radio" name="inp9" id="inp91" value="1" <?php if (!$first_grade_flag) {
                                                                                                  if ($school_result[9]['grade'] == 1)
                                                                                                    echo 'checked';
                                                                                                } ?>>
                <label class="form-check-label text-danger" for="inp91"><strong>1- Gelişmesi Gerekir</strong></label>
              </div>
            </div>
            <div class="form-check form-check-inline">
              <div class="radio-grade-btn">
                <input class="form-check-input" type="radio" name="inp9" id="inp92" value="2" <?php if (!$first_grade_flag) {
                                                                                                if ($school_result[9]['grade'] == 2)
                                                                                                  echo 'checked';
                                                                                              } ?>>
                <label class="form-check-label" for="inp92" style="color:darkorange"><strong>2- Az</strong></label>
              </div>
            </div>
            <div class="form-check form-check-inline">
              <div class="radio-grade-btn">
                <input class="form-check-input" type="radio" name="inp9" id="inp93" value="3" <?php if ($first_grade_flag || ($school_result[9]['grade'] == 3)) {
                                                                                                echo 'checked';
                                                                                              } ?>>
                <label class="form-check-label" for="inp93"><strong>3- Yeterli</strong></label>
              </div>
            </div>
            <div class="form-check form-check-inline">
              <div class="radio-grade-btn">
                <input class="form-check-input" type="radio" name="inp9" id="inp94" value="4" <?php if (!$first_grade_flag) {
                                                                                                if ($school_result[9]['grade'] == 4)
                                                                                                  echo 'checked';
                                                                                              } ?>>
                <label class="form-check-label text-info" for="inp94"><strong>4-İyi</strong></label>
              </div>
            </div>
            <div class="form-check form-check-inline">
              <div class="radio-grade-btn">
                <input class="form-check-input" type="radio" name="inp9" id="inp95" value="5" <?php if (!$first_grade_flag) {
                                                                                                if ($school_result[9]['grade'] == 5)
                                                                                                  echo 'checked';
                                                                                              } ?>>
                <label class="form-check-label text-success" for="inp95"><strong>5- Çok iyi</strong></label>
              </div>
            </div>
          </div>
          <?php

          // Enable/Disable button when results are getting out
          if ($status_result[0]['activation_status'] == 1 || $status_result[0]['activation_status'] == -1) {
            echo '<br/><button type="submit" name="sbt" class="btn btn-success btn-lg btn-block grade-btn-cus">Save changes</button>
                  <button type="reset" class="btn btn-danger btn-lg btn-block grade-btn-cus">Bring back the previous grades</button>';
          }
          ?>


        </form>
      </div>
    </div>
  </section>

  <?php
  include_once('incs/footer.inc.php');
  ?>
</body>

</html>

<script>
  const help_icon = document.getElementsByClassName('help-icon')[0];
  const help_alert = document.getElementById('help-alert');
  help_alert.style.display = "none";
  help_icon.addEventListener('click', function() {
    const help_alert = document.getElementById('help-alert');
    if (help_alert.style.display != "none") {
      help_alert.style.display = "none";
    } else {
      help_alert.style.display = "block";
    }
  });
</script>