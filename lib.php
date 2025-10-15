<?php
// This file is part of Moodle - http://moodle.org/

/**
 * AI Grader assignment submission plugin
 *
 * @package   assignsubmission_aigrader
 * @copyright 2025 Your Name
 * @license   http://www.gnu.org/copyleft/gpl.html GNU GPL v3 or later
 */

defined('MOODLE_INTERNAL') || die();

require_once($CFG->dirroot . '/local/ai_autograder/lib.php');

/**
 * AI Grader submission plugin
 */
class assign_submission_aigrader extends assign_submission_plugin {

    /**
     * Get the name of the plugin
     *
     * @return string
     */
    public function get_name() {
        return get_string('pluginname', 'assignsubmission_aigrader');
    }

    /**
     * Get settings for the assignment form
     *
     * @param MoodleQuickForm $mform The form to add elements to
     * @return void
     */
    public function get_settings(MoodleQuickForm $mform) {
        global $CFG, $DB;

        // Get existing configuration
        $assignment_id = $this->assignment->get_instance()->id;
        $config = local_ai_autograder_get_config($assignment_id);
        
        if (!$config) {
            $config = new stdClass();
            $config->enabled = 0;
            $config->ai_provider = '';
            $config->ai_model = '';
            $config->leniency_level = '';
            $config->custom_prompt = '';
            $config->course_transcript = '';
        }

        // ========== Main Settings ==========
        $mform->addElement('header', 'aigrader_header', 
            get_string('aigrader_settings', 'assignsubmission_aigrader'));

        // Enable AI Auto-Grading
        $mform->addElement('selectyesno', 'assignsubmission_aigrader_enabled',
            get_string('enabled', 'assignsubmission_aigrader'));
        $mform->addHelpButton('assignsubmission_aigrader_enabled', 
            'enabled', 'assignsubmission_aigrader');
        $mform->setDefault('assignsubmission_aigrader_enabled', $config->enabled);

        // AI Provider Override
        $provider_options = [
            '' => get_string('use_default', 'assignsubmission_aigrader'),
            'openai' => 'OpenAI',
            'gemini' => 'Google Gemini',
            'claude' => 'Anthropic Claude'
        ];
        
        $mform->addElement('select', 'assignsubmission_aigrader_provider',
            get_string('ai_provider', 'assignsubmission_aigrader'),
            $provider_options);
        $mform->addHelpButton('assignsubmission_aigrader_provider',
            'ai_provider', 'assignsubmission_aigrader');
        $mform->setDefault('assignsubmission_aigrader_provider', $config->ai_provider);
        $mform->disabledIf('assignsubmission_aigrader_provider',
            'assignsubmission_aigrader_enabled', 'eq', 0);

        // AI Model Override (optional)
        $mform->addElement('text', 'assignsubmission_aigrader_model',
            get_string('ai_model', 'assignsubmission_aigrader'), 
            ['size' => 50]);
        $mform->setType('assignsubmission_aigrader_model', PARAM_TEXT);
        $mform->addHelpButton('assignsubmission_aigrader_model',
            'ai_model', 'assignsubmission_aigrader');
        $mform->setDefault('assignsubmission_aigrader_model', $config->ai_model);
        $mform->disabledIf('assignsubmission_aigrader_model',
            'assignsubmission_aigrader_enabled', 'eq', 0);

        // Leniency Level
        $leniency_options = [
            '' => get_string('use_default', 'assignsubmission_aigrader'),
            'very_lenient' => get_string('very_lenient', 'assignsubmission_aigrader') . ' (+10%)',
            'lenient' => get_string('lenient', 'assignsubmission_aigrader') . ' (+5%)',
            'moderate' => get_string('moderate', 'assignsubmission_aigrader') . ' (0%)',
            'strict' => get_string('strict', 'assignsubmission_aigrader') . ' (-5%)',
            'very_strict' => get_string('very_strict', 'assignsubmission_aigrader') . ' (-10%)'
        ];
        
        $mform->addElement('select', 'assignsubmission_aigrader_leniency',
            get_string('leniency_level', 'assignsubmission_aigrader'),
            $leniency_options);
        $mform->addHelpButton('assignsubmission_aigrader_leniency',
            'leniency_level', 'assignsubmission_aigrader');
        $mform->setDefault('assignsubmission_aigrader_leniency', $config->leniency_level);
        $mform->disabledIf('assignsubmission_aigrader_leniency',
            'assignsubmission_aigrader_enabled', 'eq', 0);

        // ========== Course Context ==========
        $mform->addElement('header', 'aigrader_context_header',
            get_string('course_context', 'assignsubmission_aigrader'));

        // Course Transcript (Text)
        $mform->addElement('textarea', 'assignsubmission_aigrader_transcript',
            get_string('course_transcript', 'assignsubmission_aigrader'),
            ['rows' => 12, 'cols' => 80]);
        $mform->setType('assignsubmission_aigrader_transcript', PARAM_TEXT);
        $mform->addHelpButton('assignsubmission_aigrader_transcript',
            'course_transcript', 'assignsubmission_aigrader');
        $mform->setDefault('assignsubmission_aigrader_transcript', $config->course_transcript);
        $mform->disabledIf('assignsubmission_aigrader_transcript',
            'assignsubmission_aigrader_enabled', 'eq', 0);

        // Course Transcript (File Upload)
        $mform->addElement('filemanager', 'assignsubmission_aigrader_transcript_file',
            get_string('transcript_file', 'assignsubmission_aigrader'),
            null,
            [
                'subdirs' => 0,
                'maxbytes' => 10485760, // 10MB
                'maxfiles' => 1,
                'accepted_types' => ['.pdf', '.docx', '.doc', '.txt', '.odt']
            ]);
        $mform->addHelpButton('assignsubmission_aigrader_transcript_file',
            'transcript_file', 'assignsubmission_aigrader');
        $mform->disabledIf('assignsubmission_aigrader_transcript_file',
            'assignsubmission_aigrader_enabled', 'eq', 0);

        // Note about text vs file
        $mform->addElement('static', 'transcript_note', '',
            '<div class="alert alert-info">' . 
            get_string('transcript_note', 'assignsubmission_aigrader') . 
            '</div>');

        // ========== Grading Instructions ==========
        $mform->addElement('header', 'aigrader_instructions_header',
            get_string('grading_instructions', 'assignsubmission_aigrader'));

        // Custom AI Prompt
        $mform->addElement('editor', 'assignsubmission_aigrader_prompt',
            get_string('custom_prompt', 'assignsubmission_aigrader'),
            ['rows' => 10],
            ['maxfiles' => 0, 'noclean' => true]);
        $mform->setType('assignsubmission_aigrader_prompt', PARAM_RAW);
        $mform->addHelpButton('assignsubmission_aigrader_prompt',
            'custom_prompt', 'assignsubmission_aigrader');
        
        if (!empty($config->custom_prompt)) {
            $mform->setDefault('assignsubmission_aigrader_prompt', [
                'text' => $config->custom_prompt,
                'format' => FORMAT_HTML
            ]);
        }
        $mform->disabledIf('assignsubmission_aigrader_prompt',
            'assignsubmission_aigrader_enabled', 'eq', 0);

        // Reference Document Upload
        $mform->addElement('filemanager', 'assignsubmission_aigrader_reference',
            get_string('reference_document', 'assignsubmission_aigrader'),
            null,
            [
                'subdirs' => 0,
                'maxbytes' => 10485760, // 10MB
                'maxfiles' => 1,
                'accepted_types' => ['.pdf', '.docx', '.doc', '.txt']
            ]);
        $mform->addHelpButton('assignsubmission_aigrader_reference',
            'reference_document', 'assignsubmission_aigrader');
        $mform->disabledIf('assignsubmission_aigrader_reference',
            'assignsubmission_aigrader_enabled', 'eq', 0);

        // Validation note
        $mform->addElement('static', 'validation_note', '',
            '<div class="alert alert-warning">' . 
            get_string('validation_note', 'assignsubmission_aigrader') . 
            '</div>');

        // Prepare file areas
        if ($config && isset($config->transcript_file) && $config->transcript_file > 0) {
            $draftitemid = file_get_submitted_draft_itemid('assignsubmission_aigrader_transcript_file');
            file_prepare_draft_area(
                $draftitemid,
                $this->assignment->get_context()->id,
                'local_ai_autograder',
                'transcript',
                $assignment_id
            );
            $mform->setDefault('assignsubmission_aigrader_transcript_file', $draftitemid);
        }

        if ($config && isset($config->reference_file) && $config->reference_file > 0) {
            $draftitemid = file_get_submitted_draft_itemid('assignsubmission_aigrader_reference');
            file_prepare_draft_area(
                $draftitemid,
                $this->assignment->get_context()->id,
                'local_ai_autograder',
                'reference',
                $assignment_id
            );
            $mform->setDefault('assignsubmission_aigrader_reference', $draftitemid);
        }
    }

