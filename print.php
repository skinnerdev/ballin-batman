<?php 
	//include 'core/init.php';
	require_once("includes/fpdf17/fpdf.php");
class PDF extends FPDF
{
	// Get character data
	function get_data() {
		$character_data = array();
		include 'core/functions/print_functions.php';
		get_all_data($project_id);
	}
	
	
	// Page header
	/*function Header()
	{
		$character_id = mysql_result(mysql_query("SELECT `character_id` FROM `characters` WHERE `project_id` = '$project_id' && `faction`='1' && `character_number`='1'"),0);
		$character_name = mysql_result(mysql_query("SELECT `character_name` FROM `characters` WHERE `project_id` = '$project_id' && `character_id`='$character_id'"),0);
		// Logo
	   // $this->Image('logo.png',10,6,30);
		// Arial bold 15
		$this->SetFont('Arial','B',15);
		// Move to the right
		$this->Cell(80);
		// Title
		$this->Cell(30,10,$character_name,1,0,'C');
		// Line break
		$this->Ln(20);
	}*/

	// Page footer
	/*function Footer()
	{
		// Position at 1.5 cm from bottom
		$this->SetY(-15);
		// Arial italic 8
		$this->SetFont('Arial','I',8);
		// Page number
		$this->Cell(0,10,'Page '.$this->PageNo().'/{nb}',0,0,'C');
	}*/
}

// Instanciation of inherited class
$pdf = new PDF('L','mm','Letter');
$pdf->AliasNbPages();
$pdf->AddPage();
$pdf->SetFont('Times','',12);

$pdf->get_data();
global $player_name, $character_name, $character_id;

//f2f data
$result = mysql_query("SELECT `faction_1_id`, `faction_2_id`, `opinion_word`, `opinion_text` FROM `opinions_f2f` WHERE (`project_id`='$project_id') ORDER BY `faction_1_id`") or die(mysql_error());
$f2f_opinions = array();
if (mysql_num_rows($result)==0) {
	$f2f_opinion_word = "Neutral";
	$f2f_opinion_text = "No opinion";
} else {
	$number=1;
	while ($row = mysql_fetch_assoc($result)) {
		foreach ($row as $key => $value) {
			if ($key=='faction_1_id') {
				$first_faction = $value;
			} else if ($key=='faction_2_id') {
				$second_faction = $value;
			} else if ($key !== 'faction_1_id' && $key !== 'faction_2_id') {
				$f2f_opinions[$first_faction][$second_faction][$key] = $value;///////////issue
			} else echo "error";
		}
		$number++;
	}
	$f2f_opinions['empty'] = false;
}


//c2f data
$c2f_opinions = array();
$result = mysql_query("SELECT `character_1_id`, `faction_2_id`, `opinion_word`, `opinion_text` FROM `opinions_c2f` WHERE `project_id`='$project_id' ORDER BY `character_1_id`");
if (mysql_num_rows($result)==0) {
	$c2f_opinions['empty'] = true;
} else {
	while ($row = mysql_fetch_assoc($result)) {
		foreach ($row as $key => $value) {
			if ($key=='character_1_id') {
				$c2f_opinions[1] = $value;
			} else if ($key=='faction_2_id') {
				$c2f_opinions[2] = $value;
			} else if ($key!='character_1_id' && $key!='faction_2_id' ) {
				$c2f_opinions[$c2f_opinions[1]][$c2f_opinions[2]][$key] = $value;
			} else echo "error";
		}
	}
	unset($c2f_opinions[0]);
	unset($c2f_opinions[1]);
	unset($c2f_opinions[2]);
	$c2f_opinions['empty'] = false;
}


//c2c opinions
$c2c_opinions = array();
$result = mysql_query("SELECT `character_1_id`, `character_2_id`, `opinion_word`, `opinion_text` FROM `opinions_c2c` WHERE `project_id` = '$project_id' ORDER BY `character_1_id`");
if (mysql_num_rows($result)==0) {
	$c2c_opinions['empty'] = true;
} else {
	while ($row = mysql_fetch_assoc($result)) {
		foreach ($row as $key => $value) {
			if ($key=='character_1_id') {
				$c2c_opinions[1] = $value;
			} else if ($key=='character_2_id') {
				$c2c_opinions[2] = $value;
			} else if ($key!='character_1_id' && $key!='character_2_id' ) {
				$c2c_opinions[$c2c_opinions[1]][$c2c_opinions[2]][$key] = $value;
			} else echo "error";
		}
	}
	unset($c2c_opinions[0]);
	unset($c2c_opinions[1]);
	unset($c2c_opinions[2]);
	$c2c_opinions['empty'] = false;
}

