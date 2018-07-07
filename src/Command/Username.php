<?php

namespace App\Command;

use Symfony\Component\Console\Command\Command;
use Symfony\Component\Console\Input\InputInterface;
use Symfony\Component\Console\Output\OutputInterface;

//use Symfony\Component\Console\Input\InputArgument;

use Symfony\Component\Console\Input\InputDefinition;
//use Symfony\Component\Console\Input\InputInterface;
use Symfony\Component\Console\Input\InputOption;
//use Symfony\Component\Console\Output\OutputInterface;


use Symfony\Bundle\FrameworkBundle\Command\ContainerAwareCommand;

class Username extends ContainerAwareCommand
{
    protected function configure()
    {
        $this->setName('Username')
            //  ->addArgument('multi',InputArgument::OPTIONAL, 'Requesting usernames after responding with full name, until the user enters ‘\quit’.')
            ->setDefinition(
                new InputDefinition(array(
                    new InputOption('multi', 'multi'),
                    ))
            )
            ->setDescription('Returns username info.')
            ->setHelp('Returns username info.');

        // ->addArgument('multi',InputArgument::OPTIONAL, 'Requesting usernames after responding with full name, until the user enters ‘\quit’.')
    }

    protected function initialize(InputInterface $input, OutputInterface $output)
    {
        parent::initialize($input, $output); // TODO: Change the autogenerated stub
    }

    protected function interact(InputInterface $input, OutputInterface $output)
    {
        parent::interact($input, $output); // TODO: Change the autogenerated stub
    }

    private function getUser($username){

        $em = $this->getContainer()->get('doctrine')->getManager();
        $db = $em->getConnection();

        $q = "SELECT * 
                    FROM problem1.User
                      WHERE username='$username'";
        $stmt = $db->prepare($q);
        $stmt->execute();
        $user = $stmt->fetch();

        return $user;
    }

    protected function execute(InputInterface $input, OutputInterface $output)
    {
        $username = readline('username: ');
        //dd($input);
        $options = $input->getOptions();
        //dd($options);

        if ($options['multi'] == TRUE){
            //dd($options);
            while($username != '\quit'){
                $user = $this->getUser($username);

                if (!empty($user)){
                    $msg = "full name: " . $user['full_name'];
                    $output->writeln($msg);
                } else {
                    $output->writeln("[ERROR]: username $username not found.");
                }

                $username = readline('username: ');
            }
        } else {

            $user = $this->getUser($username);
            //dd($user);

            if (empty($user)){

                $found = false;
                while (!$found){
                    $output->writeln("[ERROR]: username $username not found.");
                    $username = readline('username: ');
                    $user = $this->getUser($username);

                    if(!empty($user)){
                        $found = true;
                    }
                }

            } else {
                //dd($user);
                $output->writeln("full name: ".$user['full_name']);
                exit;
            }
        }
    }
}