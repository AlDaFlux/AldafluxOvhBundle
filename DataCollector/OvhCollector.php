<?php

namespace Aldaflux\AldafluxOvhBundle\DataCollector;

use Symfony\Component\HttpFoundation\Request;
use Symfony\Component\HttpFoundation\Response;


use Symfony\Bundle\FrameworkBundle\DataCollector\AbstractDataCollector;

use Aldaflux\AldafluxOvhBundle\Service\OvhService;


class OvhCollector extends AbstractDataCollector{

    
    protected $ovhService;
    
    public function __construct(OvhService $ovhService)
    {   
        $this->ovhService=$ovhService;            
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
            $this->data = [
                'test' => "OK", 
                'logs' => $this->ovhService->getLogs(),
                'nbLogs' => $this->ovhService->getNbLogs(),
                'nbErrorLogs' => $this->ovhService->getNbErrorLogs(),
                ];
    }
    
    public function nbLogs()
    {
        return $this->data['nbLogs'];
    }

    public function nbErrorLogs()
    {
        return $this->data['nbErrorLogs'];
    }
    
    public function getLogs()
    {
        return $this->data['logs'];
    }


    

    public static function getTemplate(): ?string
    {
        return '@AldafluxOvh/data_collector/ovh_collector.html.twig';
    }
    
    
    
 
    
    
    
    
}