<?php
// This file is part of Moodle - http://moodle.org/

/**
 * The assignsubmission_aigrader submission_graded event.
 *
 * @package   assignsubmission_aigrader
 * @copyright 2025 Your Name
 * @license   http://www.gnu.org/copyleft/gpl.html GNU GPL v3 or later
 */

namespace assignsubmission_aigrader\event;

defined('MOODLE_INTERNAL') || die();

/**
 * The assignsubmission_aigrader submission_graded event class.
 *
 * @property-read array $other {
 *      Extra information about the event.
 *
 *      - int submissionid: The submission ID that was graded.
 *      - float grade: The grade assigned by AI.
 *      - string ai_provider: The AI provider used for grading.
 * }
 *
 * @package   assignsubmission_aigrader
 * @copyright 2025 Your Name
 * @license   http://www.gnu.org/copyleft/gpl.html GNU GPL v3 or later
 */
class submission_graded extends \core\event\base {

    /**
     * Init method.
     */
    protected function init() {
        $this->data['objecttable'] = 'assign_submission';
        $this->data['crud'] = 'u';
        $this->data['edulevel'] = self::LEVEL_TEACHING;
    }

    /**
     * Returns description of what happened.
     *
     * @return string
     */
    public function get_description() {
        $descriptionstring = "The submission with id '{$this->other['submissionid']}' was automatically graded " .
            "by AI with a score of '{$this->other['grade']}' using provider '{$this->other['ai_provider']}' " .
            "in the assignment with course module id '$this->contextinstanceid'";
        
        if (!empty($this->relateduserid)) {
            $descriptionstring .= " for the user with id '$this->relateduserid'.";
        } else {
            $descriptionstring .= ".";
        }

        return $descriptionstring;
    }

    /**
     * Return localised event name.
     *
     * @return string
     */
    public static function get_name() {
        return get_string('event_submission_graded', 'assignsubmission_aigrader');
    }

    /**
     * Get URL related to the action
     *
     * @return \moodle_url
     */
    public function get_url() {
        return new \moodle_url('/mod/assign/view.php', 
            array('id' => $this->contextinstanceid, 'action' => 'grading'));
    }

    /**
     * Custom validation.
     *
     * @throws \coding_exception
     * @return void
     */
    protected function validate_data() {
        parent::validate_data();
        
        if (!isset($this->other['submissionid'])) {
            throw new \coding_exception('The \'submissionid\' value must be set in other.');
        }
        
        if (!isset($this->other['grade'])) {
            throw new \coding_exception('The \'grade\' value must be set in other.');
        }
        
        if (!isset($this->other['ai_provider'])) {
            throw new \coding_exception('The \'ai_provider\' value must be set in other.');
        }
    }

    public static function get_objectid_mapping() {
        return array('db' => 'assign_submission', 'restore' => 'submission');
    }
}