    /**
     * Save the settings
     *
     * @param stdClass $data
     * @return bool
     */
    public function save_settings(stdClass $data) {
        global $DB;

        $assignment_id = $this->assignment->get_instance()->id;
        
        // Get or create config record
        $config = local_ai_autograder_get_config($assignment_id);
        
        if (!$config) {
            $config = new stdClass();
            $config->assignmentid = $assignment_id;
            $config->timecreated = time();
        }

        // Update settings
        $config->enabled = isset($data->assignsubmission_aigrader_enabled) ? 
            $data->assignsubmission_aigrader_enabled : 0;
        $config->ai_provider = $data->assignsubmission_aigrader_provider ?? '';
        $config->ai_model = $data->assignsubmission_aigrader_model ?? '';
        $config->leniency_level = $data->assignsubmission_aigrader_leniency ?? '';
        $config->course_transcript = $data->assignsubmission_aigrader_transcript ?? '';
        
        // Save custom prompt
        if (isset($data->assignsubmission_aigrader_prompt['text'])) {
            $config->custom_prompt = $data->assignsubmission_aigrader_prompt['text'];
        }

        // Save transcript file
        if (isset($data->assignsubmission_aigrader_transcript_file)) {
            $context = $this->assignment->get_context();
            $draftitemid = $data->assignsubmission_aigrader_transcript_file;
            
            file_save_draft_area_files(
                $draftitemid,
                $context->id,
                'local_ai_autograder',
                'transcript',
                $assignment_id,
                [
                    'subdirs' => 0,
                    'maxbytes' => 10485760,
                    'maxfiles' => 1
                ]
            );
        }

        // Save reference file
        if (isset($data->assignsubmission_aigrader_reference)) {
            $context = $this->assignment->get_context();
            $draftitemid = $data->assignsubmission_aigrader_reference;
            
            file_save_draft_area_files(
                $draftitemid,
                $context->id,
                'local_ai_autograder',
                'reference',
                $assignment_id,
                [
                    'subdirs' => 0,
                    'maxbytes' => 10485760,
                    'maxfiles' => 1
                ]
            );
        }

        $config->timemodified = time();

        // Save to database
        return local_ai_autograder_save_config($config);
    }

