<?php
global $PAGE;
require(dirname(dirname(dirname(dirname(__FILE__)))) . '/config.php');
require_once("$CFG->libdir/formslib.php");
$PAGE->set_context(context_system::instance());
$PAGE->set_url('/local/lecrec/pages/createposting.php');
$PAGE->set_title('Lecturer Recruitment');
$PAGE->requires->jquery();

$context = context_system::instance();
$user = $USER->id;

$PAGE->set_heading("Create a job posting");


class createposting extends moodleform
{


    //Add elements to form
    public function definition()
    {
        global $DB;

        $mform = $this->_form; // Don't forget the underscore!


        $records = $DB->get_records('lr_module');
        $modules[] = null;
        foreach ($records as $item) {
            $modules[$item->id] = $item->module_name;
        }

        $courses = $DB->get_records_select('sr_active_study_course', 'closed = ?', array(0));
        $course[] = null;
        foreach ($courses as $item) {
            $course[$item->id] = $item->study_course_abbreviation;
        }
        $subjects[] = null;

        $mform->addElement('select', 'module', 'Module', $modules, array('onchange' => 'javascript:loadSubjects();'));
        $mform->addElement('select', 'subject', 'Subject', [], array('onchange' => 'javascript:loadContent();')); // Add elements to your form
        $mform->setType('subject', PARAM_NOTAGS);

        $mform->addElement('select', 'course', 'course', $course); // Add elements to your form
        $mform->setType('course', PARAM_NOTAGS);   //Set type of element

        $mform->addElement('select', 'semester', 'Semester', ['sose1'=>'Summer Semester A' , 'sose2'=>'Summer Semester B' , 'wise1' => 'Winter Semester A', 'wise2' => 'Winter Semester B'] ); // Add elements to your form
        $mform->setType('course', PARAM_NOTAGS);   //Set type of element

        $mform->addElement('advcheckbox', 'external', 'External', 'Check if posting is open for external applications', '', array(0, 1));

        $mform->addElement('text', 'contactperson', 'Contact Person', 'size="50"');
        $mform->setType('contactperson', PARAM_NOTAGS);

        $mform->addElement('text', 'emailcontactperson', 'E-Mail Contact Person', 'size="50"');
        $mform->addRule('emailcontactperson', null, 'email',null, 'client');
        $mform->setType('emailcontactperson', PARAM_NOTAGS);

        $mform->addElement('text', 'phonecontactperson', 'Contact Person Phone', 'size="50"');
        $mform->addRule('phonecontactperson', null, 'numeric',null, 'client');
        $mform->setType('emailcontactperson', PARAM_NOTAGS);

        $mform->addElement('text', 'expected_hours', 'Expected Hours', 'size="50"');
        $mform->setType('expected_hours', PARAM_NOTAGS);

        $mform->addElement('textarea', 'description', 'Description' ,'wrap="virtual" rows="8" cols="70"');
        $mform->setType('description', PARAM_NOTAGS);

        $buttonarray = array();
        $buttonarray[] = $mform->createElement('submit', 'submitbutton', 'Save');
        $buttonarray[] = $mform->createElement('cancel');
        $mform->addGroup($buttonarray, 'buttonar', '', ' ', false);
    }
    //Custom validation should be added here
    function validation($data, $files)
    {
        return array();
    }
}

echo $OUTPUT->header();


$mform = new createposting();
$mform->display();


if ($mform->is_cancelled()) {
    //Handle form cancel operation, if cancel button is present on form
    $url = new moodle_url($CFG->wwwroot . '/local/lecrec/index.php');
    redirect($url);
}
if ($mform->is_submitted()) {

    $data = $mform->get_data();
    if($data->semester == 'sose1'){
        $start_date = date("Y").'-05-01 00:00:00';
        $end_date= date("Y").'-08-01 00:00:00';
    }elseif ($data->semester == 'sose2'){
        $start_date= date("Y").'-06-01 00:00:00';
        $end_date   = date("Y").'-09-01 00:00:00';
    }elseif ($data->semester == 'wise1'){
        $start_date= date("Y").'-10-01 00:00:00';
        $end_date= date('Y', strtotime('+1 years')).'-01-01 00:00:00';
    }else{
        $start_date= date("Y").'-11-01 00:00:00';
        $end_date= date('Y', strtotime('+1 years')).'-02-01 00:00:00';
    }
    $data->lr_subjects_id = $DB->get_record_select('lr_subjects', 'lr_module_id = ? AND lr_subject_name = ?', array($data->module, $_POST['subject']))->id;
    $data->description = $DB->get_record_select('lr_subjects', 'lr_module_id = ? AND lr_subject_name = ?', array($data->module, $_POST['subject']))->lr_description;
    $DB->insert_record('lr_job_postings', array(
        'external' => $data->external,
        'description' => $data->description,
        'expected_hours' => $data->expected_hours,
        'lr_subjects_id' => $data->lr_subjects_id,
        'start_date' => $start_date,
        'end_date' => $end_date,
        'director_id' => $user,
        'cp_name' => $data->contactperson,
        'cp_email' => $data->emailcontactperson,
        'sr_course_id' => $data->course,
        'timecreated' => time(),
        'usermodified' => $user
    ));
    redirect(new moodle_url('/local/lecrec/index.php'));
}
?>
<script>
    function loadSubjects() {
        var module_id = $('#id_module option:selected').val();
        $.ajax({
            type: "POST",
            url: "../assets/PHPFunctions/get_subjects.php",
            datatype: 'html',
            data: {
                module_id: module_id
            },
            success: function(subjects) {
                $("#id_subject").empty();
                $.each(JSON.parse(subjects), function() {

                    $("#id_subject").append("<option value='" + this + "'>" + this + "</option>");
                });
            },
            error: function(error) {}
        })
    }

    function loadContent() {
        var module_id = $('#id_module option:selected').val();
        var subject_id = $('#id_subject option:selected').val();
        $.ajax({
            type: "POST",
            url: "../assets/PHPFunctions/get_description.php",
            datatype: 'html',
            data: {
                module_id: module_id,
                subject_id: subject_id
            },
            success: function(subject) {
                $.each(JSON.parse(subject), function() {
                    if (isNaN(this)) {
                        $("#id_description").val(this);
                    } else {
                        $("#id_expected_hours").val(this);
                    }

                });
            },
            error: function(error) {}
        })
    }

</script>

<?php
echo $OUTPUT->footer();
?>