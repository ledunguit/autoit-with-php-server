<?php
require 'sql_dm.php';

if (isset($_POST['username'], $_POST['password'])) {
    $u = $_POST['username'];
    $p = $_POST['password'];

    if ($u == "admin" && $p == "admin") {
        $_SESSION['LOGIN'] = 'OK';
        header("Refresh:0");
    }
}

if (isLogin() == false) {
    include 'login.php';
    die();
}

$db_keys = db_list('SELECT * FROM `keys` ORDER BY id DESC');

?>
<!DOCTYPE html>
<html lang="en">

<head>

    <meta charset="utf-8">
    <meta http-equiv="X-UA-Compatible" content="IE=edge">
    <meta name="viewport" content="width=device-width, initial-scale=1, shrink-to-fit=no">
    <meta name="description" content="">
    <meta name="author" content="">

    <title>SB Admin 2 - Tables</title>

    <!-- Custom fonts for this template -->
    <link href="vendor/fontawesome-free/css/all.min.css" rel="stylesheet" type="text/css">
    <link href="https://fonts.googleapis.com/css?family=Nunito:200,200i,300,300i,400,400i,600,600i,700,700i,800,800i,900,900i" rel="stylesheet">

    <!-- Custom styles for this template -->
    <link href="css/sb-admin-2.min.css" rel="stylesheet">

    <!-- Custom styles for this page -->
    <link href="vendor/datatables/dataTables.bootstrap4.min.css" rel="stylesheet">

    <script>
        var actionPost = '';
    </script>

</head>

