<?php /** @noinspection ALL */

namespace blueturk\skyblock\forms\island;

use blueturk\skyblock\managers\IslandManager;
use blueturk\skyblock\SkyBlock;
use dktapps\pmforms\MenuForm;
use dktapps\pmforms\MenuOption;
use pocketmine\Player;
use pocketmine\Server;

class IslandUnBanPlayerForm extends MenuForm
{

    public function __construct(Player $player)
    {
        $options = [];
        foreach (SkyBlock::getInstance()->getConfig()->getNested($player->getName() . ".island" . ".banneds") as $item => $value) {
            $options[] = new MenuOption($value);
        }
        parent::__construct(SkyBlock::BT_TITLE . "Oyuncu Yasağı Kaldır", "\n",
            $options, function (Player $player, int $option): void {
                $selectedPlayer = $this->getOption($option)->getText();
                IslandManager::islandUnBanPlayer($player, $selectedPlayer);
            }
        );
    }
}