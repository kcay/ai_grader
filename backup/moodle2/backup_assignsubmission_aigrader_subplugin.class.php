<?php
// ==============================================================================
// FILE: backup/moodle2/backup_assignsubmission_aigrader_subplugin.class.php
// ==============================================================================

/**
 * Backup subplugin for assignsubmission_aigrader
 *
 * @package   assignsubmission_aigrader
 * @copyright 2025 Your Name
 * @license   http://www.gnu.org/copyleft/gpl.html GNU GPL v3 or later
 */

defined('MOODLE_INTERNAL') || die();

/**
 * Backup class for AI grader submission plugin
 */
class backup_assignsubmission_aigrader_subplugin extends backup_assignsubmission_subplugin {

    /**
     * Define the structure of the backup
     *
     * @return backup_subplugin_element
     */
    protected function define_submission_subplugin_structure() {
        
        // Create the wrapper element
        $subplugin = $this->get_subplugin_element();
        
        // Create a wrapper for the settings
        $subpluginwrapper = new backup_nested_element($this->get_recommended_name());
        
        // Define the config element
        $config = new backup_nested_element('aigrader_config', ['id'], [
            'assignmentid',
            'enabled',
            'ai_provider',
            'ai_model',
            'leniency_level',
            'custom_prompt',
            'course_transcript',
            'timecreated',
            'timemodified'
        ]);
        
        // Build the tree
        $subplugin->add_child($subpluginwrapper);
        $subpluginwrapper->add_child($config);
        
        // Set the source for config
        $config->set_source_table('local_ai_autograder_config', [
            'assignmentid' => backup::VAR_PARENTID
        ]);
        
        // Annotate files
        $config->annotate_files('local_ai_autograder', 'transcript', null);
        $config->annotate_files('local_ai_autograder', 'reference', null);
        
        return $subplugin;
    }
}