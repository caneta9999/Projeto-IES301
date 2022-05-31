<?php
/**
 * Validar CPF
 * Classe usada para validação de CPF. 
 * @author Jefferson Sant' Ana 
 * @version 1.0 stable
 */

class CPF{
	
	public static function validate($cpf):bool{
        
        if(empty($cpf)) {           
            return false;
        }        
     
        $cpf = preg_match('/[0-9]/', $cpf)?$cpf:0;

        // Retira pontos, traços e espaços.
        $cpf = trim($cpf);
        $cpf = str_replace(".", "", $cpf);
        $cpf = str_replace(",", "", $cpf);
        $cpf = str_replace("-", "", $cpf);
        $cpf = str_replace("/", "", $cpf);    
        
        // Verifica se a quantidade de caractéres esta correta.
        if (strlen($cpf) != 11) {
            echo "Quantidade de números maior do que o padrão!";
            return false;
        }

        // Verifica as sequencias abaixo, caso foi digitadas retorna false.
        else if ($cpf == '00000000000' || 
            $cpf == '11111111111' || 
            $cpf == '22222222222' || 
            $cpf == '33333333333' || 
            $cpf == '44444444444' || 
            $cpf == '55555555555' || 
            $cpf == '66666666666' || 
            $cpf == '77777777777' || 
            $cpf == '88888888888' || 
            $cpf == '99999999999') {
            return false;
          
         } else {   

            //Calcula os digitos do CPF para verificar se ele é válido.
            for ($t = 9; $t < 11; $t++) {
                 
                for ($d = 0, $c = 0; $c < $t; $c++) {
                    $d += $cpf[$c] * (($t + 1) - $c);
                }
                $d = ((10 * $d) % 11) % 10;
                if ($cpf[$c] != $d) {
                    return false;
                }
            }
     
            return true;
        }
    }
}
?>
