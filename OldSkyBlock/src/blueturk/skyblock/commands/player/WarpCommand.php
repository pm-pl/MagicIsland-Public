<?php


namespace blueturk\skyblock\commands\player;

use blueturk\skyblock\forms\warp\WarpMainForm;
use pocketmine\command\Command;
use pocketmine\command\CommandSender;
use pocketmine\Player;

class WarpCommand extends Command
{
    public function __construct()
    {
        parent::__construct("mevki", "Warp komutu.", "/mevki");
    }

    public function execute(CommandSender $sender, string $commandLabel, array $args)
    {
        if ($sender instanceof Player){
            $sender->sendForm(new WarpMainForm($sender));
        }
    }
}