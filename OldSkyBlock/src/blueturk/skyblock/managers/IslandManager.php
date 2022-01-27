<?php

namespace blueturk\skyblock\managers;


use blueturk\skyblock\forms\island\partner\PartnerRequestForm;
use blueturk\skyblock\SkyBlock;
use pocketmine\Player;
use pocketmine\Server;

class IslandManager
{

    /**
     * @param Player $player
     * @param string $selectedPlayer
     */
    public static function islandVisit(Player $player, string $selectedPlayer): void
    {
        $selectedPlayer = Server::getInstance()->getPlayerExact($selectedPlayer);
        if (!$selectedPlayer instanceof Player) {
            $player->sendMessage(SkyBlock::BT_MARK . "cOyuncu aktif değil ziyaret edemezsin!");
            return;
        }
        if (!Server::getInstance()->isLevelLoaded($selectedPlayer->getName())) Server::getInstance()->loadLevel($selectedPlayer->getName());
        $player->teleport(Server::getInstance()->getLevelByName($selectedPlayer->getName())->getSpawnLocation());
        $player->sendMessage(SkyBlock::BT_MARK . "bAdayı ziyaret ettin!");
        $selectedPlayer->sendMessage(SkyBlock::BT_MARK . "b" . $player->getName() . " adlı oyuncu adanı ziyaret etti!");
        return;
    }

    /**
     * @param Player $player
     * @param string $selectedPlayer
     */
    public static function partnerRemove(Player $player, string $selectedPlayer)
    {
        $array = SkyBlock::getInstance()->getConfig()->getNested($player->getName() . ".island" . ".this-partners");
        $array2 = SkyBlock::getInstance()->getConfig()->getNested($selectedPlayer . ".island" . ".other-partners");
        unset($array[array_search($selectedPlayer, $array)]);
        unset($array2[array_search($player->getName(), $array2)]);
        SkyBlock::getInstance()->getConfig()->setNested($player->getName() . ".island" . ".this-partners", $array);
        SkyBlock::getInstance()->getConfig()->setNested($selectedPlayer . ".island" . ".other-partners", $array2);
        $player->sendMessage(SkyBlock::BT_MARK . "bOyuncuyu partnerlikten çıkardın!");
        $selectedPlayer = Server::getInstance()->getPlayerExact($selectedPlayer);
        if ($selectedPlayer instanceof Player) {
            $selectedPlayer->sendMessage(SkyBlock::BT_MARK . "b" . $player->getName() . " adlı oyuncu sizi partnerlikten çıkardı!");
        }
    }

    /**
     * @param Player $player
     * @param string $requestPlayer
     */
    public static function partnerRequestConfirm(Player $player, string $requestPlayer)
    {
        $requestPlayer = Server::getInstance()->getPlayerExact($requestPlayer);
        if ($requestPlayer instanceof Player) {
            $array = SkyBlock::getInstance()->getConfig()->getNested($requestPlayer->getName() . ".island" . ".this-partners");
            if (!in_array($player->getName(), $array)) {
                array_push($array, $player->getName());
                SkyBlock::getInstance()->getConfig()->setNested($requestPlayer->getName() . ".island" . ".this-partners", $array);
                if (SkyBlock::getInstance()->getConfig()->getNested($player->getName() . ".island") != null) {
                    $array1 = SkyBlock::getInstance()->getConfig()->getNested($player->getName() . ".island" . ".other-partners");
                    array_push($array1, $requestPlayer->getName());
                    SkyBlock::getInstance()->getConfig()->setNested($player->getName() . ".island" . ".other-partners", $array1);
                } else {
                    $array1 = SkyBlock::getInstance()->getConfig()->getNested($player->getName() . ".partners");
                    array_push($array1, $requestPlayer->getName());
                    SkyBlock::getInstance()->getConfig()->setNested($player->getName() . ".partners", $array1);
                }
                $player->sendMessage(SkyBlock::BT_MARK . "bPartnerlik teklifini kabul ettin!");
                $requestPlayer->sendMessage(SkyBlock::BT_MARK . "bPartnerlik teklifinizi kabul etti!");
            } else {
                $requestPlayer->sendMessage(SkyBlock::BT_MARK . "cBu oyuncu zaten partneriniz!");
            }
        } else {
            $player->sendMessage(SkyBlock::BT_MARK . "cOyuncu aktif değil!");
        }
    }

