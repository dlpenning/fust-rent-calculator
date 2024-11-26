<?php

namespace FUST\Calculator;

use WASP\Util\Map;

/**
 * FUST Rent Calculator Logic
 * 
 * @since 0.1.0
 * 
 */


/**
 * Represents a loaded calculator: see static method load() for the loading mechanism from the .json -> the Calculator class
 */
class Calculator {

    /**
     * Holds an associative array of questions mapping id => Question.
     */
    public array $questions = [];
    
    static function load(array $raw) : Calculator {
        return new Calculator;
    }
    
}

abstract class Question {

    abstract function render();

    abstract function save();

}

class NumericQuestion extends Question {
    function render() {
        
    }

    function save() {

    }
}

class SingleOptionQuestion extends Question {
    function render() {

    }

    function save() {

    }
}
class MultiOptionQuestion extends Question {
    function render() {

    }

    function save() {

    }
}

