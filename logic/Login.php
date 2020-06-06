<?php
    require_once('../include/MrManager.php');

    require_once('../persistence/MySQL.php');

    require_once('../object/CURL.php');
    require_once('../object/Token.php');
    require_once('../object/User.php');
    
    if ($_SERVER['REQUEST_METHOD'] !== 'POST') {
        // 405 method not allowed
        http_response_code(405);
        die();
    }

    if (!isset($_POST['id']) || !isset($_POST['password'])) {
        // 400 bad request
        http_response_code(400);
        die();
    }

    $manager = new MrManager();
    $config = null;

    try {
        $config = $manager->get_config();
    } catch (MrManagerInvalidConfig $e) {
        // 500 internal server error
        http_response_code(500);
        die();
    }

    //FIXME: this doesnt work, also 400 might be better suited vs 403
    if (!is_numeric($_POST['id']) || !strlen($_POST['id']) === 7) {
        // 403 forbidden
        http_response_code(403);
        die();
    }

    $tmp_file_path = $manager->generate_tmp_file();

    $main_url = $config['general']['main_url'];

    $curl = new CURL($main_url, $tmp_file_path);

    $curl->get_page('/banprod/twbkwbis.P_WWWLogin');

    $post_params = array('sid' => $_POST['id'], 'PIN' => $_POST['password']);
    $curl->set_post($post_params);

    $response = $curl->get_page('/banprod/twbkwbis.P_ValLogin');
    $response_size = $curl->get_downloaded_size();
    
    $new_token = null;
    $sql = null;
    try {
        $sql = new MySQL($config);

        if ($response_size < 500) {
            $user = $sql->get_user($_POST['id']);
            if ($user === false) {
                error_log('create new user', 4);
                $sql->insert_new_user($_POST['id']);
            } else {
                error_log('update user', 4);
                $sql->update_user_last_login($_POST['id'], time());
            }

            $file_name_split = explode('/', $tmp_file_path);

            $new_token = new Token();
            $new_token->generate_token();
            $new_token->set_tmp_file_name(end($file_name_split));
            $new_token->set_expires(time());

            $token = $sql->get_token($_POST['id']);
            if ($token !== false) {
                //TODO: un-hardcode this
                unlink('../tmp/' . $token->get_tmp_file_name());
                $sql->delete_token($_POST['id']);
            }

            $sql->insert_new_token($_POST['id'], $new_token);
        } else {
            //TODO: un-hardcode this
            unlink($tmp_file_path);

            // 200 but json error?
            http_response_code(200);
            die('bad login');
        }
    } catch (PDOException $e) {
        // 500 internal server error
        http_response_code(500);
        die();
    }

    http_response_code(200);
    die($new_token->get_token());
?>