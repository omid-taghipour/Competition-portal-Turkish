<?php
@session_start();
if ($_SESSION['userid'] != 21474836) {
    header('../login.php');
}
require_once('db.inc.php');

$query = "SELECT COUNT(*) FROM juri_members";
$stmt = $conn->prepare($query);
$stmt->execute();
$juri_number = $stmt->fetchColumn() - 1;


$query = "SELECT COUNT(*) FROM schools";
$stmt = $conn->prepare($query);
$stmt->execute();
$school_number = $stmt->fetchColumn();


header("Content-type: application/vnd.ms-excel");
header("Content-Disposition: attachment; filename=juri_results_confirmation.com.xls");


//
for ($k = 1; $k <= $juri_number; $k++) {
    echo 'Liseler arası Girişimcilik/Yenilikçilik Fikir Yarışması';
    echo '<br/>';
    echo '21 Nisan 2022 - Perşembe';
    echo '<br/>';
    echo 'Jüri Değerlendirme Formu';
    $query = "SELECT schools.school_name, schools.result, grades.grade
            FROM schools, grades
            WHERE grades.juri_id = ? and schools.school_id = grades.school_id";
    $stmt = $conn->prepare($query);
    $stmt->execute([$k]);
    $result = $stmt->fetchAll(PDO::FETCH_ASSOC);
    if (count($result) <= 0) {
        echo "No data found";
    } else {
?>
        <br>
        <!-- Creating summary table for each juri member -->
        <table border='1'>
            <tr>
                <th></th>
                <?php
                for ($i = 0; $i < count($result); $i += 10) {
                    echo '<th>' . $result[$i]['school_name'] . '</th>';
                }
                echo '</tr>';

                $query = "SELECT criteria_name, percent
                    FROM criterias";
                $stmt = $conn->prepare($query);
                $stmt->execute();
                $criteria_result = $stmt->fetchAll(PDO::FETCH_ASSOC);
                
                foreach ($criteria_result as $index => $criteria) {
                    echo '<tr>';
                    echo '<th>' . $criteria['criteria_name'] . ' - ' . $criteria['percent'] . '%</th>';
                    for ($i = $index; $i < count($result); $i += 10) {
                        echo '<td>' . $result[$i]['grade'] . '</td>';
                    }
                }
                echo '<tr><th>TOPLAM(100%)</th>';
                $sum = 0.0;
                for ($i = 0; $i < count($result); $i++) {
                    if ((($i % 10 == 0) && ($i != 0))) {
                        // Formula : Toplam = (sum * 100) / 50
                        echo '<td>' . ($sum * 2) . '</td>';
                        $sum = 0;
                    }
                    // if(){
                    //     echo '<td>' . ($sum * 100) / 50 . '</td>';
                    //     $sum = 0;                       
                    // }
                    $sum += $result[$i]['grade'];
                }
                echo '</tr></tr>';
                ?>
        </table>
        <br />
        <br />
        <table style="width: 100%;" border="0">
            <tr>
                <?php
                $query = "SELECT full_name FROM juri_members WHERE juri_id = ?";
                $stmt = $conn->prepare($query);
                $stmt->execute([$k]);
                $juri_name = $stmt->fetch(PDO::FETCH_ASSOC);
                echo '<th>Jüri Üyesi: ' . $juri_name['full_name'] . '</th>';
                ?>
                <th>İmza:</th>
            </tr>
        </table>

<?php
    }
    echo '<br/><br/><br/><br/><br/><br/><br/><br/><br/><br/><br/><br/><br/><br/><br/>';
}
?>