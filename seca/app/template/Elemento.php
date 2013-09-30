<?php

    class Elemento {

        private $name;
        private $properties;
        private $children;

    public function Elemento($name) {
        $this->name = $name;
    }

    public function __set($name, $value) {
        $this->properties[$name] = $value;
    }

    public function setProperty($name, $value) {
        $this->properties[$name] = $value;
    }
   
    public function getProperty() {
            return $this->properties[$name];
    }
   
    public function getProperties() {
            return $this->properties;
    }
    
    public function add($child) {
        $this->children[] = $child;
    }
    
    private function open() {
        echo "<{$this->name}";
        
		if ($this->properties) {
            foreach ($this->properties as $name=>$value) {
                echo " {$name}=\"{$value}\"";
            }
        }
        
		echo ">";
    }
    
    public function show() {
        $this->open();
        echo "\n";
        
		if ($this->children) {
            foreach ($this->children as $child) {
                if (is_object($child)) {
                    $child->show();
                } elseif ((is_string($child)) or (is_numeric($child))) {
                    echo $child;
                }
            }
            $this->close();
       	 	return;
		}
        
		$this->close();
    }
    
    private function close() {
        echo "</{$this->name}>\n";
    }
 
}
?>