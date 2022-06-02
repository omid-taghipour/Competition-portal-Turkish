<!DOCTYPE html>
<html style="font-size: 16px;">

<head>
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <meta charset="utf-8">
    <meta name="keywords" content="Welcome">
    <meta name="description" content="">
    <meta name="page_type" content="np-template-header-footer-from-plugin">
    <title>Admin</title>
    <link rel="stylesheet" href="nicepage.css" media="screen">
    <link rel="stylesheet" href="Main.css" media="screen">
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
    <meta property="og:title" content="Main">
    <meta property="og:type" content="website">
</head>

<body class="u-body u-xl-mode">
    <?php
    @session_start();
    if ($_SESSION['userid'] != 21474836) {
        echo "<script>document.location.href='main.php';</script>";
    }
    include_once('incs/header.inc.php');
    if (isset($_GET['msg'])) {
        if ($_GET['msg'] == "closed") {
            echo '<div class="alert alert-primary" role="alert">
                    Grade changing is closed and results are shared!
                  </div>';
        }
    }
    ?><br>
    <section class="u-align-center u-clearfix u-section-1" id="sec-359e">
        <div class="jumbotron">
            <h1 class="display-4">Admin Dashboard</h1>
            <p class="lead">Quick functions are designed specially for the admin of the Juri Grading system</p>
            <hr class="my-4">
            <p>Choose function by clicking on the buttons below!</p>
            <form action="<?php echo htmlspecialchars($_SERVER['PHP_SELF']); ?>" method="POST">
                <p class="lead">
                    <button type="submit" class="btn btn-success btn-lg" name="preresult_btn" role="button">Publish the pre-result of competition</button>
                </p>

                <p class="lead">
                    <button type="submit" name="finalresult_btn" class="btn btn-warning btn-lg">Publish the FINAL reuslt</button>
                </p>

                <p class="lead">
                    <a class="btn btn-primary btn-lg" href="incs/excel.inc.php" role="button">Export results in Excel</a>
                </p>

            </form>
        </div>
    </section>

    <?php
    include_once('incs/footer.inc.php');
    if (isset($_POST['preresult_btn'])){
        try{
            $query = "UPDATE activation SET activation_status = -1";
            $stmt = $conn->prepare($query);
            $stmt->execute();
            echo "<script>alert('Pre results are published!');</script>";
        }catch(Exception $ex){
            echo "Error -1: " . $ex->getMessage();
        }
        
    } elseif(isset($_POST['finalresult_btn'])){
        // Close grading
        $query = "UPDATE activation SET activation_status = 0";
        $stmt = $conn->prepare($query);
        $stmt->execute();
        echo "<script>alert('Final results are published!');</script>";

    }
    ?>
</body>

</html>