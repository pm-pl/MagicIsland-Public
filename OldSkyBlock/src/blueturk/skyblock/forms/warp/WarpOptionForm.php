<?php

namespace blueturk\skyblock\forms\warp;

use blueturk\skyblock\SkyBlock;
use dktapps\pmforms\FormIcon;
use dktapps\pmforms\MenuForm;
use dktapps\pmforms\MenuOption;
use pocketmine\level\sound\EndermanTeleportSound;
use pocketmine\Player;

class WarpOptionForm extends MenuForm
{

    public function __construct(string $warpName)
    {
        parent::__construct(SkyBlock::BT_TITLE . "Mevki [" . $warpName . "]", "",
            [
                new MenuOption("Işınlan", new FormIcon('textures/ui/realm_icon_small', FormIcon::IMAGE_TYPE_PATH)),
                new MenuOption("Düzenle", new FormIcon('textures/items/book_enchanted', FormIcon::IMAGE_TYPE_PATH)),
                new MenuOption("Sil", new FormIcon('textures/ui/icon_trash', FormIcon::IMAGE_TYPE_PATH)),
                new MenuOption("Geri", new FormIcon('textures/ui/switch_dpad_left', FormIcon::IMAGE_TYPE_PATH)),
            ], function (Player $player, int $option)use($warpName): void {
                $data = SkyBlock::getInstance()->getWarps();
                switch ($option) {
                    case 0:
                        if (isset($data->getNested($player->getName())[$warpName])) {
                            $player->teleport($data->getNested($player->getName())[$warpName]['Location']);
                            $player->getLevel()->addSound(new EndermanTeleportSound($player->asVector3()));
                        }
                        break;
                    case 1:
                        if (isset($data->getNested($player->getName())[$warpName])) {
                            $player->sendForm(new WarpEditForm($player, $warpName));
                        }
                        break;
                    case 2:
                        $data->removeNested($player->getName() . "." . $warpName);
                        $player->sendMessage(SkyBlock::BT_MARK . "dMevki silindi!");
                        break;
                    case 3:
                        $player->sendForm(new WarpMainForm($player));
                        break;
                }
            }
        );
    }
}