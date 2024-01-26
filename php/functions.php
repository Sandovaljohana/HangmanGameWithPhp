<?php

function getCurrentPicture($part){
    return "./src/images/hangman_" . $part . ".png";
}

function startGame(){
    session_destroy();
    session_start();
    initializeParts();
    $_SESSION["current_part"] = getCurrentPart(); 
}

function restartGame(){
    session_destroy();
    session_start();
    initializeParts(); 
    $_SESSION["current_part"] = getCurrentPart();
}

function getParts(){
    return isset($_SESSION["parts"]) ? $_SESSION["parts"] : initializeParts();
}

function initializeParts(){
    global $bodyParts;
    $_SESSION["parts"] = $bodyParts;
    $_SESSION["current_part"] = "full";
    $_SESSION["word"] = getRandomWord();
    $_SESSION["responses"] = [];
    $_SESSION["gamecomplete"] = false;
}

function getRandomWord(){
    global $words;
    if (is_array($words) && !empty($words)) {
        $key = array_rand($words);
        return $words[$key];
    } else {
        return "PALABRA_POR_DEFECTO";
    }
}

function addPart(){
    $parts = getParts();
    if (!isBodyComplete()) {
        array_shift($parts);
        $_SESSION["parts"] = $parts;
        $_SESSION["current_part"] = $parts[0];
    }
}

function getCurrentPart(){
    $parts = getParts();
    return $_SESSION["current_part"];
}

function getCurrentResponses(){
    return isset($_SESSION["responses"]) ? $_SESSION["responses"] : [];
}

function addResponse($letter){
    $responses = getCurrentResponses();
    array_push($responses, $letter);
    $_SESSION["responses"] = $responses;
}

function isLetterCorrect($letter){
    $word = $_SESSION["word"];
    return strpos($word, $letter) !== false;
}

function isWordCorrect(){
    $guess = $_SESSION["word"];
    $responses = getCurrentResponses();
    return str_replace($responses, '', $guess) === '';
}

function isBodyComplete(){
    $parts = getParts();
    return is_array($parts) && count($parts) <= 1;
}

function gameComplete(){
    if (isWordCorrect() && !isBodyComplete()) {
        return "WON";
    } elseif (isBodyComplete()) {
        return "LOST";
    }
    return false;
}

function markGameAsComplete(){
    $_SESSION["gamecomplete"] = true;
}

function markGameAsNew(){
    $_SESSION["gamecomplete"] = false;
}

if(isset($_GET['start'])){
    restartGame();
}

if(isset($_GET['kp'])){
    $currentPressedKey = isset($_GET['kp']) ? $_GET['kp'] : null;
    if($currentPressedKey && isLetterCorrect($currentPressedKey) && !isBodyComplete() && !gameComplete()){
        addResponse($currentPressedKey);
        if(isWordCorrect()){
            markGameAsComplete();
        }
    } else {
        if(!isBodyComplete()){
            addPart(); 
            if(isBodyComplete()){
                markGameAsComplete();
            }
        } else {
            markGameAsComplete(); 
        }
    }
}

?>