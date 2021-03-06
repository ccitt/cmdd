﻿<?php
/**
 * Generate specifies the html format data dictionary for the mysql database
 * Licensed ( http://www.apache.org/licenses/LICENSE-2.0 )
 * Author: james.zhang <ccitt@tom.com>
 * Version: 0.1a
 */

//The configuration needs to generate information about the data dictionary database connection
define('DB_HOST', '127.0.0.1');
define('DB_PORT', 3306);
define('DB_USER', 'Fill in the database username');
define('DB_PWD', 'Fill in the database password');
define('DATABASE_NAME', 'Fill in the database name');
define('DB_CHARSET', 'utf8');

try {
    $html = '';
    $table_ordinal=1;

    $dbc= new PDO('mysql:host='.DB_HOST.';port='.DB_PORT.';dbname='.DATABASE_NAME.';charset='.DB_CHARSET, DB_USER, DB_PWD);
    //Get all the table names and comments in the database
    $table_sql = 'SELECT TABLE_NAME,TABLE_COMMENT FROM INFORMATION_SCHEMA.TABLES WHERE table_schema = "'.DATABASE_NAME.'"';
    foreach ($dbc->query($table_sql, PDO::FETCH_ASSOC) as $tables) {
        $html .= '	<h3>' . $table_ordinal . '、' .$tables['TABLE_NAME'].'  （'.$tables['TABLE_COMMENT']. '）</h3>'."\n";
        $html .= '	<table border="1" cellspacing="0" cellpadding="0" width="100%">'."\n";
        $html .= '		<tbody>'."\n";
        $html .= '			<tr>'."\n";
        $html .= '				<th>字段名</th>'."\n";
        $html .= '				<th>数据类型</th>'."\n";
        $html .= '				<th>默认值</th>'."\n";
        $html .= '				<th>是否为空</th>'."\n";
        $html .= '				<th>是否自增</th>'."\n";
        $html .= '				<th>注释</th>'."\n";
        $html .= '			</tr>'."\n";

   		//Generate each table data dictionary
        $field_sql = 'SELECT * FROM INFORMATION_SCHEMA.COLUMNS WHERE table_name = "'.$tables['TABLE_NAME'].'" AND table_schema = "'.DATABASE_NAME.'"';
        foreach ($dbc->query($field_sql, PDO::FETCH_ASSOC) as $field) {
            $html .= '			<tr>'."\n";
            $html .= '				<td class="c1">' . $field['COLUMN_NAME'] . '</td>'."\n";
            $html .= '				<td class="c2">' . $field['COLUMN_TYPE'] . '</td>'."\n";
            $html .= '				<td class="c3">' . $field['COLUMN_DEFAULT'] . '</td>'."\n";
            $html .= '				<td class="c4">' . $field['IS_NULLABLE'] . '</td>'."\n";
            $html .= '				<td class="c5">' . ($field['EXTRA']=='auto_increment'?'YES':'NO') . '</td>'."\n";
            $html .= '				<td class="c6">' . $field['COLUMN_COMMENT'] . '</td>'."\n";
            $html .= '			</tr>'."\n";
        }
        $html .= '		</tbody>'."\n";
        $html .= '	</table>'."\n<br/>";

        $table_ordinal++;    
    }
     $dbc=null;
} catch (PDOException $e) {
    exit ("Database connect Error : " . $e->getMessage() . "<br/>");
}
?>
<!doctype html>
<html>
<head>
<meta charset="utf-8" />
<title><?php echo DATABASE_NAME; ?>数据库数据字典</title>
<style>
body, td, th { font-family: "微软雅黑"; font-size: 14px; }
.warp{margin:auto; width:1000px;}
.warp h3{margin:0px; padding:0px; line-height:30px; margin-top:10px;}
table { border-collapse: collapse; border: 1px solid #d0fb74; background: #A7DE49; }
table th { text-align: left; font-weight: bold; height: 26px; line-height: 26px; font-size: 14px; text-align:center; border: 1px solid #d0fb74; padding:5px;}
table td { height: 20px; font-size: 14px; border: 1px solid #d0fb74; background-color: #fff; padding:5px;}
.c1 { width: 150px; }
.c2 { width: 120px; }
.c3 { width: 150px; }
.c4 { width: 100px; text-align:center;}
.c5 { width: 100px; text-align:center;}
.c6 { width: 300px; }
</style>
</head>
<body>
<div class="warp">
    <h1 style="text-align:center;"><?php echo DATABASE_NAME; ?>数据库数据字典</h1>
<?php echo $html; ?>
</div>
</body>
</html>