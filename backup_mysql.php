<?php

include_once 'inc/config.php';
echo 1;
$filename='database_backup_'.date('Y_m_d_H_i').'.sql';

$result=exec('mysqldump --user=geomatb_stat --password=mo75zzarella --single-transaction geomatb_stat > backups/'.$filename,$output);
//var_dump($output);
//var_dump($result);
//backup_tables($db);


/* backup the db OR just a table */

function backup_tables(PDO $db, $tables = '*') {

    //$link = mysql_connect($host,$user,$pass);
    //mysql_select_db($name,$link);
    //get all of the tables
    if ($tables == '*') {
        $tables = array();
        $stmt =$db->query('SHOW TABLES');
        while ($row = $stmt->fetch()) {
            $tables[] = $row[0];
        }
    } else {
        $tables = is_array($tables) ? $tables : explode(',', $tables);
    }

    //cycle through
    //var_dump($tables);
    foreach ($tables as $table) {
        $stmt = $db->query('SELECT * FROM `' . $table.'`');
        var_dump($table);
        $num_fields = $stmt->columnCount();

        $return.= 'DROP TABLE ' . $table . ';';
        $stmt2 = $db->query('SHOW CREATE TABLE `' . $table.'`');
        $stmt2->execute();
        $row2 = $stmt2->fetch();
        $return.= "\n\n" . $row2[1] . ";\n\n";

        for ($i = 0; $i < $num_fields; $i++) {
            while ($row = $stmt->fetch(PDO::FETCH_BOTH)) {
                
                $return.= 'INSERT INTO ' . $table . ' VALUES(';
                for ($j = 0; $j < $num_fields; $j++) {
                    $row[$j] = addslashes($row[$j]);
                    $row[$j] = preg_replace("\n", "\\n", $row[$j]);
                    if (isset($row[$j])) {
                        $return.= '"' . $row[$j] . '"';
                    } else {
                        $return.= '""';
                    }
                    if ($j < ($num_fields - 1)) {
                        $return.= ',';
                    }
                }
                $return.= ");\n";
            }
        }
        $return.="\n\n\n";
    }

    //save file
    $handle = fopen('db-backup-' . time() . '.sql', 'w+');
    var_dump($handle);
    fwrite($handle, $return);
    fclose($handle);
}
