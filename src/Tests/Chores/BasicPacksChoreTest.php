<?php


namespace Nassau\CartoonBattle\Tests\Chores;


use Nassau\CartoonBattle\Entity\Game\Farming\UserFarming;
use Nassau\CartoonBattle\Services\Farming\Chores\BasicPacksChore;

class BasicPacksChoreTest extends \PHPUnit_Framework_TestCase
{
    /**
     * @param       $money
     * @param       $space
     * @param       $expectedNbPulls
     * @param       $expectedLog
     * @param array $pullQueue
     *
     * @dataProvider data
     */
    public function test($money, $space, $expectedNbPulls, $expectedLog = '', $pullQueue = [])
    {
        $chore = new BasicPacksChore(new Repository($pullQueue), 0);

        $game = new GameStub($money, $space);

        $logger = function ($s = "", $newline = true) {
            static $log;

            return chop($log .= $s . (($s && $newline) ? "\n" : ""));
        };

        $configuration = (new UserFarming)->setSettings([UserFarming::SETTING_GOLD]);

        $chore->make($game, $configuration, $logger);

        $this->assertEquals($expectedNbPulls, $game->getNumberOfPulls());

        $this->assertEquals($expectedLog, $logger());
    }

    public function data()
    {
        return [
            'below 100k' => [ 99000, 1, 0],
            'above 100k' => [101000, 1, 1, 'Buying basic packs: <info>1</info>, drops: none'],
            'above 100k, but too little to pull' => [101000, 2, 0, ''],
            'above 100k, little pulls' =>
                [115000, 5, 3, implode("\n", array_pad([], 3, 'Buying basic packs: <info>5</info>, drops: none'))],

            'big pull above 100k' => [150000, 50, 1, 'Buying basic packs: <info>50</info>, drops: none'],
            'huge inventory, but above 400k any way' =>
                [450000, 500, 1, 'Buying basic packs: <info>500</info>, drops: none'],

            'loads of cash, many pulls' =>
                [450000, 50, 7, implode("\n", array_pad([], 7, 'Buying basic packs: <info>50</info>, drops: none'))],

            'legendary drop' =>
                [150000, 50, 1, 'Buying basic packs: <info>50</info>, drops: <comment>Foobar</comment>', [['Foobar']]],


        ];
    }
}
