<?php
// This file is part of Moodle - http://moodle.org/
//
// Moodle is free software: you can redistribute it and/or modify
// it under the terms of the GNU General Public License as published by
// the Free Software Foundation, either version 3 of the License, or
// (at your option) any later version.

/**
 * AI Grader assignment submission plugin version information
 *
 * @package   assignsubmission_aigrader
 * @copyright 2025 Your Name
 * @license   http://www.gnu.org/copyleft/gpl.html GNU GPL v3 or later
 */

defined('MOODLE_INTERNAL') || die();

$plugin->version   = 2025101506;
$plugin->requires  = 2022112800; // Moodle 4.1
$plugin->component = 'assignsubmission_aigrader';
$plugin->maturity  = MATURITY_STABLE;
$plugin->release   = 'v1.0.0';
$plugin->dependencies = [
    'local_ai_autograder' => 2025101500  // Requires the main AI grader plugin
];