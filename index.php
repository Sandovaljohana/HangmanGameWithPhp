<?php session_start(); ?>
<?php include 'functions.php'; ?>
<?php include 'config.php'; ?>

<!DOCTYPE html>
<html lang="en">

<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <link rel="preconnect" href="https://fonts.googleapis.com">
    <link rel="preconnect" href="https://fonts.gstatic.com" crossorigin>
    <link href="https://fonts.googleapis.com/css2?family=Sofia&display=swap" rel="stylesheet">
    <title>Ahorcado</title>
    <link rel="stylesheet" href="styles.css">
</head>

<body class="background hangman-container">
    <h1 class="hangman-info">Juego del Ahorcado</h1>
    <div class="hangman-container">
        <div class="hangman-image">
            <img src="<?php echo getCurrentPicture(getCurrentPart()); ?>" alt="Hangman Image" />
            <?php $gameResult = gameComplete(); ?>
            <?php if ($gameResult) : ?>
                <h1></h1>
                <?php if ($gameResult === "WON") : ?>
                    <p class="game-won">Ganaste!</p>
                <?php elseif ($gameResult === "LOST") : ?>
                    <p class="game-lost">Perdiste! Inténtalo de nuevo!</p>
                <?php endif; ?>
            <?php endif; ?>
        </div>

        <div >
            <div class="letter-buttons">
                <form class="box-letters" method="get">
                    <?php
                    $max = strlen($letters) - 1;
                    for ($i = 0; $i <= $max; $i++) :
                    ?>
                        <button type='submit' name='kp' value='<?php echo $letters[$i]; ?>'>
                            <?php echo $letters[$i]; ?>
                        </button>
                    <?php
                        if ($i % 7 == 0 && $i > 0) {
                            echo '<br>';
                        }
                    endfor;
                    ?>
                    <br><br>
                    <button type="submit" name="start">Empezar de nuevo</button>
                </form>
            </div>
        </div>

        <div class="hangman-guess">
            <?php
            $guess = $_SESSION["word"];
            $responses = getCurrentResponses();
            $maxLetters = strlen($guess) - 1;
            for ($j = 0; $j <= $maxLetters; $j++) :
                $l = $guess[$j];
            ?>
                <?php if (in_array($l, $responses)) : ?>
                    <span class="guessed-letter"><?php echo $l; ?></span>
                <?php else : ?>
                    <span class="unguessed-letter">&nbsp;&nbsp;&nbsp;</span>
                <?php endif; ?>
            <?php endfor; ?>
        </div>
    </div>

</body>

</html>