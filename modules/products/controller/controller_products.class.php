<?php

//include  with absolute route
include ($_SERVER['DOCUMENT_ROOT'] . "/products_ORM_pagination_autocomplete/modules/products/utils/functions_products.inc.php");
include ($_SERVER['DOCUMENT_ROOT'] . "/products_ORM_pagination_autocomplete/utils/upload.php");
include ($_SERVER['DOCUMENT_ROOT'] . "/products_ORM_pagination_autocomplete/utils/common.inc.php");
$path = $_SERVER['DOCUMENT_ROOT'] . '/products_ORM_pagination_autocomplete/';
define('SITE_ROOT', $path);

session_start();

$_SESSION['module'] = "products";

	if ((isset($_POST['discharge_products_json']))) {

	  	discharge_products();
	}

	function discharge_products() {//Ahora que se que funciona dropzone implemento la funcion completa de cargar los productos
	  	$jsondata = array();
			$productsJSON = json_decode($_POST["discharge_products_json"], true);

		$jsondata["redirect4"]=$productsJSON['pais'];

	//	$jsondata["success"]=true;
	  //echo json_encode($jsondata);
		//exit();

	  	$result = validate_products($productsJSON);

	//	$result['resultado']=true;

    	if (empty($_SESSION['result_avatar'])) {
        $_SESSION['result_avatar'] = array('resultado' => true, 'error' => "", 'datos' => 'media/default-avatar.png');
    	}

    	$result_avatar = $_SESSION['result_avatar'];

	   if (($result['resultado']) && ($result_avatar['resultado'])) {
        $arrArgument = array(
            'name' => ucfirst($result['datos']['name']),
            'code' => ($result['datos']['code']),
           'origin' => $result['datos']['origin'],
            'provider' => $result['datos']['provider'],
            'email' => $result['datos']['email'],
            'price' => $result['datos']['price'],
            'description' => ucfirst($result['datos']['description']),
            'material' => $result['datos']['material'],
            'type' => ($result['datos']['type']), //strtoupper > para convertir string a mayusculas
            'shape' => ($result['datos']['shape']),
            'brand' => ($result['datos']['brand']),
            'stock' => $result['datos']['stock'],
            'date_reception' => $result['datos']['date_reception'],
            'departure_date' => $result['datos']['departure_date'],
						'pais' => $result['datos']['pais'],
						'provincia' => $result['datos']['provincia'],
						'poblacion' => $result['datos']['poblacion'],
        	  'avatar' => $result_avatar['datos']

        );

				/////////////////insert into BD////////////////////////
        $arrValue = false;
        $path_model = $_SERVER['DOCUMENT_ROOT'] . '/products_ORM_pagination_autocomplete/modules/products/model/model/';
        $arrValue = loadModel($path_model, "products_model", "create_products", $arrArgument);
      //  echo json_encode($arrArgument);
        //exit();

        if ($arrValue)
            $mensaje = "User has been successfully registered";
        else
            $mensaje = "No se ha podido realizar su alta. Intentelo mas tarde";




        //redirigir a otra p�gina con los datos de $arrArgument y $mensaje
        $_SESSION['products'] = $arrArgument;
        $_SESSION['msje'] = $mensaje;
        $callback = "index.php?module=products&view=results_products";
        $jsondata["redirect1"]= $result['datos'];
        $jsondata["success"] = true;
        $jsondata["redirect"] = $callback;
        //$jsondata["redirect1"] =  $_SESSION['products']['avatar'];
        //$jsondata["redirect1"] =  $result['datos']['name'];
        echo json_encode($jsondata);
        exit;
        //redirect($callback);
    } else {

    	$jsondata["success"] = false;
        $jsondata["error"] = $result['error'];
        $jsondata["error_avatar"] = $result_avatar['error'];

       $jsondata["success1"] = false;
        if ($result_avatar['resultado']) {
            $jsondata["success1"] = true;
            $jsondata["img_avatar"] = $result_avatar['datos'];
        }
        header('HTTP/1.0 400 Bad error');
        echo json_encode($jsondata);
        //exit;

    }

	}


//////////////////////////
if (isset($_GET["delete"]) && $_GET["delete"] == true) {
    $_SESSION['result_avatar'] = array();
    $result = remove_files();
    if ($result === true) {
        echo json_encode(array("res" => true));
    } else {
       echo json_encode(array("res" => false));
    }
}


