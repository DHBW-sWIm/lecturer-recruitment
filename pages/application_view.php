
<?php
//Bewerberprofil über Anwendung: Recruitment Prozesse

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
    $PAGE->navbar->add('Bewerber', new moodle_url('/local/lecrec/pages/application_overview.php'));
    echo $OUTPUT->header();
    echo $OUTPUT->heading('Bewerber');
    $record = $DB->get_record('lr_application', array('id' => $RecordID));
}
$icon = '010-mail.svg';
$status = 'pending';
if ($record->status_of_application === 'Abgelehnt') {
    $interviewsent = 'Aktiv';
    $accepted = 'Nicht Aktiv';
    $icon = '003-reject-2.svg';
    $status = 'Abgelehnt';
} elseif ($record->status_of_application === 'Akzeptiert') {
    $interviewsent = 'Aktiv';
    $accepted = 'Aktiv';
    $status = 'Akzeptiert';
} elseif ($record->status_of_application === 'Einladung gesendet') {
    $interviewsent = 'Aktiv';


}
//$record->status_of_application
echo html_writer::tag('br', '');
echo "<div id='tracking' class=\"card\">
        <div class=\"row d-flex justify-content-between px-3 top\">
            <div class=\"d-flex\">
                <h5><span class=\"text-primary\"> " . $record->title . ' ' . $record->fname . ' ' . $record->lname . "</span></h5>
            </div>
            <div class=\"d-flex flex-column text-sm-right\">
                 <p>Application received on: <span >" . date('d.m.Y H:i', $record->timecreated) . "</span></p>
                <p class=\"mb-0\">Last Update: <span>" . date('d.m.Y H:i', $record->timemodified) . "</span></p>
            </div>
        </div> <!-- Add class 'active' to progress -->
        <div class=\"row d-flex justify-content-center\">
            <div class=\"col-12\">
                <ul id=\"progressbar\" class=\"text-center\">
                    <li class=\"active step0\"></li>
                    <li class=\"" . $interviewsent . " step0\"></li>
                    <li class=\"" . $accepted . " step0\"></li>          
                </ul>
            </div>
        </div>
        <div class=\"row justify-content-between top\">
            <div class=\"row d-flex icon-content\"> <img class=\"icon\" src=\"../assets/images/005-resume.svg\">
                <div class=\"d-flex flex-column\">
                    <p class=\"font-weight-bold\">Application<br>received</p>
                </div>
            </div> 
            <div class=\"row d-flex icon-content\"> <img class=\"icon\" src=\"../assets/images/mail-send.svg\">
                <div class=\"d-flex flex-column\">
                    <p class=\"font-weight-bold\">Invitation<br>sent </p>
                </div>
            </div>
            <div class=\"row d-flex icon-content\"> <img class=\"icon\" src=\"../assets/images/" . $icon . "\">
                <div class=\"d-flex flex-column\">
                    <p class=\"font-weight-bold\">Applicant<br>" . $status . "</p>
                </div>
            </div>
        </div>
    </div>";
