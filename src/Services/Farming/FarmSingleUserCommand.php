<?php


namespace Nassau\CartoonBattle\Services\Farming;

use Doctrine\ORM\EntityManagerInterface;
use Nassau\CartoonBattle\Entity\Game\Farming\UserFarming;
use Symfony\Component\Console\Command\Command;
use Symfony\Component\Console\Input\InputArgument;
use Symfony\Component\Console\Input\InputInterface;
use Symfony\Component\Console\Input\InputOption;
use Symfony\Component\Console\Output\OutputInterface;

class FarmSingleUserCommand extends Command
{
    /**
     * @var EntityManagerInterface
     */
    private $em;

    /**
     * @var FarmingHandler
     */
    private $handler;


    public function __construct(EntityManagerInterface $em, FarmingHandler $handler)
    {
        parent::__construct('animation-throwdown:farm-single-user');

        $this->addArgument('id', InputArgument::REQUIRED);
        $this->addOption('chore', null, InputOption::VALUE_IS_ARRAY | InputOption::VALUE_REQUIRED);
        $this->addOption('force', null, InputOption::VALUE_NONE);

        $this->em = $em;
        $this->handler = $handler;
    }

    protected function execute(InputInterface $input, OutputInterface $output)
    {
        $id = $input->getArgument('id');
        $chores = $input->getOption('chore');
        $force = $input->getOption('force');

        $farming = $this->getFarming($id);

        $name = $farming->getUser()->getName();

        if (false === $farming->isEnabled() && !$force) {
            $output->writeln(sprintf(
                'Farming is disabled for user <comment>%s</comment>. Use <question>--force</question> to override',
                $name
            ));
            return ;
        }

        $farming->setRuntimeSettings($chores);

        $output->writeln(sprintf(
            '<info>%s</info>: Farming user <comment>%s</comment>',
            strftime('%Y-%m-%d %H:%M:%S'),
            $name
        ));

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

    /**
     * @param $id
     *
     * @return UserFarming
     */
    private function getFarming($id)
    {
        if (preg_match('/^[0-9a-f]{8}-[0-9a-f]{4}-[0-9a-f]{4}-[0-9a-f]{4}-[0-9a-f]{12}$/', $id)) {
            $farming = $this->em->getRepository('CartoonBattleBundle:Game\Farming\UserFarming')->find($id);
        } else {
            $farming = $this->em->createQueryBuilder()
                ->select('farming')
                ->from('CartoonBattleBundle:Game\Farming\UserFarming', 'farming')
                ->join('farming.user', 'user')
                ->where('user.name = :name')
                ->setParameter(':name', $id)
                ->getQuery()
                ->getOneOrNullResult();
        }


        if (!$farming) {
            throw new \InvalidArgumentException('There is no such farming: ' . $id);
        }
        return $farming;
    }

}