<?php
session_start(); // đăng ký phiên trên server
ob_start();
// error_reporting(0);// hiện tất cả lỗi php
// ini_set('display_errors', 'off');// bật hiện lỗi
// error_reporting(E_ALL);
// ini_set('display_errors', 1);
date_default_timezone_set("Asia/Ho_Chi_Minh"); // giờ Việt Nam
header("Content-type: text/html; charset=utf-8"); // set định dạng utf-8

// Auth
function isLogin()
{
    return isset($_SESSION['LOGIN']) && $_SESSION['LOGIN'] == "OK" ? true : false;
}

// Database
function db_connect()
{
    $db_host = "localhost";
    $db_user = "root";
    $db_pass = '';
    $db_name = "autoit_check_key";


    global $conn;
    if (!$conn) {
        $conn = mysqli_connect($db_host, $db_user, $db_pass, $db_name) or die("Database Connection  Failed!");
        mysqli_set_charset($conn, "utf8");
    }
}

function db_close()
{
    global $conn;
    if ($conn) {
        mysqli_close($conn);
    }
}

function db_escape($string)
{
    db_connect();
    global $conn;
    $escape = mysqli_real_escape_string($conn, $string);
    return $escape;
}

function db_list($sql)
{
    db_connect();
    global $conn;
    $data  = array();
    $result = mysqli_query($conn, $sql);
    while ($row = mysqli_fetch_assoc($result)) {
        $data[] = $row;
    }
    mysqli_free_result($result);
    return $data;
}

function db_row($sql)
{
    db_connect();
    global $conn;
    $result = mysqli_query($conn, $sql);
    $row = array();
    if (mysqli_num_rows($result) > 0) {
        $row = mysqli_fetch_assoc($result);
    }
    mysqli_free_result($result);
    return $row;
}

function db_insert($table, $data = array())
{
    db_connect();
    global $conn;
    $field_list = '';
    $value_list = '';
    foreach ($data as $key => $value) {
        $field_list .= ",$key";
        $value_list .= ",'" . mysqli_escape_string($conn, $value) . "'";
    }
    $field_list = trim($field_list, ',');
    $value_list = trim($value_list, ',');
    // echo "INSERT INTO $table ($field_list) VALUES ($value_list)";
    return mysqli_query($conn, "INSERT INTO $table ($field_list) VALUES ($value_list)");
}

function db_update($table, $data, $where)
{
    db_connect();
    global $conn;
    $sql = '';
    foreach ($data as $field => $value) {
        $sql .= "$field='" . mysqli_escape_string($conn, $value) . "',";
    }
    $sql = trim($sql, ',');
    $sql = "UPDATE $table SET $sql WHERE $where";
    return mysqli_query($conn, $sql);
}

function db_query($sql)
{
    db_connect();
    global $conn;
    return mysqli_query($conn, $sql);
}

function db_insert_id()
{
    db_connect();
    global $conn;
    return mysqli_insert_id($conn);
}
