<?php /** @noinspection ALL */

namespace blueturk\skyblock\forms\island;

use blueturk\skyblock\managers\IslandManager;
use blueturk\skyblock\SkyBlock;
use dktapps\pmforms\MenuForm;
use dktapps\pmforms\MenuOption;
use pocketmine\Player;

class IslandVisitAllOpenForm extends MenuForm
{

    public function __construct()
    {
        $options = [];
        if (SkyBlock::getInstance()->getConfig()->getNested("Visits") != null) {
            foreach (SkyBlock::getInstance()->getConfig()->getNested("Visits") as $item => $value) {
                if ($value === true) {
                    $options[] = new MenuOption($item);
                }
            }
        }
        parent::__construct(SkyBlock::BT_TITLE . "Ziyarete Açık Oyuncular", "\n",
            $options, function (Player $player, int $option): void {
                $selectedPlayer = $this->getOption($option)->getText();
                IslandManager::islandVisit($player, $selectedPlayer);
            }
        );
    }
}