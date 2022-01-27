<?php

namespace blueturk\skyblock\tasks;

use blueturk\skyblock\SkyBlock;
use pocketmine\entity\Entity;
use pocketmine\level\Position;
use pocketmine\Player;
use pocketmine\scheduler\Task;
use pocketmine\Server;

class SpawnerSpawnTask extends Task
{

    private $mode = 0;

    public static $time = 40;

    public function onRun(int $currentTick)
    {
        foreach (Server::getInstance()->getLevels() as $level) {
            if (SkyBlock::getInstance()->getSpawners()->exists($level->getFolderName())) {
                $array = SkyBlock::getInstance()->getSpawners()->get($level->getFolderName());
                if (is_array($array)) {
                    foreach ($array as $str) {
                        $str = explode(":", $str);
                        $player = Server::getInstance()->getPlayerExact($str[4]);
                        if ($player instanceof Player) {
                            if ($player->isOnline()) {
                                $pos = new Position($str[0] + rand(0, 1), $str[1] + 0.1, $str[2] + rand(0, 1), $level);
                                switch ($str[5]) {
                                    case 1:
                                        $entity = Entity::createEntity($str[3], $level, Entity::createBaseNBT($pos));
                                        $entity->setNameTag("§7x1 " . $str[3]);
                                        $entity->setNameTagVisible();
                                        $entity->spawnToAll();
                                        break;
                                    case 2:
                                        $entity = Entity::createEntity($str[3], $level, Entity::createBaseNBT($pos));
                                        $entity1 = Entity::createEntity($str[3], $level, Entity::createBaseNBT($pos));
                                        $entity->setNameTag("§7x1 " . $str[3]);
                                        $entity1->setNameTag("§7x1 " . $str[3]);
                                        $entity->setNameTagVisible();
                                        $entity1->setNameTagVisible();
                                        $entity->spawnToAll();
                                        $entity1->spawnToAll();
                                        break;
                                    case 3:
                                        $entity = Entity::createEntity($str[3], $level, Entity::createBaseNBT($pos));
                                        $entity1 = Entity::createEntity($str[3], $level, Entity::createBaseNBT($pos));
                                        $entity2 = Entity::createEntity($str[3], $level, Entity::createBaseNBT($pos));
                                        $entity->setNameTag("§7x1 " . $str[3]);
                                        $entity1->setNameTag("§7x1 " . $str[3]);
                                        $entity2->setNameTag("§7x1 " . $str[3]);
                                        $entity->setNameTagVisible();
                                        $entity1->setNameTagVisible();
                                        $entity2->setNameTagVisible();
                                        $entity->spawnToAll();
                                        $entity1->spawnToAll();
                                        $entity2->spawnToAll();
                                        break;
                                }
                            }
                        }
                    }
                }
            }
        }
        $this->setLevelTime();
        self::$time--;
    }

    /**
     * @param int $time
     * @return int
     */
    public function setLevelTime(int $time = 1000): int
    {
       switch ($this->getMode()){
           case 0:
               $this->setMode(1);
               break;
           case 1:
               $this->setMode(2);
               break;
           case 2:
               $this->setMode(3);
               break;
           case 3:
               $this->setMode(4);
               break;
           case 4:
               foreach (Server::getInstance()->getLevels() as $level) $level->setTime($time);
               $this->setMode(0);
               break;
       }
       return $time;
    }

    /**
     * @return int
     */
    public function getMode(): int
    {
        return $this->mode;
    }

    /**
     * @param int $mode
     */
    public function setMode(int $mode): void
    {
        $this->mode = $mode;
    }
}