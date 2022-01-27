<?php /** @noinspection ALL */

namespace blueturk\skyblock\forms\island;

use blueturk\skyblock\SkyBlock;
use dktapps\pmforms\FormIcon;
use dktapps\pmforms\MenuForm;
use dktapps\pmforms\MenuOption;
use pocketmine\Player;

class IslandTypeForm extends MenuForm
{

    public function __construct()
    {
        parent::__construct(SkyBlock::BT_TITLE . "Ada Oluştur", "§7Oluşturmak istediğin ada türünü seç.\n",
            [
                new MenuOption("Ada Türü: Stabil", new FormIcon('https://www.hizliresim.com/pwkrjfv', FormIcon::IMAGE_TYPE_URL)),
                new MenuOption("Ada Türü: Cehennem", new FormIcon('https://www.hizliresim.com/hycukme', FormIcon::IMAGE_TYPE_URL)),
                new MenuOption("Ada Türü: Kutup", new FormIcon('https://www.hizliresim.com/c0vwp8j', FormIcon::IMAGE_TYPE_URL)),
                new MenuOption("Ada Türü: Düşler", new FormIcon('https://www.hizliresim.com/kuqi0ok', FormIcon::IMAGE_TYPE_URL))
            ], function (Player $player, int $option): void {
                switch ($option) {
                    case 0:
                        $player->sendForm(new IslandCreateConfirmForm($type = "Stabil"));
                        break;
                    case 1:
                        $player->sendForm(new IslandCreateConfirmForm($type = "Cehennem"));
                        break;
                    case 2:
                        $player->sendForm(new IslandCreateConfirmForm($type = "Kutup"));
                        break;
                    case 3:
                        $player->sendForm(new IslandCreateConfirmForm($type = "Düşler"));
                        break;
                }
            }
        );
    }
}

