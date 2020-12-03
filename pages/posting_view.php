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
    echo $OUTPUT->heading('Offene Stelle für ' + " " + $ID);


    $record = $DB->get_record_select('lr_job_postings', 'id = ?' , array($rowID));

echo html_writer::tag('br', '');
echo html_writer::start_div('');
echo html_writer::start_div('card');
echo html_writer::start_div('card-header');
echo html_writer::start_tag('div');
echo "Informationen zur ausgeschriebenen Stelle";
echo html_writer::end_tag('div');
echo html_writer::end_div();
echo html_writer::start_div('collapse show', array('id' => "personal-info"));
echo html_writer::start_div('card-body');
echo html_writer::start_div('row');
echo html_writer::start_div('col-md-4');
echo html_writer::tag('h6', 'Fach', array('class' => "font-weight-bold"));
echo html_writer::tag('p', $ID );
echo html_writer::tag('h6', 'Erwartete Unterrichtsstunden', array('class' => "font-weight-bold"));
echo html_writer::tag('address', $record->expected_hours);
echo html_writer::tag('address', '<b>Hinweis:</b> Eine Vorlesungsstunde umfassen 45 Minuten');
echo html_writer::end_div();
echo html_writer::start_div('col-md-4');
echo html_writer::tag('h6', 'Kontaktperson', array('class' => "font-weight-bold"));
echo html_writer::tag('p', $record->cp_name);
echo html_writer::tag('h6', 'E-Mail Adresse', array('class' => "font-weight-bold"));
echo html_writer::tag('p', $record->cp_email);
echo html_writer::tag('h6', 'Telefonnummer', array('class' => "font-weight-bold"));
echo html_writer::tag('p', $record->cp_phone);
echo html_writer::end_div();
echo html_writer::start_div('col-md-4');
echo html_writer::tag('h6', 'Startdatum', array('class' => "font-weight-bold"));
echo html_writer::tag('p', (new DateTime($record->start_date))->format('d-m-Y'));
echo html_writer::tag('h6', 'Enddatum', array('class' => "font-weight-bold"));
echo html_writer::tag('p', (new DateTime($record->end_date))->format('d-m-Y'));
echo html_writer::end_div();
echo html_writer::end_div();

echo html_writer::start_div('row');
echo html_writer::start_div('col-md');

echo html_writer::tag('h6', 'Beschreibung', array('class' => "font-weight-bold"));
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
<input type ="submit" class="btn btn-success" value="Bewerben">
</div>
</from>';



echo $OUTPUT->footer();
