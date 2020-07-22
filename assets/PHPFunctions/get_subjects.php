<?php
if ($_SERVER['REQUEST_METHOD'] == 'POST') {
    $input = filter_input_array(INPUT_POST);
} else {
    $input = filter_input_array(INPUT_GET);
};

require_once(dirname(dirname(dirname(dirname(__DIR__)))) . '/config.php');
global $DB, $USER;

$subjects = $DB->get_records_select('lr_subjects','lr_module_id = ?', array($input['module_id']), '', 'lr_subject_name');
$subjects_array = [];
foreach ($subjects as $subject ){
    array_push($subjects_array,$subject->lr_subject_name);
}
echo json_encode($subjects_array);
