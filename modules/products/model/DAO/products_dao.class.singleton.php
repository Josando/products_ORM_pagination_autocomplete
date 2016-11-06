<?php


//echo json_encode("12dd");
//  exit();
class productsDAO {

    static $_instance;

    private function __construct() {

    }

    public static function getInstance() {
        if (!(self::$_instance instanceof self))
            self::$_instance = new self();
        return self::$_instance;
    }

    public function create_products_DAO($db, $arrArgument) {

        $name = $arrArgument['name'];
        $code = $arrArgument['code'];
        $origin = $arrArgument['origin'];
        $provider = $arrArgument['provider'];
        $email = $arrArgument['email'];
        $price = $arrArgument['price'];
        $description = $arrArgument['description'];
        $material = $arrArgument['material'];
        $type = $arrArgument['type'];
        $shape = $arrArgument['shape'];
        $brand = $arrArgument['brand'];
        $stock = $arrArgument['stock'];
        $date_reception = $arrArgument['date_reception'];
        $depurate_date = $arrArgument['departure_date'];
        $avatar = $arrArgument['avatar'];
        $provincia = $arrArgument['provincia'];
        $pais = $arrArgument['pais'];
        $poblacion = $arrArgument['poblacion'];

        $carbon = 0;
        $fiberglass = 0;
        $graphinte= 0;
        $grafeno = 0;

        foreach ($material as $indice) {
            if ($indice === 'Carbon')
                $carbon = 1;
            if ($indice === 'Fiberglass')
                $fiberglass = 1;
            if ($indice === 'Graphite')
                $graphinte = 1;
            if ($indice === 'Grafeno')
                $grafeno = 1;
        }

        $sql = "INSERT INTO products (Products_name, Code, Origin, Provider,"
                . " Email, Price, Description, Carbon, Fiberglass, Graphinte, Grafeno, Stock, Date_reception, Depurate_date, Type, Shovel, Brand, Avatar, Pais, Provincia, Ciudad"
                . " ) VALUES ('$name', '$code', '$origin',"
                . " '$provider', '$email', '$price', '$description', '$carbon', '$fiberglass', '$graphinte', '$grafeno', '$stock', '$date_reception', '$depurate_date', '$type', '$shape', '$brand', '$avatar', '$pais', '$provincia', '$poblacion')";

        return $db->ejecutar($sql);

    }

    public function obtain_paises_DAO($url) {
              $ch = curl_init();
              curl_setopt ($ch, CURLOPT_URL, $url);
              curl_setopt ($ch, CURLOPT_RETURNTRANSFER, 1);
              curl_setopt ($ch, CURLOPT_CONNECTTIMEOUT, $timeout);
              $file_contents = curl_exec($ch);
              curl_close($ch);

              return ($file_contents) ? $file_contents : FALSE;
          }

          public function obtain_provincias_DAO() {
              $json = array();
  		    $tmp = array();

      		$provincias = simplexml_load_file($_SERVER['DOCUMENT_ROOT'] . '/resources/provinciasypoblaciones.xml');
      		$result = $provincias->xpath("/lista/provincia/nombre | /lista/provincia/@id");
      		for ($i=0; $i<count($result); $i+=2) {
      			$e=$i+1;
      			$provincia=$result[$e];

      			$tmp = array(
      				'id' => (string) $result[$i], 'nombre' => (string) $provincia
      			);
      			array_push($json, $tmp);
      		}
              return $json;
          }

          public function obtain_poblaciones_DAO($arrArgument) {
              $json = array();
  		    $tmp = array();

              $filter = (string)$arrArgument;
      	    $xml = simplexml_load_file($_SERVER['DOCUMENT_ROOT'] . '/resources/provinciasypoblaciones.xml');
  		    $result = $xml->xpath("/lista[nombre='$filter']/localidades");

          	for ($i=0; $i<count($result[0]); $i++) {
          		$tmp = array(
          			'poblacion' => (string) $result[0]->localidad[$i]
          		);
          		array_push($json, $tmp);
          	}
              return $json;
          }

          public function list_products_DAO($db) {
            //echo json_encode($db);
            //exit;
            $sql = "SELECT * FROM products";
            $stmt = $db->ejecutar($sql);
            return $db->listar($stmt);

          }

          public function details_products_DAO($db,$id) {
            $sql = "SELECT * FROM products WHERE Code="."'$id"."'";
            $stmt = $db->ejecutar($sql);
            return $db->listar($stmt);

          }

}
