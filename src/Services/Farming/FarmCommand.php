<?php


namespace Nassau\CartoonBattle\Services\Farming;

use Doctrine\ORM\AbstractQuery;
use Doctrine\ORM\EntityManagerInterface;
use Symfony\Component\Console\Command\Command;
use Symfony\Component\Console\Input\InputInterface;
use Symfony\Component\Console\Output\OutputInterface;
use Symfony\Component\Process\Exception\ProcessTimedOutException;
use Symfony\Component\Process\ProcessBuilder;

class FarmCommand extends Command
{
    /**
     * @var EntityManagerInterface
     */
    private $em;

    public function __construct(EntityManagerInterface $em)
    {
        parent::__construct('animation-throwdown:farm');

        $this->em = $em;
    }

    protected function execute(InputInterface $input, OutputInterface $output)
    {
        $qb = $this->em->createQueryBuilder()
            ->select(['farming.id'])
            ->from('CartoonBattleBundle:Game\Farming\UserFarming', 'farming')
            ->where('farming.enabled = true')
            ->andWhere('farming.expiresAt > :today')
            ->setParameter('today', new \DateTime);

        $items = $qb->getQuery()->getResult(AbstractQuery::HYDRATE_SCALAR);
        $ids = array_column($items, 'id');

        foreach ($ids as $id) {
            try {
                (new ProcessBuilder(['app/console', '--ansi', 'animation-throwdown:farm-single-user', $id]))
                    ->setTimeout(300)
                    ->getProcess()
                    ->run(function ($type, $buffer) use ($output) {
                        $output->write($type ? $buffer : $buffer); // unused var
                    });
            } catch (ProcessTimedOutException $e) {
                $this->getApplication()->renderException($e, $output);
            }
        }

    }

}
