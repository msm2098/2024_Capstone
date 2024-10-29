<?php
session_start();
require_once "User/config/database.php";
require_once "User/models/UserModel.php";
class UserController
{
    private $model;

    public function __construct()
    {
        $this->model = new UserModel();
    }

    public function login()
    {
        if ($_SERVER['REQUEST_METHOD'] === 'POST') {
            $user_name = $_POST['username'];
            $user_pw = $_POST['userpw'];
            #사용자 인증
            $user_id = $this->model->authentication($user_name, $user_pw);
            if ($user_id) {
                $_SESSION['user_id'] = $user_id;
                $_SESSION['username'] = $user_name;

                $this->model->saveSession($user_id, session_id());#세션값을전달
                header('Location: index.php?controller=user&action=main'); // index.php로 리디렉션
                exit();
                }else{
                #echo "로그인 실패 : 잘못된 사용자 또는 비밀번호 입니다.";
                include "User/views/login.php";
            }
        }else{
            include "User/views/login.php";
        }
    }

    public function logout()
    {
        $session_id=session_id();
        session_unset();
        session_destroy();


        $this->model->deleteSession($session_id);
        header('Location: index.php?controller=user&action=main');

        exit();
    }
    public function signup()
    {
        if ($_SERVER['REQUEST_METHOD'] === 'POST') {
            $user_name = $_POST['username'];
            $user_pw = $_POST['userpw'];
            $this->model->signup($user_name,$user_pw);
            header('Location: index.php?controller=user&action=login');
            exit();
        }else{
            include 'User/views/signup.php';
        }
    }
    public function checkDuplication() {
        if (isset($_POST['username'])) {
            $isAvailable = $this->model->checkDuplication($_POST['username']);

            if ($isAvailable) { // 존재하는 경우
                echo "unavailable"; // 이미 존재하는 아이디
            } else { // 존재하지 않는 경우
                echo "available"; // 사용 가능한 아이디
            }
        } else {
            echo "아이디를 입력하세요."; // 사용자 ID가 비어있는 경우
        }
    }
    public function main(){
        include 'User/views/main.php';
    }
    public function boardlist(){

    }
}
?>

