<?php

namespace blueturk\skyblock\forms\warp;

use blueturk\skyblock\SkyBlock;
use dktapps\pmforms\CustomForm;
use dktapps\pmforms\CustomFormResponse;
use dktapps\pmforms\element\Input;
use dktapps\pmforms\element\Label;
use dktapps\pmforms\element\Toggle;
use pocketmine\Player;

class WarpAddForm extends CustomForm
{

    public function __construct()
    {
        parent::__construct(SkyBlock::BT_TITLE . "Mevki Oluştur",
            [
                new Label("lbl", "\n"),
                new Input("warpName", "Mevki İsmi:", "Örnek; Taş Generator"),
                new Label("lbl", "\n"),
                new Toggle("warpPrivacy", "Mevki Gizliliği (Genel / Özel)"),
                new Label("lbl", "\n")
            ], function (Player $player, CustomFormResponse $response): void {
                $warpName = $response->getString("warpName"); $warpPrivacy = $response->getBool("warpPrivacy"); $data = SkyBlock::getInstance()->getWarps();
                if (empty($warpName)) {
                    $player->sendMessage(SkyBlock::BT_MARK . "cMevki ismini boş bırakma!");
                    return;
                }
                if (isset($data->getNested($player->getName())[$warpName])){
                    $player->sendMessage(SkyBlock::BT_MARK . "cAynı isimde mevki mevcut!");
                    return;
                }
                if (strlen($warpName) > 13){
                    $player->sendMessage(SkyBlock::BT_MARK . "cMevki ismi çok uzun!");
                    return;
                }
                $data->setNested($player->getName() . "." . $warpName, [
                    "Privacy" => $warpPrivacy,
                    "Location" => $player->asLocation()
                ]);
                $player->sendMessage(SkyBlock::BT_MARK . "dMevki oluşturuldu!");
            }
        );
    }
}