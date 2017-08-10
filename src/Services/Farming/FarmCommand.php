<?php


namespace Nassau\CartoonBattle\Services\Farming;

use Doctrine\ORM\EntityManagerInterface;
use Symfony\Component\Console\Command\Command;
use Symfony\Component\Console\Input\InputInterface;
use Symfony\Component\Console\Output\OutputInterface;

class FarmCommand extends Command
{
    /**
     * @var EntityManagerInterface
     */
    private $em;

    /**
     * @var FarmingHandler
     */
    private $handler;

    /**
     * @param EntityManagerInterface $em
     * @param FarmingHandler $handler
     */
    public function __construct(EntityManagerInterface $em, FarmingHandler $handler)
    {
        parent::__construct('animation-throwdown:farm');

        $this->em = $em;
        $this->handler = $handler;
    }

    protected function execute(InputInterface $input, OutputInterface $output)
    {
        $items = $this->em->getRepository('CartoonBattleBundle:Game\UserFarming')->findBy(['enabled' => 1]);

        foreach ($items as $farming) {
            $output->writeln(sprintf('%s: farming user <comment>%s</comment>', date('r'), $farming->getUser()->getName()));

            try {
                $this->handler->farm($farming, $output);
            } catch (FarmingException $e) {
                $this->getApplication()->renderException($e, $output);
            } finally {

                $this->em->persist($farming);
                $this->em->flush();

                $output->writeln("\n");
            }

        }

    }

}