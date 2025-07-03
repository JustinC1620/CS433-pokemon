<!-- 
* File: select6.js 
 * Author: Justin C & Kevin K 
 * Description: Page where user selects their pokemon team
-->
<?php
require "scripts/utils.php";

if (array_key_exists("team", $_POST)){      // If redirected from select_moves.php

    $team = json_decode($_POST["team"]);
}
else {                                      // If redirected from proj3.php
    // Generate 6 random IDs between 1 and 151 excluding 132 (Ditto has no moves)
    $randIds = range(1, 151);
    unset($randIds[131]);
    shuffle($randIds);
    $randIds = array_slice($randIds, 0, TEAMSIZE);

    // Populate team
    $team = new Team(); 
    for ($i = 0; $i < TEAMSIZE; $i++) {     
        $team->pkm[$i] = new Pokemon();
        $team->pkm[$i]->id = $randIds[$i];  // Assign random ids to pokemon
    }

    // Loads pokemon team data given a set of Pokedex Ids, 
    foreach ($randIds as $key => $id) {  // Iterates over team
        $team->pkm[$key] = makePokemon($team->pkm[$key]->id);
    }
}

$volume = $_POST["volume"]; // Get volume setting 
$muted = $_POST["muted"];   // Get mute setting 
?>

<!DOCTYPE html>
<html lang="en">
<head>
    <meta charset="UTF-8">
    <title>Choose your Pokémon</title>
    <link rel="stylesheet" href="styles/shared.css">
    <link rel="stylesheet" href="styles/select6.css">
    <link href="https://fonts.googleapis.com/css2?family=Press+Start+2P&display=swap" rel="stylesheet">  <!-- Link to font -->
    <script src="https://ajax.googleapis.com/ajax/libs/jquery/3.7.1/jquery.min.js"></script>
    <script src="./scripts/utils.js"></script>
    <script src="./scripts/select6.js"></script>
</head>
<body>
<audio id="bgMusic" muted loop hidden>
    <source src="dataFiles/audio/songs/Route_8-XY.mp3">
</audio>
<audio id="btnClk" muted hidden>
    <source src="dataFiles/audio/sfx/Pokemon_(A_Button).mp3">
</audio>
<div class="center">                                              <!-- Center Block Of Content -->
    <div class="container">                                           <!-- Pokedex Grey Border -->
        <h1>Choose Your Team</h1>                                         <!-- Page Title -->
        <div class="center">                                              <!-- Center Block Of Content -->
            <div class="screen">                                              <!-- Pokedex Screen -->
                <div class="bor header-row">                                      <!-- Page Header -->
                    <span class="left">Pokemon Types</span>                           <!-- Pokemon Name and Type Header -->
                    <span class="right">                                              <!-- Pokemon Stats Header -->
                        <?php foreach ($team->pkm[0]->attr as $key => $stat): ?>          <!-- Pokemon Stat Header -->
                            <?php if ($key != "legendary"): ?>
                                <span class="stat <?= $key ?>-stat"><?= ucfirst($key) ?></span>
                            <?php endif; ?>
                        <?php endforeach; ?>
                    </span>
                </div>
                <?php foreach ($team->pkm as $i => $pkm): ?>                      <!-- Pokemon Entries -->
                    <button type="button" class="entry" id="pkm<?= $i ?>">            <!-- Pokemon Entry -->
                        <div class="left">
                            <img src="<?= $pkm->img ?>" name="<?= $pkm->id ?>">           <!-- Pokemon Image Entry -->
                            <span class="name"><?= $pkm->name ?></span>                   <!-- Pokemon Name Entry -->
                            <span class="types">                                          <!-- Pokemon Types Entry -->
                                <?php foreach ($pkm->types as $type): ?>                      <!-- Pokemon Type image Entry -->
                                    <?php if ($type): ?>
                                        <img class="type-icon"
                                                src="<?= FPATH . TPATH . strtolower($type) ?>.png"
                                                alt="<?= $type ?>">
                                    <?php endif; ?>
                                <?php endforeach; ?>
                            </span>
                        </div>
                        <div class="right">                                               <!-- Pokemon Stats Entry -->
                            <?php foreach ($pkm->attr as $j => $stat): ?>                     <!-- Pokemon Stat Entry -->
                                <?php if ($j != "legendary"): ?>
                                    <span class="stat <?= $j ?>-stat" name="stat<?= $j ?>"><?= $stat ?></span>
                                <?php endif; ?>
                            <?php endforeach; ?>
                        </div>
                    </button>
                <?php endforeach; ?>
            </div>
        </div>                                                                            
        <div class="center">                                                              <!-- Center Block Of Content -->
            <div class="menu">                                                                <!-- Menu Buttons -->
                <button type="button" class="back" id="back">Back</button>                        <!-- Back Button -->
                <button id="sound">Toggle Sound</button>                                          <!-- Toggle Sound Button -->
                <button id="up">Volume Up</button>                                                <!-- Volume Up Button -->
                <button id="down">Volume Down</button>                                            <!-- Volume Down Button -->
                <button type="button" class="reroll" id="reroll">Reroll Pokemon</button>          <!-- Reroll Button -->
                <button type="button" class="confirm" id="confirm">Confirm Team</button>          <!-- Confirm Button -->
            </div>
        </div> 
    </div>
</div>
<div id="teamJSON" hidden><?= json_encode($team) ?></div>         <!-- Team Data For JS -->
<div id="volumeJSON" hidden><?= $volume ?></div>                  <!-- Volume Data For JS -->
<div id="mutedJSON" hidden><?= $muted ?></div>                    <!-- Mute Data For JS -->            
</body>
</html>