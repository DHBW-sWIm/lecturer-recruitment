<?php
global $PAGE;
require(dirname(dirname(dirname(dirname(__FILE__)))) . '/config.php');
require_once("$CFG->libdir/formslib.php");
$PAGE->set_context(context_system::instance());
$PAGE->set_url('/local/lecrec/pages/lecturer_c_u_form.php');
$PAGE->set_title('Lecturer Recruitment');
$PAGE->requires->jquery();

$context = context_system::instance();
$user = $USER->id;

$PAGE->set_heading("Add new lecturer");


class addlecturer extends moodleform {


    //Add elements to form
    public function definition() {
        global $DB, $USER;

        $mform = $this->_form; // Don't forget the underscore!


        $records = $DB->get_records('lr_module');
        $modules[] = null;
        foreach ($records as $item){
            $modules[$item->id] = $item->module_name;
        }

        $mform->addElement('select','module', 'Module', $modules , array('onchange' => 'javascript:loadSubjects();'));
        $mform->addElement('select', 'subject', 'Subject',); // Add elements to your form
        $mform->setType('subject', PARAM_NOTAGS);                   //Set type of element

        $mform->addElement('advcheckbox', 'external', 'External', 'Check if posting is open for external applications', '', array(0, 1));

        $mform->addElement('date_selector', 'startdate', 'Start Date');
        $mform->addElement('date_selector', 'enddate', 'End Date');

        $mform->addElement('text', 'directorid', 'Director ID', 'size="50"');
        $mform->setType('directorid', PARAM_NOTAGS);

        $mform->addElement('text', 'contactperson', 'Contact Person', 'size="50"');
        $mform->setType('contactperson', PARAM_NOTAGS);

        $mform->addElement('text', 'emailcontactperson', 'E-Mail Contact Person', 'size="50"');
        $mform->setType('emailcontactperson', PARAM_NOTAGS);

        $buttonarray=array();
        $buttonarray[] = $mform->createElement('submit', 'submitbutton','Save');
        $buttonarray[] = $mform->createElement('reset', 'resetbutton','Revert');
        $buttonarray[] = $mform->createElement('cancel');
        $mform->addGroup($buttonarray, 'buttonar', '', ' ', false);
    }
    //Custom validation should be added here
    function validation($data, $files) {
        return array();
    }
}

echo $OUTPUT->header();


$mform = new addlecturer();
$mform->display();


if ($mform->is_cancelled()) {
    //Handle form cancel operation, if cancel button is present on form
    $url = new moodle_url($CFG->wwwroot.'/local/lecrec/index.php');
    redirect($url);

}
else if ($fromform = $mform->get_data()) {
    //In this case you process validated data. $mform->get_data() returns data posted in form.
    print_r($fromform);
}
if ($mform->is_submitted())
{
    //  $DB->insert_record('lr_job_postings',)
}