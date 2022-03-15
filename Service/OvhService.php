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

    protected $parameter;
    
    protected $logs;
    protected $nbErrorLogs;
    protected $index;

    public function __construct(ParameterBagInterface $parameterBag)
    {
        
        $this->nbErrorLogs = 0;
        $this->index= 1;
        $this->logs = array();
        
        $this->parameters = $parameterBag;
        $this->ipDefault = $this->parameters->Get("ovh_default")["ip"];
        $this->domainDefault= $this->parameters->Get("ovh_default")["domain"];
        $this->ovhApi = new Api( $this->parameters->Get("ovh_credentials")["application_key"],  // Application Key
        $this->parameters->Get("ovh_credentials")["application_secret"],  // Application Secret
        $this->parameters->Get("ovh_credentials")["endpoint_name"],      // Endpoint of API OVH Europe (List of available endpoints)
        $this->parameters->Get("ovh_credentials")["consumer_key"]); // Consumer Key
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