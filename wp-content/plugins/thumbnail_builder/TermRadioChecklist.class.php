<?php
/**
 * Custom walker for switching checkbox inputs to radio.
 *
 * @see Walker_Category_Checklist
 */
class TermRadioChecklistClass extends Walker_Category_Checklist {
    function walk( $elements, $max_depth, $args = array() ) {
        var_dump($args);exit;
        $output = parent::walk( $elements, $max_depth, $args );
        $output = str_replace(
            array( 'type="checkbox"', "type='checkbox'" ),
            array( 'type="radio"', "type='radio'" ),
            $output
        );

        return $output;
    }
}