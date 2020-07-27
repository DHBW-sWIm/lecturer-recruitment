<?php


require(dirname(__FILE__, 4) . '/config.php');
require_login();

if ($_SERVER['REQUEST_METHOD'] === 'POST') {
    $input = filter_input_array(INPUT_POST);
} else {
    $input = filter_input_array(INPUT_GET);
}

$RecordID = $input['RecordID'];

global $DB, $PAGE, $OUTPUT, $CFG, $USER;

$PAGE->set_context(context_system::instance());
$PAGE->set_url('/local/lecrec/pages/recruitmentprocess.php');
$PAGE->requires->jquery();
$PAGE->requires->css('/local/lecrec/assets/CSS/application.css');
$PAGE->set_title('Lecturer Applications');
$context = context_system::instance();
$user = $USER->id;

if (has_capability('local/lecrec:manager', $context)) {

    $PAGE->set_heading('Lecturer Recruitment');
    $PAGE->navbar->add('Lecturer Recruitment', new moodle_url('/local/lecrec/index.php'));
    $PAGE->navbar->add('Recruitment Processes', new moodle_url('/local/lecrec/pages/recruitmentprocess.php'));
    $PAGE->navbar->add('Applications', new moodle_url('/local/lecrec/pages/application_overview.php'));
    echo $OUTPUT->header();
    echo $OUTPUT->heading('Application');
    $record = $DB->get_record('lr_application', array('id' => $RecordID));




}
//$record->status_of_application
echo html_writer::tag('br', '');
echo "<div id='tracking' class=\"card\">
        <div class=\"row d-flex justify-content-between px-3 top\">
            <div class=\"d-flex\">
                <h5>ORDER <span class=\"text-primary font-weight-bold\">#Y34XDHR</span></h5>
            </div>
            <div class=\"d-flex flex-column text-sm-right\">
                <p class=\"mb-0\">Last Update: <span>" . date('d.m.Y H:i', $record->timemodified) . "</span></p>
                <p>USPS <span class=\"font-weight-bold\">234094567242423422898</span></p>
            </div>
        </div> <!-- Add class 'active' to progress -->
        <div class=\"row d-flex justify-content-center\">
            <div class=\"col-12\">
                <ul id=\"progressbar\" class=\"text-center\">
                    <li class=\"active step0\"></li>
                    <li class=\"active step0\"></li>
                    <li class=\"active step0\"></li>
                    <li class=\"step0\"></li>
                </ul>
            </div>
        </div>
        <div class=\"row justify-content-between top\">
            <div class=\"row d-flex icon-content\"> <img class=\"icon\" src=\"../assets/images/005-resume.svg\">
                <div class=\"d-flex flex-column\">
                    <p class=\"font-weight-bold\">Application<br>received</p>
                </div>
            </div>
            <div class=\"row d-flex icon-content\"> <img class=\"icon\" src=\"../assets/images/007-chronometer-1.svg\">
                <div class=\"d-flex flex-column\">
                    <p class=\"font-weight-bold\">Waiting to <br>interview</p>
                </div>
            </div>
            <div class=\"row d-flex icon-content\"> <img class=\"icon\" src=\"../assets/images/008-job-interview.svg\">
                <div class=\"d-flex flex-column\">
                    <p class=\"font-weight-bold\">Interviewed</p>
                </div>
            </div>
            <div class=\"row d-flex icon-content\"> <img class=\"icon\" src=\"https://i.imgur.com/HdsziHP.png\">
                <div class=\"d-flex flex-column\">
                    <p class=\"font-weight-bold\">Order<br>Arrived</p>
                </div>
            </div>
        </div>
    </div>";
