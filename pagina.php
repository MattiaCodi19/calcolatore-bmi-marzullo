    <?php 
        // Inizializzazione variabili per evitare errori
        $nome = "";
        $peso = "";
        $altezza = "";
        $bmi = 0;
        $classe = "NOT_VALID";

        // Eseguiamo i calcoli e l'output della card solo se il form è stato inviato
        if ($_SERVER['REQUEST_METHOD'] == "POST"){
            $nome = $_POST['nome'];
            $peso = $_POST['peso'];
            $altezza = $_POST['altezza'];
            if ($nome == "" || $peso == "" || $altezza == "") {
                echo "Campi non correttamente inseriti.";
            } else {
                // Conversione dell'altezza da cm a metri per la formula
                $altezza_m = $altezza / 100;
                $bmi = 0;
                if ($altezza_m > 0) {
                    // Calcolo BMI: peso (kg) / altezza (m)^2
                    $bmi = $peso / ($altezza_m * $altezza_m);
                }
                $bmi_formattato = number_format($bmi, 2);
                // Determinazione della fascia e della classe CSS
                if ($bmi < 18.5) {
                    $classe = "sottopeso";
                    $fascia = "SOTTOPESO";
                } else if ($bmi >= 18.5 && $bmi <= 24.9) {
                    $classe = "normopeso";
                    $fascia = "NORMOPESO";
                } else if ($bmi >= 25 && $bmi <= 29.9) {
                    $classe = "sovrappeso";
                    $fascia = "SOVRAPPESO";
                } else {
                    $classe = "obesita";
                    $fascia = "OBESITÀ";
                }
                // Output della Card Risultato
                echo "<h1>Risultato per $nome</h1>";
                echo "<div class='card $classe'>";
                echo "  <div class='valore-bmi'>$bmi_formattato</div>";
                echo "  <div class='fascia-bmi'>$fascia</div>";
                echo "</div>";
            }
        }

        // Controlli pre-scrittura su file (eseguiti solo se è stato inviato il form)
        if ($_SERVER['REQUEST_METHOD'] == "POST"){
            if (empty($nome) || is_numeric($nome)) {
                $nome = "NOT_VALID";
            }
            if (empty($peso) || !is_numeric($peso)) {
                $peso = "NOT_VALID";
            }
            if (empty($altezza) || !is_numeric($altezza)) {
                $altezza = "NOT_VALID";
            }
            if ($bmi == 0) {
                $bmi_log = "NOT_VALID";
                $classe = "NOT_VALID";
            } else {
                $bmi_log = number_format($bmi, 2);
            }
            
            // Creazione e scrittura dei valori sul file
            $nf = "log.csv";
            $file = fopen($nf, "a");
            if ($file) {
                $record = date("d/m/Y");
                $record .= ", ";
                $record .= date("g:i:s");
                $record .= ", ";
                $record .= $_SERVER['REMOTE_ADDR'];
                $record .= ", ";
                $record .= $nome;
                $record .= ", ";
                $record .= $peso;
                $record .= ", ";
                $record .= $altezza;
                $record .= ", ";
                $record .= $bmi_log;
                $record .= ", ";
                $record .= $classe;
                $record .= ";\n";
                fwrite($file, $record);
                fclose($file);
            }
        }
    ?>


<!DOCTYPE html>
<html lang="en">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Calcolatore BMI</title>
    <link rel="stylesheet" href="stile.css">
</head>
<body>
    <form action="pagina.php" method="post">
        <h1>Calcolatore BMI</h1>
        <label for="nome" id="nome">Nome </label>
        <input type="text" name="nome" id="nome" placeholder="Luca">

        <label for="peso" id="peso">Peso (kg) </label>
        <input type="number" name="peso" id="peso" placeholder="70">

        <label for="altezza" id="nome">Altezza (cm) </label>
        <input type="number" name="altezza" id="altezza" placeholder="178">

        <input type="submit" value="Calcola">
</form>
</body>

</html>