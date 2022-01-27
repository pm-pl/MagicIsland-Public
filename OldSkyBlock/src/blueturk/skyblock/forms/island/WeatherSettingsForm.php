<?php

namespace blueturk\skyblock\forms\island;

use blueturk\skyblock\managers\IslandManager;
use blueturk\skyblock\SkyBlock;
use dktapps\pmforms\MenuForm;
use dktapps\pmforms\MenuOption;
use pocketmine\network\mcpe\protocol\LevelEventPacket;
use pocketmine\Player;
use pocketmine\Server;
use pocketmine\utils\BinaryStream;

class WeatherSettingsForm extends MenuForm
{

    public function __construct()
    {
        parent::__construct(SkyBlock::BT_TITLE . "Hava Durumu", "",
            [
                new MenuOption("Yağmurlu"),
                new MenuOption("Şimşekli"),
                new MenuOption("Karanlık"),
                new MenuOption("Aydınlık")
            ], function (Player $player, int $option): void {
                if ($player->getLevel()->getFolderName() === $player->getName()) {
                    switch ($option) {
                        case 0:
                            if (isset(SkyBlock::$weathers[$player->getName()])) {
                                $player->sendMessage(SkyBlock::BT_MARK . "cSunucu yeniden başlatılana kadar değiştiremezsin!");
                                return;
                            }
                            SkyBlock::$weathers[$player->getName()] = "rain";
                            $packet = new LevelEventPacket();
                            $packet->evid = LevelEventPacket::EVENT_START_RAIN;
                            $packet->position = null;
                            $packet->data = 10000;
                            $player->dataPacket($packet);
                            $player->sendMessage(SkyBlock::BT_MARK . "bHava durumu yağmurlu olarak ayarlandı!");
                            break;
                        case 1:
                            if (isset(SkyBlock::$weathers[$player->getName()])) {
                                $player->sendMessage(SkyBlock::BT_MARK . "cSunucu yeniden başlatılana kadar değiştiremezsin!");
                                return;
                            }
                            SkyBlock::$weathers[$player->getName()] = "thunder";
                            $packet = new LevelEventPacket();
                            $packet->evid = LevelEventPacket::EVENT_START_THUNDER;
                            $packet->position = null;
                            $packet->data = 10000;
                            $player->dataPacket($packet);
                            $player->sendMessage(SkyBlock::BT_MARK . "bHava durumu şimşekli olarak ayarlandı!");
                            break;
                        case 2:
                            $player->getLevel()->setTime(13000);
                            $player->sendMessage(SkyBlock::BT_MARK . "bHava durumu karanlık olarak ayarlandı!");
                            break;
                        case 3:
                            $player->getLevel()->setTime(1000);
                            $player->sendMessage(SkyBlock::BT_MARK . "bHava durumu aydınlık olarak ayarlandı!");
                            break;
                    }
                } else {
                    $player->sendMessage(SkyBlock::BT_MARK . "cBu özelliği kullanmak için adanda olmalısın!");
                }
            }
        );
    }
}