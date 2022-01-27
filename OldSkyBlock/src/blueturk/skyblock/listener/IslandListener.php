<?php /** @noinspection ALL */


namespace blueturk\skyblock\listener;

use blueturk\skyblock\forms\spawner\SpawnerStatusForm;
use blueturk\skyblock\SkyBlock;
use pocketmine\block\Block;
use pocketmine\block\BlockIds;
use pocketmine\event\block\BlockBreakEvent;
use pocketmine\event\block\BlockPlaceEvent;
use pocketmine\event\entity\EntityDamageEvent;
use pocketmine\event\inventory\InventoryPickupItemEvent;
use pocketmine\event\level\ChunkLoadEvent;
use pocketmine\event\Listener;
use pocketmine\event\player\PlayerInteractEvent;
use pocketmine\event\player\PlayerMoveEvent;
use pocketmine\item\Item;
use pocketmine\item\Tool;
use pocketmine\level\biome\Biome;
use pocketmine\math\Vector3;
use pocketmine\Player;
use pocketmine\Server;

class IslandListener implements Listener
{


    public function onInteract(PlayerInteractEvent $event)
    {
        $player = $event->getPlayer();
        $block = $event->getBlock();
        $level = $player->getLevel()->getFolderName();
        $data = SkyBlock::getInstance()->getConfig();
        if ($data->getNested($level . ".island") != null) {
            if ($level === $player->getName()) {
                $array = SkyBlock::getInstance()->getSpawners()->get($level);
                if (is_array($array)) {
                    foreach ($array as $str) {
                        $str2 = explode(":", $str);
                        if ($str2[0] == $block->getX() and $str2[1] == $block->getY() and $str2[2] == $block->getZ() and $str2[4] == $player->getName()) {
                            if (!$player->getInventory()->getItemInHand() instanceof Tool) {
                                $player->sendForm(new SpawnerStatusForm($player, $block, $str2[3], $str2[5]));
                            } else {
                                $player->sendMessage(SkyBlock::BT_MARK . "cSpawnere aletle dokunamazsınız!");
                            }
                        }
                    }
                }
                $event->setCancelled(false);
            } elseif ($player->isOp()) {
                $event->setCancelled(false);
            } elseif (in_array($player->getName(), $data->getNested($level . ".island" . ".this-partners"))) {
                if ($data->getNested($level . ".island" . ".settings" . ".interact") === true) {
                    $event->setCancelled(false);
                } else {
                    $event->setCancelled();
                    $player->sendPopup(SkyBlock::BT_MARK . "cPartneriniz izin vermiyor!");
                }
            } else {
                $event->setCancelled();
            }
        }
    }

    public function onPlaced(BlockPlaceEvent $event)
    {
        $player = $event->getPlayer();
        $item = $event->getItem();
        $level = $player->getLevel()->getFolderName();
        $data = SkyBlock::getInstance()->getConfig();
        if ($data->getNested($level . ".island") != null) {
            if ($level === $player->getName()) {
                if ($item->getId() === BlockIds::MONSTER_SPAWNER) {
                    if ($item->getNamedTag()->getString("type") != null) {
                        $array = SkyBlock::getInstance()->getSpawners()->get($level);
                        if (!is_array($array)) $array = [];
                        $pos = $event->getBlock()->getX() . ":" . $event->getBlock()->getY() . ":" . $event->getBlock()->getZ() . ":" . $item->getNamedTag()->getString("type") . ":" . $player->getName() . ":" . 1;
                        array_push($array, $pos);
                        SkyBlock::getInstance()->getSpawners()->set($level, $array);
                        $player->sendMessage(SkyBlock::BT_MARK . "aSpawner yerleştirildi!");
                    }
                }
                $event->setCancelled(false);
            } elseif ($player->isOp()) {
                if ($item->getId() === BlockIds::MONSTER_SPAWNER) {
                    if ($item->getNamedTag()->getString("type") != null) {
                        $array = SkyBlock::getInstance()->getSpawners()->get($level);
                        if (!is_array($array)) $array = [];
                        $pos = $event->getBlock()->getX() . ":" . $event->getBlock()->getY() . ":" . $event->getBlock()->getZ() . ":" . $item->getNamedTag()->getString("type") . ":" . $player->getName() . ":" . 1;
                        array_push($array, $pos);
                        SkyBlock::getInstance()->getSpawners()->set($level, $array);
                        $player->sendMessage(SkyBlock::BT_MARK . "aSpawner yerleştirildi!");
                    }
                }
                $event->setCancelled(false);
            } elseif (in_array($player->getName(), $data->getNested($level . ".island" . ".this-partners"))) {
                if ($data->getNested($level . ".island" . ".settings" . ".place") === true) {
                    if ($item->getId() === BlockIds::MONSTER_SPAWNER) {
                        $event->setCancelled();
                        $player->sendPopup(SkyBlock::BT_MARK . "cSpawneri sadece adana koyabilirsin!");
                    } else {
                        $event->setCancelled(false);
                    }
                } else {
                    $event->setCancelled();
                    $player->sendPopup(SkyBlock::BT_MARK . "cPartneriniz izin vermiyor!");
                }
            } else {
                $event->setCancelled();
            }
        }
    }

