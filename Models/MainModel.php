<?php 


if ($ajaxRequest) {
    require_once "../Config/SERVER.php";
} else {
    require_once "./Config/SERVER.php";
}

namespace Models;

use PDO;

/**
 * Clase base de la cuál heredan todos los modelos de la aplicación.
 * 
 * @package Models
 */
class MainModel {

                
    /**
     * Obtiene la conexión a la base de datos.
     * 
     * @return PDO Conexíon a la base de datos. 
     */
    protected static function getDBConnection() {
        $cnn = new PDO(SGBD, USER, PASS);
        $cnn->exec("SET CHARACTER SET utf8");

        return $cnn;
    }

    /**
     * Ejecuta una consulta a la base de datos.
     * 
     * @param string $query  Consulta.
     * @return PDOStatement Resultado de la consulta. 
     */
    protected static function executeSimpleQuery($query) {
        $sql = self::getDBConnection()->prepare($query);
        $sql->execute();

        return $sql;
    }

    /**
     * Permite encriptar cadenas de texto.
     * 
     * @param string $text Cadena de texto a encriptar.
     * @return string Cadena de texto encriptada.
     */
    public function encryption($text) {
        $output=FALSE;
        $key=hash('sha256', SECRET_KEY);
        $iv=substr(hash('sha256', SECRET_IV), 0, 16);
        $output=openssl_encrypt($text, METHOD, $key, 0, $iv);
        $output=base64_encode($output);
        
        return $output;
    }
    
    /**
     * Desecripta la cadena de texto dada.
     * 
     * @param string $text Cadena de texto a desencriptar.
     * @return string Cadena de texto desencriptada.
     */
    protected static function decryption($text){
        $key=hash('sha256', SECRET_KEY);
        $iv=substr(hash('sha256', SECRET_IV), 0, 16);
        $output=openssl_decrypt(base64_decode($text), METHOD, $key, 0, $iv);
        return $output;
    }

    /**
     * Genera códigos aleatorios para ser utilizados como Ids en los modelos.
     * 
     * @param string $letter Letra con la que comenzará el número aleatorio.
     * @param int $size Tamaño del número aleatorio.
     * @param int $number Número con el que finalizará el número aleatorio.
     * @return string Número aleatorio.
     */
    protected static function generateRandomCode($letter, $size, $number) {
        for ($i = 1; $i <= $size; $i++) {
            $random = rand(0, 9);
            $letter .= $random;
        }

        return $letter . "-" . $number;
    }

    /**
     * Limpia las cadenas de texto que provienen de los formularios para evitar ataques conocidos como
     * la iyeccíon SQL entre otros.
     * 
     * @param string $text Cadena a limpiar.
     * @return string Cadena limpia de datos inválidos.
     */
    protected static function clearString($text) {
        $text = trim($text);
        $text = stripslashes($text);
        $text = str_ireplace("<script>", "", $text);
        $text = str_ireplace("</script>", "", $text);
        $text = str_ireplace("<script src", "", $text);
        $text = str_ireplace("<script type=", "", $text);
        $text = str_ireplace("SELECT * FROM", "", $text);
        $text = str_ireplace("DELETE FROM", "", $text);
        $text = str_ireplace("INSERT INTO", "", $text);
        $text = str_ireplace("DROP TABLE", "", $text);
        $text = str_ireplace("DROP DATABASE", "", $text);
        $text = str_ireplace("TRUNCATE TABLE", "", $text);
        $text = str_ireplace("SHOW TABLES", "", $text);
        $text = str_ireplace("SHOW DATABASES", "", $text);
        $text = str_ireplace("<?php", "", $text);
        $text = str_ireplace("?>", "", $text);
        $text = str_ireplace("--", "", $text);
        $text = str_ireplace(">", "", $text);
        $text = str_ireplace("<", "", $text);
        $text = str_ireplace("[", "", $text);
        $text = str_ireplace("]", "", $text);
        $text = str_ireplace("^", "", $text);
        $text = str_ireplace("==", "", $text);
        $text = str_ireplace(";", "", $text);
        $text = str_ireplace("::", "", $text);
        $text = stripslashes($text);
        $text = trim($text);

        return $text;
    }

    /**
     * Verifica que los datos proporcionados sean válidos según la expresión regular dada.
     * 
     * @param string $filter Expresión regular a utilizar como filtro.
     * @param string $text Cadena de texto a verificar.
     * @return true|false
     */
    protected static function verifyData($filter, $text) {
        /* if (preg_match("/^".$filter."$/" ,$text)) {
            return false;
        } else {
            return true;
        } */

        return preg_match("/^".$filter."$/", $text) ? false : true;
    }

    /**
     * Verifica que la fecha dada sea válida.
     * 
     * @param string $value Fecha a validar.
     * @return true|false
     */
    protected static function verifyDate($value) {
        $values = explode('-', $value);
        /*
        if (count($values) == 3 && checkdate($values[1], $values[2], $values[0])) {
            return false;
        } else {
            return true;
        } */

        return count($values) == 3 && checkdate($values[1], $values[2], $values[0]) ? false : true;
    }

    /**
     * Paginador de tablas.
     * 
     * @param int $page Página actual.
     * @param int $pageNumber Número de páginas.
     * @param string $url URL de la página.
     * @param int $buttons Cantidad de botones que se desean mostrar.
     * @return string Cadena que representa el HTML del paginador.
     */
    protected static function tablePaginator($page, $pageNumber, $url, $buttons) {
        $table = '<nav aria-label="Page navigation example">
                    <ul class="pagination justify-content-center">';

        if ($page == 1) {
            $table .= '<li class="page-item disabled"><a class="page-link"><i class="fas fa-angle-double-left"></i></a></li>';
        } else {
            $table .= '
                <li class="page-item"><a class="page-link" href="'.$url.'1/"><i class="fas fa-angle-double-left"></i></a></li>
                <li class="page-item"><a class="page-link" href="'.$url.($page - 1).'/">Anterior</a></li>
            ';
        }

        $ci = 0;
        for ($i = $page; $i <= $pageNumber; $i++) {
            if ($ci >= $buttons) {
                break;
            }

            if ($page == $i) {
                $table .= '<li class="page-item"><a class="page-link active" href="'.$url.$i.'/">'.$i.'</a></li>';
            } else {
                $table .= '<li class="page-item"><a class="page-link" href="'.$url.$i.'/">'.$i.'</a></li>';
            }

            $ci++;
        }

        if ($page == $pageNumber) {
            $table .= '<li class="page-item disabled"><a class="page-link"><i class="fas fa-angle-double-right"></i></a></li>';
        } else {
            $table .= '
                <li class="page-item"><a class="page-link" href="'.$url.($page + 1).'/">Siguiente</a></li>
                <li class="page-item"><a class="page-link" href="'.$url.$pageNumber.'/"><i class="fas fa-angle-double-right"></i></a></li>
            ';
        }

        $table .= '</ul></nav>';

        return $table;
    }
}