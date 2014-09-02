<?php 
include 'core/init.php';
protect_page();
if (empty($activeProject)) {  //redirects if there's no active project for the user (if they've not created one)
	header("Location: new_project.php");
	exit;
}
require_once("includes/fpdf17/fpdf.php");

$pdf = new FPDF('L','mm','Letter');
$pdf->AliasNbPages();
$pdf->AddPage();
$pdf->SetFont('Times','',12);

$project_id = $activeProject['project_id'];
$characters = get_project_characters($project_id);
$factions = get_project_factions($project_id);
$firstPage = true;
foreach ($characters as $faction_num => $faction_characters) {
	if ($factions[$faction_num]['deleted']) {
		continue;
	}
	foreach ($faction_characters as $character_num => $faction_character) {
		if ($faction_character['deleted']) {
			continue;
		}
		if ( ! $firstPage) {
			$pdf->AddPage();
		}
		$firstPage = false;
		$character_data = get_character_data($project_id, $faction_character['character_id']);
		$pdf->cell(45, 10, 'Character: ' . $faction_character['character_name'], 0, 0, 'L');
		$pdf->cell(45, 10, 'Player: ' . $faction_character['player_name'], 0, 0, 'L');
		$pdf->cell(45, 10, 'Faction: ' . $factions[$faction_num]['faction_name'], 0, 1, 'L');
		$pdf->multicell(90, 4, 'Bio: ' . $faction_character['character_bio'], 0, 2);
		$pdf->Ln();
		$inner_characters = $characters;
		foreach ($inner_characters as $inner_faction_num => $inner_faction_characters) {
			if ($factions[$inner_faction_num]['deleted']) {
				continue;
			}
			$pdf->cell(45, 4, $factions[$inner_faction_num]['faction_name'], 0, 1);
			foreach ($inner_faction_characters as $inner_character_num => $inner_faction_character) {
				if ($inner_faction_character['deleted']) {
					continue;
				}
				$pdf->cell(21.5, 4, $inner_faction_character['character_name'], 1, 0);
			}
			$pdf->Ln();
			foreach ($inner_faction_characters as $inner_character_num => $inner_faction_character) {
				if ($inner_faction_character['deleted']) {
					continue;
				}
				$opinion['opinion_word'] = "Neutral";
				$opinion['opinion_text'] = "No Opinion";
				if ($character_data['character']['character_id'] != $inner_faction_character['character_id']) {
					$opinion = get_character_opinions('c2c', $project_id, $character_data['character']['character_id'], $inner_faction_character['character_id']);
				}
				$pdf->cell(21.5, 4, $opinion['opinion_word'], 1, 0);
			}
			$pdf->Ln();
			foreach ($inner_faction_characters as $inner_character_num => $inner_faction_character) {
				if ($inner_faction_character['deleted']) {
					continue;
				}
				$pdf->cell(23, 4, $inner_faction_character['character_name'] . ' - ' . $opinion['opinion_word'] . ' - ' . $opinion['opinion_text'], 0, 1);
			}
			$pdf->Ln();
			$pdf->Ln();
		}
	}
}
$pdf->Output();