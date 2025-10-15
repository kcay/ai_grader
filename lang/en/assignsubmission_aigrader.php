<?php
// This file is part of Moodle - http://moodle.org/

/**
 * Language strings for assignsubmission_aigrader
 *
 * @package   assignsubmission_aigrader
 * @copyright 2025 Your Name
 * @license   http://www.gnu.org/copyleft/gpl.html GNU GPL v3 or later
 */

defined('MOODLE_INTERNAL') || die();

$string['pluginname'] = 'AI Auto-Grader';
$string['aigrader'] = 'AI Auto-Grader';

// Settings header
$string['aigrader_settings'] = 'AI Auto-Grading Settings';
$string['course_context'] = 'Course Context';
$string['grading_instructions'] = 'Grading Instructions';

// Main settings
$string['enabled'] = 'Enable AI Auto-Grading';
$string['enabled_help'] = 'Enable automatic grading of submissions using AI. When enabled, the AI will grade submissions based on the settings below.';

$string['use_default'] = 'Use site default';

$string['ai_provider'] = 'AI Provider';
$string['ai_provider_help'] = 'Choose which AI service to use for grading. Leave as default to use the site-wide setting.';

$string['ai_model'] = 'AI Model (Optional)';
$string['ai_model_help'] = 'Specify a particular AI model to use (e.g., gpt-4o, gemini-2.0-flash-exp, claude-sonnet-4-5). Leave blank to use the provider default.';

$string['leniency_level'] = 'Leniency Level';
$string['leniency_level_help'] = 'Adjust how strictly the AI grades:<br>
<ul>
<li><strong>Very Lenient (Dove):</strong> +10% bonus on scores</li>
<li><strong>Lenient:</strong> +5% bonus on scores</li>
<li><strong>Moderate:</strong> No adjustment (recommended)</li>
<li><strong>Strict:</strong> -5% penalty on scores</li>
<li><strong>Very Strict (Hawk):</strong> -10% penalty on scores</li>
</ul>';

$string['very_lenient'] = 'Very Lenient (Dove)';
$string['lenient'] = 'Lenient';
$string['moderate'] = 'Moderate';
$string['strict'] = 'Strict';
$string['very_strict'] = 'Very Strict (Hawk)';

// Course context
$string['course_transcript'] = 'Course Transcript / Syllabus (Text)';
$string['course_transcript_help'] = 'Paste course transcript, syllabus, lecture notes, or learning objectives here. This provides context to the AI about what was taught in the course, resulting in more accurate and relevant grading.

Example:
<pre>
Week 1: Introduction to Climate Science
- Greenhouse effect fundamentals
- Carbon cycle basics
- Historical temperature data

Week 2: Climate Models
- Types of climate models
- Interpreting model outputs
- Uncertainty in predictions
</pre>

The AI will use this context when evaluating student submissions.';

$string['transcript_file'] = 'Course Transcript / Syllabus (File)';
$string['transcript_file_help'] = 'Upload a PDF, Word document, or text file containing your course transcript, syllabus, or lecture notes. This is an alternative to pasting text above.

Supported formats: PDF, DOCX, DOC, TXT, ODT
Maximum file size: 10MB';

$string['transcript_note'] = '<strong>Note:</strong> You can provide course context as text OR as a file. If both are provided, the text version will be used. This helps the AI understand what concepts were taught and grade accordingly.';

// Grading instructions
$string['custom_prompt'] = 'Custom Grading Instructions';
$string['custom_prompt_help'] = 'Provide specific instructions for how the AI should grade this assignment. This is in addition to the rubric (if used).

Example:
<pre>
Award points based on:
- Clear thesis statement (20 points)
- Three well-supported arguments (45 points, 15 each)
- Proper citations (20 points)
- Strong conclusion (15 points)

Look for:
- Understanding of key concepts from weeks 1-3
- Application of climate models
- Critical thinking about predictions
</pre>

Be specific about what you want the AI to evaluate.';

$string['reference_document'] = 'Reference Document / Answer Key';
$string['reference_document_help'] = 'Upload a sample answer, grading rubric, or exemplar submission. The AI will use this to understand the expected quality and content.

Supported formats: PDF, DOCX, DOC, TXT
Maximum file size: 10MB

This is particularly useful for assignments with specific correct answers or when you want to show what an "A" submission looks like.';

$string['validation_note'] = '<strong>Important:</strong> Either custom grading instructions OR a reference document should be provided for best AI grading results. For rubric-based assignments, the rubric itself may be sufficient.';

// Capabilities
$string['aigrader:configure'] = 'Configure AI auto-grading settings';
$string['aigrader:view'] = 'View AI grading results';

// Privacy
$string['privacy:metadata:local_ai_autograder_config'] = 'AI grading configuration stored by the main AI Auto-Grader plugin';
$string['privacy:metadata:local_ai_autograder_config:assignmentid'] = 'The assignment ID';
$string['privacy:metadata:local_ai_autograder_config:enabled'] = 'Whether AI grading is enabled';
$string['privacy:metadata:local_ai_autograder_config:ai_provider'] = 'Which AI provider is used';
$string['privacy:metadata:local_ai_autograder_config:course_transcript'] = 'Course context information';
$string['privacy:metadata:local_ai_autograder_config:custom_prompt'] = 'Custom grading instructions';

// Events
$string['event_config_updated'] = 'AI grading configuration updated';
$string['event_config_updated_desc'] = 'AI grading configuration was updated for assignment {$a}';

// Errors
$string['error_no_local_plugin'] = 'The AI Auto-Grader local plugin (local_ai_autograder) must be installed and enabled.';
$string['error_saving_config'] = 'Error saving AI grading configuration';

// Backup/Restore
$string['backup_config'] = 'Backup AI grading configuration';
$string['restore_config'] = 'Restore AI grading configuration';