    /**
     * Check if the plugin has been configured
     *
     * @return bool
     */
    public function is_enabled() {
        $config = local_ai_autograder_get_config($this->assignment->get_instance()->id);
        return $config && $config->enabled;
    }

    /**
     * Automatically enable this plugin
     *
     * @return bool
     */
    public function is_configurable() {
        return true;
    }

    /**
     * The assignment has been deleted - cleanup
     *
     * @return bool
     */
    public function delete_instance() {
        global $DB;
        
        $assignment_id = $this->assignment->get_instance()->id;
        
        // Delete configuration
        $DB->delete_records('local_ai_autograder_config', ['assignmentid' => $assignment_id]);
        
        // Delete files
        $fs = get_file_storage();
        $context = $this->assignment->get_context();
        
        $fs->delete_area_files($context->id, 'local_ai_autograder', 'transcript', $assignment_id);
        $fs->delete_area_files($context->id, 'local_ai_autograder', 'reference', $assignment_id);
        
        return true;
    }

    /**
     * No submission data for this plugin
     *
     * @param stdClass $submission
     * @return bool
     */
    public function is_empty(stdClass $submission) {
        // This plugin doesn't store submission data
        return true;
    }

    /**
     * Get file areas for this plugin
     *
     * @return array
     */
    public function get_file_areas() {
        return [
            'transcript' => get_string('transcript_file', 'assignsubmission_aigrader'),
            'reference' => get_string('reference_document', 'assignsubmission_aigrader')
        ];
    }

    /**
     * Copy settings from another assignment instance
     *
     * @param assign $oldassignment
     * @return bool
     */
    public function copy_submission(stdClass $oldassignment, stdClass $newassignment) {
        global $DB;
        
        // Copy configuration
        $oldconfig = local_ai_autograder_get_config($oldassignment->id);
        
        if ($oldconfig) {
            $newconfig = clone($oldconfig);
            $newconfig->id = null;
            $newconfig->assignmentid = $newassignment->id;
            $newconfig->timecreated = time();
            $newconfig->timemodified = time();
            
            local_ai_autograder_save_config($newconfig);
            
            // Copy files
            $fs = get_file_storage();
            $oldcontext = context_module::instance($oldassignment->cmid);
            $newcontext = context_module::instance($newassignment->cmid);
            
            // Copy transcript files
            $files = $fs->get_area_files(
                $oldcontext->id,
                'local_ai_autograder',
                'transcript',
                $oldassignment->id,
                'id',
                false
            );
            
            foreach ($files as $file) {
                $filerecord = [
                    'contextid' => $newcontext->id,
                    'itemid' => $newassignment->id
                ];
                $fs->create_file_from_storedfile($filerecord, $file);
            }
            
            // Copy reference files
            $files = $fs->get_area_files(
                $oldcontext->id,
                'local_ai_autograder',
                'reference',
                $oldassignment->id,
                'id',
                false
            );
            
            foreach ($files as $file) {
                $filerecord = [
                    'contextid' => $newcontext->id,
                    'itemid' => $newassignment->id
                ];
                $fs->create_file_from_storedfile($filerecord, $file);
            }
        }
        
        return true;
    }
}