<?php

function getCurrentPicture($part){
    return "./images/hangman_" . $part . ".png";
}

function startGame(){
    session_destroy();
    session_start();
    initializeParts(); // Inicializa las partes
    $_SESSION["current_part"] = getCurrentPart(); // Inicializa con la primera parte
}

// restart the game. Clear the session variables
function restartGame(){
    session_destroy();
    session_start();
    initializeParts(); // Inicializa las partes
    $_SESSION["current_part"] = getCurrentPart(); // Reinicia con la primera parte
}

// Get all the hangman Parts
function getParts(){
    return isset($_SESSION["parts"]) ? $_SESSION["parts"] : initializeParts();
}

function initializeParts(){
    global $bodyParts;
    $_SESSION["parts"] = $bodyParts;
    $_SESSION["current_part"] = "full"; // Asegurar que la parte actual sea "full" al inicio
    $_SESSION["word"] = getRandomWord();
    $_SESSION["responses"] = [];
    $_SESSION["gamecomplete"] = false;
}

function getRandomWord(){
    global $words;
    $key = array_rand($words);
    return $words[$key];
}

// add part to the Hangman
function addPart(){
    $parts = getParts();
    if (!isBodyComplete()) {
        array_shift($parts);
        $_SESSION["parts"] = $parts;
        $_SESSION["current_part"] = $parts[0]; // Actualizar la parte actual
    }
}

// get Current Hangman Body part
function getCurrentPart(){
    $parts = getParts();
    return $_SESSION["current_part"];
}

// user responses logic

// get user response
function getCurrentResponses(){
    return isset($_SESSION["responses"]) ? $_SESSION["responses"] : [];
}

function addResponse($letter){
    $responses = getCurrentResponses();
    array_push($responses, $letter);
    $_SESSION["responses"] = $responses;
}

// check if pressed letter is correct
function isLetterCorrect($letter){
    $word = $_SESSION["word"];
    return strpos($word, $letter) !== false;
}

// is the word (guess) correct
function isWordCorrect(){
    $guess = $_SESSION["word"];
    $responses = getCurrentResponses();
    return str_replace($responses, '', $guess) === '';
}

// check if the body is ready to hang
function isBodyComplete(){
    $parts = getParts();
    // is the current parts less than or equal to one
    return count($parts) <= 1;
}

// manage game session

// is game complete
function gameComplete(){
    if (isWordCorrect() && !isBodyComplete()) {
        return "WON";
    } elseif (isBodyComplete()) {
        return "LOST";
    }
    return false;
}

// set game as complete
function markGameAsComplete(){
    $_SESSION["gamecomplete"] = true;
}

// start a new game
function markGameAsNew(){
    $_SESSION["gamecomplete"] = false;
}

/* Detect when the game is to restart. From the restart button press */
if(isset($_GET['start'])){
    restartGame();
}

/* Detect when Key is pressed */
if(isset($_GET['kp'])){
    $currentPressedKey = isset($_GET['kp']) ? $_GET['kp'] : null;
    // if the key press is correct
    if($currentPressedKey && isLetterCorrect($currentPressedKey) && !isBodyComplete() && !gameComplete()){
        addResponse($currentPressedKey);
        if(isWordCorrect()){
            markGameAsComplete();
        }
    } else {
        // start hanging the man :)
        if(!isBodyComplete()){
            addPart(); 
            if(isBodyComplete()){
                markGameAsComplete(); // lost condition
            }
        } else {
            markGameAsComplete(); // lost condition
        }
    }
}

?>