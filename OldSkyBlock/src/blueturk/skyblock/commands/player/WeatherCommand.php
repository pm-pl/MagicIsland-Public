<?php


namespace blueturk\skyblock\commands\player;


use blueturk\skyblock\forms\island\WeatherSettingsForm;
use pocketmine\command\Command;
use pocketmine\command\CommandSender;
use pocketmine\Player;

class WeatherCommand extends Command
{

    public function __construct()
    {
        parent::__construct("weather", "Adanın hava durumunu değiştir!", "/weather", ["havadurumu"]);
        $this->setPermission("weather.command.bt");
        $this->setPermissionMessage("§8» §7Bu komut sadece VIP kullanıcılara özeldir!");
    }

    public function execute(CommandSender $sender, string $commandLabel, array $args)
    {
        if ($sender instanceof Player){
            if ($sender->hasPermission("weather.command.bt")){
                $sender->sendForm(new WeatherSettingsForm());
            }else{
                $sender->sendMessage($this->getPermissionMessage());
            }
        }
    }
}