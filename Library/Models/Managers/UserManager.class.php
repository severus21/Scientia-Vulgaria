<?php
/*
 * name: UserManager
 * @description :  
 */

class UserManager extends Manager{
    /*
        Attributs
    */

        /*
            Constantes
        */
            
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
         * 
         * name: exists
         * @param $login(string)
         * @return bool
         * 
         */     
        public function exists($login=null, $email=null){
			$requeteObj=new Requete();
			$requeteObj->addCol(new StringCol(array('name'=>'login', 'value'=>$login, 'strict'=>true)));
			$requeteObj->addCol(new StringCol(array('name'=>'email', 'value'=>$email, 'strict'=>true, 'logicalOperator'=>'OR')));			
			$recs=$this->getList($requeteObj);

			if(empty($recs)){
                return null;
            }else{
				$str='';
				foreach($recs as $rec){
					if($email==$rec->getEmail()){
						$str.='Email déjà utilisé!';
					}elseif($login==$rec->getLogin()){
						$str.='Login déjà utilisé!';
					}
				}
				return $str;
            }
        }
        
        public function insert($donnees){
			$str=$this->exists($donnees['login'], $donnees['email']);
            if(isset($str)){
                return $str;
            }else{
                $id=parent::insert($donnees);
                return $id;
            }
        }
        
        public function getLogin($login){
            if(preg_match('#^[a-zA-Z0-9_@]{4,20}$#i',$login) || empty($login)){    
                $getLogin=$this->dao->prepare('SELECT * FROM user WHERE login=:login');
                $getLogin->bindValue(':login', $login, PDO::PARAM_STR);
                $getLogin->execute();
                while($data=$getLogin->fetch()){
                    $user=new UserRecord($data);
                }
                $getLogin->closeCursor();
                
                if(!empty($user)){
                    return $user;
                }else{
                    return false;
                }
            }else{
                return false;
            }
        }
}
