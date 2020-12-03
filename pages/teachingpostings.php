<?php

require(dirname(__FILE__, 4) . '/config.php');
include('../tables/tableteachingpostings.php');


$PAGE->set_title('Lecturer Recruitment');
$PAGE->set_context(context_system::instance());
$PAGE->set_url('/local/lecrec/pages/teachingpostings.php');
$PAGE->set_title('Lecturer Recruitment');
$PAGE->requires->css('/local/lecrec/assets/CSS/jquery.dataTables.min.css');
$PAGE->requires->jquery();
$PAGE->requires->js('/local/lecrec/assets/js/jquery.dataTables.min.js', true);
echo $OUTPUT->header();
echo $OUTPUT->heading('Offene Stellenausschreibungen');
$context = context_system::instance();
$user = $USER->id;
echo '</br>';

$table = new html_table();
$table->id = 'postings';
//$table->attributes['class'] = 'table table-striped table-xl';
$records = $DB->get_records_select("lr_job_postings", 'closed = ?', array('0'));

$table->head = array('Vorlesung', 'Beschreibung', 'Qualifikation','Kurs', 'Zeitraum', 'Stunden pro Semester');
$table->align = array('center', 'center', 'center', 'center', 'center','center');
//TODO: Pagination
//TODO: Search and/or filter
$i = 0;
foreach ($records as $record) {
    $i++;
    $row = new html_table_row();
    $row->attributes['RecordID'] = $record->id;
    $vor = $DB->get_record_select('lr_subjects', 'id= ?', array($record->lr_subjects_id));

    $cell1 = new html_table_cell();
    $cell1->id = $i;
    $cell1->text = $vor->lr_subject_name;

    $cell2 = new html_table_cell();
    $cell2->text = $record->description;

    $cell3 = new html_table_cell();

    $cell3->text = 'Bachelor in MINT Fach';

    $cell4 = new html_table_cell();
    $course = $DB->get_record_select("sr_active_study_course", 'id = ?', array($record->sr_course_id));
    $cell4->text = $course->study_course_abbreviation;

    $cell5 = new html_table_cell();
    // $cell5->style = "min-width: 5%;";
    $cell5->text = 'From ' . date('Y-m-d', strtotime($record->start_date)) . ' To ' . date('Y-m-d', strtotime($record->end_date));
    $cell6 = new html_table_cell();

    $cell6->text = $record->expected_hours;

    $row->cells = array($cell1, $cell2, $cell3, $cell4, $cell5,$cell6);
    $table->rowclasses[$record->id] = '';
    $table->data[] = $row;
}

echo html_writer::table($table);

?>

<script>
    $(function() {
        $('#postings tr[RecordID] td').each(function() {
            $(this).css('cursor', 'pointer').click(function() {
                var ID = $(this).parent().children(":first").html();
                var rowID = $(this).parent().attr('RecordID');
                redirectUrl = 'posting_view.php';
                var form = $('<form action="' + redirectUrl + '" method="post">' +
                    '<input type="hidden" name="rowID" value="' + rowID + '"></input>' +
                    '<input type="hidden" name="ID" value="' + ID + '"></input>' + '</form>');
                $('body').append(form);
                $(form).submit();
            });
        });
    });
    $(document).ready(function() {
        $('#postings').DataTable();
    });
</script>