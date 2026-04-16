<?php
//model
require_once(__DIR__ . '/../model/authModel.php');

//global variable
$page['page'] = 'Index';
$page['subpage'] = isset($_GET['subpage']) ? $_GET['subpage'] : 'login';

// ---------------------------
// START SESSION SAFELY
// ---------------------------
if (session_status() === PHP_SESSION_NONE) {
    session_start();
}

// ---------------------------
// HANDLE LOGOUT FIRST
// ---------------------------
if ($page['subpage'] === 'logout') {
    session_unset();
    session_destroy();
    header("Location: ../page/index.php?subpage=login");
    exit();
}
// ---------------------------
// REDIRECT IF ALREADY LOGGED IN
// ---------------------------
if (isset($_SESSION['official_id']) && $page['subpage'] == 'login') {

    switch ($_SESSION['position']) {

        case 'Captain':
            header('Location: ../page/captain.php');
            break;

        case 'Secretary':
            header('Location: ../page/secretary.php');
            break;

        case 'Treasurer':
            header('Location: ../page/treasurer.php');
            break;

        case 'Health':
            header('Location: ../page/kagawad.php');
            break;

        case 'Environment':
            header('Location: ../page/kagawad.php');
            break;

        case 'Education':
            header('Location: ../page/kagawad.php');
            break;

        case 'Infrastructure':
            header('Location: ../page/kagawad.php');
            break;

        case 'Peace':
            header('Location: ../page/kagawad.php');
            break;

        case 'SK':
            header('Location: ../page/sk_kagawad.php');
            break;
    }

    exit();
}

// ---------------------------
// HANDLE OTHER ACTIONS
// ---------------------------
if (isset($_GET['function'])) {
    new ActiveIndex($page);
} else {
    new Index($page);
}

// ------CLASSES------
class Index
{
    private $page = '';
    private $subpage = '';

    function __construct($page)
    {
        $this->page = $page['page'];
        $this->subpage = $page['subpage'];

        $this->{$page['subpage']}();
    }

    function login()
    {
        // Add no-cache headers to prevent browser back after logout
        header("Cache-Control: no-store, no-cache, must-revalidate, max-age=0");
        header("Cache-Control: post-check=0, pre-check=0", false);
        header("Pragma: no-cache");

        include '../login/login.php';
    }

    function register()
    {
        // Add no-cache headers to prevent browser back after logout
        header("Cache-Control: no-store, no-cache, must-revalidate, max-age=0");
        header("Cache-Control: post-check=0, pre-check=0", false);
        header("Pragma: no-cache");

        include(__DIR__ . '/../admin/register.php');
    }

    function logout()
    {
        // fallback (already handled at top)
        session_unset();
        session_destroy();
        header("Location: index.php?subpage=login");
        exit();
    }

    function profile()
    {
        include(__DIR__ . '/../admin/profile.php');
    }

    function secretary()
    {
        include(__DIR__ . '/../views/secretary.php');
    }
}

class ActiveIndex
{
    private $page = '';
    private $subpage = '';
    protected $authModel = '';

    function __construct($page)
    {
        $this->page = $page['page'];
        $this->subpage = $page['subpage'];
        $this->authModel = new authModel();

        if (isset($_GET['function'])) {
            $this->{$_GET['function']}();
        }
    }

    function register()
    {
        if ($this->authModel->register($_POST)) {
            echo "<script>alert('Register Successfully!');</script>";
            header("Location: authenticate.php?subpage=register");
            exit();
        } else {
            echo "Registration Failed!";
        }
    }

    function login()
    {
        $error = "";
        if ($_SERVER['REQUEST_METHOD'] !== 'POST') {
            header("Location: index.php?subpage=login");
            exit();
        }

        $email = $_POST['email'] ?? '';
        $password = $_POST['password'] ?? '';

        $login = $this->authModel->getEmail($email);

        if ($login && password_verify($password, $login['password'])) {
            $_SESSION['official_id'] = $login['official_id'];
            $_SESSION['email'] = $login['email'];
            $_SESSION['position'] = $login['position'];

            switch ($login['position']) {
                case 'Captain':
                    header('Location: ../page/captain.php?subpage=dashboard');
                    break;
                case 'Secretary':
                    header('Location: ../page/secretary.php?subpage=dashboard');
                    break;
                case 'Treasurer':
                    header('Location: ../page/treasurer.php?subpage=dashboard');
                    break;
                case 'Health':
                    header('Location: ../page/kagawad.php?subpage=dashboard');
                    break;
                case 'Peace':
                    header('Location: ../page/kagawad.php?subpage=dashboard');
                    break;
                case 'Infrastructure':
                    header('Location: ../page/kagawad.php?subpage=dashboard');
                    break;
                case 'Education':
                    header('Location: ../page/kagawad.php?subpage=dashboard');
                    break;
                case 'Environment':
                    header('Location: ../page/kagawad.php?subpage=dashboard');
                    break;
                case 'SK':
                    header('Location: ../page/sk_kagawad.php?subpage=dashboard');
                    break;
            }
            exit();
        } else {
            $error = " ⚠️ Invalid Email or Password!";

            // NO CACHE
            header("Cache-Control: no-store, no-cache, must-revalidate, max-age=0");
            header("Pragma: no-cache");

            // PASS ERROR TO VIEW
            include(__DIR__ . '/../login/login.php');
            exit();
        }
    }
}
