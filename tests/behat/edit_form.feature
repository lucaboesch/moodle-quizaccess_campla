@javascript @mod_quiz @quizaccess @quizaccess_campla
Feature: CAMPLA button in quiz edit form
  In order to configure a CAMPLA exam
  As a manager
  I need to be able to push the generate CAMPLA configuration button in the quiz edit form

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
      | basisurl | https://campla.moodle.org            | quizaccess_campla |
      | secret   | 1UmPYTe3Th1_95                       | quizaccess_campla |
      | appid    | 6a6f9d8f-dc8c-4fb2-ad60-ecf7acf7ef7f | quizaccess_campla |

  Scenario: Quiz setting "CAMPLA" features a "Generate CAMPLA configuration" button.
    Given the following "activities" exist:
      | activity | course | section | name   |
      | quiz     | C1     | 1       | Quiz 1 |
    When I am on the "Quiz 1" "quiz activity editing" page logged in as admin
    And I expand all fieldsets
    Then I should see "Generate CAMPLA configuration"

  Scenario: Teachers without capabilities should not see the "Generate CAMPLA configuration" button.
    Given the following "activities" exist:
      | activity | course | section | name   |
      | quiz     | C1     | 1       | Quiz 1 |
    When I am on the "Quiz 1" "quiz activity editing" page logged in as teacher1
    And I expand all fieldsets
    Then I should not see "Generate CAMPLA configuration"

  Scenario Outline: Pushing the "Generate CAMPLA configuration" button.
    Given the following "activities" exist:
      | activity | course | section | name   | timeopen   | timeclose   |
      | quiz     | C1     | 1       | Quiz 1 | <timeopen> | <timeclose> |
    And the following "permission overrides" exist:
      | capability                     | permission | role           | contextlevel | reference |
      | quizaccess/campla:canusecampla | Allow      | editingteacher | Course       | C1        |
    When I am on the "Quiz 1" "quiz activity editing" page logged in as teacher1
    And I expand all fieldsets
    And I click on "Generate CAMPLA configuration" "button"
    Then I should see "Generate CAMPLA configuration"
    And I should see "Send to CAMPLA"
    And the field "Quiz name" matches value "Quiz 1"
    And the field "Course name" matches value "Course 1"
    And the field "Quiz owner" matches value "teacher1@example.com"
    And the field "Quiz opens" matches value "<timeopen>%a, %d %b %Y, %I:%M %p##"
    And the field "Quiz closes" matches value "<timeclose>%a, %d %b %Y, %I:%M %p##"

    Examples:
      | timeopen              | timeclose             |
      | ##31 Dec 2024 12:00## | ##31 Dec 2040 12:00## |
