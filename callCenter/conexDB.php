 <?php
    class DB
    {
        private static $instance;

        private function __construct()
        {
        }

        public static function getInstance($plataforma)
        {
            if (!self::$instance) {
                switch ($plataforma) {
                    case 'FTTH':
                        if ($_SERVER['HTTP_HOST'] == 'localhost:8080') {
                            $conex = new mysqli('127.0.0.1:3308', 'root', 'mysql456456tel*', 'multiplataforma');
                        } else {
                            $conex = new mysqli('10.211.2.27:3308', 'root', 'mysql456456tel*', 'multiplataforma');
                        }
                        break;
                    case 'MOVIL':
                        if ($_SERVER['HTTP_HOST'] == 'localhost:8080') {
                            $conex = new mysqli('127.0.0.1:3312', 'root', 'mysql456456tel*', 'multiplataforma');
                        } else {
                            $conex = new mysqli('10.211.2.6:3306', 'root', 'mysql456456tel*', 'multiplataforma');
                        }
                        break;
                    case 'FIJA':
                        if ($_SERVER['HTTP_HOST'] == 'localhost:8080') {
                            $conex = new mysqli('127.0.0.1:3309', 'root', 'mysql456456tel*', 'multiplataforma');
                        } else {
                            $conex = new mysqli('10.211.2.27:3309', 'root', 'mysql456456tel*', 'multiplataforma');
                        }
                        break;
                    case 'BISTREAM':
                        if ($_SERVER['HTTP_HOST'] == 'localhost:8080') {
                            $conex = new mysqli('127.0.0.1:3311', 'root', 'mysql456456tel*', 'multiplataforma');
                        } else {
                            $conex = new mysqli('10.211.2.27:3311', 'root', 'mysql456456tel*', 'multiplataforma');
                        }
                        break;
                    case 'WHOLESALE':
                        if ($_SERVER['HTTP_HOST'] == 'localhost:8080') {
                            $conex = new mysqli('127.0.0.1:3313', 'root', 'mysql456456tel*', 'multiplataforma');
                        } else {
                            $conex = new mysqli('10.211.2.27:3313', 'root', 'mysql456456tel*', 'multiplataforma');
                        }
                        break;
                    case 'AMAZON':
                        $conex = new mysqli('52.215.151.47:3314', 'root', 'Nex@TDigital#2020', 'multiplataforma');
                        break;
                    case 'TEST':
                        $conex = new mysqli('10.211.2.185:3306', 'software2', '<#Software2#>', 'multiplataforma');
                        break;
                    case 'OSTICKET':
                        $conex = new mysqli('89.140.17.5:3306', 'osuser', 'osuser', 'osticket');
                        break;
                }
                if (mysqli_connect_errno()) {
                    error_log("Connect failed: %s\n", mysqli_connect_error());
                    exit();
                }
                mysqli_set_charset($conex, 'utf8');
                self::$instance = $conex;
            }
            return self::$instance;
        }
    }
    ?>