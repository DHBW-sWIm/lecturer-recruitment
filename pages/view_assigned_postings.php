<?php

require(dirname(__FILE__, 4) . '/config.php');
require_login();

if ($_SERVER['REQUEST_METHOD'] == 'POST') {
    $input = filter_input_array(INPUT_POST);
} else {
    $input = filter_input_array(INPUT_GET);
};

global $DB, $PAGE, $OUTPUT, $CFG, $USER;

$PAGE->set_context(context_system::instance());
$PAGE->set_url('/local/lecrec/pages/lecturerpool.php');
$PAGE->set_title('Lecturer Recruitment');
$PAGE->requires->jquery();

$context = context_system::instance();
$user = $USER->id;

if (has_capability('local/lecrec:manager', $context)) {

    $PAGE->set_heading('Lecturer Recruitment');
    $PAGE->navbar->add('Lecturer Recruitment', new moodle_url('/local/lecrec/index.php', array('id' => $user)));
    $PAGE->navbar->add('Lecturer Pool', new moodle_url('/local/lecrec/pages/lecturerpool.php', array('id' => $user)));

    echo $OUTPUT->header();
    echo $OUTPUT->heading('Previously Assigned Postings');
    $record_id = $input['record_id'];
    $table = new html_table();
    $table->id = 'my-table';
    $table->attributes['class'] = 'table table-sm';
    $where = "WHERE pa.lr_lecturer_id = $record_id";
    $sql = "SELECT pa.id , po.expected_hours, mo.module_name, su.lr_subject_name , ac.study_course_abbreviation 
            FROM {lr_posting_assignment} AS pa
            INNER JOIN {lr_job_postings} AS po ON pa.lr_job_postings_id = po.id
            INNER JOIN {lr_subjects} AS su ON su.id = po.lr_subjects_id
            INNER JOIN {lr_module} AS mo ON mo.id = su.lr_module_id
            INNER JOIN {sr_active_study_course} AS ac on ac.id = po.sr_course_id
            {$where}";
    $records = $DB->get_records_sql($sql);


    $table->head = array('Module', 'Subject', 'Expected Hours', 'Course');
    $table->align = array('center', 'center', 'center', 'center');


    foreach ($records as $record) {

        $row = new html_table_row();


        $cell1 = new html_table_cell();
        $cell1->text = $record->module_name;

        $cell2 = new html_table_cell();
        $cell2->text = $record->lr_subject_name;

        $cell3 = new html_table_cell();
        $cell3->text = $record->expected_hours;

        $cell4 = new html_table_cell();
        $cell4->text = $record->study_course_abbreviation;


        $row->cells  = array($cell1, $cell2, $cell3, $cell4);

        $table->rowclasses[$record->id] = '';
        $table->data[]  = $row;
    };


    echo html_writer::table($table);
} else {
    redirect($CFG->wwwroot);
}

echo $OUTPUT->footer();