////////////////////////////
if ((isset($_GET["upload"])) && ($_GET["upload"] == true)) {
		$result_avatar = upload_files();
		$_SESSION['result_avatar'] = $result_avatar;
		//echo json_encode($result_avatar);
	//	exit();
}
///////////////////////////
if (isset($_GET["load"]) && $_GET["load"] == true) {
    $jsondata = array();
    if (isset($_SESSION['products'])) {
        //echo debug($_SESSION['products']);
        $jsondata["products"] = $_SESSION['products'];
    }
    if (isset($_SESSION['msje'])) {
        //echo $_SESSION['msje'];
        $jsondata["msje"] = $_SESSION['msje'];
    }
    close_session();
    echo json_encode($jsondata);
    //exit;
}

function close_session() {
    unset($_SESSION['products']);
    unset($_SESSION['msje']);
    $_SESSION = array(); // Destruye todas las variables de la sesión
    session_destroy(); // Destruye la sesión
}



/////////////////////////////////////////////////// load_data
if ((isset($_GET["load_data"])) && ($_GET["load_data"] == true)) {
    $jsondata = array();

    if (isset($_SESSION['products'])) {
        $jsondata["products"] = $_SESSION['products'];
        echo json_encode($jsondata);
        exit;
    } else {
        $jsondata["products"] = "";
        echo json_encode($jsondata);
        exit;
    }
}

/////////////////////////////////////////////////// load_pais
if(  (isset($_GET["load_pais"])) && ($_GET["load_pais"] == true)  ){
	$json = array();

		$url = 'http://www.oorsprong.org/websamples.countryinfo/CountryInfoService.wso/ListOfCountryNamesByName/JSON';

	$path_model=$_SERVER['DOCUMENT_ROOT'].'/products_ORM_pagination_autocomplete/modules/products/model/model/';
	$json = loadModel($path_model, "products_model", "obtain_paises", $url);

			if(stristr($json,'error')){
					$json = "error";
					exit;
			if($json){
					echo $json;
					exit;
			}else{
					$json = "error";
					echo $json;
					exit;
						}
			}
	}

/////////////////////////////////////////////////// load_provincias
if(  (isset($_GET["load_provincias"])) && ($_GET["load_provincias"] == true)  ){
	$jsondata = array();
			$json = array();

	$path_model=$_SERVER['DOCUMENT_ROOT'].'/products_ORM_pagination_autocomplete/modules/products/model/model/';
	$json = loadModel($path_model, "products_model", "obtain_provincias");

	if($json){
		$jsondata["provincias"] = $json;
		echo json_encode($jsondata);
		exit;
	}else{
		$jsondata["provincias"] = "error";
		echo json_encode($jsondata);
		exit;
	}
}

/////////////////////////////////////////////////// load_poblaciones
if(  isset($_POST['idPoblac']) ){
		$jsondata = array();
			$json = array();

	$path_model=$_SERVER['DOCUMENT_ROOT'].'/products_ORM_pagination_autocomplete/modules/products/model/model/';
	$json = loadModel($path_model, "products_model", "obtain_poblaciones", $_POST['idPoblac']);

	if($json){
		$jsondata["poblaciones"] = $json;
		echo json_encode($jsondata);
		exit;
	}else{
		$jsondata["poblaciones"] = "error";
		echo json_encode($jsondata);
		exit;
	}
}

/*
/////////////////////////////
if ($_GET["idProduct"]) {
    $id = $_GET["idProduct"];
    $path_model = SITE_ROOT . '/modules/products/model/model/';
    $arrValue = loadModel($path_model, "products_model", "details_products",$id);

    if ($arrValue[0]) {
        loadView('modules/products/view/', 'details_products.php', $arrValue[0]);
    } else {
        $message = "NOT FOUND PRODUCT";
        loadView('view/inc/', '404.php', $message);
    }
} else {

    $path_model = SITE_ROOT . '/modules/products/model/model/';
    $arrValue = loadModel($path_model, "products_model", "list_products");

		if ($arrValue) {
			  loadView('modules/products/view/', 'list_products.php', $arrValue);
    } else {
        $message = "NOT PRODUCTS";
        loadView('view/inc/', '404.php', $message);
    }
}
*/
