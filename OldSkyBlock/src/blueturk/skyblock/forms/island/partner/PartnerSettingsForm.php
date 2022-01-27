<?php /** @noinspection ALL */

namespace blueturk\skyblock\forms\island\partner;

use blueturk\skyblock\managers\IslandManager;
use blueturk\skyblock\SkyBlock;
use dktapps\pmforms\CustomForm;
use dktapps\pmforms\CustomFormResponse;
use dktapps\pmforms\element\Toggle;
use pocketmine\Player;

class PartnerSettingsForm extends CustomForm
{

    public function __construct(Player $player)
    {
        $data = SkyBlock::getInstance()->getConfig()->getNested($player->getName() . ".island" . ".settings");
        parent::__construct(SkyBlock::BT_TITLE . "Partner Ayarları",
            [
                new Toggle("interact", "Dokunma", $data["interact"]),
                new Toggle("place", "Blok Koyma", $data["place"]),
                new Toggle("break", "Blok Kırma", $data["break"]),
                new Toggle("picking-up", "Yerden Eşya Alma", $data["picking-up"]),
                new Toggle("de-active-teleport", "Aktif Değilken Işınlanma", $data["de-active-teleport"])
            ],
            function (Player $player, CustomFormResponse $response): void {
                $interact = $response->getBool("interact");
                $place = $response->getBool("place");
                $break = $response->getBool("break");
                $pickingUp = $response->getBool("picking-up");
                $deActiveTeleport = $response->getBool("de-active-teleport");
                IslandManager::changePartnerSettings($player, $interact, $place, $break, $pickingUp, $deActiveTeleport);
            }
        );
    }
}