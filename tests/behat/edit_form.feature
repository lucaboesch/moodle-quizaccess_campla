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

    When I am on the "Quiz 1" "quiz activity editing" page logged in as admin
    And I expand all fieldsets
    And I click on "Generate CAMPLA configuration" "button"
    Then I should see "Generate CAMPLA configuration"
    And the field "Quiz name" matches value "Quiz 1"
    And the field "Course name" matches value "Course 1"
    And the field "Quiz opens" matches value "<timeopen>%a, %d %b %Y, %I:%M %p##"
    And the field "Quiz closes" matches value "<timeclose>%a, %d %b %Y, %I:%M %p##"

    Examples:
      | timeopen      | timeclose    |
      | ##yesterday## | ##tomorrow## |
