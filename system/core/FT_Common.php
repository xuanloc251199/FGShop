<?php 
if (!defined('PATH_SYSTEM')) die('Bad requested');

// Include FT_Config_Loader để sử dụng class
require_once PATH_SYSTEM . '/core/loader/FT_Config_Loader.php';

/**
 * Function run program
 */
function FT_load() {
    // Tạo đối tượng của FT_Config_Loader
    $configLoader = new FT_Config_Loader();

    // Nạp file cấu hình (ví dụ: 'app' nếu có config trong /config/app.php)
    $configLoader->load('config');

    // Lấy cấu hình cho controller và action mặc định
    $defaultController = $configLoader->item('default_controller', 'home'); // Sử dụng phương thức item() để lấy giá trị
    $defaultAction = $configLoader->item('default_action', 'index'); // Sử dụng phương thức item() để lấy giá trị

    // Nếu không truyền controller thì lấy controller mặc định
    $controller = empty($_REQUEST['controller']) ? $defaultController : $_REQUEST['controller'];

    // Nếu không truyền action thì lấy action mặc định
    $action = empty($_REQUEST['action']) ? $defaultAction : $_REQUEST['action'];

    // Chuyển đổi tên controller theo định dạng {Name}_Controller
    $controller = ucfirst($controller) . '_Controller';

    // Chuyển đổi tên action theo định dạng {name}Action
    $action = strtolower($action);

    // Kiểm tra file controller có tồn tại không
    if (!file_exists(PATH_APPLICATION . '/controller/' . $controller . '.php')) {
        die('Not found controller');
    }

    // Bao gồm controller chính cho các controller con
    include_once PATH_SYSTEM . '/core/FT_Controller.php';

    // Nạp Base_Controller nếu tồn tại
    if (file_exists(PATH_APPLICATION . '/core/Base_Controller.php')) {
        include_once PATH_APPLICATION . '/core/Base_Controller.php';
    }

    // Gọi file controller
    require_once PATH_APPLICATION . '/controller/' . $controller . '.php';

    // Kiểm tra class controller có tồn tại không?
    if (!class_exists($controller)) {
        die('Not found controller');
    }

    // Tạo đối tượng controller
    $controllerObject = new $controller();

    // Kiểm tra action có tồn tại trong controller không?
    if (!method_exists($controllerObject, $action)) {
        die('Not found action');
    }

    // Chạy chương trình
    $controllerObject->{$action}();
}
?>
