<?php


require(dirname(__FILE__, 4) . '/config.php');

if ($_SERVER['REQUEST_METHOD'] === 'POST') {
    $input = filter_input_array(INPUT_POST);
    $rowID = $input['rowID'];
    $ID = $input['ID'];
} else {
    $input = filter_input_array(INPUT_GET);
}


global $DB, $PAGE, $OUTPUT;

$PAGE->set_context(context_system::instance());

$PAGE->requires->jquery();
$PAGE->requires->css('/local/lecrec/assets/CSS/application.css');

    $PAGE->set_heading('Lecturer Recruitment');

    echo $OUTPUT->header();
    echo $OUTPUT->heading('DHBW Mannheim Posting');


    $record = $DB->get_record_select('lr_job_postings', 'id = ?' , array($rowID));

echo html_writer::tag('br', '');
echo html_writer::start_div('');
echo html_writer::start_div('card');
echo html_writer::start_div('card-header');
echo html_writer::start_tag('div');
echo "Posting Information:";
echo html_writer::end_tag('div');
echo html_writer::end_div();
echo html_writer::start_div('collapse show', array('id' => "personal-info"));
echo html_writer::start_div('card-body');
echo html_writer::start_div('row');
echo html_writer::start_div('col-md-4');
echo html_writer::tag('h6', 'Subject Name', array('class' => "font-weight-bold"));
echo html_writer::tag('p', $ID );
echo html_writer::tag('h6', 'Expected Teaching Hours', array('class' => "font-weight-bold"));
echo html_writer::tag('address', $record->expected_hours);
echo html_writer::tag('address', '<b>Hint:</b> Each Teaching Hour is 45 Minutes');
echo html_writer::end_div();
echo html_writer::start_div('col-md-4');
echo html_writer::tag('h6', 'Contact Person', array('class' => "font-weight-bold"));
echo html_writer::tag('p', $record->cp_name);
echo html_writer::tag('h6', 'Email', array('class' => "font-weight-bold"));
echo html_writer::tag('p', $record->cp_email);
echo html_writer::tag('h6', 'Phone', array('class' => "font-weight-bold"));
echo html_writer::tag('p', $record->cp_phone);
echo html_writer::end_div();
echo html_writer::start_div('col-md-4');
echo html_writer::tag('h6', 'Start Date', array('class' => "font-weight-bold"));
echo html_writer::tag('p', (new DateTime($record->start_date))->format('d-m-Y'));
echo html_writer::tag('h6', 'End Date', array('class' => "font-weight-bold"));
echo html_writer::tag('p', (new DateTime($record->end_date))->format('d-m-Y'));
echo html_writer::end_div();
echo html_writer::end_div();

echo html_writer::start_div('row');
echo html_writer::start_div('col-md');

echo html_writer::tag('h6', 'Description', array('class' => "font-weight-bold"));
echo html_writer::tag('p', $record->description);

echo html_writer::end_div();
echo html_writer::end_div();

echo html_writer::end_div();
echo html_writer::end_div();
echo html_writer::end_div();



    echo '<br><form action="application.php" method="post">
<div class="row mx-auto">
<input name="rowID" value="' . $rowID . '" hidden>
<input name="ID" value="' . $ID . '" hidden>
<input type ="submit" class="btn btn-success" value="Apply">
</div>
</from>';



echo $OUTPUT->footer();
