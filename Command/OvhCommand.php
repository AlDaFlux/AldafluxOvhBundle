<?php

namespace Aldaflux\AldafluxOvhBundle\Command;
  
use Symfony\Component\Console\Command\Command;
use Symfony\Component\Console\Output\OutputInterface;
use Symfony\Component\Console\Input\InputInterface;

use Symfony\Component\Console\Style\SymfonyStyle; 


use Symfony\Component\DependencyInjection\ParameterBag\ParameterBagInterface;



use \Ovh\Api;
 
 
class OvhCommand extends Command
{
       
    protected $io;
    protected $input;
    protected $output;
    protected $ovhApi;
    private $parameters;
        


    public function __construct(ParameterBagInterface $parameterBag)
    {
        parent::__construct();
        $this->parameters = $parameterBag;
        $this->ipDefault = $this->parameters->Get("ovh_default")["ip"];
        $this->domainDefault= $this->parameters->Get("ovh_default")["domain"];
        
        
        
        $this->ovhApi = new Api( $this->parameters->Get("ovh_credentials")["application_key"],  // Application Key
                $this->parameters->Get("ovh_credentials")["application_secret"],  // Application Secret
                $this->parameters->Get("ovh_credentials")["endpoint_name"],      // Endpoint of API OVH Europe (List of available endpoints)
                $this->parameters->Get("ovh_credentials")["consumer_key"]); // Consumer Key
        
        
    } 
    
    
          /**
     * This optional method is the first one executed for a command after configure()
     * and is useful to initialize properties based on the input arguments and options.
     */
    protected function initialize(InputInterface $input, OutputInterface $output): void
    {
        $this->io = new SymfonyStyle($input, $output);
        $this->input = $input;
        $this->output = $output;
    }
    
     
}