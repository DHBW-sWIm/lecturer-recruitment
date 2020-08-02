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
 * Code to be executed after the plugin's database scheme has been installed is defined here.
 *
 * @package     local_lecrec
 * @category    upgrade
 * @copyright   nico_andres@gmx.de
 * @license     http://www.gnu.org/copyleft/gpl.html GNU GPL v3 or later
 */

defined('MOODLE_INTERNAL') || die();

/**
 * Custom code to be run on installing the plugin.
 */
function xmldb_local_lecrec_install() {
    global $DB;

    $dbman = $DB->get_manager();

    // Define table dg_company to be created.
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


    // Define table sr_study_programs to be created.
    $table = new xmldb_table('sr_study_programs');

    // Adding fields to table sr_study_programs.
    $table->add_field('id', XMLDB_TYPE_INTEGER, '18', null, XMLDB_NOTNULL, XMLDB_SEQUENCE, null);
    $table->add_field('study_program_name', XMLDB_TYPE_CHAR, '255', null, null, null, null);
    $table->add_field('description', XMLDB_TYPE_CHAR, '455', null, null, null, null);
    $table->add_field('valid_from', XMLDB_TYPE_DATETIME, null, null, null, null, null);
    $table->add_field('valid_to', XMLDB_TYPE_DATETIME, null, null, null, null, null);
    $table->add_field('old', XMLDB_TYPE_INTEGER, '1', null, null, null, '0');

    // Adding keys to table sr_study_programs.
    $table->add_key('primary', XMLDB_KEY_PRIMARY, ['id']);

    // Conditionally launch create table for sr_study_programs.
    if (!$dbman->table_exists($table)) {
        $dbman->create_table($table);
    }

    // Define table sr_study_fields to be created.
    $table = new xmldb_table('sr_study_fields');

    // Adding fields to table sr_study_fields.
    $table->add_field('id', XMLDB_TYPE_INTEGER, '18', null, XMLDB_NOTNULL, XMLDB_SEQUENCE, null);
    $table->add_field('study_field_name', XMLDB_TYPE_CHAR, '255', null, null, null, null);
    $table->add_field('description', XMLDB_TYPE_CHAR, '455', null, null, null, null);
    $table->add_field('old', XMLDB_TYPE_INTEGER, '1', null, null, null, '0');
    $table->add_field('sr_study_programs_id', XMLDB_TYPE_INTEGER, '18', null, null, null, null);

    // Adding keys to table sr_study_fields.
    $table->add_key('primary', XMLDB_KEY_PRIMARY, ['id']);
    $table->add_key('sr_study_programs_id', XMLDB_KEY_FOREIGN, ['sr_study_programs_id'], 'sr_study_programs', ['id']);

    // Conditionally launch create table for sr_study_fields.
    if (!$dbman->table_exists($table)) {
        $dbman->create_table($table);
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
    $table->add_key('sr_employees_id', XMLDB_KEY_FOREIGN, ['sr_employees_id'], 'user', ['id']);
    $table->add_key('sr_study_fields_id', XMLDB_KEY_FOREIGN, ['sr_study_fields_id'], 'sr_study_fields', ['id']);
    $table->add_key('usermodified', XMLDB_KEY_FOREIGN, ['usermodified'], 'user', ['id']);

    // Conditionally launch create table for sr_active_study_course.
    if (!$dbman->table_exists($table)) {
        $dbman->create_table($table);
    }

    // Define key sr_study_field_id (foreign) to be added to lr_subj_field_asgnmt.
    $table = new xmldb_table('lr_subj_field_asgnmt');
    $key = new xmldb_key('sr_study_field_id', XMLDB_KEY_FOREIGN, ['sr_study_field_id'], 'sr_study_fields', ['id']);

    // Launch add key sr_study_field_id.
    $dbman->add_key($table, $key);

    // Define key dg_company_id (foreign) to be added to lr_lecturer.
    $table = new xmldb_table('lr_lecturer');
    $key = new xmldb_key('dg_company_id', XMLDB_KEY_FOREIGN, ['dg_company_id'], 'dg_company', ['id']);

    // Launch add key dg_company_id.
    $dbman->add_key($table, $key);

    return true;
}
