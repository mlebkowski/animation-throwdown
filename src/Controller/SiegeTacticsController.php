<?php


namespace Nassau\CartoonBattle\Controller;


use Nassau\CartoonBattle\Entity\Game\UserGatherStats;
use Nassau\CartoonBattle\Services\Game\GameFactory;
use Nassau\CartoonBattle\Services\Game\User\InfoFetcher;
use Nassau\CartoonBattle\Services\Request\CsvResponse;
use Symfony\Bundle\FrameworkBundle\Controller\Controller;

class SiegeTacticsController extends Controller
{

    /**
     * @var GameFactory
     */
    private $gameFactory;

    /**
     * @var InfoFetcher
     */
    private $userInfoFetcher;

    public function __construct(GameFactory $gameFactory, InfoFetcher $userInfoFetcher)
    {
        $this->gameFactory = $gameFactory;
        $this->userInfoFetcher = $userInfoFetcher;
    }


    public function statsAction(UserGatherStats $request)
    {
        $game = $this->gameFactory->getGame($request->getUser());

        $status = $game->getGuildSiegeStatus();

        $data = [];
        foreach (['Finlandia' => 'locations', 'Enemy' => 'enemy_locations'] as $guild => $key) {
            foreach ($status[$key] as $island) {
                $name = str_replace(' Island', '', $island['data']['name']);

                foreach ($island['users'] as $user) {

                    $user = $this->userInfoFetcher->getUserInfo($user['user_id']);

                    $user = [
                        $user['name'],
                        $user['pvp_data']['level'],
                        $user['pvp_data']['win_count'],
                        isset($user['cards_by_rarity'][4]) ? $user['cards_by_rarity'][4] : 0,
                        isset($user['cards_by_rarity'][5]) ? $user['cards_by_rarity'][5] : 0,
                        $user['pvp_data']['rating'],
                    ];

                    $data[] = array_merge([$guild, $name], $user);
                }
            }
        }

        return CsvResponse::fromArray($data);

    }
}
