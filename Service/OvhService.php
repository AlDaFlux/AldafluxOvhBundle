<?php

namespace Aldaflux\AldafluxOvhBundle\Service;

use Symfony\Component\HttpFoundation\Request;
use Symfony\Component\HttpFoundation\Response;

use Doctrine\Common\Collections\ArrayCollection;


use Symfony\Bundle\FrameworkBundle\DataCollector\AbstractDataCollector;

use Symfony\Component\DependencyInjection\ParameterBag\ParameterBagInterface;

use \Ovh\Api;




class OvhService  
{

    protected $parameters;
    
    protected $logs;
    protected $nbErrorLogs;
    protected $index;
    protected $ipDefault;
    protected $domainDefault;
    
    protected $ovhApi;

    public function __construct(ParameterBagInterface $parameterBag)
    {
        
        $this->nbErrorLogs = 0;
        $this->index= 1;
        $this->logs = array();
        
        $this->parameters = $parameterBag;

        $ovhDefault = $this->parameters->Get("ovh_default") ?? [];
        $this->ipDefault = $ovhDefault["ip"] ?? null;
        $this->domainDefault = $ovhDefault["domain"] ?? null;


        $ovhCredentials = $this->parameters->Get("ovh_credentials") ?? [];
        
        $requiredKeys = ["application_key", "application_secret", "endpoint_name", "consumer_key"];
        $missingKeys = array_diff($requiredKeys, array_keys(array_filter($ovhCredentials)));

                
        if (empty($missingKeys)) {
            $this->ovhApi = new Api(
                $ovhCredentials["application_key"],
                $ovhCredentials["application_secret"],
                $ovhCredentials["endpoint_name"],
                $ovhCredentials["consumer_key"]
            );
        }
      
    } 
    
    
    
    public function getLogs() 
    {
        return($this->logs);
    }
    
    /*
    public function getErrorLogs() 
    {
        return($this->errorLogs);
    }
     * 
     */
    public function getNbLogs() 
    {
        return(count($this->logs));
    }
    
    public function getNbErrorLogs() 
    {
        return($this->nbErrorLogs);
    }
    
    
    public function get($params,$param2=null)
    {
        $log["params"]=$params;
        try
        {
            $result=$this->ovhApi->get($params,$param2);
            $log["result"]=$result;
        }
        catch (\Exception $ex)
        {
            $log["error"]=$ex;
            $this->nbErrorLogs++;
            $result=null;
        }
        $this->logs[]=$log;
        return($result);
    }
    
    
    public function getOneField($params,$field) 
    {
        $result=$this->get($params);
        if (isset($result[$field]))
        {
            return($result[$field]);
        }
    }
    
    

    public function getBillDetail($codeBill) 
    {
        $bills=array();
        foreach ($this->ovhApi->get("/me/bill/".$codeBill."/details") as $detailCode)
        {
            $bills[$detailCode]=$this->ovhApi->get("/me/bill/".$codeBill."/details/".$detailCode);
        }
        return($bills);
    }
    
    
    public function getRedirectionsDomain($domain) 
    {
        $redirections=New ArrayCollection;
        $records = $this->get('/domain/zone/'.$domain.'/record', array(
             'fieldType' => 'A', // Filter the value of fieldType property (like) (type: zone.NamedResolutionFieldTypeEnum)
        ));
        foreach ($records as $record)
        {
            
            $recordT=$this->get('/domain/zone/'.$domain.'/record/'.$record);
            if ($recordT["subDomain"])
            {
                $recordT['url']=$recordT["subDomain"].".".$domain;
            }
            else
            {
                $recordT['url']=$domain;
            }

            $redirections->add($recordT);
        }
        return($redirections);
        
    }
    
    
    
    
    
    
    
    
    
}