    /**
     * @param Player $player
     * @param string $selectedPlayer
     */
    public static function partnerRequest(Player $player, string $selectedPlayer): void
    {
        $selectedPlayer = Server::getInstance()->getPlayerExact($selectedPlayer);
        if ($selectedPlayer instanceof Player) {
            if ($selectedPlayer->getName() === $player->getName()) {
                $player->sendMessage(SkyBlock::BT_MARK . "cKendini partner ekleyemezsin!");
                return;
            }
            $array = SkyBlock::getInstance()->getConfig()->getNested($player->getName() . ".island" . ".this-partners");
            if (in_array($selectedPlayer->getName(), $array)) {
                $player->sendMessage(SkyBlock::BT_MARK . "cBu oyuncu zaten partneriniz!");
                return;
            }
            $selectedPlayer->sendForm(new PartnerRequestForm($player));
            $player->sendMessage(SkyBlock::BT_MARK . "b" . $selectedPlayer->getName() . " adlı oyuncuya partnerlik isteği gönderildi!");
        } else {
            $player->sendMessage(SkyBlock::BT_MARK . "cOyuncu aktif değil!");
        }
    }

    /**
     * @param Player $player
     * @param string $selectedPlayer
     */
    public static function islandUnBanPlayer(Player $player, string $selectedPlayer)
    {
        $array = SkyBlock::getInstance()->getConfig()->getNested($player->getName() . ".island" . ".banneds");
        unset($array[array_search($selectedPlayer, $array)]);
        SkyBlock::getInstance()->getConfig()->setNested($player->getName() . ".island" . ".banneds", $array);
        $player->sendMessage(SkyBlock::BT_MARK . "bOyuncunun yasağını kaldırdın!");
    }

    /**
     * @param Player $player
     * @param string $selectedPlayer
     */
    public static function islandBanPlayer(Player $player, string $selectedPlayer)
    {
        $selectedPlayer = Server::getInstance()->getPlayerExact($selectedPlayer);
        if ($selectedPlayer instanceof Player) {
            $array = SkyBlock::getInstance()->getConfig()->getNested($player->getName() . ".island" . ".banneds");
            array_push($array, $selectedPlayer->getName());
            SkyBlock::getInstance()->getConfig()->setNested($player->getName() . ".island" . ".banneds", $array);
            $selectedPlayer->teleport(Server::getInstance()->getDefaultLevel()->getSpawnLocation());
            $selectedPlayer->sendMessage(SkyBlock::BT_MARK . "cAdadan yasaklandın!");
            $player->sendMessage(SkyBlock::BT_MARK . "bOyuncuyu yasakladın!");
        } else {
            $player->sendMessage(SkyBlock::BT_MARK . "cOyuncu aktif değil!");
        }
    }

    /**
     * @param Player $player
     * @param string $selectedPlayer
     */
    public static function islandKickPlayer(Player $player, string $selectedPlayer): void
    {
        $selectedPlayer = Server::getInstance()->getPlayerExact($selectedPlayer);
        if ($selectedPlayer instanceof Player) {
            if ($selectedPlayer->getName() === $player->getName()) {
                $player->sendMessage(SkyBlock::BT_MARK . "cKendini tekmeleyemezsin!");
                return;
            }
            $selectedPlayer->teleport(Server::getInstance()->getDefaultLevel()->getSpawnLocation());
            $selectedPlayer->sendMessage(SkyBlock::BT_MARK . "cAdadan tekmelendiniz!");
            $player->sendMessage(SkyBlock::BT_MARK . "bOyuncu tekmelendi!");
        } else {
            $player->sendMessage(SkyBlock::BT_MARK . "cOyuncu aktif değil!");
        }
    }

    /**
     * @param Player $player
     * @param string $selectedPlayer
     */
    public static function teleportPartnerIsland(Player $player, string $selectedPlayer)
    {
        $selectedPlayers = Server::getInstance()->getPlayerExact($selectedPlayer);
        if ($selectedPlayers instanceof Player) {
            if (!Server::getInstance()->isLevelLoaded($selectedPlayers->getName())) Server::getInstance()->loadLevel($selectedPlayers->getName());
            $level = Server::getInstance()->getLevelByName($selectedPlayers->getName());
            $player->teleport($level->getSpawnLocation());
            $player->sendMessage(SkyBlock::BT_MARK . "bPartner adasına ışınlandın!");
        } else {
            $status = SkyBlock::getInstance()->getConfig()->getNested($selectedPlayer . ".island" . ".settings" . ".de-active-teleport");
            if ($status === true) {
                if (!Server::getInstance()->isLevelLoaded($selectedPlayer)) Server::getInstance()->loadLevel($selectedPlayer);
                $level = Server::getInstance()->getLevelByName($selectedPlayer);
                $player->teleport($level->getSpawnLocation());
                $player->sendMessage(SkyBlock::BT_MARK . "bPartner adasına ışınlandın!");
            } elseif ($status === false) {
                $player->sendMessage(SkyBlock::BT_MARK . "cPartnerin aktif değilken adasına ışınlanamazsın!");
            } else {
                $player->sendMessage(SkyBlock::BT_MARK . "cPartneriniz adasını silmiş!");
            }
        }
    }

