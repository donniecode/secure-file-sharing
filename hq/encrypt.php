<?php include "../includes/header.php"; ?>

    <div class="container-scroller">
    <!-- partial:partials/_sidebar.html -->
<?php include "../includes/sidebar.php";

if (isset($_GET['encrypt'])) {

    $request_id = $_GET['req_id'];
    $doc_en = $_GET['doc_en'];
    $encrypted = "encrypted";

    function encrypt_decrypt($string, $action = 'encrypt')
    {
        $encrypt_method = "AES-256-CBC";
        $secret_key = 'AA74CDCC2BBRT935136HH7B63C27'; // user define private key
        $secret_iv = '5fgf5HJ5g27'; // user define secret key
        $key = hash('sha256', $secret_key);
        $iv = substr(hash('sha256', $secret_iv), 0, 16); // sha256 is hash_hmac_algo
        if ($action == 'encrypt') {
            $output = openssl_encrypt($string, $encrypt_method, $key, 0, $iv);
            $output = base64_encode($output);
        } else if ($action == 'decrypt') {
            $output = openssl_decrypt(base64_decode($string), $encrypt_method, $key, 0, $iv);
        }
        return $output;
    }

    $aa = encrypt_decrypt($doc_en, 'encrypt');


//    echo "Your Encrypted password is = ". $pwd = encrypt_decrypt('spaceo', 'encrypt');
//    echo "Your Decrypted password is = ". encrypt_decrypt($pwd, 'decrypt');



    $query = "UPDATE documents SET ";
    $query .= "status  = '{$encrypted}', ";
    $query .= "doc_file  = '{$aa}' ";
    $query .= "WHERE doc_id = {$request_id} ";


    $update_query = mysqli_query($conn, $query);
    if (!$update_query) {
        die("Query failed" . mysqli_error($conn));
    }
};

?>



    <!-- partial -->
    <div class="container-fluid page-body-wrapper">
        <!-- partial:partials/_navbar.html -->
        <nav class="navbar p-0 fixed-top d-flex flex-row">
            <div class="navbar-brand-wrapper d-flex d-lg-none align-items-center justify-content-center">
                <a class="navbar-brand brand-logo-mini" href="home.php"><img src="../assets/images/logo-mini.svg" alt="logo" /></a>
            </div>
            <div class="navbar-menu-wrapper flex-grow d-flex align-items-stretch">
                <button class="navbar-toggler navbar-toggler align-self-center" type="button" data-toggle="minimize">
                    <span class="mdi mdi-menu"></span>
                </button>

                <ul class="navbar-nav navbar-nav-right">
                    <h1 class="text-center">Air Force of Zimbabwe</h1>
                    <li class="nav-item m-2">
                        <img src="../airflag.jpg" class="rounded-circle" width="50" height="50" alt="">
                    </li>

                </ul>
                <button class="navbar-toggler navbar-toggler-right d-lg-none align-self-center" type="button" data-toggle="offcanvas">
                    <span class="mdi mdi-format-line-spacing"></span>
                </button>
            </div>
        </nav>
        <!-- partial -->
        <div class="main-panel">
            <div class="content-wrapper">
                <div class="row">
                    <div class="col-12 grid-margin stretch-card">
                        <div class="card corona-gradient-card">
                            <div class="card-body py-0 px-0 px-sm-3">
                                <div class="row align-items-center">
                                    <div class="col-4 col-sm-3 col-xl-2">
                                        <img src="../assets/images/dashboard/Group126@2x.png" class="gradient-corona-img img-fluid" alt="">
                                    </div>
                                    <div class="col-5 col-sm-7 col-xl-8 p-0">
                                        <h1 class="mb-1 mb-sm-0 text-center">Classified Secure File Sharing</h1>
                                    </div>
                                    <div class="col-3 col-sm-2 col-xl-2 pl-0 text-center">
                        <span>
                             <img src="../assets/images/dashboard/Group126@2x.png" class="gradient-corona-img img-fluid" alt="">
                        </span>
                                    </div>
                                </div>
                            </div>
                        </div>
                    </div>
                </div>
                <div class="row">
                    <div class="col-lg-12 grid-margin stretch-card">
                        <div class="card">
                            <div class="card-body">
                                <h4 class="card-title">File Encryption</h4>
                                </p>
                                <div class="table-responsive">
                                    <table class="table">
                                        <thead>
                                        <tr>
                                            <th>DocNo.</th>
                                            <th>Title</th>
                                            <th>Content</th>
                                            <th>Status</th>
                                            <th></th>
                                        </tr>
                                        </thead>
                                        <tbody>

     <?php

$sql = "SELECT * FROM documents ORDER BY doc_id DESC";
$result = $conn->query($sql);

if ($result->num_rows > 0) {
    // output data of each row
    while($row = $result->fetch_assoc()) {

        $doc_id = $row["doc_id"];
        $doc_title = $row["doc_title"];
        $doc_file = $row["doc_file"];
        $status = $row["status"];
        $con_level = $row["con_level"];
        $date_created = $row["date_created"];

        ?>

                                        <tr>
                                            <td><?php echo $doc_id ?></td>
                                            <td><?php echo $doc_title ?></td>
                                            <td><?php echo substr($doc_file, 0, 40) . '...'; ?></td>
                                            <td>
                                                <?php
                                                if($status === "encrypted"){
                                                    echo "<label class='badge badge-info'> $status </label>";
                                                }else{
                                                    echo "<label class='badge badge-success'> $status</label>";
                                                }
                                                ?>
                                                </td>
                                            <td>
                                                 <?php
                                                if($status != "encrypted"){
                                                    ?>
                                                <form action="" method="get">
                                                    <input type="hidden" name="req_id" value="<?php echo $doc_id ?>">
                                                    <input type="hidden" name="doc_en" value="<?php echo $doc_file ?>">
                                                    <button name="encrypt" type="submit" class="btn btn-primary">encrypt</button>
                                                </form>
                                                <?php    } ?>
                                            </td>
                                        </tr>
        <?php
    }
} else {
    echo "0 results";
}
     ?>
                                        </tbody>
                                    </table>
                                </div>
                            </div>
                        </div>
                    </div>
                </div>

            </div>
            <!-- content-wrapper ends -->
<?php include "../includes/footer.php"; ?>