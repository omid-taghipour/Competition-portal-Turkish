<?php
@session_start();
if (!isset($_SESSION['userid'])) {
    try {
        echo "<script>location.href='../login.php'</script>";
        // header('Location: ../login.php');
    } catch (Exception $ex) {
        echo "<script>location.href='../login.php';</script>";
    }
} else {
    include_once('db.inc.php');
    // Reading number of schools from database
    $query = "SELECT COUNT(*) FROM schools";
    $cout_stmt = $conn->prepare($query);
    $cout_stmt->execute();
    $schools_number = $cout_stmt->fetchColumn();

    // Reading number of Juris from database
    $query = "SELECT COUNT(*) FROM juri_members";
    $cout_stmt = $conn->prepare($query);
    $cout_stmt->execute();
    $juris_number = $cout_stmt->fetchColumn() - 1;

    // if ($_GET['type'] == 'final') {

    //     echo "Closed!";
    // }
    // calculate grades for each group criteria by criteria
    // define("schools_number", 15);


    // publish the result
    // }
    //  else if ($_GET['type'] == 'pre') {
    for ($i = 1; $i <= $schools_number; $i++) {
        // grab a school
        $query = "SELECT grade 
                  FROM grades
                  WHERE school_id = ?";
        try {
            $stmt = $conn->prepare($query);
            $stmt->execute([$i]);
            $grade_result = $stmt->fetchAll(PDO::FETCH_ASSOC);
        } catch (Exception $ex) {
            echo "Error in grades selection" . $ex->getMessage();
        }

        // Calculating the score for each school
        $score = 0;
        for ($j = 0; $j < count($grade_result); $j++) {
            $score += ($grade_result[$j]['grade'] * 2);
        }
        $score = $score / $juris_number;

        // Update database
        try {
            $query = "UPDATE schools SET result = ? WHERE school_id = ?";
            $stmt = $conn->prepare($query);
            $stmt->execute([$score, $i]);
        } catch (Exception $ex) {
            echo "Update was not successful";
        }
        header('location: ../adminpanel.php?msg=closed');
    }
    // }
}
