@javascript @mod_quiz @quizaccess @quizaccess_campla
Feature: CAMPLA unconfigured warning in quiz edit form
  In order to configure a CAMPLA exam
  As a manager
  I need to be warned thet the configuration is not fullfilled the quiz edit form

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

  Scenario: Quiz setting "CAMPLA" features a "CAMPLA endpoint not configured" warning.
    Given the following "activities" exist:
      | activity | course | section | name   |
      | quiz     | C1     | 1       | Quiz 1 |
    When I am on the "Quiz 1" "quiz activity editing" page logged in as admin
    And I expand all fieldsets
    Then I should see "The CAMPLA endpoint is not configured."
    But I should not see "Generate CAMPLA configuration"
