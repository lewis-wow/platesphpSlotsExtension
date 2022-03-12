<?php        
        $templates->loadExtension(new class() implements League\Plates\Extension\ExtensionInterface {
            protected $engine;
            public $template; // must be public

            private $openSlots = [];
            private $slots = [];
            private $component = null;

            public function register($engine) {
                $this->engine = $engine;
                
                //components
                $engine->registerFunction('component', [$this, 'component']);
                $engine->registerFunction('endcomponent', [$this, 'endcomponent']);

                //slots
                $engine->registerFunction('startSlot', [$this, 'startSlot']);
                $engine->registerFunction('stopSlot', [$this, 'stopSlot']);
            }
        
            public function component($path, $data) {
                $this->component = [$path, $data];
        
                ob_start();
                ob_implicit_flush(0);
            }
        
            public function endcomponent() {   
                $defaultSlot = ob_get_clean();
                [$path, $data] = $this->component;
                $slots = $this->slots;
                
                $slotFunction = function($slotName = null) use($defaultSlot, $slots) {
                    if($slotName === null) return $defaultSlot;

                    if(array_key_exists($slotName, $slots)) {
                        return $slots[$slotName];
                    }

                    return null;
                };

                $result = $this->template->insert($path, array_merge($data, [
                    "slot" => $slotFunction
                ]));

                $this->slots = [];
                echo $result;
            }

            public function startSlot($slotName) {
                array_push($this->openSlots, $slotName);
                ob_start();
                ob_implicit_flush(0);
            }

            public function stopSlot() {
                $currentSlot = ob_get_clean();
                $slotName = array_pop($this->openSlots);
                $this->slots[$slotName] = $currentSlot;
            }

        });
