<?php

namespace silawrenc\Yocto;

class Yocto {

    protected $executables;
    protected $serviceResolver;

    public function __construct(Callable $serviceResolver)
    {
        $this->serviceResolver = $serviceResolver;
    }

    public function add(Callable $executable)
    {
        $this->executables[] = $executable;
        return $this;
    }

    public function get($name)
    {
        return call_user_func_array($this->serviceResolver, func_get_args());
    }

    public function run()
    {
        foreach ($this->executables as $executable) {
            $return = call_user_func($executable, $this);
            if ($return === false) {
                    return;
            }
        }
    }
}
