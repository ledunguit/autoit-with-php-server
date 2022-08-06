<?php
require 'sql_dm.php';

if (isset($_GET['version'])) {
    $version = $_GET['version'];
    $initVersion = [
        'linkNew' => 'https://google.com',
        'version' => '1.0.0'
    ];

    echo json_encode($initVersion);
}

if (isset($_GET['deleteKeys'])) {
    $key = $_GET['deleteKeys'];

    db_query('DELETE FROM `keys` WHERE id = ' . $key);

    header('location: index.php');
}

if (isset($_GET['addKeys'])) {
    $key = $_POST['key'];
    $name = $_POST['name'];
    $times = $_POST['times'];

    $sql = db_query('select * from `keys` where `key` = "' . $key . '"');
    if (mysqli_num_rows($sql) > 0) {
        echo json_encode(['status' => 'error', 'message' => 'Đã tồn tại key này']);
    } else {
        db_insert('`keys`', [
            '`key`' => $key,
            '`name`' => $name,
            '`times`' => $times,
        ]);

        echo json_encode(['status' => 'success', 'message' => 'Thành công']);
    }
}

if (isset($_GET['editKeys'])) {
    $id = $_POST['id'];
    $name = $_POST['name'];
    $hiwd = $_POST['hiwd'];

    $sql = db_query('select * from `keys` where `id` = ' . $id);
    if (mysqli_num_rows($sql) <= 0) {
        echo json_encode(['status' => 'error', 'message' => 'Không tồn tại key này']);
    } else {
        $arrUpdate = [];
        $arrUpdate['`name`'] = $name;
        $arrUpdate['`hiwd`'] = $hiwd;

        db_update('`keys`', $arrUpdate, '`id` = "' . $id . '"');

        echo json_encode(['status' => 'success', 'message' => 'Thành công']);
    }
}

if (isset($_POST['requestTool'])) {
    // chống bị sql inject
    $key = db_escape($_POST['key']);
    $hiwd = db_escape($_POST['hiwd']);

    $checkKey = db_row('select * from `keys` where `key` = "' . $key . '"');
    if (count($checkKey) > 0) {
        // key status = N thì đã hết hạn trước đó rồi
        if ($checkKey['status'] == "N") {
            echo json_encode([
                'status' => 'error',
                'message' => 'Key đã hết hạn'
            ]);
            die();
        }

        // lấy ngày hiện tại
        $currenDate = date('Y-m-d');

        // lấy ngày hết hạn
        $expireKey = $checkKey['expire'];

        // lần đầu sử dụng key
        $is_first = false;
        if ($checkKey['hiwd'] == "") {
            $is_first = true;
            $expireKey = date('Y-m-d', strtotime($currenDate . ' + ' . $checkKey['times'] . ' days'));
            db_update('`keys`', ['`hiwd`' => $hiwd, '`status`' => 'Y', '`expire`' => $expireKey], '`id` = "' . $checkKey['id'] . '"');
        }

        // kiểm tra xem hiwd máy vs csdl có trùng không hoạcw nếu là lần đầu tiên
        if ($hiwd == $checkKey['hiwd'] || $is_first == true) {
            // kiểm tra ngày hiện tại vs ngày hết hạn của key
            if ($currenDate > $expireKey) {
                db_update('`keys`', ['`status`' => 'N'], '`id` = "' . $checkKey['id'] . '"');
                echo json_encode([
                    'status' => 'error',
                    'message' => 'Key đã hết hạn'
                ]);
                die();
            }

            echo json_encode([
                'status' => 'success',
                'message' => 'Đăng nhập thành công',
                'data' => [
                    'name' => $checkKey['name'],
                    'expire' => $expireKey,
                ]
            ]);
            die();
        } else {
            // lỗi key đã sử dụng trên máy tính khác
            echo json_encode([
                'status' => 'error',
                'message' => 'Key này đã được sử dụng trên máy tính khác'
            ]);
            die();
        }
    } else {
        // không tồn tại key này
        echo json_encode([
            'status' => 'error',
            'message' => 'Không tồn tại'
        ]);
    }
}
