<?php
        if (!isset($_SERVER['REMOTE_ADDR']))
        {
                $_SERVER['REMOTE_ADDR'] = '127.0.0.1';
                $argv = $_SERVER['argv'];
                $argc = $_SERVER['argc'];
                if ($argc > 1)
                        $_REQUEST['dir'] =  $argv[1];
        }


        require '../config.php';
        /*
                make_title.php
                script requires InScript or "_html" Bibles from eBible.org
                they must be unpacked in directory with the script
                and their names must be of languages in which they are written
                e.g. `en`, `english`, `English` etc

                the script needs only one file from InScript Bible index.html
                ["index.htm" for "_html"]
                which contains titles of a Bible
                e.g.
                `<li><a href='RT.html' class='oo'>Ruth</a></li>`

        */

        $abbr = 
        [
        /*OT*/ 'GEN' => 'GN', 'EXO' => 'EX', 'LEV' => 'LV', 'NUM' => 'NU', 'DEU' => 'DT',
        'JOS' => 'JS', 'JDG' => 'JG', 'RUT' => 'RT', '1SA' => 'S1', '2SA' => 'S2', 
        '1KI' => 'K1', '2KI' => 'K2', '1CH' => 'R1', '2CH' => 'R2', 'EZR' => 'ER', 
        'NEH' => 'NH', 'EST' => 'ET', 'JOB' => 'JB', 'PSA' => 'PS', 'PRO' => 'PR', 
        'ECC' => 'EC', 'SNG' => 'SS', 'SOL' => 'SS', 'ISA' => 'IS', 'JER' => 'JR', 'LAM' => 'LM', 
        'EZE' => 'EK', 'EZK' => 'EK','DAN' => 'DN', 'HOS' => 'HS', 'JOL' => 'JL',
        'JOE' => 'JL', 'AMO' => 'AM', 
        'OBA' => 'OB', 'JON' => 'JH', 'MIC' => 'MC', 'NAH' => 'NM','NAM' => 'NM', 'HAB' => 'HK', 
        'ZEP' => 'ZP', 'HAG' => 'HG', 'ZEC' => 'ZC', 'MAL' => 'ML',
        /*AP*/
        'TOB' => 'TB', 'JDT' => 'JT', 'WIS' => 'WS', 'SIR' => 'SR', 'BAR' => 'BR', 
        '1MA' => 'M1', '2MA' => 'M2', '1ES' => 'E1', 'MAN' => 'PN', '3MA' => 'M3',
        '2ES' => 'E2', '4MA' => 'M4', 'DAG' => 'DG', 'PS2' => 'PX',
        /*NT*/ 'MAT' => 'MT', 
        'MRK' => 'MK', 'MAR' => 'MK', 'LUK' => 'LK', 'JHN' => 'JN', 'JOH' => 'JN','ACT' => 'AC', 'ROM' => 'RM', 
        '1CO' => 'C1', '2CO' => 'C2', 'GAL' => 'GL', 'EPH' => 'EP', 'PHP' => 'PP', 'PHI' => 'PP',
        'COL' => 'CL', '1TH' => 'H1', '2TH' => 'H2', '1TI' => 'T1', '2TI' => 'T2',
        'TIT' => 'TT', 'PHM' => 'PM', 'HEB' => 'HB', 'JAS' => 'JM', 'JAM' => 'JM',
        '1PE' => 'P1',
        '2PE' => 'P2','1JN' => 'J1', '1JO' => 'J1','2JN' => 'J2', '2JO' => 'J2',
        '3JO' => 'J3', '3JN' => 'J3',
        'JUD' => 'JD','REV' => 'RV'
        ];

        $str_eval = '$abbrs = [';
        foreach ($abbr as $key => $value) 
        {
                $str_eval .= "'$value' => '$key', ";
        }

        $synonyms = " SOL - SNG , JOL - JOE , MRK - MAR , JOH - JHN , PHP - PHI , JAM - JAS , 1JO - 1JN , 2JO - 2JN , 3JO - 3JN ";

        eval($str_eval . '];');
        //print_r($abbrs);
        $dh = opendir('./');
        //mysqli_query($mysql, 'delete from book_titles');

        function insert_titles($file)
        {
                global $abbrs;
                global $pdo;
                global $mysql;
                global $synonyms;

                if (is_dir($file) and ($file !== '.') and ($file !== '..') 
                        and file_exists('./' . $file . '/index.html')
                        and file_exists('./' . $file . '/inscript.txt')
                        )
                {
                        // found a directory with InScript
                        $language = $file;
                        $language = preg_replace('/[0-9]/', '', $language);
                        $language_statement = $pdo -> prepare('select language_code from iso_639_1_languages where language_name = :language 
                                                                union select language_code from iso_639_1_languages where language_code = :language
                                                                union select language_code from iso_639_1_languages where native_name = :language');
                        $result = $language_statement -> execute(['language' => trim($language)]);
                        if (!$result)
                        {
                                echo json_encode($language_statement) . '<br />';
                                echo  json_encode($language_statement -> errorInfo()) . '<br />';
                        }
                        $language_row = $language_statement -> fetch();
                        $language_code = $language_row['language_code'];
                        echo '<br /><h3>' . $file . ': ' . $language_code . '</h3><br />';
                        $indexhtml = file('./' . $file . '/index.html');

                        $insert_query = 'insert into book_titles (language_code, book, shorttitle) values ';
                        $counter = 0;
                        foreach ($indexhtml as $line) 
                        {
                                if ((stripos($line, '<li>') !== FALSE ) 
                                        and (stripos($line, 'about') === FALSE )
                                        and (stripos($line, 'preface') === FALSE )
                                        and (stripos($line, 'glossary') === FALSE ))
                                {
                                        $book = explode("'", $line);
                                        $book = $book[1];
                                        $book = str_ireplace('.html', '', $book);
                                        if (strlen($book) === 2)
                                                $book = $abbrs[$book];
                                        $name = trim(strip_tags($line));
                                        echo $book . ': ' . $name . '<br />';
                                        $counter++;
                                        $insert_query .= "('{$language_code}', '{$book}', '$name'),";
                                        if (stripos($synonyms, $book) !== FALSE)
                                        {
                                                $synonyms_ar = explode(',', $synonyms);
                                                foreach ($synonyms_ar as $item) 
                                                {
                                                        if (stripos($item, $book) !== FALSE)
                                                        {
                                                                $item_ar = explode('-', $item);
                                                                foreach ($item_ar as &$value)
                                                                {
                                                                        $value = trim($value);
                                                                }
                                                                if ($item_ar[0] == $book)
                                                                {
                                                                        $insert_query .= "('{$language_code}', '{$item_ar[1]}', '$name'),";
                                                                }
                                                                elseif ($item_ar[1] == $book)
                                                                {
                                                                        $insert_query .= "('{$language_code}', '{$item_ar[0]}', '$name'),";
                                                                }
                                                        }

                                                }
                                        }
                                }
                        }
                        echo 'Total Count: ' . $counter . '<br />';
                        $insert_query[strlen($insert_query)-1] = ';';
                        $result = mysqli_query($mysql, $insert_query);
                        if ($result)
                                echo '<font color="green">Rows with titles are inserted</font><br/>';
                        else
                        {
                                echo '<font color="red">Query to database has some issues</font><br/>';
                                echo mysqli_error($mysql) . '<br />';
                        }
                }
                elseif (is_dir($file) and ($file !== '.') and ($file !== '..') 
                        and file_exists('./' . $file . '/index.htm')
                        and file_exists('./' . $file . '/html.txt')
                        )
                {
                        // found a directory with "_html"
                        $language = $file;
                        $language = preg_replace('/[0-9]/', '', $language);
                        $language_statement = $pdo -> prepare('select language_code from iso_639_1_languages where language_name = :language 
                                                                union select language_code from iso_639_1_languages where language_code = :language
                                                                union select language_code from iso_639_1_languages where native_name = :language');
                        $result = $language_statement -> execute(['language' => trim($language)]);
                        if (!$result)
                        {
                                echo json_encode($language_statement) . '<br />';
                                echo  json_encode($language_statement -> errorInfo()) . '<br />';
                        }
                        $language_row = $language_statement -> fetch();
                        $language_code = $language_row['language_code'];
                        echo '<br /><h3>' . $file . ': ' . $language_code . '</h3><br />';
                        $indexhtml = file('./' . $file . '/index.htm');

                        $insert_query = 'insert into book_titles (language_code, book, shorttitle) values ';
                        $counter = 0;
                        foreach ($indexhtml as $line) 
                        {
                                if ((stripos($line, '<li>') !== FALSE ) 
                                        and (stripos($line, 'about') === FALSE )
                                        and (stripos($line, 'preface') === FALSE )
                                        and (stripos($line, 'glossary') === FALSE )
                                        and (stripos($line, 'Greek') === FALSE )
                                        and (stripos($line, 'copyright') === FALSE ))
                                {
                                        $book = explode("'", $line);
                                        $book = $book[3];
                                        $book = str_ireplace('.htm', '', $book);
                                        $book = preg_replace('/[0-9]{2,3}$/', '', $book);
                                        $name = trim(strip_tags($line));
                                        echo $book . ': ' . $name . '<br />';
                                        $insert_query .= "('{$language_code}', '{$book}', '$name'),";
                                        $counter++;
                                }
                        }
                        echo 'Total Count: ' . $counter . '<br />';
                        $insert_query[strlen($insert_query)-1] = ';';
                        $result = mysqli_query($mysql, $insert_query);
                        if ($result)
                                echo '<font color="green">Rows with titles are inserted</font><br/>';
                        else
                        {
                                echo '<font color="red">Query to database has some issues</font><br/>';
                                echo mysqli_error($mysql) . '<br />';
                        }
                }
        }

        if (isset($_REQUEST['dir']))
                insert_titles($_REQUEST['dir']);
        else
        while ($file = readdir($dh))
        {
                insert_titles($file);
        }
?>