<?php

namespace Aldaflux\AldafluxOvhBundle\DataCollector;

use Symfony\Component\HttpFoundation\Request;
use Symfony\Component\HttpFoundation\Response;


use Symfony\Bundle\FrameworkBundle\DataCollector\AbstractDataCollector;


class OvhCollector extends AbstractDataCollector{

    public function __construct()
    {
    
    }
    
    
    public function getName() : string
    {
        return 'aldaflux.ovh_collector';
    }
    
    
     public function reset(): void
    {
        $this->data = [];
    }

    
    
//    Response $response
            
    public function collect(Request $request, Response $response, \Throwable $exception = null)
    {
            $this->data = ['test' => "OK"];
    }
    
    

    public static function getTemplate(): ?string
    {
        return '@AldafluxOvh/data_collector/ovh_collector.html.twig';
    }
    
    
    
 
    
    
    
    
}