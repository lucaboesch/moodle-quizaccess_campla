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

namespace quizaccess_campla;

/**
 * PHPUnit tests for settings_provider.
 *
 * @package    quizaccess_campla
 * @author     Luca Bösch <luca.boesch@bfh.ch>
 * @copyright  2024 BFH Bern University of Applied Sciences
 * @license    http://www.gnu.org/copyleft/gpl.html GNU GPL v3 or later
 */
final class settings_provider_test extends \advanced_testcase {

    /**
     * Mocked quiz form instance.
     * @var \mod_quiz_mod_form
     */
    protected $mockedquizform;

    /**
     * Test moodle form.
     * @var \MoodleQuickForm
     */
    protected $mockedform;

    /**
     * Context for testing.
     * @var \context
     */
    protected $context;

    /**
     * Test user.
     * @var \stdClass
     */
    protected $user;

    /**
     * Test role ID.
     * @var int
     */
    protected $roleid;

    /**
     * Test quiz.
     * @var \stdClass
     */
    protected $quiz;

    /**
     * Test quiz.
     * @var \stdClass
     */
    protected $course;

    /**
     * Helper method to set up form mocks.
     */
    protected function set_up_form_mocks() {
        if (empty($this->context)) {
            $this->context = \context_module::instance($this->quiz->cmid);
        }

        $this->mockedquizform = $this->createMock('mod_quiz_mod_form');
        $this->mockedquizform->method('get_context')->willReturn($this->context);
        $this->mockedquizform->method('get_instance')->willReturn($this->quiz->id);
        $this->mockedform = new \MoodleQuickForm('test', 'post', '');
        $this->mockedform->addElement('static', 'security');
    }

    /**
     * Helper method to set up user and role for testing.
     */
    protected function set_up_user_and_role() {
        $this->user = $this->getDataGenerator()->create_user();

        $this->setUser($this->user);
        $this->roleid = $this->getDataGenerator()->create_role();

        $this->getDataGenerator()->role_assign($this->roleid, $this->user->id, $this->context->id);
    }

    /**
     * Test if a user can configure CAMPLA.
     *
     * @covers \quizaccess_campla\settings_provider::can_configure_campla
     * @return void
     * @throws \coding_exception
     */
    public function test_can_configure_campla(): void {
        $this->resetAfterTest();

        $this->course = $this->getDataGenerator()->create_course();
        $this->quiz = $this->getDataGenerator()->create_module('quiz', ['course' => $this->course->id]);
        $this->context = \context_module::instance($this->quiz->cmid);
        $this->setAdminUser();

        $this->assertTrue(settings_provider::can_configure_campla($this->context));

        $this->set_up_user_and_role();

        $this->assertFalse(settings_provider::can_configure_campla($this->context));

        assign_capability('quizaccess/campla:canusecampla', CAP_ALLOW, $this->roleid, $this->context->id);
        $this->assertTrue(settings_provider::can_configure_campla($this->context));
    }

    /**
     * Test the return of the quiz name.
     *
     * @covers \quizaccess_campla\settings_provider::get_campla_quizname
     * @return void
     */
    public function test_get_campla_quizname(): void {
        $this->resetAfterTest();

        $this->course = $this->getDataGenerator()->create_course();
        $this->quiz = $this->getDataGenerator()->create_module('quiz', ['course' => $this->course->id]);
        $this->context = \context_module::instance($this->quiz->cmid);

        $this->set_up_user_and_role();

        $this->assertEquals(format_string($this->quiz->name, false, ['context' => $this->context]),
            settings_provider::get_campla_quizname($this->quiz->cmid));
    }

    /**
     * Test the return of the quiz URL.
     *
     * @covers \quizaccess_campla\settings_provider::get_campla_quizurl
     * @return void
     */
    public function test_get_campla_quizurl(): void {
        $this->resetAfterTest();

        $this->course = $this->getDataGenerator()->create_course();
        $this->quiz = $this->getDataGenerator()->create_module('quiz', ['course' => $this->course->id]);
        $this->context = \context_module::instance($this->quiz->cmid);

        $this->set_up_user_and_role();

        $this->assertEquals(new \moodle_url('/mod/quiz/view.php', ['cmid' => $this->quiz->cmid]),
            settings_provider::get_campla_quizurl($this->quiz->cmid));
    }

    /**
     * Test the return of the quiz start time.
     *
     * @covers \quizaccess_campla\settings_provider::get_campla_timeopen
     * @return void
     */
    public function test_get_campla_timeopen(): void {
        $this->resetAfterTest();

        $this->course = $this->getDataGenerator()->create_course();
        $this->quiz = $this->getDataGenerator()->create_module('quiz', ['course' => $this->course->id]);
        $this->context = \context_module::instance($this->quiz->cmid);

        $this->set_up_user_and_role();

        $this->assertEquals($this->quiz->timeopen, settings_provider::get_campla_timeopen($this->quiz->cmid));
    }

    /**
     * Test the return of the quiz end time.
     *
     * @covers \quizaccess_campla\settings_provider::get_campla_timeclose
     * @return void
     */
    public function test_get_campla_timeclose(): void {
        $this->resetAfterTest();

        $this->course = $this->getDataGenerator()->create_course();
        $this->quiz = $this->getDataGenerator()->create_module('quiz', ['course' => $this->course->id]);
        $this->context = \context_module::instance($this->quiz->cmid);

        $this->set_up_user_and_role();

        $this->assertEquals($this->quiz->timeclose, settings_provider::get_campla_timeclose($this->quiz->cmid));
    }

    /**
     * Test the return of the course name.
     *
     * @covers \quizaccess_campla\settings_provider::get_campla_coursename
     * @return void
     */
    public function test_get_campla_coursename(): void {
        $this->resetAfterTest();

        $this->course = $this->getDataGenerator()->create_course();
        $this->quiz = $this->getDataGenerator()->create_module('quiz', ['course' => $this->course->id]);
        $this->context = \context_module::instance($this->quiz->cmid);

        $this->set_up_user_and_role();

        $this->assertEquals(format_string($this->course->fullname, false, ['context' => $this->context]),
            settings_provider::get_campla_coursename($this->quiz->cmid));
    }


    /**
     * Test the return of the course students.
     *
     * @covers \quizaccess_campla\settings_provider::get_campla_coursestudents
     * @return void
     */
    public function test_get_campla_coursestudents(): void {
        global $DB;
        $this->resetAfterTest();

        $this->course = $this->getDataGenerator()->create_course();
        $this->quiz = $this->getDataGenerator()->create_module('quiz', ['course' => $this->course->id]);
        $this->context = \context_module::instance($this->quiz->cmid);

        $this->set_up_user_and_role();

        $coursecontext = \context_course::instance($this->course->id);

        $this->assertEquals(get_enrolled_users($coursecontext, 'mod/quiz:attempt', 0, 'u.*', null, 0, 0, true),
            settings_provider::get_campla_coursestudents($this->quiz->cmid));
    }
}
