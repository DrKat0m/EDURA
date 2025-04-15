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
 * Library of interface functions and constants.
 *
 * @package   mod_geniai
 * @copyright 2025 Eduardo Kraus https://eduardokraus.com/
 * @license   http://www.gnu.org/copyleft/gpl.html GNU GPL v3 or later
 */

/**
 * Checks if certificate activity supports a specific feature.
 *
 * @uses FEATURE_GROUPS
 * @uses FEATURE_GROUPINGS
 * @uses FEATURE_MOD_INTRO
 * @uses FEATURE_SHOW_DESCRIPTION
 * @uses FEATURE_COMPLETION_TRACKS_VIEWS
 * @uses FEATURE_COMPLETION_HAS_RULES
 * @uses FEATURE_MODEDIT_DEFAULT_COMPLETION
 * @uses FEATURE_BACKUP_MOODLE2
 *
 * @param string $feature FEATURE_xx constant for requested feature
 *
 * @return mixed True if module supports feature, false if not, null if doesn't know
 */
function geniai_supports(string $feature) {
    switch ($feature) {
        case FEATURE_GROUPS:
            return true;
        case FEATURE_GROUPINGS:
            return true;
        case FEATURE_MOD_INTRO:
            return true;
        case FEATURE_SHOW_DESCRIPTION:
            return true;
        case FEATURE_COMPLETION_TRACKS_VIEWS:
            return true;
        case FEATURE_MODEDIT_DEFAULT_COMPLETION:
            return true;
        case FEATURE_BACKUP_MOODLE2:
            return true;
        case FEATURE_MOD_PURPOSE:
            return MOD_PURPOSE_ASSESSMENT;
        case FEATURE_GRADE_HAS_GRADE:
            return true;
        default:
            return null;
    }
}

/**
 * Saves a new instance of the mod_geniai into the database.
 *
 * Given an object containing all the necessary data, (defined by the form
 * in mod_form.php) this function will create a new instance and return the id
 * number of the instance.
 *
 * @param stdClass $data                           An object from the form.
 * @param mod_geniai_mod_form $mform The form.
 *
 * @return int The id of the newly inserted record.
 * @throws dml_exception
 */
function geniai_add_instance(stdClass $data, $mform = null): int {
    global $DB;

    $data->timecreated = time();
    $cmid = $data->coursemodule;

    $data->id = $DB->insert_record("geniai", $data);
    // We need to use context now, so we need to make sure all needed info is already in db.
    $DB->set_field("course_modules", "instance", $data->id, ["id" => $cmid]);

    return $data->id;
}

/**
 * Updates an instance of the mod_geniai in the database.
 *
 * Given an object containing all the necessary data (defined in mod_form.php),
 * this function will update an existing instance with new data.
 *
 * @param stdClass $data                           An object from the form in mod_form.php.
 * @param mod_geniai_mod_form $mform The form.
 *
 * @return bool True if successful, false otherwise.
 * @throws dml_exception
 */
function geniai_update_instance(stdClass $data, $mform = null): bool {
    global $DB;

    $data->timemodified = time();
    $data->id = $data->instance;
    return $DB->update_record("geniai", $data);
}

/**
 * Removes an instance of the mod_geniai from the database.
 *
 * @param int $id Id of the module instance.
 *
 * @return bool True if successful, false on failure.
 * @throws coding_exception
 * @throws dml_exception
 */
function geniai_delete_instance(int $id): bool {
    global $DB;

    if (!$DB->record_exists("geniai", ["id" => $id])) {
        return false;
    }

    if (!$cm = get_coursemodule_from_instance("geniai", $id)) {
        return false;
    }
    $DB->delete_records("geniai", ["id" => $id]);

    return true;
}

function geniai_grade_item_update($geniai, $grades = null) {
    require_once(__DIR__ . '/../../lib/gradelib.php');

    $item = array(
        'itemname' => $geniai->name,
        'gradetype' => GRADE_TYPE_VALUE,
        'grademax'  => 10,
        'grademin'  => 0
    );

    if ($grades === null) {
        $item['reset'] = true;
        return grade_update('mod/geniai', $geniai->course, 'mod', 'geniai', $geniai->id, 0, null, $item);
    } else {
        return grade_update('mod/geniai', $geniai->course, 'mod', 'geniai', $geniai->id, 0, $grades, $item);
    }
}

function geniai_grade_item_delete($geniai) {
    return grade_update('mod/geniai', $geniai->course, 'mod', 'geniai', $geniai->id, 0, null, array('deleted'=>1));
}