    /**
     * @param Player $player
     * @param bool $interact
     * @param bool $place
     * @param bool $break
     * @param bool $pickingUp
     * @param bool $deActiveTeleport
     */
    public static function changePartnerSettings(Player $player, bool $interact, bool $place, bool $break, bool $pickingUp, bool $deActiveTeleport)
    {
        SkyBlock::getInstance()->getConfig()->setNested($player->getName() . ".island" . ".settings" . ".interact", $interact);
        SkyBlock::getInstance()->getConfig()->setNested($player->getName() . ".island" . ".settings" . ".place", $place);
        SkyBlock::getInstance()->getConfig()->setNested($player->getName() . ".island" . ".settings" . ".break", $break);
        SkyBlock::getInstance()->getConfig()->setNested($player->getName() . ".island" . ".settings" . ".picking-up", $pickingUp);
        SkyBlock::getInstance()->getConfig()->setNested($player->getName() . ".island" . ".settings" . ".de-active-teleport", $deActiveTeleport);
        $player->sendMessage(SkyBlock::BT_MARK . "bPartner ayarları kaydedildi!");
    }

    /**
     * @param Player $player
     */
    public static function teleportToIsland(Player $player)
    {
        if (!Server::getInstance()->isLevelLoaded($player->getName())) Server::getInstance()->loadLevel($player->getName());
        $level = Server::getInstance()->getLevelByName($player->getName());
        $player->teleport($level->getSpawnLocation());
        $player->sendMessage(SkyBlock::BT_MARK . "bAdana ışınlandın!");
    }

    /**
     * @param Player $player
     */
    public static function setIslandSpawnLocation(Player $player)
    {
        if ($player->getLevel()->getFolderName() === $player->getName()) {
            $player->getLevel()->setSpawnLocation($player->asVector3());
            $player->sendMessage(SkyBlock::BT_MARK . "bAda merkezi ayarlandı!!");
        } else {
            $player->sendMessage(SkyBlock::BT_MARK . "cBu işlemi sadece adanızda yapabilirsiniz!");
        }
    }

    /**
     * @param Player $player
     * @param bool $status
     */
    public static function changeIslandVisit(Player $player, bool $status)
    {
        if ($status === true) {
            SkyBlock::getInstance()->getConfig()->setNested("Visits." . $player->getName(), false);
            $player->sendMessage(SkyBlock::BT_MARK . "bZiyaret kapalı olarak ayarlandı!");
        } elseif ($status === false) {
            SkyBlock::getInstance()->getConfig()->setNested("Visits." . $player->getName(), true);
            $player->sendMessage(SkyBlock::BT_MARK . "bZiyaret açık olarak ayarlandı!");
        } else {
            $player->sendMessage(SkyBlock::BT_MARK . "cBilinmeyen bir hata oluştu, yetkili ekibine bildirin!");
        }
    }

    /**
     * @param Player $player
     * @param string $islandType
     */
    public static function islandCreate(Player $player, string $islandType)
    {
        #Copy Island Word
        $dataPath = SkyBlock::getInstance()->getServer()->getDataPath();
        @mkdir($dataPath . "worlds/" . $player->getName() . "/");
        @mkdir($dataPath . "worlds/" . $player->getName() . "/region/");
        $world = opendir(SkyBlock::getInstance()->getServer()->getDataPath() . $islandType . "/region/");
        while ($file = readdir($world)) {
            if ($file != "." and $file != "..") {
                copy($dataPath . $islandType . "/region/" . $file, $dataPath . "worlds/" . $player->getName() . "/region/" . $file);
            }
        }
        copy($dataPath . $islandType . "/level.dat", $dataPath . "worlds/" . $player->getName() . "/level.dat");
        #Create YAML Data
        $data = SkyBlock::getInstance()->getConfig();
        $deleteTime = $data->getNested($player->getName() . ".delete-time");
        $partners = $data->getNested($player->getName() . ".partners");
        if ($partners === null) $partners = [];
        if ($deleteTime === null) $deleteTime = null;
        $data->setNested($player->getName() . ".island", [
            "settings" => [
                "interact" => false,
                "place" => false,
                "break" => false,
                "picking-up" => false,
                "de-active-teleport" => false,
                "delete-time" => $deleteTime
            ],
            "banneds" => [],
            "this-partners" => [],
            "other-partners" => $partners
        ]);
        $data->setNested("Visits." . $player->getName(), false);
        #Teleporting
        Server::getInstance()->loadLevel($player->getName());
        $player->teleport(Server::getInstance()->getLevelByName($player->getName())->getSpawnLocation());
        $player->getLevel()->populateChunk($player->getFloorX() >> 4, $player->getFloorZ() >> 4, true);
        $player->sendMessage(SkyBlock::BT_MARK . "bAdanız oluşturuldu, Işınlanıyorsunuz!");
    }