echo html_writer::start_div('');
echo html_writer::start_div('card');
echo html_writer::start_div('card-header');
echo html_writer::start_tag('a', array('class' => 'card-link', 'data-toggle' => "collapse", 'href' => "#personal-info"));
echo "Personal information";
echo html_writer::end_tag('a');
echo html_writer::end_div();
echo html_writer::start_div('collapse show', array('id' => "personal-info"));
echo html_writer::start_div('card-body');
echo html_writer::start_div('row');
echo html_writer::start_div('col-md-4');
echo html_writer::tag('h6', 'Name and title', array('class' => "font-weight-bold"));
echo html_writer::tag('p', $record->title . ' ' . $record->fname . ' ' . $record->lname);
echo html_writer::tag('h6', 'Home address', array('class' => "font-weight-bold"));
echo html_writer::tag('address', $record->private_add_str . '<br>' . $record->private_add_zip . ' ' . $record->private_add_city . '<br> <abbr title="Phone">P: </abbr>' . $record->private_tele . '<br> <abbr title="Mobile">M: </abbr>' . $record->private_mobile . '<br> <abbr title="Fax">F: </abbr>' . $record->private_fax);
echo html_writer::end_div();
echo html_writer::start_div('col-md-4');
echo html_writer::tag('h6', 'Email address', array('class' => "font-weight-bold"));
echo html_writer::tag('p', $record->private_email);
echo html_writer::tag('h6', 'Education', array('class' => "font-weight-bold"));
echo html_writer::tag('p', $record->education);
echo html_writer::end_div();
echo html_writer::start_div('col-md-4');
echo html_writer::tag('h6', 'Date of birth', array('class' => "font-weight-bold"));
echo html_writer::tag('p', (new DateTime($record->date_of_birth))->format('d-m-Y'));
echo html_writer::tag('h6', 'Place of birth', array('class' => "font-weight-bold"));
echo html_writer::tag('p', $record->place_of_birth);
echo html_writer::end_div();
echo html_writer::end_div();
echo html_writer::end_div();
echo html_writer::end_div();
echo html_writer::end_div();
echo html_writer::start_div('');
echo html_writer::start_div('card');
echo html_writer::start_div('card-header');
echo html_writer::start_tag('a', array('class' => 'card-link', 'data-toggle' => "collapse", 'href' => "#work-info"));
echo "Work information";
echo html_writer::end_tag('a');
echo html_writer::end_div();
echo html_writer::start_div('collapse show', array('id' => "work-info"));
echo html_writer::start_div('card-body');
echo html_writer::start_div('row');
echo html_writer::start_div('col-md-4');
echo html_writer::tag('h6', 'Company name', array('class' => "font-weight-bold"));
echo html_writer::tag('p', $record->company);
echo html_writer::tag('h6', 'Work address', array('class' => "font-weight-bold"));
echo html_writer::tag('address', $record->company_add_str . '<br>' . $record->company_add_zip . ' ' . $record->company_add_city . '<br> <abbr title="Phone">P: </abbr>' . $record->company_tele . '<br> <abbr title="Fax">F: </abbr>' . $record->company_fax);
echo html_writer::end_div();
echo html_writer::start_div('col-md-4');
echo html_writer::tag('h6', 'Work Email address', array('class' => "font-weight-bold"));
echo html_writer::tag('p', $record->company_email);
echo html_writer::tag('h6', 'Job', array('class' => "font-weight-bold"));
echo html_writer::tag('p', $record->job);
echo html_writer::end_div();
echo html_writer::end_div();
echo html_writer::end_div();
echo html_writer::end_div();
echo html_writer::end_div();
echo html_writer::start_div('');
echo html_writer::start_div('card');
echo html_writer::start_div('card-header');
echo html_writer::start_tag('a', array('class' => 'card-link', 'data-toggle' => "collapse", 'href' => "#interests"));
echo "Experience and Preferences ";
echo html_writer::end_tag('a');
echo html_writer::end_div();
echo html_writer::start_div('collapse show', array('id' => "interests"));
echo html_writer::start_div('card-body');
echo html_writer::start_div('row');
echo html_writer::start_div('col-md-4');
echo html_writer::tag('h6', 'Previous teaching activities', array('class' => "font-weight-bold"));
echo html_writer::tag('p', $record->teaching_activities);
echo html_writer::end_div();
echo html_writer::start_div('col-md-4');
echo html_writer::tag('h6', 'Job activities', array('class' => "font-weight-bold"));
echo html_writer::tag('p', $record->job_activities);
echo html_writer::end_div();
echo html_writer::start_div('col-md-4');
echo html_writer::tag('h6', 'Subjects the applicant is interested in teaching', array('class' => "font-weight-bold"));
echo html_writer::tag('p', $record->subject_of_interest);
echo html_writer::end_div();
echo html_writer::end_div();
echo html_writer::end_div();
echo html_writer::end_div();
echo html_writer::end_div();
echo html_writer::end_div();
echo $OUTPUT->footer();
