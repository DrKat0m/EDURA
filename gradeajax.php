<?php
require_once(__DIR__ . '/../../config.php');
require_once($CFG->libdir.'/gradelib.php');
require_once($CFG->dirroot.'/mod/geniai/lib.php');

require_login();
$input = json_decode(file_get_contents('php://input'), true);
if (!confirm_sesskey($input['sesskey'] ?? '')) {
    throw new moodle_exception('invalidsesskey', 'error');
}

$input = json_decode(file_get_contents('php://input'), true);
$cmid = clean_param($input['cmid'] ?? 0, PARAM_INT);
$grade = clean_param($input['grade'] ?? null, PARAM_FLOAT);

if (!$cmid || $grade === null) {
    http_response_code(400);
    echo json_encode(['status' => 'error', 'message' => 'Invalid input']);
    exit;
}

$cm = get_coursemodule_from_id('geniai', $cmid, 0, false, MUST_EXIST);
$context = context_module::instance($cmid);
require_capability('mod/geniai:view', $context);

$geniai = $DB->get_record('geniai', ['id' => $cm->instance], '*', MUST_EXIST);

$grades = ['userid' => $USER->id, 'rawgrade' => $grade];
$result = geniai_grade_item_update($geniai, $grades);

echo json_encode([
    'status' => $result === GRADE_UPDATE_OK ? 'success' : 'error',
    'grade' => $grade
]);
