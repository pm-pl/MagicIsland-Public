<?php /** @noinspection ALL */

namespace blueturk\skyblock\forms\warp;

use blueturk\skyblock\SkyBlock;
use dktapps\pmforms\FormIcon;
use dktapps\pmforms\MenuForm;
use dktapps\pmforms\MenuOption;
use pocketmine\level\sound\EndermanTeleportSound;
use pocketmine\Player;
use pocketmine\utils\TextFormat;

class WarpMainForm extends MenuForm
{

    public function __construct(Player $player)
    {
        $options = [];
        $data = SkyBlock::getInstance()->getWarps();
        $level = $player->getLevel()->getFolderName();
        if ($level === $player->getName()) {
            $options[] = new MenuOption("Mevki Oluştur", new FormIcon('textures/ui/icon_book_writable', FormIcon::IMAGE_TYPE_PATH));
            if ($data->get($player->getName()) != null) {
                foreach ($data->getNested($player->getName()) as $item => $value) {
                    $options[] = new MenuOption($item, new FormIcon('textures/items/sign_jungle', FormIcon::IMAGE_TYPE_PATH));
                }
            }
        } else {
            if ($data->getNested($level) != null) {
                foreach ($data->getNested($level) as $item => $value) {
                    if ($value['Privacy'] === false) {
                        $options[] = new MenuOption($item, new FormIcon('textures/items/sign_jungle', FormIcon::IMAGE_TYPE_PATH));
                    }
                }
            }
        }
        parent::__construct(SkyBlock::BT_TITLE . "Mevki", "",
            $options, function (Player $player, int $option): void {
                $selected = TextFormat::clean($this->getOption($option)->getText());
                $level = $player->getLevel()->getFolderName();
                $data = SkyBlock::getInstance()->getWarps();
                if ($selected === "Mevki Oluştur") {
                    if ($level === $player->getName()) {
                        $player->sendForm(new WarpAddForm());
                    }
                } else {
                    if ($level === $player->getName()) {
                        $player->sendForm(new WarpOptionForm($selected));
                    } else {
                        if (isset($data->getNested($level)[$selected])) {
                            $player->teleport($data->getNested($level)[$selected]['Location']);
                            $player->getLevel()->addSound(new EndermanTeleportSound($player->asVector3()));
                        }
                    }
                }
            }
        );
    }
}