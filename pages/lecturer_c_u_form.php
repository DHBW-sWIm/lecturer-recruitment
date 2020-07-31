<?php

if ($_SERVER['REQUEST_METHOD'] == 'POST') {
    $input = filter_input_array(INPUT_POST);
} else {
    $input = filter_input_array(INPUT_GET);
};

require(dirname(__FILE__, 4) . '/config.php');
require_once("$CFG->libdir/formslib.php");
global $PAGE, $USER, $DB;
$PAGE->set_context(context_system::instance());
$PAGE->set_url('/local/lecrec/pages/lecturer_c_u_form.php');
$PAGE->set_title('Lecturer Recruitment');
$PAGE->requires->jquery();
$context = context_system::instance();


$PAGE->set_heading("Add new lecturer");
echo $OUTPUT->header();

//TODO: add applicant to lecturer pool

if ($input['status'] == 'update') {

    $data = $DB->get_record_select('lr_lecturer', 'id = ?', array($input['ID']));

    $data = array(
        'lastname' => $data->lastname, 'firstname' => $data->firstname, 'title' => $data->title, 'dateofbirth' => $data->dateofbirth,
        'self_employed' => $data->self_employed, 'private_street' => $data->private_street, 'private_postalcode' => $data->private_postalcode, 'private_city' => $data->private_city,
        'private_state' => $data->private_state, 'private_phonenumber' => $data->private_phonenumber, 'private_cellphone_number' => $data->private_cellphone_number, 'private_mail' => $data->private_mail,
        'company' => $data->company, 'business_phonenumber' => $data->business_phonenumber, 'business_mail' => $data->business_mail, 'previous_teaching_activities' => $data->previous_teaching_activities,
        'professional_activities' => $data->professional_activities, 'educational_interest' => $data->educational_interest, 'subject_area' => $data->subject_area, 'dg_company_id' => $data->dg_company_id
    );
} else {
    $data = array(
        'lastname' => null, 'firstname' => null, 'title' => null, 'dateofbirth' => null,
        'self_employed' => null, 'private_street' => null, 'private_postalcode' => null, 'private_city' => null,
        'private_state' => null, 'private_phonenumber' => null, 'private_cellphone_number' => null, 'private_mail' => null,
        'company' => null, 'business_phonenumber' => null, 'business_mail' => null, 'previous_teaching_activities' => null,
        'professional_activities' => null, 'educational_interest' => null, 'subject_area' => null, 'dg_company_id' => null
    );
}

$_SESSION['data'] = $data;

class addlecturer extends moodleform
{

