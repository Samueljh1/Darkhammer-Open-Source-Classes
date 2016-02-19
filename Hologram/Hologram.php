<?php

/*
 *
 * ______           _    _
 * |  _  \         | |  | |
 * | | | |__ _ _ __| | _| |__   __ _ _ __ ___  _ __ ___   ___ _ __
 * | | | / _` | '__| |/ / '_ \ / _` | '_ ` _ \| '_ ` _ \ / _ \ '__|
 * | |/ / (_| | |  |   <| | | | (_| | | | | | | | | | | |  __/ |
 * |___/ \__,_|_|  |_|\_\_| |_|\__,_|_| |_| |_|_| |_| |_|\___|_|
 *
 *
 * Created for the Darkhammer Network.
 *
 * @author Samueljh1
 * @link http://www.samueljh1.net/
 *
 *
 */

namespace Samueljh1\DarkHammer\DHCore\Hologram;

use pocketmine\plugin\Plugin;

use pocketmine\Player;

use pocketmine\level\Level;
use pocketmine\level\particle\FloatingTextParticle;

use pocketmine\math\Vector3;

class Hologram extends FloatingTextParticle {

    private $plugin;

    public static $holograms = [];

    protected $level;
    protected $position;

    public $hologram;

    protected $_title;
    protected $_text;
    protected $_invisible;

    function __construct (Plugin $plugin, Level $level, Vector3 $position, $title, $text) {

        parent::__construct($position, $text, $title);

        $this->plugin = $plugin;

        $this->level = $level;
        $this->position = $position;

        $this->_title = $title;
        $this->_text = $text;

        if($title instanceof \Closure) $title = call_user_func($title);
        if($text instanceof \Closure) $text = call_user_func($text);

        $this->hologram = new FloatingTextParticle($position, $text . "§r", $title . "§r");
        array_push(self::$holograms, $this);

    }

    public function setText($text, Player $player = null) {
        $this->_text = $text;
        $this->update($player);
    }

    public function setTitle($title, Player $player = null) {
        $this->_title = $title;
        $this->update($player);
    }

    public function isInvisible() {
        return $this->_invisible;
    }

    public function setInvisible($value = \true, Player $player = null) {
        $this->_invisible = (bool) $value;
        $this->update($player);
    }

    public function getTitle() {
        if($this->_title instanceof \Closure) return call_user_func($this->_title);
        return $this->_title;
    }

    public function getText() {
        if($this->_text instanceof \Closure) return call_user_func($this->_text);
        return $this->_text;
    }

    public function update(Player $player = null) {

        $this->hologram->setText($this->getText() . "§r");
        $this->hologram->setTitle($this->getTitle() . "§r");
        $this->hologram->setInvisible($this->_invisible);

        if($player === null) $this->showToAll();
        else {
            $this->_invisible = false; //so new players can see hologram
            $this->showToPlayer($player);
        }

    }

    public function showToAll() {
        $this->level->addParticle($this->hologram, $this->level->getPlayers());
    }

    public function showToPlayer(Player $player) {
        $this->level->addParticle($this->hologram, [$player]);
    }

    public function getLevel() {
        return $this->level;
    }

}