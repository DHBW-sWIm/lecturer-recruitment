<?php
require(dirname(__FILE__, 4) . '/config.php');
require_login();
//include('../tables/tablerecruitmentprocess.php');
//include_once('../table.php');

global $DB, $PAGE, $OUTPUT, $CFG, $USER;

$PAGE->set_context(context_system::instance());
$PAGE->set_url('/local/lecrec/pages/recruitmentprocess.php');
$PAGE->set_title('Lecturer Recruitment');
$PAGE->requires->css('/local/lecrec/assets/CSS/jquery.dataTables.min.css');
$PAGE->requires->jquery();
$PAGE->requires->js('/local/lecrec/assets/js/jquery.dataTables.min.js',true);

$context = context_system::instance();
$user = $USER->id;

if (has_capability('local/lecrec:manager', $context)) {

    $PAGE->set_heading('Lecturer Recruitment');
    $PAGE->navbar->add('Lecturer Recruitment', new moodle_url('/local/lecrec/index.php', array('id' => $user)));


    echo $OUTPUT->header();
    echo $OUTPUT->heading('Recruitment Processes');



    $table = new html_table();
    $table->id = 'my_table';
  //  $table->attributes['class'] = 'table table-sm ';

    $records = $DB->get_records_select("lr_job_postings", 'director_id = ?', array($user));
    $table->head = array('Posting Name', 'Expected Hours','Course', 'Number of Applications');
    $table->align = array('center', 'center', 'center');




    foreach ($records as $record) {

        // $table->data[] = array($record->id, $record->first_name, $record->last_name, $record->date_of_birth, $record->private_email, $record->timecreated, $record->contract_status);
        $row = new html_table_row();

        $row->attributes['RecordID'] = $record->id;
        $cell0 = new html_table_cell();
        $subject = $DB->get_record_select("lr_subjects", 'id = ?', array($record->lr_subjects_id));
        $cell0->text = $subject->lr_subject_name;

        $cell1 = new html_table_cell();
        $cell1->text = $record->expected_hours;

        $cell2 = new html_table_cell();
        $course = $DB->get_record_select("sr_active_study_course", 'id = ?', array($record->sr_course_id));
        $cell2->text = $course->study_course_abbreviation;

        $count = $DB->count_records_select('lr_application', 'lr_job_postings_id = ? AND closed =0', array($record->id));

        $cell3 = new html_table_cell();
        $cell3->text = $count;


        $row->cells  = array($cell0, $cell1, $cell2,$cell3);

        $table->data[]  = $row;
    };


    echo html_writer::table($table);







} else {
    redirect($CFG->wwwroot);
}


?>

<script>
   $(document).ready(function() {
        $('#my_table tr[RecordID]').each(function() {
            $(this).css('cursor', 'pointer').hover(
                function() {
                    $(this).addClass('active');

                },
                function() {


                    $(this).removeClass('active');
                }).click(function() {
                var RecordID = $(this).attr('RecordID');
                redirectUrl = 'application_overview.php';
                var form = $('<form action="' + redirectUrl + '" method="post">' +
                    '<input type="hidden" name="RecordID" value="' + RecordID + '"></input>' + '</form>');
                $('body').append(form);
                $(form).submit();


            });
        });
    });
$(document).ready(function() {
    $('#my_table').DataTable();
});
</script>
<?php
echo $OUTPUT->footer();
?>