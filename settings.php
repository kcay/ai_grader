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

// Add strings for settings
$string['default'] = 'Enabled by default';
$string['default_help'] = 'If enabled, AI auto-grading will be enabled by default for all new assignments. Teachers can override this setting for individual assignments.';
$string['plugin_info'] = 'Plugin Information';
$string['plugin_info_desc'] = 'This submission plugin integrates with the <strong>AI Auto-Grader</strong> local plugin to provide AI-powered automatic grading. 
<br><br>
<strong>Configuration:</strong>
<ul>
<li>Global AI settings (API keys, default models) are configured in: <a href="../../../admin/settings.php?section=local_ai_autograder">Site Administration → Plugins → Local plugins → AI Auto-Grader</a></li>
<li>Assignment-specific settings appear in the assignment settings form when this plugin is enabled</li>
</ul>
<br>
<strong>Required:</strong> The <code>local_ai_autograder</code> plugin must be installed for this to work.';