echo html_writer::start_div('');
echo html_writer::start_div('card');
echo html_writer::start_div('card-header');
echo html_writer::start_tag('a', array('class' => 'card-link', 'data-toggle' => "collapse", 'href' => "#personal-info"));
echo "Persönliche Informationen";
echo html_writer::end_tag('a');
echo html_writer::end_div();
echo html_writer::start_div('collapse show', array('id' => "personal-info"));
echo html_writer::start_div('card-body');
echo html_writer::start_div('row');
echo html_writer::start_div('col-md-4');
echo html_writer::tag('h6', 'Name und Titel', array('class' => "font-weight-bold"));
echo html_writer::tag('p', $record->title . ' ' . $record->fname . ' ' . $record->lname);
echo html_writer::tag('h6', 'Private Adresse', array('class' => "font-weight-bold"));
echo html_writer::tag('Addresse', $record->private_add_str . '<br>' . $record->private_add_zip . ' ' . $record->private_add_city . '<br> <abbr title="Phone">P: </abbr>' . $record->private_tele . '<br> <abbr title="Mobile">M: </abbr>' . $record->private_mobile . '<br> <abbr title="Fax">F: </abbr>' . $record->private_fax);
echo html_writer::end_div();
echo html_writer::start_div('col-md-4');
echo html_writer::tag('h6', 'E-Mail Adresse', array('class' => "font-weight-bold"));
echo html_writer::tag('p', $record->private_email);
echo html_writer::tag('h6', 'Ausbildung', array('class' => "font-weight-bold"));
echo html_writer::tag('p', $record->education);
echo html_writer::end_div();
echo html_writer::start_div('col-md-4');
echo html_writer::tag('h6', 'Geburtsdatum', array('class' => "font-weight-bold"));
echo html_writer::tag('p', (new DateTime($record->date_of_birth))->format('d-m-Y'));
echo html_writer::tag('h6', 'Geburtsort', array('class' => "font-weight-bold"));
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
echo "Informationen zur Arbeitsstelle";
echo html_writer::end_tag('a');
echo html_writer::end_div();
echo html_writer::start_div('collapse show', array('id' => "work-info"));
echo html_writer::start_div('card-body');
echo html_writer::start_div('row');
echo html_writer::start_div('col-md-4');
echo html_writer::tag('h6', 'Firmenname', array('class' => "font-weight-bold"));
echo html_writer::tag('p', $record->company);
echo html_writer::tag('h6', 'Firmenadresse', array('class' => "font-weight-bold"));
echo html_writer::tag('address', $record->company_add_str . '<br>' . $record->company_add_zip . ' ' . $record->company_add_city . '<br> <abbr title="Phone">P: </abbr>' . $record->company_tele . '<br> <abbr title="Fax">F: </abbr>' . $record->company_fax);
echo html_writer::end_div();
echo html_writer::start_div('col-md-4');
echo html_writer::tag('h6', 'Firmen E-Mail Adresse', array('class' => "font-weight-bold"));
echo html_writer::tag('p', $record->company_email);
echo html_writer::tag('h6', 'Berufsbezeichnung', array('class' => "font-weight-bold"));
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
echo "Erfahrungen und Präferenzen";
echo html_writer::end_tag('a');
echo html_writer::end_div();
echo html_writer::start_div('collapse show', array('id' => "interests"));
echo html_writer::start_div('card-body');
echo html_writer::start_div('row');
echo html_writer::start_div('col-md-4');
echo html_writer::tag('h6', 'Vorherige Lehraktivitäten', array('class' => "font-weight-bold"));
echo html_writer::tag('p', $record->teaching_activities);
echo html_writer::end_div();
echo html_writer::start_div('col-md-4');
echo html_writer::tag('h6', 'Berufliche Aufgaben', array('class' => "font-weight-bold"));
echo html_writer::tag('p', $record->job_activities);
echo html_writer::end_div();
echo html_writer::start_div('col-md-4');
echo html_writer::tag('h6', 'Präferenzierte Lehrfächer', array('class' => "font-weight-bold"));
echo html_writer::tag('p', $record->subject_of_interest);
echo html_writer::end_div();
echo html_writer::end_div();
echo html_writer::end_div();
echo html_writer::end_div();
echo html_writer::end_div();
echo html_writer::end_div();
//TODO connect with camunda to qualify there

if ($record->status_of_application == 'Einladung versandt') {
    echo '<br><form action="../assets/PHPFunctions/respond_to_application.php" method="post">
<div class="row mx-auto">
<input name="app_id" value="' . $RecordID . '" hidden>
<input type ="submit" name="approve" class="btn btn-success" value="Genehmigen">
<input type ="submit" name="qualify" class="btn btn-info" value="Neues Gespräch">
<input type ="submit" name="reject" class="btn btn-danger" value="Ablehnen">
</div>
</from>';
} else if ($record->status_of_application == 'Beworben') {
    echo '<br><form action="../assets/PHPFunctions/respond_to_application.php" method="post">
<div class="row mx-auto">
<input name="app_id" value="' . $RecordID . '" hidden>
<input type ="submit" name="qualify" class="btn btn-success" value="Zu 1. Gespräch einladen">
<input type ="submit" name="reject" class="btn btn-danger" value="Ablehnen">
</div>
</from>';
} else if ($record->status_of_application == 'Gespräch ausstehend') {
    echo '<br><form action="../assets/PHPFunctions/respond_to_application.php" method="post">
<div class="row mx-auto">
<input name="app_id" value="' . $RecordID . '" hidden>
<input type ="submit" name="qualify" class="btn btn-info" value="Neues Gespräch">
<input type ="submit" name="reject" class="btn btn-danger" value="Ablehnen">
</div>
</from>';
}

echo $OUTPUT->footer();
