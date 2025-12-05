@javascript @mod_quiz @quizaccess @quizaccess_campla
Feature: Quiz build including CAMPLA button in quiz edit form
  In order to configure a CAMPLA exam
  As a manager
  I need to be able to create a quiz when the CAMPLA plugin is active

  Background:
    Given the following "courses" exist:
      | fullname | shortname |
      | Course 1 | C1        |
    And the following "users" exist:
      | username | firstname | lastname | email                |
      | teacher1 | Teacher   | 1        | teacher1@example.com |
    And the following "course enrolments" exist:
      | user     | course | role           |
      | teacher1 | C1     | editingteacher |
    And the following config values are set as admin:
      | basisurl      | https://campla.moodle.org            | quizaccess_campla |
      | secret        | 1UmPYTe3Th1_95                       | quizaccess_campla |
      | appid         | 6a6f9d8f-dc8c-4fb2-ad60-ecf7acf7ef7f | quizaccess_campla |
      | securitylevel | Lernstick                            | quizaccess_campla |
    And the following "permission overrides" exist:
      | capability                     | permission | role           | contextlevel | reference |
      | quizaccess/campla:canusecampla | Allow      | editingteacher | Course       | C1        |

  Scenario: Create a quiz when the CAMPLA plugin is installed for Moodle ≤ 5.0
    Given the site is running Moodle version 5.0 or lower
    And I log in as "teacher1"
    When I am on "Course 1" course homepage with editing mode on
    And I open the activity chooser
    And I click on "Add a new Quiz" "link" in the "Add an activity or resource" "dialogue"
    Then I should see "New Quiz"
    And I expand all fieldsets
    And I should see "The settings in this section will be only visible once the quiz is created."

  Scenario: Create a quiz when the CAMPLA plugin is installed for Moodle ≥ 5.1
    Given the site is running Moodle version 5.1 or higher
    And I log in as "teacher1"
    When I am on "Course 1" course homepage with editing mode on
    And I open the activity chooser
    And I should see "Quiz" in the "Add an activity or resource" "dialogue"
    And I click on "Add a new Quiz" "link" in the "Add an activity or resource" "dialogue"
    And I click on "Add selected activity" "button" in the "Add an activity or resource" "dialogue"
    Then I should see "New Quiz"
    And I expand all fieldsets
    And I should see "The settings in this section will be only visible once the quiz is created."
