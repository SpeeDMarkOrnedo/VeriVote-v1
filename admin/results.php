    <?php 
    include '../config.php';

        // Reset all votes
        if(isset($_POST['reset_results'])){
            $conn->query("DELETE FROM votes");
            echo "<script>alert('All election results have been reset.'); window.location.href='".$_SERVER['PHP_SELF']."';</script>";
            exit();
        }
    if(!isset($_SESSION['admin'])) header("Location: login.php");

    date_default_timezone_set("Asia/Manila");
    ?>
    <?php
    use Dompdf\Dompdf;
    use Dompdf\Options;

    if(isset($_POST['export_pdf'])){

    require_once '/dompdf/vendor/autoloader.php';
    Dompdf\autoloader::register();

        $options = new Options();
        $options->set('isRemoteEnabled', true);

        $dompdf = new Dompdf($options);

        $html = "<h1>Election Results</h1>";
        $html .= "<p>Generated at: ".date("F d, Y h:i A")."</p>";

        $positions = $conn->query("SELECT * FROM positions");

        while($pos = $positions->fetch_assoc()){

            $html .= "<h2>".$pos['position_name']."</h2>";
            $html .= "<table border='1' width='100%' cellpadding='5' cellspacing='0'>";
            $html .= "<tr>
                        <th>Photo</th>
                        <th>Name</th>
                        <th>Votes</th>
                    </tr>";

            $results = $conn->query("
                SELECT candidates.id,
                    candidates.name,
                    candidates.photo,
                    COUNT(votes.id) as total_votes
                FROM candidates
                LEFT JOIN votes ON candidates.id = votes.candidate_id
                WHERE candidates.position_id=".$pos['id']."
                GROUP BY candidates.id
                ORDER BY total_votes DESC
            ");

            while($row = $results->fetch_assoc()){

                $photoPath = "../uploads/".$row['photo'];

                if(!empty($row['photo']) && file_exists($photoPath)){
                    $imageData = base64_encode(file_get_contents($photoPath));
                    $src = 'data:image/jpeg;base64,'.$imageData;
                } else {
                    $src = "";
                }

                $html .= "<tr>
                            <td>";
                if($src != ""){
                    $html .= "<img src='".$src."' width='50' height='50'>";
                }
                $html .= "</td>
                            <td>".$row['name']."</td>
                            <td>".$row['total_votes']."</td>
                        </tr>";
            }

            $html .= "</table><br>";
        }

        $dompdf->loadHtml($html);
        $dompdf->setPaper('A4', 'portrait');
        $dompdf->render();
        $dompdf->stream("Election_Results.pdf", ["Attachment" => true]);
        exit();
    }
    ?>
    
    <!DOCTYPE html>
    <html>
    <head>
        <title>Election Results</title>
        <link rel="stylesheet" href="../assets/css/admin.css">
        <style>
            .candidate{
                display:flex;
                align-items:center;
                justify-content:space-between;
                padding:10px;
                margin-bottom:8px;
                border-radius:8px;
            }

            .candidate-info{
                display:flex;
                align-items:center;
                gap:12px;
            }

            .candidate img{
                width:50px;
                height:50px;
                border-radius:50%;
                object-fit:cover;
                border:2px solid #ddd;
            }

            .winner{
                background:#e6ffed;
                border-left:5px solid #28a745;
            }

            .loser{
                background:#fff5f5;
                border-left:5px solid #dc3545;
            }
            .draw{
                background:#fff3cd;
                border-left:5px solid #ffc107;
            }

            .badge{
                padding:4px 8px;
                border-radius:12px;
                font-size:12px;
                font-weight:bold;
            }

            .badge-win{
                background:#28a745;
                color:#fff;
            }

            .badge-lose{
                background:#dc3545;
                color:#fff;
            }
        </style>
    </head>
    <body>

    <?php include '../includes/admin_sidebar.php'; ?>

    <div class="main-content">

    <h1 class="page-title">Election Results</h1>
    <!-- <form method="POST" style="margin-bottom:15px;">
    <button type="submit" name="export_pdf" class="btn btn-primary">
        Export to PDF
    </button>
</form> -->

<form method="POST" style="margin-bottom:15px;" onsubmit="return confirm('Are you sure you want to reset all results?');">
    <button type="submit" name="reset_results" class="btn btn-danger">
        Reset Results
    </button>
</form>
    <div class="timestamp">
        Generated at: <?= date("F d, Y h:i A"); ?>
    </div>

    <?php
$positions = $conn->query("SELECT * FROM positions");

while($pos = $positions->fetch_assoc()){

    echo "<div class='position-card'>";
    echo "<h2>".$pos['position_name']."</h2>";

    $results = $conn->query("
        SELECT candidates.id,
            candidates.name,
            candidates.photo,
            COUNT(votes.id) as total_votes
        FROM candidates
        LEFT JOIN votes ON candidates.id = votes.candidate_id
        WHERE candidates.position_id=".$pos['id']."
        GROUP BY candidates.id
        ORDER BY total_votes DESC
    ");

    $maxVotes = 0;
    $candidates = [];

    // Collect candidates and find the maximum votes
    while($row = $results->fetch_assoc()){
        $candidates[] = $row;
        if($row['total_votes'] > $maxVotes){
            $maxVotes = $row['total_votes'];
        }
    }

    // Mark all candidates with maxVotes as winners (draw included)
    foreach($candidates as $row){

        $isWinner = ($row['total_votes'] == $maxVotes && $maxVotes > 0);
        $photoPath = "../uploads/".$row['photo'];

        echo "<div class='candidate ".($isWinner ? 'winner' : 'loser')."'>";
        echo "<div class='candidate-info'>";

        if(!empty($row['photo']) && file_exists($photoPath)){
            echo "<img src='".$photoPath."'>";
        } else {
            echo "<img src='../uploads/default.png'>";
        }

        echo "<span>".$row['name']." - ".$row['total_votes']." votes</span>";
        echo "</div>";

        if($isWinner){
            // If more than one candidate has maxVotes, indicate DRAW
            $winnerCount = count(array_filter($candidates, fn($c) => $c['total_votes'] == $maxVotes));
            echo "<span class='badge badge-win'>".($winnerCount > 1 ? 'DRAW' : 'WINNER')."</span>";
        } else {
            echo "<span class='badge badge-lose'>LOSER</span>";
        }

        echo "</div>";
    }

    echo "</div>";
}
?>
    </div>
    </body>
    </html>