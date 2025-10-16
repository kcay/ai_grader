<?php
// This file is part of Moodle - http://moodle.org/

/**
 * The assignsubmission_aigrader config_updated event.
 *
 * @package   assignsubmission_aigrader
 * @copyright 2025 Your Name
 * @license   http://www.gnu.org/copyleft/gpl.html GNU GPL v3 or later
 */

namespace assignsubmission_aigrader\event;

defined('MOODLE_INTERNAL') || die();

/**
 * The assignsubmission_aigrader config_updated event class.
 *
 * @property-read array $other {
 *      Extra information about the event.
 *
 *      - bool enabled: Whether AI grading is enabled.
 *      - string ai_provider: The AI provider being used.
 * }
 *
 * @package   assignsubmission_aigrader
 * @copyright 2025 Your Name
 * @license   http://www.gnu.org/copyleft/gpl.html GNU GPL v3 or later
 */
class config_updated extends \core\event\base {

    /**
     * Init method.
     */
    protected function init() {
        $this->data['objecttable'] = 'assign';
        $this->data['crud'] = 'u';
        $this->data['edulevel'] = self::LEVEL_TEACHING;
    }

    /**
     * Returns description of what happened.
     *
     * @return string
     */
    public function get_description() {
        $enabled = $this->other['enabled'] ? 'enabled' : 'disabled';
        return "The user with id '$this->userid' has $enabled AI auto-grading for the assignment " .
            "with course module id '$this->contextinstanceid'.";
    }

    /**
     * Return localised event name.
     *
     * @return string
     */
    public static function get_name() {
        return get_string('event_config_updated', 'assignsubmission_aigrader');
    }

    /**
     * Get URL related to the action
     *
     * @return \moodle_url
     */
    public function get_url() {
        return new \moodle_url('/mod/assign/view.php', array('id' => $this->contextinstanceid));
    }

    /**
     * Custom validation.
     *
     * @throws \coding_exception
     * @return void
     */
    protected function validate_data() {
        parent::validate_data();
        
        if (!isset($this->other['enabled'])) {
            throw new \coding_exception('The \'enabled\' value must be set in other.');
        }
        
        if (!isset($this->other['ai_provider'])) {
            throw new \coding_exception('The \'ai_provider\' value must be set in other.');
        }
    }

    public static function get_objectid_mapping() {
        return array('db' => 'assign', 'restore' => 'assign');
    }
}