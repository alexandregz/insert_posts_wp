<?php
/**
 * Script para inxerir posts ao novo dicionario de Fraseologia da AGAL
 * Alexandre Espinosa Menor <aemenor@gmail.com>
 *
 * Usage: $ php -d memory_limit=1024M insert_posts_fraseoloxia.php |tee insert_posts.log
 *
 * Reference: http://codex.wordpress.org/Function_Reference/wp_insert_post
 *
 * Total (*2): cat *.txt | sed '/^\s*$/d'|wc -l
 */
ini_set('display_errors', true);
error_reporting(E_ALL);

require_once '/home/anossagalaxia/www/wp-load.php';

define("PATH_FILES", '/home/anossagalaxia/fraseologia_vogais');

// para meter varias categorias ha que empregar os IDs (Fraseologia e Recursos, neste casso)
$categorias = array(12, 14);

$user_id = 1;


//date_default_timezone_set('Europe/Paris');


$numero_post_insertar = 0;
if ($files = scandir(PATH_FILES)) {             // scandir para que vaiam ordeados alfabeticamente
    foreach($files as $file) {
        if (substr($file, -4) == '.txt' ) {
                $ficheiro_path = PATH_FILES.'/'.$file;
                echo "processando $ficheiro_path...\n";

                $ficheiro_texto = file_get_contents($ficheiro_path);

                $posts = getTitulosEContidos($ficheiro_texto);


                foreach($posts as $p) {
                        $numero_post_insertar++;
                        echo "Post a insertar $numero_post_insertar\n";
                        if(file_exists('./actual_post.php')) {
                                include './actual_post.php';
                                if($numero_post_insertar <= $ultimo_post_insertado) {
                                        echo "\t skipping, actual: $ultimo_post_insertado\n";
                                        continue;
                                }
                        }

                        // Create post
                        $id = wp_insert_post(array(
                            'post_title'    => $p['titulo'],
                            'post_content'  => $p['contido'],
                            //'post_date'     => date('Y-m-d H:i:s'),
                            'post_author'   => $user_id,
                            'post_type'     => 'post',
                            'post_status'   => 'publish',
                            'post_category' => $categorias,
                        ));

                        if(!$id) die("Erro ao insertar post [$title]\n");

                        $php_log_post_actual = '<?php
                        $ultimo_post_insertado = '.$numero_post_insertar.';';

                        file_put_contents('./actual_post.php', $php_log_post_actual); 
			echo "\t actual post: $numero_post_insertar \t title: ".$p['titulo']."\n";

                        ob_flush();
                }
        }
    }
}


// devolve array( array('titulo' => 'x1', 'contido' => 'z1'), array('titulo' => 'x2', 'contido' => 'z2'), ...)
function getTitulosEContidos($ficheiro_texto) {
        $definicions = array();

        $ficheiro_lineas = explode("\n", $ficheiro_texto);

        $titulo = '';
        $contador = 0;

        foreach($ficheiro_lineas as $l) {
                if(trim($l) == '') continue;

                $contador++;

                if(($contador % 2) == 0) {
                        $definicions[] = array(
                                'titulo' => $titulo,
                                'contido' => $l
                        );
                }
                else {
                        $titulo = $l;
                }
        }
        return $definicions;
}
