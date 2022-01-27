<?php

namespace blueturk\skyblock\forms\warp;

use blueturk\skyblock\SkyBlock;
use dktapps\pmforms\CustomForm;
use dktapps\pmforms\CustomFormResponse;
use dktapps\pmforms\element\Toggle;
use pocketmine\Player;

class WarpEditForm extends CustomForm
{

    public function __construct(Player $player, string $warpName)
    {
        $old = SkyBlock::getInstance()->getWarps()->getNested($player->getName() . "." . $warpName . ".Privacy");
        parent::__construct(SkyBlock::BT_TITLE . "Mevki [" . $warpName . "]",
            [
                new Toggle("toggle", "Mevki Gizliliği (Genel / Özel)", $old)
            ], function (Player $player, CustomFormResponse $response)use($warpName): void {
                SkyBlock::getInstance()->getWarps()->setNested($player->getName() . "." . $warpName . ".Privacy", $response->getBool("toggle"));
                $player->sendMessage(SkyBlock::BT_MARK . "dMevki düzenlendi!");
            }
        );
    }
}