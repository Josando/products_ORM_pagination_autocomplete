<?php
//include  with absolute route
$path = $_SERVER['DOCUMENT_ROOT'] . '/products_ORM_pagination_autocomplete/';
include($path . "modules/products_FE/utils/utils.inc.php");
define('SITE_ROOT', $path);
include $path . 'paths.php';
include $path . 'classes/Log.class.singleton.php';
include $path . 'utils/common.inc.php';
include $path . 'utils/filter.inc.php';
include $path . 'utils/response_code.inc.php';

$_SESSION['module'] = "products_FE";


if ((isset($_GET["autocomplete"])) && ($_GET["autocomplete"] === "true")) {
    set_error_handler('ErrorHandler');
    $model_path = SITE_ROOT . 'modules/products_FE/model/model/';
    try {

        $nameProducts = loadModel($model_path, "products_model", "select_column_products", "Products_name");
    } catch (Exception $e) {
        showErrorPage(2, "ERROR - 503 BD", 'HTTP/1.0 503 Service Unavailable', 503);
    }
    restore_error_handler();

    if ($nameProducts) {
        $jsondata["nom_productos"] = $nameProducts;
        echo json_encode($jsondata);
        exit;
    } else {

        showErrorPage(2, "ERROR - 404 NO DATA", 'HTTP/1.0 404 Not Found', 404);
    }
}


if (($_GET["nom_product"])) {
    //filtrar $_GET["nom_product"]

    $result = filter_string($_GET["nom_product"]);
    if ($result['resultado']) {
        $criteria = $result['datos'];
    } else {
        $criteria = '';
    }
    $model_path = SITE_ROOT . 'modules/products_FE/model/model/';
    set_error_handler('ErrorHandler');
    try {

        $arrArgument = array(
            "column" => "Products_name",
            "like" => $criteria
        );
        $producto = loadModel($model_path, "products_model", "select_like_products", $arrArgument);


        //throw new Exception(); //que entre en el catch
    } catch (Exception $e) {
        showErrorPage(2, "ERROR - 503 BD", 'HTTP/1.0 503 Service Unavailable', 503);
    }
    restore_error_handler();

    if ($producto) {
        $jsondata["product_autocomplete"] = $producto;
        echo json_encode($jsondata);
        exit;
    } else {

        showErrorPage(2, "ERROR - 404 NO DATA", 'HTTP/1.0 404 Not Found', 404);
    }
}

if (($_GET["count_product"])) {
    //filtrar $_GET["count_product"]
    $result = filter_string($_GET["count_product"]);
    if ($result['resultado']) {
       $criteria = $result['datos'];
      //  $jsondata["criteria"] = $criteria;
      //  echo json_encode($jsondata);

    } else {
        $criteria = 'jorge';
  //    $jsondata["criteria"] = "else";
    //  echo json_encode($jsondata);

    }
    $model_path = SITE_ROOT . 'modules/products_FE/model/model/';
    set_error_handler('ErrorHandler');
    try {

        $arrArgument = array(
            "column" => "Products_name",
            "like" => $criteria
        );

      //  $jsondata["colums"]=$arrArgument['column'];
      //  $jsondata["like"]=$arrArgument['like'];

        $total_rows = loadModel($model_path, "products_model", "count_like_products", $arrArgument);
        //throw new Exception(); //que entre en el catch
    } catch (Exception $e) {
        showErrorPage(2, "ERROR - 503 BD", 'HTTP/1.0 503 Service Unavailable', 503);
    }
    restore_error_handler();

    if ($total_rows) {
        $jsondata["num_products"] = $total_rows[0]["total"];
        echo json_encode($jsondata);
        exit;
    } else {
        //if($total_rows){ //que lance error si no existe el producto
        showErrorPage(2, "ERROR - 404 NO DATA", 'HTTP/1.0 404 Not Found', 404);
    }
}




