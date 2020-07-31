<?php
global $PAGE;

use local_digitalsignature\Controllers\Templates\SigningViaEmail;

require(dirname(__FILE__, 4) . '/config.php');
require_once("$CFG->libdir/formslib.php");
$PAGE->set_context(context_system::instance());
$PAGE->set_url('/local/lecrec/pages/createposting.php');
$PAGE->set_title('Lecturer Recruitment');


$context = context_system::instance();
$user = $USER->id;

$PAGE->set_heading("Send and Sign Contracts");


class sendcontract extends moodleform
{


    //Add elements to form
    public function definition()
    {
        global $DB;

        $mform = $this->_form; // Don't forget the underscore!

        $mform->addElement('text', 'signer1', 'First Signer Name'); // Add elements to your form
        $mform->setType('signer1', PARAM_NOTAGS);   //Set type of element
        $mform->addElement('text', 'signer1e', 'First Signer Email'); // Add elements to your form
        $mform->addRule('signer1e','please enter an email', 'email');
        $mform->addElement('text', 'signer2', 'Second Signer'); // Add elements to your form
        $mform->setType('signer2', PARAM_NOTAGS);   //Set type of element
        $mform->addElement('text', 'signer2e', 'First Signer Email'); // Add elements to your form
        $mform->addRule('signer2e','please enter an email', 'email');
        $mform->addElement('file', 'contract', 'Contract File');//, null, array('maxbytes' => 5120, 'accepted_types' => '.pdf'));
        $buttonarray = array();
        $buttonarray[] = $mform->createElement('submit', 'send', 'Send');
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


$mform = new sendcontract();
$mform->display();


if ($mform->is_cancelled()) {
    //Handle form cancel operation, if cancel button is present on form
    $url = new moodle_url($CFG->wwwroot . '/local/lecrec/index.php');
    redirect($url);
}


if ($mform->is_submitted()) {

    $success = $mform->save_file('contract', '../assets/pdfs/' . $mform->get_new_filename('contract'), true);
if ($success && $data = $mform->get_data()){
    $filepath = 'local/lecrec/assets/pdfs/' . $mform->get_new_filename('contract');
    $signers[0] = ['email' => $data->signer1e , 'name' =>$data->signer1,
        'recipient_id' => "1", 'routing_order' => "1"];
    $signers[1] = ['email' => $data->signer2e , 'name' => $data->signer2,
        'recipient_id' => "2", 'routing_order' => "2"];
    try {
        new SigningViaEmail($signers, $filepath);
        echo $OUTPUT->notification('Email Sent');
    } catch (Exception $ex){

    }

}

}
