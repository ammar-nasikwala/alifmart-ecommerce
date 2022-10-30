<?php

require("../lib/inc_connection.php");

die("ok 44");

mysql_query("delete from event_registration");

die("ok 33");

mysql_query("alter table event_registration add reg_board varchar(20)");
mysql_query("alter table event_registration add reg_pass_percent float(2,2)");
mysql_query("alter table event_registration add reg_stream varchar(20)");

mysql_query("delete from event_mast where event_id in (1,2)");

die("ok 22");

mysql_query("alter table event_registration add reg_paid INT default 0");

die("ok 11");

$qry = "create table event_registration (";
$qry .= " reg_id INT AUTO_INCREMENT PRIMARY KEY,";
$qry .= " reg_date date,";
$qry .= " reg_event_id integer,";
$qry .= " reg_ej_id integer,";
$qry .= " reg_full_name varchar(100),";
$qry .= " reg_contact varchar(50),";
$qry .= " reg_email varchar(100),";
$qry .= " reg_address varchar(1000)";
$qry .= " )";

mysql_query($qry);


die("ok");

mysql_query("drop table event_mast");

$qry = "create table event_mast (";
$qry .= " event_id INT AUTO_INCREMENT PRIMARY KEY,";
$qry .= " event_name varchar(100),";
$qry .= " event_date date,";
$qry .= " event_time varchar(10),";
$qry .= " event_location varchar(50),";
$qry .= " event_topics varchar(500),";
$qry .= " event_presenter varchar(100),";
$qry .= " event_fees varchar(10),";
$qry .= " event_details varchar(1000),";
$qry .= " event_closed integer)";


mysql_query($qry);



mysql_query("drop table configuration");

$qry = "create table configuration";
$qry .= " (admin_name varchar(20),";
$qry .= " admin_pwd varchar(20),";
$qry .= " admin_email varchar(100),";
$qry .= " smtp_server varchar(100))";

mysql_query($qry);


mysql_query("insert into configuration (admin_name,admin_pwd) values ('a','a')");



die("done");
?>