//obtain num total pages
if ((isset($_GET["num_pages"])) && ($_GET["num_pages"] === "true")) {

  if (isset($_GET["keyword"])) {
        $result = filter_string($_GET["keyword"]);
        if ($result['resultado']) {
            $criteria = $result['datos'];
        } else {
            $criteria = '';
        }
    } else {
        $criteria = '';
    }

    $item_per_page = 6;
    $path_model = SITE_ROOT . '/modules/products_FE/model/model/';

    //change work error apache
    set_error_handler('ErrorHandler');

    try {

      //loadmodel
      $arrArgument = array(
          "column" => "Products_name",
          "like" => $criteria
      );

      $resultado = loadModel($path_model, "products_model", "count_like_products", $arrArgument);


        $resultado = $resultado[0]["total"];
        $pages = ceil($resultado / $item_per_page); //break total records into pages

        //throw new Exception();
      //  $arrValue = loadModel($path_model, "products_model", "total_products");
    //    $get_total_rows = $arrValue[0]["total"]; //total records
      //  $pages = ceil($get_total_rows / $item_per_page); //break total records into pages
    } catch (Exception $e) {
        showErrorPage(2, "ERROR - 503 BD", 'HTTP/1.0 503 Service Unavailable', 503);
    }

    //change to defualt work error apache
    restore_error_handler();

    if ($resultado) {
        $jsondata["pages"] = $pages;
        echo json_encode($jsondata);
        exit;
    } else {
        showErrorPage(2, "ERROR - 404 NO DATA", 'HTTP/1.0 404 Not Found', 404);
    }

}


if ((isset($_GET["view_error"])) && ($_GET["view_error"] === "true")) {
    showErrorPage(0, "ERROR - 503 BD Unavailable");
}
if ((isset($_GET["view_error"])) && ($_GET["view_error"] === "false")) {
    showErrorPage(3, "RESULTS NOT FOUND <br> Please, check over if you misspelled any letter of the search word");
}




if (isset($_GET["idProduct"])) {
    $arrValue = null;
    $id=$_GET["idProduct"];
    //filter if idProduct is a number
    /*
    $result = filter_num_int($_GET["idProduct"]);
    if ($result['resultado']) {
        $id = $result['datos'];
    } else {
        $id = 1;
    }
*/
    set_error_handler('ErrorHandler');
    try {
        //throw new Exception();
        $path_model = SITE_ROOT . '/modules/products_FE/model/model/';
        $arrValue = loadModel($path_model, "products_model", "details_products", $id);
    } catch (Exception $e) {
        showErrorPage(2, "ERROR - 503 BD", 'HTTP/1.0 503 Service Unavailable', 503);
    }
    restore_error_handler();

    if ($arrValue) {
        $jsondata["product"] = $arrValue[0];
	echo json_encode($jsondata, JSON_UNESCAPED_UNICODE);
        exit;
    } else {
        showErrorPage(2, "ERROR - 404 NO DATA", 'HTTP/1.0 404 Not Found', 404);
    }
} else {

    $item_per_page = 6;

    //filter to $_POST["page_num"]
    if (isset($_POST["page_num"])) {
        $result = filter_num_int($_POST["page_num"]);
        if ($result['resultado']) {
            $page_number = $result['datos'];
        }
    } else {
        $page_number = 1;
    }

    if (isset($_GET["keyword"])) {
        $result = filter_string($_GET["keyword"]);
        if ($result['resultado']) {
            $criteria = $result['datos'];
        } else {
            $criteria = '';
        }
    } else {
        $criteria = '';
    }

    if (isset($_POST["keyword"])) {
        $result = filter_string($_POST["keyword"]);
        if ($result['resultado']) {
            $criteria = $result['datos'];
        } else {
            $criteria = '';
        }
    }

    set_error_handler('ErrorHandler');
    try {
        //throw new Exception();
        $position = (($page_number - 1) * $item_per_page);

        $arrArgument = array(
            'column' => "Products_name",
            'like' => $criteria,
            'position' => $position,
            'item_per_page' => $item_per_page
        );

        $path_model = SITE_ROOT . '/modules/products_FE/model/model/';
        $arrValue = loadModel($path_model, "products_model", "select_like_limit_products", $arrArgument);
    } catch (Exception $e) {
        showErrorPage(0, "ERROR - 503 BD Unavailable");
    }
    restore_error_handler();

    if ($arrValue) {
        paint_template_products($arrValue);
    } else {
        showErrorPage(0, "ERROR - 404 NO PRODUCTS");
    }
}
