<?php /** @noinspection ALL */

namespace blueturk\skyblock\forms\island;

use blueturk\skyblock\managers\IslandManager;
use blueturk\skyblock\SkyBlock;
use dktapps\pmforms\MenuForm;
use dktapps\pmforms\MenuOption;
use pocketmine\Player;
use pocketmine\Server;

class IslandBanPlayerForm extends MenuForm
{

    public function __construct(Player $player)
    {
        $options = [];
        $level = Server::getInstance()->getLevelByName($player->getName());
        if (Server::getInstance()->isLevelLoaded($player->getName())) if ($level->getPlayers() != null) foreach ($level->getPlayers() as $player) $options[] = new MenuOption($player->getName());
        parent::__construct(SkyBlock::BT_TITLE . "Adandan Oyuncu Yasakla", "\n",
            $options, function (Player $player, int $option): void {
                $selectedPlayer = $this->getOption($option)->getText();
                IslandManager::islandBanPlayer($player, $selectedPlayer);
            }
        );
    }
}