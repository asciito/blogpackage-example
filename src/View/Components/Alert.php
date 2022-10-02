<?php

namespace asciito\BlogPackage\View\Components;

class Alert extends \Illuminate\View\Component
{
    public $message;

    public function __construct($message)
    {
        $this->message = $message;
    }

    /**
     * @inheritDoc
     */
    public function render()
    {
        return view('blogpackage::components.alert');
    }
}