<body id="page-top">
    <div id="wrapper">
        <div id="content-wrapper" class="d-flex flex-column">
            <div id="content">
                <nav class="navbar navbar-expand navbar-light bg-white topbar mb-4 static-top shadow">
                    <ul class="navbar-nav ml-auto">
                        <li class="nav-item dropdown no-arrow">
                            <a class="nav-link dropdown-toggle" href="#" id="userDropdown" role="button" data-toggle="dropdown" aria-haspopup="true" aria-expanded="false">
                                <span class="mr-2 d-none d-lg-inline text-gray-600 small">Admin</span>
                                <img class="img-profile rounded-circle" src="img/undraw_profile.svg">
                            </a>
                            <div class="dropdown-menu dropdown-menu-right shadow animated--grow-in" aria-labelledby="userDropdown">
                                <a class="dropdown-item" href="#" data-toggle="modal" data-target="#logoutModal">
                                    <i class="fas fa-sign-out-alt fa-sm fa-fw mr-2 text-gray-400"></i>
                                    Logout
                                </a>
                            </div>
                        </li>
                    </ul>
                </nav>

                <div class="container-fluid">
                    <h1 class="h3 mb-2 text-gray-800">Keys</h1>
                    <div class="card shadow mb-4">
                        <div class="card-header py-3">
                            <h6 class="m-0 font-weight-bold text-primary">Danh sách key <button onclick="addKeys()" class="btn btn-success">Thêm Key</button></h6>
                        </div>
                        <div class="card-body">
                            <div class="table-responsive">
                                <table class="table table-bordered">
                                    <thead>
                                        <tr>
                                            <th>#</th>
                                            <th>Key</th>
                                            <th>Tên</th>
                                            <th>Hiwd</th>
                                            <th>Số ngày</th>
                                            <th>Hết hạn</th>
                                            <th>Tình trạng</th>
                                        </tr>
                                    </thead>
                                    <tbody>
                                        <?php foreach ($db_keys as $key => $value) { ?>
                                            <tr>
                                                <td>
                                                    <button onclick="deleteKeys('<?= $value['id'] ?>')" class="btn btn-danger">Xóa</button>
                                                    <button onclick="editKeys('<?= $value['id'] ?>', '<?= $value['key'] ?>', '<?= $value['name'] ?>', '<?= $value['expire'] ?>', '<?= $value['hiwd'] ?>')" class="btn btn-primary">Sửa</button>
                                                </td>
                                                <td><?= $value['key'] ?></td>
                                                <td><?= $value['name'] ?></td>
                                                <td><?= $value['hiwd'] ?></td>
                                                <td><?= $value['times'] ?></td>
                                                <td><?= $value['expire'] ?></td>
                                                <td>
                                                    <?php
                                                    switch ($value['status']) {
                                                        case 'Y':
                                                            echo '<span class="badge badge-success">Đang sử dụng</span>';
                                                            break;
                                                        case 'N':
                                                            echo '<span class="badge badge-danger">Hết hạn</span>';
                                                            break;
                                                        case 'P':
                                                            echo '<span class="badge badge-warning">Chưa sử dụng</span>';
                                                            break;
                                                        default:
                                                            break;
                                                    }
                                                    ?>
                                                </td>
                                            </tr>
                                        <?php } ?>
                                    </tbody>
                                </table>
                            </div>
                        </div>
                    </div>
                </div>
            </div>

            <footer class="sticky-footer bg-white">
                <div class="container my-auto">
                    <div class="copyright text-center my-auto">
                        <span>FREE</span>
                    </div>
                </div>
            </footer>
        </div>
    </div>

    <a class="scroll-to-top rounded" href="#page-top">
        <i class="fas fa-angle-up"></i>
    </a>

    <div class="modal fade" id="modaIU" tabindex="-1" role="dialog" aria-hidden="true">
        <div class="modal-dialog" role="document">
            <div class="modal-content">
                <div class="modal-header">
                    <h5 class="modal-title" id="titleModal"></h5>
                    <button class="close" type="button" data-dismiss="modal" aria-label="Close">
                        <span aria-hidden="true">×</span>
                    </button>
                </div>
                <div class="modal-body">
                    <form>
                        <div class="form-group">
                            <input type="text" class="form-control" id="txtId" disabled placeholder="ID">
                        </div>
                        <div class="form-group">
                            <input type="text" class="form-control" id="txtKey" placeholder="Key">
                        </div>
                        <div class="form-group">
                            <input type="text" class="form-control" id="txtName" placeholder="Tên">
                        </div>
                        <div class="form-group">
                            <input type="text" class="form-control" id="txtTimes" placeholder="Số ngày">
                        </div>
                        <div class="form-group">
                            <input type="text" class="form-control" id="txtHiwd" placeholder="Hiwd">
                        </div>
                    </form>
                </div>
                <div class="modal-footer">
                    <button class="btn btn-primary" onclick="actionInsertAndUpdate()" type="button">OK</button>
                </div>
            </div>
        </div>
    </div>

    <script>
        function actionInsertAndUpdate() {
            let txtId = $("#txtId").val()
            let txtKey = $("#txtKey").val()
            let txtName = $("#txtName").val()
            let txtHiwd = $("#txtHiwd").val()
            let txtTimes = $("#txtTimes").val()

            $.ajax({
                type: "POST",
                url: "api.php?" + actionPost + "=true",
                data: {
                    'id': txtId,
                    'key': txtKey,
                    'name': txtName,
                    'hiwd': txtHiwd,
                    'times': txtTimes,
                },
                dataType: 'JSON',
                success: function(data) {
                    alert(data.message)
                    if (data.status == "success") {
                        $("#modaIU").modal('hide');
                        window.location.reload()
                    }
                }
            });
        }

        function addKeys() {
            $("#titleModal").html('Thêm key')
            $("#modaIU").modal('show');
            $("#txtId").hide();
            $("#txtKey").val('')
            $("#txtKey").prop('disabled', false);
            $("#txtName").val('')
            $("#txtTimes").val('1')
            $("#txtTimes").show()
            $("#txtHiwd").hide()
            $("#modaIU").modal('show');
            actionPost = "addKeys";
        }

        function editKeys(id, key, name, date, hiwd) {
            $("#titleModal").html('Sửa key');
            $("#txtId").show()
            $("#txtKey").show()
            $("#txtName").show()
            $("#txtHiwd").show()
            $("#txtTimes").hide()
            
            $("#txtKey").prop('disabled', true);
            $("#txtId").val(id)
            $("#txtKey").val(key)
            $("#txtName").val(name)
            $("#txtHiwd").val(hiwd)
            $("#modaIU").modal('show');
            actionPost = "editKeys";
        }

        function deleteKeys(id) {
            let confirmDelete = confirm('Bạn có chắc')
            if (confirmDelete) {
                window.location = "api.php?deleteKeys=" + id
            }
        }
    </script>

    <script src="vendor/jquery/jquery.min.js"></script>
    <script src="vendor/bootstrap/js/bootstrap.bundle.min.js"></script>

    <script src="vendor/jquery-easing/jquery.easing.min.js"></script>

    <script src="js/sb-admin-2.min.js"></script>

    <script src="vendor/datatables/jquery.dataTables.min.js"></script>
    <script src="vendor/datatables/dataTables.bootstrap4.min.js"></script>

    <script src="js/demo/datatables-demo.js"></script>

</body>

</html>