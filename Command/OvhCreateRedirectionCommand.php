<?php

namespace Aldaflux\AldafluxOvhBundle\Command;
  
 
use Symfony\Component\Console\Exception\RuntimeException;
use Symfony\Component\Console\Output\OutputInterface;
use Symfony\Component\Console\Input\InputInterface;
use Symfony\Component\Console\Input\InputArgument;

use Symfony\Component\Console\Input\InputOption;
use Symfony\Component\Console\Style\SymfonyStyle; 
  
use Symfony\Component\Console\Command\Command;

use Symfony\Component\Console\Question\Question;

Use Symfony\Component\Console\Question\ConfirmationQuestion;

use Symfony\Component\Console\Attribute\AsCommand;
    
use Symfony\Component\DependencyInjection\ParameterBag\ParameterBagInterface;

#[AsCommand('ovh:domaine:zone:redirection:create', 'Add a short description for your command')]
class OvhCreateRedirectionCommand extends OvhCommand
{
     
    public function __construct(ParameterBagInterface $parameterBag)
    {
        parent::__construct($parameterBag);
    }

    /**
     * {@inheritdoc}
     */
    protected function configure(): void
    {
        $this->addArgument('domain', InputArgument::REQUIRED, 'domaine');
        $this->addArgument('subdomain', InputArgument::REQUIRED, 'Sous domaine');
    }
 

    
    /**
     * This method is executed after interact() and initialize(). It usually
     * contains the logic to execute to complete this command task.
     */
    protected function execute(InputInterface $input, OutputInterface $output): int
    {

//        $domain=$this->domainDefault;

        $domain = $input->getArgument('domain');
        $subdomain = $input->getArgument('subdomain');

        
        $helper = $this->getHelper('question');
        /*
        $question = new Question('Please enter the name of the bundle', 'AcmeDemoBundle');
        $bundleName = $helper->ask($input, $output, $question);
         * 
         */
        
        $ip=$this->ipDefault;
        
        
        $question = new ConfirmationQuestion('Do you want creat '.$subdomain.".".$domain." to ".$ip." ?\n", false);

        
        if ($helper->ask($input, $output, $question)) 
        {
                $result =  $this->ovhApi->post('/domain/zone/'.$domain.'/record', array(
                    'fieldType' => 'A', // Resource record Name (type: zone.NamedResolutionFieldTypeEnum)
                    'subDomain' => $subdomain, // Resource record subdomain (type: string)
                    'target' => $ip  // Resource record ttl (type: long)
                ));
 
            $this->io->success($subdomain.".".$domain. "ok !");
            return Command::SUCCESS;
        }
        else
        {
            $this->io->warning("Opération annulée");
        }
        
            
            
            
            /*
        
            $records = $this->ovhApi->get('/domain/zone/handidonnees.fr/record', array(
                 'fieldType' => 'A', // Filter the value of fieldType property (like) (type: zone.NamedResolutionFieldTypeEnum)
            ));
            
            foreach ($records as $record)
            {
                $recordT=$this->ovhApi->get('/domain/zone/handidonnees.fr/record/'.$record);
                $this->io->success($recordT["subDomain"]);
            }
             * 
             */
            
        
        
        if ($output->isVerbose()) 
        {
            
        }

        return Command::SUCCESS;
    }

    private function validateUserData($username, $plainPassword, $email, $fullName): void
    {
        // first check if a user with the same username already exists.
        $existingUser = $this->users->findOneBy(['username' => $username]);

        if (null !== $existingUser) {
            throw new RuntimeException(sprintf('There is already a user registered with the "%s" username.', $username));
        }

        // validate password and email if is not this input means interactive.
        $this->validator->validatePassword($plainPassword);
        $this->validator->validateEmail($email);
        $this->validator->validateFullName($fullName);

        // check if a user with the same email already exists.
        $existingEmail = $this->users->findOneBy(['email' => $email]);

        if (null !== $existingEmail) {
            throw new RuntimeException(sprintf('There is already a user registered with the "%s" email.', $email));
        }
    }
 
}