    public function onBreak(BlockBreakEvent $event)
    {
        $player = $event->getPlayer();
        $level = $player->getLevel()->getFolderName();
        $block = $event->getBlock();
        $data = SkyBlock::getInstance()->getConfig();
        if ($data->getNested($level . ".island") != null) {
            if ($level === $player->getName()) {
                if ($block->getId() === BlockIds::MONSTER_SPAWNER) {
                    $array = SkyBlock::getInstance()->getSpawners()->get($level);
                    if (is_array($array)) {
                        foreach ($array as $str) {
                            $str2 = explode(":", $str);
                            $pos = new Vector3((int)$str2[0], (int)$str2[1], (int)$str2[2]);
                            if ($pos->equals($block)) {
                                $item = Item::get(BlockIds::MONSTER_SPAWNER);
                                $item->setCustomName("§b" . $str2[3] . " Spawner");
                                $item->getNamedTag()->setString("type", $str2[3]);
                                $player->getInventory()->addItem($item);
                                unset($array[array_search($str, $array)]);
                                SkyBlock::getInstance()->getSpawners()->set($level, $array);
                                $player->sendMessage(SkyBlock::BT_MARK . "aSpawner kaldırıldı!");
                            }
                        }
                    }
                }
                $event->setCancelled(false);
            } elseif ($player->isOp()) {
                if ($block->getId() === BlockIds::MONSTER_SPAWNER) {
                    $array = SkyBlock::getInstance()->getSpawners()->get($level);
                    if (is_array($array)) {
                        foreach ($array as $str) {
                            $str2 = explode(":", $str);
                            $pos = new Vector3((int)$str2[0], (int)$str2[1], (int)$str2[2]);
                            if ($pos->equals($block)) {
                                $item = Item::get(BlockIds::MONSTER_SPAWNER);
                                $item->setCustomName("§b" . $str2[3] . " Spawner");
                                $item->getNamedTag()->setString("type", $str2[3]);
                                $player->getInventory()->addItem($item);
                                unset($array[array_search($str, $array)]);
                                SkyBlock::getInstance()->getSpawners()->set($level, $array);
                                $player->sendMessage(SkyBlock::BT_MARK . "aSpawner kaldırıldı!");
                            }
                        }
                    }
                }
                $event->setCancelled(false);
            } elseif (in_array($player->getName(), $data->getNested($level . ".island" . ".this-partners"))) {
                if ($data->getNested($level . ".island" . ".settings" . ".break") === true) {
                    if ($block->getId() === BlockIds::MONSTER_SPAWNER) {
                        $event->setCancelled();
                        $player->sendPopup(SkyBlock::BT_MARK . "cSpawnere dokunamazsın!");
                    } else {
                        $event->setCancelled(false);
                    }
                } else {
                    $event->setCancelled();
                    $player->sendPopup(SkyBlock::BT_MARK . "cPartneriniz izin vermiyor!");
                }
            } else {
                $event->setCancelled();
            }
        }
    }

    public function onPickingUp(InventoryPickupItemEvent $event)
    {
        $viewers = $event->getInventory()->getViewers();
        foreach ($viewers as $player) {
            $level = $player->getLevel()->getFolderName();
            $data = SkyBlock::getInstance()->getConfig();
            if ($data->getNested($level . ".island") != null) {
                if ($level === $player->getName()) {
                    $event->setCancelled(false);
                } elseif ($player->isOp()) {
                    $event->setCancelled(false);
                } elseif (in_array($player->getName(), $data->getNested($level . ".island" . ".this-partners"))) {
                    if ($data->getNested($level . ".island" . ".settings" . ".picking-up") === true) {
                        $event->setCancelled(false);
                    } else {
                        $event->setCancelled();
                        $player->sendPopup(SkyBlock::BT_MARK . "cPartneriniz izin vermiyor!");
                    }
                } else {
                    $event->setCancelled();
                }
            }
        }
    }

    public function onMove(PlayerMoveEvent $event)
    {
        $player = $event->getPlayer();
        $level = $player->getLevel()->getFolderName();
        $data = SkyBlock::getInstance()->getConfig();
        if ($data->getNested($level . ".island") != null) {
            if (in_array($player->getName(), $data->getNested($level . ".island" . ".banneds"))) {
                if (!$player->isOp()) {
                    $player->teleport(Server::getInstance()->getDefaultLevel()->getSpawnLocation());
                    $player->sendPopup(SkyBlock::BT_MARK . "cBu adada  yasaklısın!");
                }
            }
        }
    }

    public function onDamage(EntityDamageEvent $event)
    {
        $player = $event->getEntity();
        if ($player instanceof Player) {
            $level = $player->getLevel()->getFolderName();
            if ($level === $player->getName()) {
                if ($event->getCause() === EntityDamageEvent::CAUSE_VOID) {
                    $event->setCancelled();
                    $player->teleport(Server::getInstance()->getLevelByName($player->getName())->getSpawnLocation());
                    if ($player->getXpLevel() >= 7) {
                        $player->setXpLevel($player->getXpLevel() - 7);
                        $player->sendMessage("§8» §cMaalesef adanda öldün ve §7(%3) XP §cdeneyim seviyesi kaybettin.");
                    }
                }
                if ($event->getCause() === EntityDamageEvent::CAUSE_FALL) {
                    $event->setCancelled();
                }
                $event->setCancelled();
            } else {
                if (SkyBlock::getInstance()->getConfig()->getNested($player->getName() . ".island") != null) {
                    $event->setCancelled();
                }
            }
        }
    }
}