//A list of all character ID's in the project
$character_id_list = array();
$number=0;
$result = mysql_query("SELECT `character_id` FROM `characters` WHERE `project_id`='$project_id' ORDER BY `character_id`");
while($row = mysql_fetch_array($result)){
	$character_id_list[] = $row;  //$character_id_list[0]['character_id']; //range is 0 to 143
	unset($character_id_list[$number][0]);
	$number++;
}

$faction_num=1;
while ($faction_num<=12){
	$character_num=1;
	while ($character_num<=12) {
		$character_id = mysql_result(mysql_query("SELECT `character_id` FROM `characters` WHERE `project_id` = '$project_id' && `faction`='$faction_num' && `character_number`='$character_num'"),0);
		$character_name = mysql_result(mysql_query("SELECT `character_name` FROM `characters` WHERE `project_id` = '$project_id' && `character_id`='$character_id'"),0);
		$player_name = mysql_result(mysql_query("SELECT `player_name` FROM `characters` WHERE `project_id` = '$project_id' && `character_id`='$character_id'"),0);
		$character_data[$faction_num . "_" . $character_num]['character_name'] = $character_name;
		$character_data[$faction_num . "_" . $character_num]['player_name'] = $player_name;
		$character_data[$faction_num . "_" . $character_num]['character_id'] = $character_id;
		$character_num++;
	}
	$faction_num++;
}

global $character_id_1, $character_id_2;
global $opinions;
$opinions = array();
$character_num_1=0;
while ($character_num_1<=143) {
	$character_id_1 = $character_id_list[$character_num_1]['character_id'];
	$faction_num_1 = floor($character_num_1/12)+1;
	$faction_id_1 = mysql_result(mysql_query("SELECT `faction_id` FROM `factions` WHERE `project_id` = '$project_id' && `faction_num`='$faction_num_1'"),0);
	$character_num_2=0;
	while ($character_num_2<=143) {
		if (isset($faction_num_2)) {
			$temp_faction = $faction_num_2;
		} else $temp_faction=0;
		$faction_num_2 = floor($character_num_2/12)+1;
		if ($faction_num_2 != $temp_faction) {
			$faction_id_2 = mysql_result(mysql_query("SELECT `faction_id` FROM `factions` WHERE `project_id` = '$project_id' && `faction_num`='$faction_num_2'"),0);
		}
		$character_id_2 = $character_id_list[$character_num_2]['character_id'];
		if (($c2c_opinions['empty'] == true) || (!isset($c2c_opinions[$character_id_1][$character_id_2]))) {
			//if it's empty or not set, check the c2f opinion
			if (($c2f_opinions['empty'] == true) || (!isset($c2f_opinions[$character_id_1][$faction_id_2]))) {
				//if c2f is empty or not set, set it to the f2f opinion
				
				if (isset($f2f_opinions[$faction_id_1][$faction_id_2]['opinion_word'])) {
					$opinions[$character_id_1][$character_id_2]['opinion_word'] = $f2f_opinions[$faction_id_1][$faction_id_2]['opinion_word'];
					$opinions[$character_id_1][$character_id_2]['opinion_text'] = $f2f_opinions[$faction_id_1][$faction_id_2]['opinion_text'];
				} else {
					$opinions[$character_id_1][$character_id_2]['opinion_word'] = "Neutral";
					$opinions[$character_id_1][$character_id_2]['opinion_text'] = "No Opinion";
				}
			} else {
				//sets it to the c2f opinion
				$opinions[$character_id_1][$character_id_2]['opinion_word'] = $c2f_opinions[$character_id_1][$faction_id_2]['opinion_word'];
				$opinions[$character_id_1][$character_id_2]['opinion_text'] = $c2f_opinions[$character_id_1][$faction_id_2]['opinion_text'];
			}
		} else {
			//sets it to the c2c opinion
			$opinions[$character_id_1][$character_id_2]['opinion_word'] = $c2c_opinions[$character_id_1][$character_id_2]['opinion_word'];
			$opinions[$character_id_1][$character_id_2]['opinion_text'] = $c2c_opinions[$character_id_1][$character_id_2]['opinion_text'];
		}
		$character_num_2++;
	}
	$character_num_1++;
}
//print_r($opinions);
//exit();


