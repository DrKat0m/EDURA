<?php
// This file is part of Moodle - http://moodle.org/
//
// Moodle is free software: you can redistribute it and/or modify
// it under the terms of the GNU General Public License as published by
// the Free Software Foundation, either version 3 of the License, or
// (at your option) any later version.
//
// Moodle is distributed in the hope that it will be useful,
// but WITHOUT ANY WARRANTY; without even the implied warranty of
// MERCHANTABILITY or FITNESS FOR A PARTICULAR PURPOSE.  See the
// GNU General Public License for more details.
//
// You should have received a copy of the GNU General Public License
// along with Moodle.  If not, see <http://www.gnu.org/licenses/>.

/**
 * Prints an instance of mod_geniai.
 *
 * @package   mod_geniai
 * @copyright 2025 Eduardo Kraus https://eduardokraus.com/
 * @license   http://www.gnu.org/copyleft/gpl.html GNU GPL v3 or later
 */

use local_geniai\local\util\release;

require_once("../../config.php");

global $PAGE, $USER, $CFG;

$id = required_param("id", PARAM_INT);

$cm = get_coursemodule_from_id("geniai", $id, 0, false, MUST_EXIST);
$course = $DB->get_record("course", ["id" => $cm->course], "*", MUST_EXIST);

$context = context_module::instance($cm->id);

/** @var \mod_geniai\vo\geniai $geniai */
$geniai = $DB->get_record("geniai", ["id" => $cm->instance], "*", MUST_EXIST);

$PAGE->set_context($context);
$PAGE->set_url("/mod/geniai/view.php", ["id" => $id]);
$PAGE->set_title($course->shortname . ": " . $geniai->name);
$PAGE->set_heading(format_string($course->fullname));

require_course_login($course, true, $cm);
require_capability("mod/geniai:view", $context);

$event = \mod_geniai\event\geniai_course_module_viewed::create([
    "objectid" => $PAGE->cm->instance,
    "context" => $PAGE->context,
]);
$event->add_record_snapshot("course", $PAGE->course);
$event->add_record_snapshot($PAGE->cm->modname, $geniai);
$event->trigger();

// Update "viewed" state if required by completion system.
$completion = new completion_info($course);
$completion->set_module_viewed($cm);

echo $OUTPUT->header();

$capability = has_capability("local/geniai:manage", $context);

$data = [
    "message_01" => get_string("message_01", "local_geniai", fullname($USER)),
    "manage_capability" => $capability,
    "geniainame" => get_config("local_geniai", "geniainame"),
    "mode" => get_config("local_geniai", "mode"),
    "talk_geniai" => get_string("talk_geniai", "local_geniai", get_config("local_geniai", "geniainame")),
];

$geniainame = get_config("local_geniai", "geniainame");
$course = $DB->get_record("course", ["id" => $COURSE->id]);
$data["message_02"] = get_string("message_02_course", "local_geniai",
    ["geniainame" => $geniainame, "moodlename" => $SITE->fullname, "coursename" => $course->fullname]);

echo $OUTPUT->render_from_template("mod_geniai/chat", $data);
$PAGE->requires->js_call_amd("local_geniai/chat", "init", [$COURSE->id, release::version()]);

echo $OUTPUT->footer();
