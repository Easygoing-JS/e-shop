<?php    spl_autoload_register(function ($class) {        include("c/$class.php");    });    session_start();    $action = 'action_';    // $action .= (isset($_GET['act'])) ? $_GET['act'] : 'index';    $action .= (isset($_GET['act'])) ? $_GET['act'] : 'catalog';    if (isset($_GET['id'])) {        $id = $_GET['id'];    } else {        $id = false;    }    switch ($_GET['c']) {        case 'page':            $controller = new C_Page();            break;        case 'catalog':            $controller = new C_Catalog();            break;        case 'cart':            $controller = new C_Cart();            break;        case 'user':            $controller = new C_User();            break;        case 'admin':            $controller = new C_Admin();            break;        default:            $controller = new C_Catalog();    }    $controller->Request($action, $id);    // TODO: ������\������ �������� �������    // * ������� *    echo '<br>var_dump($_SESSION[user]):<br>';    var_dump($_SESSION['user']);    echo '<br><br>($_SESSION):<br>';    print_r($_SESSION);    echo '<br><br>($_POST):<br>';    print_r($_POST);