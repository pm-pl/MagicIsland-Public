<?php /** @noinspection ALL */

namespace blueturk\skyblock\forms\island\partner;

use blueturk\skyblock\SkyBlock;
use dktapps\pmforms\MenuForm;
use dktapps\pmforms\MenuOption;
use pocketmine\Player;

class PartnerOptionsForm extends MenuForm
{

    public function __construct()
    {
        parent::__construct(SkyBlock::BT_TITLE . "Partner Seçenekleri", "\n",
            [
                new MenuOption("Partner Ayarları"),
                new MenuOption("Partner Ekle"),
                new MenuOption("Partner Çıkar")
            ],
            function (Player $player, int $option): void {
                switch ($option) {
                    case 0:
                        $player->sendForm(new PartnerSettingsForm($player));
                        break;
                    case 1:
                        $player->sendForm(new PartnerAddForm());
                        break;
                    case 2:
                        $player->sendForm(new PartnerRemoveForm($player));
                        break;
                }
            }
        );
    }
}
