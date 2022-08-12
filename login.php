<?php require_once($_SERVER['DOCUMENT_ROOT'] . '/includes/theme-compat/header.php');

if (!empty($_POST['email']) && !empty($_POST['password'])) {

    if (empty($_SESSION['user_id'])) {
        $correct_password = false;
    } else {
        header('Location: ' . BASE_URL);
    }

    $email = $_POST['email'];
    $password = $_POST['password'];

    //Preventing SQL Injection Attacks in Postgres
    $sql = "SELECT * FROM user WHERE email = ?";
    $params = [$email];
    $result = db_sql_protect($sql, $params);

    if (mysqli_num_rows($result) > 0) {
        $result = mysqli_fetch_assoc($result);

        $db_password = $result['password'];

        if (password_verify($password, $db_password)) {
            $correct_password = true;
            $_SESSION['user_id'] = $result['id_user'];
            $_SESSION['firs_name'] = $result['first_name'];
            $_SESSION['last_name'] = $result['last_name'];
            $_SESSION['email'] = $result['email'];
            $_SESSION['phone'] = $result['phone'];
            if (!empty($result['company_name'])) {
                $_SESSION['company_name'] = $result['company_name'];
            }
            if (!empty($result['company_site'])) {
                $_SESSION['company_site'] = $result['company_site'];
            }
        }
    }
}
?>
    <section class="section-fullwidth section-login">
        <div class="row">
            <div class="flex-container centered-vertically centered-horizontally">
                <div class="form-box box-shadow">
                    <div class="section-heading">
                        <h2 class="heading-title">Login</h2>
                    </div>
                    <form method="post">
                        <div class="form-field-wrapper">
                            <input type="email" name="email" placeholder="Email"/>
                        </div>
                        <div class="form-field-wrapper">
                            <input type="password" name="password" placeholder="Password"/>
                        </div>
                        <?php if (isset($password)) {
                            if (!$correct_password) { ?>
                                <p class="error" style="color: red;"><?php echo "Wrong password or Email"; ?></p>
                            <?php }
                        }
                        if (!empty($_SESSION['id_user'])) {
                            header('Location: ' . BASE_URL);
                        }
                        ?>
                        <button type="submit" class="button">
                            Login
                        </button>
                    </form>
                    <a href="#" class="button button-inline">Forgot Password</a>
                </div>
            </div>
        </div>
    </section>
<?php
require_once($_SERVER['DOCUMENT_ROOT'] . '/includes/theme-compat/footer.php'); ?>