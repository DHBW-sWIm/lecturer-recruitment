<?php
// This file is part of Moodle - http://moodle.org/
//
// Moodle is free software: you can redistribute it and/or modify
// it under the terms of the GNU General Public License as published by
// the Free Software Foundation, either version 3 of the License, or
// (at your option) any later version.
//
// Moodle is distributed in the hope that it will be useful,
// but WITHOUT ANY WARRANTY; without even the implied warranty of
// MERCHANTABILITY or FITNESS FOR A PARTICULAR PURPOSE.  See the
// GNU General Public License for more details.
//
// You should have received a copy of the GNU General Public License
// along with Moodle.  If not, see <http://www.gnu.org/licenses/>.

/**
 * Plugin upgrade steps are defined here.
 *
 * @package     local_lecrec
 * @category    upgrade
 * @copyright   nico_andres@gmx.de
 * @license     http://www.gnu.org/copyleft/gpl.html GNU GPL v3 or later
 */

defined('MOODLE_INTERNAL') || die();

require_once(__DIR__ . '/upgradelib.php');

/**
 * Execute local_lecrec upgrade from the given old version.
 *
 * @param int $oldversion
 * @return bool
 */
function xmldb_local_lecrec_upgrade($oldversion)
{
    global $DB;

    $dbman = $DB->get_manager();

    // For further information please read the Upgrade API documentation:
    // https://docs.moodle.org/dev/Upgrade_API
    //
    // You will also have to create the db/install.xml file by using the XMLDB Editor.
    // Documentation for the XMLDB Editor can be found at:
    // https://docs.moodle.org/dev/XMLDB_editor

    if ($oldversion < 2020062313) {

        // Define field sr_course_id to be added to lr_job_postings.
        $table = new xmldb_table('lr_job_postings');
        $field = new xmldb_field('sr_course_id', XMLDB_TYPE_INTEGER, '10', null, null, null, null);

        // Conditionally launch add field sr_course_id.
        if (!$dbman->field_exists($table, $field)) {
            $dbman->add_field($table, $field);
        }
        // Define table sr_active_study_course to be created.
        $table = new xmldb_table('sr_active_study_course');

        // Adding fields to table sr_active_study_course.
        $table->add_field('id', XMLDB_TYPE_INTEGER, '18', null, XMLDB_NOTNULL, XMLDB_SEQUENCE, null);
        $table->add_field('study_course_name', XMLDB_TYPE_CHAR, '255', null, null, null, null);
        $table->add_field('study_course_abbreviation', XMLDB_TYPE_CHAR, '45', null, null, null, null);
        $table->add_field('start_date', XMLDB_TYPE_DATETIME, null, null, null, null, null);
        $table->add_field('end_date', XMLDB_TYPE_DATETIME, null, null, null, null, null);
        $table->add_field('sr_process_id', XMLDB_TYPE_INTEGER, '18', null, XMLDB_NOTNULL, null, '0');
        $table->add_field('course_capacity', XMLDB_TYPE_INTEGER, '18', null, null, null, null);
        $table->add_field('sr_employees_id', XMLDB_TYPE_INTEGER, '18', null, null, null, null);
        $table->add_field('sr_study_fields_id', XMLDB_TYPE_INTEGER, '18', null, null, null, null);
        $table->add_field('usermodified', XMLDB_TYPE_INTEGER, '10', null, XMLDB_NOTNULL, null, '0');
        $table->add_field('timecreated', XMLDB_TYPE_DATETIME, null, null, null, null, null);
        $table->add_field('timemodified', XMLDB_TYPE_INTEGER, '10', null, XMLDB_NOTNULL, null, '0');
        $table->add_field('closed', XMLDB_TYPE_INTEGER, '10', null, null, null, '0');

        // Adding keys to table sr_active_study_course.
        $table->add_key('primary', XMLDB_KEY_PRIMARY, ['id']);
        // Conditionally launch create table for sr_active_study_course.
        if (!$dbman->table_exists($table)) {
            $dbman->create_table($table);
        }

        // Define field cp_name to be dropped from lr_job_postings.
        $table = new xmldb_table('lr_job_postings');
        $field = new xmldb_field('contact_person');

        // Conditionally launch drop field cp_name.
        if ($dbman->field_exists($table, $field)) {
            $dbman->drop_field($table, $field);
        }

        $field = new xmldb_field(
            'cp_name',
            XMLDB_TYPE_CHAR,
            '255',
            null,
            null,
            null,
            null,
            'director_id'
        );

        // Conditionally launch add field cp_name.
        if (!$dbman->field_exists($table, $field)) {
            $dbman->add_field($table, $field);
        }
        $field = new xmldb_field(
            'cp_email',
            XMLDB_TYPE_CHAR,
            '255',
            null,
            null,
            null,
            null,
            'cp_name'
        );

        // Conditionally launch add field cp_email.
        if (!$dbman->field_exists($table, $field)) {
            $dbman->add_field($table, $field);
        }
        $field = new xmldb_field(
            'cp_phone',
            XMLDB_TYPE_INTEGER,
            '10',
            null,
            null,
            null,
            null,
            'cp_email'
        );

        // Conditionally launch add field cp_phone.
        if (!$dbman->field_exists($table, $field)) {
            $dbman->add_field($table, $field);
        }
        $table = new xmldb_table('dg_company');

        // Adding fields to table dg_company.
        $table->add_field('id', XMLDB_TYPE_INTEGER, '10', null, XMLDB_NOTNULL, XMLDB_SEQUENCE, null);
        $table->add_field('company_name', XMLDB_TYPE_CHAR, '255', null, null, null, null);
        $table->add_field('classification', XMLDB_TYPE_CHAR, '8', null, null, null, 'B');

        // Adding keys to table dg_company.
        $table->add_key('primary', XMLDB_KEY_PRIMARY, ['id']);

        // Conditionally launch create table for dg_company.
        if (!$dbman->table_exists($table)) {
            $dbman->create_table($table);
        }
        // Rename field company on table lr_lecturer to NEWNAMEGOESHERE.
          $table = new xmldb_table('lr_lecturer');
        //$field = new xmldb_field('company_id', XMLDB_TYPE_INTEGER, 9, null, null, null, null, 'private_mail');

        // Launch rename field company.
         $dbman->rename_field($table, $field, 'company');

         $field = new xmldb_field('company', XMLDB_TYPE_CHAR, '255', null, null, null, null, 'private_mail');

        //         Launch change of type for field company.
        $dbman->change_field_type($table, $field);
        //         Lecrec savepoint reached.
        $table = new xmldb_table('lr_application');
        $field = new xmldb_field('email');


        if ($dbman->field_exists($table, $field)) {
            $dbman->drop_field($table, $field);
        }
        $table = new xmldb_table('lr_application');
        $field = new xmldb_field('usermodified', XMLDB_TYPE_INTEGER, '10', null, XMLDB_NOTNULL, null, '0', 'subject_of_interest');

        // Conditionally launch add field usermodified.
        if (!$dbman->field_exists($table, $field)) {
            $dbman->add_field($table, $field);
        }
        // Define field timecreated to be added to lr_application.
        $table = new xmldb_table('lr_application');
        $field = new xmldb_field('timecreated', XMLDB_TYPE_INTEGER, '10', null, XMLDB_NOTNULL, null, '0', 'usermodified');

        // Conditionally launch add field timecreated.
        if (!$dbman->field_exists($table, $field)) {
            $dbman->add_field($table, $field);
        }

        // Define field timemodified to be added to lr_application.
        $table = new xmldb_table('lr_application');
        $field = new xmldb_field('timemodified', XMLDB_TYPE_INTEGER, '10', null, XMLDB_NOTNULL, null, '0', 'timecreated');

        // Conditionally launch add field timemodified.
        if (!$dbman->field_exists($table, $field)) {
            $dbman->add_field($table, $field);
        }
        $table = new xmldb_table('lr_job_postings');
        $field = new xmldb_field('usermodified', XMLDB_TYPE_INTEGER, '10', null, XMLDB_NOTNULL, null, '0', null);

        // Conditionally launch add field usermodified.
        if (!$dbman->field_exists($table, $field)) {
            $dbman->add_field($table, $field);
        }
        // Define field timecreated to be added to lr_job_postings.
        $table = new xmldb_table('lr_job_postings');
        $field = new xmldb_field('timecreated', XMLDB_TYPE_INTEGER, '10', null, XMLDB_NOTNULL, null, '0', 'usermodified');

        // Conditionally launch add field timecreated.
        if (!$dbman->field_exists($table, $field)) {
            $dbman->add_field($table, $field);
        }

        // Define field timemodified to be added to lr_job_postings.
        $table = new xmldb_table('lr_job_postings');
        $field = new xmldb_field('timemodified', XMLDB_TYPE_INTEGER, '10', null, XMLDB_NOTNULL, null, '0', 'timecreated');

        // Conditionally launch add field timemodified.
        if (!$dbman->field_exists($table, $field)) {
            $dbman->add_field($table, $field);
        }


        upgrade_plugin_savepoint(true, 2020062313, 'local', 'lecrec');
    }

    if ($oldversion < 2020062314) {

        // Define field interview_date to be added to lr_application.
        $table = new xmldb_table('lr_application');
        $field = new xmldb_field('interview_date', XMLDB_TYPE_INTEGER, '10', null, null, null, null, 'timemodified');

        // Conditionally launch add field interview_date.
        if (!$dbman->field_exists($table, $field)) {
            $dbman->add_field($table, $field);
        }

        // Define field interview_street to be added to lr_application.
        $table = new xmldb_table('lr_application');
        $field = new xmldb_field(
            'interview_street',
            XMLDB_TYPE_CHAR,
            '255',
            null,
            null,
            null,
            null,
            'interview_date'
        );

        // Conditionally launch add field interview_street.
        if (!$dbman->field_exists($table, $field)) {
            $dbman->add_field($table, $field);
        }

        // Define field interview_city to be added to lr_application.
        $table = new xmldb_table('lr_application');
        $field = new xmldb_field('interview_city', XMLDB_TYPE_CHAR, '45', null, null, null, null, 'interview_street');

        // Conditionally launch add field interview_city.
        if (!$dbman->field_exists($table, $field)) {
            $dbman->add_field($table, $field);
        }
        // Define field interview_city_zip to be added to lr_application.
        $table = new xmldb_table('lr_application');
        $field = new xmldb_field('interview_city_zip', XMLDB_TYPE_INTEGER, '10', null, null, null, null, 'interview_city');

        // Conditionally launch add field interview_city_zip.
        if (!$dbman->field_exists($table, $field)) {
            $dbman->add_field($table, $field);
        }


        // Lecrec savepoint reached.
        upgrade_plugin_savepoint(true, 2020062314, 'local', 'lecrec');
    }

  



    if ($oldversion < 2020062315) {

        // Define field closed to be added to lr_job_postings.
        $table = new xmldb_table('lr_job_postings');
        $field = new xmldb_field('closed', XMLDB_TYPE_INTEGER, '1', null, null, null, '0', 'timemodified');

        // Conditionally launch add field closed.
        if (!$dbman->field_exists($table, $field)) {
            $dbman->add_field($table, $field);
        }

        // Lecrec savepoint reached.
        upgrade_plugin_savepoint(true, 2020062315, 'local', 'lecrec');
    }

  if ($oldversion < 2020062316) {

        // Changing type of field private_tele on table lr_application to int.
        $table = new xmldb_table('lr_application');
        $field = new xmldb_field('private_tele', XMLDB_TYPE_INTEGER, '15', null, null, null, null, 'private_add_city');

        // Launch change of type for field private_tele.
        $dbman->change_field_type($table, $field);

        // Lecrec savepoint reached.
       

        // Changing type of field private_mobile on table lr_application to int.
        $table = new xmldb_table('lr_application');
        $field = new xmldb_field('private_mobile', XMLDB_TYPE_INTEGER, '15', null, null, null, null, 'private_tele');

        // Launch change of type for field private_mobile.
        $dbman->change_field_type($table, $field);
        // Changing type of field private_fax on table lr_application to int.
        $table = new xmldb_table('lr_application');
        $field = new xmldb_field('private_fax', XMLDB_TYPE_INTEGER, '15', null, null, null, null, 'private_email');

        // Launch change of type for field private_fax.
        $dbman->change_field_type($table, $field);
        // Changing type of field company_tele on table lr_application to int.
        $table = new xmldb_table('lr_application');
        $field = new xmldb_field('company_tele', XMLDB_TYPE_INTEGER, '15', null, null, null, null, 'company_add_city');

        // Launch change of type for field company_tele.
        $dbman->change_field_type($table, $field);
        // Changing type of field company_fax on table lr_application to int.
        $table = new xmldb_table('lr_application');
        $field = new xmldb_field('company_fax', XMLDB_TYPE_INTEGER, '15', null, null, null, null, 'company_tele');

        // Launch change of type for field company_fax.
        $dbman->change_field_type($table, $field);
        // Changing type of field private_phonenumber on table lr_lecturer to int.
        $table = new xmldb_table('lr_lecturer');
        $field = new xmldb_field('private_phonenumber', XMLDB_TYPE_INTEGER, '15', null, null, null, null, 'private_state');

        // Launch change of type for field private_phonenumber.
        $dbman->change_field_type($table, $field);
        // Changing type of field private_cellphone_number on table lr_lecturer to int.
        $table = new xmldb_table('lr_lecturer');
        $field = new xmldb_field('private_cellphone_number', XMLDB_TYPE_INTEGER, '15', null, null, null, null, 'private_phonenumber');

        // Launch change of type for field private_cellphone_number.
        $dbman->change_field_type($table, $field);
        // Changing type of field business_phonenumber on table lr_lecturer to int.
        $table = new xmldb_table('lr_lecturer');
        $field = new xmldb_field('business_phonenumber', XMLDB_TYPE_INTEGER, '15', null, null, null, null, 'company');

        // Launch change of type for field business_phonenumber.
        $dbman->change_field_type($table, $field);
 upgrade_plugin_savepoint(true, 2020062316, 'local', 'lecrec');
    }

    return true;
}
