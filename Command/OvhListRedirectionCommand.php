<?php

namespace Aldaflux\AldafluxOvhBundle\Command;
  
use Symfony\Component\Console\Helper\Table;

 
use Symfony\Component\Console\Exception\RuntimeException;
use Symfony\Component\Console\Output\OutputInterface;
use Symfony\Component\Console\Input\InputInterface;
use Symfony\Component\Console\Input\InputArgument;

use Symfony\Component\Console\Input\InputOption;
use Symfony\Component\Console\Style\SymfonyStyle; 
  
use Symfony\Component\Console\Command\Command;
use Symfony\Component\Console\Attribute\AsCommand;


use Symfony\Component\DependencyInjection\ParameterBag\ParameterBagInterface;

#[AsCommand('ovh:list:redirection')]
class OvhListRedirectionCommand extends OvhCommand
{
     
    protected $table;
     

    public function __construct(ParameterBagInterface $parameterBag)
    {
        parent::__construct($parameterBag);
    }

    
    
    /**
     * {@inheritdoc}
     */
    protected function configure(): void
    {
        $this->setDescription('test OVH')->setHelp("Send help !");

        $this->addOption('ip', null, InputOption::VALUE_REQUIRED, 'Filter By Ip');
        $this->addOption('domain', null, InputOption::VALUE_REQUIRED, 'Filter By domain'); 

    }


    
    /**
     * This method is executed after interact() and initialize(). It usually
     * contains the logic to execute to complete this command task.
     */
    protected function execute(InputInterface $input, OutputInterface $output): int
    {
        
        $domain = $input->getOption('domain');
        $ip = $input->getOption('ip');

        if ($domain)
        {
                $table=$this->displayRediretionDomain($domain,$ip);
        }
        else
        {
            $domains = $this->ovhApi->get('/domain');
            foreach ($domains as $domain)
            {
                $table=$this->displayRediretionDomain($domain,$ip);
            }
        }
        
            
            
        
        if ($output->isVerbose()) 
        {
            
        }

        return Command::SUCCESS;
    }

    private function displayRediretionDomain($domain,$ip=null) 
    {
        
            $this->io->success($domain." ".$ip);
            $records = $this->ovhApi->get('/domain/zone/'.$domain.'/record', array(
                 'fieldType' => 'A', // Filter the value of fieldType property (like) (type: zone.NamedResolutionFieldTypeEnum)
            ));
            
            $table = new Table($this->output);
            $table->setHeaders(['usrl', 'target', 'fieldType']);

            foreach ($records as $record)
            {
                
                $recordT=$this->ovhApi->get('/domain/zone/'.$domain.'/record/'.$record);
                
                if ($recordT["subDomain"])
                {
                    $url=$recordT["subDomain"].".".$domain;
                }
                else
                {
                    $url=$domain;
                }
                if ($ip!=null &&$ip==$recordT["target"] or $ip==null )
                {
                    $table->addRow([$url, $recordT["target"], $recordT["fieldType"]]);
                }
            }
            $table->render(); 
        
    }
 
}