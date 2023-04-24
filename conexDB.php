 <?php
    class DB
    {
        private static $instance;

        private function __construct()
        {
        }

        public static function getInstance()
        {
            if (!self::$instance) {
                // Conexión con BD Local
                $conex = new mysqli('127.0.0.1:3306', 'root', '', 'centrojoven');
                
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