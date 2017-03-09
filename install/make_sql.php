<?php
  
  $argv = $_SERVER['argv'];
  $argc = $_SERVER['argc'];
  if($argc < 2) { echo 'Where is parameter, genius? First parameter should be name of translation.'; exit;}

  // pt_bliv
  $tr_name = $argv[1];
  if (stripos($tr_name, '_vpl') === FALSE)
  {
    $tr_name .= '_vpl';
  }

  $lines = file('./' . $tr_name . '/' . $tr_name . '.txt');
  $file = fopen('./' . $tr_name . '/' . $tr_name . '.sql', 'w');

  fwrite($file, 'USE sofia;
DROP TABLE IF EXISTS sofia.' . $tr_name . ';
CREATE TABLE ' . $tr_name . ' (
  verseID VARCHAR(16) NOT NULL PRIMARY KEY,
  canon_order VARCHAR(12) NOT NULL,
  book VARCHAR(3) NOT NULL,
  chapter VARCHAR(3) NOT NULL,
  startVerse VARCHAR(3) NOT NULL,
  endVerse VARCHAR(3) NOT NULL,
  verseText TEXT CHARACTER SET UTF8 NOT NULL) ENGINE=MyISAM;
LOCK TABLES ' . $tr_name . ' WRITE;' . PHP_EOL);
  $replace = $arrayName = array(  /*OT*/ 'GEN' => 'GN', 'EXO' => 'EX', 'LEV' => 'LV', 'NUM' => 'NU', 'DEU' => 'DT',
                                'JOS' => 'JS', 'JDG' => 'JG', 'RUT' => 'RT', '1SA' => 'S1', '2SA' => 'S2', 
                                '1KI' => 'K1', '2KI' => 'K2', '1CH' => 'R1', '2CH' => 'R2', 'EZR' => 'ER', 
                                'NEH' => 'NH', 'EST' => 'ET', 'JOB' => 'JB', 'PSA' => 'PS', 'PRO' => 'PR', 
                                'ECC' => 'EC', 'SNG' => 'SS', 'SOL' => 'SS', 'ISA' => 'IS', 'JER' => 'JR', 'LAM' => 'LM', 
                                'EZE' => 'EK', 'EZK' => 'EK','DAN' => 'DN', 'HOS' => 'HS', 'JOL' => 'JL',
                                'JOE' => 'JL', 'AMO' => 'AM', 
                                'OBA' => 'OB', 'JON' => 'JH', 'MIC' => 'MC', 'NAH' => 'NM','NAM' => 'NM', 'HAB' => 'HK', 
                                'ZEP' => 'ZP', 'HAG' => 'HG', 'ZEC' => 'ZC', 'MAL' => 'ML',
                                /*NT*/ 'MAT' => 'MT', 
                                'MRK' => 'MK', 'MAR' => 'MK', 'LUK' => 'LK', 'JHN' => 'JN', 'JOH' => 'JN','ACT' => 'AC', 'ROM' => 'RM', 
                                '1CO' => 'C1', '2CO' => 'C2', 'GAL' => 'GL', 'EPH' => 'EP', 'PHP' => 'PP', 'PHI' => 'PP',
                                'COL' => 'CL', '1TH' => 'H1', '2TH' => 'H2', '1TI' => 'T1', '2TI' => 'T2',
                                'TIT' => 'TT', 'PHM' => 'PM', 'HEB' => 'HB', 'JAS' => 'JM', 'JAM' => 'JM',
                                '1PE' => 'P1',
                                '2PE' => 'P2','1JN' => 'J1', '1JO' => 'J1','2JN' => 'J2', '2JO' => 'J2',
                                '3JO' => 'J3',
                                'JUD' => 'JD','REV' => 'RV'
                                );
$canon_order_num = 1;
$canon_order = '';
$current_book = '';
//$counter = 0;
  foreach ($lines as $line)
  {
    $line = trim($line);
    //$counter++;
    //echo 'Line ' . $counter . PHP_EOL;
    $pos = strpos($line, ' ');
    $book = ltrim(substr($line, 0, $pos));
    if (strpos($book, 'GEN') !== FALSE) $book = 'GEN';
    if($current_book !== $book) 
    {
      $current_book = $book;
      $canon_order_num++;
      if ($canon_order_num < 10)
        $canon_order = '00' . $canon_order_num;
      else
        $canon_order = '0' . $canon_order_num;
    }
    $line[$pos] = '[';
    $pos = strpos($line, '[');
    $chapter = substr($line, $pos+1, strpos($line, ':') - $pos - 1);
    $pos = strpos($line, ':');
    $pos_sp = strpos($line, ' ');
    $verse_num = substr($line, $pos+1, $pos_sp - $pos - 1);
    $verse = substr($line, $pos_sp + 1);
    // Book code in verseID do not equals to book code from vpl file
    fwrite($file, 'INSERT INTO ' . $tr_name . ' VALUES ("'.$replace[$book].$chapter.'_'. $verse_num .'","' . $canon_order . '_'.$chapter.'_'.$verse_num.'","' . $book . '","'.$chapter.'","'.$verse_num.'","'.$verse_num.'","'.rtrim($verse).'");' . PHP_EOL);
  }
  fwrite($file, 'ALTER TABLE ' . $tr_name . ' ADD FULLTEXT(verseText); ' . PHP_EOL . 'UNLOCK TABLES;');
  fclose($file);
?>