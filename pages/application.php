<?php

require(dirname(__FILE__, 4) . '/config.php');
require_once("$CFG->libdir/formslib.php");
global $PAGE, $DB, $USER;
if ($_SERVER['REQUEST_METHOD'] === 'POST') {
    $input = filter_input_array(INPUT_POST);
    $name = $input['ID'];
    $rowID = $input['rowID'];
} else {
    $input = filter_input_array(INPUT_GET);
};


if (!isset($_SESSION['$rowID'])) {
    $_SESSION['$rowID'] = $rowID;
}

$PAGE->set_context(context_system::instance());
$PAGE->set_url('/local/lecrec/pages/teachingpostings.php');
$PAGE->set_title('Lecturer Recruitment');
echo $OUTPUT->header();
echo $OUTPUT->heading('Bewerberformular für ' . $name . '');
$PAGE->requires->jquery();
$context = context_system::instance();

echo '</br></br>';


class addlecturer extends moodleform
{

    //Add elements to form
    public function definition()
    {
        global $DB;

        $mform = $this->_form; // Don't forget the underscore!


        $mform->addElement('text', 'title', 'Title', 'size="50"');
        $mform->setType('title', PARAM_NOTAGS);
        $mform->addElement('text', 'lastname', 'Last Name', 'size="50"');
        $mform->setType('lastname', PARAM_NOTAGS);
        $mform->addElement('text', 'firstname', 'First Name', 'size="50"');
        $mform->setType('firstname', PARAM_NOTAGS);
        $mform->addElement('date_selector', 'dateofbirth', 'Date of Birth ');
        $mform->addElement('text', 'place_of_birth', 'Place of Birth ');
        //  $mform->addElement('advcheckbox', 'self_employed', 'Self Employed', 'Check if self employed', '', array(0, 1));
        $private_address = array();
        $mform->addGroup($private_address, null, 'Private Data', null, false);
        $mform->addElement('text', 'private_street', 'Street and Nr.', 'size="50"');
        $mform->setType('private_street', PARAM_NOTAGS);
        $mform->addElement('text', 'private_postalcode', 'Postal Code', 'size="50"');
        $mform->addRule('private_postalcode', null, 'numeric', null, 'client');
        $mform->addRule('private_postalcode', 'Only numbers are allowed', 'required', null, 'client');
        $mform->addRule('private_postalcode', null, 'minlength', 5, 'client');
        $mform->addRule('private_postalcode', null, 'maxlength', 5, 'client');
        $mform->addElement('text', 'private_city', 'City', 'size="50"');
        $mform->setType('private_city', PARAM_NOTAGS);

        $mform->addElement('text', 'private_phonenumber', 'Private Phone Number', 'size="50"');
        $mform->addRule('private_phonenumber', null, 'numeric', null, 'client');
        $mform->setType('private_phonenumber', PARAM_NOTAGS);
        $mform->addElement('text', 'private_cellphone_number', 'Cellphone Number', 'size="50"');
        $mform->addRule('private_cellphone_number', null, 'numeric', null, 'client');
        $mform->setType('private_cellphone_number', PARAM_NOTAGS);

        $mform->addElement('text', 'private_fax', 'Private Fax', 'size="50"');
        $mform->setType('private_fax', PARAM_NOTAGS);

        $mform->addElement('text', 'private_mail', 'Private Email', 'size="50"');
        $mform->addRule('private_mail', null, 'email', null, 'client');
        $business_address = array();
        $mform->addGroup($business_address, null, 'Work Data', null, false);
        $mform->addElement('text', 'business_street', 'Street and Nr.', 'size="50"');
        $mform->setType('business_street', PARAM_NOTAGS);
        $mform->addElement('text', 'business_postalcode', 'Postal Code', 'size="50"');
        $mform->addRule('business_postalcode', null, 'numeric', null, 'client');
        $mform->addRule('business_postalcode', 'Only numbers are allowed', 'required', null, 'client');
        $mform->addRule('business_postalcode', null, 'minlength', 5, 'client');
        $mform->addRule('business_postalcode', null, 'maxlength', 5, 'client');
        $mform->addElement('text', 'business_city', 'City', 'size="50"');
        $mform->setType('business_city', PARAM_NOTAGS);

        $mform->addElement('text', 'job', 'Job', 'size="50"');
        $mform->setType('job', PARAM_NOTAGS);

        $mform->addElement('text', 'business_phonenumber', 'Work Phone Number', 'size="50"');
        $mform->addRule('business_phonenumber', null, 'numeric', null, 'client');
        $mform->setType('business_phonenumber', PARAM_NOTAGS);
        $mform->addElement('text', 'company', 'Company Name', 'size="50"');
        $mform->setType('company', PARAM_NOTAGS);
        $mform->addElement('text', 'business_mail', 'Work Email', 'size="50"');
        $mform->addElement('text', 'company_fax', 'Company Fax', 'size="50"');
        $mform->setType('company_fax', PARAM_NOTAGS);
        $mform->addRule('business_mail', null, 'email', null, 'client');

        $mform->addElement('text', 'subject_area', 'Education', 'size="50"');
        $mform->setType('subject_area', PARAM_NOTAGS);

        $mform->addElement('textarea', 'previous_teaching_activities', 'Previous Teaching Activities', 'wrap="virtual" rows="8" cols="70"');
        $mform->setType('previous_teaching_activities', PARAM_NOTAGS);
        $mform->addElement('textarea', 'professional_activities', 'Professional Activities', 'wrap="virtual" rows="8" cols="70"');
        $mform->setType('professional_activities', PARAM_NOTAGS);
        $mform->addElement('textarea', 'education', 'Subjects you\'re interested in teaching', 'wrap="virtual" rows="8" cols="70"');
        $mform->setType('education', PARAM_NOTAGS);


        unset($_SESSION['data']);
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


$mform = new addlecturer();
$mform->display();


if ($mform->is_cancelled()) {
    //Handle form cancel operation, if cancel button is present on form
    $url = new moodle_url($CFG->wwwroot . '/local/lecrec/index.php');
    redirect($url);
} else if ($fromform = $mform->get_data()) {
    //In this case you process validated data. $mform->get_data() returns data posted in form.
}

if ($mform->is_submitted()) {
    $input = (array)$mform->get_data();
    $rowID = $_SESSION['$rowID'];
    unset($_SESSION['$rowID']);
    $input['dateofbirth'] = date('Y-m-d', $input['dateofbirth']);
    try {
        $id = $DB->insert_record("lr_application", array(
            'lr_job_postings_id' => $rowID,
            'fname' => $input['firstname'], 'lname' => $input['lastname'],
            'title' => $input['title'],
            'date_of_birth' => $input['dateofbirth'], 'place_of_birth' => $input['place_of_birth'],
            'job' => $input['job'], 'private_add_str' => $input['private_street'],
            'private_add_zip' => $input['private_postalcode'], 'private_add_city' => $input['private_city'],
            'private_tele' => $input['private_phonenumber'],
            'private_email' => $input['private_mail'],
            'private_mobile' => $input['private_cellphone_number'],
            'private_fax' => $input['private_fax'], 'company' => $input['company'],
            'company_add_str' => $input['business_street'], 'company_add_zip' => $input['business_postalcode'],
            'company_add_city' => $input['business_city'], 'company_tele' => $input['business_phonenumber'],
            'education' => $input['education'], 'company_fax' => $input['company_fax'],
            'company_email' => $input['business_mail'], 'teaching_activities' => $input['previous_teaching_activities'],
            'job_activities' => $input['professional_activities'], 'subject_of_interest' => $input['subject_area'],
            'timecreated' => time(),
            'timemodified' => time(),
            'status_of_application' => 'Applied'

        ));
        if (empty($from)) {
            $from = 'Lecturer Recruitment';
        }
        $posting = $DB->get_record('lr_job_postings', array('id' => $rowID));
        $managerid = $posting->director_id;
        $subject = $DB->get_record('lr_subjects', array('id' => $posting->lr_subjects_id))->lr_subject_name;
        $user = $DB->get_record('user', array('id' => $managerid));
        $title = 'New Application Received';
        $form = '<html><head><meta http-equiv="Content-Type" content="text/html; charset=iso-8859-1"><style></style></head>';
        $form .= '<body><span style="font-family: Arial; font-size: 10pt;">Dear ' . $user->first_name . ',<br>';
        $form .= '<br>A new person has applied to ' . $subject . '<br>';
        $form .= '<b>Name: ' . $input['title'] . ' ' . $input['firstname'] . ' ' . $input['lastname'] . '</b><br>';
        $form .= '<br>Kind regards<br>DHBW Mannheim';
        email_to_user($user, $from, $title, $form);
        redirect(new moodle_url('/local/lecrec/pages/teachingpostings.php'));
    } catch (Exception $ex) {

    }
}
$companies = $DB->get_records('dg_company');
echo '
<datalist id="id_companies">';
foreach ($companies as $company) {

    echo '<option value="' . $company->company_name . '">';
}
echo '</datalist>';

?>
    <script>
        $('#id_company').attr('list', 'id_company');
    </script>
<?php
echo $OUTPUT->footer();