    /**
     * @param Player $player
     */
    public static function islandRemove(Player $player): void
    {
        if (SkyBlock::getInstance()->getConfig()->getNested($player->getName() . ".delete-time") === null){
            $deleteTime = null;
        }else{
            $deleteTime = SkyBlock::getInstance()->getConfig()->getNested($player->getName())["delete-time"];
        }
        var_dump($deleteTime);
        if ($deleteTime === null) {
            var_dump($deleteTime);
            self::islandDataDelete($player);
        } else {
            if (time() > (int)$deleteTime) {
                self::islandDataDelete($player);
            } else {
                $deleteTime = $deleteTime - time();
                $day = floor($deleteTime / 86400);
                $hourSecond = $deleteTime % 86400;
                $hour = floor($hourSecond / 3600);
                $minuteHour = $hourSecond % 3600;
                $minute = floor($minuteHour / 60);
                $player->sendMessage(SkyBlock::BT_MARK . "fAdanı silebilmek için §6". $day . " §fgün, §6" . $hour . " §fsaat, §6" . $minute . " §fdakika beklemelisin!");
            }
        }
    }

    public static function islandDataDelete(Player $player){
        $level = Server::getInstance()->getLevelByName($player->getName());
        if ($level->getPlayers() != null) {
            foreach ($level->getPlayers() as $islandPlayer) {
                $islandPlayer->teleport(Server::getInstance()->getDefaultLevel()->getSpawnLocation());
                $islandPlayer->sendMessage(SkyBlock::BT_MARK . "bBulunduğun ada siliniyor..");
            }
        }
        $old = SkyBlock::getInstance()->getConfig()->getNested($player->getName() . ".island" . ".this-partners");
        if ($old != null) {
            foreach ($old as $value) {
                $array = SkyBlock::getInstance()->getConfig()->getNested($value . ".island" . ".other-partners");
                if ($array != null) {
                    unset($array[array_search($player->getName(), $array)]);
                    SkyBlock::getInstance()->getConfig()->setNested($value . ".island" . ".other-partners", $array);
                } else {
                    $array = SkyBlock::getInstance()->getConfig()->getNested($value . ".partners");
                    unset($array[array_search($player->getName(), $array)]);
                    SkyBlock::getInstance()->getConfig()->setNested($value . ".partners", $array);
                }
            }
        }
        $old2 = SkyBlock::getInstance()->getConfig()->getNested($player->getName() . ".island" . ".other-partners");
        if ($old2 != null) {
            foreach ($old2 as $value) {
                $array = SkyBlock::getInstance()->getConfig()->getNested($value . ".island" . ".this-partners");
                if ($array != null) {
                    unset($array[array_search($player->getName(), $array)]);
                    SkyBlock::getInstance()->getConfig()->setNested($value . ".island" . ".this-partners", $array);
                }
            }
        }
        $world = Server::getInstance()->getLevelByName($player->getName());
        Server::getInstance()->unloadLevel($world);
        $world = SkyBlock::getInstance()->getServer()->getDataPath() . "/worlds/" . $player->getName();
        self::worldDelete($world);
        SkyBlock::getInstance()->getConfig()->removeNested($player->getName() . ".island");
        SkyBlock::getInstance()->getConfig()->removeNested("Visits." . $player->getName());
        SkyBlock::getInstance()->getConfig()->setNested($player->getName() . ".delete-time", (time() + 7 * 86400));
        $player->sendMessage(SkyBlock::BT_MARK . "bAdanı başarıyla sildin!");
    }

    public static function worldDelete(string $world): int
    {
        $file = 1;
        if (basename($world) == "." || basename($world) == "..") {
            return 0;
        }
        foreach (scandir($world) as $item) {
            if ($item != "." || $item != "..") {
                if (is_dir($world . DIRECTORY_SEPARATOR . $item)) {
                    $file += self::worldDelete($world . DIRECTORY_SEPARATOR . $item);
                }
                if (is_file($world . DIRECTORY_SEPARATOR . $item)) {
                    $file += unlink($world . DIRECTORY_SEPARATOR . $item);
                }
            }
        }
        rmdir($world);
        return $file;
    }
}