<?php
require('core.php');

$DBCon->SetLangAndAlias(); /* si funkcija naudoajama pacioje pradzioje kalbos ir aliasu uzsetinimui pagal linką */
$DBCon->SetMeniu(); //užsetina meniu ul->li 1na uzklausa
$DBCon->SetTransalteVars(); /* Ši funkcija naudoajama transleitų vertimui į smarty kintamuosius  1 uzklausa*/
$DBCon->SetContents(); //užsetina turiny, nustato aliasa 1na arba 2 uzklausos
$DBCon->languageChooser();

$DBCon->DisplayPage();

