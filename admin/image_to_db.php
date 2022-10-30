<?
First you create a MySQL table to store images, like for example:

create table testblob (
    image_id        tinyint(3)  not null default '0',
    image_type      varchar(25) not null default '',
    image           blob        not null,
    image_size      varchar(25) not null default '',
    image_ctgy      varchar(25) not null default '',
    image_name      varchar(50) not null default ''
);



Then you can write an image to the database like:

$imgData = file_get_contents($filename);
$size = getimagesize($filename);
mysqli_connect("localhost", "$username", "$password");
mysqli_select_db ("$dbname");
$sql = sprintf("INSERT INTO testblob
    (image_type, image, image_size, image_name)
    VALUES
    ('%s', '%s', '%d', '%s')",
    mysqli_real_escape_string($size['mime']),
    mysqli_real_escape_string($imgData),
    $size[3],
    mysqli_real_escape_string($_FILES['userfile']['name'])
    );
mysqli_query($sql);



You can display an image from the database in a web page with:

$link = mysqli_connect("localhost", "username", "password");
mysqli_select_db("testblob");
$sql = "SELECT image FROM testblob WHERE image_id=0";
$result = mysqli_query("$sql");
header("Content-type: image/jpeg");
echo mysqli_result($result, 0);
mysqli_close($link);
?>
