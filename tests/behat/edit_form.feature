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
      | username |
      | teacher1 |
    And the following "course enrolments" exist:
      | user     | course | role           |
      | teacher1 | C1     | editingteacher |
    And the following "activities" exist:
      | activity | course | section | name   |
      | quiz     | C1     | 1       | Quiz 1 |

  Scenario: Quiz setting "CAMPLA" features a "Generate CAMPLA configuration" button.
    When I am on the "Quiz 1" "quiz activity editing" page logged in as admin
    And I expand all fieldsets
    Then I should see "Generate CAMPLA configuration"

  Scenario: Teachers without capabilities should not see the "Generate CAMPLA configuration" button.
    When I am on the "Quiz 1" "quiz activity editing" page logged in as teacher1
    And I expand all fieldsets
    Then I should not see "Generate CAMPLA configuration"
