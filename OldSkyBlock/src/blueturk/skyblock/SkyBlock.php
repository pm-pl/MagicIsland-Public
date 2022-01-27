<?php /** @noinspection ALL */

namespace blueturk\skyblock;

use blueturk\skyblock\listener\IslandListener;
use blueturk\skyblock\manager\CommandHandler;
use blueturk\skyblock\managers\CommandManager;
use blueturk\skyblock\provider\YAMLProvider;
use blueturk\skyblock\tasks\SpawnerSpawnTask;
use pocketmine\{utils\Config};
use pocketmine\plugin\PluginBase;
use pocketmine\utils\MainLogger;

class SkyBlock extends PluginBase
{

    /**
     * @var SkyBlock
     */
    protected static $api;

    /**
     * @var string
     */
    public const BT_TITLE = "§7SkyBlock §7- §8";

    public static $weathers = [];
    /**
     * @var string
     */
    public const BT_MARK = "§8» §";

    /** @var Config */
    private $spawners;

    /** @var Config */
    private $warps;

    public function onEnable()
    {
        self::$api = $this;
        CommandManager::loadCommands();
        self::$api->getServer()->getPluginManager()->registerEvents(new IslandListener(), self::$api);
        $this->spawners = new Config($this->getDataFolder() . "spawners.yaml", Config::YAML);
        $this->warps = new Config($this->getDataFolder() . "warps.yml", Config::YAML);
        $this->getScheduler()->scheduleRepeatingTask(new SpawnerSpawnTask(), 20 * 40);
        MainLogger::getLogger()->notice(sprintf("%s commands loaded!", CommandManager::commandsCount()));
    }

    public function onDisable()
    {
        self::$api->saveConfig();
        $this->getSpawners()->save();
        $this->getWarps()->save();
    }

    /**
     * @return Config
     */
    public function getSpawners(): Config
    {
        return $this->spawners;
    }

    public function getWarps(): Config
    {
        return $this->warps;
    }
    /**
     * @return mixed
     */
    public static function getInstance(): SkyBlock
    {
        return self::$api;
    }
}