<?php


namespace Nassau\CartoonBattle\Services\Farming;

use Doctrine\ORM\EntityManagerInterface;
use Symfony\Component\Console\Command\Command;
use Symfony\Component\Console\Input\InputArgument;
use Symfony\Component\Console\Input\InputInterface;
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

        $this->em = $em;
        $this->handler = $handler;
    }

    protected function execute(InputInterface $input, OutputInterface $output)
    {
        $id = $input->getArgument('id');
        $farming = $this->em->getRepository('CartoonBattleBundle:Game\Farming\UserFarming')->find($id);

        if (!$farming) {
            throw new \InvalidArgumentException('There is no such farming: ' . $id);
        }

        $output->writeln(strftime('%Y-%m-%d %H:%M:%S'));
        $output->writeln(sprintf(
            'Farming user <comment>%s</comment>, %smembership expires at <comment>%s</comment>',
            $farming->getUser()->getName(),
            $farming->getSubscription() ? '<info>VIP</info> ': '',
            $farming->getExpiresAt()->format('Y-m-d H:i:s')
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

}