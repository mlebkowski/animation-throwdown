<?php


namespace Nassau\CartoonBattle\Services\Farming;

use Doctrine\ORM\EntityManagerInterface;
use Symfony\Component\Console\Command\Command;
use Symfony\Component\Console\Input\InputArgument;
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

        $this->addArgument('name', InputArgument::IS_ARRAY);

        $this->em = $em;
        $this->handler = $handler;
    }

    protected function execute(InputInterface $input, OutputInterface $output)
    {
        $names = $input->getArgument('name');

        $qb = $this->em->createQueryBuilder()
            ->select('farming')
            ->from('CartoonBattleBundle:Game\UserFarming', 'farming')
            ->where('farming.enabled = true')
            ->andWhere('farming.expiresAt > :today')
            ->setParameter('today', new \DateTime);

        if (sizeof($names)) {
            $qb->join('farming.user', 'user')
                ->andWhere('user.name in (:names)')
                ->setParameter('names', $names);
        }

        $items = $qb->getQuery()->getResult();

        while ($farming = array_shift($items)) {
            $output->writeln(sprintf('%s: farming user <comment>%s</comment>', date('r'), $farming->getUser()->getName()));

            try {
                $this->handler->farm($farming, $output);
            } catch (FarmingException $e) {
                $this->getApplication()->renderException($e, $output);
            } finally {

                $this->em->persist($farming);
                $this->em->flush();

                $this->em->detach($farming);
                $this->em->detach($farming->getUser());

                unset($farming);

                $output->writeln("\n");
            }

        }

    }

}
