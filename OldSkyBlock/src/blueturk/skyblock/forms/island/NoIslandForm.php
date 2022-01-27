<?php /** @noinspection ALL */

namespace blueturk\skyblock\forms\island;

use blueturk\skyblock\forms\island\partner\PartnerTeleportForm;
use blueturk\skyblock\SkyBlock;
use dktapps\pmforms\FormIcon;
use dktapps\pmforms\MenuForm;
use dktapps\pmforms\MenuOption;
use pocketmine\Player;

class NoIslandForm extends MenuForm
{

    public function __construct()
    {
        parent::__construct(SkyBlock::BT_TITLE . "SkyBlock", "\n",
            [
                new MenuOption("Ada Türleri ve Oluşturma", new FormIcon('textures/ui/World', FormIcon::IMAGE_TYPE_PATH)),
                new MenuOption("Partner Olduğun Adalar", new FormIcon('textures/ui/dressing_room_skins', FormIcon::IMAGE_TYPE_PATH)),
                new MenuOption("Ziyarete Açık Adalar", new FormIcon('textures/gui/newgui/mob_effects/night_vision_effect', FormIcon::IMAGE_TYPE_PATH))
            ], function (Player $player, int $option): void {
                switch ($option) {
                    case 0:
                        $player->sendForm(new IslandTypeForm());
                        break;
                    case 1:
                        $player->sendForm(new PartnerTeleportForm($player));
                        break;
                    case 2:
                        $player->sendForm(new IslandVisitAllOpenForm());
                }
            }
        );
    }
}