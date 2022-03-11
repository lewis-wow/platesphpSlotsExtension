        $templates->loadExtension(new class() implements League\Plates\Extension\ExtensionInterface {
            protected $engine;
            public $template; // must be public
        
            public function register($engine) {
                $this->engine = $engine;
                
                //components
                $engine->registerFunction('component', [$this, 'component']);
                $engine->registerFunction('endcomponent', [$this, 'endcomponent']);

                //slots
                $engine->registerFunction('startSlot', [$this, 'startSlot']);
                $engine->registerFunction('stopSlot', [$this, 'stopSlot']);
                $engine->registerFunction('slot', [$this, 'useSlot']);
                $engine->registerFunction('hasSlot', [$this, 'hasSlot']);
            }
        
            public function component($path, $data) {
                $this->component = [$path, $data];
                $this->slots = [];
                $this->defaultSlot = "";
        
                ob_start();
                ob_implicit_flush(0);
            }
        
            public function endcomponent() {        
                $this->defaultSlot = ob_get_clean();
                [$path, $data] = $this->component;
                list($path, $data) = $this->component;
        
                $result = $this->template->insert($path, $data);
                
                $this->openSlot = "";
                $this->componentData = [];
                $this->slots = [];
                $this->defaultSlot = "";

                echo $result;
            }

            public function startSlot($slotName) {
                $this->openSlot = $slotName;
                ob_start();
                ob_implicit_flush(0);
            }

            public function stopSlot() {
                $currentSlot = ob_get_clean();
                $this->slots[$this->openSlot] = $currentSlot;
                $this->openSlot = "";
            }
        
            public function useSlot($slotName = null) {
                return $slotName === null ? $this->defaultSlot : $this->slots[$slotName];
            }

            public function hasSlot($slotName = null) {
                if($slotName === null) return $this->defaultSlot !== "";
                return array_key_exists($slotName, $this->slots);
            }
        });
