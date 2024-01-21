
<?php session_start();?>
<?php include 'functions.php' ; ?>
<?php include 'config.php' ; ?>

<!DOCTYPE html>
<html lang="en">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Hangman game</title>
    <link rel="stylesheet" href="styles.css">
</head>
<body class="background">

<div class="hangman-container">
    <div class="hangman-image">
        <img src="<?php echo getCurrentPicture(getCurrentPart());?>" alt="Hangman Image"/>
        <?php $gameResult = gameComplete(); ?>
        <?php if ($gameResult): ?>
            <h1>GAME COMPLETE</h1>
            <?php if ($gameResult === "WON"): ?>
                <p class="game-won">You Won! HURRAY! :)</p>
            <?php elseif ($gameResult === "LOST"): ?>
                <p class="game-lost">You LOST! OH NO! :(</p>
            <?php endif; ?>
        <?php endif; ?>
    </div>

    <div class="hangman-info">
        <h1>Hangman the Game</h1>
        <div class="letter-buttons">
            <form method="get">
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
                <button type="submit" name="start">Restart Game</button>
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
            <?php if (in_array($l, $responses)): ?>
                <span class="guessed-letter"><?php echo $l; ?></span>
            <?php else: ?>
                <span class="unguessed-letter">&nbsp;&nbsp;&nbsp;</span>
            <?php endif; ?>
        <?php endfor; ?>
    </div>
</div>

</body>
</html>