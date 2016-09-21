<?php 
namespace Ry;

use Philo\Blade\Blade;

class View
{
    private $blade, $content;
    
    public function __construct($view, $data) {
        $views = __DIR__ . '/resources/views';
        $cache = __DIR__ . '/storage/cache/views';
        
        $this->blade = new Blade($views, $cache);
        $this->content = $this->blade->view()->make($view, $data);
    }
    
    public static function make($view, $data=array()) {
        return new View($view, $data);
    }
    
    public function render($section = null) {
        if($section) { 
            $sections = $this->content->renderSections();           
            if(!isset($sections[$section])) {
                echo "Bloc $section Non Existant";
            }
            else {
                echo $sections[$section];
            }
        }
        else
            echo $this->content->render;
    }
}
