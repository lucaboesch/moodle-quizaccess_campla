@javascript @mod_quiz @quizaccess @quizaccess_campla
Feature: Configuration of the CAMPLA quiz access rule
  In order to have my managers easily create CAMPLA exams
  As an admin
  I need to manage the CAMPLA credentials and URL

  @javascript
  Scenario: Configure the CAMPLA quiz access rule URL and secret
    Given I log in as "admin"
    And I navigate to "Plugins > Activity modules > Quiz > CAMPLA" in site administration
    And I set the field "CAMPLA basis URL" to "https://campla.moodle.org"
    And I set the field "CAMPLA secret" to "1UmPYTe3Th1_95"
    And I press "Save changes"
    When I navigate to "Plugins > Activity modules > Quiz > CAMPLA" in site administration
    Then the field "CAMPLA basis URL" matches value "https://campla.moodle.org"
    And the field "CAMPLA secret" matches value "1UmPYTe3Th1_95"
    And I log out
