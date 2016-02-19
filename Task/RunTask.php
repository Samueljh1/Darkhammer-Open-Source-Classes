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
 * A Simple Task Class that Accepts a Closure as a Param.
 * Usage:
 *
 * $server->getScheduler->scheduleDelayedTask(new RunTask(function ($arg1, $arg2...) {
 *      echo $arg1 . " " . $arg2;
 * }, $arg1, $arg2...), 20); //Delay
 *
 *
 */

namespace Samueljh1\DarkHammer\DHCore\Task;

use pocketmine\scheduler\Task;

class RunTask extends Task {

    private $function, $data;

    function __construct(\Closure $function) {

        $this->function = $function;
        $this->data = func_get_args();

        //This block can be removed; It is only here to add support for another class
        if(count($this->data) === 2 && is_array($this->data[1])) {
            $this->data = $this->data[1];
        }

        array_shift($this->data);

    }

    public function onRun($currentTick){
        self::execute();
    }

    protected function execute() {
        call_user_func_array($this->function, $this->data); //Converts Array to Func Args
    }

}