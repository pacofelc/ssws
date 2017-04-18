<?php 
    include "JWT.php";
     class Match {
     
         public function Join ( ) {
             // Extraemos el JWT para saber que usuario quiere unirse una partida
             
             // Buscamos si el usuario ya se encuentra jugando alguna de las partidas en curso.
             // Si se trata de una reconexión devolvemos el match_id
             
             // Buscamos si hay algún usuario esperando contrincante
             // Devolvemos su match_id
             
             // Creamos un nuevo Match
             this->New ( $userId );
         }
         
         
         private function New ($userId) {
             // Creamos una nueva partida
         }
         
         public function End ($matchId) {
             // Puede ser llamado por cualquiera de los contrincantes
             
             // Si no existe matchId devolvemos error
             
             // Si el partido está en estado finalizado devolvemos el resultado final
             
             // Si el Match todavía está en juego (ambos contrincantes conectados y no último turno)
             // Posibles trampas (Apuntamos al llamante). Devolvemos error.
             
             // Guardamos el resultado 
             // Guardamos experiencia
             // Entregamos premios
             
             // Devolvemos el resultado final
         }
         
     }
 ?>
