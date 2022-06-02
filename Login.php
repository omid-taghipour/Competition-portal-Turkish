<!DOCTYPE html>
<html style="font-size: 16px;">

<head>
  <meta name="viewport" content="width=device-width, initial-scale=1.0">
  <meta charset="utf-8">
  <meta name="keywords" content="Login form goes here!">
  <meta name="description" content="">
  <meta name="page_type" content="np-template-header-footer-from-plugin">
  <title>Login</title>
  <link rel="stylesheet" href="nicepage.css" media="screen">
  <link rel="stylesheet" href="Login.css" media="screen">
  <script class="u-script" type="text/javascript" src="jquery.js" defer=""></script>
  <script class="u-script" type="text/javascript" src="nicepage.js" defer=""></script>
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
  <meta property="og:title" content="Login">
  <meta property="og:type" content="website">
</head>

<body class="u-body u-xl-mode">
  <?php
  @session_start();
  include_once('incs/header.inc.php');

  //form is submitted
  if (isset($_POST['sbt']) && !isset($_SESSION['userid'])) {
    // form is submitted \
    // Reading username and password from the form
    require_once('incs/db.inc.php');
    $username = strtolower(filter_input(INPUT_POST, 'username', FILTER_SANITIZE_SPECIAL_CHARS));
    $password = filter_input(INPUT_POST, 'password', FILTER_SANITIZE_SPECIAL_CHARS);

    try {
      $query = 'SELECT juri_id, full_name
                FROM juri_members 
                WHERE user_name=? and password=?';

      $stmt = $conn->prepare($query);
      $stmt->execute([$username, $password]);
      $result = $stmt->fetch(PDO::FETCH_ASSOC);
      if ($result) {
        // User verified
        $_SESSION['userid'] = $result['juri_id'];
        $_SESSION['name'] = $result['full_name'];
        echo '<script>alert('.gettype($_SESSION['userid']).');</script>';
        if ($_SESSION['userid'] == 21474836) {
          echo "<script>window.location.href='adminpanel.php';</script>";
        } else {
          echo "<script>window.location.href='main.php';</script>";
        }
      } else {
        echo '<div class="alert alert-danger" role="alert">Username or password is incorrect! Try again.</div>';
      }
    } catch (PDOException $ex) {
      echo "Something is wrong!" . $ex->getMessage();
    }
  }
  
  ?>
  <section class="u-align-center u-clearfix u-section-1" id="sec-48cd">
    <div class="u-clearfix u-sheet u-valign-middle u-sheet-1">
      <div class="u-clearfix u-expanded-width u-layout-wrap u-layout-wrap-1">
        <div class="u-layout">
          <div class="u-layout-row">
            <div class="u-container-style u-layout-cell u-size-30 u-layout-cell-1">
              <div class="u-container-layout u-valign-middle u-container-layout-1">
                <img class="u-align-center u-image u-image-default u-image-1" src="images/undraw_my_password_re_ydq7.svg" alt="" data-image-width="493" data-image-height="480">
              </div>
            </div>
            <div class="u-align-center u-container-style u-layout-cell u-size-30 u-layout-cell-2">
              <div class="u-container-layout u-container-layout-2">
                <h1 class="centered-header">Login to the portal</h1>
                <div class="u-expanded-width u-form u-form-1">
                  <p>Fill the form with the username and password provided.</p>
                  <form action="<?php echo htmlspecialchars($_SERVER['PHP_SELF']); ?>" method="POST" style="padding: 10px;">
                    <div class="u-form-group u-form-name">
                      <label for="name-d3ff" class="u-label">Username:</label>
                      <input type="text" placeholder="Enter your username..." id="name-d3ff" name="username" class="u-border-1 u-border-grey-30 u-input u-input-rectangle u-radius-2 u-white" required>
                    </div>
                    <div class="u-form-group u-form-group-2">
                      <label for="text-f74c" class="u-label">Password:</label>
                      <input type="password" id="text-f74c" name="password" class="u-border-1 u-border-grey-30 u-input u-input-rectangle u-radius-2 u-white" placeholder="Enter your password..." required>
                    </div>
                    <div class="u-align-center u-form-group u-form-submit">
                      <button type="submit" name="sbt" class="u-border-none u-btn u-btn-round u-btn-submit u-button-style u-custom-color-5 u-hover-custom-color-5 u-radius-8 u-text-hover-black u-btn-1">Login</button>
                    </div>
                  </form>
                </div>
              </div>
            </div>
          </div>
        </div>
      </div>
    </div>
  </section>

  <?php
  include_once('incs/footer.inc.php');

  ?>
</body>

</html>