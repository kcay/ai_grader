<?php
// This file is part of Moodle - http://moodle.org/

/**
 * Privacy provider for assignsubmission_aigrader
 *
 * @package   assignsubmission_aigrader
 * @copyright 2025 Your Name
 * @license   http://www.gnu.org/copyleft/gpl.html GNU GPL v3 or later
 */

namespace assignsubmission_aigrader\privacy;

defined('MOODLE_INTERNAL') || die();

use core_privacy\local\metadata\collection;
use core_privacy\local\request\contextlist;

/**
 * Privacy provider implementation
 */
class provider implements
    \core_privacy\local\metadata\provider,
    \core_privacy\local\request\plugin\subplugin_provider {

    /**
     * Get the list of metadata about data stored by this plugin
     *
     * @param collection $collection
     * @return collection
     */
    public static function get_metadata(collection $collection): collection {
        
        // This plugin stores configuration data via the local_ai_autograder plugin
        $collection->add_external_location_link(
            'local_ai_autograder_config',
            [
                'assignmentid' => 'privacy:metadata:local_ai_autograder_config:assignmentid',
                'enabled' => 'privacy:metadata:local_ai_autograder_config:enabled',
                'ai_provider' => 'privacy:metadata:local_ai_autograder_config:ai_provider',
                'course_transcript' => 'privacy:metadata:local_ai_autograder_config:course_transcript',
                'custom_prompt' => 'privacy:metadata:local_ai_autograder_config:custom_prompt',
            ],
            'privacy:metadata:local_ai_autograder_config'
        );

        return $collection;
    }

    /**
     * Get the list of contexts that contain user information
     *
     * @param int $userid
     * @return contextlist
     */
    public static function get_context_for_userid(int $userid): contextlist {
        // This plugin doesn't store user-specific data
        // Configuration is assignment-level, not user-level
        return new contextlist();
    }

    /**
     * Export all user data for the specified user
     *
     * @param \core_privacy\local\request\approved_contextlist $contextlist
     */
    public static function export_user_data(\core_privacy\local\request\approved_contextlist $contextlist) {
        // This plugin doesn't store user-specific data
    }

    /**
     * Delete all user data for the specified context
     *
     * @param \context $context
     */
    public static function delete_data_for_all_users_in_context(\context $context) {
        // This plugin doesn't store user-specific data
    }

    /**
     * Delete all user data for the specified user
     *
     * @param \core_privacy\local\request\approved_contextlist $contextlist
     */
    public static function delete_data_for_user(\core_privacy\local\request\approved_contextlist $contextlist) {
        // This plugin doesn't store user-specific data
    }

    /**
     * Get student user ids for context
     *
     * @param \core_privacy\local\request\userlist $userlist
     */
    public static function get_student_user_ids(\core_privacy\local\request\userlist $userlist) {
        // This plugin doesn't store user-specific data
    }

    /**
     * Delete multiple users within a single context
     *
     * @param \core_privacy\local\request\approved_userlist $userlist
     */
    public static function delete_data_for_users(\core_privacy\local\request\approved_userlist $userlist) {
        // This plugin doesn't store user-specific data
    }
}