 <?php
    $then = microtime();
    include "FileCache.php";


    // RewriteRule ^((?!Index\.php).+)$ /Index.php [L]

//    $fc = new FileCache("test");
//    if(!isset($fc->Pepe)) $fc->Pepe = 10;
//    $fc->Pepe = $fc->Pepe+1;
//    $fc->End();
    try {
        $elements = explode( '/', urldecode( ltrim( parse_url( "http://".$_SERVER['HTTP_HOST'].$_SERVER['REQUEST_URI'] )['path'], '/') ) );
        
		// El primer parámetro que empiece por V es la versión
		// a partir de aquí /Vx/Controller/Method/Param1/Param2...
		$elementsCount=Count($elements);
		$posVersion=-1;
		for ( $i=0;$i<$elementsCount;$i++) {
			if ( $elements[$i][0]=='V' && strlen($elements[$i])<4 )
				$posVersion=$i;
		}		

		if ( $elementsCount<3) {
			throw new Exception("Incorrect url format. Version doesn't exists ");
		}
		if ( $elementsCount - $posVersion<3) {
			throw new Exception("Incorrect url format. Empty class or method ");
		}
		
		$version 	= $elements[$posVersion];
		$class		= $elements[$posVersion+1];
		$method     = $elements[$posVersion+2];				
		
		if( !@include( "$version/$class.php" ) )
            throw new Exception("Invalid version or class : $version.$class .");

		
		
		
        $reflectionClass = new ReflectionClass( $class );
        if($reflectionClass != null ) {
            $reflectionMethod = $reflectionClass->getMethod( $method );
            if( $reflectionMethod != null ) {
                if( $reflectionMethod->isProtected() ) {
                    $headers = getallheaders();
                    include "Authorization.php";
                    if( !isset( $headers['Authorization'] ) || Authorization::checkAuthorization($headers['Authorization']) )
                        // Check autorization
                        throw new Exception("Unauthorized access: $version.$class");
                }

                $reflectionMethod->setAccessible(true);
                $args = array_slice ( $elements, $posVersion+3);
                if( $reflectionMethod->getNumberOfParameters() == count($args) ) {
                    $instance = $reflectionClass->newInstanceWithoutConstructor ();			
					
                    $value = array( 'code' => 200, 'result' => $reflectionMethod->invokeArgs( $instance, $args ) );
                }else
                    throw new Exception("Invalid number of parameters: $version.$class.$method .");
            }else
                throw new Exception("Invalid method: $version.$class.$method.");
        }else
            throw new Exception("Invalid class: $version.$class.");

    } catch(Exception $e) {
        $value = array( 'code' => 400, 'result' => $e->getMessage() );
    }

    $value['it-takes'] = round( microtime() - $then, 4);
    header('Content-Type: application/json');
    echo json_encode( (array)$value, JSON_PRETTY_PRINT );
 ?>
