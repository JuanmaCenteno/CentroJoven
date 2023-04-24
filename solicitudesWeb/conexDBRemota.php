 <?php
    class DBRemota
    {
        private static $instance;

        private function __construct()
        {
        }

        public static function getInstance()
        {
            if (!self::$instance) {

                $conex = new mysqli('10.211.2.185:3306', 'software2', '<#Software2#>', 'multiplataforma');

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