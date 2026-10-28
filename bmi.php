<!DOCTYPE html>
<html lang="en">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Document</title>
    <link rel="stylesheet" href="stile.css">
</head>
<body>
    <?php 
        if ($_SERVER['REQUEST_METHOD'] != "POST"){
            header("Location: form.html");
            exit();
        }
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
            echo "<a href='form.html' class='btn-ricalcola'>Calcola di nuovo</a>";
        }

        // Controlli pre-scrittura su file
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
            $bmi = "NOT_VALID";
            $classe = "NOT_VALID";
        } else {
            $bmi = number_format($bmi, 2);
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
            $record .= $bmi;
            $record .= ", ";
            $record .= $classe;
            $record .= ";\n";
            fwrite($file, $record);
            fclose($file);
        }
    ?>
    
</body>
</html>