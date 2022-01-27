<?php /** @noinspection ALL */

namespace blueturk\skyblock\commands\admin;

use pocketmine\block\BlockIds;
use pocketmine\command\Command;
use pocketmine\command\CommandSender;
use pocketmine\item\Item;
use pocketmine\Player;

class SpawnerGiveCommand extends Command
{

    public function __construct()
    {
        parent::__construct("spg", "admin commands", "/spg player type");
        $this->setPermission("spawner.give.command");
    }

    public function execute(CommandSender $sender, string $commandLabel, array $args)
    {
        if ($sender->hasPermission("spawner.give.command")) {
            $spawners = ["Cow", "Chicken", "Sheep", "Pig", "Horse"];
            if (isset($args[0]) and $args[1] and in_array($args[1], $spawners)) {
                $player = $sender->getServer()->getPlayerExact($args[0]);
                if ($player instanceof Player) {
                    $item = Item::get(BlockIds::MONSTER_SPAWNER);
                    if ($player->getInventory()->canAddItem($item)) {
                        $item->setCustomName("§b" . $args[1] . " Spawner");
                        $item->getNamedTag()->setString("type", $args[1]);
                        $player->getInventory()->addItem($item);
                        $player->sendMessage("§8» §aEnvanterine " . $item->getCustomName() . "§a eklendi!");
                        $sender->sendMessage("§8» §aSpawner verildi!");
                    } else $sender->sendMessage("§8» §cOyuncunun envanteri dolu!");

                } else $sender->sendMessage("§8» §cOyuncu aktif değil!");

            } else $sender->sendMessage("§8» §7Kullanım: §f" . $this->getUsage());

        } else $sender->sendMessage("§8» §cGerekli yetki bulunamadı!");
    }
}