    //Add elements to form
    public function definition()
    {
        global $DB;
        $values = (object)$_SESSION['data'];
        $mform = $this->_form; // Don't forget the underscore!


        $records = $DB->get_records('lr_module');
        $modules[] = null;
        foreach ($records as $item) {
            $modules[$item->id] = $item->module_name;
        }
        $mform->addElement('text', 'title', 'Title', 'size="50"')->setValue($values->title);
        $mform->setType('title', PARAM_NOTAGS);
        $mform->addElement('text', 'lastname', 'Last Name', 'size="50"')->setValue($values->lastname);
        $mform->setType('lastname', PARAM_NOTAGS);
        $mform->addElement('text', 'firstname', 'First Name', 'size="50"')->setValue($values->firstname);
        $mform->setType('firstname', PARAM_NOTAGS);
        $mform->addElement('date_selector', 'dateofbirth', 'Date of Birth ')->setValue($values->dateofbirth);
        $mform->addElement('advcheckbox', 'self_employed', 'Self Employed', 'Check if self employed', '', array(0, 1))->setValue($values->self_employed);
        $private_address = array();
        $mform->addGroup($private_address, null, 'Private Data', null, false);
        $mform->addElement('text', 'private_street', 'Street and Nr.', 'size="50"')->setValue($values->private_street);
        $mform->setType('private_street', PARAM_NOTAGS);
        $mform->addElement('text', 'private_postalcode', 'Postal Code', 'size="50"')->setValue($values->private_postalcode);
        $mform->addRule('private_postalcode', null, 'numeric', null, 'client');
        $mform->addRule('private_postalcode', 'Only numbers are allowed', 'required', null, 'client');
        $mform->addRule('private_postalcode', null, 'minlength', 5, 'client');
        $mform->addRule('private_postalcode', null, 'maxlength', 5, 'client');
        $mform->addElement('text', 'private_city', 'City', 'size="50"')->setValue($values->private_city);
        $mform->setType('private_city', PARAM_NOTAGS);
        $mform->addElement('text', 'private_state', 'State', 'size="50"')->setValue($values->private_state);
        $mform->setType('private_state', PARAM_NOTAGS);
        $mform->addElement('text', 'private_phonenumber', 'Private Phone Number', 'size="50"')->setValue($values->private_phonenumber);
        $mform->addRule('private_phonenumber', null, 'numeric', null, 'client');
        $mform->setType('private_phonenumber', PARAM_NOTAGS);
        $mform->addElement('text', 'private_cellphone_number', 'Cellphone Number', 'size="50"')->setValue($values->private_cellphone_number);
        $mform->addRule('private_cellphone_number', null, 'numeric', null, 'client');
        $mform->setType('private_cellphone_number', PARAM_NOTAGS);
        $mform->addElement('text', 'private_mail', 'Private Email', 'size="50"')->setValue($values->private_mail);
        $mform->addRule('private_mail', null, 'email', null, 'client');
        $business_address = array();
        $mform->addGroup($business_address, null, 'Work Data', null, false);
        $mform->addElement('text', 'business_street', 'Street and Nr.', 'size="50"')->setValue($values->business_street);
        $mform->setType('business_street', PARAM_NOTAGS);
        $mform->addElement('text', 'business_postalcode', 'Postal Code', 'size="50"')->setValue($values->business_postalcode);
        $mform->addRule('business_postalcode', null, 'numeric', null, 'client');
        $mform->addRule('business_postalcode', 'Only numbers are allowed', 'required', null, 'client');
        $mform->addRule('business_postalcode', null, 'minlength', 5, 'client');
        $mform->addRule('business_postalcode', null, 'maxlength', 5, 'client');
        $mform->addElement('text', 'business_city', 'City', 'size="50"')->setValue($values->business_city);
        $mform->setType('business_city', PARAM_NOTAGS);
        $mform->addElement('text', 'business_state', 'State', 'size="50"')->setValue($values->business_state);
        $mform->setType('business_state', PARAM_NOTAGS);
        $mform->addElement('text', 'business_phonenumber', 'Work Phone Number', 'size="50"')->setValue($values->business_phonenumber);
        $mform->addRule('business_phonenumber', null, 'numeric', null, 'client');
        $mform->setType('business_phonenumber', PARAM_NOTAGS);
        $mform->addElement('text', 'company', 'Company Name', 'size="50"')->setValue($values->company);
        $mform->setType('company', PARAM_NOTAGS);
        $mform->addElement('text', 'business_mail', 'Work Email', 'size="50"')->setValue($values->business_mail);
        $mform->addRule('business_mail', null, 'email', null, 'client');
        $mform->addElement('textarea', 'previous_teaching_activities', 'Previous Teaching Activities', 'size="50"')->setValue($values->previous_teaching_activities);
        $mform->setType('previous_teaching_activities', PARAM_NOTAGS);
        $mform->addElement('textarea', 'professional_activities', 'Professional Activities', 'size="50"')->setValue($values->professional_activities);
        $mform->setType('professional_activities', PARAM_NOTAGS);
        $mform->addElement('textarea', 'educational_interest', 'Educational Interest', 'size="50"')->setValue($values->educational_interest);
        $mform->setType('educational_interest', PARAM_NOTAGS);
        $mform->addElement('text', 'subject_area', 'Subject Area', 'size="50"')->setValue($values->subject_area);
        $mform->setType('subject_area', PARAM_NOTAGS);
        /*
                $mform->setType('directorid', PARAM_NOTAGS);
                $mform->addElement('select', 'module', 'Module', $modules, array('onchange' => 'javascript:loadSubjects();'))->setValue(;
                $mform->addElement('select', 'subject', 'Subject',); // Add elements to your form
                $mform->setType('subject', PARAM_NOTAGS);                   //Set type of element


                $mform->addElement('select',);
                $mform->addElement('date_selector', 'startdate', 'Start Date');
                $mform->addElement('date_selector', 'enddate', 'End Date');


                $mform->addElement('text', 'contactperson', 'Contact Person', 'size="50"');
                $mform->setType('contactperson', PARAM_NOTAGS);

                $mform->addElement('text', 'emailcontactperson', 'E-Mail Contact Person', 'size="50"');
                $mform->setType('emailcontactperson', PARAM_NOTAGS);*/
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
    $data = $mform->get_data();
    $record = $DB->get_record_select('dg_company', 'company_name = ?', array($data->company));
    if ($record) {
        $data->company_id = $record->id;
        $data->company = $record->company_name;
    } else {
        $like = '"' . $data->company . '%"';
        $record = $DB->get_record_sql("SELECT id , company_name FROM {dg_company} WHERE company_name LIKE $like");
        if ($record) {
            $data->company_id = $record->id;
            $data->company = $record->company_name;
        } else {
            $data->company_id = '0';
        }
    }
    $exists = $DB->get_record_select('lr_lecturer', 'firstname = ? AND lastname = ? AND private_mail = ?', array($data->firstname, $data->lastname, $data->private_mail));
    if ($exists) {
        echo '<script type="text/javascript">alert("This Record Already Exists")</script>';
    } else {
        $DB->insert_record('lr_lecturer', array(
            'lastname' => $data->lastname, 'firstname' => $data->firstname, 'title' => $data->title, 'dateofbirth' => $data->dateofbirth,
            'self_employed' => $data->self_employed, 'private_street' => $data->private_street, 'private_postalcode' => $data->private_postalcode, 'private_city' => $data->private_city,
            'private_state' => $data->private_state, 'private_phonenumber' => $data->private_phonenumber, 'private_cellphone_number' => $data->private_cellphone_number, 'private_mail' => $data->private_mail,
            'company' => $data->company, 'business_phonenumber' => $data->business_phonenumber, 'business_mail' => $data->business_mail, 'previous_teaching_activities' => $data->previous_teaching_activities,
            'professional_activities' => $data->professional_activities, 'educational_interest' => $data->educational_interest, 'subject_area' => $data->subject_area, 'dg_company_id' => $data->company_id
        ));
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
    $('#id_company').attr('list', 'id_companies');
</script>
<?php
echo $OUTPUT->footer();