$sql = "SELECT `faction_id`,`faction_name`,`faction_num`, `deleted` FROM `factions` WHERE `project_id` = '$project_id' ORDER BY `faction_id`";
$result = mysql_query($sql);
$num=mysql_numrows($result);  //counts the rows
$i=0;
while ($i < $num) {
	$var = $i+1;
	$faction_data['faction_id_' . $var] = mysql_result($result,$i);  //id of faction based on number
	$faction_data['faction_name_' . $var] = mysql_result($result,$i,1);  //name of faction
	$faction_data['faction_num_' . $var . '_deleted'] = mysql_result($result,$i,3); //deleted or not
	$i++;
}
		// $faction_data['faction_id_' . $number]
		// $faction_data['faction_name_' . $number]
		// $faction_data['faction_num_' . $number]
		// $faction_data['faction_num_' . $number . '_deleted']

$faction_num=1;
while ($faction_num<=12) {  //loops for each character sheet, 144 times
	if ($faction_data['faction_num_' . $faction_num . '_deleted'] == false){
		$character_num = 1;
		while ($character_num<=12) {
			$character_name = $character_data[$faction_num . "_" . $character_num]['character_name'];
			$player_name = $character_data[$faction_num . "_" . $character_num]['player_name'];
			$character_id = $character_data[$faction_num . "_" . $character_num]['character_id'];
			
			// Self Opinion
			if (isset($opinions[$character_id][$character_id]['opinion_text'])) {
				$self_opinion_text = $opinions[$character_id][$character_id]['opinion_text'];
			} else {
				$self_opinion_text = "No character details set.";
			}
			
			// Print the header & player details & history
			$pdf->cell(45,10,'Character: ' . $character_name,0,0,'L');
			$pdf->cell(45,10,'Player: ' . $player_name,0,1,'L');
			$pdf->multicell(90,4,$self_opinion_text ,0,2);
			$pdf->Ln();
			//  Loops again for each character within each character sheet
			$faction_num_data=1;
			while ($faction_num_data<=12) {
				if ($faction_data['faction_num_' . $faction_num_data . '_deleted'] == false){
					$faction_name = $faction_data['faction_name_' . $faction_num_data];  //Print Faction Name
					$pdf->cell(45,4,$faction_name,0,1);
					
					// Print 12 names
					$character_num_name=1;
					while ($character_num_name<=12) {
						$character_name = $character_data[$faction_num_data . "_" . $character_num_name]['character_name'];
						$pdf->cell(21.5,4,$character_name,1,0);
						$character_num_name++;
					}

					$pdf->Ln();
					
					// Print 12 opinion words
					$character_num_opinion=1;
					while ($character_num_opinion<=12) {
						$receiver_num = ((($faction_num_data-1)*12) + $character_num_opinion-1);
						$receiver_id = $character_id_list[$receiver_num]['character_id'];
						$character_opinion_word = $opinions[$character_id][$receiver_id]['opinion_word'];
						
						$pdf->cell(21.5,4,$character_opinion_word,1,0);
						$character_num_opinion++;
					}

					$pdf->Ln();
					
					//Print 12 opinion texts
					$character_num_text=1;
					while ($character_num_text<=12) {
						$receiver_num = ((($faction_num_data-1)*12) + $character_num_text-1);
						$receiver_id = $character_id_list[$receiver_num]['character_id'];
						$character_opinion_word = $opinions[$character_id][$receiver_id]['opinion_word'];
						$character_opinion_text = $opinions[$character_id][$receiver_id]['opinion_text'];
						$character_name = $character_data[$faction_num_data . "_" . $character_num_text]['character_name'];
						$pdf->cell(23,4,$character_name . ' - ' . $character_opinion_word . ' - ' . $character_opinion_text,0,1);
						$character_num_text++;
					}
					$pdf->Ln();
					$pdf->Ln();
				}
				$faction_num_data++;
			}
			
			//jump to next page for the next character
			$pdf->AddPage();
			$character_num++;
		}
	}
	$faction_num++;
}

$pdf->Output();
?>