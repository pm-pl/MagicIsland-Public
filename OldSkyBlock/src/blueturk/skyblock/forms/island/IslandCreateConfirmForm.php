<?php /** @noinspection ALL */

namespace blueturk\skyblock\forms\island;

use blueturk\skyblock\managers\IslandManager;
use blueturk\skyblock\SkyBlock;
use dktapps\pmforms\ModalForm;
use pocketmine\Player;

class IslandCreateConfirmForm extends ModalForm
{

    public function __construct(string $type)
    {
        parent::__construct(SkyBlock::BT_TITLE . "Ada Oluşturma Onayı", "\n§7Ada oluşturup yeni bir maceraya atılmaya ne dersin?\n\n§7Ada Türü: §b" . $type . "\n",
            function (Player $player, bool $choice) use ($type): void {
                switch ($choice) {
                    case true:
                        $player->sendMessage(SkyBlock::BT_MARK . "bAdanız Oluşturuluyor..");
                        if ($type === "Stabil") IslandManager::islandCreate($player, $islandType = "world");
                        if ($type === "Cehennem") IslandManager::islandCreate($player, $islandType = "world");
                        if ($type === "Kutup") IslandManager::islandCreate($player, $islandType = "world");
                        if ($type === "Düşler") IslandManager::islandCreate($player, $islandType = "world");
                        break;
                    case false:
                        $player->sendForm(new IslandTypeForm());
                        break;
                }
            },
            "Ada Oluştur",
            "< Geri Dön"
        );
    }
}
