 <?php
    class FileCache {
        var $dirty = false;
        private $data = array();
        private $fileName;

        function __construct($name) {
            $this->fileName = getcwd()."/caches/$name.cache";
            if( file_exists($this->fileName) ) {
                $fp = fopen( $this->fileName, "r");
                if ( flock($fp, LOCK_EX) ) {
                    $this->data = unserialize( fread($fp,filesize($this->fileName)) );
                    flock($fp, LOCK_UN);
                }
                fclose( $fp );
            }
        }

        public function __get($field) {
            if (array_key_exists($field, $this->data))
                return $this->data[$field];
            else
                throw new Exception("Invalid FileCache field : can't find $field.");
        }

        public function __set($field, $value) {
            $this->dirty = true;
            $this->data[$field] = $value;
        }

        public function __isset($field) {
            return isset($this->data[$field]);
        }

        public function __unset($field) {
            unset($this->data[$field]);
        }

        function __destruct () {
            if($this->dirty){
                $fp = fopen( $this->fileName, "w+");
                if ( flock($fp, LOCK_EX) ) {
                    ftruncate($fp, 0);
                    fwrite($fp, serialize($this->data) );
                    fflush($fp);
                    flock($fp, LOCK_UN);
                }
                fclose( $fp );  
            }
        }
    }
?>