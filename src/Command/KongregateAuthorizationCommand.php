<?php

namespace Nassau\CartoonBattle\Command;

use Symfony\Bundle\FrameworkBundle\Command\ContainerAwareCommand;
use Symfony\Component\Console\Input\InputArgument;
use Symfony\Component\Console\Input\InputInterface;
use Symfony\Component\Console\Output\OutputInterface;
use Symfony\Component\Console\Question\Question;

class KongregateAuthorizationCommand extends ContainerAwareCommand
{
    protected function configure()
    {
        $this->setName('animation-throwdown:kongregate:authorize')
            ->addArgument('username', InputArgument::REQUIRED)
            ->addArgument('password');
    }

    protected function execute(InputInterface $input, OutputInterface $output)
    {
        $username = $input->getArgument('username');
        $password = $input->getArgument('password');

        if (!$password) {
            $helper = $this->getHelper('question');
            $question = new Question('password> ');
            $question->setHidden(true);
            $question->setHiddenFallback(false);

            $password = $helper->ask($input, $output, $question);
        }


        $auth = $this->getContainer()->get('cartoon_battle.kongregate.auth');

        var_dump($auth->getUser($username, $password));
    }


}
