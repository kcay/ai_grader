<?php
// This file is part of Moodle - http://moodle.org/

/**
 * Admin settings for assignsubmission_aigrader
 *
 * @package   assignsubmission_aigrader
 * @copyright 2025 Your Name
 * @license   http://www.gnu.org/copyleft/gpl.html GNU GPL v3 or later
 */

defined('MOODLE_INTERNAL') || die();

if ($ADMIN->fulltree) {
    
    // Default enabled setting
    $settings->add(new admin_setting_configcheckbox(
        'assignsubmission_aigrader/default',
        get_string('default', 'assignsubmission_aigrader'),
        get_string('default_help', 'assignsubmission_aigrader'),
        0
    ));
    
    // Information about the main plugin
    $settings->add(new admin_setting_heading(
        'assignsubmission_aigrader/info',
        get_string('plugin_info', 'assignsubmission_aigrader'),
        get_string('plugin_info_desc', 'assignsubmission_aigrader')
    ));
}