<?php
// MostViewed Extension for Bolt, by issux

namespace MostViewed;

class Extension extends \Bolt\BaseExtension
{

    /**
     * Info block for MostViewed Extension.
     */
    function info()
    {
        $data = array(
            'name' => "MostViewed",
            'description' => "List the most viewed contenttype",
            'keywords' => "most viewed",
            'author' => "Nacho Fernandez",
            'link' => "http://www.fernandezsansalvador.es",
            'version' => "0.1",
            'required_bolt_version' => "1.4",
            'highest_bolt_version' => "1.4",
            'type' => "General",
            'first_releasedate' => "2014-01-19",
            'latest_releasedate' => "2014-01-19",
            'dependencies' => "",
            'priority' => 10
        );

        return $data;

    }

    /**
     * Initialize MostViewed. Called during bootstrap phase.
     */
    function initialize()
    {

        // Add CSS file
        $this->addCSS("assets/mostviewed.css");

        // Initialize the Twig function
        $this->addTwigFunction('mostviewedget', 'twigMostViewedGet');
        $this->addTwigFunction('mostviewedupdate', 'twigMostViewedUpdate');

        // Set the path to match in the controller. 
        $path = $this->app['config']->get('general/branding/path') . '/mostviewed';

        // Add the controller, so it can be matched.
        $this->app->match($path, array($this, 'controllerMostViewed'));

    }

    /**
     * Twig function {{ mostviewedshow('foo') }} in MostViewed extension.
     */
    function twigMostViewedGet($contenttype)
    {
        
        $prefix = isset($this->config['general']['database']['prefix']) ? $this->config['general']['database']['prefix'] : "bolt_";
        
        $limit = (isset($this->config['number'])) ? $this->config['number'] : 5 ; 
        
        $query = "SELECT id FROM ".$prefix."most_viewed LEFT JOIN ".$prefix.$contenttype." ON id=contenttypeid WHERE contenttype='".$contenttype."' ORDER BY views DESC LIMIT ".$limit;
        $entries = $this->app['db']->fetchAll($query);
        
         foreach($entries as $entry) {
             $id = array('id' => $entry['id']);
             $currententry = $this->app['storage']->getContent($contenttype, $id);
             $relations[] = $currententry;
         }        
        
        return $relations;

    }

    /**
     * Twig function {{ mostviewedadd(id,contenttype) }} in MostViewed extension.
     */
    function twigMostViewedUpdate($id,$contenttype)
    {
        
         $prefix = isset($this->config['general']['database']['prefix']) ? $this->config['general']['database']['prefix'] : "bolt_";
        
         $query = "INSERT INTO ".$prefix."most_viewed (contenttypeid,contenttype,views) values(?,?,1) on DUPLICATE KEY UPDATE views=views+1";
         $stmt = $this->app['db']->prepare($query);
         $stmt->bindValue(1, $id);
         $stmt->bindValue(2, $contenttype);
         $stmt->execute();

    }
}


