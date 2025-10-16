<?php
// This file is part of Moodle - http://moodle.org/

/**
 * AI Grader assignment submission plugin - file serving
 *
 * @package   assignsubmission_aigrader
 * @copyright 2025 Your Name
 * @license   http://www.gnu.org/copyleft/gpl.html GNU GPL v3 or later
 */

defined('MOODLE_INTERNAL') || die();

/**
 * Serves files for AI grader plugin
 *
 * @param mixed $course course or id of the course
 * @param mixed $cm course module or id of the course module
 * @param context $context
 * @param string $filearea
 * @param array $args
 * @param bool $forcedownload
 * @param array $options additional options affecting the file serving
 * @return bool false if file not found, does not return if found - just send the file
 */
function assignsubmission_aigrader_pluginfile($course,
                                              $cm,
                                              context $context,
                                              $filearea,
                                              $args,
                                              $forcedownload,
                                              array $options = array()) {
    global $DB, $CFG;

    if ($context->contextlevel != CONTEXT_MODULE) {
        return false;
    }

    require_login($course, false, $cm);
    
    $itemid = (int)array_shift($args);
    
    require_once($CFG->dirroot . '/mod/assign/locallib.php');
    
    $assign = new assign($context, $cm, $course);
    
    // Check capability to view
    if (!has_capability('assignsubmission/aigrader:view', $context)) {
        return false;
    }

    $relativepath = implode('/', $args);
    $fullpath = "/{$context->id}/local_ai_autograder/$filearea/$itemid/$relativepath";

    $fs = get_file_storage();
    if (!($file = $fs->get_file_by_hash(sha1($fullpath))) || $file->is_directory()) {
        return false;
    }

    // Finally send the file
    send_stored_file($file, 0, 0, $forcedownload, $options);
}