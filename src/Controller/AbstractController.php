<?php

namespace App\Controller;

use App\Model\User;
use App\Service\Db\Db;

abstract class AbstractController
{
    protected $templatePath = '/View';
    protected $layout = '/layout.html.php';

    protected $db;
    protected $user;

    public function __construct(array $config)
    {
        $mysqli = new \mysqli($config['db']['host'], $config['db']['user'], $config['db']['password'], $config['db']['database']);
        $mysqli->set_charset($config['db']['charset']);

        $this->db = new Db($mysqli);
        $this->user = new User();
    }

    public function render($template, array $vars = [])
    {
        $user = $this->user;
        extract($vars);

        ob_start();
        require(ROOT_DIR . '/src' . $this->templatePath . $template);
        $_content = ob_get_clean();

        ob_start();
        require(ROOT_DIR . '/src' . $this->templatePath . $this->layout);
        return ob_get_clean();
    }

    protected function isPost(): bool
    {
        return ($_SERVER['REQUEST_METHOD'] === 'POST') ? true : false;
    }

    protected function redirect($url)
    {
        header('location: ' . $url);
        exit();
    }

    protected function addFlash($type, $message)
    {
        $_SESSION['flash'][] = ['type' => $type, 'message' => $message];
    }

    protected function hasFlash(): bool
    {
        return (isset($_SESSION['flash']) && !empty($_SESSION['flash'])) ? true : false;
    }

    public function getFlash()
    {
        $flash = $_SESSION['flash'] ?? [];
        unset($_SESSION['flash']);
        return $flash;
    }
}
