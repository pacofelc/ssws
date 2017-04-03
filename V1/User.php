 <?php 
    include "JWT.php";

     class User {
/*
        private function deployment($mysqli){
            if (!$mysqli->query("DROP TABLE IF EXISTS Users") || !$mysqli->query("CREATE TABLE Users(id INT, user varchar(256), password varchar(40), reg_date TIMESTAMP )")) {
                throw new Exception("Falló la creación de la tabla: (" . $mysqli->errno . ") " . $mysqli->error);
            }
        }
*/
		public function LoginTest($user,$pass){
			$ret = JWT::encode( json_encode(array( "id" => $user ) ) );

            return array( "value" => 0, 'bearer'=>$ret );
		}
		
        public function Login($user,$pass){
            $mysqli = new mysqli("mysql.hostinger.es", "u881264441_admin", "Password123", "u881264441_wsdb");
            if ($mysqli->connect_errno) throw new Exception("Fallo al conectar a MySQL: (" . $mysqli->connect_errno . ") " . $mysqli->connect_error);

            $resultado = $mysqli->query("SELECT * FROM Users WHERE user='$user' AND password='$pass' ORDER BY id ASC");
            $fila = $resultado->fetch_assoc();
            session_start();

            $ret = JWT::encode( json_encode(array( "id" => $fila['id'] ) ) );

            return array( "value" => 0, 'bearer'=>$ret );
        }

        protected function Profile(){
//            $query = 'SELECT * FROM Demo';
//            $result = mysql_query($query);
//            while ($line = mysql_fetch_array($result, MYSQL_ASSOC)) { }
            return array( "value" => 10, "message" => "Hola !!!");
        }
    }
?>