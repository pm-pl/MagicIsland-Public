<?php /** @noinspection ALL */

namespace blueturk\skyblock\forms\spawner;

use blueturk\skyblock\SkyBlock;
use blueturk\skyblock\tasks\SpawnerSpawnTask;
use dktapps\pmforms\MenuForm;
use dktapps\pmforms\MenuOption;
use onebone\economyapi\EconomyAPI;
use pocketmine\block\Block;
use pocketmine\entity\Entity;
use pocketmine\Player;

class SpawnerStatusForm extends MenuForm
{
    /**
     * @var int[]
     */
    public $array = [
        "level-2" => 75000,
        "level-3" => 125000,
        "deliver-spawner" => 12500
    ];

    /**
     * SpawnerStatusForm constructor.
     * @param Player $player
     * @param Block $block
     * @param string $type
     * @param int $level
     */
    public function __construct(Player $player, Block $block, string $type, int $level)
    {
        if ($level < 3) $nextLevel = $level + 1; else $nextLevel = "Max";
        parent::__construct($type . " Spawner",
            "§5Spawner Sahibi: §d" . $player->getName() . "\n§5Spawner Seviyesi: §d" . $level . "\n§5Sonraki Seviye: §d" . $nextLevel . "\n§5Doğma Süresi: §d" . date("i:s", SpawnerSpawnTask::$time) . "\n",
            [
                new MenuOption("Seviye Atlat"),
                new MenuOption("Şimdi Spawn Et\n" . $this->array["deliver-spawner"] . " BT Gerekli"),
            ], function (Player $player, int $option) use ($block, $type, $level, $nextLevel): void {
                if ($option === 0) {
                    if ($nextLevel === "Max") {
                        $player->sendMessage(SkyBlock::BT_MARK . "cSpawner en yüksek seviyede!");
                        return;
                    }
                    $player->sendForm(new SpawnerUpgradeConfirmForm($player, $block, $type, $nextLevel));
                } elseif ($option === 1) {
                    $this->deliverSpawner($player, $block, $type);
                }
            }
        );
    }


    /**
     * @param Player $player
     * @param Block $block
     * @param string $type
     */
    private function deliverSpawner(Player $player, Block $block, string $type)
    {
        if (EconomyAPI::getInstance()->myMoney($player) >= $this->array["deliver-spawner"]) {
            $entity = Entity::createEntity($type, $block->getLevel(), Entity::createBaseNBT($block->asPosition()));
            $entity->setNameTag("§7x1 " . $type);
            $entity->setNameTagVisible();
            $entity->spawnToAll();
            $player->sendMessage(SkyBlock::BT_MARK . "aAnında spawn edildi!");
            EconomyAPI::getInstance()->reduceMoney($player, $this->array["deliver-spawner"]);
        } else {
            $player->sendMessage(SkyBlock::BT_MARK . "cYeterli paran yok!");
        }
    }

}