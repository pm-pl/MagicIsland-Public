<?php /** @noinspection ALL */

namespace blueturk\skyblock\forms\island\partner;

use blueturk\skyblock\managers\IslandManager;
use blueturk\skyblock\SkyBlock;
use dktapps\pmforms\ModalForm;
use pocketmine\Player;

class PartnerRequestForm extends ModalForm
{

    public function __construct(Player $requestPlayer)
    {
        parent::__construct(SkyBlock::BT_TITLE . "Partnerlik İsteği", $requestPlayer->getName() . " adlı oyuncu sizi adasına partner olarak eklemek istiyor!",
            function (Player $player, bool $choice) use ($requestPlayer): void {
                if ($choice === true) IslandManager::partnerRequestConfirm($player, $requestPlayer->getName());
                if ($choice === false) {
                    $player->sendMessage(SkyBlock::BT_MARK . "bPartnerlik teklifini kabul etmedin!");
                    if ($requestPlayer->isOnline()) {
                        $requestPlayer->sendMessage(SkyBlock::BT_MARK . "bPartnerlik teklifinizi kabul etmedi!");
                    }
                }
            },
            "Kabul Et",
            "Reddet"
        );
    }
}