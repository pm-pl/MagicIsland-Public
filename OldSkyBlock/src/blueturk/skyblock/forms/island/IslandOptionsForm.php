<?php /** @noinspection ALL */

namespace blueturk\skyblock\forms\island;

use blueturk\skyblock\forms\island\partner\PartnerOptionsForm;
use blueturk\skyblock\forms\island\partner\PartnerTeleportForm;
use blueturk\skyblock\managers\IslandManager;
use blueturk\skyblock\SkyBlock;
use dktapps\pmforms\MenuForm;
use dktapps\pmforms\MenuOption;
use pocketmine\Player;

class IslandOptionsForm extends MenuForm
{

    public function __construct(Player $player)
    {
        $visitStatus = SkyBlock::getInstance()->getConfig()->getNested("Visits." . $player->getName());
        parent::__construct(SkyBlock::BT_TITLE . "Ada", "",
            [
                new MenuOption("Adana Işınlan"),
                new MenuOption("Partner Adasına Işınlan"),
                new MenuOption("Partner Seçenekleri"),
                new MenuOption("Ada Merkezini Ayarla"),
                new MenuOption("Ada Ziyaret: " . ($visitStatus === true ? "§l§2AÇIK" : "§l§4KAPALI")),
                new MenuOption("Ziyarete Açık Adalar"),
                new MenuOption("Adandaki Oyuncular"),
                new MenuOption("Adandan Oyuncu Tekmele"),
                new MenuOption("Adandan Oyuncu Yasakla"),
                new MenuOption("Yasaklanmış Oyuncunun Yasağını Kaldır"),
                new MenuOption("Adanı Sil")
            ], function (Player $player, int $option) use ($visitStatus): void {
                switch ($option) {
                    case 0:
                        IslandManager::teleportToIsland($player);
                        break;
                    case 1:
                        $player->sendForm(new PartnerTeleportForm($player));
                        break;
                    case 2:
                        $player->sendForm(new PartnerOptionsForm());
                        break;
                    case 3:
                        IslandManager::setIslandSpawnLocation($player);
                        break;
                    case 4:
                        IslandManager::changeIslandVisit($player, $visitStatus);
                        break;
                    case 5:
                        $player->sendForm(new IslandVisitAllOpenForm());
                        break;
                    case 6:
                        $player->sendForm(new IslandPlayersForm($player));
                        break;
                    case 7:
                        $player->sendForm(new IslandKickPlayerForm($player));
                        break;
                    case 8:
                        $player->sendForm(new IslandBanPlayerForm($player));
                        break;
                    case 9:
                        $player->sendForm(new IslandUnBanPlayerForm($player));
                        break;
                    case 10:
                        IslandManager::islandRemove($player);
                        break;
                    default:
                        throw new \Exception('Unexpected value');
                }
            }
        );
    }
}