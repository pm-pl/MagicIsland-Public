<?php /** @noinspection ALL */

namespace blueturk\skyblock\forms\spawner;

use blueturk\skyblock\SkyBlock;
use dktapps\pmforms\ModalForm;
use onebone\economyapi\EconomyAPI;
use pocketmine\block\Block;
use pocketmine\math\Vector3;
use pocketmine\Player;

class SpawnerUpgradeConfirmForm extends ModalForm
{

    /**
     * @var int[]
     */
    public $array = [
        "level-2" => 75000,
        "level-3" => 125000,
        "deliver-spawner" => 12500
    ];

    public function __construct(Player $player, Block $block, string $type, int $nextLevel)
    {
        $money = EconomyAPI::getInstance()->myMoney($player);
        parent::__construct($type . " Spawner - " . ($nextLevel - 1) . " Seviye", "§5Sonraki Seviye: §d" . $nextLevel . "\n\n§5Mob Oranı: §dx" . $nextLevel . "\n\n§5Gereken Ücret: §d" . $this->array["level-" . $nextLevel] . " BT\n\n§5Paran: §d" . $money . " BT",
            function (Player $player, bool $choice) use ($block, $type, $nextLevel): void {
                if ($choice === true) {
                    $this->spawnerUpgradeConfirm($player, $block, $type, $nextLevel);
                }
            },
            "Yükseltme Yap",
            "Vazgeç"
        );
    }

    /**
     * @param Player $player
     * @param Block $block
     * @param string $type
     * @param int $nextLevel
     */
    private function spawnerUpgradeConfirm(Player $player, Block $block, string $type, int $nextLevel)
    {
        $price = $this->array["level-" . $nextLevel];
        if (EconomyAPI::getInstance()->myMoney($player) >= $price) {
            $array = SkyBlock::getInstance()->getSpawners()->get($block->getLevel()->getFolderName());
            if (is_array($array)) {
                foreach ($array as $str) {
                    $str2 = explode(":", $str);
                    $pos = new Vector3((int)$str2[0], (int)$str2[1], (int)$str2[2]);
                    if ($pos->equals($block)) {
                        unset($array[array_search($str, $array)]);
                        $newSpawner = $block->getX() . ":" . $block->getY() . ":" . $block->getZ() . ":" . $type . ":" . $player->getName() . ":" . $nextLevel;
                        array_push($array, $newSpawner);
                        SkyBlock::getInstance()->getSpawners()->set($block->getLevel()->getFolderName(), $array);
                        $player->sendMessage(SkyBlock::BT_MARK . "aSpawner seviyesi yükseltildi!");
                    }
                }
            }
            EconomyAPI::getInstance()->reduceMoney($player, $price);
        }
    }
}