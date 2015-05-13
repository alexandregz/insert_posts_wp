<?php
// borra artigos dumha categoria

$fraseologia_id = 14;

require_once('/home/anossagalaxia/www/wp-config.php');


$mysqli = new mysqli(DB_HOST, DB_USER, DB_PASSWORD, DB_NAME);

/* check connection */
if ($mysqli->connect_errno) {
    printf("Connect failed: %s\n", $mysqli->connect_error);
    exit();
}


$contador = 0;
$sql = "SELECT *
FROM wp_posts a
LEFT JOIN wp_term_relationships b ON ( a.ID = b.object_id )
LEFT JOIN wp_postmeta c ON ( a.ID = c.post_id )
LEFT JOIN wp_term_taxonomy d ON ( d.term_taxonomy_id = b.term_taxonomy_id )
LEFT JOIN wp_terms e ON ( e.term_id = d.term_id )
WHERE e.term_id = ".$fraseologia_id;

if ($result = $mysqli->query($sql)) {
    printf("Select returned %d rows.\n", $result->num_rows);

    while($obj = $result->fetch_object()){
	if($contador > 10) break;
        echo $obj->ID.": ".$obj->post_title."\n";
	$contador++;
    }

    $result->close();
}


$sql = "DELETE a
FROM wp_posts a
LEFT JOIN wp_term_relationships b ON ( a.ID = b.object_id )
LEFT JOIN wp_postmeta c ON ( a.ID = c.post_id )
LEFT JOIN wp_term_taxonomy d ON ( d.term_taxonomy_id = b.term_taxonomy_id )
LEFT JOIN wp_terms e ON ( e.term_id = d.term_id )
WHERE e.term_id = ".$fraseologia_id;

if ($mysqli->real_query($sql)) {
    printf("Deleted returned %d rows.\n", $mysqli->affected_rows);
}



$sql = "SELECT *
FROM wp_posts a
LEFT JOIN wp_term_relationships b ON ( a.ID = b.object_id )
LEFT JOIN wp_postmeta c ON ( a.ID = c.post_id )
LEFT JOIN wp_term_taxonomy d ON ( d.term_taxonomy_id = b.term_taxonomy_id )
LEFT JOIN wp_terms e ON ( e.term_id = d.term_id )
WHERE e.term_id = ".$fraseologia_id;

if ($result = $mysqli->query($sql)) {
    printf("Select returned %d rows.\n", $result->num_rows);

    while($obj = $result->fetch_object()){
        echo $obj->ID.": ".$obj->post_title."\n";
    }

    $result->close();
}

$mysqli->close();
