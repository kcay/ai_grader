<?php
// ==============================================================================
// FILE: backup/moodle2/restore_assignsubmission_aigrader_subplugin.class.php
// ==============================================================================

/**
 * Restore subplugin for assignsubmission_aigrader
 *
 * @package   assignsubmission_aigrader
 * @copyright 2025 Your Name
 * @license   http://www.gnu.org/copyleft/gpl.html GNU GPL v3 or later
 */

defined('MOODLE_INTERNAL') || die();

/**
 * Restore class for AI grader submission plugin
 */
class restore_assignsubmission_aigrader_subplugin extends restore_assignsubmission_subplugin {

    /**
     * Define the structure of the restore
     *
     * @return array
     */
    protected function define_submission_subplugin_structure() {
        
        $paths = [];
        
        // Define path for config
        $elename = $this->get_namefor('config');
        $elepath = $this->get_pathfor('/aigrader_config');
        $paths[] = new restore_path_element($elename, $elepath);
        
        return $paths;
    }

    /**
     * Process the config element
     *
     * @param array $data
     */
    public function process_assignsubmission_aigrader_config($data) {
        global $DB;
        
        $data = (object)$data;
        
        // Get the new assignment id
        $data->assignmentid = $this->task->get_activityid();
        
        // Update timestamps
        $data->timecreated = time();
        $data->timemodified = time();
        
        // Insert the config
        $newitemid = $DB->insert_record('local_ai_autograder_config', $data);
        
        // Store the mapping
        $this->set_mapping('assignsubmission_aigrader_config', $data->id, $newitemid);
    }

    /**
     * After execution tasks
     */
    public function after_execute_assignment() {
        // Add related files
        $this->add_related_files('local_ai_autograder', 'transcript', null);
        $this->add_related_files('local_ai_autograder', 'reference', null);
    }
}