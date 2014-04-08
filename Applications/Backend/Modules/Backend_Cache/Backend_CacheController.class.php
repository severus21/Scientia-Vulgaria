<?php
/*
    Auteur : Oceane21
    Version : 1.0.0
    Projet : Scientia Vulgaria Project
*/

class Backend_CacheController extends BackController{
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
	
	public function executeDrop(){
		$cache=new Cache();
		$cache->dropCache();
		
		$this->app->getUser()->setFlash( $this->config['flash']['drop-cache-done'] );
		$this->httpReponse->redirect('index');
	}
	
	public function executeDropMem(){
		$cache=new Cache();
		$cache->dropMemCache();
		
		$this->app->getUser()->setFlash( $this->config['flash']['drop-memcache-done'] );
		$this->httpReponse->redirect('index');
	}
	
	public function executeUnset(){
		$app=$this->httpRequette->getData('app');
		$mod=$this->httpRequette->getData('mod');
		$name=$this->httpRequette->getData('name');
		
		$cache= new Cache();
		
		if( !empty($app) && !empty($mod) && !empty($name) ) //On supprime un fichier
			$cache->unlink($app, $mod, $name);
		elseif( !empty($app) && !empty($mod) ) //On supprime un module
			$cache->dropCache( Cache::DIR.'/'.$app.'/'.$mod );
		elseif( !empty($app) ) //On supprime le dossier de l'appli
			$cache->dropCache( Cache::DIR.'/'.$app );
			
		$this->app->getUser()->setFlash( $this->config['flash']['unset-cache-done'] );
		$this->httpReponse->redirect('list');
	}
	
	public function executeIndex(){
		$navBuilder=new Backend_CacheNavBuilder(null, $this->config);
		$this->page->addVar('secondaryTopNav', $navBuilder->buildIndexTop() );
		
		
		$cache=new Cache();
		$statsMem=$cache->getMemStats();
		$statsDisk=$cache->getStats();
		
		
		$this->page->addVar('statsDisk', $statsDisk);
		$this->page->addVar('statsMem', $statsMem);
		$this->page->addVar('config', $this->config);
	}
	
	public function executeList(){
		$cache=new Cache();
		$cache->buildCacheStructure();
		
		$this->page->addVar('cacheElements', $cache->getStructure());
		$this->page->addVar('config', $this->config);
	}
}
