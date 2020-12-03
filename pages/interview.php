<?php

require(dirname(__FILE__, 4) . '/config.php');


global $PAGE, $CFG, $USER;
require_once("$CFG->libdir/formslib.php");

$PAGE->set_context(context_system::instance());
$PAGE->set_url('/local/lecrec/pages/createposting.php');
$PAGE->set_title('Lecturer Recruitment');

$context = context_system::instance();
$user = $USER->id;
$PAGE->set_heading("Interview Details");


class setInterview extends moodleform
{

    //Add elements to form
    public function definition()
    {
        global $DB;

        $mform = $this->_form; // Don't forget the underscore!
        $app_id = $_SESSION['InterviewDetails_app_id'];

        $record = $DB->get_record('lr_application', array('id' => $app_id));
        $mform->addElement('text', 'subject', 'Betreff');
        $mform->setType('subject', PARAM_RAW);
        $mform->addElement('editor', 'editor', 'E-Mail Inhalt');
        $mform->setType('editor', PARAM_RAW);
        //  $mform->addElement('textarea', 'editor', 'Send Response' , 'wrap="virtual" rows="18" cols="70"');


        $buttonarray = array();
        $buttonarray[] = $mform->createElement('submit', 'submitbutton', 'Senden');
        $buttonarray[] = $mform->createElement('Abbrechen');
        $mform->addGroup($buttonarray, 'buttonar', '', ' ', false);


    }

    //Custom validation should be added here
    function validation($data, $files)
    {
        return array();
    }
}

echo $OUTPUT->header();


$mform = new setInterview();

$mform->display();


if ($mform->is_cancelled()) {
    //Handle form cancel operation, if cancel button is present on form
    $url = new moodle_url($CFG->wwwroot . '/local/lecrec/index.php');
    redirect($url);
}
if ($mform->is_submitted()) {
    $data = $mform->get_data();
    $app_id = $_SESSION['InterviewDetails_app_id'];

    $DB->update_record('lr_application', (object)array(
        'id' => $app_id,
        'status_of_application' => 'Einladung versandt',
        'timemodified' => time()
    ));
    //TODO send email to camunda
    if (empty($from)) {
        $from = 'Lecturer Recruitment';
    }
    $application = $DB->get_record('lr_application', array('id' => $app_id));
    $user = new stdClass();
    $user->id = $USER->id;
    $username = $DB->get_record('user', array('id' => $USER->id ))->username;
    $user->username = $username;
    $user->email = $application->private_email;
    $form = $data->editor['text'];

    email_to_user($user, $from, $data->subject, $form,$form);
    unset($_SESSION['InterviewDetails_app_id']);
    redirect('../index.php');
};


echo $OUTPUT->footer();
?>