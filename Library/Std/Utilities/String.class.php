<?php
/*
 * name: String
 * @description :  traitement des chaines de caractères
 */

class String{

    /*
        Attributs
    */
         protected $caracteresAccent=array('à','á','â','ã','ä','ç','è','é','ê','ë','ì','í','î','ï','ñ','ò','ó','ô','õ','ö','ù','ú','û','ü','ý','ÿ','À','
            Á','Â','Ã','Ä','Ç','È','É','Ê','Ë','Ì','Í','Î','Ï','Ñ','Ò','Ó','Ô','Õ','Ö','Ù','Ú','Û','Ü','Ý');
         protected $caracteres=array('a','a','a','a','a','c','e','e','e','e','i','i','i','i','n','o','o','o','o','o','u','u','u','u','y','y','A','A','A','
            A','A','C','E','E','E','E','I','I','I','I','N','O','O','O','O','O','U','U','U','U','Y');
        /*
            Constantes
        */
			//Entité html, pr bbcode
			const CROCHET_OUVRANT="&lbrack;";
			const CROCHET_FERMANT="&rsqb;";
			const ANTISLASH="&bsol;";
           
    /*
        Méthodes générales
    */
        /*
            Getters
        */
            
        /*
            Setters
        */
    
    /*
        Autres méthodes
    */
		 /*
         * name: size2str
         * @param : $octet(int)
         * @return : string
         * description: retourne le poids d'un fichier de manière compréhensible pour un humain
         */
		public static function uniqStr($l=6){
			$str=microtime(true);
			
			for($a=0; $a<=$l; $a++){
				$str.=mt_rand(0,9);
			}

			return strtr($str, '.','0');
		}
        /*
         * name: size2str
         * @param : $size(int), $chiffres(in) nbr de chiffre après la virgule
         * @return : string
         * description: retourne le poids d'un fichier de manière compréhensible pour un humain
         */
         
        public static function size2str($size,$decimals=0){
			$Unities=['B','kB','MB','GB','TB','PB','EB','ZB','YB'];
			$size=(float)$size;
			
			$i=floor(log($size)/log(1024));
			if($i!=	-INF){
				$size/=pow(1024,$i);
				return number_format($size, $decimals, ',', '').$Unities[$i]; 
			}
			return 0;	      
        }
        
        /*
         * name: stripAccent
         * @param : $string(string)
         * @return : string
         * description: remplace les accents par des lettre non accentuée
         */
        public static function stripAccent($string){
            for($a=0; $a<count($this->caracteresAccent); $a++){
                $string=utf8_encode(strtr(utf8_decode($string), utf8_decode($this->caracteresAccent[$a]), utf8_decode($this->caracteres[$a])));
            }
            return $string;
        }
        
        /*
         * name: str2url
         * @param : $string(string)
         * @return : string
         * description: transforme une chaine de caractère en url valide
         */
        public static function str2url($string){
            $string=preg_replace('#[^a-zA-Z0-9-]#', '-', $string);
            return $string;
        }
		
		public static function NlToBr($string){
			// pour php "\r\n" <>'\r\n'
			return str_replace(['\r\n',"\r\n", '\r', "\r",'\n', "\n"], '<br />', $string);
		}

		public static function HTML2BBCode($texte){
			$texte=str_replace( ["&lbrack;", '&lbrack;'], "[", $texte); //Crochet ouvrant
			$texte=str_replace( ["&rsqb;", '&rsqb;'], "]", $texte); //croches fermant
			$texte=str_replace( ["&bsol;", '&bsol;'], "\\", $texte); //antislash
			return $texte;
		}

		public static function parseBBCode($texte){echo'<br/>';
			$texte=self::HTML2BBCode($texte);
			//gras
			$texte = preg_replace('`\[b\](.+)\[\\\b\]`isU', '<strong>$1</strong>', $texte); 
			//italique
			$texte = preg_replace('`\[i\](.+)\[\\\i\]`isU', '<em>$1</em>', $texte);
			//souligné
			$texte = preg_replace('`\[s\](.+)\[\\\s\]`isU', '<u>$1</u>', $texte);
			//lien
			$texte = preg_replace('`\[url\](.+)\[\\\url\]`isU', '<a href="$1">$1</a>', $texte);
			//avec titre spécifié
			$texte = preg_replace('`\[url=(.+)\](.+)\[\\\url\]`isU', '<a href="$1">$2</a>', $texte);
			
			return $texte;
		}
		
}
