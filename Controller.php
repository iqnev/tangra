<?php
/**
 * Created by PhpStorm.
 * User: iqnev
 * Date: 9/9/14
 * Time: 10:22 PM
 */

namespace TG;


class Controller {

    /**
     * @var \TG\App
     */
    public $app;
    /**
     * @var \TG\View
     */
    public $view;
    /**
     * @var \TG\Config
     */
    public $config;
    /**
     * @var \TG\Input
     */
    public $input;

    public function __construct()
    {
        $this->app = \TG\App::getInstance();
        $this->config = $this->app->getConfig();
        $this->view = \TG\View::getInstance();
        //$this->input = \TG\Input::getInstance();
    }
} 